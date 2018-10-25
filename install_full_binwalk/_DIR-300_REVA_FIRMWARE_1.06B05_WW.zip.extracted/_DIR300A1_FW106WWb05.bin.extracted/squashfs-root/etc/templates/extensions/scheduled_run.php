#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
if ($generate_start==1)
{
	echo "echo Start scheduled ... > /dev/console\n";
	echo "scheduled &\n";
}
else
{
	 echo "echo Stop scheduled ... > /dev/console\n";
	 echo "killall scheduled\n";
}
?>
