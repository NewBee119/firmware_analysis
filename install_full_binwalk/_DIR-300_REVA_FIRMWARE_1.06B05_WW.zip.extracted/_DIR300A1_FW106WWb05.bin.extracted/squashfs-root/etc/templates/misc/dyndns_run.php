<? if ($rg_script!=1) { echo "#!/bin/sh\necho [$0] ... > /dev/console\n"; } ?># dyndns_run.php >>>
<?
/* vi: set sw=4 ts=4: */
$progfile="/var/run/dyndns.name";

echo "if [ -f ".$progfile." ]; then\n";
echo "	prog=`cat ".$progfile."`\n";
echo "	if [ \"$prog\" = \"peanut\" ]; then\n";
echo "		killall -16 $prog\n";
echo "	fi\n";
echo "	if [ \"$prog\" = \"dyndns\" ]; then\n";
echo "		killall -9 $prog\n";
echo "	fi\n";
echo "	rm -f ".$progfile."\n";
//echo "echo \"status:failed\" > /var/run/dyndns.info\n"; 
echo "fi\n";

if ($generate_start==1 && query("/ddns/enable")==1)
{
	$provider=get("s","/ddns/provider");
	$HOST=get("s","/ddns/hostname");
	$USER=get("s","/ddns/user");
	$PASS=get("s","/ddns/password");
	$IPADDR=query("/runtime/wan/inf:1/ip");
	$TIME=query("/ddns/ipchecktime");
	if ($TIME=="") { $TIME="21600"; }	/* set default to 15 day */

	$vendor=get("s","/sys/vendor");
	$model=get("s","/sys/modelname");
	if ($provider!=1 && $provider!=2 && $provider!=3 &&
		$provider!=4 && $provider!=6 && $provider!=7 &&
		$provider!=12 && $provider!=13 && $provider!=14)
	{
		if( query("/runtime/func/peanut") == 1 && $provider == 5)
		{
			if ($USER!="" && $PASS!="")
			{
				echo "echo Start peanut #".$provider."... > /dev/console\n";
				echo "echo peanut > ".$progfile."\n";
				echo "peanut -S \"".$provider;
				echo "\" -u \"".$USER."\" -p \"".$PASS."\" -i ".$IPADDR."";
				echo " -q \"/var/run/dyndns.info\"";
				echo " > /dev/console &\n";
				exit;
			}

		}else{
			echo "echo \"Unsupported dyndns provider #".$provider."\" > /dev/console\n";
			echo "# dyndns_run.php <<<\n";
			exit;
		}
	}

	if ($USER!="" && $PASS!="")
	{
		echo "echo Start dyndns #".$provider."... > /dev/console\n";
		echo "echo dyndns > ".$progfile."\n";
		echo "dyndns -S \"".$provider;
		echo "\" -u \"".$USER."\" -p \"".$PASS."\" -i ".$IPADDR." -t ".$TIME;
		echo " -o /var/run/dyndns.html -d \"".$vendor." ".$model."\"";
		echo " -q \"/var/run/dyndns.info\"";
		if ($HOST!="") { echo " -n \"".$HOST."\""; }
		echo " > /dev/console &\n";
	}
	else
	{
		echo "echo \"No user/password for dyndns ...\" > /dev/console\n";
	}
}
?>
# dyndns_run.php <<<
