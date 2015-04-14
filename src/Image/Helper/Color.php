<?php

namespace Image\Helper;

class Color {

    /**
     * Array => Hex
     * 
     * @param array $arrColor
     * @return string
     */
    public static function arrayColorToHexColor($arrColor = array(0, 0, 0)) {
        return self::intColorToHexColor(self::arrayColorToIntColor($arrColor));
    }

    /**
     * Int => Hex
     * 
     * @param int $intColor
     * @return string
     */
    public static function intColorToHexColor($intColor = 0) {
        return str_pad(dechex($intColor), 6, "0", STR_PAD_LEFT);
    }

    /**
     * Hex => Int
     * 
     * @param string $hexColor
     * @return int
     */
    public static function hexColorToIntColor($hexColor = "000000") {
        return self::arrayColorToIntColor(self::hexColorToArrayColor($hexColor));
    }

    /**
     * Int => Array
     * 
     * @param int $intColor
     * @return array
     */
    public static function intColorToArrayColor($intColor = 0) {
        return array(
            'alpha' => ($intColor >> 24) & 0xFF,
            'red' => ($intColor >> 16) & 0xFF,
            'green' => ($intColor >> 8) & 0xFF,
            'blue' => ($intColor) & 0xFF
        );
    }

    /**
     * Hex => Array
     * 
     * @param string $hexColor
     * @return array
     */
    public static function hexColorToArrayColor($hexColor = "000000") {
        return array(
            'alpha' => null,
            'red' => hexdec(substr($hexColor, 0, 2)),
            'green' => hexdec(substr($hexColor, 2, 2)),
            'blue' => hexdec(substr($hexColor, 4, 2))
        );
    }

    /**
     * Array => Int
     * 
     * @param array $arrColor
     * @return int
     */
    public static function arrayColorToIntColor($arrColor = array(0, 0, 0)) {
        return (($arrColor['alpha'] & 0xFF) << 24) |
                (($arrColor['red'] & 0xFF) << 16) |
                (($arrColor['green'] & 0xFF) << 8) |
                (($arrColor['blue'] & 0xFF) << 0);
    }

    /**
     * Get current color in XYZ format
     * 
     * @param array $arrColor
     * @return array
     */
    public static function arrayColorToXyz($arrColor = array(0, 0, 0)) {

        // Normalize RGB values to 1
        $arrColor = array_map(function($item) {
            return $item / 255;
        }, $arrColor);

        $arrColor = array_map(function($item) {
            $item = ($item > 0.04045) ? pow((($item + 0.055) / 1.055), 2.4) : $item / 12.92; 
            return $item * 100;
        }, $arrColor);

        //Observer. = 2°, Illuminant = D65
        $xyz = array(
            'x' => ($arrColor['red'] * 0.4124) + ($arrColor['green'] * 0.3576) + ($arrColor['blue'] * 0.1805),
            'y' => ($arrColor['red'] * 0.2126) + ($arrColor['green'] * 0.7152) + ($arrColor['blue'] * 0.0722),
            'z' => ($arrColor['red'] * 0.0193) + ($arrColor['green'] * 0.1192) + ($arrColor['blue'] * 0.9505)
        );

        return $xyz;
    }

    /**
     * Get color CIE-Lab values
     * 
     * @param array $arrColor
     * @return array
     */
    public static function arraColorToLabCie($arrColor = array(0, 0, 0)) {

        $xyz = self::arrayColorToXyz($arrColor);

        //Ovserver = 2*, Iluminant=D65
        $xyz['x'] /= 95.047;
        $xyz['y'] /= 100;
        $xyz['z'] /= 108.883;

        $xyz = array_map(function($item) {
            return ($item > 0.008856) ? pow($item, 1 / 3) : (7.787 * $item) + (16 / 116);
        }, $xyz);

        return array(
            'l' => (116 * $xyz['y']) - 16,
            'a' => 500 * ($xyz['x'] - $xyz['y']),
            'b' => 200 * ($xyz['y'] - $xyz['z'])
        );
    }

    /**
     * Get HSV values for color
     * (integer values from 0-255, fast but less accurate)
     * 
     * @param array $arrColor
     * @return int 
     */
    public static function arrayColorToHsvInt($arrColor = array(0, 0, 0)) {

        $rgbMin = min($arrColor);
        $rgbMax = max($arrColor);

        $hsv = array(
            'hue' => 0,
            'sat' => 0,
            'val' => $rgbMax
        );

        // If value is 0, color is black
        if ($hsv['val'] == 0) {
            return $hsv;
        }

        // Calculate saturation
        $hsv['sat'] = round(255 * ($rgbMax - $rgbMin) / $hsv['val']);
        if ($hsv['sat'] == 0) {
            $hsv['hue'] = 0;
            return $hsv;
        }

        // Calculate hue
        if ($rgbMax == $arrColor['red']) {
            $hsv['hue'] = round(0 + 43 * ($arrColor['green'] - $arrColor['blue']) / ($rgbMax - $rgbMin));
        } else if ($rgbMax == $arrColor['green']) {
            $hsv['hue'] = round(85 + 43 * ($arrColor['blue'] - $arrColor['red']) / ($rgbMax - $rgbMin));
        } else {
            $hsv['hue'] = round(171 + 43 * ($arrColor['red'] - $arrColor['green']) / ($rgbMax - $rgbMin));
        }
        if ($hsv['hue'] < 0) {
            $hsv['hue'] += 255;
        }

        return $hsv;
    }

}
