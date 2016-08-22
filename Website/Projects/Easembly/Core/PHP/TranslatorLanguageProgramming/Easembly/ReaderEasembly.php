<?php
include 'CodeEasembly.php';
/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 11/16/15
 * Time: 08:12
 */
class ReaderEasembly{
    private static $instance;

    protected function __construct() {
    }

    public static function ReaderEasembly() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public function codeEasemblyWithTextEasembly($text,$file){
        $codeEasembly = CodeEasembly::CodeEasemblyWithText($text);
        return $codeEasembly->getAssembly($file);
    }

}