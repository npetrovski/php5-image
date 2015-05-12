<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Offset extends FxBase implements PluginInterface
{
    private $_offset_x;
    private $_offset_y;

    public function __construct($offset_x = 0, $offset_y = 0)
    {
        $this->setOffset($offset_x, $offset_y);
    }

    public function setOffset($offset_x = 0, $offset_y = 0)
    {
        $this->_offset_x = $offset_x;
        $this->_offset_y = $offset_y;

        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        $temp = new Canvas();
        $temp->createImageTrueColor($width, $height);

        imagecopy($temp->image, $this->_owner->image, $this->_offset_x, $this->_offset_y, 0, 0,
                $width - $this->_offset_x, $height - $this->_offset_y);

        imagecopy($temp->image, $this->_owner->image, 0, 0, $width - $this->_offset_x, $height -
                $this->_offset_y, $this->_offset_x, $this->_offset_y);

        imagecopy($temp->image, $this->_owner->image, 0, $this->_offset_y, $width - $this->_offset_x, 0,
                $this->_offset_x, $height - $this->_offset_y);

        imagecopy($temp->image, $this->_owner->image, $this->_offset_x, 0, 0, $height - $this->_offset_y,
                $width - $this->_offset_x, $this->_offset_y);

        $this->_owner->image = $temp->image;

        unset($temp);

        return true;
    }
}
