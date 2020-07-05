<?php
include_once "../path.php";
include_once "library.php";
include_once "header_item.php";

$mainMenueArr = getMainMenueArray();
$menueIcon = getHeaderMenueIcons();
$subMenueArr = getSubMenueArray();
$leftMenue = getLeftMainMenue($mainMenueArr,$subMenueArr,$menueIcon);
$headerProfile = getHeaderProfileSect($userInfo);

$headerSect = '
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>'.TITLE.'</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">';

$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap/dist/css/bootstrap.min.css">';

$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/font-awesome/css/font-awesome.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/Ionicons/css/ionicons.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'dist/css/AdminLTE.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'dist/css/skins/_all-skins.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/morris.js/morris.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/jvectormap/jquery-jvectormap.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap-daterangepicker/daterangepicker.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'dist/css/font-awesome-animation.min.css">';
$headerSect .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap/dist/css/less/modals.less">';

$headerSect .= '<meta name="viewport" content="width=device-width, user-scalable=no" />';
$headerSect .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">';
$headerSect .= '<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>';
$headerSect .= '<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>';

$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/jquery/dist/jquery.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/jquery-ui/jquery-ui.min.js"></script>';
$headerSect .= '<script>$.widget.bridge(\'uibutton\', $.ui.button);</script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap/dist/js/bootstrap.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/raphael/raphael.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/morris.js/morris.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/jquery-knob/dist/jquery.knob.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/moment/min/moment.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/chart.js/Chart.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'bower_components/fastclick/lib/fastclick.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'dist/js/adminlte.min.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'dist/js/pages/dashboard.js"></script>';
$headerSect .= '<script src="http://'.ADMIN_STYLE_ROOT.'dist/js/demo.js"></script>';

if(!empty($jsFiles)){
    foreach($jsFiles as $jsFile){
        $headerSect .= '<script src="http://'.$jsFile.'"></script>';
    }
}

if(!empty($csFiles)){
    foreach($csFiles as $csFile){
        $headerSect .= '<link rel="stylesheet" href="http://'.$csFile.'">';
    }
}
//============sortable Table Script========================\\
$headerSect .= '<script type="text/javascript" src="/webtoolkit.sortabletable.js"></script>';
$headerSect .= '<script type="text/javascript">
        window.onload = function() {
            var t = new SortableTable(document.getElementById(\'sortableTable\'), 100);
        };
    </script>';
//========================End===============================\
$headerSect .= '</head>';

$headerSect .= '<body class="hold-transition skin-blue sidebar-mini">';
$headerSect .= '<div class="wrapper">';
    $headerSect .= '<header class="main-header">';
        $headerSect .= '<a href="index2.html" class="logo">';
            $headerSect .= '<span class="logo-mini">'.SYS_SHORT_NAME.'</span>';
            $headerSect .= '<span class="logo-lg">'.SYS_NAME.'</span>';
         $headerSect .= '</a>';
        $headerSect .= '<nav class="navbar navbar-static-top">';
            $headerSect .= '<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">Toggle navigation</span></a>';
            $headerSect .= '<div class="navbar-custom-menu">';
                $headerSect .= '<ul class="nav navbar-nav">';
                    $headerSect .= $headerProfile;
                $headerSect .= '</ul>';
            $headerSect .= '</div>';
        $headerSect .= '</nav>';
    $headerSect .= '</header>';
    $headerSect .= '<aside class="main-sidebar">';
        $headerSect .= $leftMenue;
    $headerSect .= '</aside>';
    //---Start Page Content---\\
    $headerSect .= '<div class="content-wrapper" style="min-height: 926.3px; padding-top: 10px;">';

echo $headerSect;


?>