<?
$uplimit = query("/flowmeter/tc/uplimit/threshold");

$cfg_year	= query("/flowmeter/starttime/year");
$cfg_month	= query("/flowmeter/starttime/month");
$cfg_day	= query("/flowmeter/starttime/day");
$cfg_hour	= query("/flowmeter/starttime/hour");
if($cfg_year!="")
{
	if($cfg_month<10) { $start_month = "0".$cfg_month; }
	else		      { $start_month = $cfg_month; }
	if($cfg_day<10)	{ $start_day = "0".$cfg_day; }
	else			{ $start_day = $cfg_day; }
	if($cfg_hour<10) { $start_hour = "0".$cfg_hour; }
	else			 { $start_hour = $cfg_hour; }
	$starttime	= "(".$cfg_year."/".$start_month."/".$start_day.", ".$start_hour.":00:00".")";
}

$m_html_title="BANDWIDTH EXCEEDED";
$m_context_title="Bandwidth Exceeded";
$m_context="The upload volume limit ".$uplimit." Mbytes has been reach.".$starttime;
?>
