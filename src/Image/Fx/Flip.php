<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Flip extends FxBase implements PluginInterface
{
    private $_flip_x = false;
    private $_flip_y = false;

    public function __construct($flip_x = true, $flip_y = false)
    {
        $this->_flip_x = $flip_x;
        $this->_flip_y = $flip_y;
    }

    public function generate()
    {
        if ($this->_flip_x || $this->_flip_y) {
            $src_x = $this->_owner->getImageWidth();
            $src_y = $this->_owner->getImageHeight();

            $flip = new Canvas($src_x, $src_y);
            if ($this->_flip_x == true) {
                imagecopy($flip->image, $this->_owner->image, 0, 0, 0, 0, $src_x, $src_y);
                for ($x = 0; $x < $src_x; $x++) {
                    imagecopy($this->_owner->image, $flip->image, $src_x - $x - 1, 0, $x, 0, 1, $src_y);
                }
            }

            if ($this->_flip_y == true) {
                imagecopy($flip->image, $this->_owner->image, 0, 0, 0, 0, $src_x, $src_y);
                for ($y = 0; $y < $src_y; $y++) {
                    imagecopy($this->_owner->image, $flip->image, 0, $src_y - $y - 1, 0, $y, $src_x, 1);
                }
            }

            $this->_owner->image = $flip->image;
            unset($flip);
        }

        return true;
    }
}
