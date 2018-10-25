<?
if ($TARGET_PAGE=="") { $TARGET_PAGE="step1"; }
$POST_ACTION="do_wps.php";
require("/www/do_wps_".$TARGET_PAGE.".php");
}
?>
