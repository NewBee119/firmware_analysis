<?
/* vi: set sw=4 ts=4: */

$email_file_path	=	"/var/www/mailfile.txt";


if ($generate_mail == 1)
{

	$modulename=query("/sys/modelname");
	
	$root_node="/runtime/firmware/";
	echo "New firmware could be download for your router:".$modulename.",\n";
	echo "you could get it from: http:\/\/wrdp.dlink.com.tw/register.asp";
	echo "\n\n\n";
	
	$CUR_FW_VER=fread("/etc/config/buildver");
	if($CUR_FW_VER !="")
	{
		echo "Current firmware version is: ".$CUR_FW_VER."\n";
	}
	
	$NEW_FW_VER_MAJ=query($root_node."FW_Version/Major");	
	$NEW_FW_VER_MIN=query($root_node."FW_Version/Minor");	
	
	
	echo "New firmware version is: ".$NEW_FW_VER_MAJ.".".$NEW_FW_VER_MIN."\n";	
	
	echo "Download Site:\n";
	
	$Global=query($root_node."Download_Site/Global/Firmware");
		
	if($Global != "")
	{
		echo "Global:".$Global."\n";
	}
	
	$Europe=query($root_node."Download_Site/Europe/Firmware");
	if ($Europe != "")
	{
		echo "Europe:".$Europe."\n";
	}
	
	$NAmerica=query($root_node."Download_Site/North_America/Firmware");
	if ($NAmerica != "")
	{
		echo "North America:".$NAmerica."\n";
	}
}
else
{
	echo "\n\n\n";

	$root_node="/runtime/language/";
	
	$NEW_LANGCODE_VER_MAJ=query($root_node."FW_Version/Major");	
	$NEW_LANGCODE_VER_MIN=query($root_node."FW_Version/Minor");	
	
	if($NEW_LANGCODE_VER_MAJ !="")
	{
		echo "New Language  version is: ".$NEW_LANGCODE_VER_MAJ.".".$NEW_LANGCODE_VER_MIN."\n";	
		
		echo "Download Site:\n";
		$Global=query($root_node."Download_Site/Global/Firmware");
		
		if($Global != "")
		{
			echo "Global:".$Global."\n";
		}
	
		$Europe=query($root_node."Download_Site/Europe/Firmware");
		if ($Europe != "")
		{
			echo "Europe:".$Europe."\n";
		}
	
		$NAmerica=query($root_node."Download_Site/North_America/Firmware");
		if ($NAmerica != "")
		{
			echo "North America:".$NAmerica."\n";
		}
	}
}

?>
