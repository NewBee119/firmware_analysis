<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_sch";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
/* --------------------------------------------------------------------------- */
$MAX_RULES		=query("/sys/schedule/max_rules");
if($MAX_RULES==""){$MAX_RULES=10;}
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($router=="1")
{
	if($del_id!="")
	{
		require("/www/model/__admin_check.php");
		// Only the schedule is not used by other application, then we can delete it.
		del("/sys/schedule/entry:".$del_id);
		$SUBMIT_STR=";";
		$NEXT_PAGE=$MY_NAME;
		require($G_SAVING_URL);
	}
	else if ($ACTION_POST!="")
	{
		require("/www/model/__admin_check.php");

		echo "<!--\n";
		echo "sch_name=".$sch_name."\n";
		echo "save_id=".$save_id."\n";
		echo "unique_id=".$unique_id."\n";
		echo "week_or_days=".$week_or_days."\n";
		echo "day0=".$day0."\n";
		echo "day1=".$day1."\n";
		echo "day2=".$day2."\n";
		echo "day3=".$day3."\n";
		echo "day4=".$day4."\n";
		echo "day5=".$day5."\n";
		echo "day6=".$day6."\n";
		echo "sch_time_start=".$sch_time_start."\n";
		echo "sch_time_end=".$sch_time_end."\n";
		echo "-->\n";

		if ($week_or_days==1)
		{
			$day0=1; $day1=1; $day2=1; $day3=1; $day4=1; $day5=1; $day6=1;
		}
		else
		{
			if ($day0!="1") { $day0=0; }
			if ($day1!="1") { $day1=0; }
			if ($day2!="1") { $day2=0; }
			if ($day3!="1") { $day3=0; }
			if ($day4!="1") { $day4=0; }
			if ($day5!="1") { $day5=0; }
			if ($day6!="1") { $day6=0; }
		}

		if($unique_id=="")
		{
			$i=1;
			$uid=query("/sys/schedule/entry:".$save_id."/id");

			/* IP filter */
			$id_used=0;
			for ("/security/ipfilter/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			if ($id_used>0) { $SUBMIT_STR=$SUBMIT_STR."; submit RG_IP_FILTER"; }
			/* Firewall */
			$id_used=0;
			for ("/security/firewall/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			if ($id_used>0) { $SUBMIT_STR=$SUBMIT_STR."; submit RG_FIREWALL"; }
			/* Domain blocking / URL blocking */
			$id_used=0;
			for ("/security/domainblocking/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			for ("/security/urlblocking/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			if ($id_used>0) { $SUBMIT_STR=$SUBMIT_STR."; submit RG_BLOCKING"; }
			/* MAC filter */
			$id_used=0;
			for ("/security/macfilter/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			if ($id_used>0) { $SUBMIT_STR=$SUBMIT_STR."; submit RG_MAC_FILTER"; }
			/* PORT trigger */
			$id_used=0;
			for ("/nat/porttrigger/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			if ($id_used>0) { $SUBMIT_STR=$SUBMIT_STR."; submit RG_APP"; }
			/* Virtual Server */
			$id_used=0;
			for ("/nat/vrtsrv/entry")
			{	if ($id_used==0 && query("enable")==1 && query("schedule/id")==$uid) {$id_used=1;}	}
			if ($id_used>0) { $SUBMIT_STR=$SUBMIT_STR."; submit RG_VSVR"; }
			/* DMZ */
			if (query("/nat/dmzsrv/enable")==1 && query("/nat/dmzsrv/schedule/id")==$uid)
			{
				$SUBMIT_STR=$SUBMIT_STR."; submit RG_DMZ";
			}
			if (query("/gzone/enable")==1 && query("/gzone/schedule/id")==$uid)
			{
				$SUBMIT_STR=$SUBMIT_STR."; submit GZONE_ENABLE";
			}
			$mode=query("/wan/rg/inf:1/mode");
			$wan_dirty=0;
			if ($mode=="3" && query("/wan/rg/inf:1/pppoe/schedule/id")==$uid)  {$wan_dirty++;}
			if ($mode=="4" && query("/wan/rg/inf:1/pptp/schedule/id")==$uid)   {$wan_dirty++;}
			if ($mode=="5" && query("/wan/rg/inf:1/l2tp/schedule/id")==$uid)   {$wan_dirty++;}
			if($wan_dirty > 0)	{$SUBMIT_STR=$SUBMIT_STR."; submit WAN";}
		}

		$db_dirty=0;
		if($unique_id		!=""){set("/sys/schedule/entry:".$save_id."/id",$unique_id);$db_dirty++;}
		anchor("/sys/schedule/entry:".$save_id);
		if(query("description")	!=$sch_name)		{set("description",	$sch_name);			$db_dirty++;}
		if(query("sun")			!=$day0)			{set("sun",			$day0);				$db_dirty++;}
		if(query("mon")			!=$day1)			{set("mon",			$day1);				$db_dirty++;}
		if(query("tue")			!=$day2)			{set("tue",			$day2);				$db_dirty++;}
		if(query("wed")			!=$day3)			{set("wed",			$day3);				$db_dirty++;}
		if(query("thu")			!=$day4)			{set("thu",			$day4);				$db_dirty++;}
		if(query("fri")			!=$day5)			{set("fri",			$day5);				$db_dirty++;}
		if(query("sat")			!=$day6)			{set("sat",			$day6);				$db_dirty++;}
		if(query("starttime")	!=$sch_time_start)	{set("starttime",	$sch_time_start);	$db_dirty++;}
		if(query("endtime")		!=$sch_time_end)	{set("endtime",		$sch_time_end);		$db_dirty++;}

		if($db_dirty>0 && $SUBMIT_STR==""){$SUBMIT_STR=";";}
		$NEXT_PAGE=$MY_NAME;
		if($db_dirty>0)	{require($G_SAVING_URL);}
		else			{require($G_NO_CHANGED_URL);}
	}
}
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
echo "<script>\n";
require("/www/comm/__js_select.php");
echo "</script>\n";
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.

/* --------------------------------------------------------------------------- */
?>

<script>
var used_sch=[''<?
for("/security/ipfilter/entry")			{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
for("/security/firewall/entry")			{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
for("/security/domainblocking/entry")	{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
for("/security/urlblocking/entry")		{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
for("/security/macfilter/entry")		{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
for("/nat/porttrigger/entry")			{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
for("/nat/vrtsrv/entry")				{	$uid=query("schedule/id"); if($uid!=""){echo ",'".$uid."'";}	} echo "\n";
$uid = query("/nat/dmzsrv/schedule/id"); if ($uid!="") { echo ",'".$uid."'"; } echo "\n";
$uid = query("/wan/rg/inf:1/pptp/schedule/id"); if ($uid!="") { echo ",'".$uid."'"; } echo "\n";
$uid = query("/wan/rg/inf:1/l2tp/schedule/id"); if ($uid!="") { echo ",'".$uid."'"; } echo "\n";
$uid = query("/wan/rg/inf:1/pppoe/schedule/id"); if ($uid!="") { echo ",'".$uid."'"; } 
?>];
var sch_list=[['index','id','description','Sun','Mon','Tue','Wed','Thu','Fri','Sat','starttime','endtime']<?
$sch_num=0;
for("/sys/schedule/entry")
{
	$sch_num++;
	echo ",\n";
	echo "\t['".$@;
	echo "','".	query("id");
	echo "','". get("j","description");
	echo "','".	query("sun");
	echo "','".	query("mon");
	echo "','".	query("tue");
	echo "','".	query("wed");
	echo "','".	query("thu");
	echo "','".	query("fri");
	echo "','".	query("sat");
	echo "','".	query("starttime");
	echo "','".	query("endtime");
	echo "']";
}
?>];
/* page init functoin */
function init()
{
	var f=get_obj("frm");
<?
if($edit_id=="")
{
	$save_id=1;
	$unique_id=0;
	for("/sys/schedule/entry")
	{
		$save_id++;
		if(query("id")>$unique_id){$unique_id=query("id");}
	}
	$unique_id++;
	echo "f.week_or_days[1].checked=true;\n";
}
else
{
	$save_id=$edit_id;
	anchor("/sys/schedule/entry:".$edit_id);
	echo "f.sch_name.value=\"";	get("j","description"); echo "\";\n";

	$days = 0;
	if (query("sun")==1) { echo "f.day0.checked=true;\n"; $days++; }
	if (query("mon")==1) { echo "f.day1.checked=true;\n"; $days++; }
	if (query("tue")==1) { echo "f.day2.checked=true;\n"; $days++; }
	if (query("wed")==1) { echo "f.day3.checked=true;\n"; $days++; }
	if (query("thu")==1) { echo "f.day4.checked=true;\n"; $days++; }
	if (query("fri")==1) { echo "f.day5.checked=true;\n"; $days++; }
	if (query("sat")==1) { echo "f.day6.checked=true;\n"; $days++; }

	if($days==7)
	{
		echo "f.week_or_days[0].checked=true;\n";
		echo "display_fields('week');\n";
	}
	else
	{
		echo "f.week_or_days[1].checked=true;\n";
		echo "display_time_fields();\n";
	}

	$begintime=query("starttime");
	$endtime=query("endtime");
	if($begintime=="00:00" && $endtime=="23:59")
	{
		echo "f.all_day.checked=true;\n";
		echo "display_fields('time');\n";
	}
	if($begintime!="")
	{
		if($endtime==""){$endtime="00:00";}
		echo "fill_time_fields('".$begintime."','".$endtime."');\n";
	}
}
if($router!="1"){echo "fields_disabled(f,true);\n";}
?>
}
function fill_time_fields(begintime,endtime)
{
	var f=get_obj("frm");
	var tmp_time, hr, am_pm;
	tmp_time=begintime.split(":");
	hr=decstr2int(tmp_time[0]);
	if(hr>=12)	{am_pm="pm";	hr-=12;	}
	else		{am_pm="am";			}
	select_index(f.s_am_pm, am_pm);
	select_index(f.s_hr, hr);
	f.s_min.value=(tmp_time[1]==""?"00":tmp_time[1]);

	tmp_time=endtime.split(":");
	hr=decstr2int(tmp_time[0]);
	if(hr>=12)	{am_pm="pm";	hr-=12;	}
	else		{am_pm="am";			}
	select_index(f.e_am_pm, am_pm);
	select_index(f.e_hr, hr);
	f.e_min.value=(tmp_time[1]==""?"00":tmp_time[1]);
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	var days=0;

	<?
		/* max rules check */
		if($MAX_RULES < $save_id){
			echo "alert('".$a_outside_max_rules."');";
			echo "return false";
		}

	?>

	if(is_blank(f.sch_name.value)==true)
	{
		alert("<?=$a_invalid_sch_name?>");
		f.sch_name.focus();
		return false;
	}

	if (f.week_or_days[1].checked)
	{
		if (f.day0.checked) days++;
		if (f.day1.checked) days++;
		if (f.day2.checked) days++;
		if (f.day3.checked) days++;
		if (f.day4.checked) days++;
		if (f.day5.checked) days++;
		if (f.day6.checked) days++;

		if (days == 0)
		{
			alert("<?=$a_invalid_days?>");
			return false;
		}
	}
	else
	{
		days=7;
	}

	if(f.all_day.checked==false)
	{
		var s_pm_min, e_pm_min, s_time, e_time, s_def, e_def;
		if(f.s_am_pm.value=="pm"){s_pm_min=720;s_def="12";}else{s_pm_min=0;s_def="0";}
		if(f.e_am_pm.value=="pm"){e_pm_min=720;e_def="12";}else{e_pm_min=0;e_def="0";}

		if(!is_in_range(f.s_min.value,0,59))		{alert("<?=$a_invalid_time?>"); field_select(f.s_min,"00");	return false;}
		if(!is_in_range(f.e_min.value,0,59))		{alert("<?=$a_invalid_time?>"); field_select(f.e_min,"00");	return false;}
		
		s_time = decstr2int(f.s_hr.value)*60 + decstr2int(f.s_min.value) + s_pm_min;
		e_time = decstr2int(f.e_hr.value)*60 + decstr2int(f.e_min.value) + e_pm_min;
		if(s_time>=e_time){alert("<?=$a_invalid_start_time?>");	f.s_hr.focus();	return false;}
		
		f.sch_time_start.value = (decstr2int(f.s_hr.value)+decstr2int(s_def));
		if(f.sch_time_start.value<10)	f.sch_time_start.value ="0"+f.sch_time_start.value;
		if(f.s_min.value.length==1)	f.s_min.value="0"+f.s_min.value;
		
		f.sch_time_end.value = (decstr2int(f.e_hr.value)+decstr2int(e_def));
		if(f.sch_time_end.value<10)	f.sch_time_end.value ="0"+f.sch_time_end.value;
		if(f.e_min.value.length==1)	f.e_min.value="0"+f.e_min.value;

		f.sch_time_start.value		= f.sch_time_start.value+":"+f.s_min.value;
		f.sch_time_end.value		= f.sch_time_end.value+":"+f.e_min.value;
	}
	else
	{
		f.sch_time_start.value="00:00";
		f.sch_time_end.value="23:59";
	}
	if(days==7 && f.sch_time_start.value=="00:00" && f.sch_time_end.value=="23:59")
	{
		alert("<?=$a_invalid_schedule?>");
		return false;
	}
	
	for(i=1; i <= <?=$sch_num?>; i++)
	{
		if("<?=$edit_id?>"!=sch_list[i][0])
		{
			if(f.sch_name.value==sch_list[i][2])
			{
				alert("<?=$a_same_name_record?>");
				f.sch_name.select();
				return false;
			}
		}
	}
	f.save_id.value="<?=$save_id?>";
	f.unique_id.value="<?=$unique_id?>";
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function display_time_fields()
{
	var f=get_obj("frm");
	if(f.all_day.checked)	dis=true;
	else					dis=false;
	f.s_hr.disabled=f.s_min.disabled=f.s_am_pm.disabled=dis;
	f.e_hr.disabled=f.e_min.disabled=f.e_am_pm.disabled=dis;

}
function display_fields(from)
{
	var f=get_obj("frm");
	var dis;
	if(from=="week")
	{
		if(f.week_or_days[0].checked)	{dis=true;		f.all_day.checked=false;}
		else							{dis=false;}
		f.all_day.disabled = dis;
		f.day0.disabled = f.day1.disabled = f.day2.disabled = f.day3.disabled = dis;
		f.day4.disabled = f.day5.disabled = f.day6.disabled = dis;
		f.day0.checked = f.day1.checked = f.day2.checked = f.day3.checked = dis;
		f.day4.checked = f.day5.checked = f.day6.checked = dis;
	}
	else if(from=="time")
	{
		if(f.all_day.checked)		{f.week_or_days.disabled=true;	f.week_or_days.checked=false;}
		else						{f.week_or_days.disabled=false;}
	}
	display_time_fields();
}
function print_del(id)
{
	var str="";
	var used=false;
	for(i=1;i<used_sch.length; i++)
	{
		if(sch_list[id][1]==used_sch[i])	used=true;
	}
	if(used)	str="<img src='/pic/delete_g.jpg'>";
	else		str="<a href='javascript:del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";
	document.write(str);
}
function del_confirm(id)
{
	if(confirm("<?=$a_del_confirm?>")==false) return;
	self.location.href="<?=$MY_NAME?>.php?del_id="+id;
}
</script>
<body onLoad="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onSubmit="return check();";>

<input type="hidden" name="ACTION_POST"		value="1">
<input type="hidden" name="sch_time_start"	value="">
<input type="hidden" name="sch_time_end"	value="">
<input type="hidden" name="save_id"			value="">
<input type="hidden" name="unique_id"		value="">

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
		<?$m_colon="&nbsp;:&nbsp;";?>
		<div class="box">
			<h2><?=$MAX_RULES?> - <?=$m_add_sch_title?></h2>
			<table>
			<tr>
				<td width=30% class=br_tb><?=$m_name?><?=$m_colon?></td>
				<td><input type="text" name="sch_name" size="20" maxlength="16"></td>
			</tr>
			<tr>
				<td class=br_tb><?=$m_days?><?=$m_colon?></td>
				<td>
					<input type="radio" name="week_or_days" value="1" onClick="display_fields('week')"><?=$m_all_week?>&nbsp;
					<input type="radio" name="week_or_days" value="0" onClick="display_fields('week')"><?=$m_sel_days?>&nbsp;
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="day0" value="1"><?=$m_sun?>&nbsp;
					<input type="checkbox" name="day1" value="1"><?=$m_mon?>&nbsp;
					<input type="checkbox" name="day2" value="1"><?=$m_tue?>&nbsp;
					<input type="checkbox" name="day3" value="1"><?=$m_wed?>&nbsp;
					<input type="checkbox" name="day4" value="1"><?=$m_thu?>&nbsp;
					<input type="checkbox" name="day5" value="1"><?=$m_fri?>&nbsp;
					<input type="checkbox" name="day6" value="1"><?=$m_sat?>&nbsp;
				</td>
			</tr>
			<tr>
				<td class=br_tb><?=$m_all_day?><?=$m_colon?></td>
				<td><input type="checkbox" name="all_day" onClick="display_fields('time')"></td>
			</tr>
			<tr>
				<td class=br_tb><?=$m_s_time?><?=$m_colon?></td>
				<td>
				<script>print_select("s_hr", "s_hr", 0, 11, 1);</script><?=$m_colon?>
				<input type="text" name="s_min" size="2" maxlength="2" value="00">
				<script>print_am("s_am_pm");</script>
				<?=$m_time_dsc?>
				</td>
			</tr>
			<tr>
				<td class=br_tb><?=$m_e_time?><?=$m_colon?></td>
				<td>
				<script>print_select("e_hr", "e_hr", 0, 11, 1);</script><?=$m_colon?>
				<input type="text" name="e_min" size="2" maxlength="2" value="00">
				<script>print_am("e_am_pm");</script>
				<?=$m_time_dsc?>
				</td>
			</tr>
			</table>
		</div>

		<div class="box">
			<h2><?=$m_lst_sch_title?></h2>
			<table width=96%>
			<tr>
				<td class=bc_tb width=30%><?=$m_name?></td>
				<td class=bc_tb width=40%><?=$m_days?></td>
				<td class=bc_tb width=20%><?=$m_time_frame?></td>
				<td class=bc_tb width=10%></td>
			</tr>
			<?
			for("/sys/schedule/entry")
			{
				$td_h="<td class=c_tb>";
				$td_rh="<td class=r_tb>";
				$td_t="</td>";

				/* Highlight this entry */
				if($edit_id==$@){echo "<tr bgcolor=yellow>\n";}
				else {echo "<tr>\n";}

				/* Description */
				echo $td_h.get("h","description").$td_t."\n";

				/* Days */
				$comma="";
				$q_day="";
				$days=0;
				if (query("sun")==1)	{ $q_day=$q_day.$comma.$m_sun; $comma=","; $days++; }
				if (query("mon")==1)	{ $q_day=$q_day.$comma.$m_mon; $comma=","; $days++; }
				if (query("tue")==1)	{ $q_day=$q_day.$comma.$m_tue; $comma=","; $days++; }
				if (query("wed")==1)	{ $q_day=$q_day.$comma.$m_wed; $comma=","; $days++; }
				if (query("thu")==1)	{ $q_day=$q_day.$comma.$m_thu; $comma=","; $days++; }
				if (query("fri")==1)	{ $q_day=$q_day.$comma.$m_fri; $comma=","; $days++; }
				if (query("sat")==1)	{ $q_day=$q_day.$comma.$m_sat; $comma=","; $days++; }
				if ($days == 7)			{ $q_day=$m_all_week; }

				echo $td_h.$q_day.$td_t."\n";

				/* TIME */
				$stime	= query("starttime");
				$etime	= query("endtime");
				if($stime=="00:00" && $etime=="23:59")	{$q_time=$m_all_day;}
				else									{$q_time=$stime." ~ ".$etime;}
				echo $td_h.$q_time.$td_t."\n";

				if($router=="1")
				{
					echo $td_rh."<a href='".$MY_NAME.".php?edit_id=".$@."'><img src='/pic/edit.jpg' border=0></a>&nbsp;";
					echo "<script>print_del('".$@."');</script>".$td_t."\n";
				}
//				echo "<a href='".$MY_NAME.".php?del_id='".$@."'>".$m_del."</a>".$td_t."\n";
				echo "</tr>\n";
			}
			?>
			</table>
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
