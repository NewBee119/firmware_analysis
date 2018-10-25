<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		= "adv_mac_filter";
$MY_MSG_FILE	= $MY_NAME.".php";
$CATEGORY		= "adv";
$TEMP_NODES		= "/runtime/post/session_".$sid;
/* --------------------------------------------------------------------------- */
$COUNT_RULES_PATH	= "/security/macfilter/entry:";
$COUNT_RULES_VALUE	= "mac";
$MAX_RULES = query("/security/macfilter/max_rules");
if($MAX_RULES==""){$MAX_RULES=10;}
$HAS_SCHEDULE = query("/runtime/func/schedule/macfilter");
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");
	echo "<!--\n";
	echo "cfg_en=".$cfg_en."\n";
	echo "cfg_act=".$cfg_act."\n";

	$i=0;
	$dirty=0;
	while ($i < $MAX_RULES)
	{
		$index = $i+1;
		$en  = query($TEMP_NODES."/entry:".$index."/data_0"); if ($en!="1") {$en="0";}
		$mac = query($TEMP_NODES."/entry:".$index."/data_1");
		$sch = query($TEMP_NODES."/entry:".$index."/data_2");

		echo "en  = ".$en."\n";
		echo "mac = ".$mac."\n";
		echo "sch = ".$sch."\n";

        $entry = "/security/macfilter/entry:".$index;
		if (query($entry."/enable") != $en)		{$dirty++; set($entry."/enable", $en);}
		if (query($entry."/mac") != $mac)		{$dirty++; set($entry."/mac", $mac);}
		if (query($entry."/schedule/id")!=$sch)	{$dirty++; set($entry."/schedule/id", $sch);}
		$i++;
	}
	anchor("/security/macfilter");
	if (query("enable")!=$cfg_en)	{$dirty++; set("enable", $cfg_en);}
	if (query("action")!=$cfg_act)	{$dirty++; set("action", $cfg_act);}

	if($dirty > 0)	{$SUBMIT_STR=";submit RG_MAC_FILTER";}
	else			{$SUBMIT_STR="";}

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
anchor("/security/macfilter");
$enable=query("enable");
$action=query("action");
if ($enable==1)
{	if ($action==1)	{$mode=1;}
	else			{$mode=2;}
} else				{$mode=0;}

/* --------------------------------------------------------------------------- */
?>

<script>
var sch_list=[['index','uid','description']<?
$sch_num=0;
for("/sys/schedule/entry")
{
	$sch_num++;
	echo ",\n ['".$@."','".query("id")."','".get("j","description")."']";
}
?>];

var rules=<?=$MAX_RULES?>+1;
var max_rules=<?=$MAX_RULES?>;
var data = new Array(rules);
var AjaxReq = null;
var rules_cnt = 0;

function print_sch(n, sch_index)
{
	var str="";
	var i;
	str="<select name='"+n+"' id='"+n+"'>";
	str+="<option value=''><?=$m_always_on?></option>";
	for(i=1; i<=<?=$sch_num?>; i++)
	{
		if(sch_index==sch_list[i][1])	str+="<option value='"+sch_list[i][1]+"' selected>"+sch_list[i][2]+"</option>";
		else							str+="<option value='"+sch_list[i][1]+"'>"+sch_list[i][2]+"</option>";
	}
	str+="</select>";
	str+="&nbsp;";
	str+="<input type=button value='<?=$m_add_new_sch?>' onclick=\"javascript:self.location.href='tools_sch.php'\">";
	document.write(str);
	var f=get_obj(n);
}
function copy_mac(index)
{
	var mac = get_obj("mac_"+index);
	var dhcp = get_obj("dhcp_"+index);

	if (dhcp.value == 0)
	{
		alert("<?=$a_no_pc_selected?>");
		return false;
	}
	mac.value = dhcp.value;
}

function clear_mac(index)
{
	var mac = get_obj("mac_"+index);
	mac.value = "";
	var sch = get_obj("schedule_"+index);
	select_index(sch, "");
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

		if (rules_cnt < max_rules)  send_rules(20);
		else                        get_obj("frm").submit();
	}
	return true;
}

