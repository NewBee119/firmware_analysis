<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="bsc_wlan";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="bsc";
$SUB_CATEGORY	="bsc_wlan";
/* --------------------------------------------------------------------------- */
$def_password	="XxXxXxXx";
$def_psk		="XxXxXxXx";

$support_wps=query("/runtime/func/wps");
$superg		=query("/runtime/func/superg");
$support11n =query("/runtime/func/ieee80211n");
if ($support_wps==1) { require("/www/__bsc_wlan_wps_action.php"); }

if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	echo "<!--\n";
	echo "f_enable=".			$f_enable.			"\n";
	echo "f_wps_enable=".		$wps_enable.		"\n";
	echo "f_wps_locksecurity=".	$wps_locksecurity.	"\n";
	echo "f_ssid=".				$f_ssid.			"\n";
	echo "f_channel=".			$f_channel.			"\n";
	echo "f_auto_channel=".		$f_auto_channel.	"\n";
	echo "f_super_g=".			$f_super_g.			"\n";
	echo "f_xr=".				$f_xr.				"\n";
	echo "f_ap_hidden=".		$f_ap_hidden.		"\n";
	echo "f_authentication=".	$f_authentication.	"\n";
	echo "f_cipher=".			$f_cipher.			"\n";
	echo "f_wep_len=".			$f_wep_len.			"\n";
	echo "f_wep_format=".		$f_wep_format.		"\n";
	echo "f_wep_def_key=".		$f_wep_def_key.		"\n";
	echo "f_wep=".				$f_wep.				"\n";
	echo "f_wpa_psk=".			$f_wpa_psk.			"\n";
	echo "f_radius_ip1=".		$f_radius_ip1.		"\n";
	echo "f_radius_port1=".		$f_radius_port1.	"\n";
	echo "f_radius_secret1=".	$f_radius_secret1.	"\n";
	echo "f_txrate=".	$f_txrate.	"\n";
	echo "-->\n";
	
	anchor("/wireless");
	$db_dirty=0;
	$wps_cfg=0;
	if(query("enable")!=$f_enable)	{set("enable",$f_enable); $db_dirty++;	}
	if($f_enable=="1")
	{
		if(query("ssid")		!= $f_ssid)				{set("ssid",		$f_ssid);			$db_dirty++; $wps_cfg++; }
		if(query("channel")		!= $f_channel)			{set("channel",		$f_channel);		$db_dirty++;}
		if(query("autochannel")	!= $f_auto_channel)		{set("autochannel",	$f_auto_channel);	$db_dirty++;}
		if(query("wmm")			!= $f_wmm_enable)		{set("wmm",			$f_wmm_enable);		$db_dirty++;}
		if(query("ssidhidden")	!= $f_ap_hidden)		{set("ssidhidden",	$f_ap_hidden);		$db_dirty++;}
		if(query("authtype")	!= $f_authentication)	{set("authtype",	$f_authentication);	$db_dirty++; $wps_cfg++; }
		if(query("encrypttype")	!= $f_cipher)			{set("encrypttype",	$f_cipher);			$db_dirty++; $wps_cfg++; }
		if($support11n!="1")
		{
			if(query("txrate")      != $f_txrate)
			{
				set("txrate", $f_txrate);
				$db_dirty++;
				if($f_txrate!="0")				{set("ctsmode","0");}
				else if(query("ctsmode")=="0")	{set("ctsmode","2");}
			}
		}
		else
		{
			$wlanMode = query("wlanmode");
			if($wlanMode=="1" || $wlanMode=="2")
			{
				if(query("txrate") != $f_txrate)
				{
					set("txrate", $f_txrate);
					$db_dirty++;
				}
			}
			else if($wlanMode=="4")
			{
				if($f_txrate=="0")
				{
					if(query("mcs/auto")!="1")
					{
						set("mcs/auto","1");
						set("mcs/index","");
						$db_dirty++;
					}
				}
				else
				{
					$mcsIndex = $f_txrate - 1;
					if(query("mcs/auto")!="0" || query("mcs/index")!=$mcsIndex)
					{
						set("mcs/auto","0");
						set("mcs/index", $mcsIndex);
						$db_dirty++;
					}
				}
			}
			//if($f_txrate!="0")				{set("ctsmode","0");}
			//else if(query("ctsmode")=="0")	{set("ctsmode","2");}
		}

		if($superg=="1")
		{
			if($f_super_g=="")	{$f_super_g="0";}
			if($f_xr!="1")		{$f_xr="0";}
			if(query("atheros/supermode")!=$f_super_g)	{set("atheros/supermode",	$f_super_g);	$db_dirty++;}
			if(query("atheros/xr")!=$f_xr)				{set("atheros/xr",			$f_xr);			$db_dirty++;}
		}

		if($f_cipher=="1")	//wep key
		{
			anchor("/wireless/wep");
			if(query("length")	!= $f_wep_len)			{set("length",	$f_wep_len);		$db_dirty++;}
			if(query("format")	!= $f_wep_format)		{set("format",	$f_wep_format);		$db_dirty++;}
			if(query("defkey")	!= $f_wep_def_key)		{set("defkey",	$f_wep_def_key);	$db_dirty++;}
			if(query("key:".$f_wep_def_key)	!= $f_wep)	{set("key:".$f_wep_def_key,	$f_wep);$db_dirty++;}
		}
		
		if($f_authentication=="2" || $f_authentication=="4" || $f_authentication=="6")	// wpa series
		{
			anchor("/wireless/wpa/radius:1");
			if(query("host")		!= $f_radius_ip1)	{set("host",	$f_radius_ip1);		$db_dirty++;}
			if(query("port")		!= $f_radius_port1)	{set("port",	$f_radius_port1);	$db_dirty++;}
			if($f_radius_secret1	!= $def_password)
			{ if(query("secret")	!= $f_radius_secret1){set("secret",	$f_radius_secret1);	$db_dirty++;}}
		}
		else if($f_authentication=="3" || $f_authentication=="5" || $f_authentication=="7")// wpa psk series
		{
			anchor("/wireless/wpa");
			if(query("format")!=$f_wpa_psk_type)		{set("format",	$f_wpa_psk_type);	$db_dirty++;}
			if($f_wpa_psk!=$def_psk)
			{
				if(query("key") != $f_wpa_psk)			{set("key", 	$f_wpa_psk);		$db_dirty++;}
			}
		}
		if ($support_wps==1)
		{
			anchor("/wireless/wps");
			if(query("locksecurity") !=$f_wps_locksecurity)	{set("locksecurity",	$f_wps_locksecurity);	$db_dirty++;}
			if($wps_cfg > 0)
			{
				set("configured", "1");
				set("locksecurity",	"1");
			}
			if(query("enable")	!=$f_wps_enable)		{set("enable",	 $f_wps_enable);	$db_dirty++;}
		}
	}

	if($db_dirty > 0)	{$SUBMIT_STR="submit WLAN";}
	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
