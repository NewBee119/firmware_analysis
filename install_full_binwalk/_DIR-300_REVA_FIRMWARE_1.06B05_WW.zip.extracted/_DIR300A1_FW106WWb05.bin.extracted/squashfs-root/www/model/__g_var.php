<?
if ($__GLOBAL_VARIABLE_REQUIRED != "true")
{
	$__GLOBAL_VARIABLE_REQUIRED = "true";

	$G_WIZ_PREFIX_WAN	="/tmp/wiz/wan";
	$G_WIZ_PREFIX_WLAN	="/tmp/wiz/wlan";

	// about page url
	$G_HOME_PAGE		="bsc_internet";
	$G_SAVING_URL		="/www/model/__saving.php";
	$G_NO_CHANGED_URL	="/www/model/__no_changed.php";
	// -----------------------------------------------------------------------
	
	// about table attribute.
	$G_BODY_ATTR="topmargin=\"1\" leftmargin=\"0\" rightmargin=\"0\" bgcolor=\"#757575\"";
	$G_MAIN_TABLE_ATTR="border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=\"838\"".
		" align=\"center\" bgcolor=\"#FFFFFF\" bordercolordark=\"#FFFFFF\"";
	$G_MENU_TABLE_ATTR="id=\"sidenav_container\" valign=\"top\" width=\"125\" align=\"right\"";
	$G_HELP_TABLE_ATTR="id='help_text' width='133'";
	$G_SELECTED_BAR_COLOR="FFFF00";
	
	// -----------------------------------------------------------------------

	$G_APPLY_CANEL_BUTTON="<script>apply(''); echo(\"&nbsp;\"); cancel('');</script>";
	// Please DO NOT set the default password too long.
	// If the length of default password is longer than the max length of the password field,
	// it may cause the discrimination incorrect.
	$G_DEF_PASSWORD="**********";

}
?>