function send_rules(count)
{
	var str = "TEMP_NODES="+escape("<?=$TEMP_NODES?>")+"&data=3";

	str += "&start="+(rules_cnt+1);
	for (var i=0; i<count && rules_cnt < max_rules; i++)
	{
		rules_cnt++;
		str += "&d_"+rules_cnt+"_0="+escape(data[rules_cnt][0]);
		str += "&d_"+rules_cnt+"_1="+escape(data[rules_cnt][1]);
		str += "&d_"+rules_cnt+"_2="+escape(data[rules_cnt][2]);
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
	var f = get_obj("frm");
	var obj, obj2;
	var mac;
	var count = 0;
	var see_me = 0;
	var i, j;

	for (i=1; i <= <?=$MAX_RULES?>; i++)
	{
		obj = get_obj("mac_"+i);
		if (!is_blank(obj.value))
		{
			mac = get_mac(obj.value);
			if (is_blank(mac[1]) || !is_valid_mac(mac[1]) || is_blank(mac[2]) || !is_valid_mac(mac[2]) ||
				is_blank(mac[3]) || !is_valid_mac(mac[3]) || is_blank(mac[4]) || !is_valid_mac(mac[4]) ||
				is_blank(mac[5]) || !is_valid_mac(mac[5]) || is_blank(mac[6]) || !is_valid_mac(mac[6]))
			{
				alert("<?=$a_invalid_mac?>");
				field_focus(obj, "**");
				return false;
			}
			for (j=1; j<=6; j++) { if (mac[j].length == 1) mac[j] = "0"+mac[j]; }
			obj.value = mac[1].toUpperCase()+":"+mac[2].toUpperCase()+":"+mac[3].toUpperCase()+":"+
						mac[4].toUpperCase()+":"+mac[5].toUpperCase()+":"+mac[6].toUpperCase();
			count++;
		}
	}
	if (count > 0)
	{
		count = 0;
		for (i=1; i <= <?=$MAX_RULES?>; i++)
		{
			obj = get_obj("mac_"+i);
			if (!is_blank(obj.value))
			{
				for (j=i+1; j<=<?=$MAX_RULES?>; j++)
				{
					obj2 = get_obj("mac_"+j);
					if (!is_blank(obj2.value) && obj.value == obj2.value)
					{
						alert("<?=$a_macaddr_exist?>");
						field_focus(obj2, "**");
						return false;
					}
				}
				count++;
				data[count] = new Array(3);
				data[count][0] = get_obj("entry_enable_"+i).checked ? "1" : "";
				data[count][1] = obj.value;
				data[count][2] = <?
				if ($HAS_SCHEDULE==1)	{ echo "get_obj(\"schedule_\"+i).value"; }
				else					{ echo "\"\""; } ?>;

				if (data[count][0] == "1" && data[count][1] == "<?=$macaddr?>") see_me++;
			}
		}

		if ((f.mode.value==2 && see_me > 0) || (f.mode.value==1 && see_me == 0))
		{
			if (confirm("<?=$a_blocking_warning?>")==false) return false;
		}
	}
	else { f.mode.value = 0; }

	switch (f.mode.value)
	{
	case "2":		get_obj("cfg_en").value = 1; get_obj("cfg_act").value = 0; break;
	case "1":		get_obj("cfg_en").value = 1; get_obj("cfg_act").value = 1; break;
	default:	get_obj("cfg_en").value = 0; get_obj("cfg_act").value = 0; break;
	}

	count++;
	for (i=count; i<rules; i++)
	{
		data[i] = new Array(3);
		data[i][0] = data[i][1] = data[i][2] = "";
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
<input type='hidden' id='cfg_en' name='cfg_en' value="">
<input type='hidden' id='cfg_act' name='cfg_act' value="">
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
<!--</form>-->
<!-- ________________________________ Main Content Start ______________________________ -->
<!--<form name="frm" id="frm">-->
		<div class="box">
			<h2><?=$MAX_RULES?> - <?=$m_mac_title?></h2>
			<table cellSpacing=1 cellPadding=2 width=525 border=0>
			<tr><td><?=$m_mac_desc?></td></tr>
			<tr>
				<td>
				<select name="mode">
					<option value="0"<?if ($mode==0){echo " selected";}?>><?=$m_mac_filter_off?></option>
					<option value="1"<?if ($mode==1){echo " selected";}?>><?=$m_mac_filter_allow_entries?></option>
					<option value="2"<?if ($mode==2){echo " selected";}?>><?=$m_mac_filter_deny_entries?></option>
				</select>
				</td>
			</tr>
			</table>
			<br>
			<script>remain_rules();</script><br><br>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=4 width=525 bgColor=#dfdfdf border=1>
			<tr>
				<td align=middle width=20>&nbsp;</td>
				<td class=c_tb><?=$m_macaddr?></td>
				<td>&nbsp;</td>
				<td class=c_tb><?=$m_dhcp_client_list?></td>
				<? if ($HAS_SCHEDULE==1){echo "<td class=c_tb>".$m_schedule."</td>\n";} ?>
				<!--<td width="50" align=middle>&nbsp;</td>-->
			</tr>
<?
			$index=0;
			while ($index < $MAX_RULES)
			{
				$index++;
				anchor("/security/macfilter/entry:".$index);
				require("/www/__adv_mac_filter.php");
			}

?>			</table>
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
