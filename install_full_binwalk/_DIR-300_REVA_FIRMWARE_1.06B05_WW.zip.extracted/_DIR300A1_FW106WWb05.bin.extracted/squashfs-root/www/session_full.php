<?
$MY_NAME	="session_full";
$MY_MSG_FILE	=$MY_NAME.".php";

$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";
require("/www/model/__html_head.php");
?>
<script>
function init()
{
	var f=get_obj("frm");
	f.bt.focus();
}
function click_bt()
{
	self.location.href="login.php";
}
</script>
<?
$USE_BUTTON="1";
require("/www/model/__show_info.php");
?>
