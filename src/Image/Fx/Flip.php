<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Flip extends FxBase implements PluginInterface {

    public function __construct($flip_x = true, $flip_y = false) {
        $this->flip_x = $flip_x;
        $this->flip_y = $flip_y;
    }

    public function setFlip($flip_x = true, $flip_y = false) {
        $this->flip_x = $flip_x;
        $this->flip_y = $flip_y;
        return $this;
    }

    public function generate() {
        $src_x = $this->_owner->imagesx();
        $src_y = $this->_owner->imagesy();
        $flip_x = $this->flip_x;
        $flip_y = $this->flip_y;
        $flip = new Canvas($src_x, $src_y);
        if ($flip_x == true) {
            imagecopy($flip->image, $this->_owner->image, 0, 0, 0, 0, $src_x, $src_y);
            for ($x = 0; $x < $src_x; $x++) {
                imagecopy($this->_owner->image, $flip->image, $src_x - $x - 1, 0, $x, 0, 1, $src_y);
            }
        }
        
        if ($flip_y == true) {
            imagecopy($flip->image, $this->_owner->image, 0, 0, 0, 0, $src_x, $src_y);
            for ($y = 0; $y < $src_y; $y++) {
                imagecopy($this->_owner->image, $flip->image, 0, $src_y - $y - 1, 0, $y, $src_x, 1);
            }
        }
        
        $this->_owner->image = $flip->image;
        unset($flip);
        return true;
    }

}
