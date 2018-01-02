<?php
if(!session_id()) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
$alt = "";
if (!(isset($_SESSION['User_ID'])&&isset($_SESSION['User_Token']))) {
     $alt =  '<script language="javascript">alert("Vui lòng đăng nhập để tiếp tục!");window.location="https://admin.clacfs.tk/login.php";</script>';
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

    <title>CLA Confessions and Memories Admin Panel | Confessions Tables </title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="<?$response = $fb->get('/'.$_SESSION['User_ID'].'/?fields=picture', $_SESSION['User_Token']); $graphNode = $response->getDecodedBody(); echo htmlspecialchars($graphNode["picture"]["data"]["url"]);?>" />
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?echo $_SESSION['User_Name'];?></strong>
                             </span> <span class="text-muted text-xs block"><?echo $_SESSION['User_Perm'];?></span> </span> </a>
                    </div>
                    <div class="logo-element">
                        CLA
                    </div>
                </li>
                <li class="active">
                    <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Bảng điều khiển</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="index.php">Thống kê</a></li>
                        <li><a href="form_tables.php">Duyệt Confessions</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome <? echo $_SESSION['User_Name'];?> CLA Confessions and Memories Admin Panel</span>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>
            <div class="row wrapper border-CLA Confessions and Memories Admin Panel | Dashboard bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Data Tables</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Tables</a>
                        </li>
                        <li class="active">
                            <strong>Data Tables</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Những Confessions đã nhận. </h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive cfstables" >
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Send_Time</th>
                        <th>Messages</th>
                        <th>Publish_ID</th>
                        <th>Publish_Time</th> 
                        <th>Publish_By</th>
                        <th>CMC</th>
                    </tr>
                    </thead> 
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Send_Time</th>
                        <th>Messages</th>
                        <th>Publish_ID</th>
                        <th>Publish_Time</th> 
                        <th>Publish_By</th>
                        <th>CMC</th>
                    </tr>
                    </tfoot>
                    </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> CLA Confessions and Memories&copy; 2018
            </div>
        </div>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.cfstables').DataTable({
            	order: [[ 2, "desc" ]],
           		columns: [
				{ "width": "3%" },
				{ "width": "10%" },
				{ "width": "30%" },	
				{ "width": "15%" },
				{ "width": "10%" },
				{ "width": "5%" },
				{ "width": "3%" }
				],
                pageLength: 30,
                responsive: true,
                ajax: "getcfs.php",
                dom: 'lTfgitp',
                buttons: [
                    {
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]
            });
        });

    </script>

</body>

</html>
