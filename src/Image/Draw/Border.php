<?php

namespace Image\Draw;

use Image\Canvas;
use Image\Plugin\PluginInterface;
use Image\Helper\Color;

class Border extends DrawBase implements PluginInterface
{
    private $_padding;
    private $_color;

    public function __construct($padding = 10, $color = '000000')
    {
        $this->_padding = $padding;
        $this->_color = $color;
    }

    public function setBorder($padding = 10, $color = '000000')
    {
        $this->_padding = $padding;
        $this->_color = $color;

        return $this;
    }

    public function setPadding($padding = 10)
    {
        $this->_padding = $padding;

        return $this;
    }

    public function setColor($color = '000000')
    {
        $this->_color = $color;

        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        $padding = $this->_padding;
        $arrColor = Color::hexColorToArrayColor($this->_color);

        $temp = new Canvas();
        $temp->createImageTrueColor($width + ($padding * 2), $height + ($padding * 2));
        $tempcolor = imagecolorallocate($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
        imagefill($temp->image, 0, 0, $tempcolor);
        imagecopy($temp->image, $this->_owner->image, $padding, $padding, 0, 0, $width, $height);

        $this->_owner->image = $temp->image;
        unset($temp);

        return true;
    }
}
