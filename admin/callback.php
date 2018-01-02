<?
if(!session_id()) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config/Page_config.php';
require_once 'Facebook/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => $App_ID,
    'app_secret' => $App_Secrect,
    'default_graph_version' => 'v2.11'
    ]);

$helper = $fb->getRedirectLoginHelper();
$accessToken = $helper->getAccessToken();
if (! isset($accessToken)) {
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
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId($App_ID);
$tokenMetadata->validateExpiration();
if (! $accessToken->isLongLived()) {
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }
}
$User_ID = $tokenMetadata->getUserId();
$User_Token = $accessToken->getValue();
try {
  $Get_Data = $fb->get(''.$User_ID.'/accounts', $accessToken);
}
catch(FacebookExceptionsFacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(FacebookExceptionsFacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$Data = $Get_Data->getDecodedBody();
$Perm_check = false;
for ($Count_Pages = 0; $Count_Pages < sizeof($Data["data"]); $Count_Pages++) {
	if ($Data["data"][$Count_Pages]["id"] == $Page_ID) {
		for ($Count_Perm = 0; $Count_Perm < sizeof($Data["data"][$Count_Pages]["perms"]); $Count_Perm++) {
			if ($Data["data"][$Count_Pages]["perms"][$Count_Perm] == "CREATE_CONTENT")  {
        $Perm_check = true;
        $Page_Token = $Data["data"][$Count_Pages]["access_token"];
		    break;break;
      }
    }
	}
}
$response = $fb->get('/'.$User_ID.'/?fields=name', $accessToken); $graphNode = $response->getDecodedBody(); $User_Name = $graphNode["name"];
if ($Perm_check) {
  try {
     $Get_Data = $fb->get(''.$Page_ID.'/roles', $Page_Token);
  }
  catch(FacebookExceptionsFacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
  } catch(FacebookExceptionsFacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
  }
  $Data = $Get_Data->getDecodedBody();
  for ($Count_Pages = 0; $Count_Pages < sizeof($Data["data"]); $Count_Pages++) {
  if ($Data["data"][$Count_Pages]["id"] == $User_ID) {
      $User_Perm = $Data["data"][$Count_Pages]["role"];
      break;
    }
  }
	$_SESSION['User_ID'] = $User_ID;
  $_SESSION['User_Token'] = $User_Token;  
  $_SESSION['User_Name'] = $User_Name;  
  $_SESSION['User_Perm'] = $User_Perm;
  $_SESSION['Page_Token'] = $Page_Token;
	 $alt =  '<script language="javascript">alert("Đăng nhập thành công!");window.location="https://admin.clacfs.tk/index.php";</script>';
} else {
	      $alt = '<script language="javascript">alert("Tài khoản không có đủ quyền truy cập vào trang!\nVui lòng đăng nhập lại bằng tài khoản khác có đủ quyền truy cập!");window.location="https://admin.clacfs.tk/login.php";</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Processing</title>
	<? echo $alt ?>
</head>
<body>
<center><img src="img/loading.gif" width="50" height="50"></img></center>
</body>
</html>