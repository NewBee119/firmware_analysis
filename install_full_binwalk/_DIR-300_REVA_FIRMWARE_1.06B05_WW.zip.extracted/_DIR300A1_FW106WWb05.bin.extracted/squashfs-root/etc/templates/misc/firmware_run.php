<?
/* vi: set sw=4 ts=4: */
/*tsrites*/

require("/etc/templates/troot.php");

//echo "\necho \"begin\"\n\n";

$rumtime_checkfw=query("/runtime/func/checkfw");
$firmware_notify=query("/sys/email/firmwarenotify");
$auto_check_firmware=query("/sys/autocheckfirmware");

$runtime_fw_rootpath ="/runtime/firmware/";
$runtime_langcode_rootpath ="/runtime/language/";

$firmware_xml_path	= "/var/www/fwinfo.xml";
$email_file_path	=	"/var/www/mailfile.txt";

if ($rumtime_checkfw == 1)
{
	if($firmware_notify == 1||$auto_check_firmware==1)
	{
		/*check_firmware*/
				
		/* If the multiple languages are built-in, the langcode is configured by user. */
		/* 1. set to default langcode */
		$__LOCALE_LANGUAGE = fread("/etc/config/langcode");
		//echo "#1:".$__LOCALE_LANGUAGE."\n";
		/* 2. if no default value, use user configured value. */
		if ($__LOCALE_LANGUAGE=="") { $__LOCALE_LANGUAGE = query("/sys/langcode"); }
		/* 3. if no user configured, the alternated (external) lang pack is installed.
		*    check the lang pack setting. */
		//echo "#2:".$__LOCALE_LANGUAGE."\n";
		
		if ($__LOCALE_LANGUAGE=="")
		{
			/* get the lang pack info. */
			$CHARSET = fread("/www/locale/alt/charset");
			$LANGCODE = fread("/www/locale/alt/langcode");
			if ($CHARSET == "") { $__LOCALE_LANGUAGE = "en"; }
			else { $__LOCALE_LANGUAGE = "alt"; }
		}else{
			$LANGCODE = $__LOCALE_LANGUAGE;
		}
		
		if($LANGCODE == "")
		{
			$LANGCODE = $__LOCALE_LANGUAGE;
		}
		
		if($LANGCODE == "de")
		{
			$langcode="DE";
		}
		else if($LANGCODE == "fr")
		{
			$langcode="FR";
		}
		else if($LANGCODE == "ko")
		{
			$langcode="KR";
		}
		else if($LANGCODE == "zhcn")
		{
			$langcode="CN";
		}
		else if($LANGCODE == "zhtw")
		{
			$langcode="TW";
		}
		else
		{
			$langcode="EN";
		}
		
		echo "rm -f ".$firmware_xml_path."\n";
		echo "rm -f ".$email_file_path."\n\n";

		echo "hwversion=`rgdb -g /sys/hwversion|cut -b 1|tr '[a-z]' '[A-Z]'`\n";
		echo "buildver=`cat /etc/config/buildver|tr -d '.'`\n";	
		echo "boardfw=\"$hwversion\"x_Default_FW_0\"$buildver\"\n";
		echo "boardlangcode=\"$hwversion\"x_\"$langcode\"_FW_0\"$buildver\"\n";

		echo "sys -s check_fw \"$boardfw\"\n"; 
		echo "if [ -s ".$firmware_xml_path." ]; then\n";
		echo "#get firmware information\n";
		echo "new_major=`grep Major ".$firmware_xml_path."|sed 's/^[ \\t]*\/\/'|sed 's/<Major>\/\/'|sed 's/<\\\/Major>\/\/'`\n";
		echo "new_minor=`grep Minor ".$firmware_xml_path."|sed 's/^[ \\t]*\/\/'|sed 's/<Minor>\/\/'|sed 's/<\\\/Minor>\/\/'`\n";
		echo "rgdb -i -s ".$runtime_fw_rootpath."FW_Version/Major \"$new_major\"\n";
		echo "rgdb -i -s ".$runtime_fw_rootpath."FW_Version/Minor \"$new_minor\"\n";
		echo "fi\n";

		echo "needmail=0\n";
		echo "buildver=`cat /etc/config/buildver`\n";	
		echo "old_major=`echo $buildver|sed 's/.[0-9][0-9]\/\/g'`\n";
		echo "old_minor=`echo $buildver|sed 's/.*\\.\/\/g'`\n";

		echo "if [ -n \"$new_major\" ]; then\n";
		echo "	if [ $new_major -gt $old_major -o $new_major -eq $old_major -a $new_minor -gt $old_minor ]; then\n";
		echo "		echo \"Have new firmware\"\n";
		if($auto_check_firmware==1)
		{
			echo "		rgdb -i -s /runtime/firmware/havenewfirmware 1\n";
		}
		
		if($firmware_notify == 1)
		{	
			echo "		needmail=\"1\"\n";	
			echo "		rgdb -A ".$template_root."/misc/firmware_gen_mail.php -V generate_mail=1 >".$email_file_path."\n";//gen firmware information
		}
			echo "	fi\n";
		echo "fi \n";
		/*firmware end*******************************************************************/

		/*language start*******************************************************************/
		echo "sys -s check_fw \"$boardlangcode\"\n"; 
		echo "if [ -s ".$firmware_xml_path." ]; then\n";
		echo "#get language information\n";
		echo "new_major=`grep Major ".$firmware_xml_path."|sed 's/^[ \\t]*\/\/'|sed 's/<Major>\/\/'|sed 's/<\\\/Major>\/\/'`\n";
		echo "new_minor=`grep Minor ".$firmware_xml_path."|sed 's/^[ \\t]*\/\/'|sed 's/<Minor>\/\/'|sed 's/<\\\/Minor>\/\/'`\n";
		echo "rgdb -i -s ".$runtime_langcode_rootpath."FW_Version/Major \"$new_major\"\n";
		echo "rgdb -i -s ".$runtime_langcode_rootpath."FW_Version/Minor \"$new_minor\"\n";
		echo "fi\n";

		echo "if [ -n \"$new_major\" ]; then\n";
		echo "	if [ $new_major -gt $old_major -o $new_major -eq $old_major -a $new_minor -gt $old_minor ]; then\n";
		echo "		echo \"Have new langcode\"\n";
		if($auto_check_firmware==1)
		{
			echo "		rgdb -i -s /runtime/firmware/havenewfirmware 1\n";
		}
		
		if($firmware_notify == 1)
		{
			echo "		needmail=\"1\"\n";
			echo "		rgdb -A ".$template_root."/misc/firmware_gen_mail.php -V generate_mail=0 >>".$email_file_path."\n";
		}
		echo "	fi\n";
		echo "fi \n";
		/*language end*******************************************************************/		

		/*SEND MAIL*/
		if($firmware_notify == 1)	
		{
			echo "\n";
			echo "#send mail\n";
			echo "if [ $needmail = \"1\" ]; then\n";
			echo "	#Send mail\n";
			echo "	mail_server=`rgdb -g /sys/log/mailserver`\n";
			echo "	email_addr=`rgdb -g /sys/log/email`\n";
			echo "	mail_subject=`rgdb -g /sys/log/subject`\n";
			echo "	username=`rgdb -g /sys/log/username`\n";
			echo "	password=`rgdb -g /sys/log/pass1`\n";
			echo "	from=`rgdb -g /sys/log/sender`\n";

			echo "	if [ \"$mail_server\" != \"\" -a \"$email_addr\" != \"\" ]; then\n";
			echo "		if [ \"$username\" != \"\" ]; then\n";
			echo "			/usr/sbin/sendmail -s \"$mail_subject\" -S \"$mail_server\" -a \"$from\"  -t \"$email_addr\" -u \"$username\" -p \"$password\" -f ".$email_file_path."\n";
			echo "		else\n";
			echo "			/usr/sbin/sendmail -s \"$mail_subject\" -S \"$mail_server\" -a \"$from\"  -t \"$email_addr\" -f ".$email_file_path."\n ";
			echo "		fi\n";
			echo "	#	logger -p 192.1 \"SYS:004[$email_addr]\"\n";
			echo "	fi\n\n";
			echo "fi\n\n";
				/*Clean the template data*/
			echo "#Clear tmp data\n";
		}
		echo "#rm -f ".$email_file_path."\n";
	}
}
//echo "\necho \"end\"\n";
?>
