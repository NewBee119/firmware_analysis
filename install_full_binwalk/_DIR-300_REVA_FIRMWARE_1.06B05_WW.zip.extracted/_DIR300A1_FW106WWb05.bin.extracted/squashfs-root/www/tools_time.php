<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_time";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="bsc";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");

	echo "<!--\n";
	echo "tzone=".$tzone."\n";
	echo "daylight=".$daylight."\n";
	echo "sync=".$sync."\n";
	echo "ntp_server=".$ntp_server."\n";
	echo "year=".$year."\n";
	echo "mon=".$mon."\n";
	echo "day=".$day."\n";
	echo "hour=".$hour."\n";
	echo "min=".$min."\n";
	echo "sec=".$sec."\n";

	$dirty=0;
	if (query("/time/timezone")!=$tzone)			{$dirty++; set("/time/timezone", $tzone); }
	if (query("/time/daylightsaving")!=$daylight)	{$dirty++; set("/time/daylightsaving", $daylight); }
	if (query("/time/syncwith")!=$sync)				{$dirty++; set("/time/syncwith", $sync); }
	if ($sync == "2")
	{
		if (query("/time/ntpserver/ip")!=$ntp_server)	{$dirty++; set("/time/ntpserver/ip", $ntp_server); }
		if ($dirty > 0) { $SUBMIT_STR=";submit TIME"; }
	}
	else
	{
		$XGISET_STR="setPath=/runtime/time/";
		$XGISET_STR=$XGISET_STR."&date=".$mon."/".$day."/".$year;
		$XGISET_STR=$XGISET_STR."&time=".$hour.":".$min.":".$sec;
		$XGISET_STR=$XGISET_STR."&endSetPath=1";
		if ($dirty > 0) { $SUBMIT_STR=";submit TIME"; }
		$dirty++;
	}
	echo "SUBMIT_STR=".$SUBMIT_STR."\n";
	echo "-->\n";
	$NEXT_PAGE=$MY_NAME;
	if($dirty > 0)	{require($G_SAVING_URL);}
	else			{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$cfg_date	= query("/runtime/time/date");
$cfg_time	= query("/runtime/time/time");
$cfg_tzone	= query("/time/timezone");
$cfg_ds		= query("/time/daylightsaving");
$cfg_sync	= query("/time/syncwith");
$cfg_ntp_server	= get(h,"/time/ntpserver/ip");

/* --------------------------------------------------------------------------- */
?>

<script>
// Lily: gray Enable Daynight Saving
dsFlag=[''<?
for("/tmp/tz/zone")
{
	echo ",'";
	map("dst","","0",*,"1");
	echo "'";
}
?>];

function CheckDS(chkid)
{
	var f=get_obj("frm");
	var val=dsFlag[chkid];
	var dis=f.tzone.disabled;
	/* Synchronize the modem's clock; */
	if (val=="0")
	{
		f.daylight.checked=false;
		dis=true;
	}
	f.daylight.disabled=dis;
}

/* check LeapYear */
function isFourDigitYear(year)
{
	if (year.length != 4)
	{
		document.frm.year.select();
		document.frm.year.focus();
		return false;
	}
	else
	{
		return true;
	}
}

function selectDate()
{
	var year = document.frm.year.value;
	if (isFourDigitYear(year))
	{
		var mon = document.frm.mon.selectedIndex;
		mon = parseInt(mon);
		year = parseInt(year);
		var days = getDaysInMonth(mon+1,year);
		for (var i=0;i<days;i++)
		{
			document.frm.day.options[i]=new Option(i+1, i+1);
			document.frm.day.length=days;
		}

	}
}

function getDaysInMonth(mon,year)
{
	var days;
	if (mon==1 || mon==3 || mon==5 || mon==7 || mon==8 || mon==10 || mon==12) days=31;
	else if (mon==4 || mon==6 || mon==9 || mon==11) days=30;
	else if (mon==2)
	{
		if (isLeapYear(year)) { days=29; }
		else { days=28; }
	}
	return (days);
}

function isLeapYear (Year)
{
	if (((Year % 4)==0) && ((Year % 100)!=0) || ((Year % 400)==0)) {
		return (true);
	} else { return (false); }
}
// Lily end

var AjaxReq = null;

function send_request()
{
	var url = "/sync_time.php?r="+generate_random_str();
	AjaxReq = createRequest();
	AjaxReq.open("GET", url, true);
	AjaxReq.onreadystatechange = update_page;
	AjaxReq.send(null);
}

var count = 0;

function update_page()
{
	count ++;
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		/* get_obj("sync_msg").innerHTML = count+":"+AjaxReq.responseText; */
		if (AjaxReq.responseText.substring(0,3)=="var")
		{
			eval(AjaxReq.responseText);
			if (result[0] == "WAIT")
			{
				setTimeout("send_request()", 1000);
			}
			else if (result[0] == "OK")
			{
				var msg_str = "<?=$m_synced?>";
				var tstr;

				tstr = result[4].split("T");
				msg_str += "<br>(<?=$m_ntp_server?>: "+result[3]+", <?=$m_time?>: "+tstr[0]+" "+tstr[1]+")";
				tstr = result[5].split("T");
				msg_str += "<br><?=$m_next_sync?>: "+tstr[0]+" "+tstr[1];

				get_obj("time_str").innerHTML = "&nbsp;"+result[1]+"&nbsp;"+result[2];
				get_obj("sync_msg").innerHTML = msg_str;
			}
			else if (result[0] == "MANUAL")
			{
				get_obj("time_str").innerHTML = "&nbsp;"+result[1]+"&nbsp;"+result[2];
				get_obj("sync_msg").innerHTML = "";
			}
			delete AjaxReq;
			delete result;
			AjaxReq = null;
		}
	}
}

