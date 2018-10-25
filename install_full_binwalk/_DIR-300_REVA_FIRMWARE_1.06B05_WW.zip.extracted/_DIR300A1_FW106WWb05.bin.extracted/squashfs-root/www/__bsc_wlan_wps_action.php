<?
if($ACTION_WPS!="")
{
	require("/www/model/__admin_check.php");
	if ($ACTION_WPS=="GEN_PIN")
	{
		$new_pin=query("/runtime/genpin");
		set("/wireless/wps/pin", $new_pin);
		$SUBMIT_STR="submit WLAN";
	}
	else if ($ACTION_WPS=="RESET_PIN")
	{
		del("/wireless/wps/pin");
		$SUBMIT_STR="submit WLAN";
	}
	else if ($ACTION_WPS=="RESET_TO_UNCONFIG")
	{
		$ONLY_DO_SUBMIT_STR="submit RESET_WLAN; submit COMMIT; submit WLAN";
	}
	set("/wireless/wps/enable", "1");
	$NEXT_PAGE=$MY_NAME;
	require($G_SAVING_URL);
}
?>
