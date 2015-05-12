<?php

namespace Image\Reader;

use Image\Plugin\PluginAbstract;

abstract class ReaderAbstract extends PluginAbstract
{
    public $type_id = 'reader_adapter';
    protected $_supportGD;

    abstract public function getImage($filename);

    protected function _detectGD($key = '')
    {
        if (is_null($this->_supportGD)) {
            $gd_info = gd_info();

            $match = array();
            preg_match('/\d+/', $gd_info['GD Version'], $match);
            $this->_supportGD['Version'] = $match[0];
            $this->_supportGD['GIF'] = $gd_info['GIF Create Support'];
            $this->_supportGD['PNG'] = $gd_info['PNG Support'];
            $this->_supportGD['JPEG'] = (key_exists('JPG Support', $gd_info)) ? $gd_info['JPG Support'] : $gd_info['JPEG Support'];

            $this->_supportGD['TTF'] = $gd_info['FreeType Support'];
        }

        if (isset($this->_supportGD[$key])) {
            return $this->_supportGD[$key];
        }
    }
}
