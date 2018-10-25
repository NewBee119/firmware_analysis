<?
/* vi: set sw=4 ts=4: */
$MY_NAME	="tools_vct_info";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY	="tools";

$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>
<script>
port_id="<?=$port_id?>";
linkType=["<?=$m_disconnected?>","<?=$m_100full?>","<?=$m_100half?>","<?=$m_10full?>","<?=$m_10half?>"];
txrxstat=["<?=$m_error?>","<?=$m_normal_cable?>","<?=$m_open_cable?>","<?=$m_short_cable?>"];

function getInfo()
{
	switch(port_id)
	{
	case "1":
		cable_name="<?=$m_wan?>";
		linkStat="<?query("/runtime/wan/inf:1/linkType");?>";
		break;
	default:
		cable_name="<?=$m_lan?>"+eval(parseInt("<?=$port_id?>", [10])-1);
		linkStat="<?$lan_id=$port_id-1; query("/runtime/switch/port:".$lan_id."/linkType");?>";
		break;
	}
	txstatus="<?query("/runtime/cabletest:".$port_id."/txstatus");?>";
	rxstatus="<?query("/runtime/cabletest:".$port_id."/rxstatus");?>";
	txmeter="<?query("/runtime/cabletest:".$port_id."/txmeter");?>";
	rxmeter="<?query("/runtime/cabletest:".$port_id."/rxmeter");?>";
}

function getConnectString(s)
{
	return linkType[isNaN(parseInt(s, [10]))? 0: parseInt(s, [10])];
}

function getStatString(s)
{
	return txrxstat[isNaN(parseInt(s, [10]))? 0: parseInt(s, [10])];
}

function generateInfo()
{
	getInfo();
	var str=new String("");
	str+="<tr>";
	str+="<td width=11% height=76 align=left><b><font face=Arial size=2>"+cable_name+"</font></b></td>";
	if (linkStat != "0" && linkStat != "")
	{
		str+="<td width=37% height=76 align=center><img src=<?=$g_link?> width=223 height=35 border=0></td>";
		str+="<td width=35% height=76 align=center><font font=Tahoma size=2 color=#000000><b><strong>"+getConnectString(linkStat)+"</strong></b></font></td>";
		str+="</tr>";
		str+="<tr><td height=20><div align=center></div></td><td height=20><div align=center><font face=Tahoma size=2><strong><?=$m_txpair_normal_cable?></strong></font></div></td><td height=20>&nbsp;</td></tr>";
		str+="<tr><td height=20><div align=center></div></td><td height=20><div align=center><font face=Tahoma size=2><strong><?=$m_rxpair_normal_cable?></strong></font></div></td><td height=20>&nbsp;</td></tr>";
		
	}
	else
	{
		str+="<td width=37% height=76 align=center><img src=<?=$g_nolink?> width=223 height=35 border=0></td>";
		str+="<td width=35% height=76 align=center><font font=Tahoma size=2 color=#000000><b><strong>"+getConnectString(linkStat)+"</strong></b></font></td>";
		str+="</tr>";
		str+="<tr><td height=20><div align=center></div></td><td height=20><div align=center><font face=Tahoma size=2><strong><?=$m_txpair_status?></strong></font></div></td><td height=20>&nbsp;</td></tr>";
		str+="<tr><td height=20><div align=center></div></td><td height=20><div align=center><font face=Tahoma size=2><strong><?=$m_rxpair_status?></strong></font></div></td><td height=20>&nbsp;</td></tr>";
	}
	document.write(str);
}

function ExitWindow()
{
        self.parent.close();
}

</script>
</head>
<body bgcolor=#CCCCCC text=#000000 topmargin=0 leftmargin=0>
<form method=post id="vct_info">
<table border=0 cellspacing=0 cellpadding=0 height=211 width=450>
<tr>
	<td height=11><img src="<?=$g_ww_cvt?>" width=450></td>
</tr>
<tr>
	<td height=10 bgcolor=#CCCCCC>
	<table border=0 width=450 height=175 cellpadding=0>
		<script>generateInfo();</script>
	</table>
	</td>
</tr>
<tr bgcolor=#CCCCCC align=right>
	<td height=10 colspan=2>
	<input name='exit' type=button value='&nbsp;<?=$m_exit?>&nbsp;' class=button width=36 height=52 onClick='ExitWindow()'>&nbsp;
	</td>
</tr>
</table>
</form>
</body>
</html>
