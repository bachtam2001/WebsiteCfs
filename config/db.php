
<?

$db_host = "localhost"; //Mặc đinh localhost
$db_name = "clacfstk_userDB"; //Tên database
$db_user = "clacfstk_admin"; //Tên đăng nhập MySQL
$db_pass = "Bachtam2001"; //Password đăng nhập MySQL

$db_connect = mysqli_connect($db_host, $db_user, $db_pass) or die("Can't connect to MySQL"); 

mysqli_select_db($db_connect , $db_name) or die("Can't select database ".$db_name);
mysqli_set_charset($db_connect, 'UTF8');

?>

