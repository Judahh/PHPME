<?php
//include '../../facebook-sdk-v4-5.0-dev/JavaScriptFacebookLoginToPHP.php';
include '../../../../Core/PHP/TranslatorLanguageProgramming/Easembly/ReaderEasembly.php';
//require_once('../../../../Core/PHP/facebook-sdk-v4-5.0-dev/JavaScriptFacebookLoginToPHP.php');


$facebookId='error';

//if($userNode!=null) {
//    $facebookId = $userNode->getId();
//}else{
////    $facebookId='error';
//}

$data = array();
$file = $_POST['data'];
$files = array();

$files = json_decode($file);

$name=$files[0];
$fileMode = $files[3];
$name = preg_replace('/\s+/', '', $name);
$name = preg_replace("/[^A-Za-z0-9.]/", "", $name);
$fileParts = explode(".", $name);

if($files[4]!=null && (strcmp($files[4], "")!=0)) {
    $facebookId = $files[4];
}

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
    $newFileName=$name;
    $name=$name.".".$fileExtension;
}

$fileText=$files[1];
$compile=false;
$compile=$files[2];

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

if (!file_exists($uploadFolder)) {
    mkdir($uploadFolder, 0777, true);
}

$uploadFolder = $uploadFolder.'/';

$newFile = fopen($uploadFolder.$name, "w");// or die("Unable to open file!");
fwrite($newFile, $fileText);
fclose($newFile);

if(strcmp($fileExtension, "easy")==0) {
    $reader = ReaderEasembly::ReaderEasembly();
    $code8051 = $reader->codeEasemblyWithTextEasembly($fileText, "assembly_8051");
    $codeZ80 = $reader->codeEasemblyWithTextEasembly($fileText, "assembly_z80");

    $newFile = fopen($uploadFolder.'8051/'.$newFileName.".asm", "w");// or die("Unable to open file!");
    fwrite($newFile, $code8051);
    fclose($newFile);

    $newFile = fopen($uploadFolder.'Z80/'.$newFileName.".z80", "w");// or die("Unable to open file!");
    fwrite($newFile, $codeZ80);
    fclose($newFile);
}

if($compile){
    $output = shell_exec('sdas8051 -o'.$uploadFolder.'8051/'.$newFileName.'.asm');//This function is disabled when PHP is running in safe mode.
    $output = shell_exec('sdasz80 -o'.$uploadFolder.'Z80/'.$newFileName.'.z80');//This function is disabled when PHP is running in safe mode.
}

$data = array('success' => 'facebookID:'.$facebookId.' name:'.$uploadFolder.$name.' fileMode:'.$fileMode.' !'.' file:'.$fileText.' !'.' Assembly:'.$code8051.' !');
echo json_encode($data);