function on_click_ntp_sync()
{
	var f = get_obj("frm");

	if (is_blank(f.ntp_server.value) || strchk_hostname(f.ntp_server.value)==false)
	{
		alert("<?=$a_invalid_ntp_server?>");
		return false;
	}

	var str = "sync_time.xgi?r="+generate_random_str();

	str += "&setPath=/time/";
	str += "&timezone="+get_obj("tzone").value;
	str += "&daylightsaving=";
	if (get_obj("daylight").checked) str += "1";
	else str += "0";
	str += "&syncwith=2";
	str += "&ntpserver/ip="+f.ntp_server.value;
	str += "&endSetPath=1";
	str += exe_str("submit TIME");

	get_obj("sync_msg").innerHTML = "<?=$m_syncing?> ...";

	AjaxReq = createRequest();
	AjaxReq.open("GET", str, true);
	AjaxReq.onreadystatechange = update_page;
	AjaxReq.send(null);
}

function on_click_manual_sync()
{
	var d = new Date();
	var date = (d.getMonth()+1)+"/"+d.getDate()+"/"+d.getFullYear();
	var time = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
	var str = "sync_time.xgi?";

	str += "set/time/timezone="+get_obj("tzone").value;
	str += "&set/time/daylightsaving=";
	if (get_obj("daylight").checked) str += "1";
	else str += "0";
	str += "&set/time/syncwith=0";
	str += "&setPath=/runtime/time/";
	str += "&date="+date+"&time="+time;
	str += "&endSetPath=1";
	str += exe_str("submit TIME");

	get_obj("sync_msg").innerHTML = "<?=$m_syncing?> ...";

	AjaxReq = createRequest();
	AjaxReq.open("GET", str, true);
	AjaxReq.onreadystatechange = update_page;
	AjaxReq.send(null);
}

function on_click_ntp()
{
	var f = get_obj("frm");
	var dis=f.time_type.checked ? false : true;;
	/* ntp part */
	f.ntp_server.disabled = f.ntp_sync.disabled = dis;
	f.manual_sync.disabled = !(dis);
	/* manual part */
	f.year.disabled = f.mon.disabled = f.day.disabled = !(dis);
	f.hour.disabled = f.min.disabled = f.sec.disabled = !(dis);
}

function set_time()
{
	var date = new Date();
	var year = date.getFullYear();
	var mon = date.getMonth();

	mon = parseInt(mon);
	year = parseInt(year);
	var days = getDaysInMonth(mon+1,year);
	for(var i=0;i<days;i++){
		document.frm.day.options[i]=new Option(i+1, i+1);
		document.frm.day.length=days;
	}
	select_index(get_obj("year"), date.getFullYear());
	get_obj("mon").selectedIndex = date.getMonth();
	get_obj("day").selectedIndex = date.getDate() - 1;
	get_obj("hour").selectedIndex = date.getHours(); 
	get_obj("min").selectedIndex = date.getMinutes(); 
	get_obj("sec").selectedIndex = date.getSeconds();
}

