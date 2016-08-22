<?php
require_once('FacebookInitialization.php');

$helper = $fb->getJavaScriptHelper();
try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
//    echo 'Graph returned an error: ' . $e->getMessage();
//    exit;
    $accessToken = 'asfsadfasdf';
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
//    echo 'Facebook SDK returned an error: ' . $e->getMessage();
//    exit;
    $accessToken = 'asfsadfasdf';
}

if($accessToken=='asfsadfasdf'){
    include('FacebookInitialization.php');
    $helper = $fb->getJavaScriptHelper();
    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
//    echo 'Graph returned an error: ' . $e->getMessage();
//    exit;
        $accessToken = 'asfsadfasdf';
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
//    echo 'Facebook SDK returned an error: ' . $e->getMessage();
//    exit;
        $accessToken = 'asfsadfasdf';
    }
}

$fb->setDefaultAccessToken($accessToken);

try {
    $response = $fb->get('/me');
    $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
//    echo 'Graph returned an error: ' . $e->getMessage();
//    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
//    echo 'Facebook SDK returned an error: ' . $e->getMessage();
//    exit;
}

//echo 'Logged in as ' . $userNode->getId();
//
////echo 'c';

