<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="adv_firewall";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="adv";
/* --------------------------------------------------------------------------- */
$COUNT_RULES_PATH	= "/security/firewall/entry:";
$COUNT_RULES_VALUE	= "description";
$MAX_RULES			= query("/security/firewall/max_rules");
if($MAX_RULES=="")	{$MAX_RULES=20;}
$HAS_SCHEDULE		= query("/runtime/func/schedule");
$TEMP_NODES			= "/runtime/post/session_".$sid;
/* --------------------------------------------------------------------------- */
$router = query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");

	echo "<!--\n";
	echo "dmz_enable=".$dmz_enable."\n";
	echo "dmz_ipaddr=".$dmz_ipaddr."\n";
	
	/* SPI (DOS)*/
	$db_dirty_spi=0;
	if ($spi_enable!="1") {$spi_enable=0;}
	if (query("/security/dos/enable")!=$spi_enable) {set("/security/dos/enable", $spi_enable); $db_dirty_spi++;}

	/* RTSP for ALG */
	$db_dirty_rtsp=0;
	if(query("/runtime/func/rtsp") == "1")
	{
		if ($rtsp_enable!="1") {$rtsp_enable=0;}
		if (query("/nat/passthrough/rtsp")!=$rtsp_enable) {set("/nat/passthrough/rtsp", $rtsp_enable); $db_dirty_rtsp++;}
	}

	/* DMZ */
	$db_dirty_dmz=0;
	anchor("/nat/dmzsrv");
	if ($dmz_enable	!="1") {$dmz_enable="0";}
	if (query("enable")	!=$dmz_enable)	{set("enable", $dmz_enable);	$db_dirty_dmz++;}
	if ($dmz_enable == "1")
	{ if (query("ip")!=$dmz_ipaddr)		{set("ip", $dmz_ipaddr);		$db_dirty_dmz++;}}

	/* Firewall */
	$db_dirty_fw=0;
	$index=0;
	while($index < $MAX_RULES)
	{
		$index++;
		$epath=$COUNT_RULES_PATH.$index;

		$fw_enable		= query($TEMP_NODES."/entry:".$index."/data_0");
		$fw_action		= query($TEMP_NODES."/entry:".$index."/data_1");
		$fw_desc		= query($TEMP_NODES."/entry:".$index."/data_2");
		$fw_src_inf		= query($TEMP_NODES."/entry:".$index."/data_3");
		$fw_src_sip		= query($TEMP_NODES."/entry:".$index."/data_4");
		$fw_src_eip		= query($TEMP_NODES."/entry:".$index."/data_5");
		$fw_dst_inf		= query($TEMP_NODES."/entry:".$index."/data_6");
		$fw_dst_sip		= query($TEMP_NODES."/entry:".$index."/data_7");
		$fw_dst_eip		= query($TEMP_NODES."/entry:".$index."/data_8");
		$fw_dst_sport	= query($TEMP_NODES."/entry:".$index."/data_9");
		$fw_dst_eport	= query($TEMP_NODES."/entry:".$index."/data_10");
		$fw_prot		= query($TEMP_NODES."/entry:".$index."/data_11");
		$fw_schedule	= query($TEMP_NODES."/entry:".$index."/data_12");
		$fw_siptype		= query($TEMP_NODES."/entry:".$index."/data_13");
		$fw_diptype		= query($TEMP_NODES."/entry:".$index."/data_14");
		$fw_ptype		= query($TEMP_NODES."/entry:".$index."/data_15");

		if ($fw_enable	!=query($epath."/enable"))			{$db_dirty_fw++; set($epath."/enable",		$fw_enable);}
		if ($fw_action	!=query($epath."/action"))			{$db_dirty_fw++; set($epath."/action",		$fw_action);}
		if ($fw_desc	!=query($epath."/description"))		{$db_dirty_fw++; set($epath."/description",	$fw_desc);}
		if ($fw_src_inf	!=query($epath."/src/inf"))			{$db_dirty_fw++; set($epath."/src/inf",		$fw_src_inf);}
		if ($fw_src_sip	!=query($epath."/src/startip"))		{$db_dirty_fw++; set($epath."/src/startip", $fw_src_sip);}
		if ($fw_src_eip	!=query($epath."/src/endip"))		{$db_dirty_fw++; set($epath."/src/endip",	$fw_src_eip);}
		if ($fw_dst_inf	!=query($epath."/dst/inf"))			{$db_dirty_fw++; set($epath."/dst/inf",		$fw_dst_inf);}
		if ($fw_dst_sip	!=query($epath."/dst/startip"))		{$db_dirty_fw++; set($epath."/dst/startip", $fw_dst_sip);}
		if ($fw_dst_eip	!=query($epath."/dst/endip"))		{$db_dirty_fw++; set($epath."/dst/endip",	$fw_dst_eip);}
		if ($fw_dst_sport!=query($epath."/dst/startport"))	{$db_dirty_fw++; set($epath."/dst/startport",$fw_dst_sport);}
		if ($fw_dst_eport!=query($epath."/dst/endport"))	{$db_dirty_fw++; set($epath."/dst/endport",	$fw_dst_eport);}
		if ($fw_prot	!=query($epath."/protocol"))		{$db_dirty_fw++; set($epath."/protocol",	$fw_prot);}
		if ($fw_schedule!=query($epath."/schedule/id"))		{$db_dirty_fw++; set($epath."/schedule/id", $fw_schedule);}
		if ($fw_siptype	!=query($epath."/src/iptype"))		{$db_dirty_fw++; set($epath."/src/iptype",	$fw_siptype);}
		if ($fw_diptype	!=query($epath."/dst/iptype"))		{$db_dirty_fw++; set($epath."/dst/iptype",	$fw_diptype);}
		if ($fw_ptype	!=query($epath."/dst/porttype"))	{$db_dirty_fw++; set($epath."/dst/porttype",$fw_ptype);}
	}

	$SUBMIT_STR="";
	if($db_dirty_dmz > 0)						{$SUBMIT_STR=$SUBMIT_STR.";submit RG_DMZ";}
	if($db_dirty_fw > 0)						{$SUBMIT_STR=$SUBMIT_STR.";submit RG_FIREWALL";}
	if($db_dirty_spi > 0 || $db_dirty_rtsp > 0)	{$SUBMIT_STR=$SUBMIT_STR.";submit RG_MISC";}

	echo "SUBMIT_STR=".$SUBMIT_STR."\n";
	echo "-->\n";

	del($TEMP_NODES);

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else			{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
require("/www/model/__count_rules.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$cfg_dmz_enable = query("/nat/dmzsrv/enable");
$cfg_dmz_ipaddr = query("/nat/dmzsrv/ip");
$cfg_ipaddr		= query("/lan/ethernet/ip");
$cfg_netmask	= query("/lan/ethernet/netmask");
/* --------------------------------------------------------------------------- */
?>

<script>
var rules=<?=$MAX_RULES?>+1;
var max_rules=<?=$MAX_RULES?>;

var data = new Array(rules);
var AjaxReq = null;
var rules_cnt = 0;

function send_callback()
{
	if (AjaxReq != null & AjaxReq.readyState == 4)
	{
		delete AjaxReq;
		AjaxReq = null;

		if (rules_cnt < max_rules)	send_rules(10);
		else						get_obj("frm").submit();
	}
	return true;
}

function send_rules(count)
{
	var str = "TEMP_NODES="+escape("<?=$TEMP_NODES?>")+"&data=16";

	str += "&start="+(rules_cnt+1);
	for (i=0;  i<count && rules_cnt < max_rules;  i++)
	{
		rules_cnt++;
		str += "&d_"+rules_cnt+"_0=" +escape(data[rules_cnt][0]);
		str += "&d_"+rules_cnt+"_1=" +escape(data[rules_cnt][1]);
		str += "&d_"+rules_cnt+"_2=" +escape(data[rules_cnt][2]);
		str += "&d_"+rules_cnt+"_3=" +escape(data[rules_cnt][3]);
		str += "&d_"+rules_cnt+"_4=" +escape(data[rules_cnt][4]);
		str += "&d_"+rules_cnt+"_5=" +escape(data[rules_cnt][5]);
		str += "&d_"+rules_cnt+"_6=" +escape(data[rules_cnt][6]);
		str += "&d_"+rules_cnt+"_7=" +escape(data[rules_cnt][7]);
		str += "&d_"+rules_cnt+"_8=" +escape(data[rules_cnt][8]);
		str += "&d_"+rules_cnt+"_9=" +escape(data[rules_cnt][9]);
		str += "&d_"+rules_cnt+"_10="+escape(data[rules_cnt][10]);
		str += "&d_"+rules_cnt+"_11="+escape(data[rules_cnt][11]);
		str += "&d_"+rules_cnt+"_12="+escape(data[rules_cnt][12]);
		str += "&d_"+rules_cnt+"_13="+escape(data[rules_cnt][13]);
		str += "&d_"+rules_cnt+"_14="+escape(data[rules_cnt][14]);
		str += "&d_"+rules_cnt+"_15="+escape(data[rules_cnt][15]);
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

function on_click_dmz_enable()
{
	var f = get_obj("frm");

	f.dmz_ipaddr.disabled = f.copy_ip.disabled = f.dhcp.disabled =
	f.dmz_enable.checked ? false : true;
}

function copy_ipaddr()
{
    var IP = get_obj("dmz_ipaddr");
    var dhcp = get_obj("dhcp");
	
	if (dhcp.value == 0)
	{
		alert("<?=$a_no_ip_selected?>");
		return false;
	}
	IP.value = dhcp.value;
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	/* SPI (DOS)*/
	f.spi_enable.checked = <?if(query("/security/dos/enable")=="1"){echo "true";}else{echo "false";}?>;

	/* RTSP for ALG */
<?
	if(query("/runtime/func/rtsp") == "1")
	{
		if(query("/nat/passthrough/rtsp")=="1")
		{
			echo "f.rtsp_enable.checked = true";
		}
		else
		{
			echo "f.rtsp_enable.checked = false";
		}
	}
	
?>
	/* DMZ */
	f.dmz_enable.checked = <? if ($cfg_dmz_enable == "1") {echo "true";} else {echo "false";} ?>;
	f.dmz_ipaddr.value = "<?=$cfg_dmz_ipaddr?>";
	on_click_dmz_enable();

	<?if($router!=1){echo "fields_disabled(f, true);\n";}?>
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	var len=0;
	var tmp_fw_description;
	var tmp_src_inf;
	var tmp_src_startip;
	var tmp_src_endip;
	var tmp_dst_inf;
	var tmp_dst_startip;
	var tmp_dst_endip;
	var tmp_dst_startport;
	var tmp_dst_endport;
	var tmp_fw_pro;

	/* DMZ */
	if (f.dmz_enable.checked)
	{
		if (is_valid_ip(f.dmz_ipaddr.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.dmz_ipaddr, "**");
			return false;
		}
		if (is_valid_ip2(f.dmz_ipaddr.value, "<?=$cfg_netmask?>")==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.dmz_ipaddr, "**");
			return false;
		}
		net1 = get_network_id(f.dmz_ipaddr.value, "<?=$cfg_netmask?>");
		net2 = get_network_id("<?=$cfg_ipaddr?>", "<?=$cfg_netmask?>");
		if (net1[0] != net2[0])
		{
			alert("<?=$a_dmzip_in_different_subnet?>");
			field_focus(f.dmz_ipaddr, "**");
			return false;
		}
	}
	/* Firewall */
	for(i=1; i < rules; i++)
	{
		tmp_fw_description	= eval("f.fw_description_"+	i+".value");
		tmp_src_inf			= eval("f.src_inf_"+		i+".value");
		tmp_src_startip		= eval("f.src_startip_"+	i+".value");
		tmp_src_endip		= eval("f.src_endip_"+		i+".value");
		tmp_dst_inf			= eval("f.dst_inf_"+		i+".value");
		tmp_dst_startip		= eval("f.dst_startip_"+	i+".value");
		tmp_dst_endip		= eval("f.dst_endip_"+		i+".value");
		tmp_dst_startport	= eval("f.dst_startport_"+	i+".value");
		tmp_dst_endport		= eval("f.dst_endport_"+	i+".value");
		tmp_fw_pro			= eval("f.fw_pro_"+			i+".value");

		if (!is_blank(tmp_fw_description))
		{
			if (tmp_dst_inf==tmp_src_inf && tmp_dst_inf!="0")
			{
				alert("<?=$a_not_support_same_direction?>");
				return false;
			}

			var src_iptype=3;
			var dst_iptype=3;
			var dst_porttype=3;

			/* src_startip */
			if (tmp_src_startip == tmp_src_endip) tmp_src_endip="";
			if (tmp_src_endip == "") src_iptype=2;
			if (tmp_src_startip=="*")
			{
				src_iptype=1;
				tmp_src_endip="";
			}
			else if (is_valid_ip(tmp_src_startip, false)==false)
			{
				alert("<?=$a_invalid_src_startip?>");
				field_focus(get_obj("src_startip_"+i), "**");
				return false;
			}
			if (tmp_src_inf == "1")
			{
				if (is_valid_ip2(tmp_src_startip, "<?=$cfg_netmask?>")==false)
				{
					alert("<?=$a_invalid_src_startip?>");
					field_focus(get_obj("src_startip_"+i), "**");
					return false;
				}
				net1 = get_network_id(tmp_src_startip, "<?=$cfg_netmask?>");
				net2 = get_network_id("<?=$cfg_ipaddr?>", "<?=$cfg_netmask?>");
				if (net1[0] != net2[0])
				{
					alert("<?=$a_src_startip_in_different_subnet?>");
					field_focus(get_obj("src_startip_"+i), "**");
					return false;
				}
			}

			/* src_endip */
			if (tmp_src_endip!="" && is_valid_ip(tmp_src_endip, false)==false)
			{
				alert("<?=$a_invalid_src_endip?>");
				field_focus(get_obj("src_endip_"+i), "**");
				return false;
			}
			if (tmp_src_endip!="" && tmp_src_inf=="1")
			{
				if (is_valid_ip2(tmp_src_endip, "<?=$cfg_netmask?>")==false)
				{
					alert("<?=$a_invalid_src_endip?>");
					field_focus(get_obj("src_endip_"+i), "**");
					return false;
				}
				net1 = get_network_id(tmp_src_endip, "<?=$cfg_netmask?>");
				net2 = get_network_id("<?=$cfg_ipaddr?>", "<?=$cfg_netmask?>");
				if (net1[0] != net2[0])
				{
					alert("<?=$a_src_endip_in_different_subnet?>");
					field_focus(get_obj("src_endip_"+i), "**");
					return false;
				}
			}

			/* dst_startip */
			if (tmp_dst_startip==tmp_dst_endip) tmp_dst_endip="";
			if (tmp_dst_endip=="") dst_iptype=2;
			if (tmp_dst_startip=="*")
			{
				dst_iptype=1;
				tmp_dst_endip="";
			}
			else if (is_valid_ip(tmp_dst_startip, false)==false)
			{
				alert("<?=$a_invalid_dst_startip?>");
				field_focus(get_obj("dst_startip_"+i), "**");
				return false;
			}
			if (tmp_dst_inf=="1")
			{
				if (is_valid_ip2(tmp_dst_startip, "<?=$cfg_netmask?>")==false)
				{
					alert("<?=$a_invalid_dst_startip?>");
					field_focus(get_obj("dst_startip_"+i), "**");
					return false;
				}
				net1 = get_network_id(tmp_dst_startip, "<?=$cfg_netmask?>");
				net2 = get_network_id("<?=$cfg_ipaddr?>", "<?=$cfg_netmask?>");
				if (net1[0] != net2[0])
				{
					alert("<?=$a_dst_startip_in_different_subnet?>");
					field_focus(get_obj("dst_startip_"+i), "**");
					return false;
				}
			}

			/* dst_endip */
			if (tmp_dst_endip!="" && is_valid_ip(tmp_dst_endip, false)==false)
			{
				alert("<?=$a_invalid_dst_endip?>");
				field_focus(get_obj("dst_endip_"+i), "**");
				return false;
			}
			if (tmp_dst_endip!="" && tmp_dst_inf=="1")
			{
				if (is_valid_ip2(tmp_dst_endip, "<?=$cfg_netmask?>")==false)
				{
					alert("<?=$a_invalid_dst_endip?>");
					field_focus(get_obj("dst_endip_"+i), "**");
					return false;
				}
				net1 = get_network_id(tmp_dst_endip, "<?=$cfg_netmask?>");
				net2 = get_network_id("<?=$cfg_ipaddr?>", "<?=$cfg_netmask?>");
				if (net1[0] != net2[0])
				{
					alert("<?=$a_dst_endip_in_different_subnet?>");
					field_focus(get_obj("dst_endip_"+i), "**");
					return false;
				}
			}
			
			/* check if src startip is lesser than end ip */
			if (tmp_src_endip!="")
			{
				var src_sip=get_ip(tmp_src_startip);
				var src_eip=get_ip(tmp_src_endip);
				var j;
				var is_valid_src_ip_range=false;
				for (j=1; j<5; j++)
				{
					if (decstr2int(src_sip[j]) < decstr2int(src_eip[j]))
					{
						is_valid_src_ip_range=true;
						j=5;
					}
				}
				if (is_valid_src_ip_range==false)
				{
					alert("<?=$a_invalid_src_ip_range?>");
					field_focus(get_obj("src_startip_"+i), "**");
					return false;
				}
			}
			/* check if dst startip is lesser than end ip */
			if (tmp_dst_endip!="")
			{
				var dst_sip=get_ip(tmp_dst_startip);
				var dst_eip=get_ip(tmp_dst_endip);
				var j;
				var is_valid_dst_ip_range=false;
				for (j=1; j<5; j++)
				{
					if (decstr2int(dst_sip[j]) < decstr2int(dst_eip[j]))
					{
						is_valid_dst_ip_range=true;
						break;
					}
				}
				if (is_valid_dst_ip_range==false)
				{
					alert("<?=$a_invalid_dst_ip_range?>");
					field_focus(get_obj("dst_startip_"+i), "**");
					return false;
				}
			}

			if (tmp_fw_pro=="2" || tmp_fw_pro=="3")
			{
				if (tmp_dst_startport != "")
				{
					if(tmp_dst_startport.charAt(0)=='0')
					{
						alert("<?=$a_invalid_port?>");
						field_focus(get_obj("dst_startport_"+i), "**");
						return false;
					}
				}
				if (tmp_dst_endport != "")
				{
					if(tmp_dst_endport.charAt(0)=='0')
					{
						alert("<?=$a_invalid_port?>");
						field_focus(get_obj("dst_endport_"+i), "**");
						return false;
					}
				}
				/* start port */
				if (tmp_dst_startport==tmp_dst_endport) tmp_dst_endport="";
				if (tmp_dst_endport=="") dst_porttype=2;
				if (tmp_dst_startport=="*")
				{
					dst_porttype=1;
					tmp_dst_endport="";
				}
				if (tmp_dst_endport !="")
				{
					if (is_valid_port_range_str(tmp_dst_startport, tmp_dst_endport)==false)
					{
						alert("<?=$a_invalid_port_range?>");
						field_focus(get_obj("dst_startport_"+i), "**");
						return false;
					}
				}
				else if (is_valid_port_str(tmp_dst_startport)==false)
				{
					alert("<?=$a_invalid_port?>");
					field_focus(get_obj("dst_startport_"+i), "**");
					return false;
				}
			}	
			
			//check same rule exist or not			
			for(j=1; j < i; j++)
			{
				if(eval("f.src_inf_"+j+".value") == tmp_src_inf && eval("f.src_startip_"+j+".value") == tmp_src_startip
				&& eval("f.src_endip_"+j+".value") == eval("f.src_endip_"+i+".value") 
				&& eval("f.fw_pro_"+j+".value") == tmp_fw_pro
				&& eval("f.schedule_"+j+".value") == eval("f.schedule_"+i+".value")
				&& eval("f.fw_action_"+j+".value") == eval("f.fw_action_"+i+".value")
				&& eval("f.dst_inf_"+j+".value") == tmp_dst_inf && eval("f.dst_startip_"+j+".value") == tmp_dst_startip 
				&& eval("f.dst_endip_"+j+".value") == eval("f.dst_endip_"+i+".value") 
				&& eval("f.dst_startport_"+j+".value") == tmp_dst_startport
				&& eval("f.dst_endport_"+j+".value") == eval("f.dst_endport_"+i+".value"))
				{
					alert("<?=$a_same_rule_exist?>");
					field_focus(get_obj("fw_description_"+i), "**");
					return false;
				}
			}

			/* Allocate space for this rule */
			len++;
			data[len] = new Array(16);
			data[len][0] = get_obj("fw_enable_"+i).checked ? "1" : "";
			data[len][1] = eval("f.fw_action_"+i+".value");
			data[len][2] = eval("f.fw_description_"+i+".value");
			data[len][3] = eval("f.src_inf_"+i+".value");
			data[len][4] = eval("f.src_startip_"+i+".value");
			data[len][5] = eval("f.src_endip_"+i+".value");
			data[len][6] = eval("f.dst_inf_"+i+".value");
			data[len][7] = eval("f.dst_startip_"+i+".value");
			data[len][8] = eval("f.dst_endip_"+i+".value");
			data[len][9] = eval("f.dst_startport_"+i+".value");
			data[len][10] = eval("f.dst_endport_"+i+".value");
			data[len][11] = eval("f.fw_pro_"+i+".value");
			data[len][12] = <?

			if ($HAS_SCHEDULE=="1")	{ echo "eval(\"f.schedule_\"+i+\".value\")"; }
			else					{ echo "\"\""; }

			?>;
			data[len][13] = src_iptype;
			data[len][14] = dst_iptype;
			data[len][15] = dst_porttype;
		}
	}
	len++;
	for(j=len; j < rules; j++)
	{
		data[j] = new Array(16);
		data[j][0] = data[j][1] = data[j][2] = data[j][3] = data[j][4] = data[j][5] = "";
		data[j][6] = data[j][7] = data[j][8] = data[j][9] = data[j][10] = data[j][11] = "";
		data[j][12] = data[j][13] = data[j][14] = data[j][15] = "";
	}

	var objs = document.getElementsByName("apply");
	for (var i=0; i<objs.length; i++) objs[i].disabled = true;

	rules_cnt = 0;
	send_rules(10);
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function chg_fw_pro(id)
{
	var f=get_obj("frm");
	var dis=true;

	fw_pro=eval("f.fw_pro_"+id+".value");
	if(fw_pro=="2" || fw_pro=="3")	dis = false;

	eval("f.dst_startport_"+id+".disabled="+dis);
	eval("f.dst_endport_"+id+".disabled="+dis);
}
function print_edit_del(id)
{
	var str="";
	str="<a href='<?=$MY_NAME?>.php?edit_id="+id+"'><img alt='edit' border=0 src='/pic/edit.jpg'></a>";
	str+="&nbsp;";
	str+="<a href=\"javascript:do_delete('"+id+"')\"><img alt='delete' border=0 src='/pic/delete.jpg'></a>";
	document.write(str);
}
function do_delete(id)
{
	if(confirm("<?=$a_confirm_to_del_fw?>")==true)
	self.location.href="<?=$MY_NAME?>.php?del_id="+id;
}
function chg_pri(s, d)
{
	var f=get_obj("swap");
	if(confirm("<?=$a_confirm_swap_fw?>"))
	{
		f.swap_from.value=s;
		f.swap_to.value=d;
		f.ACTION_POST.value="FIREWALL_SWAP";
		f.submit();
	}
}
function print_pri(id)
{
	var str="";
	var fw_rules="<?=$firewall_rule?>";
	var pre=decstr2int(id)-1;
	var next=decstr2int(id)+1;
	if(id=="1")	str="<img src='/pic/up_g.gif'>";
	else		str="<a href=\"javascript:chg_pri('"+id+"','"+pre+"')\"><img src='/pic/up.gif' border=0 alt='up'></a>";
	if(id==fw_rules)	str+="&nbsp;<img src='/pic/down_g.gif'>";
	else				str+="&nbsp;<a href=\"javascript:chg_pri('"+id+"','"+next+"')\"><img src='/pic/down.gif' border=0 alt='down'></a>";
	document.write(str);
}
</script>

<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST"	value="SOMETHING">

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
<?
$td_width0="width=\"180\" align=\"right\"";
$td_width1="width=\"160\" align=\"right\"";
$td_width2="width=\"150\"";
$td_width3="width=\"60\"";
?>
		<div class="box">
			<h2><?=$m_title_firewall?></h2>
			<table border=0>
			<tr>
				<td <?=$td_width0?>><?=$m_enable_spi?>&nbsp;:</td>
				<td <?=$td_width2?>>&nbsp;<input type="checkbox" name="spi_enable" id=spi_enable value="1"></td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?=$m_title_dmz_rule?></h2>
			<?=$m_desc_dmz?><br><br>
			<?=$m_note_dmz?>
			<table border=0>
			<tr>
				<td <?=$td_width0?>><?=$m_enable_dmz_host?>&nbsp;:</td>
				<td <?=$td_width2?>>&nbsp;<input type="checkbox" name="dmz_enable" value="1" onclick="on_click_dmz_enable()"></td>
				<td></td>
			</tr>
			
			<tr>
				<td align="right"><?=$m_ip_addr_dmz?>&nbsp;:</td>
				<td>&nbsp;<input type="text" id="dmz_ipaddr" name="dmz_ipaddr" size=15 maxlength=15></td>
				<td>
					<input id='copy_ip' name='copy_ip' type=button value='<<' class=button onClick='copy_ipaddr()'>
					<select id='dhcp' name='dhcp'>
						<option value=0><?=$m_computer_name?></option>
<?

for("/runtime/dhcpserver/lease")
{ echo "\t\t\t\t\t\t<option value=\"".query("ip")."\">".get(h,"hostname")."</option>\n"; }

?>					</select>
				</td>
			</tr>
			</table>
		</div>
<? 
if(query("/runtime/func/rtsp") == "1")
{
	echo "		<div class=\"box\">";
	echo "			<h2>".$m_title_rtsp."</h2>";
	echo "			<table border=0>";
	echo "			<tr>";
	echo "				<td ".$td_width0.">".$m_enable_rtsp."&nbsp;:</td>";
	echo "				<td ".$td_width2.">&nbsp;<input type=\"checkbox\" name=\"rtsp_enable\" id=rtsp_enable value=\"1\"></td>";
	echo "			</tr>";
	echo "			</table>";
	echo "		</div>";
}
?>
		<div class="box">
			<h2><?=$MAX_RULES?> - <?=$m_title_firewall_rules?></h2>
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
				<td>&nbsp;</td>
				<td class=c_tb><?=$m_inf?></div></td>
				<td class=c_tb><?=$m_ipaddr?></td>
				<td>&nbsp;</td>
				<? if($HAS_SCHEDULE=="1") {echo "<td class=c_tb>".$m_schedule."</div></td>\n";} ?>
			</tr>
<?

/* Virtaul Server rule */
for("/nat/vrtsrv/entry")
{
	if(query("enable")=="1") { require("/www/__adv_firewall_vrtsrv.php"); }
}

$index=0;
while($index < $MAX_RULES)
{		
	$index++;
	anchor("/security/firewall/entry:".$index);
	require("/www/__adv_firewall.php");	
}

/* pingallow */
if(query("/security/firewall/pingallow")=="1")	{ require("/www/__adv_firewall_pingallow.php"); }
/* remote access */
if(query("/security/firewall/httpallow")=="1")	{ require("/www/__adv_firewall_httpallow.php"); }

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
