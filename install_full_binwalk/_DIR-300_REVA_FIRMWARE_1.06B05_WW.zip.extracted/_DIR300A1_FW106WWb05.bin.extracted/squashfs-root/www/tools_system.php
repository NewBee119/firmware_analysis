<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_system";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
if ($ACTION_POST == "CLEAR_LANGPACK")
{
	require("/www/model/__admin_check.php");
	$ONLY_DO_SUBMIT_STR="submit CLEAR_LANG_PACK";
	$NEXT_PAGE=$MY_NAME;
	require($G_SAVING_URL);
}
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.

/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function save_cfg()
{
	self.location.href="../config.bin";
}
function load_cfg(f)
{
	var f=get_obj("configuration");
	if(f.value=="")
	{
		alert("<?=$a_empty_cfg_file_path?>");
		return false;
	}
	if(!confirm("<?=$a_sure_to_reload_cfg?>")) return false;
}
function do_factory_reset()
{
	if(!confirm("<?=$a_sure_to_factory_reset?>")) return;
	var str="/sys_cfg_valid.xgi?";
	str+=exe_str("submit FRESET");
	self.location.href=str;
}
function do_clear_langpack()
{
	if(!confirm("<?=$a_sure_to_clear_langpack?>")) return;
	/*
	var str="";
	str="/sys_cfg_valid.xgi?";
	str+=exe_str("submit CLEAR_LANG_PACK; submit REBOOT");
	self.location.href=str;
	*/
	get_obj("frm").submit();
}
</script>
<body <?=$G_BODY_ATTR?>>
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
			<h2><?=$m_context_title?></h2>
			<table width=95%>
			<form>
			<tr>
				<td class=r_tb width=45%><?=$m_save_cfg?> :</td>
				<td><input type=button value="<?=$m_save?>" onclick="save_cfg()"></td>
			</tr>
			</form>
			<form method=POST action="upload_config._int" enctype=multipart/form-data onsubmit="return load_cfg(this.form);">
			<tr>
				<td class=r_tb><?=$m_load_cfg?> :</td>
				<td>
				<input type=file name="configuration" id="configuration" size=20><br>
				<input type="submit" value="<?=$m_b_load?>" name=load>
				</td>
			</tr>
			</form>
			<form>
			<tr>
				<td class=r_tb><?=$m_factory_reset?> :</td>
				<td><input type="button" value="<?=$m_b_restore?>" name=restore onclick="do_factory_reset(this.form)"></td>
			</tr>
			</form>
			<form name="frm" id="frm" method="post" action="".$MY_NAME.".php">
			<tr>
				<td class=r_tb><?=$m_clear_langpack?> :</td>
				<td>
					<input type=button value="<?=$m_clear?>" onclick="do_clear_langpack()">
					<input type=hidden name="ACTION_POST" value="CLEAR_LANGPACK">
				</td>
			</tr>
			</form>
			</table>
		</div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>>
	<?
	require($LOCALE_PATH."/help/h_".$MY_NAME.".php");
	echo "<br><br>";
	require($LOCALE_PATH."/help/h_".$MY_NAME."_js.php");
	?>
	</td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</body>
</html>
