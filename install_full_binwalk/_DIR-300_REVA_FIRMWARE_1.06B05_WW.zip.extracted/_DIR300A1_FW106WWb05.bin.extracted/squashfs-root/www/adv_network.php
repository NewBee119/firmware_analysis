<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="adv_network";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="adv";
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");

	echo "<!--\n";
	echo "upnp = ".$upnp."\n";
	echo "wan_ping = ".$wan_ping."\n";
	echo "wan_speed = ".$wan_speed."\n";

	if ($upnp		!="1")	{ $upnp="0"; }
	if ($wan_ping	!="1")	{ $wan_ping="0"; }
	$dirty = 0;
	if($upnp		!= query("/upnp/enable"))					{ $dirty++; set("/upnp/enable", $upnp); }
	if($wan_ping	!= query("/security/firewall/pingAllow"))	{ $dirty++; set("/security/firewall/pingAllow", $wan_ping); }
	if($wan_speed	!= query("/wan/rg/inf:1/etherLinkType"))	{ $dirty++; set("/wan/rg/inf:1/etherLinkType", $wan_speed); }
	if($multicast_streams	!= query("/nat/general/igmpproxy/enable"))	{ $dirty++; set("/nat/general/igmpproxy/enable", $multicast_streams); }
	if($multicast_enhancement	!= query("/nat/general/igmpproxy/enhancement"))	{ $dirty++; set("/nat/general/igmpproxy/enhancement", $multicast_enhancement); }

	/* Check dirty */
	$SUBMIT_STR="";
	if ($dirty > 0)			{$SUBMIT_STR="submit RG_MISC";}
	if($db_dirty2 > 0)	{$SUBMIT_STR=$SUBMIT_STR.";submit RG_MISC";}

	echo "-->\n";

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$cfg_upnp		= query("/upnp/enable");
$cfg_wan_ping	= query("/security/firewall/pingAllow");
$cfg_wan_speed	= query("/wan/rg/inf:1/etherLinkType");
$cfg_multicast_streams	= query("/nat/general/igmpproxy/enable");
$cfg_multicast_enhancement	= query("/nat/general/igmpproxy/enhancement");
if ($cfg_wan_speed != 2 && $cfg_wan_speed != 4) {$cfg_wan_speed = "0";}
/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");

	// init here ...
	f.upnp.checked 		= <? if ($cfg_upnp == "1") {echo "true";} else {echo "false";} ?>;
	f.wan_ping.checked 	= <? if ($cfg_wan_ping == "1") {echo "true";} else {echo "false";} ?>;
	<?
	if(query("/runtime/func/dis_multicast_stream") != "1")
	{
	echo "f.multicast_streams.checked ="; 
	if ($cfg_multicast_streams == "1") {echo "true;\n";} else {echo "false;\n";}
	echo "f.multicast_enhancement.checked = ";
	if ($cfg_multicast_enhancement == "1") {echo "true;\n";} else {echo "false;\n";}
	echo "f.multicast_enhancement.disabled = f.multicast_streams.checked ? false : true;\n";
	}
	?>
	select_index(f.wan_speed, <?=$cfg_wan_speed?>);
	<?if($router!=1){echo "fields_disabled(f, true);\n";}?>
}
/* parameter checking */
function check()
{
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

function on_click_multicast_enable()
{
	var f = get_obj("frm");
	f.multicast_enhancement.disabled = f.multicast_streams.checked ? false : true;
	f.multicast_enhancement.checked = f.multicast_enhancement.disabled ? false : false;
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
		require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");
		echo $G_APPLY_CANEL_BUTTON;
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_title_upnp?></h2>
			<p><?=$m_desc_upnp?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width=160><?=$m_enable_upnp?>&nbsp;:&nbsp;</td>
				<td class="l_tb">&nbsp;
					<input type="checkbox" name="upnp" value=1>
				</td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?=$m_title_wan_ping?></h2>
			<p><?=$m_desc_wan_ping?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width=160><?=$m_enable_wan_ping?>&nbsp;:&nbsp;</td>
				<td class="l_tb">&nbsp;
					<input type="checkbox" name="wan_ping" value=1>
				</td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?=$m_title_wan_speed?></h2>
			<select name="wan_speed" id="wan_speed">
				<option value=4><?=$m_option_wan_speed_10?></option>
				<option value=2><?=$m_option_wan_speed_100?></option> 
				<option value=0 selected><?=$m_option_wan_speed_auto?></option>
			</select>
		</div>
		<?
		if( query("/runtime/func/dis_multicast_stream") != "1" )
		{ require("/www/__multicast_stream.php"); }
		?>
		<div id="box_bottom">
		<?
		echo $G_APPLY_CANEL_BUTTON;
		?>
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
