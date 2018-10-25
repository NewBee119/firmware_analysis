<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		= "wiz_wan_3_sel_wan";
$MY_MSG_FILE	= $MY_NAME.".php";

$MY_ACTION		= "3_sel_wan";
$WIZ_PREV		= "2_timezone";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	require("/www/__wiz_wan_action.php");
    $ACTION_POST="";
    require("/www/wiz_wan_".$WIZ_NEXT.".php");
    exit;
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$tmp_mode = query($G_WIZ_PREFIX_WAN."/wan_type");
if ($tmp_mode == "")
{
	$tmp_mode = query("/wan/rg/inf:1/mode");
	if ($tmp_mode == "3")
	{
		$wan2_type=query("/wan/rg/inf:2/mode");
		if ($wan2_type=="1" ||$wan2_type=="2"){$tmp_mode="r_pppoe";}
	}
	if ($tmp_mode == "4")
	{
		if (query("/wan/rg/inf:1/pptp/physical")=="1"){$tmp_mode="r_pptp";}
	}
	if ($tmp_mode == "5")
	{
		if (query("/wan/rg/inf:1/l2tp/physical")=="1"){$tmp_mode="r_l2tp";}
	}

}
/* --------------------------------------------------------------------------- */
?>

<script>
var tmp_mode="<?=$tmp_mode?>";
function select_wan_type()
{
	var f = get_obj("frm");
	
	f.wan_type[f.select_isp.value].checked = true;
}

/* page init functoin */
function init()
{
}
/* parameter checking */
function check()
{
	return true;
}
function go_prev()
{
	self.location.href="<?=$POST_ACTION?>?TARGET_PAGE=<?=$WIZ_PREV?>";
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="TARGET_PAGE" value="<?=$MY_ACTION?>">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
<!-- ________________________________ Main Content Start ______________________________ -->
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		<table>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input name="wan_type" id="wan_type" type="radio" value="2"<?if ($tmp_mode==2){echo " checked";}?>>
				<STRONG><?=$m_title_dhcp?></STRONG>
				<div><?=$m_desc_dhcp?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td>
				<input name="wan_type" id="wan_type" type="radio" value="3"<?if ($tmp_mode==3){echo " checked";}?>>
				<STRONG><?=$m_title_pppoe?></STRONG>
				<div><?=$m_desc_pppoe?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td>
				<input name="wan_type" id="wan_type" type="radio" value="4"<?if ($tmp_mode==4){echo " checked";}?>>
				<STRONG><?=$m_title_pptp?></STRONG>
				<div><?=$m_desc_pptp?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td>
				<input name="wan_type" id="wan_type" type="radio" value="5"<?if ($tmp_mode==5){echo " checked";}?>>
				<STRONG><?=$m_title_l2tp?></STRONG>
				<div><?=$m_desc_l2tp?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td><input name="wan_type" id="wan_type" type="radio" value="1"<?if ($tmp_mode==1){echo " checked";}?>>
				<STRONG><?=$m_title_static?></STRONG>
				<div><?=$m_desc_static?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td><input name="wan_type" id="wan_type" type="radio" value="r_pptp"<?if ($tmp_mode=="r_pptp"){echo " checked";}?>>
				<STRONG><?=$m_title_r_pptp?></STRONG>
				<div><?=$m_desc_r_pptp?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td><input name="wan_type" id="wan_type" type="radio" value="r_l2tp"<?if ($tmp_mode=="r_l2tp"){echo " checked";}?>>
				<STRONG><?=$m_title_r_l2tp?></STRONG>
				<div><?=$m_desc_r_l2tp?></div>
			</td>
		</tr>
		<tr>
			<td class=form_label>&nbsp;</td>
			<td><input name="wan_type" id="wan_type" type="radio" value="r_pppoe"<?if ($tmp_mode=="r_pppoe"){echo " checked";}?>>
				<STRONG><?=$m_title_r_pppoe?></STRONG>
				<div><?=$m_desc_r_pppoe?></div>
			</td>
		</tr>
		</table>
		<br>
		<center><script>prev("");next("");exit();</script></center>
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
