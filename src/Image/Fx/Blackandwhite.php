<?php

namespace Image\Fx;

use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Blackandwhite extends FxBase implements PluginInterface {

    public function __construct($algorithm = "YIQ") {
        $this->algorithm = $algorithm;
    }

    public function setAlgorithm($algorithm = "YIQ") {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function generate() {
        
        $width = $this->_owner->imagesx();
        $height = $this->_owner->imagesy();
        
        for ($x = 0; $x < 256; $x++) {
            $palette[$x] = imagecolorallocate($this->_owner->image, $x, $x, $x);
        }
        
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = $this->_owner->imageColorAt($x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                switch ($this->algorithm) {
                    case "red":
                        $val = $r;
                        break;
                    case "green":
                        $val = $g;
                        break;
                    case "blue":
                        $val = $b;
                        break;
                    case "max":
                        $val = max($r, $g, $b);
                        break;
                    case "avg":
                        $val = ($r + $g + $b) / 3;
                        break;
                    default:
                        $val = (($r * 0.299) + ($g * 0.587) + ($b * 0.114));
                        break;
                }
                
                imagesetpixel($this->_owner->image, $x, $y, $palette[$val]);
            }
        }
        return true;
    }

}
