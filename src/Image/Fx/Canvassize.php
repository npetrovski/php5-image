<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;
use Image\Helper\Color;

class Canvassize extends FxBase implements PluginInterface
{
    private $_top;
    private $_right;
    private $_bottom;
    private $_left;
    private $_color;

    public function __construct($top = 10, $right = 10, $bottom = 10, $left = 10, $color = '')
    {
        $this->_top = $top;
        $this->_right = $right;
        $this->_bottom = $bottom;
        $this->_left = $left;
        $this->_color = $color;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        $temp = new Canvas();
        if (!empty($this->_color)) {
            $temp->createImageTrueColor($width + ($this->_right + $this->_left), $height +
                    ($this->_top + $this->_bottom));
            $arrColor = Color::hexColorToArrayColor($this->_color);
            $tempcolor = imagecolorallocate($temp->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
            imagefilledrectangle($temp->image, 0, 0, $temp->getImageWidth(), $temp->getImageHeight(), $tempcolor);
        } else {
            $temp->createImageTrueColorTransparent($width + ($this->_right + $this->_left), $height +
                    ($this->_top + $this->_bottom));
        }

        imagecopy($temp->image, $this->_owner->image, $this->_left, $this->_top, 0, 0, $width, $height);
        $this->_owner->image = $temp->image;
        unset($temp);

        return true;
    }
}
