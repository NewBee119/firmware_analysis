<?
$pidfile		="/var/run/dyndnstest.pid";

fwrite($OUTPUT_FILE, "#!bin/sh\n");

$test_path	="/runtime/ddns/test/";
$enable		=query($test_path."enable");
$provider	=query($test_path."provider");
$host		=query($test_path."host");
$user		=query($test_path."user");
$pass_dirty	=query($test_path."pass_dirty");
if($pass_dirty == "true"){$pass=query($test_path."pass");}
else{$pass=query("/ddns/password");}

$time		=query("/ddns/ipchecktime");
if($time=="") { $time="21600"; }   /* set default to 15 day */
$vendor		=query("/sys/vendor");
$model		=query("/sys/modelname");
$ipaddr		=query("/runtime/wan/inf:1/ip");


if($enable=="1")
{
	if ($provider!=1 && $provider!=2 && $provider!=3 &&
	$provider!=4 && $provider!=6 && $provider!=7 &&
	$provider!=12 && $provider!=13 && $provider!=14)
	{
		fwrite2($OUTPUT_FILE, "echo \"Unsupported dyndns provider #".$provider."\" > /dev/console\n");
		fwrite2($OUTPUT_FILE, "# dyndns_run.php <<<\n");
		exit;
	}
}
fwrite2($OUTPUT_FILE, "#host:".$host."\n");	
if ($user!="" && $pass!="")
{
	fwrite2($OUTPUT_FILE, "echo Start dyndns #".$provider."... > /dev/console\n");
	fwrite2($OUTPUT_FILE, "if [ -f ".$pidfile." ]; then\n");
	fwrite2($OUTPUT_FILE, "  pid=`cat ".$pidfile."`\n");
	fwrite2($OUTPUT_FILE, "  if [ \"$pid\" != \"\" ]; then\n");
	fwrite2($OUTPUT_FILE, "      kill -9 $pid\n");
	fwrite2($OUTPUT_FILE, "  fi\n");
	fwrite2($OUTPUT_FILE, "  rm -f ".$pidfile."\n");
	fwrite2($OUTPUT_FILE, "fi\n");
	fwrite2($OUTPUT_FILE, "dyndns -S".$provider);
	fwrite2($OUTPUT_FILE, " -u \"".$user."\" -p \"".$pass."\" -i \"".$ipaddr."\" -t ".$time);
	fwrite2($OUTPUT_FILE, " -o \"/var/run/dyndns.html\" -d \"".$vendor." ".$model."\"");
	fwrite2($OUTPUT_FILE, " -q \"/var/run/dyndns.info\" ");
	if ($host!="") { fwrite2($OUTPUT_FILE, " -n \"".$host."\""); }
	fwrite2($OUTPUT_FILE, " > /dev/console &\n");
	fwrite2($OUTPUT_FILE, "echo $! > ".$pidfile."\n");
	fwrite2($OUTPUT_FILE, "sleep 60\n");
	fwrite2($OUTPUT_FILE, "if [ -f ".$pidfile." ]; then\n");
	fwrite2($OUTPUT_FILE, "  pid=`cat ".$pidfile."`\n");
	fwrite2($OUTPUT_FILE, "  if [ \"$pid\" != \"\" ]; then\n");
	fwrite2($OUTPUT_FILE, "      kill -9 $pid\n");
	fwrite2($OUTPUT_FILE, "  fi\n");
	fwrite2($OUTPUT_FILE, "  rm -f ".$pidfile."\n");
	fwrite2($OUTPUT_FILE, "fi\n");
}
else
{
	fwrite2($OUTPUT_FILE, "echo \"No user/password for dyndns ...\" > /dev/console\n");
}
?>
