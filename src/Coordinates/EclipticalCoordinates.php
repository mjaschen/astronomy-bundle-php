<?php

namespace Andrmoel\AstronomyBundle\Coordinates;

use Andrmoel\AstronomyBundle\TimeOfInterest;

class EclipticalCoordinates extends Coordinates
{
    private $longitude = 0.0;
    private $latitude = 0.0;


    public function __construct(float $latitude, float $longitude, TimeOfInterest $toi)
    {
        parent::__construct($toi);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }


    public function getLatitude(): float
    {
        return $this->latitude;
    }


    public function getLongitude(): float
    {
        return $this->longitude;
    }


    public function getEquatorialCoordinates(): EquatorialCoordinates
    {
        $eps = $this->earth->getTrueObliquityOfEcliptic();
        $eps = deg2rad($eps);
        $lat = deg2rad($this->latitude);
        $lon = deg2rad($this->longitude);

        $ra = atan2(sin($lon) * cos($eps) - (sin($lat) / cos($lat)) * sin($eps), cos($lon));
        $ra = rad2deg($ra);
        $d = asin(sin($lat) * cos($eps) + cos($lat) * sin($eps) * sin($lon));
        $d = rad2deg($d);

        return new EquatorialCoordinates($ra, $d, $this->toi);
    }
}
