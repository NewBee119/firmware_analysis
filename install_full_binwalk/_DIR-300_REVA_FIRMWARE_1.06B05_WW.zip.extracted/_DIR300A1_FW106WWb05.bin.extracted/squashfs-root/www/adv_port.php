<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		= "adv_port";
$MY_MSG_FILE	= $MY_NAME.".php";
$CATEGORY		= "adv";
/* --------------------------------------------------------------------------- */
/* local used variables */
$COUNT_RULES_PATH = "/nat/vrtsrv/entry:";
$COUNT_RULES_VALUE = "description";
$MAX_RULES = query("/nat/vrtsrv/max_rules");
if($MAX_RULES==""){$MAX_RULES=10;}
$HAS_SCHEDULE = query("/runtime/func/schedule/vrtsrv");
$TEMP_NODES = "/runtime/post/session_".$sid;
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");

	$dirty=0;
	$index=0;
	while($index < $MAX_RULES)
	{
		$index++;
		$cfg_en     = query($TEMP_NODES."/entry:".$index."/data_0");
		$cfg_name	= query($TEMP_NODES."/entry:".$index."/data_1");
		$cfg_ip		= query($TEMP_NODES."/entry:".$index."/data_2");
		$cfg_sport	= query($TEMP_NODES."/entry:".$index."/data_3");
		$cfg_eport	= query($TEMP_NODES."/entry:".$index."/data_4");
		$cfg_psport	= query($TEMP_NODES."/entry:".$index."/data_5");
		$cfg_peport	= query($TEMP_NODES."/entry:".$index."/data_6");
		$cfg_prot	= query($TEMP_NODES."/entry:".$index."/data_7");
		$cfg_sch	= query($TEMP_NODES."/entry:".$index."/data_8");

		$entry=$COUNT_RULES_PATH.$index."/";
		$int=$entry."internal/";
		$ext=$entry."external/";
		if ($cfg_sport==$cfg_eport)		{$cfg_eport="";}
		if ($cfg_psport==$cfg_peport)	{$cfg_peport="";}

		if (query($entry."enable")		!= $cfg_en)		{$dirty++; set($entry."enable",		$cfg_en);}
		if (query($entry."description")	!= $cfg_name)	{$dirty++; set($entry."description",$cfg_name);}
		if (query($entry."protocol")	!= $cfg_prot)	{$dirty++; set($entry."protocol",	$cfg_prot);}
		if (query($entry."schedule/id") != $cfg_sch)	{$dirty++; set($entry."schedule/id",$cfg_sch);}

		if (query($int."ip")			!= $cfg_ip)		{$dirty++; set($int."ip",			$cfg_ip);}
		if (query($int."startport")		!= $cfg_psport)	{$dirty++; set($int."startport",	$cfg_psport);}
		if (query($ext."startport")		!= $cfg_sport)	{$dirty++; set($ext."startport",	$cfg_sport);}
		if (query($int."endport")		!= $cfg_peport)	{$dirty++; set($int."endport",		$cfg_peport);}
		if (query($ext."endport")		!= $cfg_eport)	{$dirty++; set($ext."endport",		$cfg_eport);}
	}
		
	if($dirty > 0)	{$SUBMIT_STR=";submit RG_VSVR";}
	else			{$SUBMIT_STR="";}

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
$cfg_ipaddr		= query("/lan/ethernet/ip");
$cfg_netmask	= query("/lan/ethernet/netmask");
/* --------------------------------------------------------------------------- */
?>
<script>
var port_list = new Array("21","80","443","53","25","110","23","500","1723","1720","801","800","1720");
var prot_list = new Array("0","0","0","1","0","0","0","1","0","0","0","0","0");
var rules=<?=$MAX_RULES?>+1;
function copy_application(index)
{
	var name=get_obj("name_"+index);
	var app=get_obj("app_"+index);
	var sport=get_obj("start_port_"+index);
	var eport=get_obj("end_port_"+index)
	var spp=get_obj("priv_sport_"+index);
	var epp=get_obj("priv_eport_"+index);
	var prot=get_obj("protocol_"+index);
	
    if (app.selectedIndex > 0)
    {
		name.value = app.value;
		spp.value=sport.value=port_list[app.selectedIndex-1];
		epp.value=eport.value=port_list[app.selectedIndex-1];
		prot.selectedIndex=prot_list[app.selectedIndex-1];
	}	
	else
	{ alert('<?=$a_no_app_name?>');	}
}	

