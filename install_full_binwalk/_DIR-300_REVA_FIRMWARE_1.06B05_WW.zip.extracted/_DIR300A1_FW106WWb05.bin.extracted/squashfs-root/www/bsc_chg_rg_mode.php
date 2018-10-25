<?
/* vi: set sw=4 ts=4: */
$MY_NAME        ="bsc_chg_rg_mode";
$MY_MSG_FILE    =$MY_NAME.".php";
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
$bridge=query("/bridge");
?>
<body <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=middle align=center>
	<td>
	<br>
	<!-- ________________________________ Main Content Start ______________________________ -->
	<table width=80%>
	<tr>
		<td id="box_header">
			<h1><?=$m_title_chg_rg_mode?></h1><br><br>
			<center>
<?
if($bridge!="1")
{
	require($LOCALE_PATH."/__router_mode_msg.php");
	$ipaddr = query("/lan/ethernet/ip");
}
else
{
	if (query("/wan/rg/inf:1/mode")=="1")
	{
		require($LOCALE_PATH."/__bridge_mode_msg1.php");
		$ipaddr = query("/wan/rg/inf:1/static/ip");
	}
	else
	{
		require($LOCALE_PATH."/__bridge_mode_msg2.php");
		$ipaddr = "";
	}
}
if ($ipaddr!="") { echo "<a href=http:\/\/".$ipaddr.">http:\/\/".$ipaddr."</a>\n"; }
?>
			</center><br>
		</td>
	</tr>
	</table>
	<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html><? exit; ?>
