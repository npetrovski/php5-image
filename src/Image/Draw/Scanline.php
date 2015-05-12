<?php

namespace Image\Draw;

use Image\Helper\Color;
use Image\Plugin\PluginInterface;

class Scanline extends DrawBase implements PluginInterface
{
    private $_width;
    private $_color;
    private $_light_alpha;
    private $_dark_alpha;

    public function __construct($width = 4, $color = 'FFFFFF', $light_alpha = 100, $dark_alpha = 80)
    {
        $this->setWidth($width);
        $this->setColor($color);
        $this->setAlpha($light_alpha, $dark_alpha);
    }

    public function setWidth($width = 4)
    {
        $this->_width = $width;

        return $this;
    }

    public function setColor($color = 'FFFFFF')
    {
        $this->_color = $color;

        return $this;
    }

    public function setAlpha($light_alpha = 100, $dark_alpha = 80)
    {
        $this->_light_alpha = $light_alpha;
        $this->_dark_alpha = $dark_alpha;

        return $this;
    }

    public function generate()
    {
        $alt = 0;
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, true);

        $color = Color::hexColorToArrayColor($this->_color);
        $light = imagecolorallocatealpha($this->_owner->image, $color['red'], $color['green'], $color['blue'], $this->_light_alpha);
        $dark = imagecolorallocatealpha($this->_owner->image, $color['red'], $color['green'], $color['blue'], $this->_dark_alpha);

        for ($x = 0; $x < $this->_owner->getImageHeight(); $x += $this->_width) {
            if ($alt++ % 2 == 0) {
                imagefilledrectangle($this->_owner->image, 0, $x, $this->_owner->getImageWidth(), $x + $this->_width - 1, $light);
            } else {
                imagefilledrectangle($this->_owner->image, 0, $x, $this->_owner->getImageWidth(), $x + $this->_width - 1, $dark);
            }
        }

        return true;
    }
}
