<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_ium";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");

	echo "<!--\n";
	echo "ium_enable=".$ium_enable."\n";
	echo "year=".$year."\n";
	echo "mon=".$mon."\n";
	echo "day=".$day."\n";
	echo "hour=".$hour."\n";
	echo "tc_enable=".$tc_enable."\n";
	echo "en_downlimit=".$en_downlimit."\n";
	echo "down_limit=".$down_limit."\n";
	echo "en_uplimit=".$en_uplimit."\n";
	echo "up_limit=".$up_limit."\n";
	echo "enable_disconn_wan=".$enable_disconn_wan."\n";
	echo "enable_web_notify=".$enable_web_notify."\n";
	echo "down_threshold=".$down_threshold."\n";
	echo "up_threshold=".$up_threshold."\n";
	echo "enable_email_notify=".$enable_email_notify."\n";
	echo "email_period=".$email_period."\n";
	echo "time_unit=".$time_unit."\n";

	$dirty=0;
	if (query("/flowmeter/enable")!=$ium_enable)			{$dirty++; set("/flowmeter/enable", $ium_enable); }
	if (query("/flowmeter/starttime/year")!=$year)			{$dirty++; set("/flowmeter/starttime/year", $year); }
	if (query("/flowmeter/starttime/month")!=$mon)			{$dirty++; set("/flowmeter/starttime/month", $mon); }
	if (query("/flowmeter/starttime/day")!=$day)			{$dirty++; set("/flowmeter/starttime/day", $day); }
	if (query("/flowmeter/starttime/hour")!=$hour)			{$dirty++; set("/flowmeter/starttime/hour", $hour); }

	if($ium_enable!=1)
	{
		set("/flowmeter/starttime/year", "");
		//set("/flowmeter/starttime/month", "");
		//set("/flowmeter/starttime/day", "");
		//set("/flowmeter/starttime/hour", "");
	}

	if (query("/flowmeter/tc/enable")!=$tc_enable)			{$dirty++; set("/flowmeter/tc/enable", $tc_enable); }
	if (query("/flowmeter/tc/downlimit/enable")!=$en_downlimit)			
	{ $dirty++; set("/flowmeter/tc/downlimit/enable", $en_downlimit); }
	if (query("/flowmeter/tc/downlimit/threshold")!=$down_limit)			
	{ $dirty++; set("/flowmeter/tc/downlimit/threshold", $down_limit); }
	if (query("/flowmeter/tc/uplimit/enable")!=$en_uplimit)			
	{ $dirty++; set("/flowmeter/tc/uplimit/enable", $en_uplimit); }
	if (query("/flowmeter/tc/uplimit/threshold")!=$up_limit)			
	{ $dirty++; set("/flowmeter/tc/uplimit/threshold", $up_limit); }

	if (query("/flowmeter/tc/discon_wan")!=$enable_disconn_wan)
	{ $dirty++; set("/flowmeter/tc/discon_wan", $enable_disconn_wan); }
	
	if (query("/flowmeter/tc/web_notify/enable")!=$enable_web_notify)
	{ $dirty++; set("/flowmeter/tc/web_notify/enable", $enable_web_notify); }
	if (query("/flowmeter/tc/web_notify/down_threshold")!=$down_threshold)
	{ $dirty++; set("/flowmeter/tc/web_notify/down_threshold", $down_threshold); }
	if (query("/flowmeter/tc/web_notify/up_threshold")!=$up_threshold)
	{ $dirty++; set("/flowmeter/tc/web_notify/up_threshold", $up_threshold); }

	if (query("/flowmeter/tc/email_notify/enable")!=$enable_email_notify)
	{ $dirty++; set("/flowmeter/tc/email_notify/enable", $enable_email_notify); }
	if (query("/flowmeter/tc/email_notify/period")!=$email_period)
	{ $dirty++; set("/flowmeter/tc/email_notify/period", $email_period); }
	if (query("/flowmeter/tc/email_notify/unit")!=$time_unit)
	{ $dirty++; set("/flowmeter/tc/email_notify/unit", $time_unit); }
	
	if($dirty > 0)   {$SUBMIT_STR="submit FLOWMETER";}
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
$router=query("/runtime/router/enable");
// get the variable value from rgdb.
$cfg_date   = query("/runtime/time/date");
$cfg_date2	= query("/runtime/time/date2");
$cfg_time	= query("/runtime/time/time");