anchor("/wireless");
$cfg_enable		= query("enable");
$cfg_ssid		= get("j", "ssid");
$cfg_channel	= query("channel");
$cfg_autochann	= query("autochannel");
$cfg_txrate		= query("txrate");
$cfg_wmm_enable	= query("wmm");
$cfg_ssidhidden	= query("ssidhidden");

$cfg_auth		= query("authtype");
$cfg_cipher		= query("encrypttype");
if		($cfg_cipher=="0")					{$security_type="0";}
else if	($cfg_cipher=="1")					{$security_type="1";}
if		($cfg_auth=="2" || $cfg_auth=="3")	{$security_type="2";}
else if ($cfg_auth=="4" || $cfg_auth=="5")	{$security_type="3";}
else if ($cfg_auth=="6" || $cfg_auth=="7")	{$security_type="4";}
$cfg_psk_eap_type="psk";
if($cfg_auth=="2" || $cfg_auth=="4" || $cfg_auth=="6"){$cfg_psk_eap_type="eap";}

$cfg_psk_type	= query("wpa/format");
if($cfg_psk_type=="")	{$cfg_psk_type="1";}
$cfg_wpapsk		= get("j", "wpa/key");

$cfg_wep_length = query("wep/length");
$cfg_wep_format = query("wep/format");
$wep_prefix="wepkey_64";
if($cfg_wep_length=="128"){$wep_prefix="wepkey_128";}

$cfg_wep_index	= query("wep/defkey");
$wep_path		= "wep/key:".$cfg_wep_index;
$cfg_wep		= get("j", $wep_path);
$cfg_radius_ip1		= query("wpa/radius:1/host");
$cfg_radius_port1	= query("wpa/radius:1/port");
if(query("wpa/radius:1/secret")=="")	{$cfg_radius_sec1= "";}
else									{$cfg_radius_sec1=$def_password;}

$td1			="<td class='r_tb' width='200'>";
$td2			="<td class='l_tb'>";
$td3			="<td class='r_tb' width='120'>";
$td4			="<td class='r_tb' width='150'>";
$symbol			=" :&nbsp;";
$symbol2		=" :&nbsp;&nbsp;";

if($support11n=="1")
{
	$wlanMode = query("/wireless/wlanmode");
	$bandWidth = query("/wireless/bandwidth");
	$shortGuard = query("/wireless/shortguardinterval");
	$mcsAuto = query("/wireless/mcs/auto");
	$mcsIndex = query("/wireless/mcs/index");
}
/* --------------------------------------------------------------------------- */
?>

