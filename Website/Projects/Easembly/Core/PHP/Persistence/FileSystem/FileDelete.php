<?php
//include '../../facebook-sdk-v4-5.0-dev/JavaScriptFacebookLoginToPHP.php';
require_once('../../../../Core/PHP/facebook-sdk-v4-5.0-dev/JavaScriptFacebookLoginToPHP.php');

$facebookId='error';

if($userNode!=null) {
    $facebookId = $userNode->getId();
}else{
//    $facebookId='error';
}

$data = array();
$file = $_POST['data'];
$files = array();

$files = json_decode($file);

$name=$files[0];
$name = preg_replace('/\s+/', '', $name);

$facebookId = $files[1];

$fileParts = explode(".", $name);

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

$booleanDeleted=false;
if (file_exists($uploadFolder.$name)) {
    $booleanDeleted=unlink($uploadFolder.$name);
}

$data = array('success' => 'name:'.$uploadFolder.$name.'!'.' Deleted:'.$booleanDeleted.' !');
echo json_encode($data);