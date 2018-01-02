<? 
session_start();
require'Facebook/autoload.php'; 
  function get_login_url(){
    $fb = new Facebook\Facebook([
    'app_id' => '2000874680198234',
    'app_secret' => '3da406ef420557fb0bba5adcad7c19d0',
    'default_graph_version' => 'v2.11',
    ]);
    $permissions = [];
    $helper = $fb->getRedirectLoginHelper();
    $loginUrl = $helper->getLoginUrl('https://clacfs.tk/login.php', $permissions);
    return $loginUrl;
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>CLA Confessions and Memories</title>
	<meta charset="utf-8">
</head>
<body>
	<? if (($_SESSION['fb_User_ID'])==NULL) {
		$login_url = get_login_url();
		echo "<button><a href='" .$login_url."'>Log in with Facebook!</a></button>";  
	} else {
		echo "Chào ".$_SESSION['fb_User_Name'];
	}
?>
</body>
</html>