$cfg_enable_ium = query("/flowmeter/enable");
$cfg_year	= query("/flowmeter/starttime/year");
$cfg_month	= query("/flowmeter/starttime/month");
$cfg_day	= query("/flowmeter/starttime/day");
$cfg_hour	= query("/flowmeter/starttime/hour");
$cfg_enable_tc  = query("/flowmeter/tc/enable");
$cfg_en_downlimit = query("/flowmeter/tc/downlimit/enable");
$cfg_downlimit	= query("/flowmeter/tc/downlimit/threshold");
$cfg_en_uplimit = query("/flowmeter/tc/uplimit/enable");
$cfg_uplimit	= query("/flowmeter/tc/uplimit/threshold");
$cfg_discon_wan	= query("/flowmeter/tc/discon_wan");
$cfg_web_nofity	= query("/flowmeter/tc/web_notify/enable");
$cfg_down_thres = query("/flowmeter/tc/web_notify/down_threshold");
$cfg_up_thres	= query("/flowmeter/tc/web_notify/up_threshold");
$cfg_email_notify = query("/flowmeter/tc/email_notify/enable");
$cfg_email_period = query("/flowmeter/tc/email_notify/period");
$cfg_unit		= query("/flowmeter/tc/email_notify/unit");

if($cfg_year!="")
{
	if($cfg_month<10) { $start_month = "0".$cfg_month; }
	else		      { $start_month = $cfg_month; }
	if($cfg_day<10)	{ $start_day = "0".$cfg_day; }
	else			{ $start_day = $cfg_day; }
	if($cfg_hour<10) { $start_hour = "0".$cfg_hour; }
	else			 { $start_hour = $cfg_hour; }
	$starttime	= $cfg_year.":".$start_month.":".$start_day." , ".$start_hour.":00:00";
}
// get runtime node
$day_time = query("/runtime/flowmeter/time/today");
if($day_time == ""){ $day_time = 0; }
$day_upload = query("/runtime/flowmeter/up/today");
if($day_upload == "") { $day_upload = 0; }
$day_download = query("/runtime/flowmeter/down/today");
if($day_download == "") { $day_download = 0; }
$day_total = query("/runtime/flowmeter/total/today");
if($day_total == "") { $day_total = 0; }

$yest_time = query("/runtime/flowmeter/time/yesterday");
if($yest_time == ""){ $yest_time = 0; }
$yest_upload = query("/runtime/flowmeter/up/yesterday");
if($yest_upload == "") { $yest_upload = 0; }
$yest_download = query("/runtime/flowmeter/down/yesterday");
if($yest_download == "") { $yest_download = 0; }
$yest_total = query("/runtime/flowmeter/total/yesterday");
if($yest_total == "") { $yest_total = 0; }

$week_time = query("/runtime/flowmeter/time/week");
if($week_time == ""){ $week_time = 0; }
$week_upload = query("/runtime/flowmeter/up/week");
if($week_upload == "") { $week_upload = 0; }
$week_download = query("/runtime/flowmeter/down/week");
if($week_download == "") { $week_download = 0; }
$week_total = query("/runtime/flowmeter/total/week");
if($week_total == "") { $week_total = 0; }

$thismon_time = query("/runtime/flowmeter/time/thismonth");
if($thismon_time == ""){ $thismon_time = 0; }
$thismon_upload = query("/runtime/flowmeter/up/thismonth");
if($thismon_upload == "") { $thismon_upload = 0; }
$thismon_download = query("/runtime/flowmeter/down/thismonth");
if($thismon_download == "") { $thismon_download = 0; }
$thismon_total = query("/runtime/flowmeter/total/thismonth");
if($thismon_total == "") { $thismon_total = 0; }

$lastmon_time = query("/runtime/flowmeter/time/lastmonth");
if($lastmon_time == ""){ $lastmon_time = 0; }
$lastmon_upload = query("/runtime/flowmeter/up/lastmonth");
if($lastmon_upload == "") { $lastmon_upload = 0; }
$lastmon_download = query("/runtime/flowmeter/down/lastmonth");
if($lastmon_download == "") { $lastmon_download = 0; }
$lastmon_total = query("/runtime/flowmeter/total/lastmonth");
if($lastmon_total == "") { $lastmon_total = 0; }
?>

