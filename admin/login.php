<?php
if(!session_id()) {
    session_start();
}
$alt = "";
if ((isset($_SESSION['User_ID'])&&isset($_SESSION['User_Token']))) {
     $alt =  '<script language="javascript">window.location="https://admin.clacfs.tk/index.php";</script>';
}
include('config/Page_config.php');
require_once 'Facebook/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => $App_ID,
    'app_secret' => $App_Secrect,
    'default_graph_version' => 'v2.11'
    ]);
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLA Confessions and Memories | Login</title>
    <? echo $alt ?>;
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <img class="logo-name" src="<? $response = $fb->get('/'.$Page_ID.'/?fields=picture.width(200).height(200)', $App_Token); $graphNode = $response->getDecodedBody(); echo htmlspecialchars($graphNode["picture"]["data"]["url"]);?>"></img>
            </div>
            <h3>Welcome to <br>CLA Confessions and Memories Admin Panel</h3>
            <p>Đang nhập bằng tài khoản facebook có đủ quyền hạn để vào trang quản trị.
                <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
            </p>
            <p class="m-t"><a href="<?$permissions = [];$helper = $fb->getRedirectLoginHelper();$loginUrl = $helper->getLoginUrl('https://admin.clacfs.tk/callback.php', $permissions);echo htmlspecialchars($loginUrl);?>"><img src="img/facebook.png" width="300" height="50" ></img></a></p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
