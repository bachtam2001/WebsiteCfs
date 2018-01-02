<?
  require_once 'Facebook/autoload.php';
  require_once 'config/db.php';
  require 'config/Pages.php';
  if(!session_id()) {
    session_start();
  }
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  	$fb = new Facebook\Facebook([
		'app_id' => $App_ID,
		'app_secret' => $App_Secret,
		'default_graph_version' => 'v2.11'
	]); 
  $helper = $fb->getRedirectLoginHelper();
  $User_AccessToken = $helper->getAccessToken();
  if (! isset($User_AccessToken)) {
    if ($helper->getError()) {
      header('HTTP/1.0 401 Unauthorized');
      echo "Error: " . $helper->getError() . "\n";
      echo "Error Code: " . $helper->getErrorCode() . "\n";
      echo "Error Reason: " . $helper->getErrorReason() . "\n";
      echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
  exit;
  }
  $oAuth2Client = $fb->getOAuth2Client();
  $tokenMetadata = $oAuth2Client->debugToken($User_AccessToken);
  $tokenMetadata->validateAppId($App_ID); 
  $tokenMetadata->validateExpiration();
  if (! $User_AccessToken->isLongLived()) {
      $User_AccessToken = $oAuth2Client->getLongLivedAccessToken($User_AccessToken);
  }
  function Cfs_Process($CFS){
    GLOBAL $db_connect;
    $String_Array = explode("\n", $CFS["message"]);
    $CMC = substr(array_shift($String_Array), 4 , 4);
    $CMC_N = (int)$CMC;
    if (!($CMC_N)) return;
    $Message = implode("\n",$String_Array);
    $Message = addslashes($Message);
    $Time = substr(str_replace("T", " ", $CFS["created_time"]), 0 , 19);
    $query = "INSERT INTO `Content_Pages` (`ID`, `Content`, `Publish_Time`, `CMC`) VALUES ('".$CFS["id"]."','".$Message."','".$Time."','".$CMC."')";
    mysqli_query($db_connect,$query);
}
$After = "";
do{
    try {
      $response = $fb->get('/200693860353360/feed?pretty=0&limit=100&after='.$After,$User_AccessToken);
    } catch(FacebookExceptionsFacebookResponseException $e) {
       echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(FacebookExceptionsFacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    $graphNode = $response->getDecodedBody();
    if (isset($graphNode["data"])) $Data = $graphNode["data"];
    else break;
    for ($i = 0; $i < count($Data); $i++){
    if (isset($Data[$i]["message"])) {
     Cfs_Process($Data[$i]);
    };
    }
    if (isset($graphNode["paging"]["cursors"]["after"])) $After = $graphNode["paging"]["cursors"]["after"];
    else break;
} while ($Data != "" && $After != "") ;

?>
