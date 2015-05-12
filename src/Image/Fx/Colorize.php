<?php

namespace Image\Fx;

use Image\Helper\Color;
use Image\Plugin\PluginInterface;

class Colorize extends FxBase implements PluginInterface
{
    private $_find;
    private $_replace;

    public function __construct($find = '000000', $replace = '000000')
    {
        $this->setColorize($find, $replace);
    }

    public function setColorize($find = '000000', $replace = '000000')
    {
        $this->_find = $find;
        $this->_replace = $replace;

        return $this;
    }

    public function generate()
    {
        $findColor = Color::hexColorToArrayColor($this->_find);
        $replaceColor = Color::hexColorToArrayColor($this->_replace);

        $index = imagecolorclosest($this->_owner->image, $findColor['red'], $findColor['green'], $findColor['blue']); //find

        imagecolorset($this->_owner->image, $index, $replaceColor['red'], $replaceColor['green'], $replaceColor['blue']); //replace

        unset($index);

        return true;
    }
}
