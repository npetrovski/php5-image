<?php

namespace Image\Fx;

use Image\Plugin\PluginAbstract;

abstract class FxBase extends PluginAbstract
{
    public static function factory($name, $args)
    {
        $className = __NAMESPACE__.'\\'.ucfirst(strtolower($name));

        if (class_exists($className)) {
            $obj = new \ReflectionClass($className);

            return $obj->newInstanceArgs($args);
        }

        return false;
    }
}
