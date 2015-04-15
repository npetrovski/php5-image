<?php

namespace Image\Fx;

use Image\Helper\Color;
use Image\Fx\FxBase;
use Image\Plugin\PluginInterface;

class Colorize extends FxBase implements PluginInterface {

    public function __construct($find = '000000', $replace = '000000') {
        $this->setColorize($find, $replace);
    }

    public function setColorize($find = '000000', $replace = '000000') {
        $this->find = $find;
        $this->replace = $replace;
        return $this;
    }

    public function generate() {

        $findColor = Color::hexColorToArrayColor($this->find);
        $replaceColor = Color::hexColorToArrayColor($this->replace);

        $index = imagecolorclosest($this->_owner->image, $findColor['red'], $findColor['green'], $findColor['blue']); //find

        imagecolorset($this->_owner->image, $index, $replaceColor['red'], $replaceColor['green'], $replaceColor['blue']); //replace

        unset($index);  
        return true;
    }

}
