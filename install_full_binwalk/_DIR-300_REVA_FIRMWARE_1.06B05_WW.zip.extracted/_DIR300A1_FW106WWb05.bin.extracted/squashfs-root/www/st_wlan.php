<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_wlan";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
/* --------------------------------------------------------------------------- */

/* --------------------------------------------------------------------------- */
$OTHER_META="<meta http-equiv=Refresh content='10;url=st_wlan.php'>";
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>

<script>
/* page init functoin */
function show_conn_time(conn_time)
{
	var t=second_to_daytime(conn_time);
	var str;
	str=(t[0]>0 ? t[0]+" <?=$m_days?> ":"")+(t[1]>0 ? t[1]+" <?=$m_hrs?> ":"")+(t[2]>0 ? t[2]+" <?=$m_mins?> ":"")+(t[3]>0 ? t[3]+" <?=$m_secs?>":"");
	document.write(str);
}

</script>
<body <?=$G_BODY_ATTR?>>
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
		<?require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_context_title_wlan?></h2>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr id="box_header">
				<td class=bc_tb><?=$m_conn_time?></td>
				<td class=bc_tb><?=$m_macaddr?></td>
				<td class=bc_tb><?=$m_mode?></td>
			</tr>
			<?
			for("/runtime/stats/wireless/client")
			{
				echo "<td class=c_tb><script>show_conn_time('".query("time")."');</script></td>\n";
				echo "<td class=c_tb>".query("mac")."</td>\n";
				echo "<td class=c_tb>".query("mode")."</td></tr>\n";
			}
			?>
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
