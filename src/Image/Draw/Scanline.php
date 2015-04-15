<?php

namespace Image\Draw;

use Image\Helper\Color;
use Image\Draw\DrawBase;
use Image\Plugin\PluginInterface;

class Scanline extends DrawBase implements PluginInterface {

    public function __construct($width = 4, $color = "FFFFFF", $light_alpha = 100, $dark_alpha = 80) {
        $this->width = $width;
        $this->color = $color;
        $this->light_alpha = $light_alpha;
        $this->dark_alpha = $dark_alpha;
    }

    public function generate() {
        $alt = 0;
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, true);
        
        $arrColor = Color::hexColorToArrayColor($this->color);
        $l = imagecolorallocatealpha($this->_owner->image, $arrColor['red'], $arrColor['green'], $arrColor['blue'], $this->light_alpha);
        $d = imagecolorallocatealpha($this->_owner->image, $arrColor['red'], $arrColor['green'], $arrColor['blue'], $this->dark_alpha);
        for ($x = 0; $x < $this->_owner->imagesy(); $x += $this->width) {
            if ($alt++ % 2 == 0) {
                imagefilledrectangle($this->_owner->image, 0, $x, $this->_owner->imagesx(), $x + $this->width - 1, $l);
            } else {
                imagefilledrectangle($this->_owner->image, 0, $x, $this->_owner->imagesx(), $x + $this->width - 1, $d);
            }
        }
        return true;
    }

}
