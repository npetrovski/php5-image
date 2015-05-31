<?php

namespace Image\Helper;

use Image\Plugin\PluginInterface;

class Analyser extends HelperBase implements PluginInterface
{
    private $__count_colours = array();
    private $__count_a = array();
    private $__count_r = array();
    private $__count_g = array();
    private $__count_b = array();
    private $__count_hue = array();
    private $__count_saturation = array();
    private $__count_brightness = array();
    private $__analyse_complete = false;
    private $__analyse_HSB_complete = false;

    public function __construct()
    {
        for ($x = 0; $x < 255; $x++) {
            $this->__count_a[$x] = 0;
            $this->__count_r[$x] = 0;
            $this->__count_g[$x] = 0;
            $this->__count_b[$x] = 0;
        }
    }

    public function generate()
    {
    }

    public function countColors()
    {
        if (!isset($this->_owner->image)) {
            return false;
        }
        if (!$this->__analyse_complete) {
            $this->analyse();
        }

        return count($this->__count_colours);
    }

    public function averageChannel($channel = 'all')
    {
        if (!isset($this->_owner->image)) {
            return false;
        }
        $this->analyse();
        switch ($channel) {
            case 'r':
            case 'red':
                return $this->arrayAvg($this->__count_r);

            case 'g':
            case 'green':
                return $this->arrayAvg($this->__count_g);

            case 'b':
            case 'blue':
                return $this->arrayAvg($this->__count_b);
                break;
            case 'a':
            case 'alpha':
                return $this->arrayAvg($this->__count_a);

            default:
                return $this->arrayAvg($this->__count_colours);
        }
    }

    public function minChannel($channel = 'all')
    {
        if (!isset($this->_owner->image)) {
            return false;
        }
        if (!$this->__analyse_complete) {
            $this->analyse();
        }
        switch ($channel) {
            case 'r':
            case 'red':
                return $this->arrayMin($this->__count_r);

            case 'g':
            case 'green':
                return $this->arrayMin($this->__count_g);

            case 'b':
            case 'blue':
                return $this->arrayMin($this->__count_b);

            case 'a':
            case 'alpha':
                return $this->arrayMin($this->__count_a);

            default:
                return $this->arrayMin($this->__count_colours);
        }
    }

    public function maxChannel($channel = 'all')
    {
        if (!isset($this->_owner->image)) {
            return false;
        }

        if (!$this->__analyse_complete) {
            $this->analyse();
        }

        switch ($channel) {
            case 'r':
            case 'red':
                return $this->arrayMax($this->__count_r);

            case 'g':
            case 'green':
                return $this->arrayMax($this->__count_g);

            case 'b':
            case 'blue':
                return $this->arrayMax($this->__count_b);

            case 'a':
            case 'alpha':
                return $this->arrayMax($this->__count_a);

            default:
                return $this->arrayMax($this->__count_colours);
        }
    }

    public function hue($x, $y)
    {
        if (!isset($this->_owner->image)) {
            return false;
        }

        $color = $this->_owner->imageColorAt($x, $y);
        $arrColor = Color::intColorToArrayColor($color);
        list($h, $s, $b) = $this->hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);

        return $h;
    }

    public function saturation($x, $y)
    {
        if (!isset($this->_owner->image)) {
            return false;
        }

        $color = $this->_owner->imageColorAt($x, $y);
        $arrColor = Color::intColorToArrayColor($color);
        list($h, $s, $b) = $this->hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);

        return $s;
    }

    public function brightness($x, $y)
    {
        if (!isset($this->_owner->image)) {
            return false;
        }

        $color = $this->_owner->imageColorAt($x, $y);
        $arrColor = Color::intColorToArrayColor($color);
        list($h, $s, $b) = $this->hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);

        return $b;
    }

    public function imageHue()
    {
        if (!$this->__analyse_complete) {
            $this->analyse();
        }

        if (!$this->__analyse_HSB_complete) {
            $this->analyseHsb();
        }

        return $this->arrayMax($this->__count_hue);
    }

    public function imageSaturation()
    {
        if (!$this->__analyse_complete) {
            $this->analyse();
        }

        if (!$this->__analyse_HSB_complete) {
            $this->analyseHsb();
        }

        return $this->arrayMax($this->__count_saturation);
    }

    public function imageBrightness()
    {
        if (!$this->__analyse_complete) {
            $this->analyse();
        }

        if (!$this->__analyse_HSB_complete) {
            $this->analyseHsb();
        }

        return $this->arrayMax($this->__count_brightness);
    }

    private function analyse()
    {
        if (!isset($this->_owner->image)) {
            return false;
        }

        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = $this->_owner->imageColorAt($x, $y);
                $arrColor = Color::intColorToArrayColor($color);
                @$this->__count_colours[$color]++;
                @$this->__count_a[$arrColor['alpha']]++;
                @$this->__count_r[$arrColor['red']]++;
                @$this->__count_g[$arrColor['green']]++;
                @$this->__count_b[$arrColor['blue']]++;
            }
        }
        $this->__analyse_complete = true;
    }

    private function analyseHsb()
    {
        foreach (array_keys($this->__count_colours) as $color) {
            $arrColor = Color::intColorToArrayColor($color);
            list($h, $s, $b) = $this->hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);
            @$this->__count_hue[$h]++;
            @$this->__count_saturation[$s]++;
            @$this->__count_brightness[$b]++;
        }

        $this->__analyse_HSB_complete = true;
    }

    private function arrayAvg($array)
    {
        $t = $s = 0;
        foreach ($array as $k => $v) {
            $t += $k * $v;
            $s += $v;
        }

        return round($t / $s);
    }

    private function arrayMin($array)
    {
        $mv = 256;
        foreach ($array as $k => $v) {
            if ($v < $mv) {
                $mk = $k;
                $mv = $v;
            }
        }

        return $mk;
    }

    private function arrayMax($array)
    {
        $mv = 0;
        foreach ($array as $k => $v) {
            if ($v > $mv) {
                $mk = $k;
                $mv = $v;
            }
        }

        return $mk;
    }

    private function hsb($r, $g, $b)
    {
        $min = min($r, $g, $b);
        $max = max($r, $g, $b);
        $delta = ($max - $min);
        $brightness = $max;
        if ($max != .0) {
            $saturation = $delta / $max;
        } else {
            $saturation = .0;
            $hue = -1;
        }
        if ($saturation != .0) {
            if ($r == $max) {
                $hue = ($g - $b) / $delta;
            } else {
                if ($g == $max) {
                    $hue = 2.0 + ($b - $r) / $delta;
                } else {
                    if ($b == $max) {
                        $hue = 4.0 + ($r - $g) / $delta;
                    }
                }
            }
        } else {
            $hue = -1.0;
        }

        $hue = $hue * 60.0;

        if ($hue < .0) {
            $hue = $hue + 360.0;
        }

        return array(
            $hue, round($saturation * 100), round(($brightness / 255) * 100),
        );
    }
}
