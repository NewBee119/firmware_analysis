<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="bsc_wlan_main";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="bsc";
$SUB_CATEGORY	="bsc_wlan";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	
	/*
	$db_dirty=0;
	$db_dirty2=0;
	anchor("xxxx");
	if(query("xxxx")	!=$xxxx)	{set("xxxx", $xxxx);	$db_dirty++;}
	...
	anchor("oooo");
	if(query("oooo")	!=$oooo)	{set("oooo", $oooo);	$db_dirty2++;}
	...

	if($db_dirty > 0)	{$SUB_STR="submit xxxx";	$div="";}
	if($db_dirty2 > 0)	{$SUB_STR=$SUB_STR.$div."submit oooo";}
	*/

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.

/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	// init here ...
}
/* parameter checking */
function check()
{
	return false;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<?require("/www/model/__banner.php");?>
<?require("/www/model/__menu_top.php");?>
<table <?=$G_MAIN_TABLE_ATTR?> height="100%">
<tr valign=top>
	<td <?=$G_MENU_TABLE_ATTR?>>
	<?require("/www/model/__menu_left.php");?>
	</td>
	<td id="maincontent">
		<div id="box_header">
		<?require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_title_wizard?></h2>
			<br><?=$m_desc_wizard?><br><br>
			<center>
			<input type=button name="setup_wiz" value="<?=$m_setup_wiz?>" onclick="self.location.href='wiz_wlan.php'"><br><br>
			</center>
			<?=$m_note_wizard?>
		</div>
		<div class="box">
			<h2><?=$m_title_manual?></h2>
			<br><?=$m_desc_manual?><br><br>
			<center>
			<input type=button name="manual" value="<?=$m_manual_cfg?>" onclick="self.location.href='bsc_wlan.php'">
			</center>
			<br>
		</div>

<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
