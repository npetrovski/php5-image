<?php

namespace Image\Fx;

use Image\Plugin\PluginInterface;

class Ripple extends FxBase implements PluginInterface
{
    private $_frequency;
    private $_amplitude;
    private $_wrap_around;

    public function __construct($frequency = 3, $amplitude = 4, $wrap_around = true)
    {
        $this->setRipple($frequency, $amplitude, $wrap_around);
    }

    public function setRipple($frequency = 3, $amplitude = 4, $wrap_around = true)
    {
        $this->_frequency = $frequency;
        $this->_amplitude = $amplitude;
        $this->_wrap_around = $wrap_around;
        
        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();
        $map = array();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $dis_x = $x + (sin(deg2rad(($y / $height) * 360) * $this->_frequency) * $this->_amplitude);
                $dis_y = $y + (sin(deg2rad(($x / $width) * 360) * $this->_frequency) * $this->_amplitude);
                if ($this->_wrap_around == true) {
                    $dis_x = ($dis_x < 0) ? $dis_x + $width : $dis_x;
                    $dis_x = ($dis_x >= $width) ? $dis_x - $width : $dis_x;
                    $dis_y = ($dis_y < 0) ? $dis_y + $height : $dis_y;
                    $dis_y = ($dis_y >= $height) ? $dis_y - $height : $dis_y;
                } else {
                    $dis_x = ($dis_x < 0) ? 0 : $dis_x;
                    $dis_x = ($dis_x > $width) ? $width : $dis_x;
                    $dis_y = ($dis_y < 0) ? 0 : $dis_y;
                    $dis_y = ($dis_y > $height) ? $height : $dis_y;
                }
                $map['x'][$x][$y] = $dis_x;
                $map['y'][$x][$y] = $dis_y;
            }
        }

        $this->_owner->displace($map);

        return true;
    }
}
