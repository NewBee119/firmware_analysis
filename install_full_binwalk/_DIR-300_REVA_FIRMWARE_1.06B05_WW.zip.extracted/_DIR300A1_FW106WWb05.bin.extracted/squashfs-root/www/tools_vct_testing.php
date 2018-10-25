<? /* vi: set sw=4 ts=4: */
$MY_NAME		="tools_vct_testing";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>
<script>
nLan=parseInt("<?=$port_id?>", [10]);
port_id=nLan+1;
port_link="<?query("/runtime/switch/port:".$port_id."/linkType");?>";

function doNext()
{
	self.location.href="tools_vct_info.xgi?set/runtime/switch/getlinktype=1&port_id="+port_id+"&nLan="+nLan+"&set/runtime/cabletest:"+port_id+"/testnow=ok";
}
</script>
<body bgcolor=#CCCCCC text=#000000 topmargin=0 leftmargin=0 onload="doNext()">
<table width=100% height=100% align=center valign=middle>
<tr>
	<td width=100% class=bc_tb>
		<font color=blue><?=$m_diagnosing?></font>
	</td>
</tr>
</table>
</body>
</html>
