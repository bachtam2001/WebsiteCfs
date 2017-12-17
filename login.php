<?php
session_start();
require_once 'Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '2000874680198234',
  'app_secret' => '3da406ef420557fb0bba5adcad7c19d0',
  'default_graph_version' => 'v2.11',
  'persistent_data_handler'=>'session',
  'cookie' => true,
  'fileUpload' => true
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = [];
$loginUrl = $helper->getLoginUrl('https://clacfs.tk/fb-callback.php', $permissions);
echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>