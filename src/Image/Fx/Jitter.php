<?php

namespace Image\Fx;

use Image\Plugin\PluginInterface;

class Jitter extends FxBase implements PluginInterface
{
    private $_jitter;

    private $_wrap_around;

    public function __construct($jitter = 3, $wrap_around = true)
    {
        $this->setJitter($jitter, $wrap_around);
    }

    public function setJitter($jitter, $wrap_around = true)
    {
        $this->_jitter = $jitter;
        $this->_wrap_around = $wrap_around;
        
        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        $displacement = array();
        
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $dis_x = $x + (rand(0, $this->_jitter) - ($this->_jitter / 2));
                $dis_y = $y + (rand(0, $this->_jitter) - ($this->_jitter / 2));

                if ($this->_wrap_around) {
                    $dis_x = ($dis_x < 0) ? $dis_x + $width : $dis_x;
                    $dis_x = ($dis_x > $width) ? $dis_x - $width : $dis_x;
                    $dis_y = ($dis_y < 0) ? $dis_y + $height : $dis_y;
                    $dis_y = ($dis_y > $height) ? $dis_y - $height : $dis_y;
                } else {
                    $dis_x = ($dis_x < 0) ? 0 : $dis_x;
                    $dis_x = ($dis_x > $width) ? $width : $dis_x;
                    $dis_y = ($dis_y < 0) ? 0 : $dis_y;
                    $dis_y = ($dis_y > $height) ? $height : $dis_y;
                }
                $displacement['x'][$x][$y] = $dis_x;
                $displacement['y'][$x][$y] = $dis_y;
            }
        }
        $this->_owner->displace($displacement);

        return true;
    }
}
