<?php

namespace Image\Reader\Adapter;

use Image\Reader\ReaderAbstract;

class Png extends ReaderAbstract
{
    /**
     * Return an image resource from file or URL.
     *
     * @param string $filename
     *
     * @return resource an image resource identifier on success, false on errors.
     */
    public function getImage($filename)
    {
        if ($this->_detectGD('PNG') && function_exists('imagecreatefrompng')) {
            return imagecreatefrompng($filename);
        }

        return false;
    }
}
