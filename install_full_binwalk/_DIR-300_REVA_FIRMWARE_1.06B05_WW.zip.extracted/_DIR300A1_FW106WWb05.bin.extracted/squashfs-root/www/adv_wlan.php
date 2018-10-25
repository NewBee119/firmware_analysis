<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="adv_wlan";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="adv";
/* --------------------------------------------------------------------------- */
$support11n	=query("/runtime/func/ieee80211n");
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	
	$db_dirty=0;
	anchor("/wireless");
	if(query("txpower")			!=$tx_power)		{set("txpower",			$tx_power);			$db_dirty++;}
	if(query("beaconinterval")	!=$bi)				{set("beaconinterval",	$bi);				$db_dirty++;}
	if(query("rtslength")		!=$rts)				{set("rtslength",		$rts);				$db_dirty++;}
	if(query("fraglength")		!=$frag)			{set("fraglength",		$frag);				$db_dirty++;}
	if(query("dtim")			!=$dtim)			{set("dtim",			$dtim);				$db_dirty++;}
	if(query("preamble")		!=$preamble_type)	{set("preamble",		$preamble_type);	$db_dirty++;}
	if(query("ctsmode")			!=$cts_mode)		{set("ctsmode",			$cts_mode);			$db_dirty++;}
if($support11n!="1")
{
	if(query("wlanmode")		!=$f_g_only)		{set("wlanmode",		$f_g_only);			$db_dirty++;}
}
else
{
	if(query("wlanmode") != $wlan_mode)		
	{
		if($wlan_mode=="3" || $wlan_mode=="7") //mix/mixn
		{
			if(query("wlanmode")!="3" && query("wlanmode")!=7) //change from 11x only mode
			{
				set("txrate", "0");
				set("mcs/auto", "1");//kwest mark: 0 or 1?
				set("mcs/index", "");
			}
			else
			{
				set("txrate", "0");
				set("mcs/auto", "1");
				set("mcs/index", "");
			}
			if($wlan_mode=="7") // if we have 11n, force wmm enable
			{
				set("wmm", "1");
			}
		}
		else if($wlan_mode=="4") //11n only
		{
			set("txrate", "0");
			if(query("mcs/auto")=="1")
			{
				set("mcs/index", "");
			}
			set("wmm", "1"); //force wireless QoS
		}
		else //11b 11g only
		{
			set("txrate", "0");
			set("mcs/auto", "1");
			set("mcs/index", "");
		}

		set("wlanmode", $wlan_mode);
		$db_dirty++;
	}
	if(query("wlanmode")=="4" || query("wlanmode")=="7")
	{
		if(query("bandwidth") != $band_width) {set("bandwidth",        $band_width);         $db_dirty++;}
		if(query("shortguardinterval") != $shortguard) {set("shortguardinterval", $shortguard);         $db_dirty++;}
	}
}


	if($db_dirty > 0)	{$SUBMIT_STR="submit WLAN";}
	else				{$SUBMIT_STR="";}

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
anchor("/wireless");
$tx_rate	=query("txrate");
$tx_power	=query("txpower");
$bi			=query("beaconInterval");
$rts		=query("rtsLength");
$frag		=query("fragLength");
$dtim		=query("dtim");
$preamble	=query("preamble");
$cts		=query("ctsmode");
$cfg_wlmode     = query("wlanmode");
if($support11n=="1")
{
	$bandwidth = query("bandwidth");
	$shortguard = query("shortguardinterval");
}
/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	chg_cts_mode();
	select_index(f.tx_power, "<?=$tx_power?>");
	f.bi.value="<?=$bi?>";
<?
	if($cts!=""){echo "f.cts[".$cts."].checked=true;\n";}
?>	
	f.rts.value="<?=$rts?>";
	f.frag.value="<?=$frag?>";
	f.dtim.value="<?=$dtim?>";
	if("<?=$preamble?>"=="2")	f.preamble[1].checked=true;
	else						f.preamble[0].checked=true;
<?
	if($support11n=="1")
	{
		echo "select_index(f.wlan_mode, \"".$cfg_wlmode."\");\n";
		echo "select_index(f.band_width, \"".$bandwidth."\");\n";
		echo "f.short_guard.checked = ";
		if($shortguard=="1") { echo "true;\n"; } else { echo "false;\n"; }
		//init
		echo "wlanModeChg();\n";
	}
	else
	{
		echo "f.gonly.checked = ";
		if ($cfg_wlmode=="2") {echo "true;";} else {echo "false;";}
		echo "\n";
	}
?>
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(!is_in_range(f.bi.value,20,1000))
	{
		alert("<?=$a_invalid_bi?>");
		if(f.bi.value=="") f.bi.value=100;
		field_select(f.bi);
		return false;
	}
	if(!is_in_range(f.rts.value,256,2346))
	{
		alert("<?=$a_invalid_rts?>");
		if(f.rts.value=="") f.rts.value=2346;
		field_select(f.rts);
		return false;
	}
	if(!is_in_range(f.frag.value,1500,2346))
	{
		alert("<?=$a_invalid_frag?>");
		if(f.frag.value=="") f.frag.value=2346;
		field_select(f.frag);
		return false;
	}
	if(decstr2int(f.frag.value)%2)
	{
		alert("<?=$a_frag_should_be_even?>");
		field_select(f.frag);
		return false;
	}
	if(!is_in_range(f.dtim.value,1,255))
	{
		alert("<?=$a_invalid_dtim?>");
		if(f.dtim.value=="") f.dtim.value=1;
		field_select(f.dtim);
		return false;
	}
	f.preamble_type.value=(f.preamble[0].checked?1:2);
	if(f.cts[0].checked)		f.cts_mode.value=0;
	else if(f.cts[1].checked)	f.cts_mode.value=1;
	else if(f.cts[2].checked)	f.cts_mode.value=2;
