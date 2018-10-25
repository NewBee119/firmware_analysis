<?
require("/www/model/__g_var.php");
if ($TARGET_PAGE=="")
{
	del($G_WIZ_PREFIX_WAN);
	set($G_WIZ_PREFIX_WAN."/password", $G_DEF_PASSWORD);
	set($G_WIZ_PREFIX_WAN."/pppoe/password", $G_DEF_PASSWORD);
	set($G_WIZ_PREFIX_WAN."/pptp/password", $G_DEF_PASSWORD);
	set($G_WIZ_PREFIX_WAN."/l2tp/password", $G_DEF_PASSWORD);
	$TARGET_PAGE="0_flowchart";
}
$POST_ACTION="wiz_wan.php";
require("/www/wiz_wan_".$TARGET_PAGE.".php");
?>
