<?php

namespace Image\Fx;

use Image\Canvas;
use Image\Plugin\PluginInterface;

class Corners extends FxBase implements PluginInterface
{
    private $_radius_x;

    private $_radius_y;

    /**
     * Corner.
     *
     * @var string
     */
    private static $_cornerpng = <<<CORNERPNG
iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m
dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAH2SURBVHjaYvz//z/DQAFGRkZNIAXCakCsBMSyQCwJ
xCJAzA/EnAABxEJnB8kDKWsgtgBiMyA2AmJWfHoAAoiFDo4ChYQ7ELsBsTMQK5CiHyCAWGjoMBUgFQDE
fkBsS645AAHEQgOHKQKpCCAOA2IDSs0DCCAGUCahBgYCDiBOB+KjQPyfWhgggKjlODsgXkxVh0ExQABR
6jBGIM4B4ms0cRwQAwQQJY4D5cYJNHMYFAMEELmOA5Vhq2nuOCAGCCByHOcCxPvo4jggBgggUh3nAcTH
6eY4IAYIIFJDjr6OA2KAACIlze2ju+OAGCCAiM2tqwfEcUAMEEDElHMTBsxxQAwQQIQcmDOgjgNigAAi
VH1dG2gHAgQQvop/8YA7DogBAgiXA9MHheOAGCCAmHC05+IYBgkACCAmLGKgxqbVYHEgQAAxYWmmhzEM
IgAQQOghGECVZjoVAUAAMaH1vvwYBhkACCDknBs2WHIuMgYIIOQodmMYhAAggJiQevzOg9GBAAEEC0Fr
Unv89AIAAQRzoAXDIAUAAQRzoNlgdSBAADFAh79+DcYcDMIAAcQEdSDrYA09gABigg4eDloAEEBM0JHN
QQsAAogJOuw6aAFAADFBx4QHLQAIICbogPWgBQABxAQdTR+0ACCAQP3eP0DMPFgdCBBgAJ273bQUqcwV
AAAAAElFTkSuQmCC=
CORNERPNG;

    public function __construct($radius_x = 10, $radius_y = 10)
    {
        $this->setRadius($radius_x, $radius_y);
    }

    public function setRadius($x = 10, $y = 10)
    {
        $this->_radius_x = $x;
        $this->_radius_y = $y;

        return $this;
    }

    public function generate()
    {
        imagesavealpha($this->_owner->image, true);
        imagealphablending($this->_owner->image, false);

        $image_x = $this->_owner->getImageWidth();
        $image_y = $this->_owner->getImageHeight();
        $gdCorner = imagecreatefromstring(base64_decode(self::$_cornerpng));
        $corner = new Canvas();
        $corner->createImageTrueColorTransparent($this->_radius_x, $this->_radius_y);

        imagecopyresampled($corner->image, $gdCorner, 0, 0, 0, 0, $this->_radius_x, $this->_radius_y, imagesx($gdCorner), imagesy($gdCorner));
        $corner_x = $this->_radius_x - 1;
        $corner_y = $this->_radius_y - 1;
        for ($y = 0; $y < $corner_y; $y++) {
            for ($x = 0; $x < $corner_x; $x++) {
                for ($c = 0; $c < 4; $c++) {
                    switch ($c) {
                        case 0:
                            $xo = 0;
                            $yo = 0;
                            $cxo = $x;
                            $cyo = $y;
                            break;
                        case 1:
                            $xo = ($image_x - $corner_x);
                            $yo = 0;
                            $cxo = $corner_x - $x;
                            $cyo = $y;
                            break;
                        case 2:
                            $xo = ($image_x - $corner_x);
                            $yo = ($image_y - $corner_y);
                            $cxo = $corner_x - $x;
                            $cyo = $corner_y - $y;
                            break;
                        case 3:
                            $xo = 0;
                            $yo = ($image_y - $corner_y);
                            $cxo = $x;
                            $cyo = $corner_y - $y;
                            break;
                    }
                    $irgb = imagecolorat($this->_owner->image, $xo + $x, $yo + $y);
                    $r = ($irgb >> 16) & 0xFF;
                    $g = ($irgb >> 8) & 0xFF;
                    $b = $irgb & 0xFF;
                    $crgb = imagecolorat($corner->image, $cxo, $cyo);
                    $a = ($crgb >> 24) & 0xFF;
                    $colour = imagecolorallocatealpha($this->_owner->image, $r, $g, $b, $a);
                    switch ($c) {
                        case 0:
                            imagesetpixel($this->_owner->image, $x, $y, $colour);
                            break;
                        case 1:
                            imagesetpixel($this->_owner->image, $xo + $x, $y, $colour);
                            break;
                        case 2:
                            imagesetpixel($this->_owner->image, $xo + $x, $yo + $y, $colour);
                            break;
                        case 3:
                            imagesetpixel($this->_owner->image, $x, $yo + $y, $colour);
                            break;
                    }
                }
            }
        }
        
        return true;
    }
}
