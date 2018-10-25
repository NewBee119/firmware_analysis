# runtimed_run.php >>>
<?
/* vi: set sw=4 ts=4: */

$runtimed_pid="/var/run/runtimed.pid";
?>
if [ -f <?=$runtimed_pid?> ]; then
	pid=`cat <?=$runtimed_pid?>`
	if [ $pid != 0 ]; then
		kill $pid > /dev/console 2>&1
	fi
	rm -f <?=$runtimed_pid?>
fi
<?
if ($generate_start==1)
{
	$lanif=query("/runtime/layout/lanif");
	$wlanif=query("/runtime/layout/wlanif");
	$wlanif11a=query("/runtime/layout/wlanif_11a");
	$wanif=query("/runtime/wan/inf:1/interface");

	$param="";
	if ($lanif != "")		{ $param=" -l ".$lanif; }
	if ($wlanif != "")		{ $param=$param." -r ".$wlanif; }
	if ($wlanif11a!= "")	{ $param=$param." -a ".$wlanif11a; }
	if ($wanif != "")		{ $param=$param." -w ".$wanif; }
	echo "runtimed ".$param." &\n";
	echo "echo $! > ".$runtimed_pid."\n";
}
?>
# runtimed_run.php <<<
