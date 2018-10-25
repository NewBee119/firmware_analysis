<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_log_setting";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	
	echo "<!--\n";
	echo "srv=".$srv."\n";
	echo "email_addr=".$email_addr."\n";
	echo "log_sys=".$log_sys."\n";
	echo "log_dbg=".$log_dbg."\n";
	echo "log_att=".$log_att."\n";
	echo "log_drp=".$log_drp."\n";
	echo "log_ntc=".$log_ntc."\n";
	echo "send_mail=".$send_mail."\n";
	echo "-->\n";
	

	$log_dirty=0;
	if($log_sys!="1"){$log_sys="0";}
	if($log_dbg!="1"){$log_dbg="0";}
	if($log_att!="1"){$log_att="0";}
	if($log_drp!="1"){$log_drp="0";}
	if($log_ntc!="1"){$log_ntc="0";}
	
	$pre="/sys/log/";
	if(query($pre."email")		!=$email_addr)	{set($pre."email",				$email_addr);	$log_dirty++;}
	if(query($pre."subject")        !=$subject)
	{
		set($pre."subject",     $subject);		$log_dirty++;
		fwrite("/var/log/subject", $subject);
	}
	if(query($pre."sender")		!=$sender)			{set($pre."sender",				$sender);			$log_dirty++;}
	if(query($pre."mailserver")	!=$srv)				{set($pre."mailserver",		$srv);	 			$log_dirty++;}
	if(query($pre."mailport")	!=$srv_port)		{set($pre."mailport",		$srv_port);	 	$log_dirty++;}

	if($auth_enable!="1"){$auth_enable="0";}
	if(query($pre."emailauthenable")	!=$auth_enable)	{set($pre."emailauthenable",	$auth_enable);	$log_dirty++;}
	if(query($pre."username")	!=$name)				{set($pre."username",			$name);				$log_dirty++;}
	if(query($pre."pass1")		!=$pass1)				{set($pre."pass1",				$pass1);			$log_dirty++;}
	if(query($pre."pass2")		!=$pass2)				{set($pre."pass2",				$pass2);			$log_dirty++;}
	
	anchor("/security/log");
	if(query("systemInfo")		!=$log_sys)		{set("systeminfo",		$log_sys);		$log_dirty++;}
	if(query("debugInfo")		!=$log_dbg)		{set("debuginfo",		$log_dbg);		$log_dirty++;}
	if(query("attackInfo")		!=$log_att)		{set("attackinfo",		$log_att);		$log_dirty++;}
	if(query("droppacketInfo")	!=$log_drp)		{set("droppacketinfo",	$log_drp);		$log_dirty++;}
	if(query("noticeInfo")		!=$log_ntc)		{set("noticeinfo",		$log_ntc);		$log_dirty++;}
	
	$SUBMIT_STR="";
	if($log_dirty>0)	{$SUBMIT_STR=";submit SYSLOG;submit RG";}
	if($send_mail=="1")     {fwrite("/var/log/subject", $subject);  $XGISET_AFTER_COMMIT_STR="set/runtime/syslog/auth_sendmail=1";}

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="" || $XGISET_AFTER_COMMIT_STR!="")	{require($G_SAVING_URL);}
	else												{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$mail_auth_enable = query("/sys/log/emailauthenable");
/* --------------------------------------------------------------------------- */
?>

<script>
// Lily
Lists=[<?inclog("[\"%0\",\"%1\"],","/var/log/messages");?>["",""]];

sData=[<?
anchor("/sys/log");
echo "'".get("j","email")."','".get("j","subject")."','".get("j","sender")."','".get("j","mailserver")."','".get("j","mailport")."','".get("j","username")."','".get("j","pass1")."','".get("j","pass2")."'";
?>];
//system, debug, att, drop, notice
syslog=['',<?
anchor("/security/log");
echo	"'".	query("systemInfo").		"','".query("debugInfo").	"','".query("attackInfo").
		"','".	query("dropPacketInfo").	"','".query("noticeInfo").	"'";
?>];
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.email_addr.value=sData[0];
	f.subject.value=sData[1];
	f.sender.value=sData[2];
	f.srv.value=sData[3];
	
	if(sData[4]=="")
		f.srv_port.value=25;
	else
		f.srv_port.value=sData[4];
	
	<?	if($mail_auth_enable=="1")	{echo "f.auth_enable.checked=true;";}?>			
	f.name.value=sData[5];
	f.pass1.value=sData[6];
	f.pass2.value=sData[7];
	
	f.log_sys.checked=(syslog[1]=="1" ?true:false);
	f.log_dbg.checked=(syslog[2]=="1" ?true:false);
	f.log_att.checked=(syslog[3]=="1" ?true:false);
	f.log_drp.checked=(syslog[4]=="1" ?true:false);
	f.log_ntc.checked=(syslog[5]=="1" ?true:false);
	
	check_auth_ebox();
}
/* parameter checking */
function check(bmail)
{
	var f=get_obj("frm");

	if(is_blank(f.email_addr.value) && is_blank(f.subject.value) && is_blank(f.sender.value) && 
	   is_blank(f.srv.value) && is_blank(f.name.value) && is_blank(f.pass1.value) && is_blank(f.pass2.value))
	{
		if(bmail!="1") return true;
	}
	if(is_blank(f.email_addr.value))
	{
		alert("<?=$a_invalid_email?>");
		f.email_addr.focus();
		return false;
	}
	if(is_blank(f.sender.value))
	{
		alert("<?=$a_invalid_email?>");
		f.sender.focus();
		return false;
	}
	if(is_blank(f.srv.value))
	{
		alert("<?=$a_invalid_smtp_srv?>");
		f.srv.focus();
		return false;
	}
	if(!is_blank(f.email_addr.value))
	{
		if(strchk_email(f.email_addr.value)==false)
		{
			alert("<?=$a_invalid_email?>");
			f.email_addr.select();
			return false;
		}
		var str=f.email_addr.value;
		var valid_email=false;
		for(var i=0; i<str.length; i++)
		{
			if( (str.charAt(i) != '@') )	continue;
			else	valid_email=true;
		}
		if(!valid_email)
		{
				alert("<?=$a_invalid_email?>");
				f.email_addr.select();
				return false;
		}
	}
	if(!is_blank(f.sender.value))
	{
		if(strchk_email(f.sender.value)==false)
		{
			alert("<?=$a_invalid_email?>");
			f.sender.select();
			return false;
		}
		var str=f.sender.value;
		var valid_email=false;
		for(var i=0; i<str.length; i++)
		{
			if( (str.charAt(i) != '@') )	continue;
			else	valid_email=true;
		}
		if(!valid_email)
		{
				alert("<?=$a_invalid_email?>");
				f.sender.select();
				return false;
		}
	}
	if(!is_blank(f.srv.value))
	{
		if(strchk_hostname(f.srv.value)==false)
		{
			alert("<?=$a_invalid_smtp_srv?>");
			f.srv.select();
			return false;
		}
	}

	//smtp port
	if(is_blank(f.srv_port.value))
	{
		alert("<?=$a_blank_port?>");
		f.srv_port.focus();
		return false;
	}
	if (!is_valid_port_str(f.srv_port.value))
	{
		alert('<?=$a_invalid_port?>');
		f.srv_port.focus();
		return false;
	}	
	
	if(f.auth_enable.checked)
	{	
		if(is_blank(f.name.value))
		{
			alert("<?=$a_invalid_name?>");
			f.name.select();
			return false;
		}
		if(is_blank(f.pass1.value))
		{
			alert("<?=$a_invalid_password?>");
			f.pass1.select();
			return false;
		}
		if(is_blank(f.pass2.value))
		{
			alert("<?=$a_invalid_password?>");
			f.pass2.select();
			return false;
		}
		if(f.pass1.value!=f.pass2.value)
		{
			alert("<?=$a_invalid_pass?>");
			f.pass1.select();
			return false;
		}	
	}

	f.name.disabled=false;
	f.pass1.disabled=false;
	f.pass2.disabled=false;
	
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

function send_mail_now()
{
	var f=get_obj("frm");
	if(check(1)==false) return false;

	f.send_mail.value="1";
	f.submit();
}
function check_auth_ebox()
{
	var f=get_obj("frm");
	if(f.auth_enable.checked)
	{		
		f.name.disabled=false;
		f.pass1.disabled=false;
		f.pass2.disabled=false;
	}
	else
	{
		f.name.disabled=true;
		f.pass1.disabled=true;
		f.pass2.disabled=true;
	}
}

</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check(0)">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<input type="hidden" name="send_mail" value="0">
<?require("/www/model/__banner.php");?>
<?require("/www/model/__menu_top.php");?>
<table <?=$G_MAIN_TABLE_ATTR?> height="100%">
<tr valign=top>
	<td <?=$G_MENU_TABLE_ATTR?>>
	<?require("/www/model/__menu_left.php");?>
	</td>
	<td id="maincontent">
		<div id="box_header">
		<?
		require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");
		echo $G_APPLY_CANEL_BUTTON;
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_savelog_title?></h2>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr>
				<td class=l_tb colspan=2><?=$m_save_log_cfg?>&nbsp;<input type=button name=save_log value="<?=$m_save?>" onClick="window.location.href='/tsyslog.rg';"></td>
			</tr>
		</table>
		</div>
		<div class="box">
			<h2><?=$m_context_title?></h2>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr>
				<td class=l_tb><?=$m_log_type?></td>
				<td class=l_tb><input type=checkbox name=log_sys value="1"><?=$m_log_sys?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class=l_tb><input type=checkbox name=log_dbg value="1"><?=$m_log_dbg?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class=l_tb><input type=checkbox name=log_att value="1"><?=$m_log_att?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class=l_tb><input type=checkbox name=log_drp value="1"><?=$m_log_drp?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class=l_tb><input type=checkbox name=log_ntc value="1"><?=$m_log_ntc?></td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?=$m_sendmail_title?></h2>
			<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>
			<tr>
				<td width=35% class=l_tb><?=$m_email_addr?></td>
				<td width=65%><input type=text name=email_addr maxlength="60"></td>
			</tr>
			<tr>
				<td width=35% class=l_tb><?=$m_email_sub?></td>
				<td width=65%><input type=text name=subject maxlength="60"></td>
			</tr>
			<tr>
				<td width=35% class=l_tb><?=$m_send_email_addr?></td>
				<td width=65%><input type=text name=sender maxlength="60"></td>
			</tr>
			<tr>
				<td width=35% class=l_tb><?=$m_smtp_srv_ip?></td>
				<td width=65%><input type=text name=srv maxlength="60"></td>
			</tr>
			<tr>
				<td width=35% class=l_tb><?=$m_smtp_srv_port?></td>
				<td width=65%><input type=text name=srv_port maxlength="5"></td>
			</tr>			
			<tr>
				<td width=35% class=l_tb><?=$m_send_auth?></td>
				<td width=65%><input type=checkbox name=auth_enable  value=1 onclick="check_auth_ebox();"></td>
			</tr>
			<tr>
				<td width=35% class=l_tb><?=$m_send_name?></td>
				<td width=65%><input type=text name=name maxlength="60"></td>
			</tr>
			<tr>
				<td class=l_tb><?=$m_send_pass1?></td>
				<td><input type=password name=pass1 maxlength="60"></td>
			</tr>
			<tr>
				<td class=l_tb><?=$m_send_pass2?></td>
				<td><input type=password name=pass2 maxlength="60">&nbsp;
					<input type=button name=sendmail onclick="send_mail_now()" value="<?=$m_send_mail_now?>">
				</td>
			</tr>
		</table>
		</div>

<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
