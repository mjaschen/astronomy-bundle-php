<?php

namespace Andrmoel\AstronomyBundle\AstronomicalObjects\Planets;

use Andrmoel\AstronomyBundle\AstronomicalObjects\AstronomicalObject;
use Andrmoel\AstronomyBundle\AstronomicalObjects\PlanetInterface;
use Andrmoel\AstronomyBundle\Coordinates\GeocentricEclipticalSphericalCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HeliocentricEclipticalRectangularCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HeliocentricEclipticalSphericalCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HeliocentricEquatorialRectangularCoordinates;
use Andrmoel\AstronomyBundle\Tests\Calculations\GeocentricEclipticalSphericalCoordinatesTest;
use Andrmoel\AstronomyBundle\TimeOfInterest;
use Andrmoel\AstronomyBundle\Utils\AngleUtil;

abstract class Planet extends AstronomicalObject implements PlanetInterface
{
    protected const VSOP87_FILE_PATH = __DIR__ . '/../../Resources/vsop87/json/';

    protected $vsop87 = [];

    abstract function loadVSOP87Data(): array;

    public function __construct(TimeOfInterest $toi = null)
    {
        parent::__construct($toi);
        $this->vsop87 = $this->loadVSOP87Data();
    }

    // TODO
    public function getHeliocentricEclipticalRectangularCoordinates(): HeliocentricEclipticalRectangularCoordinates
    {
        return new HeliocentricEclipticalRectangularCoordinates(0, 0, 0);
    }

    public function getHeliocentricEclipticalSphericalCoordinates(): HeliocentricEclipticalSphericalCoordinates
    {
        $t = $this->toi->getJulianMillenniaFromJ2000();

        $L = $this->resolveTerms($this->vsop87['L'], $t);
        $L = rad2deg($L);
        $L = AngleUtil::normalizeAngle($L);

        $B = $this->resolveTerms($this->vsop87['B'], $t);
        $B = rad2deg($B);

        $R = $this->resolveTerms($this->vsop87['R'], $t);

        return new HeliocentricEclipticalSphericalCoordinates($B, $L, $R);
    }

    // TODO equatorial oder ecliptical?
    public function getHeliocentricEquatorialRectangularCoordinates(): HeliocentricEquatorialRectangularCoordinates
    {
        $helEclSphCoord = $this->getHeliocentricEclipticalSphericalCoordinates();

        $B = $helEclSphCoord->getLatitude();
        $L = $helEclSphCoord->getLongitude();
        $R = $helEclSphCoord->getRadiusVector();

        $BRad = deg2rad($B);
        $LRad = deg2rad($L);

        // Meeus 33.1
        $X = $R * cos($BRad) * cos($LRad);
        $Y = $R * cos($BRad) * sin($LRad);
        $Z = $R * sin($BRad);

        return new HeliocentricEquatorialRectangularCoordinates($X, $Y, $Z);
    }

    public function test()
    {
        $helEclSphCoord = $this->getHeliocentricEclipticalSphericalCoordinates();

        $B = $helEclSphCoord->getLatitude();
        $L = $helEclSphCoord->getLongitude();
        $R = $helEclSphCoord->getRadiusVector();

        $BRad = deg2rad($B);
        $LRad = deg2rad($L);

        // Meeus 33.1
        $X = $R * cos($BRad) * cos($LRad);
        $Y = $R * cos($BRad) * sin($LRad);
        $Z = $R * sin($BRad);

        // Earth
        $earth = new Earth($this->toi);
        $helEclSphCoord = $earth->getHeliocentricEclipticalSphericalCoordinates();

        $B0 = $helEclSphCoord->getLatitude();
        $L0 = $helEclSphCoord->getLongitude();
        $R0 = $helEclSphCoord->getRadiusVector();

        $B0Rad = deg2rad($B0);
        $L0Rad = deg2rad($L0);

        $X0 = $R0 * cos($B0Rad) * cos($L0Rad);
        $Y0 = $R0 * cos($B0Rad) * sin($L0Rad);
        $Z0 = $R0 * sin($B0Rad);

        $x = $X - $X0;
        $y = $Y - $Y0;
        $z = $Z - $Z0;

        // Meeus 33.2
        $lat = atan($z / (sqrt(pow($x, 2) + pow($y, 2))));
        $lat = rad2deg($lat);
        $lon = atan2($y, $z);
        $lon = AngleUtil::normalizeAngle($lon);

        var_dump($lat,$lon);die();

        var_dump($x, $y, $z);die();
    }

    public function getGeocentricEclipticalSphericalCoordinates(): GeocentricEclipticalSphericalCoordinates
    {
        $earth = new Earth($this->toi);
//        $geo = $earth->get

        // Meeus 33.2
        $lat = atan2($y, $x);

        new GeocentricEclipticalSphericalCoordinates($lat, $lon, $R);
    }

    /**
     * The apparent position is light-time corrected
     * @return HeliocentricEclipticalRectangularCoordinates
     */
    public function getApparentHeliocentricEclipticalRectangularCoordinates(): HeliocentricEclipticalRectangularCoordinates
    {
        return $this->getApparentHeliocentricEclipticalSphericalCoordinates()
            ->getHeliocentricEclipticalRectangularCoordinates();
    }

    private function resolveTerms(array $terms, float $t): float
    {
        // Meeus 32.2
        $sum = 0.0;
        foreach ($terms as $key => $arguments) {
            $value = $this->sumUpArguments($arguments, $t);

            $sum += $value * pow($t, $key);
        }

        return $sum;
    }

    private function sumUpArguments(array $arguments, float $t): float
    {
        // Meeus 21.1
        $sum = 0.0;
        foreach ($arguments as $argument) {
            $sum += $argument[0] * cos($argument[1] + $argument[2] * $t);
        }

        return $sum;
    }


//    /**
//     * The apparent position is light-time corrected
//     * @return HeliocentricEclipticalSphericalCoordinates
//     */
//    public function getApparentHeliocentricEclipticalSphericalCoordinates(): HeliocentricEclipticalSphericalCoordinates
//    {
//        // First we need to calculate the distance between the planet and the earth.
//        // With the formula Meeus 33.3 we can calculated the light-time corrected position of the planet.
//        $t = $this->toi->getJulianMillenniaFromJ2000();
//
//        $geoEclSphCoordinates = $this->getHeliocentricEclipticalSphericalCoordinates($t)
//            ->getGeocentricEclipticalSphericalCoordinates();
//
//        $distance = $geoEclSphCoordinates->getRadiusVector();
//        $toiCorrected = $this->toi->getTimeOfInterestLightTimeCorrected($distance);
//
//        // With the corrected time, we can calculate the true helopcentric position.
//        $t = $toiCorrected->getJulianMillenniaFromJ2000();
//
//        return $this->getHeliocentricEclipticalSphericalCoordinates($t);
//    }
}
