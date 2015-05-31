<?php

namespace Image;

use Image\Plugin\PluginInterface;
use Image\Helper\Color;
use Image\Reader\DefaultReader;
use Image\Fx\FxBase;
use Image\Draw\DrawBase;
use Image\Helper\HelperBase;
use Image\Exception as ImageException;

class Canvas {

    /**
     * Black PNG.
     *
     * @var string
     */
    private static $_blackpng = <<<BLACKPNG
iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m
dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBNCg
dyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAAN
egcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQ
oHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAA
DXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII=
BLACKPNG;

    /**
     * Image resource.
     *
     * @var resource
     */
    public $image;

    /**
     * Set as false to use the top left corner as the handle.
     *
     * @var bool
     */
    public $mid_handle = true;

    /**
     * Image properties.
     *
     * @var array
     */
    protected $_properties = array();

    /**
     * Plugins stack.
     *
     * @var array
     */
    protected $_plugins = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $args = func_get_args();

        switch (count($args)) {
            case 1:
                if (!empty($args[0]) && is_string($args[0])) {
                    $this->openImage($args[0]);
                }
                break;
            case 2:
            case 3:
            case 4:
                $this->createImageTrueColor(intval($args[0]), intval($args[1]), (isset($args[2]) ? strval($args[2]) : 'FFFFFF'), (isset($args[3]) ? intval($args[3]) : null));
                break;
        }
    }

    /**
     * Plugins access.
     */
    public function __call($name, $arguments) {
        foreach ($this->_plugins as $row) {
            if ($row['plugin'] instanceof PluginInterface && method_exists($row['plugin'], $name)) {
                return call_user_func_array(array($row['plugin'], $name), $arguments);
            }
        }
    }

    /**
     * Attach image plugin.
     *
     * @param \Image\Plugin\PluginInterface $plugin
     *
     * @return string
     */
    public function attach(PluginInterface $plugin) {
        $id = spl_object_hash($plugin);
        $plugin->attachToOwner($this);

        $this->_plugins[$id] = array('plugin' => $plugin, 'applied' => false);

        return $id;
    }

    /**
     * Attach image effect.
     *
     * @return \Image\Canvas
     *
     * @throws ImageException
     */
    public function fx() {
        $args = func_get_args();
        $obj = FxBase::factory(array_shift($args), $args);
        if (!($obj instanceof PluginInterface)) {
            throw new ImageException('Invalid fx class');
        }
        $this->attach($obj);

        return $this;
    }

    /**
     * Attach image drawing.
     *
     * @return \Image\Canvas
     *
     * @throws ImageException
     */
    public function draw() {
        $args = func_get_args();
        $obj = DrawBase::factory(array_shift($args), $args);
        if (!($obj instanceof PluginInterface)) {
            throw new ImageException('Invalid draw class');
        }
        $this->attach($obj);

        return $this;
    }

    /**
     * Attach image helper.
     *
     * @return \Image\Canvas
     *
     * @throws ImageException
     */
    public function helper() {
        $args = func_get_args();
        $obj = HelperBase::factory(array_shift($args), $args);
        if (!($obj instanceof PluginInterface)) {
            throw new ImageException('Invalid helper class');
        }
        $this->attach($obj);

        return $this;
    }

    /**
     * Apply plugin changes.
     *
     * @return \Image\Canvas
     */
    public function apply() {
        foreach ($this->_plugins as $id => $row) {
            if ($row['plugin'] instanceof PluginInterface && !$row['applied']) {
                $row['plugin']->generate();
                $this->_plugins[$id]['applied'] = true;
            }
        }

        return $this;
    }

    /**
     * Create an empty image.
     *
     * @param int    $width
     * @param int    $height
     * @param string $color
     *
     * @return \Image\Canvas
     */
    public function createImage($width = 100, $height = 100, $color = 'FFFFFF') {
        $this->image = imagecreate($width, $height);
        if (!empty($color)) {
            $this->imagefill(0, 0, $color);
        }

        return $this;
    }

    /**
     * Create an empty image (true color).
     *
     * @param int    $width
     * @param int    $height
     * @param string $color
     *
     * @return \Image\Canvas
     */
    public function createImageTrueColor($width = 100, $height = 100, $color = 'FFFFFF', $alpha = null) {
        $this->image = imagecreatetruecolor($width, $height);
        if (!empty($color)) {
            $this->imagefill(0, 0, $color, $alpha);
        }

        return $this;
    }

    /**
     * Create an image (color transparent).
     *
     * @param int $x
     * @param int $y
     *
     * @return \Image\Canvas
     */
    public function createImageTrueColorTransparent($x = 100, $y = 100) {
        $this->image = imagecreatetruecolor($x, $y);
        $blank = imagecreatefromstring(base64_decode(self::$_blackpng));
        imagesavealpha($this->image, true);
        imagealphablending($this->image, false);
        imagecopyresized($this->image, $blank, 0, 0, 0, 0, $x, $y, imagesx($blank), imagesy($blank));
        imagedestroy($blank);

        return $this;
    }

    /**
     * Open image from file.
     *
     * @param string $filename
     *
     * @return \Image\Canvas
     *
     * @throws ImageException
     */
    public function openImage($filename) {
        if ($filename && file_exists($filename)) {
            $reader = new DefaultReader();

            $this->image = $reader->read($filename);

            if ('resource' != gettype($this->image)) {
                unset($this->image);
                throw new ImageException('File is not an image');
            }

            $this->_getFileInfo($filename);
        } else {
            throw new ImageException('Image file does not exist');
        }

        return $this;
    }

    /**
     * Outputs an image.
     *
     * @param string $type
     * @param string $filename
     *
     * @return binary
     */
    public function printImage($type, $filename = '') {
        $this->apply();

        $gd_function = 'image' . strtolower($type);
        if (function_exists($gd_function)) {
            if (!empty($filename)) {
                return call_user_func($gd_function, $this->image, $filename);
            } else {
                header('Content-Type: ' . image_type_to_mime_type(constant('IMAGETYPE_' . strtoupper($type))));

                return call_user_func($gd_function, $this->image);
            }
        } else {
            throw new ImageException('Image type \'' . $type . '\' is not supported.');
        }
    }

    /**
     * Outputs a PNG image.
     *
     * @param string $filename
     *
     * @return binary
     */
    public function imagePng($filename = '') {
        return $this->printImage('png', $filename);
    }

    /**
     * Outputs a JPG image.
     *
     * @param string $filename
     *
     * @return binary
     */
    public function imageJpeg($filename = '') {
        return $this->printImage('jpeg', $filename);
    }

    /**
     * Outputs a WBMP image.
     *
     * @param string $filename
     *
     * @return binary
     */
    public function imageWbmp($filename = '') {
        return $this->printImage('wbmp', $filename);
    }

    /**
     * Outputs a GIF image.
     *
     * @param string $filename
     *
     * @return binary
     */
    public function imageGif($filename = '') {
        return $this->printImage('gif', $filename);
    }

    /**
     * Destroy image.
     *
     * @return \Image\Canvas
     */
    public function destroyImage() {
        imagedestroy($this->image);
        unset($this->image);

        return $this;
    }

    /**
     * Get image width.
     *
     * @return int
     */
    public function getImageWidth() {
        return imagesx($this->image);
    }

    /**
     * Get image height.
     *
     * @return int
     */
    public function getImageHeight() {
        return imagesy($this->image);
    }

    /**
     * Finds whether an image is a truecolor image.
     *
     * @return bool
     */
    public function imageIsTrueColor() {
        return imageistruecolor($this->image);
    }

    /**
     * Get the index of the color of a pixel.
     *
     * @param int $x
     * @param int $y
     *
     * @return int
     */
    public function imageColorAt($x = 0, $y = 0) {
        $color = imagecolorat($this->image, $x, $y);
        if (!$this->imageIsTrueColor()) {
            $arrColor = imagecolorsforindex($this->image, $color);

            return Color::arrayColorToIntColor($arrColor);
        } else {
            return $color;
        }
    }

    /**
     * Flood fill.
     *
     * @param int    $x
     * @param int    $y
     * @param string $color
     * @param int    $alpha
     *
     * @return \Image\Canvas
     */
    public function imagefill($x = 0, $y = 0, $color = 'FFFFFF', $alpha = null) {
        imagefill($this->image, $x, $y, $this->imagecolorallocate($color, $alpha));

        return $this;
    }

    /**
     * Allocate a color for an image.
     *
     * @param string $color
     * @param int    $alpha
     *
     * @return int
     */
    public function imagecolorallocate($color = 'FFFFFF', $alpha = null) {
        if (is_string($color)) {
            $arrColor = Color::hexColorToArrayColor($color);
        } elseif (is_int($color)) {
            $arrColor = Color::intColorToArrayColor($color);
            $alpha = $arrColor['alpha'];
        }

        if ($alpha) {
            return imagecolorallocate($this->image, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
        } else {
            return imagecolorallocatealpha($this->image, $arrColor['red'], $arrColor['green'], $arrColor['blue'], intval($alpha));
        }
    }

    /**
     * Displace image.
     *
     * @param array $map
     *
     * @return \Image\Canvas
     */
    public function displace($map) {
        $width = $this->getImageWidth();
        $height = $this->getImageHeight();
        $temp = new self($width, $height);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if (isset($map['x'][$x][$y]) && isset($map['y'][$x][$y])) {
                    $rgb = $this->imageColorAt($map['x'][$x][$y], $map['y'][$x][$y]);
                    imagesetpixel($temp->image, $x, $y, $this->imagecolorallocate($rgb));
                }
            }
        }
        $this->image = $temp->image;

        return $this;
    }

    /**
     * Gets an image width middle position.
     *
     * @return int
     */
    public function getHandleX() {
        return ($this->mid_handle == true) ? floor($this->getImageWidth() / 2) : 0;
    }

    /**
     * Gets an image height middle position.
     *
     * @return int
     */
    public function getHandleY() {
        return ($this->mid_handle == true) ? floor($this->getImageHeight() / 2) : 0;
    }

    /**
     * Gets image meta data.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getProperty($key) {
        return isset($this->_properties[$key]) ? $this->_properties[$key] : null;
    }

    /**
     * Gets file information.
     *
     * @param string $filename
     * @param int    $round
     */
    private function _getFileInfo($filename, $round = 2) {
        $ext = array('B', 'KB', 'MB', 'GB');

        $this->_properties['filepath'] = $filename;
        $this->_properties['filename'] = basename($filename);
        $this->_properties['filesize_bytes'] = filesize($filename);
        $size = $this->_properties['filesize_bytes'];
        for ($i = 0; $size > 1024 && $i < count($ext) - 1; $i++) {
            $size /= 1024;
        }
        $this->_properties['filesize_formatted'] = round($size, $round) . $ext[$i];
        $this->_properties['original_width'] = $this->getImageWidth();
        $this->_properties['original_height'] = $this->getImageHeight();
    }

    /**
     * Cloning an image.
     */
    public function __clone() {
        $original = $this->image;
        $copy = imagecreatetruecolor($this->getImageWidth(), $this->getImageHeight());
        imagealphablending($copy, false);
        imagesavealpha($copy, true);

        imagecopy($copy, $original, 0, 0, 0, 0, $this->getImageWidth(), $this->getImageHeight());

        $this->image = $copy;
    }

}
