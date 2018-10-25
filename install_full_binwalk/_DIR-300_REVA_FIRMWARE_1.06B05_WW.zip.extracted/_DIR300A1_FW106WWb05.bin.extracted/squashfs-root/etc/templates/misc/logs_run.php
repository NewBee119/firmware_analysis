<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$klog_pid="/var/run/klogd.pid";
$lanif=query("/runtime/layout/lanif");
$wanif=query("/runtime/wan/inf:1/interface");
$rumtime_mail_enabled=query("/runtime/func/logmail");
$mail_on_full=query("/sys/log/mailonfull");
$enable_mail_schedule=query("/sys/log/enablemailschedule");
if ($wanif=="") { $wanif=query("/runtime/layout/wanif"); }
$timeset=query("/runtime/timeset");

if ($klogd_only != 1)
{
	$syslog_pid="/var/run/syslogd.pid";

	echo "if [ -f ".$syslog_pid." ]; then\n";
	echo "	PID=`cat ".$syslog_pid."`\n";
	echo "	if [ $PID != 0 ]; then\n";
	echo "		kill $PID > /dev/console 2>&1\n";
	echo "	fi\n";
	echo "	rm -f ".$syslog_pid."\n";
	echo "fi\n";

	$smtps=query("/sys/log/mailserver");
	$email=query("/sys/log/email");
	$hostname=query("/sys/hostname");
	$username=query("/sys/log/username");
	$opts="";
	anchor("/security/log");
	if (query("systeminfo")==1)		{ $opts=$opts." -F sysact"; }
	if (query("debuginfo")==1)		{ $opts=$opts." -F debug"; }
	if (query("attackinfo")==1)		{ $opts=$opts." -F attack"; }
	if (query("droppacketinfo")==1)	{ $opts=$opts." -F drop"; }
	if (query("noticeinfo")==1)		{ $opts=$opts." -F notice"; }
if($rumtime_mail_enabled == 1)
{
		if ($smtps != "" && $email != "")
		{
			if($mail_on_full==1)//Enable mail on log full 
			{
				if ($username == "")	{$opts=$opts." -t /var/log/messages -m -a ".$email;}
				else					{$opts=$opts." -t /var/log/messages -m -A -a ".$email;}
			}
			else//Disable mail on full log
			{
				$opts=$opts." -t /var/log/messages";
			}
		}
		$logenbale=query("/sys/log/logserverenable");
		$logsrv=query("/sys/log/logserver");
		if ($logenbale == "1" && $logsrv != "")
		{ 
			$opts=$opts." -R \"".$logsrv.":514\""; }
			if ($opts != "")
			{
				echo "syslogd ".$opts." &\n";
				echo "echo $! > ".$syslog_pid."\n";
			}
}else{
	if ($smtps != "" && $email != "")
	{
		if ($username == "")	{$opts=$opts." -t /var/log/messages -m -a ".$email;}
		else					{$opts=$opts." -t /var/log/messages -m -A -a ".$email;}
	}
	$logenbale=query("/sys/log/logserverenable");
	$logsrv=query("/sys/log/logserver");
	if ($logenbale == "1" && $logsrv != "")
	{ $opts=$opts." -R \"".$logsrv.":514\""; }
	if ($opts != "")
	{
		echo "syslogd ".$opts." &\n";
		echo "echo $! > ".$syslog_pid."\n";
	}
}
}

echo "if [ -f ".$klog_pid." ]; then\n";
echo "	PID=`cat ".$klog_pid."`\n";
echo "	if [ $PID != 0 ]; then\n";
echo "		kill $PID > /dev/console 2>&1\n";
echo "	fi\n";
echo "	rm -f ".$klog_pid."\n";
echo "fi\n";
echo "klogd -l ".$lanif." -w ".$wanif." &\n";
echo "echo $! > ".$klog_pid."\n";
if($rumtime_mail_enabled == 1)//schedule
{	
	$log_schedule  = query("/sys/log/schedule/id");
	$sch_sock_path = "/var/run/schedule_usock";
	
	echo "usockc ".$sch_sock_path." \"act=del cmd=[sh /usr/sbin/syslog -s auth_sendmail]\"\n"." > /dev/console\n"; //client request delete chain.
		
	if($enable_mail_schedule != ""&&$enable_mail_schedule != "0")
	{
		if($log_schedule != "" && $log_schedule != 0)
		{
			$UNIQUEID = $log_schedule;
			require("/etc/templates/rg/__schedule.php");
			$sch_cmd = "usockc ".$sch_sock_path." \"act=add";
			if ($timeset != 1){$sch_cmd = $sch_cmd." et=1";}
			$sch_cmd = $sch_cmd." start=".$START." end=".$END." days=".$DAYS." cmd=[sh /usr/sbin/syslog -s auth_sendmail]\"\n";
			echo $sch_cmd." > /dev/console\n";
		}
	}
}
?>
