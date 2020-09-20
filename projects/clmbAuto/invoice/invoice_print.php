<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/invoice/Invoice.php";
include_once $sysPath."/invoice/InvoiceTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/fldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/order.php";
include_once $projPath."/dbControler/invoice.php";
include_once $projPath."/dbControler/shared.php";

/*include_once "../library.php";
include_once "header_item.php";
*/
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
$headerSect .= '<body onload="window.print();">';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';


$inv = new Invoice($link,$userInfo,$id);
$headerSect .= $inv->getInvoiceDetails(true);
echo $headerSect;
?>