<?
require("/etc/templates/upnpmsg.php");
fwrite ($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \">> [$0] ...\" > ".$UPNPMSG."\n");
fwrite2($ShellPath, "echo \">> INIT NOTIFY ".$HOST.$URI.", ".$SID." ...\" > ".$UPNPMSG."\n");
fwrite2($ShellPath, "xmldbc -A ".$TARGET_PHP);
fwrite2($ShellPath, " -V HDR_URL=".$URI);
fwrite2($ShellPath, " -V HDR_HOST=".$HOST);
fwrite2($ShellPath, " -V HDR_SID=".$SID);
fwrite2($ShellPath, " -V HDR_SEQ=0");
fwrite2($ShellPath, " | upnpkits -H ".$HOST." -p TCP\n");
fwrite2($ShellPath, "rm -f ".$ShellPath."\n");
?>