<script>
/* page init functoin */
function init()
{
	onClick_ium();
	set_time();
	onClick_tc();
	onClick_downlimit();
	onClick_uplimit();
	onClick_webnotify();
	onClick_emailnotify();
}
/* parameter checking */
function check()
{
	var f = get_obj("frm");
	var d = new Date();
	var startYear = d.getFullYear();
	var startMonth = f.mon.value;
	var startDay = f.day.value;
	var startHour = f.hour.value;
	var startDate = startMonth+"/"+startDay+"/"+startYear+" "+startHour+":00:00";
	var currDate = "<?=$cfg_date?> <?=$cfg_time?>";
	
	// check whether start date is earlier than the current time got from NTP
if(f.ium_enable.checked)
{
	if(startYear != "<?=$cfg_year?>" /*|| startMonth != "<?=$cfg_month?>" */
	   || startDay != "<?=$cfg_day?>" || startHour != "<?=$cfg_hour?>")
	{
		if(Date.parse(startDate)-Date.parse(currDate)<0)
		{
			alert("<?=$a_start_time_bigger_current_time?>");
			return false;
		}
	}
	if(f.en_downlimit[1].checked)
	{
		if(!is_digit(f.down_limit.value))
		{
			alert("<?=$a_must_be_integer?>");
			field_focus(f.down_limit, "**");
			return false;
		}
	}
	if(f.en_uplimit[1].checked)
	{
		if(!is_digit(f.up_limit.value))
		{
			alert("<?=$a_must_be_integer?>");
			field_focus(f.up_limit, "**");
			return false;
		}
	}
	if(f.enable_web_notify.checked)
	{
		if(!is_digit(f.down_threshold.value))
		{
			alert("<?=$a_must_be_integer?>");
			field_focus(f.down_threshold, "**");
			return false;
		}
		if(!is_digit(f.up_threshold.value))
		{
			alert("<?=$a_must_be_integer?>");
			field_focus(f.up_threshold, "**");
			return false;
		}
	}
	if(f.enable_email_notify.checked)
	{
		if(!is_digit(f.email_period.value))
		{
			alert("<?=$a_must_be_integer?>");
			field_focus(f.email_period, "**");
			return false;
		}
		if(f.time_unit[0].checked)
		{
			if(!is_in_range(f.email_period.value, 1, 23))
			{
				alert("<?=$a_period_should_be_1_to_23?>");
				field_focus(f.email_period, "**");
				return false;
			}
		}
		else if(f.time_unit[1].checked)
		{
			if(!is_in_range(f.email_period.value, 1, 31))
			{
				alert("<?=$a_period_should_be_1_to_31?>");
				field_focus(f.email_period, "**");
				return false;
			}
		}
		else if(f.time_unit[2].checked)
		{
			if(!is_in_range(f.email_period.value, 1, 12))
			{
				alert("<?=$a_period_should_be_1_to_12?>");
				field_focus(f.email_period, "**");
				return false;
			}
		}
	}

}

	f.year.value = startYear;
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

function onClick_ium()
{
	//alert("click ium");
	if(get_obj("ium_enable").checked)
	{
		get_obj("show_ium").style.display = "";
		get_obj("show_table").style.display = "";
		get_obj("show_tcnotify").style.display = "";
	}	
	else
	{
		get_obj("show_ium").style.display = "none";
		get_obj("show_table").style.display = "none";
		get_obj("show_tcnotify").style.display = "none";
	}
}

function onClick_tc()
{
	if(get_obj("tc_enable").checked == true)
	{
		get_obj("show_tc").style.display = "";
	}	
	else
	{
		get_obj("show_tc").style.display = "none";
	}
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

<?
	if($cfg_year!="")
	{
		
		echo "get_obj(\"mon\").selectedIndex = ".$cfg_month." - 1;\n";
		echo "get_obj(\"day\").selectedIndex = ".$cfg_day." - 1;\n";
		echo "get_obj(\"hour\").selectedIndex = ".$cfg_hour.";\n";
	}
	else
	{
		echo "get_obj(\"mon\").selectedIndex = date.getMonth();\n";
		echo "get_obj(\"day\").selectedIndex = date.getDate() - 1;\n";
		echo "get_obj(\"hour\").selectedIndex = date.getHours();\n";
	}
?>
}

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
	var d = new Date();
	var year = d.getFullYear();
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

function onClick_downlimit()
{
	var f = get_obj("frm");

	f.down_limit.disabled = f.en_downlimit[0].checked? true : false;
}

function onClick_uplimit()
{
	var f = get_obj("frm");

	f.up_limit.disabled = f.en_uplimit[0].checked? true : false;
}

function onClick_webnotify()
{
	var f = get_obj("frm");

	if(get_obj("enable_web_notify").checked == true)
	{
		f.down_threshold.disabled = false;
		f.up_threshold.disabled = false;
	}	
	else
	{
		f.down_threshold.disabled = true;
		f.up_threshold.disabled = true;
	}
}

function onClick_emailnotify()
{
	var f = get_obj("frm");

	if(get_obj("enable_email_notify").checked == true)
	{
		f.email_period.disabled = false;
	}	
	else
	{
		f.email_period.disabled = true;
	}
}

function showTime(time)
{
	var str=new String("");
	var t=parseInt(time, [10]);
	var sec=0,min=0,hr=0,day=0;
	sec=t % 60;  //sec
	min=parseInt(t/60, [10]) % 60; //min
	hr=parseInt(t/(60*60), [10]) % 24; //hr
	day=parseInt(t/(60*60*24), [10]); //day

	if(day>=0 || hr>=0 || min>=0 || sec >=0)
		str=(day >0? day+" <?=$m_days?>, ":"0 <?=$m_days?>, ")+(hr >0? ( hr > 9? hr+":":"0"+hr+":") : "00:")+(min >0? ( min > 9?min+":":"0"+min+":") : "00:")+(sec >0? (sec > 9?sec:"0"+sec):"00");
	return str;
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
			<h2><?=$m_context_title_ium?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="170"><?=$m_enable_ium?> :</td>
				<td class="l_tb">&nbsp;
					<input name="ium_enable" type=checkbox id="ium_enable" value="1" onclick="onClick_ium();" 
					<?if($cfg_enable_ium=="1") {echo "checked";}?>
					>
				</td>
			</tr>
			</table>
			<table name="show_ium" id="show_ium" cellpadding="1" cellspacing="1" border="0" width="525" style="display:none">
			<tr>
				<td class="r_tb" width="170"><?=$m_counting_from?> :</td>
				<td class="l_tb">&nbsp;
					<input type="hidden" name="year" id="year" value="">
					<?=$m_month?>
					<font face="Arial, Helvetica, sans-serif" size=2>
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
					</font>
					&nbsp;<?=$m_day?>
					<font face="Arial, Helvetica, sans-serif" size=2>
					<select size=1 id="day" name="day" style="WIDTH: 50px"></select>
					</font>
					&nbsp;<?=$m_hour?>
					<font face="Arial, Helvetica, sans-serif" size=2>
					<select size=1 id="hour" name="hour" style="WIDTH: 50px"><?
						$i=0;
						while ($i<24) { echo "<option value=".$i.">".$i."</option>\n"; $i++; }
					?></select>
					</font>
				</td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_start_date?> :</td>
				<td class="l_tb">&nbsp;
				<?=$starttime?>
				</td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_current_date?> :</td>
				<td class="l_tb">&nbsp;
				<?=$cfg_date2?>&nbsp;,&nbsp;<?=$cfg_time?>
				</td>
			</tr>
			</table>
			<br>
			<center>
			<table name="show_table" id="show_table" borderColor=#ffffff cellSpacing=1 cellPadding=2 width=450 bgColor=#dfdfdf border=1
			 style="display:none">
			<tr id="box_header">
				<td width=135 class=bc_tb><?=$m_period?></td>
				<td width=135 class=bc_tb><?=$m_conn_time?></td>
				<td width=60 class=bc_tb><?=$m_upload?></td>
				<td width=60 class=bc_tb><?=$m_download?></td>
				<td width=60 class=bc_tb><?=$m_total?></td>

			</tr>
			<tr>
				<td class=c_tb><?=$m_today?></td>
				<td class=c_tb><script>document.write(showTime("<?=$day_time?>"));</script></td>
				<td class=c_tb><?=$day_upload?></td>
				<td class=c_tb><?=$day_download?></td>
				<td class=c_tb><?=$day_total?></td>
			</tr>
			<tr>
				<td class=c_tb><?=$m_yesterday?></td>
				<td class=c_tb><script>document.write(showTime("<?=$yest_time?>"));</script></td>
				<td class=c_tb><?=$yest_upload?></td>
				<td class=c_tb><?=$yest_download?></td>
				<td class=c_tb><?=$yest_total?></td>
			</tr>
			<tr>
				<td class=c_tb><?=$m_thisweek?></td>
				<td class=c_tb><script>document.write(showTime("<?=$week_time?>"));</script></td>
				<td class=c_tb><?=$week_upload?></td>
				<td class=c_tb><?=$week_download?></td>
				<td class=c_tb><?=$week_total?></td>
			</tr>
			<tr>
				<td class=c_tb><?=$m_thismonth?></td>
				<td class=c_tb><script>document.write(showTime("<?=$thismon_time?>"));</script></td>
				<td class=c_tb><?=$thismon_upload?></td>
				<td class=c_tb><?=$thismon_download?></td>
				<td class=c_tb><?=$thismon_total?></td>
			</tr>
			<tr>
				<td class=c_tb><?=$m_lastmonth?></td>
				<td class=c_tb><script>document.write(showTime("<?=$lastmon_time?>"));</script></td>
				<td class=c_tb><?=$lastmon_upload?></td>
				<td class=c_tb><?=$lastmon_download?></td>
				<td class=c_tb><?=$lastmon_total?></td>
			</tr>
			</table>
			</center>
		</div>

		<div name="show_tcnotify" id="show_tcnotify" class="box" style="display:none">
			<h2><?=$m_context_title_tc?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="194"><?=$m_enable_tc?> :</td>
				<td class="l_tb">&nbsp;
					<input name="tc_enable" type=checkbox id="tc_enable" value="1" onclick="onClick_tc();"
					<?if($cfg_enable_tc=="1") { echo "checked"; }?>>
				</td>
			</tr>
			</table>
			<div name="show_tc" id="show_tc" style="display:none">
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="153"><?=$m_download_tc?> :</td>
				<td class="l_tb">&nbsp;
					<input type=radio name=en_downlimit id=en_downlimit value=0 onclick="onClick_downlimit();" 
					<?if($cfg_en_downlimit!="1") { echo "checked"; }?>><?=$m_no_limit?>
					<input type=radio name=en_downlimit id=en_downlimit value=1 onclick="onClick_downlimit();"
					<?if($cfg_en_downlimit=="1") { echo "checked"; }?>><?=$m_limit?>,
					<input name="down_limit" type="text" id="down_limit" size="10" maxlength="12" value="<?=$cfg_downlimit?>"><?=$m_mbytes?>
				</td>
			</tr>
			<tr>
				<td class="r_tb" width="153"><?=$m_upload_tc?> :</td>
				<td class="l_tb">&nbsp;
					<input type=radio name=en_uplimit id=en_uplimit value=0 onclick="onClick_uplimit();" 
					<?if($cfg_en_uplimit!="1") { echo "checked"; }?>><?=$m_no_limit?>
					<input type=radio name=en_uplimit id=en_uplimit value=1 onclick="onClick_uplimit();"
					<?if($cfg_en_uplimit=="1") { echo "checked"; }?>><?=$m_limit?>,
					<input name="up_limit" type="text" id="up_limit" size="10" maxlength="12" value="<?=$cfg_uplimit?>"><?=$m_mbytes?>
				</td>
			</tr>
			</table>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="203"><?=$m_discon_internet?> :</td>
				<td class="l_tb">&nbsp;
					<input name="enable_disconn_wan" type=checkbox id="enable_disconn_wan" value="1" 
					<?if($cfg_discon_wan=="1") { echo "checked"; }?>>
				</td>
			</tr>
			</table>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="143"><?=$m_web_notify?> :</td>
				<td class="l_tb">&nbsp;
					<input name="enable_web_notify" type=checkbox id="enable_web_notify" value="1" onclick="onClick_webnotify();"
					<?if($cfg_web_nofity=="1") { echo "checked"; }?>>&nbsp;,
				</td>
			</tr>
			</table>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="170"><?=$m_download_thres?> :</td>
				<td class="l_tb">&nbsp;
					<input name="down_threshold" type="text" id="down_threshold" size="10" maxlength="12" 
					value="<?=$cfg_down_thres?>"><?=$m_mbytes?>
				</td>
			</tr>
			<tr>
				<td class="r_tb" width="170"><?=$m_upload_thres?> :</td>
				<td class="l_tb">&nbsp;
					<input name="up_threshold" type="text" id="up_threshold" size="10" maxlength="12" 
					value="<?=$cfg_up_thres?>"><?=$m_mbytes?>
				</td>
			</tr>
			</table>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="145"><?=$m_email_notify?> :</td>
				<td class="l_tb">&nbsp;
					<input name="enable_email_notify" type=checkbox id="enable_email_notify" value="1" onclick="onClick_emailnotify();"
					<?if($cfg_email_notify=="1") { echo "checked"; }?> >&nbsp;,
				<?=$m_by?>&nbsp;<input name="email_period" type="text" id="email_period" size="2" maxlength="2" 
					value="<?=$cfg_email_period?>">
					<input type=radio name=time_unit id=time_unit value=1 <?if($cfg_unit=="1") { echo "checked"; }?>><?=$m_hours?>
					<input type=radio name=time_unit id=time_unit value=2 <?if($cfg_unit=="2") { echo "checked"; }?>><?=$m_days?>
					<input type=radio name=time_unit id=time_unit value=3 <?if($cfg_unit=="3") { echo "checked"; }?>><?=$m_months?>
				</td>
			</tr>
			</table>
			<div align=right><a href="/tools_log_setting.php"><?=$m_email_setting?></a></div>
			</div>
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
