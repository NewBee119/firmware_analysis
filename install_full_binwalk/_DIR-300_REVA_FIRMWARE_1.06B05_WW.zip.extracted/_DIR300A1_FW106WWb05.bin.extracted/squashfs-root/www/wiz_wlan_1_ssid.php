<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wlan_1_ssid";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		="1_ssid";
$WIZ_PREV		="0_flowchart";
//$WIZ_nex_ will be assigned again in __wiz_wlan_action.php
//$wiz_next		="3_set_sec"; 
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	require("/www/__wiz_wlan_action.php");
	$ACTION_POST="";
	require("/www/wiz_wlan_".$WIZ_NEXT.".php");
	exit;
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$db_ssid=get("h",$G_WIZ_PREFIX_WLAN."/wireless/ssid");
if( $db_ssid == "" )
{ $db_ssid = get("h","/wireless/ssid"); }
$db_sec_type= query($G_WIZ_PREFIX_WLAN."/wireless/authtype");
$db_wep_type= query($G_WIZ_PREFIX_WLAN."/wireless/wepmode");
$db_wiz_type= query($G_WIZ_PREFIX_WLAN."/wireless/wiz_type");
if($db_sec_type=="")
{
	$G_WIZ_PREFIX_WLAN_EMPTY=1;
	$db_sec_type=query("/wireless/authtype");
	$db_wep_type=query("/wireless/encrypttype");
}
else
{
	$G_WIZ_PREFIX_WLAN_EMPTY=0;
	$db_wep_type=query($G_WIZ_PREFIX_WLAN."/wireless/wepmode");
}
if($db_sec_type==""){$db_sec_type=0;}
if($db_wep_type==""){$db_wep_type=0;}
/* --------------------------------------------------------------------------- */
?>

<script>
<?require("/www/md5.js");?>
function get_random_char()
{
     var number_list = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	 var number = Math.round(Math.random()*62);
	 return(number_list.substring(number, number + 1));
}
function generate_psk(length)
{
	var i = 0;
	var len = 10;
	var psk="";
	if( length > 8 && length < 63 )
		len = length;
	for (i = 0; i < len; i++)
	{
		psk += get_random_char();
	}
	return psk;
}
function create_wep_key128(passpharse, pharse_len)
{
    var pseed2 = "";
	var md5_str = "";
	var count;
	for(var i = 0; i < 64; i++)
	{
		count = i % pharse_len;
		pseed2 += passpharse.substring(count, count+1);
	}
	md5_str = calcMD5(pseed2);
	return md5_str.substring(0, 26).toUpperCase();
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var db_sec_type="<?=$db_sec_type?>";
	var db_wep_type="<?=$db_wep_type?>";
	var db_wiz_type="<?=$db_wiz_type?>";
	var G_WIZ_PREFIX_WLAN_EMPTY=<?=$G_WIZ_PREFIX_WLAN_EMPTY?>;
	if(db_sec_type==5 || db_sec_type==3 || db_sec_type == 7)	
	{
		if( db_wiz_type == 1)
			f.sec_sel[1].checked=true;
		else
			f.sec_sel[0].checked=true;
	}
	else if(db_wep_type==1)
	{
		if( db_wiz_type == 1 )
			f.sec_sel[1].checked=true;
		else
			f.sec_sel[0].checked=true;
		f.sec_wpa.checked=false;
	}
	else
	{
		f.sec_sel[0].checked=true;
		f.sec_wpa.checked=false;
	}
	if(G_WIZ_PREFIX_WLAN_EMPTY == 1)
		f.sec_wpa.checked = true;
	
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	// do check here ....
	var ssid = f.tmp_ssid.value;
	if(is_blank(f.tmp_ssid.value)==true)
	{
		alert("<?=$a_empty_ssid?>");
		f.tmp_ssid.select();
		return false;
	}
	if(strchk_unicode(f.tmp_ssid.value)==true)
	{
		alert("<?=$a_invalid_ssid?>");
		f.tmp_ssid.select();
		return false;
	}
	f.ssid.value = f.tmp_ssid.value;
	f.tmp_ssid.disabled = true;

	if( f.sec_sel[0].checked )
	{
		f.full_secret.value = create_wep_key128(ssid,ssid.length);
		f.sec_type.value = 1;
		f.wep_length.value=128;
		f.wep_type.value=2;
		if( f.sec_wpa.checked )
		{
			f.sec_type.value = 7;
			f.full_secret.value = generate_psk(10);
			f.wep_length.disabled=true;
			f.wep_type.disabled=true;
			f.psk_type.value=1;
		}
	}
	if( f.sec_sel[1].checked )
	{
		f.sec_type.value = 1;
		if( f.sec_wpa.checked )
		{
			f.sec_type.value = 7;
		}
		f.type.value = 1;
		f.wep_length.disabled=true;
		f.wep_type.disabled=true;
		f.psk_type.disabled=true;
		f.full_secret.disabled=true;
	}
	return true;
}
function go_prev()
{
	self.location.href="<?=$POST_ACTION?>?TARGET_PAGE=<?=$WIZ_PREV?>";
}
</script>
<body onLoad="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>" OnSubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="TARGET_PAGE" value="<?=$MY_ACTION?>">
<input type="hidden" name="ssid" value="<?=$db_ssid?>">
<input type="hidden" name="sec_type" value="1">
<input type="hidden" name="full_secret" value="">
<input type="hidden" name="wep_type" value="1">
<input type="hidden" name="wep_length" value="128">
<input type="hidden" name="psk_type" value="1">
<input type="hidden" name="type" value="0">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table>
			<tr>
				<td width=10>&nbsp;</td>
				<td>
					<table>
						<tr>
						<td width=40% class=r_tb><?=$m_wlan_ssid?></td>
						<td ><input type=text name=tmp_ssid size="20" maxlength="32" value="<?=$db_ssid?>"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width=10><input type=radio name=sec_sel value=7></td>
				<td class="l_tb"><?=$m_auto_set?></td>
			</tr>
			<tr>
				<td width=10>&nbsp;</td>
				<td class="l_tb"><?=$m_auto_dsc?></td>
			</tr>
			<tr>
				<td><input type=radio name=sec_sel value=1></td>
				<td class="l_tb"><?=$m_manual_set?></td>
			</tr>
			<tr>
				<td width=10>&nbsp;</td>
				<td class="l_tb"><?=$m_manual_dsc?></td>
			</tr>
			<tr>
				<td width=10><input type=checkbox name=sec_wpa checked="checked"></td>
				<td class="l_tb"><?=$m_manual_wpa?></td>
				
			</tr>
			</table>
		<br>
		<center><script>prev("");next("");exit_wlan_wiz();</script></center>
		<br>
<!-- ________________________________  Main Content End _______________________________ -->
		</div>
		<br>
	</td>
	<td width=10%></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
