<?
if ($TARGET_PAGE=="")
{
	require("/www/model/__g_var.php");
	del($G_WIZ_PREFIX_WLAN);
	$TARGET_PAGE="0_flowchart";
}
$POST_ACTION="wiz_wlan.php";
require("/www/wiz_wlan_".$TARGET_PAGE.".php");
?>
