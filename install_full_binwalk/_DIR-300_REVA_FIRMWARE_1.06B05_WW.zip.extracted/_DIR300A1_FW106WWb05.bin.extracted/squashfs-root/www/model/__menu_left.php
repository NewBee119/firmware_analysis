<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td id="sidenav_container">
		<div id="sidenav"><? require("/www/model/__menu_".$CATEGORY.".php"); ?></div>
		
	</td>
</tr>
<script>
var __AjaxReq = null;
var __update_wan_conn_status_period=3000;

function __createRequest()
{
	var request = null;
	try { request = new XMLHttpRequest(); }
	catch (trymicrosoft)
	{
		try { request = new ActiveXObject("Msxml2.XMLHTTP"); }
		catch (othermicrosoft)
		{
			try { request = new ActiveXObject("Microsoft.XMLHTTP"); }
			catch (failed)
			{
				request = null;
			}
		}
	}
	if (request == null) alert("Error creating request object !");
	return request;
}

function __send_request(url)
{
	if (__AjaxReq == null) __AjaxReq = __createRequest();
	__AjaxReq.open("GET", url, true);
	__AjaxReq.onreadystatechange = __update_page;
	__AjaxReq.send(null);
}

function __update_state()
{
	__send_request("/model/__ajax_wan_conninfo.php?r="+generate_random_str());
}

function __update_page()
{
	var conn_msg="";
	if (__AjaxReq != null && __AjaxReq.readyState == 4)
	{
		if (__AjaxReq.responseText.substring(0,3)=="var")
		{
			eval(__AjaxReq.responseText);
			switch (__result[0])
			{
				case "OK":
					if(__result[1] == "connected")
					{
						get_obj("wan_online").style.display = "";
						get_obj("wan_offline").style.display = "none";
					}
					else
					{
						get_obj("wan_online").style.display = "none";
						get_obj("wan_offline").style.display = "";
					}
				break;

				default :
				break;
			}
			setTimeout("__update_state()", __update_wan_conn_status_period);
			delete __result;
		}
	}
}

function do_reboot()
{
	if(!confirm("<?=$a_sure_to_reboot?>")) return;
	var str="";
	str="../sys_cfg_valid.xgi?";
	str+=exe_str("submit REBOOT");
	self.location.href=str;
}

setTimeout("__update_state()", __update_wan_conn_status_period);
<?
if (query("/runtime/wan/inf:1/connectstatus")=="connected" &&
	query("/runtime/switch/wan_port/linktype")!="0")
{
	$online_style="";
	$offline_style="style=\"display:none\"";
}
else
{
	$online_style="style=\"display:none\"";
	$offline_style="";
}
?>
</script>
<tr>
	<td>
		<br>
		<div id="wan_online" <?=$online_style?>>
			<table>
			<tr>
				<td><img src="/pic/wan_on.jpg"></td>
				<td class=wansts>Internet Online</td>
			</tr>
			</table>
		</div>
		<div id="wan_offline" <?=$offline_style?>>
			<table>
			<tr>
				<td><img src="/pic/wan_off.jpg"></td>
				<td class=wansts>Internet Offline</td>
			</tr>
			</table>
		</div>
		<br>
		<table>
		<tr>
			<td align=center width=120><input type=button name="never_disabled" value=<?=$m_reboot?> onclick="do_reboot();"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
