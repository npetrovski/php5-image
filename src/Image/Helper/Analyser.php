<?php

/**
 * image-analyser
 *
 * Copyright (c) 2009-2011, Nikolay Petrovski <to.petrovski@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   Image
 * @author    Nikolay Petrovski <to.petrovski@gmail.com>
 * @copyright 2009-2011 Nikolay Petrovski <to.petrovski@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.0.0
 */
require_once 'Image/Plugin/Base.php';

require_once 'Image/Plugin/Interface.php';

class Image_Helper_Analyser extends Image_Helper_Abstract implements Image_Plugin_Interface {

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

    public function __construct() {
        for ($x = 0; $x < 255; $x++) {
            $this->__count_a[$x] = 0;
            $this->__count_r[$x] = 0;
            $this->__count_g[$x] = 0;
            $this->__count_b[$x] = 0;
        }
    }

    public function generate() {
        
    }

    public function countColors($channel = "all") {
        if (!isset($this->_owner->image)) {
            return false;
        }
        if (!$this->__analyse_complete) {
            $this->_analyse();
        }
        return count($this->__count_colours);
    }

    public function averageChannel($channel = "all") {
        if (!isset($this->_owner->image)) {
            return false;
        }
        $this->_analyse();
        switch ($channel) {
            case "r":
            case "red":
                return $this->_array_avg($this->__count_r);
                break;
            case "g":
            case "green":
                return $this->_array_avg($this->__count_g);
                break;
            case "b":
            case "blue":
                return $this->_array_avg($this->__count_b);
                break;
            case "a":
            case "alpha":
                return $this->_array_avg($this->__count_a);
                break;
            default:
                return $this->_array_avg($this->__count_colours);
                break;
        }
    }

    public function minChannel($channel = "all") {
        if (!isset($this->_owner->image)) {
            return false;
        }
        if (!$this->__analyse_complete) {
            $this->_analyse();
        }
        switch ($channel) {
            case "r":
            case "red":
                return $this->_array_min($this->__count_r);
                break;
            case "g":
            case "green":
                return $this->_array_min($this->__count_g);
                break;
            case "b":
            case "blue":
                return $this->_array_min($this->__count_b);
                break;
            case "a":
            case "alpha":
                return $this->_array_min($this->__count_a);
                break;
            default:
                return $this->_array_min($this->__count_colours);
                break;
        }
    }

    public function maxChannel($channel = "all") {
        if (!isset($this->_owner->image)) {
            return false;
        }
        if (!$this->__analyse_complete) {
            $this->_analyse();
        }
        switch ($channel) {
            case "r":
            case "red":
                return $this->_array_max($this->__count_r);
                break;
            case "g":
            case "green":
                return $this->_array_max($this->__count_g);
                break;
            case "b":
            case "blue":
                return $this->_array_max($this->__count_b);
                break;
            case "a":
            case "alpha":
                return $this->_array_max($this->__count_a);
                break;
            default:
                return $this->_array_max($this->__count_colours);
                break;
        }
    }

    public function hue($x, $y) {
        if (!isset($this->_owner->image)) {
            return false;
        }
        $color = $this->_owner->imageColorAt($x, $y);
        $arrColor = $this->_owner->intColorToArrayColor($color);
        list ($h, $s, $b) = $this->_hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);
        return $h;
    }

    public function saturation($x, $y) {
        if (!isset($this->_owner->image)) {
            return false;
        }
        $color = $this->_owner->imageColorAt($x, $y);
        $arrColor = $this->_owner->intColorToArrayColor($color);
        list ($h, $s, $b) = $this->_hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);
        return $s;
    }

    public function brightness($x, $y) {
        if (!isset($this->_owner->image)) {
            return false;
        }
        $color = $this->_owner->imageColorAt($x, $y);
        $arrColor = $this->_owner->intColorToArrayColor($color);
        list ($h, $s, $b) = $this->_hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);
        return $b;
    }

    public function imageHue() {
        if (!$this->__analyse_complete) {
            $this->_analyse();
        }
        if (!$this->__analyse_HSB_complete) {
            $this->_analyseHSB();
        }
        return $this->_array_max($this->__count_hue);
    }

    public function imageSaturation() {
        if (!$this->__analyse_complete) {
            $this->_analyse();
        }
        if (!$this->__analyse_HSB_complete) {
            $this->_analyseHSB();
        }
        return $this->_array_max($this->__count_saturation);
    }

    public function imageBrightness() {
        if (!$this->__analyse_complete) {
            $this->_analyse();
        }
        if (!$this->__analyse_HSB_complete) {
            $this->_analyseHSB();
        }
        return $this->_array_max($this->__count_brightness);
    }

    private function _analyse() {
        if (!isset($this->_owner->image)) {
            return false;
        }
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = $this->_owner->imageColorAt($x, $y);
                $arrColor = $this->_owner->intColorToArrayColor($color);
                $this->__count_colours[$color]++;
                $this->__count_a[$arrColor['alpha']]++;
                $this->__count_r[$arrColor['red']]++;
                $this->__count_g[$arrColor['green']]++;
                $this->__count_b[$arrColor['blue']]++;
            }
        }
        $this->__analyse_complete = true;
    }

    private function _analyseHSB() {
        foreach ($this->__count_colours as $color => $count) {
            $arrColor = $this->_owner->intColorToArrayColor($color);
            list ($h, $s, $b) = $this->_hsb($arrColor['red'], $arrColor['green'], $arrColor['blue']);
            $this->__count_hue[$h]++;
            $this->__count_saturation[$s]++;
            $this->__count_brightness[$b]++;
        }
        $this->__analyse_HSB_complete = true;
    }

    private function _array_avg($array) {
        foreach ($array as $k => $v) {
            $t += $k * $v;
            $s += $v;
        }
        return round($t / $s);
    }

    private function _array_min($array) {
        $mv = 256;
        foreach ($array as $k => $v) {
            if ($v < $mv) {
                $mk = $k;
                $mv = $v;
            }
        }
        return $mk;
    }

    private function _array_max($array) {
        $mv = 0;
        foreach ($array as $k => $v) {
            if ($v > $mv) {
                $mk = $k;
                $mv = $v;
            }
        }
        return $mk;
    }

    private function _hsb($r, $g, $b) {
        $hue = 0.0;
        $saturation = 0.0;
        $brightness = 0.0;
        $min = min($r, $g, $b);
        $max = max($r, $g, $b);
        $delta = ($max - $min);
        $brightness = $max;
        if ($max != 0.0) {
            $saturation = $delta / $max;
        } else {
            $saturation = 0.0;
            $hue = - 1;
        }
        if ($saturation != 0.0) {
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
            $hue = - 1.0;
        }
        $hue = $hue * 60.0;
        if ($hue < 0.0) {
            $hue = $hue + 360.0;
        }
        $saturation = round($saturation * 100);
        $brightness = round(($brightness / 255) * 100);
        return array(
            $hue, $saturation, $brightness
        );
    }

}

