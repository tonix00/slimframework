<?php
function my_autoloader($classname) {
    $filename = dirname(__FILE__)."/". $classname .".php";
    include_once($filename);
}
spl_autoload_register('my_autoloader');