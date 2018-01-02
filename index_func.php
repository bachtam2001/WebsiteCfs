<?

	require_once 'Facebook/autoload.php';
	require_once 'config/db.php';
	require_once 'config/Pages.php';  
function login_check() {
		if (!isset($_SESSION['fb_User_ID'])) return true;
		else return false;;
}
function get_login_url(){
$fb = new Facebook\Facebook([
  'app_id' => '2000874680198234',
  'app_secret' => '3da406ef420557fb0bba5adcad7c19d0',
  'default_graph_version' => 'v2.11',
]);
  $permissions = [];
  $helper = $fb->getRedirectLoginHelper();
  $loginUrl = $helper->getLoginUrl('https://clacfs.tk/', $permissions);
  return $loginUrl;
}
function get_first_user_token() {
	$fb = new Facebook\Facebook([
	'app_id' => '2000874680198234',
	'app_secret' => '3da406ef420557fb0bba5adcad7c19d0',
	'default_graph_version' => 'v2.11',
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
	    try {
	      $User_AccessToken = $oAuth2Client->getLongLivedAccessToken($User_AccessToken);
	    } catch (Facebook\Exceptions\FacebookSDKException $e) {
	      echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
	      exit;
	    }
	  }
	  return $User_AccessToken;
	}

function check_reg_user($User_ID) { 
  	$sqlquery = "select * from User where UserID=`$User_ID`";
	$query = mysqli_query($db_connect, $sqlquery);
	if(mysqli_num_rows($query) == 0) return false;
	else return true;
}

function get_user_token($User_ID) { 
  	$sqlquery = "select `UserToken` from User where UserID=`$User_ID`";
	$query = mysqli_query($db_connect, $sqlquery);
	$User_DB = mysqli_fetch_assoc($query);
	return $User_DB["UserToken"];
}

 function get_username_and_login($User_ID,$User_AccessToken) {
 		$sqlquery = "select `UserName` from User where UserID=`$User_ID`";
		$query = mysqli_query($db_connect, $sqlquery);
        $User_DB = mysqli_fetch_assoc($query);
        $User_Name = $User_DB['UserName'];
        $sqlquery = "UPDATE `User` SET `UserToken`='".$User_AccessToken."' WHERE UserID='".$User_ID."'";
        $query = mysqli_query($db_connect, $sqlquery);
        if (!$query) {
          return NULL;
        }
        else {
    	  $_SESSION['fb_User_ID'] = $User_ID;
          return $User_Name;
        }
}
function reg_user($User_ID,$User_AccessToken) {
	$fb = new Facebook\Facebook([
	'app_id' => '2000874680198234',
	'app_secret' => '3da406ef420557fb0bba5adcad7c19d0',
	'default_graph_version' => 'v2.11',
	]); 
	$helper = $fb->getRedirectLoginHelper();
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
        $sqlquery= "INSERT INTO `User`(`UserID`, `UserToken`, `UserName`, `UserPer`, `RegDate`) VALUES (`$User_ID`, `$User_AccessToken`, `$User_Name`, `$User_Per`, `$Reg_date`)";
        $query=mysqli_query($db_connect, $sqlquery);
        if (!$query) {
          return false;
        }
        else
        {
          return true;
        }
}

?>

