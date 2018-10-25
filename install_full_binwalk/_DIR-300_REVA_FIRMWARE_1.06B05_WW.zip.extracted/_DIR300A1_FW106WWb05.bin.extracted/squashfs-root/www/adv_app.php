<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		= "adv_app";
$MY_MSG_FILE	= $MY_NAME.".php";
$CATEGORY		= "adv";
$TEMP_NODES		= "/runtime/post/session_".$sid;
/* --------------------------------------------------------------------------- */
/* local used variables */
$COUNT_RULES_PATH = "/nat/porttrigger/entry:";
$COUNT_RULES_VALUE = "description";
$MAX_RULES = query("/nat/porttrigger/max_rules");
if($MAX_RULES==""){$MAX_RULES=10;}
$HAS_SCHEDULE = query("/runtime/func/schedule/portt");
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");
	echo "<!--\n";
	echo "ACTION_POST=".$ACTION_POST."\n";

	$dirty=0; $idx=0;
	while ($idx < $MAX_RULES)
	{
		$idx++;
		$cfg_en		= query($TEMP_NODES."/entry:".$idx."/data_0");
		$cfg_desc	= query($TEMP_NODES."/entry:".$idx."/data_1");
		$cfg_tstart	= query($TEMP_NODES."/entry:".$idx."/data_2");
		$cfg_tend	= query($TEMP_NODES."/entry:".$idx."/data_3");
		$cfg_tprot	= query($TEMP_NODES."/entry:".$idx."/data_4");
		$cfg_pport	= query($TEMP_NODES."/entry:".$idx."/data_5");
		$cfg_pprot	= query($TEMP_NODES."/entry:".$idx."/data_6");
		$cfg_sch	= query($TEMP_NODES."/entry:".$idx."/data_7");
		
		if ($cfg_en!="1") {$cfg_en="0";}

		echo "cfg_en=".$cfg_en.", cfg_desc=".$cfg_desc.", ";
		echo "cfg_tprot=".$cfg_tprot.", cfg_tstart=".$cfg_tstart.", cfg_tend=".$cfg_tend.", ";
		echo "cfg_pprot=".$cfg_pprot.", cfg_pport=".$cfg_pport. ", cfg_sch=".$cfg_sch."\n";

		$entry = "/nat/porttrigger/entry:".$idx."/";
		if (query($entry."enable")				!= $cfg_en)		{$dirty++; set($entry."enable",				$cfg_en);}
		if (query($entry."description")			!= $cfg_desc)	{$dirty++; set($entry."description",		$cfg_desc);}
		if (query($entry."trigger/protocol")	!= $cfg_tprot)	{$dirty++; set($entry."trigger/protocol",	$cfg_tprot);}
		if (query($entry."trigger/startport")	!= $cfg_tstart)	{$dirty++; set($entry."trigger/startport",	$cfg_tstart);}
		if (query($entry."trigger/endport")		!= $cfg_tend)	{$dirty++; set($entry."trigger/endport",	$cfg_tend);}
		if (query($entry."external/protocol")	!= $cfg_pprot)	{$dirty++; set($entry."external/protocol",	$cfg_pprot);}
		if (query($entry."external/portlist")	!= $cfg_pport)	{$dirty++; set($entry."external/portlist",	$cfg_pport);}
		if (query($entry."schedule/id")			!= $cfg_sch)	{$dirty++; set($entry."schedule/id",		$cfg_sch);}
	}

	if ($dirty > 0)	{$SUBMIT_STR=";submit RG_APP";}
	else			{$SUBMIT_STR="";}

	echo "dirty = ".$dirty.", SUBMIT_STR = [".$SUBMIT_STR."]\n";
	echo "-->\n";

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

/* --------------------------------------------------------------------------- */
?>

<script>
var prot_list = [
	["6112,0,0",	"6112"],
	["7175,0,0",	"51200-51201,51210"],
	["2019,0,0",	"2000-2038,2050-2051,2069,2085,3010-3030"],
	["47624,0,0",	"2300-2400,28800-29000"],
	["12053,0,0",	"12120,12122,24150-24220"],
	["554,0,0",		"6970-6999"]
];

var rules=<?=$MAX_RULES?>+1;
var max_rules=<?=$MAX_RULES?>;
var data = new Array(rules);
var AjaxReq = null;
var rules_cnt = 0;

function copy_application(index)
{
	var desc = get_obj("desc_"+index);
	var app = get_obj("app_"+index);
	var trigger_port = get_obj("trigger_port_"+index);
	var trigger_prot = get_obj("trigger_prot_"+index);
	var pub_port = get_obj("pub_port_"+index);
	var pub_prot = get_obj("pub_prot_"+index);
	var data;

	if (app.selectedIndex > 0)
	{
		desc.value = app.value;
		data = prot_list[app.selectedIndex - 1][0].split(",");
		pub_port.value = prot_list[app.selectedIndex - 1][1];

		trigger_port.value = data[0];
		select_index(trigger_prot, data[1]);
		select_index(pub_prot, data[2]);
	}
	else
	{
		alert('<?=$a_no_app_name?>');
	}
}

