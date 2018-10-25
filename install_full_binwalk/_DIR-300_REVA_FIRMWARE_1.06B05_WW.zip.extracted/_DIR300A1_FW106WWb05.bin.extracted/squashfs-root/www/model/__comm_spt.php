<?require("/www/model/__html_head.php");?>
<body <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$SPT_FILE_NAME?>.php">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<table <?=$G_MAIN_TABLE_ATTR?> height=100%>
<tr valign=top>
	<td id="maincontent">
<!-- ________________________________ Main Content Start ______________________________ -->
	<div id="box_header"><?require($LOCALE_PATH."/dsc/dsc_".$SPT_FILE_NAME.".php");?></div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
</tr>
</table>
<br><?require("/www/model/__copyright.php");?><br>
</form>
</body>
</html>
