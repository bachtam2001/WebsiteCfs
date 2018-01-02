<?
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require_once 'Facebook/autoload.php';
  require_once 'config/db.php';
  require_once 'config/Pages.php';  
  $fb = new Facebook\Facebook([
    'app_id' => '2000874680198234',
    'app_secret' => '3da406ef420557fb0bba5adcad7c19d0',
    'default_graph_version' => 'v2.11',
    'persistent_data_handler'=>'session',
    'cookie' => true,
    'fileUpload' => true
    ]); 

  $helper = $fb->getRedirectLoginHelper();
  try {
    $User_AccessToken = $helper->getAccessToken();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();      
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
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
  $tokenMetadata->validateAppId('2000874680198234'); 
  $tokenMetadata->validateExpiration();
  if (! $User_AccessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
      $User_AccessToken = $oAuth2Client->getLongLivedAccessToken($User_AccessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
      echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
      exit;
    }
  }
  $User_ID = $tokenMetadata->getUserId();
  $sqlquery = "select * from User where UserID='".$User_ID."'";
  $query = mysqli_query($db_connect, $sqlquery);
  if(mysqli_num_rows($query) == 0) {
        try {
            $response = $fb->get('/'.$Page_ID.'/roles/'.$User_ID.'/', $Page_accessToken);
          } catch(FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(FacebookExceptionsFacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode=$response->getDecodedBody();
        if ($graphNode["data"]!=NULL) {
            $User_Per = $graphNode["data"][0]["role"];
        }
        else $User_Per="User";
        try {
          $response = $fb->get('/'.$User_ID.'/', $Page_accessToken);
        } catch(FacebookExceptionsFacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(FacebookExceptionsFacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        $graphNode=$response->getDecodedBody();
        $User_Name = $graphNode["name"];
        $Reg_date = time();
        $sqlquery= "INSERT INTO `User`(`UserID`, `UserToken`, `UserName`, `UserPer`, `RegDate`) VALUES ('".$User_ID."','".$User_AccessToken."','".$User_Name."','".$User_Per."','".$Reg_date."')";
        $query=mysqli_query($db_connect, $sqlquery);
        if (!$query) {
          echo "Không thể đăng kí người dùng vào hệ thống!"."<p>\n"."Vui lòng thử lại sau ít phút!";
        }
        else
        {
          echo "Đăng kí thành công!";
        }
  }
  else {
        $User_DB = mysqli_fetch_assoc($query);
        $User_Name = $User_DB['UserName'];
        $sqlquery = "UPDATE `User` SET `UserToken`='".$User_AccessToken."' WHERE UserID='".$User_ID."'";
        $query = mysqli_query($db_connect, $sqlquery);
        if (!$query) {
          echo "Không thể đăng nhập vào hệ thống!"."<p>\n"."Vui lòng thử lại sau ít phút!";
        }
        else
        {
          echo "Đăng nhập thành công!"."<p>\n"."Chào ".$User_Name;
        }
  }
  $_SESSION['fb_User_ID'] = (string) $User_ID;
  <meta http-equiv=’refresh’ content=’0; url=”http://$_SERVER%5B‘HTTP_HOST’%5D/welcome2.php”/&gt;
  ?>