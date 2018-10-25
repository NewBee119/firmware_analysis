<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="adv_url_filter";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="bsc";
$TEMP_NODES		= "/runtime/post/session_".$sid;
/* --------------------------------------------------------------------------- */
$COUNT_RULES_PATH	= "/security/urlblocking/entry:";
$COUNT_RULES_VALUE	= "url";
$MAX_RULES = query("/security/urlblocking/max_rules");
if($MAX_RULES==""){$MAX_RULES=20;}
$HAS_SCHEDULE = query("/runtime/func/schedule");
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");
	echo "<!--\n";
	echo "enable=".$enable."\n";
	echo "action=".$action."\n";

	$i=0;
	$dirty=0;
	anchor("/security/urlblocking");
	while ($i < $MAX_RULES)
	{
		$index = $i+1;
		$en  = query($TEMP_NODES."/entry:".$index."/data_0"); if ($en!="1") {$en="0";}
		$url = query($TEMP_NODES."/entry:".$index."/data_1");
		$sch = query($TEMP_NODES."/entry:".$index."/data_2");

		echo "en  = ".$en."\n";
		echo "url = ".$url."\n";
		echo "sch = ".$sch."\n";

		$entry = "entry:".$index;
		if (query($entry."/enable")	!= $en)		{$dirty++; set($entry."/enable", $en);}
		if (query($entry."/url")	!= $url)	{$dirty++; set($entry."/url", $url);}
		if (query($entry."/schedule/id")!=$sch)	{$dirty++; set($entry."/schedule/id", $sch);}
		$i++;
	}
	if (query("enable")!=$enable)	{$dirty++; set("enable", $enable);}
	if (query("action")!=$action)	{$dirty++; set("action", $action);}

	if($dirty  > 0)	{$SUBMIT_STR=";submit RG_BLOCKING";}
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
require("/www/model/__count_rules.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
anchor("/security/urlblocking");
$enable=query("enable");
$action=query("action");
if ($enable==1)
{	if ($action==1)	{$mode=1;}
	else			{$mode=2;}
} else				{$mode=0;}

/* --------------------------------------------------------------------------- */
?>

<script>
var rules=<?=$MAX_RULES?>+1;
var max_rules=<?=$MAX_RULES?>;
var data = new Array(rules);
var AjaxReq = null;
var rules_cnt = 0;

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
	var i, j;
	var obj, obj2;
	var count=0;
	for (i = 1; i <= <?=$MAX_RULES?>; i++)
	{	
		obj = get_obj("url_"+i);
		if (!is_blank(obj.value))
		{
			if(strchk_url(obj.value)==false)
			{
				alert("<?=$a_invalid_url?>");
				field_focus(obj, "**");
	 			return false;
	 		}
			count++;
		}
	}
	if (count > 0)
	{
		count = 0;
		for (i=1; i <= <?=$MAX_RULES?>; i++)
		{
			obj = get_obj("url_"+i);
			if (!is_blank(obj.value))
			{
				for (j = i+1; j <= <?=$MAX_RULES?>; j++)
				{
					obj2 = get_obj("url_"+j);
					if (!is_blank(obj2.value) && obj.value == obj2.value)
					{
						alert("<?=$a_same_url_entry_exists?>");
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
			}
		}
	}
	else { f.mode.value = 0; }

	switch (f.mode.value)
	{
	case "2": /* deny entries */	f.enable.value = 1; f.action.value = 0;	break;
	case "1": /* allow entrys */	f.enable.value = 1; f.action.value = 1; break;
	default: /* disabled */		f.enable.value = 0; break;
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

function clear_url()
{
	var f=get_obj("frm");
	for (var i = 1; i <= <?=$MAX_RULES?>; i++)
	{
		eval("f.url_"+i).value = "";
	}
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
			<h2><?=$MAX_RULES?> - <?=$m_title_url_filter?></h2>
			<table cellSpacing=1 cellPadding=2 width=525 border=0>
			<tr>
				<td><?=$m_desc_url_filter?></td>
			</tr>
			<tr>
				<td>
					<select name="mode">
						<option value="0"<?if ($mode==0){echo " selected";}?>><?=$m_disable_url_filter?></option>
						<option value="1"<?if ($mode==1){echo " selected";}?>><?=$m_allow_entries_only?></option>
						<option value="2"<?if ($mode==2){echo " selected";}?>><?=$m_deny_entries_only?></option>
					</select>
					<input type=hidden id=enable name=enable>
					<input type=hidden id=action name=action>
				</td>
			</tr>
			</table>
			<br><script>remain_rules();</script><br><br>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr>
				<td align=middle width=20>&nbsp;</td>
				<td width="255" class=c_tb><?=$m_website_url?></td>
				<? if ($HAS_SCHEDULE==1){echo "<td class=c_tb>".$m_schedule."</td>\n";} ?>
			</tr>
			<?
				$index=0;
				while($index<$MAX_RULES)
				{
					$index++;
					anchor("/security/urlblocking/entry:".$index);
					require("/www/__adv_url_filter.php");	
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
