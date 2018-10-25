#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
$lanif = query("/runtime/layout/lanif");
$template_root=query("/runtime/template_root");
if ($template_root=="") { $template_root="/etc/templates"; }

$httpdpid="/var/run/httpd.pid";
$httpdloop_pid="/var/run/httpd-loop.pid";
$httpdloop="/var/run/http-loop.sh";

if ($generate_start==1)
{
	echo "echo Starting HTTPD ... > /dev/console\n";
	fwrite ($httpdloop, "#!/bin/sh\n");
	fwrite2($httpdloop, "while [ 1 = 1 ]; do\n");
	fwrite2($httpdloop, "rgdb -A ".$template_root."/httpd/httpd.php > /var/etc/httpd.cfg\n");
	fwrite2($httpdloop, "rgdb -A ".$template_root."/httpd/httpasswd.php > /var/etc/httpasswd\n");
	if (query("/function/httpd_upnp")==1)
	{ fwrite2($httpdloop, "route add -net 239.0.0.0 netmask 255.0.0.0 dev ".$lanif."\n"); }
	fwrite2($httpdloop, "httpd -s ".query("/runtime/layout/image_sign")." -f /var/etc/httpd.cfg\n");
	fwrite2($httpdloop, "done\n");
	echo "sh ".$httpdloop." &\n";
	echo "echo $! > ".$httpdloop_pid."\n";
}
else
{
	echo "echo Stopping HTTPD ... > /dev/console\n";
	echo "if [ -f ".$httpdloop_pid." ]; then\n";
	echo "	pid=`cat ".$httpdloop_pid."`\n";
	echo "	if [ $pid != 0 ]; then\n";
	echo "		kill $pid > /dev/null 2>&1\n";
	echo "	fi\n";
	echo "	rm -f ".$httpdloop_pid."\n";
	echo "fi\n";

	echo "if [ -f ".$httpdpid." ]; then\n";
	echo "	pid=`cat ".$httpdpid."`\n";
	echo "	if [ $pid != 0 ]; then\n";
	echo "		kill $pid > /dev/null 2>&1\n";
	echo "	fi\n";
	echo "	rm -f ".$httpdpid."\n";
	echo "fi\n";
	echo "route del -net 239.0.0.0 netmask 255.0.0.0 dev ".$lanif."\n";
}

?>
