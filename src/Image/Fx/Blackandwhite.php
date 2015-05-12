<?php

namespace Image\Fx;

use Image\Plugin\PluginInterface;

class Blackandwhite extends FxBase implements PluginInterface
{
    protected $_algorithm;

    public function __construct($algorithm = 'yiq')
    {
        $this->setAlgorithm($algorithm);
    }

    public function setAlgorithm($algorithm = 'yiq')
    {
        $this->_algorithm = $algorithm;

        return $this;
    }

    public function generate()
    {
        $width = $this->_owner->getImageWidth();
        $height = $this->_owner->getImageHeight();

        for ($x = 0; $x < 256; $x++) {
            $palette[$x] = imagecolorallocate($this->_owner->image, $x, $x, $x);
        }

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = $this->_owner->imageColorAt($x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                switch ($this->_algorithm) {
                    case 'red':
                        $val = $r;
                        break;
                    case 'green':
                        $val = $g;
                        break;
                    case 'blue':
                        $val = $b;
                        break;
                    case 'max':
                        $val = max($r, $g, $b);
                        break;
                    case 'avg':
                        $val = ($r + $g + $b) / 3;
                        break;
                    case 'yiq':
                    default:
                        $val = (($r * 0.299) + ($g * 0.587) + ($b * 0.114));
                        break;
                }

                imagesetpixel($this->_owner->image, $x, $y, $palette[$val]);
            }
        }

        return true;
    }
}
