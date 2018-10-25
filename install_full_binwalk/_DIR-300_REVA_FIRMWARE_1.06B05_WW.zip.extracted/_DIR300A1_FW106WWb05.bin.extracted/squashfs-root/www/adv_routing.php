<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="adv_routing";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="adv";
$TEMP_NODES		= "/runtime/post/session_".$sid;
/* --------------------------------------------------------------------------- */
/* local used variables */
$COUNT_RULES_PATH = "/routing/route/entry:";
$COUNT_RULES_VALUE = "destination";
$MAX_RULES = query("/routing/route/max_rules");
if($MAX_RULES==""){$MAX_RULES=20;}
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");

	echo "<!--\n";
	$dirty = 0;
	while($r_index<$MAX_RULES)
	{
		$r_index++;
		$en		= query($TEMP_NODES."/entry:".$r_index."/data_0");
		$inf	= query($TEMP_NODES."/entry:".$r_index."/data_1");
		$dip	= query($TEMP_NODES."/entry:".$r_index."/data_2");
		$netmask= query($TEMP_NODES."/entry:".$r_index."/data_3");
		$gw		= query($TEMP_NODES."/entry:".$r_index."/data_4");

		echo "en = ".$en."\n";
		echo "inf = ".$inf."\n";
		echo "dip = ".$dip."\n";
		echo "netmask = ".$netmask."\n";
		echo "gw = ".$gw."\n";

		anchor("/routing/route/entry:".$r_index);
		if(query("enable")		!=$en)		{ $dirty++; set("enable",		$en); }
		if(query("destination")	!=$dip)		{ $dirty++; set("destination",	$dip); }
		if(query("interface")	!=$inf)		{ $dirty++; set("interface",	$inf); }
		if(query("netmask")		!=$netmask)	{ $dirty++; set("netmask",		$netmask); }
		if(query("gateway")		!=$gw)		{ $dirty++; set("gateway",		$gw); }
	}
	/* Check dirty */
	$SUBMIT_STR="";
	if($dirty > 0)		{$SUBMIT_STR="submit ROUTE";}

	echo "SUBMIT_STR=".$SUBMIT_STR."\n";
	echo "-->\n";
	del($TEMP_NODES);

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
require("/www/model/__count_rules.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$str_wan	= "WAN";
$str_wan_phy= "WAN (".$m_wan_phy.")";
if (query("/runtime/wan/inf:1/connectstatus")=="connected")
{
	$str_wan = "WAN (".query("/runtime/wan/inf:1/ip").")";
}
if (query("/runtime/wan/inf:2/connectstatus")=="connected")
{
	$str_wan_phy = $m_phy_prefix." (".query("/runtime/wan/inf:2/ip").")";
}
/* --------------------------------------------------------------------------- */
?>

<script>
var rules=<?=$MAX_RULES?>+1;
var max_rules=<?=$MAX_RULES?>;
var data = new Array(rules);
var AjaxReq = null;
var rules_cnt = 0;

r_list=[['index','enable','inf','dest_ip','submask','gw']<?
$r_index=0;
while($r_index<$MAX_RULES)
{
	$r_index++;
	anchor("/routing/route/entry:".$r_index);
	echo ",\n\t['".$r_index."','".query("enable")."','".query("interface")."','".query("destination")."','";
	echo query("netmask")."','".query("gateway")."']";
}
?>];
/* page init functoin */
function init()
{
	// init here ...
	var f=get_obj("frm");
	var i;
	for(i=1; i<=<?=$MAX_RULES?>; i++)
	{
		get_obj("s_en_"+i).checked		=(r_list[i][1]=="1")?true:false;
		select_index(get_obj("sel_"+i),	  r_list[i][2]);
		get_obj("s_dip_"+i).value		= r_list[i][3];
		get_obj("s_netmask_"+i).value	= r_list[i][4];
		get_obj("s_gw_"+i).value		= r_list[i][5];
	}
	<?if($router!=1){echo "fields_disabled(f, true);\n";}?>
}

function send_callback()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		delete AjaxReq;
		AjaxReq = null;

		if (rules_cnt < max_rules)	send_rules(20);
		else						get_obj("frm").submit();
	}
	return true;
}

function send_rules(count)
{
	var str = "TEMP_NODES="+escape("<?=$TEMP_NODES?>")+"&data=5";

	str += "&start="+(rules_cnt+1);
	for (var i=0; i<count && rules_cnt < max_rules; i++)
	{
		rules_cnt++;
		str += "&d_"+rules_cnt+"_0="+escape(data[rules_cnt][0]);
		str += "&d_"+rules_cnt+"_1="+escape(data[rules_cnt][1]);
		str += "&d_"+rules_cnt+"_2="+escape(data[rules_cnt][2]);
		str += "&d_"+rules_cnt+"_3="+escape(data[rules_cnt][3]);
		str += "&d_"+rules_cnt+"_4="+escape(data[rules_cnt][4]);
	}
	str += "&end="+rules_cnt;

	AjaxReq = createRequest();
	if (AjaxReq != null)
	{
		AjaxReq.open("POST", "/set_temp_nodes.php", true);
		AjaxReq.onreadystatechange = send_callback;
		AjaxReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		AjaxReq.send(str);
	}
}

