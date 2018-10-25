<?
/* vi: set sw=4 ts=4: */
require("/www/model/__html_head.php");
?>
<script>
function init()
{
	var str="<?=$NEXT_PAGE?>.xgi?random_num="+generate_random_str();
<?
	if ($XGISET_STR!="")
	{
		echo "	str+=\"&".$XGISET_STR."\";\n";
	}
	if ($SUBMIT_STR!="")
	{
		echo "	str+=exe_str(\"submit COMMIT;".$SUBMIT_STR."\");\n";
	}
	if ($XGISET_AFTER_COMMIT_STR!="")
	{
		echo "	str+=\"&".$XGISET_AFTER_COMMIT_STR."\";\n";
	}
	if ($ONLY_DO_SUBMIT_STR!="")
	{
		echo "	str+=exe_str(\"".$ONLY_DO_SUBMIT_STR."\");\n";
	}
?>
	self.location.href=str;
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
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
			<h1><?=$m_saving_title?></h1><br><br><br>
			<center><?=$m_saving_dsc?><br><br><br><br>
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
