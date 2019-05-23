<?php

namespace Andrmoel\AstronomyBundle\AstronomicalObjects\Planets;

use Andrmoel\AstronomyBundle\AstronomicalObjects\AstronomicalObject;
use Andrmoel\AstronomyBundle\Calculations\VSOP87\VSOP87Interface;
use Andrmoel\AstronomyBundle\Calculations\VSOP87Calc;
use Andrmoel\AstronomyBundle\Coordinates\GeocentricEclipticalRectangularCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\GeocentricEclipticalSphericalCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HeliocentricEclipticalRectangularCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HeliocentricEclipticalSphericalCoordinates;
use Andrmoel\AstronomyBundle\Coordinates\HeliocentricEquatorialRectangularCoordinates;
use Andrmoel\AstronomyBundle\Utils\AngleUtil;

abstract class Planet extends AstronomicalObject implements PlanetInterface
{
    /** @var VSOP87Interface */
    protected $VSOP87_SPHERICAL;

    /** @var VSOP87Interface */
    protected $VSOP87_RECTANGULAR;

    public function getHeliocentricEclipticalRectangularCoordinates(): HeliocentricEclipticalRectangularCoordinates
    {
        $t = $this->toi->getJulianMillenniaFromJ2000();
        $coefficients = VSOP87Calc::solve($this->VSOP87_RECTANGULAR, $t);

        $x = $coefficients[0];
        $y = $coefficients[1];
        $z = $coefficients[2];

        return new HeliocentricEclipticalRectangularCoordinates($x, $y, $z);
    }

    public function getHeliocentricEclipticalSphericalCoordinates(): HeliocentricEclipticalSphericalCoordinates
    {
        $t = $this->toi->getJulianMillenniaFromJ2000();
        $coefficients = VSOP87Calc::solve($this->VSOP87_SPHERICAL, $t);

        $L = $coefficients[0];
        $B = $coefficients[1];
        $R = $coefficients[2];

        $L = AngleUtil::normalizeAngle(rad2deg($L));
        $B = rad2deg($B);

        return new HeliocentricEclipticalSphericalCoordinates($B, $L, $R);
    }

    // TODO
    public function getHeliocentricEquatorialRectangularCoordinates(): HeliocentricEquatorialRectangularCoordinates
    {
        return new HeliocentricEquatorialRectangularCoordinates(0, 0, 0);
    }

    public function getGeocentricEclipticalRectangularCoordinates(): GeocentricEclipticalRectangularCoordinates
    {
        return $this
            ->getHeliocentricEclipticalRectangularCoordinates()
            ->getGeocentricEclipticalRectangularCoordinates($this->T);
    }

    public function test()
    {
        $geoEclRecCoord = $this->getGeocentricEclipticalRectangularCoordinates();

        $x = $geoEclRecCoord->getX();
        $y = $geoEclRecCoord->getY();
        $z = $geoEclRecCoord->getZ();

        // Meeus 33.2
        $lat = atan($z / (sqrt(pow($x, 2) + pow($y, 2))));
        $lat = rad2deg($lat);
        $lon = atan2($y, $z);
        $lon = AngleUtil::normalizeAngle($lon);

        var_dump($lat, $lon);
        die();

        var_dump($x, $y, $z);
        die();
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
    public function getApparentHeliocentricEclipticalRectangularCoordinates(
    ): HeliocentricEclipticalRectangularCoordinates
    {
        return $this->getApparentHeliocentricEclipticalSphericalCoordinates()
            ->getHeliocentricEclipticalRectangularCoordinates();
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
