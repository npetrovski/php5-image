<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Crop extends FxBase implements PluginInterface
{
    private $_crop_x;
    private $_crop_y;

    public function __construct($cropX = 0, $cropY = 0)
    {
        $this->setCrop($cropX, $cropY);
    }

    public function setCrop($cropX = 0, $cropY = 0)
    {
        $this->_crop_x = $cropX;
        $this->_crop_y = $cropY;

        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        //Calculate the cropping area
        if ($this->_crop_x > 0 && $width > $this->_crop_x) {
            $width = $this->_crop_x;
        }

        if ($this->_crop_y > 0 && $height > $this->_crop_y) {
            $height = $this->_crop_y;
        }

        $crop = new Canvas();
        $crop->createImageTrueColorTransparent($width, $height);

        $src_width = $this->_owner->getHandleX() - floor($width / 2);
        $src_height = $this->_owner->getHandleY() - floor($height / 2);

        imagecopy($crop->image, $this->_owner->image, 0, 0, $src_width, $src_height, $width, $height);
        $this->_owner->image = $crop->image;

        unset($crop);

        return true;
    }
}