/* page init functoin */
function init()
{
	/* init here ... */

	/* Lily:daylight saving */
	var f=get_obj("frm");
	if (dsFlag[<?=$cfg_tzone?>]=="1" && "<?=$cfg_ds?>"=="1")	{ f.daylight.checked=true; }
	else	{ f.daylight.checked=false; }
	/* Lily end */

	var msg_str = "<?=$m_synced?>";
	var next_msg = "";
	var tstr;
	<? require("/www/sync_time.php"); ?>

	if (result[0] == "OK")
	{
		tstr = result[4].split("T");
		msg_str += "<br>(<?=$m_ntp_server?>: "+result[3]+", <?=$m_time?>: "+tstr[0]+" "+tstr[1]+")";
		tstr = result[5].split("T");
		next_msg += "<br><?=$m_next_sync?>: "+tstr[0]+" "+tstr[1];

		get_obj("sync_msg").innerHTML = msg_str+<?
			if ($cfg_sync=="2") { echo "next_msg"; } else { echo "\"\""; }
		?>;
	}

	CheckDS("<?=$cfg_tzone?>");	/* Lily:gray Enable Daylight Saving */
	set_time();
	select_index(get_obj("ntp_server"), "<?=$cfg_ntp_server?>");
	on_click_ntp();
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	// do check here ....
	if (f.time_type.checked)
	{
		f.sync.value = "2";
		if (is_blank(f.ntp_server.value) || strchk_hostname(f.ntp_server.value)==false)
		{
			alert("<?=$a_invalid_ntp_server?>");
			return false;
		}
	}
	else
	{
		f.sync.value = "0";
	}
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
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
		<?
		require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");
		echo $G_APPLY_CANEL_BUTTON;
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_title_time?></h2>
			<table width="525">
			<tr>
				<td class="r_tb" width="150"><?=$m_time?>&nbsp;:</td>
				<td class="l_tb" width="367">
					<strong><div id="time_str">&nbsp;<?=$cfg_date?>&nbsp;<?=$cfg_time?></div></strong>
				</td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_time_zone?>&nbsp;:</td>
				<td class="l_tb">&nbsp;
					<select size=1 name=tzone id=tzone onchange="CheckDS(this.value)">
<?
						for ("/tmp/tz/zone")
						{
							echo "<option value=".$@;
							if ($cfg_tzone==$@) {echo " selected";}
							echo ">".get(h,"name")."</option>\n";
						}
?>					</select>
				</td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_enable_ds?>&nbsp;:</td>
				<td class="l_tb">&nbsp;
					<input type="checkbox" name="daylight" id="daylight" value="1">
					<input type="button" name="manual_sync" id="manual_sync"
						value="<?=$m_msync_msg?>" onclick="on_click_manual_sync();">
				</td>
			</tr>
			</table>
		</div>
		<div class=box>
			<h2><?=$m_title_ntp?></h2>
			<table width="525">
			<tr>
				<td colspan=2>
					<input name="time_type" type=checkbox id="time_type" value="1" onclick="on_click_ntp();"<?
					if ($cfg_sync=="2") {echo " checked";}
					?>>
					<?=$m_enable_ntp?>
					<input type=hidden name=sync id=sync>
				</td>
			</tr>
			<tr>
				<td class="r_tb"><b><?=$m_ntp_server?>&nbsp;:</b></td>
				<td class="l_tb">&nbsp;
					<select name="ntp_server" id="ntp_server">
						<option value=""><?=$m_select_ntps?></option>
						<option value="ntp1.dlink.com">ntp1.dlink.com</option>
						<option value="ntp.dlink.com.tw">ntp.dlink.com.tw</option>
					</select>
					<input name="ntp_sync" id="ntp_sync" type="button" value="<?=$m_update_now?>" onclick="on_click_ntp_sync();">
				</td>
			</tr>
			<tr><td colspan=2 height=20><div id="sync_msg"></div></td></tr>
			</table>
		</div>
		<div class=box>
			<h2><?=$m_title_manual?></h2>
			<table width="525" border=0 cellpadding=2 cellspacing=0>
			<tbody>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td><?=$m_year?></td>
				<td><font face="Arial, Helvetica, sans-serif" size=2>
					<select id="year" name="year" size=1 style="WIDTH: 50px" onChange="selectDate()"><?

						$i=2009;
						while ($i<2030) { $i++; echo "<option value=".$i.">".$i."</option>\n"; }

					?></select>
				</font></td>
				<td><?=$m_month?></td>
				<td><font face="Arial, Helvetica, sans-serif" size=2>
					<select id="mon" name="mon" size=1 style="WIDTH: 50px" onChange="selectDate()">
						<option value=1>Jan</option>
						<option value=2>Feb</option>
						<option value=3>Mar</option>
						<option value=4>Apr</option>
						<option value=5>May</option>
						<option value=6>Jun</option>
						<option value=7>Jul</option>
						<option value=8>Aug</option>
						<option value=9>Sep</option>
						<option value=10>Oct</option>
						<option value=11>Nov</option>
						<option value=12>Dec</option>
					</select>
				</font></td>
				<td><?=$m_day?></td>
				<td><font face="Arial, Helvetica, sans-serif" size=2>
					<select size=1 id="day" name="day" style="WIDTH: 50px"></select>
				</font></td>
			</tr>
			<tr>
				<td><?=$m_hour?></td>
				<td><font face="Arial, Helvetica, sans-serif" size=2>
					<select size=1 id="hour" name="hour" style="WIDTH: 50px"><?

						$i=0;
						while ($i<24) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }

					?></select>
				</font></td>
				<td><?=$m_minute?></td>
				<td><font face="Arial, Helvetica, sans-serif" size=2>
					<select size=1 id="min" name="min" style="WIDTH: 50px"><?

						$i=0;
						while ($i<60) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }

					?></select>
				</font></td>
				<td><?=$m_second?></td>
				<td><font face="Arial, Helvetica, sans-serif" size=2>
					<select size=1 id="sec" name="sec" style="WIDTH: 50px"><?

						$i=0;
						while ($i<60) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }

					?></select>
				</font></td>
			</tr>
			</tbody>
			</table>
		</div>
		<div id="box_bottom">
		<? echo $G_APPLY_CANEL_BUTTON; ?>
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
