<?php

class AutoLoader {

    public static function load($class) {
        $path = TEMPLATEPATH . "/custom/";
        $classPath = $class . ".php";
        if (is_file($path . $classPath)) {
            include_once $path . $classPath;
            return;
        }
    }

}
