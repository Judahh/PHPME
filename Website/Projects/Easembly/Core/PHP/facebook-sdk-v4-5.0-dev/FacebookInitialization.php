<?php
require_once ('src/Facebook/autoload.php');
//define('FACEBOOK_SDK_V4_SRC_DIR', 'src/Facebook/');
//require __DIR__ . 'autoload.php';
//use Facebook\FacebookSession;
// add other classes you plan to use, e.g.:
// use Facebook\FacebookRequest;
// use Facebook\GraphUser;
// use Facebook\FacebookRequestException;

session_start();
require_once( 'src/Facebook/FacebookRequest.php' );
require_once( 'src/Facebook/FacebookResponse.php' );

use Facebook\FacebookRequest;
use Facebook\FacebookResponse;

$appId='974238655956097';
$secret='ac17ffd588c8c6d94fd63e0c52e21608';
$fb = new Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $secret,
    'default_graph_version' => 'v2.2',
]);