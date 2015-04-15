<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Crop extends FxBase implements PluginInterface {

    public function __construct($crop_x = 0, $crop_y = 0) {
        $this->crop_x = $crop_x;
        $this->crop_y = $crop_y;
    }

    public function setCrop($crop_x = 0, $crop_y = 0) {
        $this->crop_x = $crop_x;
        $this->crop_y = $crop_y;
        return $this;
    }

    public function calculate() {

        $old_x = $this->_owner->imagesx();
        $old_y = $this->_owner->imagesy();

        $this->canvas_x = $old_x;
        $this->canvas_y = $old_y;

        //Calculate the cropping area
        if ($this->crop_x > 0) {
            if ($this->canvas_x > $this->crop_x) {
                $this->canvas_x = $this->crop_x;
            }
        }

        if ($this->crop_y > 0) {
            if ($this->canvas_y > $this->crop_y) {
                $this->canvas_y = $this->crop_y;
            }
        }

        return true;
    }

    public function generate() {
        $this->calculate();

        $crop = new Canvas();
        $crop->createImageTrueColorTransparent($this->canvas_x, $this->canvas_y);

        $src_x = $this->_owner->getHandleX() - floor($this->canvas_x / 2);
        $src_y = $this->_owner->getHandleY() - floor($this->canvas_y / 2);

        imagecopy($crop->image, $this->_owner->image, 0, 0, $src_x, $src_y, $this->canvas_x, $this->canvas_y);

        $this->_owner->image = $crop->image;

        unset($crop);

        return true;
    }

}
