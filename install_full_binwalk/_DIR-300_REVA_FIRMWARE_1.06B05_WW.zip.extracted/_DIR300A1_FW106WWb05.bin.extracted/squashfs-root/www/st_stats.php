<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_stats";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
?>

<script>
/* page init functoin */
function init()
{
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
<form name="frm" id="frm">
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
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_context_title_stats?></h2>
			<br><center>
			<input type=button name=refresh value="<?=$m_b_refresh?>" onClick="window.location.href='st_stats.php'">
			<input type=button name=reset value="<?=$m_b_reset?>" onClick="<?
				if($AUTH_GROUP=="0")
				{
					echo "window.location.href='st_stats.xgi?set/runtime/stats/resetCounter=1'";
				}
				else
				{
					echo "window.location.href='permission_deny.php?NEXT_LINK=".$MY_NAME.".php'";
				}
			?>">
			</center>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr id="box_header">
				<td class=bl_tb>&nbsp;</td>
				<td class=bl_tb><?=$m_receive?></td>
				<td class=bl_tb><?=$m_transmit?></td>
			</tr>
<? if($router!="1"){echo "<!--\n";}?>
			<tr>
				<td width=111 height=20 class=bl_tb><?=$m_wan?></td>
				<td height=20 class=l_tb><?map("/runtime/stats/wan/inf:1/rx/packets","",0);?> <?=$m_packets?> <?=$Packets?></td>
				<td height=20 class=l_tb><?map("/runtime/stats/wan/inf:1/tx/packets","",0);?> <?=$m_packets?> <?=$Packets?></td>
			</tr>
<? if($router!="1"){echo "-->\n";}?>
			<tr>
				<td width=111 height=20 class=bl_tb><?if($router!="1"){echo $m_wired;}else{echo $m_lan;}?></td>
				<td height=20 class=l_tb><?map("/runtime/stats/lan/rx/packets","",0);?> <?=$m_packets?> <?=$Packets?></td>
				<td height=20 class=l_tb><?map("/runtime/stats/lan/tx/packets","",0);?> <?=$m_packets?> <?=$Packets?></td>
			</tr>
			<tr>
				<td width=111 height=20 class=bl_tb><?=$m_wlan_11g?></td>
				<td height=20 class=l_tb><?map("/runtime/stats/wireless/rx/packets","",0);?> <?=$m_packets?> <?=$Packets?></td>
				<td height=20 class=l_tb><?map("/runtime/stats/wireless/tx/packets","",0);?> <?=$m_packets?> <?=$Packets?></td>
			</tr>
			</table>
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
