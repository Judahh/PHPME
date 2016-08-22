<?php

/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 11/2/15
 * Time: 16:26
 */
class FileSystem{
    private static $instance;

    protected function __construct() {
    }

    //$multilingualText = MultilingualText::MultilingualText();

    public static function FileSystem() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public function deleteFile($uploadFolder, $name){
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $uploadFolder = $uploadFolder.'/';

        $booleanDeleted=false;
        if (file_exists($uploadFolder.$name)) {
            $booleanDeleted=unlink($uploadFolder.$name);
        }
        return $booleanDeleted;
    }

    public function uploadFile($uploadFolder, $name, $fileText){
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $uploadFolder = $uploadFolder.'/';

        $newFile = fopen($uploadFolder.$name, "w");// or die("Unable to open file!");
        fwrite($newFile, $fileText);
        fclose($newFile);
        return $uploadFolder.$name;
    }

    public function getUploadFolder($fileExtension, $facebookId){
        switch ($fileExtension) {
            case "asm":
                $uploadFolder = '../../../../User/'.$facebookId.'/'.'8051';
                break;
            case "z80":
                $uploadFolder = '../../../../User/'.$facebookId.'/'.'Z80';
                break;
            default:
                $uploadFolder = '../../../../User/'.$facebookId;
        }
        return $uploadFolder;
    }

    public function getFileParts($name){
        return explode(".", $name);
    }

    public function getFullFileName($fileParts, $name, $fileExtension){
        $newFileName="";
        if(count($fileParts)>1) {
            for ($index = 0; $index < count($fileParts) - 1; $index++) {
                if ($index == 0) {
                    $newFileName = $fileParts[$index];
                } else {
                    $newFileName = $newFileName . "." . $fileParts[$index];
                }
            }
            $name=$newFileName.".".$fileExtension;
        }else{
            $name=$name.".".$fileExtension;
        }
        return $name;
    }

    public function getFileExtension($fileParts, $fileMode){
        if($fileMode==null){
            $fileMode="";
        }

        switch($fileMode) {
            case "":
                if(count($fileParts)>1) {
                    $fileExtension = $fileParts[count($fileParts) - 1];
                    switch ($fileExtension) {
                        case "asm":
                            break;
                        case "z80":
                            break;
                        default:
                            $fileExtension = "easy";
                    }
                }else{
                    $fileExtension = "easy";
                }
                break;
            case "assembly_8051":
                $fileExtension = "asm";
                break;
            case "assembly_z80":
                $fileExtension = "z80";
                break;
            default:
                $fileExtension = "easy";
        }
        return $fileExtension;
    }

    public function checkFileName($name){
        $name = preg_replace('/\s+/', '', $name);
        return $name;
    }

    public function checkNewFileName($name){
        $name = preg_replace('/\s+/', '', $name);
        $name = preg_replace("/[^A-Za-z0-9.]/", "", $name);
        return $name;
    }

}