<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_vct";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
/* --------------------------------------------------------------------------- */

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
if($AUTH_GROUP!="0")
{
	require("/www/permission_deny.php");
	exit;
}
else
{
	$vct_path="/tmp/vct_status";
	$prev_time = query($vct_path);
	$curr_time = query("/runtime/sys/uptime");

	$delta_time = $curr_time - $prev_time;
	if ($delta_time > 4 && $pingIP=="")
	{
		set($vct_path, $curr_time);
		$vct_do_xgi=1;
		// get the variable value from rgdb.
		set("/runtime/diagnostic/pingResult","");//to remove the last ping result.
	}
	else
	{
		$vct_do_xgi=0;
		// get the variable value from rgdb.
		$pingResult=query("/runtime/diagnostic/pingResult");
	}
}
?>

<script>

cableTestLists=["","<?=$m_wan?>","<?=$m_lan?>1","<?=$m_lan?>2","<?=$m_lan?>3","<?=$m_lan?>4"];
linkType=["<?=$m_disconnected?>","<?=$m_100full?>","<?=$m_100half?>","<?=$m_10full?>","<?=$m_10half?>"];

sData=["","<?query("/runtime/wan/inf:1/linkType");?>",		"<?query("/runtime/switch/port:1/linkType");?>",
		"<?query("/runtime/switch/port:2/linkType");?>",	"<?query("/runtime/switch/port:3/linkType");?>",
		"<?query("/runtime/switch/port:4/linkType");?>"	];

function MoreInfo(name)
{
	window.open(name,"_blank","width=450,height=320");
}

function getConnectString(s)
{
	return linkType[isNaN(parseInt(s, [10]))? 0: parseInt(s, [10])];
}

function generateVS()
{
	var str=new String("");
	for (var i=1;i < 6;i++)
	{
		str+="<tr>";
		str+="<td width=64 height=20 align=center><b>"+cableTestLists[i]+"</b></td>";
		if (sData[i] != "0" && sData[i] != "")
		{
			str+="<td width=220 height=20>&nbsp;<img src=./pic/W_link.gif width=200 height=20 border=0></td>";
			str+="<td width=217 height=20>&nbsp;<strong>"+getConnectString(sData[i])+"</strong></td>";
		}
		else
		{
			str+="<td width=220 height=20>&nbsp;<img src=./pic/W_nolink.gif width=200 height=20 border=0></td>";
			str+="<td width=217 height=20>&nbsp;"+getConnectString(sData[i])+"</td>";
		}
		str+="<td width=17% height=20>"
		str+="<div align=left>"
		str+="<input type=button name=vct_test value=\"<?=$m_more_info?>\" onClick=\"MoreInfo('tools_vct_testing.php?port_id="+(i-1)+"')\">";
		str+="</div></td></tr>";
	}
	document.writeln(str);
}

function pingReturn()
{
	if ("<?=$pingResult?>" == "") { document.write("<br><br>"); }
	else if ("<?=$pingResult?>" == "2")
	{
	    document.write("<td><div align=\"center\"><font color=#000000 face=\"Tahoma\"><?=$pingIP?><br></font><br><font color=#000000 face=\"Tahoma\">Ping Result : <?=$m_ping_passed?><br></font><br></div></td>");
		document.write("<td height=\"20\" valign=\"top\">&nbsp;</td>");
	}
	else
	{
	    document.write("<td><div align=\"center\"><font color=#000000 face=\"Tahoma\"><?=$pingIP?><br></font><br><font color=#000000 face=\"Tahoma\">Ping Result : <?=$m_ping_failed?><br></font><br></div></td>");
		document.write("<td height=\"20\" valign=\"top\">&nbsp;</td>");
	}
}
function doPing()
{
	var f=get_obj("form5");
	if (f.test_ip.value == '')
	{
		alert("<?=$a_empty_ip_addr?>");
		return false;
	}
	else if(strchk_hostname(f.test_ip.value)==false)
	{
		alert("<?=$a_invalid_ip_addr?>");
		field_focus(f.test_ip, "**");
		return false;
	}
	return true;
}
function init()
{
<?
if ($vct_do_xgi == "1")
{
	echo "	get_obj('refreshing').style.display='';\n";
	//echo "  alert(\"".$reload."\");\n";
	$ping_parameter="";
	if($test_ip!=""){$ping_parameter="&set/runtime/diagnostic/pingIp=".$test_ip."&pingIP=".$test_ip;}
	echo "	self.location.href='tools_vct.xgi?set/runtime/switch/getlinktype=1".$ping_parameter."';\n";
}
else
{
	echo "	get_obj('vct').style.display='';\n";
	echo "	get_obj('ping_test').style.display='';\n";
	echo "	get_obj('ping_result').style.display='';\n";
}
?>
}
</script>
<body <?=$G_BODY_ATTR?> onload="init();">
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
		<div class="box" id="refreshing" style="display:none">
			<h2><?if($test_ip!=""){echo $m_title_ping_test;}else{echo $m_title_vct_info;}?></h2><br>
			<div class=bc_tb>
				<font color="blue"><?if($test_ip!=""){echo $m_pinging;}else{echo $m_refreshing;}?></font>
			</div>
			<br>
		</div>
		
		<div class="box" id="vct" style="display:none">
			<h2><?=$m_title_vct_info?></h2>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 bgColor=#dfdfdf border=1>
			<tr id="box_header">
				<td width=64 height=25><div align="center"><strong><?=$m_ports?></strong></div></td>
				<td width=220 height=25><div align="center"><strong><?=$m_link_status?></strong></div></td>
				<td width=217 height=25><div align="center">&nbsp;</div></td>
				<td width=109 height=25><div align="center">&nbsp;</div></td>
			</tr>
			<script>generateVS();</script>
			</table>
		</div>
		
		<div class="box" id="ping_test" style="display:none">
			<h2><?=$m_title_ping_test?></h2>
			<P><?=$m_desc_ping_test?></P>
			<form id="form5" name="form5" method=POST action="<?=$MY_NAME?>.php" onsubmit="return doPing();">
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tr>
				<td class=r_tb><?=$m_field_ping_test?>&nbsp;:&nbsp;</td>
				<td height="20" valign="top">&nbsp;
				<input type="text" id="test_ip" name="test_ip" size=30 maxlength=63 value="">
				<input type="submit" name="ping_button" value="<?=$m_ping?>">
				</td>
			</tr>
			</form> 
			</table>
		</div>
		
		<div class="box" id="ping_result" style="display:none">
			<h2><?=$m_title_ping_result?></h2>
			<table cellSpacing=1 cellPadding=1 width=525 border=0><script>pingReturn();</script></table>
		</div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</body>
</html>