function copy_ip(index)
{
	var ip=get_obj("ip_"+index);
	var hostname=get_obj("ip_list_"+index);
	if (hostname.selectedIndex > 0)	
		{ ip.value=hostname.value; }
	else
		{ alert('<?=$a_no_host_name?>'); }
}
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	<? if ($router!=1) { echo "\tfields_disabled(f, true);\n"; } ?>
}


var data = new Array(rules);
var rules_cnt = 0;
var AjaxReq = null;

function send_callback()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		delete AjaxReq;
		AjaxReq = null;

		if (rules_cnt < <?=$MAX_RULES?>)	send_rules(10);
		else								get_obj("frm").submit();
	}
	return true;
}

function send_rules(count)
{
	var str = "TEMP_NODES="+escape("<?=$TEMP_NODES?>")+"&data=9";

	str += "&start="+(rules_cnt+1);
	for (i=0; i<count && rules_cnt < <?=$MAX_RULES?>; i++)
	{
		rules_cnt++;
		str += "&d_"+rules_cnt+"_0="+escape(data[rules_cnt][0]);
		str += "&d_"+rules_cnt+"_1="+escape(data[rules_cnt][1]);
		str += "&d_"+rules_cnt+"_2="+escape(data[rules_cnt][2]);
		str += "&d_"+rules_cnt+"_3="+escape(data[rules_cnt][3]);
		str += "&d_"+rules_cnt+"_4="+escape(data[rules_cnt][4]);
		str += "&d_"+rules_cnt+"_5="+escape(data[rules_cnt][5]);
		str += "&d_"+rules_cnt+"_6="+escape(data[rules_cnt][6]);
		str += "&d_"+rules_cnt+"_7="+escape(data[rules_cnt][7]);
		str += "&d_"+rules_cnt+"_8="+escape(data[rules_cnt][8]);
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
	var f=get_obj("frm");
	var len=0;
	var tmp_ip;
	var tmp_sport;
	var tmp_eport;
	var start, end;


	for(i=1; i < rules ; i++)
	{
		if (!is_blank(get_obj("name_"+i).value))
		{
			tmp_ip		= eval("f.ip_"+i+".value");
			tmp_sport	= eval("f.start_port_"+i+".value");
			tmp_eport	= eval("f.end_port_"+i+".value");
			tmp_privport= eval("f.priv_sport_"+i+".value");

			if (is_blank(tmp_ip) || is_valid_ip(tmp_ip,0)==false || is_valid_ip2(tmp_ip,"<?=$cfg_netmask?>")==false)
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(get_obj("ip_"+i), "**");
				return false;
			}
			if(tmp_ip=="<?=$cfg_ipaddr?>")
            {
                alert("<?=$a_same_with_lan_ip?>");
                field_focus(get_obj("ip_"+i), "**");
                return false;
            }
			if(tmp_ip!="")
			{
				net1 = get_network_id(tmp_ip, "<?=$cfg_netmask?>");
				net2 = get_network_id("<?=$cfg_ipaddr?>", "<?=$cfg_netmask?>");
				if (net1[0] != net2[0])
				{
					alert("<?=$a_ip_in_different_subnet?>");
					field_focus(get_obj("ip_"+i), "**");
					return false;
				}
			}

			if (is_blank(tmp_sport))
			{
				alert('<?=$a_cant_blank?>');
				field_focus(get_obj("start_port_"+i), "**");
				return false;
			}
			if (is_blank(tmp_eport))
			{
				tmp_eport = tmp_sport;
				get_obj("end_port_"+i).value = tmp_eport;
			}
			if (!is_valid_port_str(tmp_sport))
			{
				alert('<?=$a_invalid_port?>');
				field_focus(get_obj("start_port_"+i), "**");
				return false;
			}
			if (!is_valid_port_str(tmp_eport))
			{
				alert('<?=$a_invalid_port?>');
				field_focus(get_obj("end_port_"+i), "**");
				return false;
			}
			if (is_blank(tmp_privport))
			{
				tmp_privport = tmp_sport;
				get_obj("priv_sport_"+i).value = tmp_privport;
			}
			else if (!is_valid_port_str(tmp_privport))
			{
				alert('<?=$a_invalid_port?>');
				field_focus(get_obj("priv_sport_"+i), "**");
				return false;
			}

			start = parseInt(tmp_sport, [10]);
			end = parseInt(tmp_eport, [10]);
			if (start > end)
			{
				alert('<?=$a_end_big_start?>');
				field_focus(get_obj("end_port_"+i), "**");
				return false;
			}

			priv = parseInt(tmp_privport, [10]);

			if ((end-start+priv)>65535)
			{
				alert('<?=$a_invalid_port?>');
				field_focus(get_obj("priv_sport_"+i), "**");
				return false;
			}
			get_obj("priv_eport_"+i).value = (end - start + priv);

			//check same rule exist or not
			for(j=1; j < i; j++)
			{
				if(eval("f.ip_"+j+".value") == tmp_ip && eval("f.start_port_"+j+".value") == tmp_sport
				   && eval("f.end_port_"+j+".value") == tmp_eport && eval("f.priv_sport_"+j+".value") == tmp_privport
				   && eval("f.priv_eport_"+j+".value") == eval("f.priv_eport_"+i+".value")
				   && eval("f.protocol_"+j+".value") == eval("f.protocol_"+i+".value")
					)
				{
					alert("<?=$a_same_rule_exist?>");
					field_focus(get_obj("name_"+i), "**");
					return false;
				}
			}
			///////////////
			/* Allocate space for this rule */
			len++;
			
			data[len] = new Array(9);
			data[len][0] = get_obj("enable_"+i).checked ? "1" : "";
			data[len][1] = eval("f.name_"+i+".value");
			data[len][2] = eval("f.ip_"+i+".value");
			data[len][3] = eval("f.start_port_"+i+".value");
			data[len][4] = eval("f.end_port_"+i+".value");
			data[len][5] = eval("f.priv_sport_"+i+".value");
			data[len][6] = eval("f.priv_eport_"+i+".value");
			data[len][7] = eval("f.protocol_"+i+".value");
			data[len][8] = <?
			if ($HAS_SCHEDULE=="1")	{ echo "eval(\"f.schedule_\"+i+\".value\")"; }
			else					{ echo "\"\""; }
			?>;
		}
	}

	while (len < rules)
	{
		len++;
		data[len] = new Array(9);
		data[len][0] = data[len][1] = data[len][2] = data[len][3] = data[len][4] =
		data[len][5] = data[len][6] = data[len][7] = data[len][8] = "";
	}

	var objs = document.getElementsByName("apply");
	for (var i=0; i<objs.length; i++) objs[i].disabled = true;

	rules_cnt = 0;
	send_rules(10);
	return true;
}
function fill_priv_port( who, inx )
{
	var pub_sport = get_obj("start_port_"+inx);
	var pub_eport = get_obj("end_port_"+inx);
	var priv_sport = get_obj("priv_sport_"+inx);
	var priv_eport = get_obj("priv_eport_"+inx);
	if(who == "pub_start" && pub_sport.value != "")
	{
		if(pub_eport.value == "")
			pub_eport.value = pub_sport.value;
		if(priv_sport.value == "")
			priv_sport.value = pub_sport.value;
		priv_eport.value = parseInt(priv_sport.value) + (parseInt(pub_eport.value) - parseInt(pub_sport.value));
	}
	if(who == "pub_end" && pub_eport.value != "")
	{
		if(pub_sport.value == "")
			pub_sport.value = pub_eport.value;
		if(priv_eport.value == "")
			priv_eport.value = pub_eport.value;
		priv_eport.value = parseInt(priv_sport.value) + (parseInt(pub_eport.value) - parseInt(pub_sport.value));
	}
	if(who == "priv_start" && priv_sport.value != "")
	{
		if(pub_sport.value == "")
			pub_sport.value = priv_sport.value;
		if(pub_eport.value == "")
			pub_eport.value = priv_sport.value;
		priv_eport.value = parseInt(priv_sport.value) + (parseInt(pub_eport.value) - parseInt(pub_sport.value));
	}
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
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
			<h2><?=$MAX_RULES?> - <?=$m_title_app_rules?></h2>
			<table cellSpacing=1 cellPadding=2 width=525 border=0>
			<tr>
				<td><script>remain_rules();</script></td>
			</tr>
			</table>
			<br>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tbody>
			<tr>
				<td align=middle width=20>&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="180" align=left><div align="left">&nbsp;</div></td>
				<td width="120" align=middle><div align="center"><?=$m_port?></div></td>
				<td width="70" align=middle><div align="center"><?=$m_traffic_type?></div></td>
				<?if($HAS_SCHEDULE=="1")
				{echo "<td width=\"69\" align=middle><div align=\"center\">".$m_schedule."</div></td>\n";}?>
			</tr>
			<?
			$index=0;
			while($index < $MAX_RULES)
			{		
				$index++;
				anchor("/nat/vrtsrv/entry:".$index);
				require("/www/__adv_port.php");	
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
