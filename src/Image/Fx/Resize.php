<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Resize extends FxBase implements PluginInterface {

    public function __construct($resize_x = 0, $resize_y = 0) {
        $this->resize_x = $resize_x;
        $this->resize_y = $resize_y;
    }

    public function setResize($resize_x = 0, $resize_y = 0) {
        $this->resize_x = $resize_x;
        $this->resize_y = $resize_y;
        return $this;
    }

    public function calculate() {
        $old_x = $this->_owner->imagesx();
        $old_y = $this->_owner->imagesy();
        //Resize the image to be a set size proportionate to the aspect ratio
        //Default to the old size
        $this->canvas_x = $old_x;
        $this->canvas_y = $old_y;
        if ($this->resize_x > 0 and $this->resize_y > 0) {
            $this->canvas_x = $this->resize_x;
            $this->canvas_y = $this->resize_y;
        } elseif ($this->resize_x > 0) {
            if ($this->resize_x < $old_x) {
                $this->canvas_x = $this->resize_x;
                $this->canvas_y = floor(($this->resize_x / $old_x) * $old_y);
            }
        } elseif ($this->resize_y > 0) {
            if ($this->resize_y < $old_y) {
                $this->canvas_x = floor(($this->resize_y / $old_y) * $old_x);
                $this->canvas_y = $this->resize_y;
            }
        }
        return true;
    }

    public function generate() {
        $src_x = $this->_owner->imagesx();
        $src_y = $this->_owner->imagesy();
        $this->calculate();
        $dst_x = $this->canvas_x;
        $dst_y = $this->canvas_y;
        $dst = new Canvas();
        $dst->createImageTrueColorTransparent($dst_x, $dst_y);
        imagecopyresampled($dst->image, $this->_owner->image, 0, 0, 0, 0, $dst_x, $dst_y, $src_x, $src_y);
        $this->_owner->image = $dst->image;
        unset($dst);
        return true;
    }

}
