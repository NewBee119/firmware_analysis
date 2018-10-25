<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="adv_apx";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="adv";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");

	$dirty = 0;
	if($apxEnable			!= query("/apx/enable"))			{ $dirty++; set("/apx/enable",				$apxEnable); }
	if($apxTcpAccEnable		!= query("/apx/tcpAccEnable"))		{ $dirty++; set("/apx/tcpAccEnable",		$apxTcpAccEnable); }
	if($apxShaperEnable		!= query("/apx/shaperEnable"))		{ $dirty++; set("/apx/shaperEnable",		$apxShaperEnable); }
	if($apxWanAutoDetect	!= query("/apx/wanAutoDetect"))		{ $dirty++; set("/apx/wanAutoDetect",		$apxWanAutoDetect); }
	if($apxWanKbps			!= query("/apx/wanKbps"))			{ $dirty++; set("/apx/wanKbps",				$apxWanKbps); }
	if($apxWanInKbps		!= query("/apx/wanInKbps"))			{ $dirty++; set("/apx/wanInKbps",			$apxWanInKbps); }
	if($apxVoipAccEnable	!= query("/apx/voipAccEnable"))		{ $dirty++; set("/apx/voipAccEnable",		$apxVoipAccEnable); }
	if($apxVoipSkipPackets	!= query("/apx/voipSkipPackets"))	{ $dirty++; set("/apx/voipSkipPackets",		$apxVoipSkipPackets); }
	if($apxHostFairEnable	!= query("/apx/hostFairEnable"))	{ $dirty++; set("/apx/hostFairEnable",		$apxHostFairEnable); }

	/* Check dirty */
	$SUBMIT_STR="";
	if ($dirty > 0)			{$SUBMIT_STR="submit APPEX";}
	if($db_dirty2 > 0)	{$SUBMIT_STR=$SUBMIT_STR.";submit APPEX";}

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$apxEnable				=query("/apx/enable");
$apxTcpAccEnable		=query("/apx/tcpAccEnable");
$apxShaperEnable		=query("/apx/shaperEnable");
$apxWanAutoDetect		=query("/apx/wanAutoDetect");
$apxWanKbps				=query("/apx/wanKbps");
$apxWanInKbps			=query("/apx/wanInKbps");
$apxVoipAccEnable		=query("/apx/voipAccEnable");
$apxVoipSkipPackets		=query("/apx/voipSkipPackets");
$apxHostFairEnable		=query("/apx/hostFairEnable");

/* --------------------------------------------------------------------------- */
?>

<script>
function on_click_apx(form)
{
	var f = get_obj(form);

	if(f.apxEnable.checked == true)
	{
		get_obj("apx_acc").style.display="";
		get_obj("apx_shaping").style.display="";
		get_obj("apx_voip").style.display="";
		if(f.apxShaperEnable.checked == true)
		{ get_obj("apx_hostfair").style.display=""; }
		else
		{ get_obj("apx_hostfair").style.display="none"; }
	}
	else
	{
		get_obj("apx_acc").style.display="none";
		get_obj("apx_shaping").style.display="none";
		get_obj("apx_voip").style.display="none";
		get_obj("apx_hostfair").style.display="none";
	}


	f.apxWanAutoDetect.disabled = f.apxShaperEnable.checked? false : true;
	f.apxWanKbps.disabled = f.apxWanInKbps.disabled =
		(f.apxShaperEnable.checked && !f.apxWanAutoDetect.checked)? false : true;

	f.apxVoipSkipPackets.disabled = f.apxVoipAccEnable.checked? false : true;
}

