<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_naptinfo";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
$SUB_CATEGORY	="st_session";
$SESSION_MAIN	="st_session";
/* --------------------------------------------------------------------------- */

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>

<script language="JavaScript">
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
<!-- ________________________________ Main Content Start ______________________________ -->

		<div id="box_header">
		<?require($LOCALE_PATH."/dsc/dsc_".$SESSION_MAIN.".php");?>
		<br><input type=button name=bt_back id="bt_back" value=<?=$m_back?> onClick="self.location.href='<?=$SESSION_MAIN?>.php'">
		</div>
		<div class="box" id="refreshing">
			<h2><?=$m_context_title?></h2><br>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
				<tr>
					<td width=50  class=bc_tb><?=$m_protocol?></td>
					<td width=100 class=bc_tb><?=$m_sip?></td>
					<td width=100 class=bc_tb><?=$m_sport?></td>
					<td width=100 class=bc_tb><?=$m_dip?></td>
					<td width=100 class=bc_tb><?=$m_dport?></td>
				</tr>
<?
for("/runtime/stats/naptsession")
{
	$q_sip=query("srcip");
	if($q_sip==$srcip)
	{
		$tab1="\t\t\t\t";
		$tab2="\t\t\t\t\t";
		echo $tab1."<tr>";
		echo $tab2."<td class=c_tb>";
		map("tcp","1","TCP",*,"UDP");
		echo "</td>";

		echo $tab2."<td class=c_tb>".$q_sip."</td>";
		echo $tab2."<td class=c_tb>".query("sport")."</td>";
		echo $tab2."<td class=c_tb>".query("dstip")."</td>";
		echo $tab2."<td class=c_tb>".query("dport")."</td>";
		echo $tab1."</tr>";
	}
}
?>
			</table>
			<br>
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
