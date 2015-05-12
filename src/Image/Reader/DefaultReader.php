<?php

namespace Image\Reader;

class DefaultReader
{
    public function read($filename)
    {
        $image_data = getimagesize($filename);
        if ($image_data) {
            $class_name = __NAMESPACE__.'\\Adapter\\'.ucfirst(strtolower(image_type_to_extension($image_data[2], false)));

            if (class_exists($class_name)) {
                $adapter = new $class_name();

                return $adapter->getImage($filename);
            }
        } else {
            //getimagesize failed
            return false;
        }
    }
}