<?if ($support_wps==1) { require("/www/__bsc_wlan_wps_js.php"); }?>
<script>
function print_keys(key_name, max_length)
{
	var str="";
	var field_size=decstr2int(max_length)+5;
	var hint_wording;
	if(max_length=="10")	{hint_wording="<?=$m_wep64_hint_wording?>";}
	else					{hint_wording="<?=$m_wep128_hint_wording?>";}
	str="<table>";
	str+="\t<tr>";
	str+="\t\t<?=$td1?><?=$m_wep_key?></td>";
	str+="\t\t<?=$td2?><?=$symbol?><input type='text' id='"+key_name+"' name='"+key_name+"' maxlength='"+max_length+"' size='"+field_size+"' value=''>&nbsp;"+hint_wording+"</td>";
	str+="\t</tr>";
	str+="</table>";
	document.write(str);
}
function on_change_security_type()
{
	var sec_type = get_obj("security_type");

	get_obj("show_wep").style.display = "none";
	get_obj("show_wpa").style.display = "none";

	if (sec_type.value == 1)
	{
		get_obj("show_wep").style.display = "";
		chg_wep_type();
	}
	else if(sec_type.value >= 2)
	{
		get_obj("show_wpa").style.display = "";
		
		get_obj("title_wpa").style.display			= "none";
		get_obj("title_wpa2").style.display			= "none";
		get_obj("title_wpa2_auto").style.display	= "none";
		if(sec_type.value == 2)		get_obj("title_wpa").style.display		= "";
		if(sec_type.value == 3)		get_obj("title_wpa2").style.display		= "";
		if(sec_type.value == 4)		get_obj("title_wpa2_auto").style.display= "";
		chg_psk_eap();
	}
}
function chg_wep_type()
{
	var f=get_obj("frm_wep");
	get_obj("wep_64").style.display		= "none";
	get_obj("wep_128").style.display	= "none";

	if(f.wep_key_len.value=="128")	{get_obj("wep_128").style.display	= "";}
	else							{get_obj("wep_64").style.display	= "";}
}
function chg_psk_eap()
{
	var wpa_type = get_obj("psk_eap");
	get_obj("psk_setting").style.display = "none";
	get_obj("eap_setting").style.display = "none";
	if(wpa_type.value=="psk")	{get_obj("psk_setting").style.display = "";	}
	else						{get_obj("eap_setting").style.display = "";	}
}
function on_check_enable()
{
	var f = get_obj("frm");
	if (f.enable.checked)
	{
		f.ssid.disabled = false;
		if (f.autochann.checked) f.channel.disabled = true;
		else f.channel.disabled = false;
		f.autochann.disabled = false;
		f.txrate.disabled = false;
	<?
	if($support11n=="1")
	{
		if($wlanMode=="4" || $wlanMode=="7") //when 11n,always enable WMM
		{
			echo "f.wmm_enable.disabled = true;\n";
		}
		else
		{
			echo "f.wmm_enable.disabled = false;\n";
		}
	}
	else
	{
		echo "f.wmm_enable.disabled = false;\n";
	}
	?>

		f.aphidden.disabled = false;
		f.security_type.disabled =false;
		<?
		if($superg=="1")
		{
			echo "f.super_g_mode.disabled=f.en_xr.disabled=false;\n";
			echo "chg_super_g();\n";
		}
		?>
		select_index(f.security_type, "<?=$security_type?>");
		on_change_security_type();
	}
	else
	{
		select_index(f.security_type,"0");
		on_change_security_type();
		fields_disabled(f, true);
		f.enable.disabled=false;
	}
	<?if ($support_wps==1)	{echo "enable_disable_wps();\n";}?>
}

