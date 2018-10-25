<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_session";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__auth_check.php");

if($AUTH_GROUP!="0")
{
	require("/www/permission_deny.php");
	exit;
}

/* --------------------------------------------------------------------------- */

$refresh_display=" style='display:none'";
$a_session_display="";

$a_session_path="/runtime/web/session_".$sid."/naptsession";
if(query($a_session_path)!="1")
{
	set($a_session_path, 1);
	$OTHER_META="<meta http-equiv=Refresh content='0;url=st_session.xgi?set/runtime/stats/naptsession=1'>";
	$refresh_display="";
	$a_session_display=" style='display:none'";
	$dis_refresh_bt=" disabled";
}
else
{
	set($a_session_path, 0);
}
require("/www/model/__html_head.php");
?>

<script language="JavaScript">
var tcpcount="<?query("/runtime/stats/tcpsession");?>";
var udpcount="<?query("/runtime/stats/udpsession");?>";
// natp
// tcp, srcip, sport, dstip, dport, time
list=[<?
$tcp_session=0;
$udp_session=0;
$num=0;
for("/runtime/stats/naptsession"){$num++;}
for("/runtime/stats/naptsession")
{
	$tcp=query("tcp");
	$udp=query("udp");
	$lanhost=query("ipaddr");
	echo "[\"".$lanhost."\",\"".$tcp."\",\"".$udp."\"]";
	if( $# != $num ) { echo ",\n"; }
}
?>];

var TCPSession = tcpcount;//"<?=$tcp_session?>";
var UDPSession = udpcount//"<?=$udp_session?>";

function generateHTML()
{
	var str=new String("");
	//assign nx3 2D array.
/*
	var list= new Array(<?=$num?>);
	for(i=0;i<parseInt("<?=$num?>", [10]);i++)	list[i]=new Array(3);
	
	var meet=false;
	var list_len=0;

	// find out all diffrent ip and list them into list[][];
	// list[j][0]: srcip,	list[j][1]: tcp num,	list[j][2]: udp num
	if(dataList.length!=0)
	{
		list[0][0]=dataList[0][1];	//source ip
		list[0][1]=0;			//init tcp sum
		list[0][2]=0;			//init udp sum
		list_len++;

		// find out all diffrent ip and list them into list[][];
		for(var i=1; i<parseInt("<?=$num?>", [10]);i++)
		{
			meet=false;
			for(j=0;j<list_len;j++)
			{
				if(dataList[i][1]!=list[j][0])	continue;
				else	{meet=true;	break;}
			}
			if(meet==false)
			{
				list[list_len][0]=dataList[i][1];	//source ip
				list[list_len][1]=0;			//init tcp sum
				list[list_len][2]=0;			//init udp sum
				list_len++;
			}
		}
		// count the tcp/udp number in the same source ip
		for(i=0; i<list_len;i++)
		{
			for(j=0;j<dataList.length;j++)
			{
				if(list[i][0]==dataList[j][1])
				{
					if(dataList[j][0]=="1")	list[i][1]++;	//tcp
					else			list[i][2]++;	//udp
				}
			}
		}

	}
*/
	// napt session
	str+="<div class='box'<?=$a_session_display?>>";
	str+="<h2><?=$m_context_title_napt_session?></h2>";
	str+="<table cellpadding=1 cellspacing=1 border=0 width=525>";
	str+="<tr>";
	str+="	<td class=r_tb width=200><?=$m_tcp_session?> :</td>";
	str+="	<td class=l_tb>&nbsp;&nbsp;"+TCPSession+"</td>";
	str+="</tr>";
	str+="<tr>";
	str+="	<td class=r_tb width=200><?=$m_udp_session?> :</td>";
	str+="	<td class=l_tb>&nbsp;&nbsp;"+UDPSession+"</td>";
	str+="</tr>";
	str+="<tr>";
	str+="	<td class=r_tb width=200><?=$m_total?> :</td>";
	
	if(parseInt(TCPSession, [10]) || parseInt(UDPSession, [10]))
		str+="<td class=l_tb>&nbsp;&nbsp;"+(parseInt(TCPSession, [10])+parseInt(UDPSession, [10]))+"</td></tr>";
	else
		str+="<td class=l_tb>&nbsp;&nbsp;"+0+"</td></tr>";
	str+="</table>";
	str+="</div>";

	// natp active session list
	str+="<div class='box'<?=$a_session_display?>>";
	str+="<h2><?=$m_context_title_active_session?></h2>";
	str+="<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>";
	str+="<tr id=box_header>";
	str+="<td class=bc_tb><?=$m_ip_addr?></td>";
	str+="<td class=bc_tb><?=$m_tcp_session?></td>";
	str+="<td class=bc_tb><?=$m_udp_session?></td>";
	//str+="<td class=bc_tb>&nbsp;</td>";
	str+="</tr>";
	
	//for debug
	var debug=false;
	dbg="";
	
	for (var i=0; i<list.length; i++)
	{
		str+="<tr><td class=c_tb>"+list[i][0]+"</td>";
		str+="<td class=c_tb>"+list[i][1]+"</td>";
		str+="<td class=c_tb>"+list[i][2]+"</td>";
/*	
		str+="<td width=25% class=c_tb>";
		str+="<input type=button name=detail value=<?=$m_detail?> onClick=\"window.location.href='st_naptinfo.php?srcip="+list[i][0]+"'\">";
		str+="</td></tr>";
*/
		str+="</tr>";
		if(debug)	dbg+="list["+i+"][srcip,tcpsum,udpsum]=["+list[i][0]+","+list[i][1]+","+list[i][2]+"]\n";
	}
	str+="</table>";
	str+="</div>";
	
	if(debug)	alert(dbg);

	document.writeln(str);
}
function do_refresh()
{
	var f=get_obj("refresh");
	f.disabled=true;
	self.location.reload();
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
		<br><input type=button name=refresh id="refresh" value=<?=$m_refresh?> onClick="do_refresh()"<?=$dis_refresh_bt?>>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->

		<div class="box" id="refreshing"<?=$refresh_display?>>
			<h2><?=$m_context_title_napt_session?></h2><br>
			<div class=bc_tb>
				<font color="blue"><?=$m_refreshing?></font>
			</div>
			<br>
		</div>

		<script language="JavaScript">generateHTML();</script>

<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
