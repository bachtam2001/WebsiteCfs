<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config/Page_config.php');
require_once 'Facebook/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => $App_ID,
    'app_secret' => $App_Secrect,
    'default_graph_version' => 'v2.11'
    ]);

?>