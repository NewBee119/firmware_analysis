<?
/* vi: set sw=4 ts=4: ---------------*/
$MY_NAME		= "do_wps_step2_pin";
$MY_MSG_FILE	= $MY_NAME.".php";
$MY_ACTION		= "step2_pin";
/* --------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>
<script>
var AjaxReq = null;

function send_request(url, callback)
{
	if (AjaxReq == null) AjaxReq = createRequest();
	AjaxReq.open("GET", url, true);
	AjaxReq.onreadystatechange = callback;
	AjaxReq.send(null);
}

var cdTimer = 0;
var wpsTimer = 0;
var countdown = -1;

function cleanup_exit(url)
{
	if (AjaxReq != null) delete AjaxReq;
	if (cdTimer > 0) clearInterval(cdTimer);
	if (wpsTimer > 0) clearInterval(wpsTimer);
	AjaxReq = null;
	cdTimer = 0;
	wpsTimer = 0;
	if (url != null) self.location.href=url;
}

function counting_down()
{
	countdown--;
	document.frm.WaitInfo.value = countdown;
	if (countdown == 0) cleanup_exit("do_wps.php?TARGET_PAGE=step3_fail");
}

function start_counting(count)
{
	if (cdTimer == 0)
	{
		countdown = count;
		document.frm.WaitInfo.value = countdown;
		cdTimer = setInterval('counting_down()', 1000);
	}
}

function callback_check_wps()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if (AjaxReq.responseText.substring(0,3)=="var")
		{
			eval(AjaxReq.responseText);
			switch (result[0])
			{
			case "OK":
				start_counting(120);
				start_checking_wps();
				if (result[1]=="WPS_SUCCESS") cleanup_exit("bsc_wlan.php");
				break;
			default:
				/* Woops! Something wrong! */
				cleanup_exit("/bsc_wlan.php");
				break;
			}
		}
	}
}

function checking_wps()
{
	var url = "wpsinfo.php?random_num="+generate_random_str();
	send_request(url, callback_check_wps);
}

function start_checking_wps()
{
	if (wpsTimer == 0) { wpsTimer = setInterval('checking_wps()', 3000); }
}

function init()
{
	var url = "wpsinfo.xgi?random_num="+generate_random_str();
	url += exe_str("submit DO_WPS");
	send_request(url, callback_check_wps);
}
</script>
<body onload="init();" onunload="cleanup_exit(null);" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="TARGET_PAGE" value="<?=$MY_ACTION?>">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		</div>
		<br>
	</td>
	<td width=10%></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
