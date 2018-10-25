<?
/* vi: set sw=4 ts=4: --------------------------------------------------------- */
$MY_NAME		="tools_admin";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");

	$httpd_passwd=0;
	$remote_access=0;
	$grap_auth=0;
	
	for ("/sys/user")
	{
		if(query("name")	==$admin_name)
		{
			if(		$admin_password1 !=$G_DEF_PASSWORD
				&& $admin_password1 !=query("password"))
				{set("password",$admin_password1);  $httpd_passwd++;}
		}

		if(query("name")	==$user_name)
		{
			if(		$user_password1 !=$G_DEF_PASSWORD
				&& $user_password1 !=query("password"))
				{set("password",$user_password1);  $httpd_passwd++;}
		}
	}
/*	
	anchor("/sys/user:1");
	if(query("name")		!=$admin_name)		{set("name",$admin_name);			$httpd_passwd++;}
	if(	   $admin_password1	!=$G_DEF_PASSWORD
		&& $admin_password1	!=query("password")){set("password",$admin_password1);	$httpd_passwd++;}
*/
	if($router=="1")
	{
		anchor("/security/firewall");
		if (query("httpAllow")		!=$rt_enable_h)	{set("httpAllow",$rt_enable_h);		$remote_access++;}
		if (query("httpremoteip")	!=$rt_ipaddr)	{set("httpremoteip",$rt_ipaddr);	$remote_access++;}
		if (query("httpremoteport")	!=$rt_port)		{set("httpremoteport",$rt_port);	$remote_access++;}
	}
	if (query("/sys/captcha")!=$grap_auth_enable_h)	{set("/sys/captcha",$grap_auth_enable_h); $grap_auth++;}

	if($httpd_passwd > 0)	{$SUBMIT_STR="submit HTTPD_PASSWD"; $div=";";}
	if($remote_access > 0)	{$SUBMIT_STR=$SUBMIT_STR.$div."submit REMOTE";}
	if($SUBMIT_STR=="" && $grap_auth > 0)	{$ONLY_DO_SUBMIT_STR="submit COMMIT";}

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="" || $ONLY_DO_SUBMIT_STR!="")
		{require($G_SAVING_URL);}
	else
		{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb
//anchor("/sys/user:1");
//$admin_name=query("name");
//if(query("password")!=""){$admin_password=$G_DEF_PASSWORD;}
$cfg_lan_ip		= query("/lan/ethernet/ip");
$cfg_lan_mask	= query("/lan/ethernet/netmask");
$admin_password=$G_DEF_PASSWORD;
$user_password=$G_DEF_PASSWORD;


/* --------------------------------------------------------------------------- */
?>
<script>
function on_check_rt_enable()
{
	var f=get_obj("frm");
	var dis=false;
	if (f.rt_enable.checked==false) dis=true;
	f.rt_ipaddr.disabled = f.rt_port.disabled = dis;
}


/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.admin_password1.value	=f.admin_password2.value="<?=$admin_password?>";
<?
if(query("/runtime/func/multi_user")==1)
{
	echo "	f.user_password1.value	=f.user_password2.value=\"".$user_password."\";";
}	
?>
	f.grap_auth_enable.checked = <? map("/sys/captcha","1","true","","true",*,"false"); ?>;
	<?anchor("/security/firewall");?>
	f.rt_enable.checked		= <? map("httpAllow","1","true",*,"false"); ?>;
	<?
		$httpremoteip = query("httpremoteip");
		if($httpremoteip == "") { $httpremoteip = "0.0.0.0"; }
	?>
	f.rt_ipaddr.value		= "<?=$httpremoteip?>";
	select_index(f.rt_port,	"<? map("httpremoteport", "","8080"); ?>");
	<?
	if($router!=1)
	{
		echo "f.rt_enable.disabled = f.rt_enable_h.disabled = f.rt_port.disabled = true;\n";
	}
	else
	{
		echo "on_check_rt_enable()\n";
	}
	?>
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
/*
	if(is_blank(f.admin_name.value))
	{
		alert("<?=$a_empty_login_name?>");
		f.admin_name.select();
		return false;
	}
	else if(strchk_hostname(f.admin_name.value)==false)
	{
		alert("<?=$a_invalid_login_name?>");
		f.admin_name.select();
		return false;
	}
*/
	if(strchk_unicode(f.admin_password1.value)==true)
	{
		alert("<?=$a_invalid_new_password?>");
		f.admin_password1.select();
		return false;
	}
	if(f.admin_password1.value!=f.admin_password2.value)
	{
		alert("<?=$a_password_not_matched?>");
		f.admin_password1.select();
		return false;
	}
<?
if(query("/runtime/func/multi_user")==1)
{
echo "	if(strchk_unicode(f.user_password1.value)==true)\n";
echo "	{\n";
echo "		alert(\"".$a_invalid_new_password."\");\n";
echo "		f.user_password1.select();\n";
echo "		return false;\n";
echo "	}\n";
echo "	if(f.user_password1.value!=f.user_password2.value)\n";
echo "	{\n";
echo "		alert(\"".$a_password_not_matched."\");\n";
echo "		f.user_password1.select();\n";
echo "		return false;\n";
echo "	}\n";
}
?>
	if (f.rt_ipaddr.value != "0.0.0.0")
	{
		if (is_valid_ip(f.rt_ipaddr.value, 0)==false)
		{
			alert("<?=$a_invalid_ipaddr?>");
			f.rt_ipaddr.select();
			return false;
		}

		lannet = get_network_id("<?=$cfg_lan_ip?>", "<?=$cfg_lan_mask?>");
		rt_net = get_network_id(f.rt_ipaddr.value, "<?=$cfg_lan_mask?>");
		if (lannet[0] == rt_net[0])
		{
			alert("<?=$a_invalid_ipaddr?>");
			f.rt_ipaddr.select();
			return false;
		}
	}
	
	f.rt_enable_h.value=(f.rt_enable.checked ?"1":"0");
	f.grap_auth_enable_h.value=(f.grap_auth_enable.checked ?"1":"0");
	return true;
}

