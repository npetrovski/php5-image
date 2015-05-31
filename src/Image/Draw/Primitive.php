<?php

namespace Image\Draw;

use Image\Plugin\PluginInterface;

class Primitive extends DrawBase implements PluginInterface
{
    const LINE = 'LINE';
    const RECTANGLE = 'RECTANGLE';
    const FILLED_RECTANGLE = 'FILLED_RECTANGLE';
    const ELLIPSE = 'ELLIPSE';
    const FILLED_ELLIPSE = 'FILLED_ELLIPSE';
    const SPIRAL = 'SPIRAL';

    private $__shapes = array();
    private $__base_color = '000000';
    private $__base_alpha = 0;

    public function __construct($base_color = '000000', $base_alpha = 0)
    {
        $this->__base_color = $base_color;
        $this->__base_alpha = $base_alpha;
    }

    public function line($x1, $y1, $x2, $y2, $color = null)
    {
        $this->__shapes[] = array(self::LINE, $color, $x1, $y1, $x2, $y2);

        return $this;
    }

    public function rectangle($x1, $y1, $x2, $y2, $color = null, $filled = false)
    {
        if (!$filled) {
            $this->__shapes[] = array(self::RECTANGLE, $color, $x1, $y1, $x2, $y2);
        } else {
            $this->__shapes[] = array(self::FILLED_RECTANGLE, $color, $x1, $y1, $x2, $y2);
        }

        return $this;
    }

    public function filledRectangle($x1, $y1, $x2, $y2, $color = null)
    {
        $this->__shapes[] = array(self::FILLED_RECTANGLE, $color, $x1, $y1, $x2, $y2);

        return $this;
    }

    public function ellipse($x1, $y1, $x2, $y2, $color = null, $filled = false)
    {
        $w = $x2 - $x1;
        $h = $y2 - $y1;
        if (!$filled) {
            $this->__shapes[] = array(self::ELLIPSE, $color, $x1, $y1, $w, $h);
        } else {
            $this->__shapes[] = array(self::FILLED_ELLIPSE, $color, $x1, $y1, $w, $h);
        }

        return $this;
    }

    public function filledEllipse($x1, $y1, $x2, $y2, $color = null)
    {
        $this->__shapes[] = array(self::FILLED_ELLIPSE, $color, $x1, $y1, $x2 - $x1, $y2 - $y1);

        return $this;
    }

    public function circle($x, $y, $radius, $color = null)
    {
        $this->__shapes[] = array(self::ELLIPSE, $color, $x, $y, $radius, $radius);

        return $this;
    }

    public function spiral($x, $y, $radius, $angle, $color = null)
    {
        $this->__shapes[] = array(self::SPIRAL, $color, $x, $y, $radius, $angle);

        return $this;
    }

    public function generate()
    {
        foreach ($this->__shapes as $shape) {
            $type = array_shift($shape);
            $color = array_shift($shape);

            $color = $this->_owner->imagecolorallocate((!isset($color) || is_null($color)) ?
                            $this->__base_color :
                            $color, $this->__base_alpha);

            switch ($type) {
                case self::LINE:
                    imageline($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::RECTANGLE:
                    imagerectangle($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::FILLED_RECTANGLE:
                    imagefilledrectangle($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::ELLIPSE:
                    imageellipse($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::FILLED_ELLIPSE:
                    imagefilledellipse($this->_owner->image, $shape[0], $shape[1], $shape[2], $shape[3], $color);
                    break;
                case self::SPIRAL:
                    $angle = $r = 0;
                    while ($r <= $shape[2]) {
                        imagearc($this->_owner->image, $shape[0], $shape[1], $r, $r, $angle - $shape[3], $angle, $color);
                        $angle += $shape[3];
                        $r++;
                    }
                    break;
            }
        }
        
        return true;
    }
}
