<?php
    session_start();
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
       $params = session_get_cookie_params();
       setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
       );
    }
    session_destroy();
?>
<html>
   <head>
      <script type="text/javascript">window.location="https://admin.clacfs.tk/login.php"</script>
   </head>
   <body><center><img src="/img/loading.gif" width="50" height="50" /></center></body>
</html>