/* parameter checking */
function check()
{
	var i;
	var idx;
	//disabled all post items
	for(i=1; i<=<?=$MAX_RULES?>; i++)
	{
		get_obj("en"		+i).disabled=true;
		get_obj("inf"		+i).disabled=true;
		get_obj("dip"		+i).disabled=true;
		get_obj("netmask"	+i).disabled=true;
		get_obj("gw"		+i).disabled=true;
	}
	//check parameter
	idx = 0;
	for(i=1; i<=<?=$MAX_RULES?>; i++)
	{
		var en		=get_obj("s_en_"		+i).checked?1:0;
		var inf		=get_obj("sel_"			+i).value;
		var dip		=get_obj("s_dip_"		+i).value;
		var netmask	=get_obj("s_netmask_"	+i).value;
		var gw		=get_obj("s_gw_"		+i).value;
		var net1, net2;
		if(en || !is_blank(dip) || !is_blank(netmask) || !is_blank(gw))
		{
			if(is_blank(netmask)||!is_valid_mask(netmask))
			{
				alert("<?=$a_invalid_netmask?>");
				get_obj("s_netmask_"+i).select();
				return false;
			}
			if(is_blank(dip))
			{
				alert("<?=$a_invalid_dest_ip?>");
				get_obj("s_dip_"+i).focus();
				return false;
			}
			net1 = get_network_id(dip, netmask);
			if(!is_valid_network(dip, netmask))
			{
				if(!is_valid_ip(dip))
				{
					alert("<?=$a_invalid_dest_ip?>");
					get_obj("s_dip_"+i).focus();
					return false;
				}
				else
				{
					get_obj("s_dip_"+i).value=dip=net1[0];
				}
			}

			if(is_blank(gw)||!is_valid_ip(gw,0))
			{
				alert("<?=$a_invalid_gw?>");
				get_obj("s_gw_"+i).select();
				return false;
			}
			var j;
			for(j=i+1;j<=<?=$MAX_RULES?>;j++)
			{
				net2 = get_network_id(get_obj("s_dip_"+j).value, get_obj("s_netmask_"+j).value);
				if(net1[0] == net2[0])
				{
					alert("<?=$a_same_rule?>");
					get_obj("s_dip_"+j).select();
					return false;
				}
			}
			get_obj("s_dip_"+i).value=dip=net1[0];

			get_obj("en"		+i).disabled=false;
			get_obj("inf"		+i).disabled=false;
			get_obj("dip"		+i).disabled=false;
			get_obj("netmask"	+i).disabled=false;
			get_obj("gw"		+i).disabled=false;
			
			get_obj("en"		+i).value=en;
			get_obj("inf"		+i).value=inf;
			get_obj("dip"		+i).value=dip;
			get_obj("netmask"	+i).value=netmask;
			get_obj("gw"		+i).value=gw;

			/* Allocate space for this rules */
			idx++;
			data[idx] = new Array(5);
			data[idx][0] = en;
			data[idx][1] = inf;
			data[idx][2] = dip;
			data[idx][3] = netmask;
			data[idx][4] = gw;
		}
	}
	idx++;
	for (i=idx; i<rules; i++)
	{
		data[i] = new Array(5);
		data[i][0] = data[i][1] = data[i][2] = data[i][3] = data[i][4] = data[i][5] = "";
	}

	var objs = document.getElementsByName("apply");
	for (var i=0; i<objs.length; i++) objs[i].disabled = true;

	rules_cnt = 0;
	send_rules(20);
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function print_sel(n)
{
	var str="";
	str ="<select id='"+n+"'>";
	str+=	"<option value='WAN'><?=$str_wan?></option>";
	str+=	"<option value='WANPHY'><?=$str_wan_phy?></option>";
/*	str+=	"<option value='LAN'>LAN</option>"; */
	str+="</select>";
	document.write(str);
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">
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
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		<script>apply('check()'); echo("&nbsp;"); cancel('');</script>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$MAX_RULES?> - <?=$m_title_routing?></h2>
			<p><script>remain_rules();</script></p>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr>
				<td class=c_tb>&nbsp;</td>
				<td class=c_tb><?=$m_inf?></td>
				<td class=c_tb><?=$m_dest?></td>
				<td class=c_tb><?=$m_submask?></td>
				<td class=c_tb><?=$m_gateway?></td>
			</tr>
<?
$index=0;
while($index<$MAX_RULES)
{
	$index++;
	echo "<tr>\n";
	echo "\t<td><input type=checkbox id=s_en_".$index."></td>\n";
	echo "\t<td class=c_tb><script>print_sel('sel_".$index."')</script></td>\n";
	echo "\t<td class=c_tb><input type=text id=s_dip_".$index." size=16 maxlength=15></td>\n";
	echo "\t<td class=c_tb><input type=text id=s_netmask_".$index." size=16 maxlength=15></td>\n";
	echo "\t<td class=c_tb><input type=text id=s_gw_".$index." size=16 maxlength=15></td>\n";
	echo "</tr>\n";
	
	echo "<input type=hidden id='en"		.$index."'>\n";
	echo "<input type=hidden id='inf"		.$index."'>\n";
	echo "<input type=hidden id='dip"		.$index."'>\n";
	echo "<input type=hidden id='netmask"	.$index."'>\n";
	echo "<input type=hidden id='gw"		.$index."'>\n";
}
?>
			</table>
		</div>
		<div id="box_bottom">
		<script>apply('check()'); echo("&nbsp;"); cancel('');</script>
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
