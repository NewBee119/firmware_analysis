<?
/* vi: set sw=4 ts=4: -------------------------------------------------------- */
$MY_NAME		= "adv_qos";
$MY_MSG_FILE	= $MY_NAME.".php";
$CATEGORY		= "adv";
/* --------------------------------------------------------------------------- */
$router	= query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");
	$dirty = 0;
	$SUBMIT_STR = "";

	echo "<!--\n";
	echo "uplink_speed = ".$uplink_speed."\n";
	echo "downlink_speed = ".$downlink_speed."\n";
	echo "qos_mode = ".$qos_mode."\n";

	if ($qos_mode == "") { $qos_mode = 0; }
	if (query("/qos/mode")!=$qos_mode)	{$dirty++; set("/qos/mode", $qos_mode);}
	if ($qos_mode == 1)
	{
		anchor("/qos/bandwidth");
		if (query("upstream")	!=$uplink_speed)	{$dirty++; set("upstream", $uplink_speed);}
		if (query("downstream")	!=$downlink_speed)	{$dirty++; set("downstream", $downlink_speed);}
	}
	
	echo "-->\n";

	if ($dirty > 0)	{$SUBMIT_STR="submit QOS";}
	$NEXT_PAGE = $MY_NAME;
	if ($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else					{require($G_NO_CHANGED_URL);}
}
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
$qos_mode	= query("/qos/mode");
$upspeed	= query("/qos/bandwidth/upstream");
$downspeed	= query("/qos/bandwidth/downstream");
$netsniper  = query("/wan/rg/inf:1/netsniper_enable");
?>
<script>
function dis_qos()
{
	var f = get_obj("frm");
	f.qos_mode.checked = <?if ($netsniper==1 || $qos_mode==0 ){echo "false";} else if($qos_mode==1) {echo "true";} else {echo "false";}?>;
	dis = <?if ($netsniper==1){echo "true";} else {echo "false";}?>;
	fields_disabled(f, dis);
}	
	
function on_click_qos_mode()
{
	var f = get_obj("frm");
	f.uplink_speed.disabled = f.downlink_speed.disabled = f.qos_mode.checked ? false : true;
}

function init()
{
	var f = get_obj("frm");
	f.qos_mode.checked = <? if ($qos_mode==1) {echo "true";} else {echo "false";} ?>;
	select_index(f.uplink_speed, "<?=$upspeed?>");
	select_index(f.downlink_speed, "<?=$downspeed?>");
	dis_qos();
	on_click_qos_mode();
	<?if($router!=1){echo "fields_disabled(f, true);\n";}?>
}

function check()
{
	return true;
}

function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php"  onsubmit="return check()">
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
		<?
		$dummy = fread($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");
		if ($dummy != "") { require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); }
		else { require("/www/locale/en/dsc/dsc_".$MY_NAME.".php"); }
		echo $G_APPLY_CANEL_BUTTON;
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_title_bandwidth?></h2>
			<p>
			<table width=525 border=0>
				<tr>
					<td class="r_tb" width=160><?=$m_uplink_speed?>&nbsp;:&nbsp;</td>
					<td class="l_tb">
						<select name="uplink_speed" id="uplink_speed">
							<option value="64">64 Kbps</option>
							<option value="128">128 Kbps</option>
							<option value="256">256 Kbps</option>
							<option value="512">512 Kbps</option>
							<option value="1024">1 Mbps</option>
							<option value="2048">2 Mbps</option>
							<option value="4096">4 Mbps</option>
							<option value="8192">8 Mbps</option>
							<option value="16384">16 Mbps</option>
							<option value="32768">32 Mbps</option>
							<option value="0">FULL</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="r_tb" width=160><?=$m_downlink_speed?>&nbsp;:&nbsp;</td>
					<td class="l_tb">
						<select name="downlink_speed" id="downlink_speed">
							<option value="64">64 Kbps</option>
							<option value="128">128 Kbps</option>
							<option value="256">256 Kbps</option>
							<option value="512">512 Kbps</option>
							<option value="1024">1 Mbps</option>
							<option value="2048">2 Mbps</option>
							<option value="4096">4 Mbps</option>
							<option value="8192">8 Mbps</option>
							<option value="16384">16 Mbps</option>
							<option value="32768">32 Mbps</option>
							<option value="0">FULL</option>
						</select>
					</td>
				</tr>
			</table>
			<p><?=$m_bandwidth_help?></p>
		</div>
		<div class="box">
			<h2><?=$m_title_qos?></h2>
			<table width=525 border=0>
				<tr>
					<td align=center valign=middle>
						<input type="checkbox" name="qos_mode" name="qos_mode" value=1
						onclick="on_click_qos_mode()">&nbsp;<?=$m_enable_auto_qos?>
					</td>
				</tr>
			</table>
		</div>
		<div id="box_bottom">
		<? echo $G_APPLY_CANEL_BUTTON; ?>
		</div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?
	$dummy = fread($LOCALE_PATH."/help/h_".$MY_NAME.".php");
	if ($dummy != "") { require($LOCALE_PATH."/help/h_".$MY_NAME.".php"); }
	else { require("/www/locale/en/help/h_".$MY_NAME.".php"); }
	?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
