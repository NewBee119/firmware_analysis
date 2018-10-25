		Control {
<?
if (query("/sys/authtype")!="s")
{
/*
 * This is for "popup" login.
 * Only DI series use "popup" dialog for login.
 */
echo "			Realm \"".query("/sys/hostname")."\"\n";
echo "			UserFile /var/etc/httpasswd\n";
echo "			Error401File /www/sys/not_auth.php\n";
}
?>
			SessionControl On
			SessionIdleTime <?=$SESSION_TIMEOUT?>
			SessionMax <?=$SESSION_NUM?>
			SessionFilter { php xgi _int cgi }
			ErrorFWUploadFile	/www/sys/wrongImg.htm
			ErrorCfgUploadFile	/www/sys/wrongConf.htm
			InfoFWRestartFile	/www/sys/restart.htm
			InfoCfgRestartFile	/www/sys/restart2.htm
			Alias /
			Location /www
<?
if (query("/runtime/func/widget/yahoo")=="1")
{
	echo "\t\t\tExternal {\n";
	echo "\t\t\t\t/sbin/atp { xml }\n";
	echo "\t\t\t}\n";
}
?>
		}
		Control {
			Alias /var
			Location /var/www
		}
