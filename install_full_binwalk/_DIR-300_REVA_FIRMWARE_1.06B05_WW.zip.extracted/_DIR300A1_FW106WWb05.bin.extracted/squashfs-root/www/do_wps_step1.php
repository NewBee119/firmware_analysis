<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="do_wps_step1";
$MY_MSG_FILE	=$MY_NAME.".php";
$MY_ACTION		="step1";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST=="DO_PIN")
{
	require("/www/model/__admin_check.php");
	set("/runtime/wps/enrollee/cfg_method", "pin");
	set("/runtime/wps/enrollee/pin", $pin);
	set("/runtime/wps/result", "");
	require("/www/do_wps_step2_pin.php");
	exit;
}
else if ($ACTION_POST=="DO_PBC")
{
	require("/www/model/__admin_check.php");
	set("/runtime/wps/result", "");
	require("/www/do_wps_step2_pbc.php");
	exit;
}
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
?>
<script>
function init()			
{
	var f=get_obj("frm");
<?	
	$cfg_wps_locksecurity = query("/wireless/wps/locksecurity");
	if($cfg_wps_locksecurity == "1")	{echo 'f.pin.disabled = true; f.do_pin.disabled = true;';}
	else								{echo 'f.pin.disabled = false; f.do_pin.disabled = false;';}
?> 
	return true;
}
function verify_wps_pin(pin)
{
	var i, c, csum = 0;
	
	if (pin.length != 8) return false;
	for (i=0; i < pin.length; i++)
	{
		c = pin.charAt(i);
		if (c > '9' || c < '0') return false;
		csum += parseInt(c,[10]) * (((i%2)==0) ? 3:1);
	}
	return ((csum % 10)==0) ? true : false;
}

function do_wps(post)
{
	var f = get_obj("frm");
	if (post == "DO_PIN")
	{
		if (verify_wps_pin(f.pin.value)== false)
		{
			alert("<?=$a_invalid_pin?>");
			field_focus(f.pin, "**");
			return false;
		}
	}
	f.ACTION_POST.value = post;
	f.submit();
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="TARGET_PAGE" value="<?=$MY_ACTION?>">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
<!-- ________________________________ Main Content Start ______________________________ -->
		<p><?=$m_wps_pin_desc?></p>
		<table width=95%>
		<tr>
			<td class=br_tb width=40%><?=$m_pin?> :&nbsp;</td>
			<td>
				<input type="text" name="pin" id="pin" size="10" maxlength="8">
				<input type="button" id="do_pin" value="<?=$m_connect?>" onclick="do_wps('DO_PIN');">
			</td>
		</tr>
		</table>
		<p><?=$m_wps_pbc_desc?></p>
		<table width=95%>
		<tr>
			<td class=br_tb width=40%><?=$m_pbc?> :&nbsp;</td>
			<td><input type="button" value="<?=$m_virtual_button?>" onclick="do_wps('DO_PBC');"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><?=$m_wps_pbc_comment?></td>
		</tr>
		</table>
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