function on_check_autochann()
{
	var f = get_obj("frm");
	f.channel.disabled = f.autochann.checked;
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var f_wep = get_obj("frm_wep");
	var f_wpa = get_obj("frm_wpa");
	// init here ...
	f.enable.checked = <? if ($cfg_enable=="1") {echo "true";} else {echo "false";} ?>;
	f.ssid.value = "<?=$cfg_ssid?>";
	select_index(f.channel, "<?=$cfg_channel?>");
	f.autochann.checked = <? if ($cfg_autochann=="1") {echo "true";} else {echo "false";}?>;
<?
	if($support11n!="1")
	{
		echo "select_index(f.txrate, \"".$cfg_txrate."\");\n";
	}
	else
	{
		if($wlanMode=="1" || $wlanMode=="2")
		{
			echo "select_index(f.txrate, \"".$cfg_txrate."\");\n";
		}
		else if($wlanMode=="4")
		{
			if($mcsAuto=="1")
			{
				echo "select_index(f.txrate, \"0\");\n";
			}
			else
			{
				$mcsRate = $mcsIndex+1;
				echo "select_index(f.txrate, \"".$mcsRate."\");\n";
			}
		}
	}
?>
	f.wmm_enable.checked = <?if($cfg_wmm_enable=="1"){echo "true";}else{echo "false";}?>;
	f.aphidden.checked = <? if ($cfg_ssidhidden=="1") {echo "true";} else {echo "false";}?>;
	select_index(f.security_type, "<?=$security_type?>");
	
	//wep
	select_index(f_wep.auth_type,	"<?=$cfg_auth?>");
	select_index(f_wep.wep_key_len,	"<?=$cfg_wep_length?>");
	select_index(f_wep.wep_def_key, "<?=$cfg_wep_index?>");
	f_wep.<?=$wep_prefix?>.value= "<?=$cfg_wep?>";

	//wpa
	select_index(f_wpa.cipher_type,	"<?=$cfg_cipher?>");
	select_index(f_wpa.psk_eap,		"<?=$cfg_psk_eap_type?>");
	f_wpa.wpapsk1.value		="<?=$cfg_wpapsk?>";
	
	f_wpa.srv_ip1.value		="<?=$cfg_radius_ip1?>";
	f_wpa.srv_port1.value	="<?=$cfg_radius_port1?>";
	f_wpa.srv_sec1.value	="<?=$cfg_radius_sec1?>";

	on_check_autochann();
	on_change_security_type();

	<?
	if($superg=="1")
	{
		echo "get_obj('show_super_g').style.display = '';\n";
		echo "select_index(f.super_g_mode, '".query("/wireless/atheros/supermode")."');\n";
		echo "f.en_xr.checked=";	map("/wireless/atheros/xr","1","true",*,"false");	echo "\n";
	}
	?>
	on_check_enable();
	<?
	if($support11n=="1")
	{
		$wlanmode = query("/wireless/wlanmode");
		if($wlanmode=="4" || $wlanmode=="7") //when 11n,always enable WMM
		{
			echo "f.wmm_enable.disabled = true;\n";
		}
	}
	?>
}
function append_zero(len)
{
	var x_zero="";
	var i=0;
	for(i=0; i<len;i++)
	{
		x_zero+="0";
	}
	return x_zero;	
}
function chk_wepkey(obj_name, key_type, key_len)
{
	var key_obj=get_obj(obj_name);
	if(key_type==1)	//ascii
	{
		if(strchk_unicode(key_obj.value))
		{
			if(key_len==13)	alert("<?=$a_invalid_wep_128_ascii_wep_key?>");
			else			alert("<?=$a_invalid_wep_64_ascii_wep_key?>");
			key_obj.select();
			return false;
		}
	}
	else	//hex
	{
		var test_char, i;
		for(i=0; i<key_obj.value.length; i++)
		{
			test_char=key_obj.value.charAt(i);
			if( (test_char >= '0' && test_char <= '9') ||
				(test_char >= 'a' && test_char <= 'f') ||
				(test_char >= 'A' && test_char <= 'F'))
				continue;

			if(key_len==26)	alert("<?=$a_invalid_wep_128_hex_wep_key?>");
			else			alert("<?=$a_invalid_wep_64_hex_wep_key?>");
			key_obj.select();
			return false;
		}
	}
	return true;
}
/* parameter checking */
function check()
{
	var f		=get_obj("frm");
	var f_wep	=get_obj("frm_wep");
	var f_wpa	=get_obj("frm_wpa");
	var f_final	=get_obj("final_form");

	f_final.f_enable.value			="";
	<?if($support_wps=="1")
	{
		echo "	f_final.f_wps_enable.value		=\"\";\n";
		echo "	f_final.f_wps_locksecurity.value	=\"\";\n";
	}
	?>
	f_final.f_ssid.value			="";
	f_final.f_channel.value			="";
	f_final.f_auto_channel.value	="";
	f_final.f_super_g.value			="";
	f_final.f_xr.value				="";
	f_final.f_txrate.value			="";
	f_final.f_ap_hidden.value		="";
	f_final.f_authentication.value	="";
	f_final.f_cipher.value			="";
	f_final.f_wep_len.value			="";
	f_final.f_wep_format.value		="";
	f_final.f_wep_def_key.value		="";
	f_final.f_wep.value				="";
	f_final.f_wpa_psk_type.value	="";
	f_final.f_wpa_psk.value			="";
	f_final.f_radius_ip1.value		="";
	f_final.f_radius_port1.value	="";
	f_final.f_radius_secret1.value	="";

	if(f.enable.checked)	{	f_final.f_enable.value="1";	}
	else					{	f_final.f_enable.value="0";		return f_final.submit();}
	<?
	if($support_wps=="1")
	{
		echo "f_final.f_wps_enable.value	=(f.wps_enable.checked ? 1:0);\n";
		echo "f_final.f_wps_locksecurity.value	=(f.wps_locksecurity.checked ? 1:0);\n";
	}
	?>
	
	if(is_blank(f.ssid.value))
	{
		alert("<?=$a_empty_ssid?>");
		f.ssid.focus();
		return false;
	}
	if(strchk_unicode(f.ssid.value))
	{
		alert("<?=$a_invalid_ssid?>");
		f.ssid.select();
		return false;
	}

	// assign final post variables
	f_final.f_ssid.value			=f.ssid.value;
	f_final.f_channel.value			=f.channel.value;
	f_final.f_auto_channel.value	=(f.autochann.checked ? "1":"0");
	f_final.f_txrate.value			=f.txrate.value;
	f_final.f_wmm_enable.value		=(f.wmm_enable.checked ? "1":"0");
	f_final.f_ap_hidden.value		=(f.aphidden.checked ? "1":"0");
	
	if(<?if($superg=="1"){echo "1";}else{echo "0";}?>)
	{
		f_final.f_super_g.value=f.super_g_mode.value;
		f_final.f_xr.value=(f.en_xr.checked?1:0);
	}
	
	//open
	if(f.security_type.value=="0")
	{
		// assign final post variables
		f_final.f_authentication.value="0";
		f_final.f_cipher.value="0";
	}
	// open+wep / shared key
	else if(f.security_type.value=="1")
	{
		var test_len=10;
		var test_wep_obj;
		var key_type;
		if(f_wep.wep_key_len.value=="128")
		{
			test_wep_obj=get_obj("wepkey_128");
			if(test_wep_obj.value.length!=13 && test_wep_obj.value.length!=26)
			{
				alert("<?=$a_invalid_wep_128_wep_key?>");
				test_wep_obj.select();
				return false;
			}
				
			key_type=(test_wep_obj.value.length==13?1:2);
			if(chk_wepkey("wepkey_128", key_type, test_wep_obj.value.length)==false)	return false;
		}
		else
		{
			test_wep_obj=get_obj("wepkey_64");
			if(test_wep_obj.value.length!=5 && test_wep_obj.value.length!=10)
			{
				alert("<?=$a_invalid_wep_64_wep_key?>");
				test_wep_obj.select();
				return false;
			}
			key_type=(test_wep_obj.value.length==5?1:2);
			if(chk_wepkey("wepkey_64", key_type, test_wep_obj.value.length)==false)	return false;
		}
		f_final.f_wep.value=test_wep_obj.value;
		
		// assign final post variables
		f_final.f_authentication.value	=f_wep.auth_type.value;
		f_final.f_cipher.value			="1";
		f_final.f_wep_len.value			=f_wep.wep_key_len.value;
		f_final.f_wep_format.value		=key_type;
		f_final.f_wep_def_key.value		=f_wep.wep_def_key.value;
	}
	// wpa series 
	else if(f.security_type.value>="2")
	{
		if(f_wpa.psk_eap.value=="eap")
		{
			if(!is_valid_ip(f_wpa.srv_ip1.value,0))
			{
				alert("<?=$a_invalid_radius_ip1?>");
				f_wpa.srv_ip1.select();
				return false;
			}
			if(is_blank(f_wpa.srv_port1.value))
			{
				alert("<?=$a_invalid_radius_port1?>");
				f_wpa.srv_port1.focus();
				return false;
			}
			if(!is_valid_port_str(f_wpa.srv_port1.value))
			{
				alert("<?=$a_invalid_radius_port1?>");
				f_wpa.srv_port1.select();
				return false;
			}
			if(is_blank(f_wpa.srv_sec1.value))
			{
				alert("<?=$a_empty_radius_sec1?>");
				f_wpa.srv_sec1.focus();
				return false;
			}
			if(strchk_unicode(f_wpa.srv_sec1.value))
			{
				alert("<?=$a_invalid_radius_sec1?>");
				f_wpa.srv_sec1.select();
				return false;
			}
			
			// assign final post variables
			switch (f.security_type.value)
			{
			case "2":	f_final.f_authentication.value="2";		break;
			case "3":	f_final.f_authentication.value="4";		break;
			case "4":	f_final.f_authentication.value="6";		break;
			default	:	break;
			}
			f_final.f_cipher.value			=f_wpa.cipher_type.value;
			f_final.f_radius_ip1.value		=f_wpa.srv_ip1.value;
			f_final.f_radius_port1.value	=f_wpa.srv_port1.value;
			f_final.f_radius_secret1.value	=f_wpa.srv_sec1.value;
		}
		else
		{
			if(f_wpa.wpapsk1.value=="<?=$def_psk?>")
			{
				f_final.f_wpa_psk_type.value="<?=$cfg_psk_type?>";
			}
			else
			{
				if(f_wpa.wpapsk1.value.length==64)
				{
					var test_char,j;
					for(j=0; j<f_wpa.wpapsk1.value.length; j++)
					{
						test_char=f_wpa.wpapsk1.value.charAt(j);
						if( (test_char >= '0' && test_char <= '9') ||
								(test_char >= 'a' && test_char <= 'f') ||
								(test_char >= 'A' && test_char <= 'F'))
							continue;

						alert("<?=$a_invalid_psk?>");
						f_wpa.wpapsk1.select();
						return false;
					}
					f_final.f_wpa_psk_type.value="2";
				}
				else
				{
					if(f_wpa.wpapsk1.value.length <8 || f_wpa.wpapsk1.value.length > 63)
					{
						alert("<?=$a_invalid_passphrase_len?>");
						f_wpa.wpapsk1.select();
						return false;
					}
					if(strchk_unicode(f_wpa.wpapsk1.value))
					{
						alert("<?=$a_invalid_passphrase?>");
						f_wpa.wpapsk1.select();
						return false;
					}
					f_final.f_wpa_psk_type.value="1";
				}
			}
			// assign final post variables
			switch (f.security_type.value)
			{
			case "2":	f_final.f_authentication.value="3";		break;
			case "3":	f_final.f_authentication.value="5";		break;
			case "4":	f_final.f_authentication.value="7";		break;
			default	:	break;
			}
			f_final.f_cipher.value=f_wpa.cipher_type.value;
			f_final.f_wpa_psk.value=f_wpa.wpapsk1.value;
		}
	}
	else
	{
		alert("Unknown Authentication Type.");
		return false;
	}
	f_final.submit();
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function chg_super_g()
{
	f=get_obj("frm");
	var dis=false;
	if(f.super_g_mode.value=="2")
	{
		dis=true;
		select_index(f.channel,"6");
		f.autochann.checked=f.en_xr.checked=false;
	}
	f.channel.disabled=	f.autochann.disabled=f.en_xr.disabled=dis;
	on_check_autochann();
}
/* draw tx rate list */
function drawRateList( id, type )
{
	var wlanMode = "<?=$wlanMode?>";
	var bandWidth = "<?=$bandWidth?>";
	var shortGuard = "<?=$shortGuard?>";
	var listOptions = new Array();
	if( type == "11g" )
	{
		listOptions = new Array("1","2","5.5","6","9","11","12","18","24","36","48","54");
	}
	else
	{
		if( wlanMode == "4" )
		{
			var condi = bandWidth.toString() + shortGuard.toString();
			switch( condi )
			{
			case "10":
				listOptions = new Array("0-6.5","1-13.0","2-19.5","3-26.0","4-39.0","5-52.0","6-58.5","7-65.0","8-13.0","9-26.0","10-39.0","11-52.0","12-78.0","13-104.0","14-117.0","15-130.0");
				break;
			case "11":
				listOptions = new Array("0-7.2","1-14.4","2-21.7","3-28.9","4-43.3","5-57.8","6-65.0","7-72.0","8-14.444","9-28.889","10-43.333","11-57.778","12-86.667","13-115.556","14-130.000","15-144.444");
				break;
			case "20":
				listOptions = new Array("0-13.5","1-27.0","2-40.5","3-54.0","4-81.0","5-108.0","6-121.5","7-135.0","8-27.0","9-54.0","10-81.0","11-108.0","12-162.0","13-216.0","14-243.0","15-270.0");
				break;
			case "21":
				listOptions = new Array("0-15.0","1-30.0","2-45.0","3-60.0","4-90.0","5-120.0","6-135.0","7-150.0","8-30.0","9-60.0","10-90.0","11-120.0","12-180.0","13-240.0","14-270.0","15-300.0");
				break;
			}
		}
		else if( wlanMode == "2" )
		{
			listOptions = new Array("6","9","12","18","24","36","48","54");
		}
		else if( wlanMode == "1" )
		{
			listOptions = new Array("1","2","5.5","11");
		}
	}
	document.write("<select name=\""+id+"\">");
	document.write("<option value=\"0\"><?=$m_best_auto?></option>\n");
	for( inx = 0; inx < listOptions.length; inx ++ )
	{
		if( type == "11g" )
			document.write("<option value=\""+listOptions[inx]+"\">"+listOptions[inx]+"</option>\n");
		else
			document.write("<option value=\""+(inx+1)+"\">"+listOptions[inx]+"</option>\n");
	}
	document.write("</select>");
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST"			value="final">
<input type="hidden" name="f_enable"			value="">
<input type="hidden" name="f_wps_enable"		value="">
<input type="hidden" name="f_wps_locksecurity"	value="">
<input type="hidden" name="f_ssid"				value="">
<input type="hidden" name="f_channel"			value="">
<input type="hidden" name="f_auto_channel"		value="">
<input type="hidden" name="f_super_g"			value="">
<input type="hidden" name="f_xr"				value="">
<input type="hidden" name="f_txrate"			value="">
<input type="hidden" name="f_wmm_enable"		value="">
<input type="hidden" name="f_ap_hidden"			value="">
<input type="hidden" name="f_authentication"	value="">
<input type="hidden" name="f_cipher"			value="">
<input type="hidden" name="f_wep_len"			value="">
<input type="hidden" name="f_wep_format"		value="">
<input type="hidden" name="f_wep_def_key"		value="">
<input type="hidden" name="f_wep"				value="">
<input type="hidden" name="f_wpa_psk_type"		value="">
<input type="hidden" name="f_wpa_psk"			value="">
<input type="hidden" name="f_radius_ip1"		value="">
<input type="hidden" name="f_radius_port1"		value="">
<input type="hidden" name="f_radius_secret1"	value="">
</form>
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
		?>
		<script>apply('check()'); echo ("&nbsp;"); cancel('');</script>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
		<? if ($support_wps==1) { require("/www/bsc_wlan_wps.php"); } ?>
		<div class="box">
			<h2><?=$m_title_wireless_setting?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<?=$td1?><?=$m_enable_wireless?></td>
				<?=$td2?><?=$symbol?>
					<input name="enable" id="enable" type="checkbox" onclick="on_check_enable()" value="1">
				<td>
			</tr>
			<tr>
				<?=$td1?><?=$m_wlan_name?></td>
				<?=$td2?><?=$symbol2?>
					<input name="ssid" id="ssid" type="text" size="20" maxlength="32" value="">
					&nbsp;<?=$m_wlan_name_comment?>
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_enable_auto_channel?></td>
				<?=$td2?><?=$symbol?>
					<input name="autochann" id="autochann" type="checkbox" onclick="on_check_autochann()" value="1">
				<td>
			</tr>
			<tr>
				<?=$td1?><?=$m_wlan_channel?></td>
				<?=$td2?><?=$symbol2?>
					<select name="channel" id="channel">
						<?require("/www/bsc_wlan_channel.php");?>
					</select>
				</td>
			</tr>
			</table>
			<div id="show_super_g" style="display:none">
				<table cellpadding="1" cellspacing="1" border="0" width="525">
				<tr>
					<?=$td1?><?=$m_super_g?></td>
					<?=$td2?><?=$symbol2?>
					<select name=super_g_mode onchange='chg_super_g();'>
						<option value='0'><?=$m_disabled?></option>
						<option value='1'><?=$m_super_g_without_turbo?></option>
						<option value='2'><?=$m_super_g_with_d_turbo?></option>
					</select>
					</td>
				</tr>
				<tr>
					<?=$td1?><?=$m_xr?></td>
					<?=$td2?><?=$symbol?>
						<input type=checkbox name=en_xr>
					</td>
				</tr>
				</table>
			</div>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<?=$td1?><?=$m_txrate?></td>
				<?=$td2?><?=$symbol2?>
				<!--
				<select name="txrate">
					<option value="0"><?=$m_best_auto?></option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="5.5">5.5</option>
					<option value="6">6</option>
					<option value="9">9</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="18">18</option>
					<option value="24">24</option>
					<option value="36">36</option>
					<option value="48">48</option>
					<option value="54">54</option>
				</select>
				-->
				<script language="javascript">
				<?
				if($support11n=="1")
				{
					echo "drawRateList(\"txrate\", \"11n\");\n";
				}
				else
				{
					echo "drawRateList(\"txrate\", \"11g\");\n";
				}
				?>
				</script>
				<?=$m_mbps?>
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_wmm_enable?></td>
				<?=$td2?><?=$symbol?>
					<input type=checkbox name=wmm_enable id=wmm_enable><?=$m_wlan_qos?>
				</td>
			<tr>
				<?=$td1?><?=$m_enable_ap_hidden?></td>
				<?=$td2?><?=$symbol?>
					<input name="aphidden" id="aphidden" type="checkbox" value="1">&nbsp;<?=$m_ap_hidden_comment?>
				<td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?=$m_title_wireless_security?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<?=$td1?><?=$m_security_mode?></td>
				<?=$td2?><?=$symbol2?>
					<select id="security_type" name="security_type" onChange="on_change_security_type()">
						<option value="0" selected><?=$m_disable_security?></option>
						<option value="1"><?=$m_enable_wep?></option>
						<option value="2"><?=$m_wpa_security?></option>
						<option value="3"><?=$m_wpa2_security?></option>
						<option value="4"><?=$m_wpa2_auto_security?></option>
					</select>
				</td>
			</tr>
			</table>
		</div>
		</form>
		<!-- ****************************no wep*******************************************-->
		<form name="frm_nowep" id="frm_nowep" method="post" action="<?=$MY_NAME?>.php">
		<input type="hidden" name="ACTION_POST" value="NOWEP">
		</form>

		<!-- **************************** wep  *******************************************-->
		<form name="frm_wep" id="frm_wep" method="post" action="<?=$MY_NAME?>.php">
		<input type="hidden" name="ACTION_POST" value="WEP">
		<div class="box" id="show_wep" style="display:none">
			<h2><?=$m_title_wep?></h2>
			<?require($LOCALE_PATH."/bsc_wlan_msg1.php");?>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<?=$td1?><?=$m_auth_type?></td>
				<?=$td2?><?=$symbol?>
					<select name="auth_type" id="auth_type">
						<option value="0"><?=$m_open?></option>
						<option value="1"><?=$m_shared_key?></option>
					</select>
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_wep_key_len?></td>
				<?=$td2?><?=$symbol?>
					<select id="wep_key_len" name="wep_key_len" size=1 onChange="chg_wep_type()">
						<option value="64"><?=$m_64bit_wep?></option>
						<option value="128"><?=$m_128bit_wep?></option>
					</select>
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_default_wep_key?></td>
				<?=$td2?><?=$symbol?>
					<select name="wep_def_key" id="wep_def_key">
						<option value="1" selected><?=$m_wep_key?> 1</option>
						<option value="2"><?=$m_wep_key?> 2</option>
						<option value="3"><?=$m_wep_key?> 3</option>
						<option value="4"><?=$m_wep_key?> 4</option>
					</select>
				</td>
			</tr>
			</table>
			<div id="wep_64"	style="display:none"><script>print_keys("wepkey_64","10");</script></div>
			<div id="wep_128"	style="display:none"><script>print_keys("wepkey_128","26");</script></div>
		</div>
		</form>
		<!-- **************************** WPA, WPA2, WPA2-auto *********************************-->
		<form name="frm_wpa" id="frm_wpa" method="post" action="<?=$MY_NAME?>.php">
		<input type="hidden" name="ACTION_POST" value="WPA">
		<div class="box" id="show_wpa" style="display:none">
			<div id="title_wpa"		style="display:none"><h2><?=$m_title_wpa?></h2>		 <p><?=$m_dsc_wpa?></p>		</div>
			<div id="title_wpa2"	style="display:none"><h2><?=$m_title_wpa2?></h2>	 <p><?=$m_dsc_wpa2?></p>	</div>
			<div id="title_wpa2_auto" style="display:none"><h2><?=$m_title_wpa2_auto?></h2><p><?=$m_dsc_wpa2_auto?></p></div>
			<div>
				<table>
				<tr>
					<?=$td1?><?=$m_cipher_type?></td>
					<?=$td2?><?=$symbol?>
						<select name="cipher_type">
						<option value="2"><?=$m_tkip?></option>
						<option value="3"><?=$m_aes?></option>
						<option value="4"><?=$m_both?></option>
						</select>
					</td>
				</tr>
				<tr>
					<?=$td1?><?=$m_psk_eap?></td>
					<?=$td2?><?=$symbol?>
						<select name="psk_eap" id="psk_eap" onchange="chg_psk_eap()">
						<option value="psk"><?=$m_psk?></option>
						<option value="eap"><?=$m_eap?></option>
						</select>
					</td>
				</tr>
				</table>
			</div>
			<!-- **************************** PSK *********************************-->
			<div id="psk_setting" style="display:none">
				<table id=passphrase>
					<tr>
						<?=$td1?><?=$m_passphrase?></td>
						<?=$td2?><?=$symbol?>
							<input type="text" id="wpapsk1" name="wpapsk1" size="40" maxlength="64" value="">
					        </td>
					</tr>
					<tr>
					        <?=$td1?></td>
						<?=$td2?>&nbsp;&nbsp;<?=$m_psk_hint_wording?></td>
					</tr>
				</table>
			</div>
			<!-- **************************** EAP *********************************-->
			<div id="eap_setting" style="display:none">
				<table>
					<tr><td class=l_tb><?=$m_8021x?></td></tr>
					<tr>
						<td>
						<table>
							<tr>
								<?=$td3?><?=$m_radius1?>&nbsp;</td><?=$td2?><?=$m_ipaddr?></td>
								<?=$td2?><?=$symbol?>
									<input id="srv_ip1" name="srv_ip1" maxlength=15 size=15 value="">
								</td>
							</tr>
							<tr>
								<?=$td3?></td><?=$td2?><?=$m_port?></td>
								<?=$td2?><?=$symbol?>
								<input type="text" id="srv_port1" name="srv_port1" size="8" maxlength="5" value="">
								</td>
							</tr>
							<tr>
								<?=$td3?></td><?=$td2?><?=$m_shared_sec?></td>
								<?=$td2?><?=$symbol?>
								<input type="password" id="srv_sec1" name="srv_sec1" size="50" maxlength="64" value="">
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
			</div>
			<!-- **************************** end of EAP *********************************-->
		</div>
		<!-- **************************** end of WPA, WPA2, WPA2-auto *********************************-->
		</form>

		<div id="box_bottom">
			<script>apply('check()'); echo ("&nbsp;"); cancel('');</script>
		</div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</body>
</html>
