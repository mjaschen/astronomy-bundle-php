<?php

namespace Andrmoel\AstronomyBundle\Calculations;

interface EarthCalcInterface
{
    // http://adsabs.harvard.edu/full/1982CeMec..27...79S
    const ARGUMENTS_NUTATION = [
        [0, 0, 0, 0, 1, -171996, -174.2, 92025, 8.9],
        [0, 0, 2, -2, 2, -13187, -1.6, 5736, -3.1],
        [0, 0, 2, 0, 2, -2274, -0.2, 977, -0.5],
        [0, 0, 0, 0, 2, 2062, 0.2, -895, 0.5],
        [0, 1, 0, 0, 0, 1426, -3.4, 54, -0.1],
        [1, 0, 0, 0, 0, 712, 0.1, -7, 0.0],
        [0, 1, 2, -2, 2, -517, 1.2, 224, -0.6],
        [0, 0, 2, 0, 1, -386, -0.4, 200, 0.0],
        [1, 0, 2, 0, 2, -301, 0.0, 129, -0.1],
        [0, -1, 2, -2, 2, 217, -0.5, -95, 0.3],
        [1, 0, 0, -2, 0, -158, 0.0, -1, 0.0],
        [0, 0, 2, -2, 1, 129, 0.1, -70, 0.0],
        [-1, 0, 2, 0, 2, 123, 0.0, -53, 0.0],
        [0, 0, 0, 2, 0, 63, 0.0, -2, 0.0],
        [1, 0, 0, 0, 1, 63, 0.1, -33, 0.0],
        [-1, 0, 2, 2, 2, -59, 0.0, 26, 0.0],
        [-1, 0, 0, 0, 1, -58, -0.1, 32, 0.0],
        [1, 0, 2, 0, 1, -51, 0.0, 27, 0.0],
        [2, 0, 0, -2, 0, 48, 0.0, 1, 0.0],
        [-2, 0, 2, 0, 1, 46, 0.0, -24, 0.0],
        [0, 0, 2, 2, 2, -38, 0.0, 16, 0.0],
        [2, 0, 2, 0, 2, -31, 0.0, 13, 0.0],
        [2, 0, 0, 0, 0, 29, 0.0, -1, 0.0],
        [1, 0, 2, -2, 2, 29, 0.0, -12, 0.0],
        [0, 0, 2, 0, 0, 26, 0.0, -1, 0.0],
        [0, 0, 2, -2, 0, -22, 0.0, 0, 0.0],
        [-1, 0, 2, 0, 1, 21, 0.0, -10, 0.0],
        [0, 2, 0, 0, 0, 17, -0.1, 0, 0.0],
        [0, 2, 2, -2, 2, -16, 0.1, 7, 0.0],
        [-1, 0, 0, 2, 1, 16, 0.0, -8, 0.0],
        [0, 1, 0, 0, 1, -15, 0.0, 9, 0.0],
        [1, 0, 0, -2, 1, -13, 0.0, 7, 0.0],
        [0, -1, 0, 0, 1, -12, 0.0, 6, 0.0],
        [2, 0, -2, 0, 0, 11, 0.0, 0, 0.0],
        [-1, 0, 2, 2, 1, -10, 0.0, 5, 0.0],
        [1, 0, 2, 2, 2, -8, 0.0, 3, 0.0],
        [1, 1, 0, -2, 0, -7, 0.0, 0, 0.0],
        [0, 1, 2, 0, 2, 7, 0.0, -3, 0.0],
        [0, -1, 2, 0, 2, -7, 0.0, 3, 0.0],
        [0, 0, 2, 2, 1, -7, 0.0, 3, 0.0],
        [-2, 0, 0, 2, 1, -6, 0.0, 3, 0.0],
        [1, 0, 0, 2, 0, 6, 0.0, 0, 0.0],
        [2, 0, 2, -2, 2, 6, 0.0, -3, 0.0],
        [0, 0, 0, 2, 1, -6, 0.0, 3, 0.0],
        [1, 0, 2, -2, 1, 6, 0.0, -3, 0.0],
        [0, -1, 2, -2, 1, -5, 0.0, 3, 0.0],
        [0, 0, 0, -2, 1, -5, 0.0, 3, 0.0],
        [1, -1, 0, 0, 0, 5, 0.0, 0, 0.0],
        [2, 0, 2, 0, 1, -5, 0.0, 3, 0.0],
        [2, 0, 0, -2, 1, 4, 0.0, -2, 0.0],
        [0, 1, 2, -2, 1, 4, 0.0, -2, 0.0],
        [1, 0, 0, -1, 0, -4, 0.0, 0, 0.0],
        [0, 1, 0, -2, 0, -4, 0.0, 0, 0.0],
        [1, 0, -2, 0, 0, 4, 0.0, 0, 0.0],
        [0, 0, 0, 1, 0, -4, 0.0, 0, 0.0],
        [-2, 0, 2, 0, 2, -3, 0.0, 1, 0.0],
        [1, -1, 0, -1, 0, -3, 0.0, 0, 0.0],
        [1, 1, 0, 0, 0, -3, 0.0, 0, 0.0],
        [1, 0, 2, 0, 0, 3, 0.0, 0, 0.0],
        [1, -1, 2, 0, 2, -3, 0.0, 1, 0.0],
        [-1, -1, 2, 2, 2, -3, 0.0, 1, 0.0],
        [3, 0, 2, 0, 2, -3, 0.0, 1, 0.0],
        [0, -1, 2, 2, 2, -3, 0.0, 1, 0.0],
        [0, -2, 2, -2, 1, -2, 0.0, 1, 0.0],
        [-2, 0, 0, 0, 1, -2, 0.0, 1, 0.0],
        [1, 1, 2, 0, 2, 2, 0.0, -1, 0.0],
        [-1, 0, 2, -2, 1, -2, 0.0, 1, 0.0],
        [2, 0, 0, 0, 1, 2, 0.0, -1, 0.0],
        [1, 0, 0, 0, 2, -2, 0.0, 1, 0.0],
        [3, 0, 0, 0, 0, 2, 0.0, 0, 0.0],
        [0, 0, 2, 1, 2, 2, 0.0, -1, 0.0],
        [-1, 0, 2, 4, 2, -2, 0.0, 1, 0.0],
        [2, 0, -2, 0, 1, 1, 0.0, 0, 0.0],
        [2, 1, 0, -2, 0, 1, 0.0, 0, 0.0],
        [0, 0, -2, 2, 1, 1, 0.0, 0, 0.0],
        [0, 1, -2, 2, 0, -1, 0.0, 0, 0.0],
        [0, 1, 0, 0, 2, 1, 0.0, 0, 0.0],
        [-1, 0, 0, 1, 1, 1, 0.0, 0, 0.0],
        [0, 1, 2, -2, 0, -1, 0.0, 0, 0.0],
        [-1, 0, 0, 0, 2, 1, 0.0, -1, 0.0],
        [1, 0, 0, -4, 0, -1, 0.0, 0, 0.0],
        [-2, 0, 2, 2, 2, 1, 0.0, -1, 0.0],
        [2, 0, 0, -4, 0, -1, 0.0, 0, 0.0],
        [1, 1, 2, -2, 2, 1, 0.0, -1, 0.0],
        [1, 0, 2, 2, 1, -1, 0.0, 1, 0.0],
        [-2, 0, 2, 4, 2, -1, 0.0, 1, 0.0],
        [-1, 0, 4, 0, 2, 1, 0.0, 0, 0.0],
        [1, -1, 0, -2, 0, 1, 0.0, 0, 0.0],
        [2, 0, 2, -2, 1, 1, 0.0, -1, 0.0],
        [2, 0, 2, 2, 2, -1, 0.0, 0, 0.0],
        [1, 0, 0, 2, 1, -1, 0.0, 0, 0.0],
        [0, 0, 4, -2, 2, 1, 0.0, 0, 0.0],
        [3, 0, 2, -2, 2, 1, 0.0, 0, 0.0],
        [1, 0, 2, -2, 0, -1, 0.0, 0, 0.0],
        [0, 1, 2, 0, 1, 1, 0.0, 0, 0.0],
        [-1, -1, 0, 2, 1, 1, 0.0, 0, 0.0],
        [0, 0, -2, 0, 1, -1, 0.0, 0, 0.0],
        [0, 0, 2, -1, 2, -1, 0.0, 0, 0.0],
        [0, 1, 0, 2, 0, -1, 0.0, 0, 0.0],
        [1, 0, -2, -2, 0, -1, 0.0, 0, 0.0],
        [0, -1, 2, 0, 1, -1, 0.0, 0, 0.0],
        [1, 1, 0, -2, 1, -1, 0.0, 0, 0.0],
        [1, 0, -2, 2, 0, -1, 0.0, 0, 0.0],
        [2, 0, 0, 2, 0, 1, 0.0, 0, 0.0],
        [0, 0, 2, 4, 2, -1, 0.0, 0, 0.0],
        [0, 1, 0, 1, 0, 1, 0.0, 0, 0.0],
    ];
}
