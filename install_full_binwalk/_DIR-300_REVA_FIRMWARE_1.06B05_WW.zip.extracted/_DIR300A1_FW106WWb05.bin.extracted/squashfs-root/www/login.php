<?
/* vi: set sw=4 ts=4: */
$AJAX_NAME="__login";
if ($ACTION_POST!="")
{
	require("/www/auth/__login.php");
	if		($AUTH_RESULT=="401")	{$HEADER_URL="login_fail.php"; require("/www/comm/__header.php");}
	else if ($AUTH_RESULT=="full")	{$HEADER_URL="session_full.php"; require("/www/comm/__header.php");}

	$HEADER_URL="index.php";
	require("/www/comm/__header.php");
}

/* ------------------------------------------------------------------------ */
$MY_NAME="login";
$MY_MSG_FILE=$MY_NAME.".php";
$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";
require("/www/model/__html_head.php");
$en_captcha=query("/sys/captcha");
if($en_captcha=="")	{$en_captcha=1;}
?>

<script>
var AjaxReq = null;
function send_request(url, update_func)
{
	if (AjaxReq == null) AjaxReq = createRequest();
	AjaxReq.open("GET", url, true);
	AjaxReq.onreadystatechange = update_func;
	AjaxReq.send(null);
}
function generate_img_ready()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if(AjaxReq.responseText!="")
		{
			var idx = AjaxReq.responseText;
			var f=document.getElementById("auth_img");
			f.innerHTML="<img src='auth_img/"+idx+"?random_str="+generate_random_str()+"'>";
			document.getElementById("FILECODE").value=idx;
		}
	}
}
function generate_img()
{
	var f=document.getElementById("auth_img");
	f.innerHTML="<font color=red><?=$m_wait_msg?></font>";
	send_request("<?=$AJAX_NAME?>.php?random_str="+generate_random_str(),generate_img_ready);
}
/* page init functoin */
function init()
{
	var f=get_obj("frm");
<?
if(query("/runtime/func/multi_user")==1)
{
	echo "	f.LOGIN_PASSWD.focus();\n";
}
else
{
	echo "	f.LOGIN_USER.focus();\n";
}
?>
	if(<?=$en_captcha?>)
	{
		get_obj('div_vercode_submit').style.display='';
		get_obj('div_vercode_dsc').style.display='';
		get_obj('div_vercode_body').style.display='';
		generate_img();
	}
	else
		get_obj('div_submit').style.display='';
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
<?
if(query("/runtime/func/multi_user")!=1)
{	
	echo '	if(f.LOGIN_USER.value=="")\n';
	echo '	{\n';
	echo '		alert("'.$a_invalid_user_name.'");\n';
	echo '		f.LOGIN_USER.focus();\n';
	echo '		return false;\n';
	echo '	}\n';
}
?>
	if(<?=$en_captcha?>)
	{
		if(f.VER_CODE.value=="")
		{
			alert("<?=$a_blank_vercode?>");
			f.VER_CODE.focus();
			return false;
		}
		else
		{
			f.VERIFICATION_CODE.value = f.VER_CODE.value.toUpperCase();
			f.VER_CODE.disabled = true;
		}
	}
	return true;
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="login.php" onSubmit="return check();">
<input type="hidden" name="ACTION_POST" value="LOGIN">
<input type="hidden" name="FILECODE" id="FILECODE" value="">
<input type="hidden" name="VERIFICATION_CODE" id="VERIFICATION_CODE" value="">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=middle align=center>
	<td>
	<br>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table width=80%>
	<tr>
		<td id="box_header">
			<h1><?=$m_context_title?></h1>
			<?=$m_login_router?>
			<br><br><center>
			<table width="40%">
			<tr>
				<td><?=$m_user_name?></td>
				<td>
					<?
					if(query("/runtime/func/multi_user")==1)
					{
						echo '<select size="1" name="LOGIN_USER" id="LOGIN_USER">';
						for("/sys/user")
						{
							echo '<option value="'.query("name").'">'.query("name").'</option>';
						}
						echo '</select>';
					}
					else
					{
						echo '<input type=text name="LOGIN_USER" id="LOGIN_USER" value="">';
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?=$m_password?></td>
				<td><input type=password name="LOGIN_PASSWD" maxlength=20></td>
				<td id="div_submit" style="display:none"><input type="submit" name="login" value="<?=$m_log_in?>"></td>
			</tr>
			<tr id="div_vercode_dsc" style="display:none">
				<td colspan=2><?=$m_vercode_dsc?>
					<input type="text" name="VER_CODE" autocomplete="off">
				</td>
			</tr>
			<tr id="div_vercode_body" style="display:none">
				<td colspan=2>
					<table><tr>
					<td><span id="auth_img"></span></td>
					<td><input type=button name="regen" onclick="generate_img();" value="<?=$m_gegenerate?>" valign="middle"></td>
					</tr></table>
				</td>
			</tr>
			<tr id="div_vercode_submit" style="display:none">
				<td colspan=2 align="center"><input type="submit" name="login" value="<?=$m_log_in?>" style="WIDTH: 150px;"></td>
			</tr>
			</table>
			</center><br>
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