<?
	if($support11n=="1")
	{
		echo "f.shortguard.value = (f.short_guard.checked? \"1\":\"0\");\n";
	}
	else
	{
		echo "f.f_g_only.value =(f.gonly.checked? \"2\":\"3\");\n";
	}
?>
	return true;

}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function chg_cts_mode()
{
	var f=get_obj("frm");
	var dis;
	if("<?=$tx_rate?>"!="0")	{	dis=true;	f.cts[0].checked=true;	}
	else						{	dis=false;	f.cts[2].checked=true;	}
	f.cts[0].disabled=(!dis);
	f.cts[1].disabled=dis;
	f.cts[2].disabled=dis;
}
<?
if($support11n=="1")
{
echo "function wlanModeChg()\n";
echo "{\n";
echo "	var f=get_obj(\"frm\");\n";
echo "	if(f.wlan_mode.value!=\"4\" && f.wlan_mode.value!=\"7\")\n";
echo "	{\n";
echo "		f.band_width.disabled = true;\n";
echo "		f.short_guard.disabled = true;\n";
echo "	}\n";
echo "	else\n";
echo "	{\n";
echo "		f.band_width.disabled = false;\n";
echo "		f.short_guard.disabled = false;\n";
echo "	}\n";
echo "}\n";
}
?>
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
		<?
		require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");
		echo $G_APPLY_CANEL_BUTTON;
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_context_title?></h2>
			<table width=525>
			<tr>
				<td class=r_tb><?=$m_tx_power?> :</td>
				<td>
				<select name="tx_power">
				<option value="1">100%</option>
				<option value="2">50%</option>
				<option value="3">25%</option>
				<option value="4">12.5%</option>
				</select>
				</td>
			</tr>
			<tr>
				<td class=r_tb><?=$m_beacon_interval?> :</td>
				<td><input maxlength=4 name=bi size=4 value=""><?=$m_dsc_bi?></td>
			</tr>
			<tr>
				<td class=r_tb><?=$m_rts?> :</td>
				<td><input maxlength=4 name=rts size=4 value=""><?=$m_dsc_rts?></td>
			</tr>
			<tr>
				<td class=r_tb><?=$m_frag?> :</td>
				<td><input maxlength=4 name=frag size=4 value=""><?=$m_dsc_frag?></td>
			</tr>
			<tr>
				<td class=r_tb><?=$m_dtim?> :</td>
				<td><input maxlength=3 name=dtim size=4 value=""><?=$m_dsc_dtim?></td>
			</tr>
			<tr>
				<td class=r_tb><?=$m_preamble?> :</td>
				<td>
				<input type=radio name=preamble value=1><?=$m_preamble_short?>
				<input type=radio name=preamble value=0><?=$m_preamble_long?>
				</td>
			</tr>
			<tr>
				<td class=r_tb><?=$m_cts?> :</td>
				<td>
				<input type=radio name=cts value=0><?=$m_none?>
				<input type=radio name=cts value=1><?=$m_always?>
				<input type=radio name=cts value=2><?=$m_auto?>
				</td>
			</tr>
			<tr>
			<?
			if($support11n=="1") { echo "<!--\n"; }
			?>
                <td class=r_tb><?=$m_11g_only?></td>
                <td><?=$symbol?>
                    <input name='gonly' id='gonly' type='checkbox' value='2'>
                <td>
			<?
			if($support11n=="1") { echo "-->\n"; }
			?>
			<?
			if($support11n=="1")
			{	
				echo "<td class=r_tb>".$m_wlan_mode_set." :</td>\n";
				echo "<td>\n";
				echo "<select name=\"wlan_mode\" onChange=\"wlanModeChg();\">\n";
				if(query("/runtime/func/dis_wlan_b")!="1")
				{	echo "<option value=\"1\">".$m_wlan_mode_11b."</option>\n";	}
				if(query("/runtime/func/dis_wlan_g")!="1")
				{	echo "<option value=\"2\">".$m_wlan_mode_11g."</option>\n";	}
				if(query("/runtime/func/dis_wlan_n")!="1")
				{	echo "<option value=\"4\">".$m_wlan_mode_11n."</option>\n";	}
				if(query("/runtime/func/dis_wlan_bg")!="1")
				{	echo "<option value=\"3\">".$m_wlan_mode_mix."</option>\n";	}
				if(query("/runtime/func/dis_wlan_nbg")!="1")
				{	echo "<option value=\"7\">".$m_wlan_mode_mixn."</option>\n";}
				echo "</select>\n";
				echo "</td>\n";
			}
			?>
            </tr>
			<?
			if($support11n=="1")
			{
				echo "<tr>\n";
				echo "	<td class=r_tb>".$m_wlan_band_width." :</td>\n";
				echo "	<td>\n";
				echo "	<select name=\"band_width\">\n";
				echo "	<option value=\"1\">20 ".$m_mhz."</option>\n";
				echo "	<option value=\"2\">20/40 ".$m_mhz."(".$m_auto.")</option>\n";
				echo "	</select>\n";
				echo "	</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<td class=r_tb>".$m_wlan_short_guard." :</td>\n";
				echo "	<td>\n";
				echo "	<input name=short_guard id=short_guard type=checkbox value=1>\n";
				echo "	</td>\n";
				echo "</tr>\n";
			}
			?>
			</table>
		</div>
		<input type=hidden name=preamble_type value="">
		<input type=hidden name=cts_mode value="">
		<input type="hidden" name="f_g_only" value="">
		<?
		if($support11n=="1")
		{
			echo "<input type=\"hidden\" name=\"shortguard\" value=\"\">\n";
		}
		?>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