/* page init functoin */
function init()
{
    var f=get_obj("frm");

	// init here ...
	f.apxEnable.checked 			= <? if ($apxEnable == "1") {echo "true";} else {echo "false";} ?>;
	f.apxTcpAccEnable.checked 		= <? if ($apxTcpAccEnable == "1") {echo "true";} else {echo "false";} ?>;
	f.apxShaperEnable.checked 		= <? if ($apxShaperEnable == "1") {echo "true";} else {echo "false";} ?>;
	f.apxWanAutoDetect.checked 		= <? if ($apxWanAutoDetect == "1") {echo "true";} else {echo "false";} ?>;
	f.apxWanKbps.value				= "<?=$apxWanKbps?>";
	f.apxWanInKbps.value			= "<?=$apxWanInKbps?>";
	f.apxVoipAccEnable.checked 		= <? if ($apxVoipAccEnable == "1") {echo "true";} else {echo "false";} ?>;
	f.apxVoipSkipPackets.value		= "<?=$apxVoipSkipPackets?>";
	f.apxHostFairEnable.checked 	= <? if ($apxHostFairEnable == "1") {echo "true";} else {echo "false";} ?>;

    on_click_apx("frm");
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
			<h2><?=$m_title_apx?></h2>
			<p><?=$m_desc_apx?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxEnable?>&nbsp;:&nbsp;</td>
				<td class="l_tb" width="70%">
					<input type="checkbox" value=1 id=apxEnable name=apxEnable onclick=on_click_apx("frm")>
				</td>
			</tr>
			</table>
		</div>

		<div class="box" id=apx_acc style="display:none">
			<h2><?=$m_title_apx_acc?></h2>
			<p><?=$m_desc_apx_acc?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxTcpAccEnable?>&nbsp;:&nbsp;</td>
				<td class="l_tb" width="70%">
					<input type="checkbox" value=1 id=apxTcpAccEnable name=apxTcpAccEnable onclick=on_click_apx("frm")>
				</td>
			</tr>
			</table>
		</div>
		<div class="box" id=apx_shaping style="display:none">
			<h2><?=$m_title_apx_shaping?></h2>
			<p><?=$m_desc_apx_shaping?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxShaperEnable?>&nbsp;:&nbsp;</td>
				<td class="l_tb" width="70%">
					<input type="checkbox" value=1 id=apxShaperEnable name=apxShaperEnable onclick=on_click_apx("frm")>
				</td>
			</tr>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxWanAutoDetect?>&nbsp;:&nbsp;</td>
				<td width="70%"><input type="checkbox" value=1 id=apxWanAutoDetect name=apxWanAutoDetect onclick=on_click_apx("frm")></td>
			</tr>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxWanKbps?>&nbsp;:&nbsp;</td>
				<td width="70%"><input maxlength=10 id=apxWanKbps name=apxWanKbps size=10 value=""><?=$m_apxWanKbps_default?></td>
			</tr>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxWanInKbps?>&nbsp;:&nbsp;</td>
				<td width="70%"><input maxlength=10 id=apxWanInKbps name=apxWanInKbps size=10 value=""><?=$m_apxWanInKbps_default?></td>
			</tr>
			</table>
		</div>
		<div class="box" id=apx_voip style="display:none">
			<h2><?=$m_title_apx_voip?></h2>
			<p><?=$m_desc_apx_voip?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxVoipAccEnable?>&nbsp;:&nbsp;</td>
				<td class="l_tb" width="70%">
					<input type="checkbox" value=1 id=apxVoipAccEnable name=apxVoipAccEnable onclick=on_click_apx("frm")>
				</td>
			</tr>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxVoipSkipPackets?>&nbsp;:&nbsp;</td>
				<td width="70%"><input maxlength=10 id=apxVoipSkipPackets name=apxVoipSkipPackets size=10 value=""><?=$m_apxVoipSkipPackets_default?></td>
			</tr>
			</table>
		</div>
		<div class="box" id=apx_hostfair style="display:none">
			<h2><?=$m_title_apx_hostfair?></h2>
			<p><?=$m_desc_apx_hostfair?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class="r_tb" width="30%"><?=$m_apxHostFairEnable?>&nbsp;:&nbsp;</td>
				<td class="l_tb" width="70%">
					<input type="checkbox" value=1 id=apxHostFairEnable name=apxHostFairEnable onclick=on_click_apx("frm")>
				</td>
			</tr>
			</table>
		</div>
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