function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="1">
<?require("/www/model/__banner.php");
require("/www/model/__menu_top.php");?>
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
		</div><br>
<!-- ________________________________ Main Content Start __________________________________________ -->
<?
$td_width1="width=\"180\" align=\"right\"";
$td_width2="width=\"368\"";
?>
		<div class="box">
		<h2><?=$m_context_title_admin?></h2><br>
			<table>
			<input type="hidden" name="admin_name" value="admin">
			<tr>
				<td colspan="2" align="left"><?=$m_enter_pwd_confirm_dec?></td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td <?=$td_width1?>><?=$m_new_password?>:</td>
				<td <?=$td_width2?>>&nbsp;<input type="password" name="admin_password1" size=20 maxlength=20 onFocus=this.select();></td>
			</tr>
			<tr>
				<td align="right"><?=$m_confirm_password?>:</td>
				<td>&nbsp;<input type="password" name="admin_password2" size=20 maxlength=20 onFocus=this.select();></td>
			</tr>
			</table>
		</div>

		<!-- ------------------------------------------------------------------------------------ -->
<?
if(query("/runtime/func/multi_user")==1)
{
	echo "		<div class=\"box\">";
	echo "			<h2>".$m_context_title_user."</h2><br>";
	echo "			<table>";
	echo "			<input type=\"hidden\" name=\"user_name\" value=\"user\">";
	echo "			<tr>";
	echo "				<td colspan=\"2\" align=\"left\">".$m_enter_pwd_confirm_dec."</td>";
	echo "			</tr>";
	echo "			<tr><td colspan=\"2\">&nbsp;</td></tr>";
	echo "			<tr>";
	echo "				<td ".$td_width1.">".$m_new_password.":</td>";
	echo "				<td ".$td_width2.">&nbsp;<input type=\"password\" name=\"user_password1\" size=20 maxlength=20 onFocus=this.select();></td>";
	echo "			</tr>";
	echo "			<tr>";
	echo "				<td align=\"right\">".$m_confirm_password.":</td>";
	echo "				<td>&nbsp;<input type=\"password\" name=\"user_password2\" size=20 maxlength=20 onFocus=this.select();></td>";
	echo "			</tr>";
	echo "			</table>";
	echo "		</div>";
}
?>
		<!-- ------------------------------------------------------------------------------------ -->
	
		<div class="box">
			<h2><?=$m_context_title_remote?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td <?=$td_width1?>><?=$m_enable_grap_auth?>:</td>
				<td <?=$td_width2?>>&nbsp;<input type="checkbox" name="grap_auth_enable"></td>
				<input type="hidden" name="grap_auth_enable_h" value=0>
			</tr>
			<tr>
				<td <?=$td_width1?>><?=$m_eable_remote?>:</td>
				<td <?=$td_width2?>>&nbsp;<input type="checkbox" name="rt_enable" onclick=on_check_rt_enable();></td>
				<input type="hidden" name="rt_enable_h" value=0>
			</tr>
			<tr>
				<td align="right"><?=$m_ip_allowed?>:</td>
				<td>&nbsp;<input type="text" name="rt_ipaddr" size=20 maxlength=16></td>
			</tr>
			<tr>
				<td align="right"><?=$m_port?>:</td>
				<td>&nbsp;<select name="rt_port">
					<option value="80">80</option>
					<option value="88">88</option>
					<option value="1080">1080</option>
					<option value="8080">8080</option>
				</select></td>
			</tr>
			</table>
		</div>

<!-- ________________________________  Main Content End ____________________________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
