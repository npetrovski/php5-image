<?php

namespace Image\Fx;

use Image\Plugin\PluginInterface;

class Rotate extends FxBase implements PluginInterface
{
    private $_angle = 0;

    public function __construct($angle = 0)
    {
        $this->_angle = $angle;
    }

    public function generate()
    {
        if (!function_exists('imagerotate')) {
            function imagerotate(&$srcImg, $angle, $bgcolor, $ignore_transparent = 0)
            {
                function rotateX($x, $y, $theta)
                {
                    return $x * cos($theta) - $y * sin($theta);
                }

                function rotateY($x, $y, $theta)
                {
                    return $x * sin($theta) + $y * cos($theta);
                }

                $srcw = imagesx($srcImg);
                $srch = imagesy($srcImg);

                if ($angle == 0) {
                    return $srcImg;
                }

                // Convert the angle to radians
                $theta = deg2rad($angle);

                // Calculate the width of the destination image.
                $temp = array(rotateX(0, 0, 0 - $theta),
                    rotateX($srcw, 0, 0 - $theta),
                    rotateX(0, $srch, 0 - $theta),
                    rotateX($srcw, $srch, 0 - $theta),
                );
                $minX = floor(min($temp));
                $maxX = ceil(max($temp));
                $width = $maxX - $minX;

                // Calculate the height of the destination image.
                $temp = array(rotateY(0, 0, 0 - $theta),
                    rotateY($srcw, 0, 0 - $theta),
                    rotateY(0, $srch, 0 - $theta),
                    rotateY($srcw, $srch, 0 - $theta),
                );
                $minY = floor(min($temp));
                $maxY = ceil(max($temp));
                $height = $maxY - $minY;

                $destimg = imagecreatetruecolor($width, $height);
                imagefill($destimg, 0, 0, imagecolorallocate($destimg, 0, 255, 0));

                // sets all pixels in the new image
                for ($x = $minX; $x < $maxX; $x++) {
                    for ($y = $minY; $y < $maxY; $y++) {
                        // fetch corresponding pixel from the source image
                        $srcX = round(rotateX($x, $y, $theta));
                        $srcY = round(rotateY($x, $y, $theta));
                        if ($srcX >= 0 && $srcX < $srcw && $srcY >= 0 && $srcY < $srch) {
                            $color = imagecolorat($srcImg, $srcX, $srcY);
                        } else {
                            $color = $bgcolor;
                        }
                        imagesetpixel($destimg, $x - $minX, $y - $minY, $color);
                    }
                }

                return $destimg;
            }
        }

        if ($this->_angle > 0) {
            $bg = imagecolorallocatealpha($this->_owner->image, 255, 255, 255, 127);

            $rotate = imagerotate($this->_owner->image, $this->_angle, $bg);
            imagecolortransparent($rotate, $bg);
            imagesavealpha($rotate, true);
            $this->_owner->image = $rotate;
        }

        return true;
    }
}
