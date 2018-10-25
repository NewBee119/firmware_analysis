#!/bin/sh
echo [$0] ... > /dev/console
# neaps_run.php >>>
<?
/* vi: set sw=4 ts=4: */
$configfile="/var/run/neaps.conf";

if ($generate_start==1 && query("/runtime/func/neaps")==1)
{
	$LANIF=query("/runtime/layout/lanif");
	echo "echo Start Neap Server ... > /dev/console\n";
	echo "neaps -i ".$LANIF." -c ".$configfile." &\n";
}
else
{
	echo "echo Stop Neap Server ... > /dev/console\n";
	echo "killall neaps> /dev/console\n";
}
?>
# neaps_run.php <<<
