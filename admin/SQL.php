<?
if(!session_id()) {
    session_start();
}
  require_once 'Facebook/autoload.php';
  require_once 'config/db.php';
  require 'config/Page_config.php';
 	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$fb = new Facebook\Facebook([
	'app_id' => $App_ID,
	'app_secret' => $App_Secrect,
	'default_graph_version' => 'v2.11'
]); 
$Data = "1";
$After = "";
try {
  $response = $fb->get('/200693860353360/feed?limit=100&after='.$After,'EAACEdEose0cBAEq3b60aR5JkFoJ9AqF8sQCmQIZAlyOShDZBQ7HGXaTUsWEpJSXATBFZBSI9x3H1laltZBSZB3ZBloAr6bdQGveewZABQtGqGh9hF5YccRlZBAJaZATcD3tP2C13qOR3DPdTZAPBJJZCPLYpHUB864JZCV5Qrj1jWRNXEKTIZBZBAWjGZBawroXhqMziHQZD');
} catch(FacebookExceptionsFacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(FacebookExceptionsFacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$graphNode = $response->getGraphNode();
var_dump($graphNode);
;


?>