function check_trigger_port(index)
{
	var trigger_port_n = get_obj("trigger_port_"+index);
	var obj;

	var trigger = (trigger_port_n.value).split(",");
	if (trigger.length != 1) return false;

	var ports = trigger[0].split("-");
	if (ports.length == 1)
	{
		if (is_valid_port_str(ports[0]))
		{
			obj = get_obj("triggerstart_"+index);
			obj.value = ports[0];
			obj = get_obj("triggerend_"+index);
			obj.value = "";
			return true;
		}
	}
	else if (ports.length == 2)
	{
		if (is_valid_port_range_str(ports[0], ports[1]))
		{
			obj = get_obj("triggerstart_"+index);
			obj.value = ports[0];
			obj = get_obj("triggerend_"+index);
			obj.value = ports[1];
			return true;
		}
	}
	return false;
}

function check_public_port(index)
{
	var ports;
	var pub_port_n = get_obj("pub_port_"+index);

	var pubport = (pub_port_n.value).split(",");
	if (pubport.length < 1) return false;

	for (i=0; i<pubport.length; i++)
	{
		ports = pubport[i].split("-");
		if (ports.length == 1)
		{
			if (!is_valid_port_str(ports[0])) return false;
		}
		else if (ports.length == 2)
		{
			if (!is_valid_port_range_str(ports[0], ports[1])) return false;
		}
		else
		{
			return false;
		}
	}
	return true;
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	// init here ...
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
	var str = "TEMP_NODES="+escape("<?=$TEMP_NODES?>")+"&data=8";

	str += "&start="+(rules_cnt+1);
	for (var i=0; i<count && rules_cnt < max_rules; i++)
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
	var idx = 0;
	var i;
	var enable;
	var desc;
	var trigger_port;
	var trigger_prot;
	var pub_port;
	var pub_prot;
<? if ($HAS_SCHEDULE==1) { echo
"	var schedule;\n";
} ?>
	// do check here ....
	for (i=1; i<rules; i++)
	{
		enable			= get_obj("enable_"+i);
		desc			= get_obj("desc_"+i);
		trigger_port	= get_obj("trigger_port_"+i);
		trigger_start	= get_obj("triggerstart_"+i);
		trigger_end		= get_obj("triggerend_"+i);
		trigger_prot	= get_obj("trigger_prot_"+i);
		pub_port		= get_obj("pub_port_"+i);
		pub_prot		= get_obj("pub_prot_"+i);
<? if ($HAS_SCHEDULE==1) { echo
"		schedule		= get_obj(\"schedule_\"+i);\n";
} ?>

		if (!is_blank(desc.value))
		{
			if (check_trigger_port(i)==false)
			{
				alert("<?=$a_invalid_trigger_port?>");
				field_focus(trigger_port, "**");
				return false;
			}
			if (check_public_port(i)==false)
			{
				alert("<?=$a_invalid_firewall_port?>");
				field_focus(pub_port, "**");
				return false;
			}

			// check same rule exist or not
			for(j=1; j < i; j++)
			{
				if(get_obj("triggerstart_"+j).value == trigger_start.value && get_obj("triggerend_"+j).value == trigger_end.value
				   && get_obj("trigger_prot_"+j).value == trigger_prot.value && get_obj("pub_port_"+j).value == pub_port.value
				   && get_obj("pub_prot_"+j).value == pub_prot.value
					)
				{
					alert("<?=$a_same_rule_exist?>");
					field_focus(get_obj("desc_"+i), "**");
					return false;
				}
			}
			///////////////

			/* allocate space for this rule */
			idx++;
			data[idx] = new Array(8);
			data[idx][0]	= enable.checked ? "1" : "0";
			data[idx][1]	= desc.value;
			data[idx][2]	= trigger_start.value;
			data[idx][3]	= trigger_end.value;
			data[idx][4]	= trigger_prot.value;
			data[idx][5]	= pub_port.value;
			data[idx][6]	= pub_prot.value;
			data[idx][7]	= <? if ($HAS_SCHEDULE==1) { echo "schedule.value"; } else { echo "\"\""; } ?>;
		}
	}

	idx++;
	for (i=idx; i<rules; i++)
	{
		data[i] = new Array(8);
		data[i][0] = data[i][1] = data[i][2] = data[i][3] =
		data[i][4] = data[i][5] = data[i][6] = data[i][7] = "";
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
				<td width="75">&nbsp;</td>
				<td width="193" align=left><div align="left">&nbsp;</div></td>
				<td width="141" align=middle><div align="center"><?=$m_port?></div></td>
				<td width="58" align=middle><div align="center"><?=$m_traffic_type?></div></td>
				<?

				if ($HAS_SCHEDULE=="1")
				{ echo "<td width=\"58\" align=middle><div align=\"center\">".$m_schedule."</div></td>\n"; }

				?>
			</tr><?

			$index=0;
			while ($index < $MAX_RULES)
			{
				$index++;
				anchor("/nat/porttrigger/entry:".$index);
				require("/www/__adv_app.php");
			}

?>			</tbody>
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
