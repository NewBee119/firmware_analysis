<?
/* vi: set sw=4 ts=4: */
$MY_NAME	="up_threshold_info";
$MY_MSG_FILE=$MY_NAME.".php";
$NO_NEED_AUTH       ="1";
$NO_SESSION_TIMEOUT ="1";

require("/www/model/__html_head.php");
echo "<!--\n";
echo "continue=".$continue."\n";
echo "url=".$url."\n";
echo "-->\n";

$SUBMIT_STR="submit FLOWMETER";
?>
<script>
function init()
{
<?
	if($continue != "" && $url != "")
	{
		echo "	self.location.href=\"http:\/\/".$url."\";\n";
	}
?>
}
function click_bt()
{
	var f = get_obj("final_form");
	
	var str="<?=$MY_NAME?>.xgi?continue=true";
<?
	//disable web notify to allow user continue web browsing
	$XGISET_STR="setPath=/flowmeter/tc/web_notify/";
	$XGISET_STR=$XGISET_STR."&enable=0";
	$XGISET_STR=$XGISET_STR."&endSetPath=1";

	if ($XGISET_STR!="")
	{
		echo "	str+=\"&".$XGISET_STR."\";\n";
	}
	if ($SUBMIT_STR!="")
	{
		echo "	str+=exe_str(\"".$SUBMIT_STR."\");\n";
	}
	if ($url!="")
	{
		echo "	str+=\"&url=".$url."\";\n";
	}
?>
	self.location.href=str;
}
</script>

<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST"	value="exit">
</form>

<?
$USE_BUTTON="1";
require("/www/model/__show_info.php");
?>
