<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Crop extends FxBase implements PluginInterface {

    private $_crop_x;
    private $_crop_y;

    public function __construct($crop_x = 0, $crop_y = 0) {
        $this->setCrop($crop_x, $crop_y);
    }

    public function setCrop($crop_x = 0, $crop_y = 0) {
        $this->_crop_x = $crop_x;
        $this->_crop_y = $crop_y;
        return $this;
    }

    public function generate() {

        $canvas_x = $this->_owner->imagesx();
        $canvas_y = $this->_owner->imagesy();

        //Calculate the cropping area
        if ($this->_crop_x > 0 && $canvas_x > $this->_crop_x) {
            $canvas_x = $this->_crop_x;
        }

        if ($this->_crop_y > 0 && $canvas_y > $this->_crop_y) {
            $canvas_y = $this->_crop_y;
        }

        $crop = new Canvas();
        $crop->createImageTrueColorTransparent($canvas_x, $canvas_y);

        $src_x = $this->_owner->getHandleX() - floor($canvas_x / 2);
        $src_y = $this->_owner->getHandleY() - floor($canvas_y / 2);

        imagecopy($crop->image, $this->_owner->image, 0, 0, $src_x, $src_y, $canvas_x, $canvas_y);
        $this->_owner->image = $crop->image;

        unset($crop);

        return true;
    }

}
