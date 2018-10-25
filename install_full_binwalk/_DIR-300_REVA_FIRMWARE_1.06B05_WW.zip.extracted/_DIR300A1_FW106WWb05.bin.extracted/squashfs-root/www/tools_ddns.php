<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_ddns";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";
$AJAX_NAME		="__ajax_tools_ddns_setnodes";
/* --------------------------------------------------------------------------- */
$router=query("/runtime/router/enable");
if ($ACTION_POST!="" && $router=="1")
{
	require("/www/model/__admin_check.php");
	echo "<!--\n";
	echo "en_ddns=".$en_ddns."\n";
	echo "ddns_server=".$ddns_server."\n";
	echo "h_name=".$h_name."\n";
	echo "u_name=".$u_name."\n";
	echo "pass=".$pass."\n";
	echo "defpassword=".$G_DEF_PASSWORD."\n";
	echo "-->\n";

	if($en_ddns!="1"){$en_ddns="0";}

	$db_dirty=0;
	if(query("/ddns/enable")!=$en_ddns)		{set("/ddns/enable",	$en_ddns);		$db_dirty++;}
	anchor("/ddns");
	if(query("provider")	!=$ddns_server)	{set("provider",		$ddns_server);	$db_dirty++;}
	if(query("hostname")	!=$h_name)		{set("hostname",		$h_name);		$db_dirty++;}
	if(query("user") 		!=$u_name)		{set("user",			$u_name);		$db_dirty++;}
	if($pass!=$G_DEF_PASSWORD && query("password")!=$pass)
	{
		set("password",	$pass);
		$db_dirty++;
	}
	if($ddns_test=="true")
	{
		$db_dirty++;
		$XGISET_AFTER_COMMIT_STR="ddns_test=true";
	}

	if($db_dirty>0){$SUBMIT_STR="submit DDNS";}
	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
anchor("/ddns");
$provider=query("provider");
$hostname=queryjs("hostname");
$cfg_user=get("j","user");

if($LANGCODE == "zhcn")
{
	if(query("/runtime/func/peanut") == "1")
	{
		$status=query("/runtime/ddns/status");
		$usertype=query("/runtime/ddns/userType");
		if(query("/runtime/switch/wan_port") == "0")
		{
			$msg_status = $m_failed;
			$status="failed";
		}
		else
		{
			if($status == "successed")
			{
					$msg_status = $m_sucess;
					if($usertype == "0")
					{
						$msg_usertype =	$m_normal;		
					}
					else if($usertype == "1")
					{
						$msg_usertype =	$m_professional;
					}
			}
			else if($status == "failed")
			{
				$msg_status = $m_failed;
			}
			else if($status == "badAuth")
			{
				$msg_status = $m_badAuth;
			}
			else if($status == "connecting")
			{
				$msg_status = $m_connecting;
			}
		}
	 }
}

/* --------------------------------------------------------------------------- */
$symbol="&nbsp;:";
$symbol2="&nbsp;&nbsp;";
$symbol3="ON";
$symbol4="OFF";
$symbol5="&nbsp;";
?>

<script>
var AjaxReq = null;
var period 	= 3000;
var Count 	= 0;

function send_request(url, update_func)
{
    if (AjaxReq == null) AjaxReq = createRequest();
    AjaxReq.open("GET", url, true);
    AjaxReq.onreadystatechange = update_func;
    AjaxReq.send(null);
}
function ddns_test()
{
	var str="__ajax_tools_ddns_info.php?random_str="+generate_random_str();
	if (AjaxReq != null && AjaxReq.readyState == 4)
    {
		AjaxReq=null;
		send_request(str, show_test_result);
	}
	
}
function show_test_result()
{
	var str="";
	if (AjaxReq != null && AjaxReq.readyState == 4)
    {
		if (AjaxReq.responseText.substring(0,3) == "var")
    	{
			eval(AjaxReq.responseText);
			if(is_blank(result[0]) && Count < 19)
			{
				get_obj("message").innerHTML = "<?=$m_testing?> ...";
				str="send_request('__ajax_tools_ddns_info.php?random_str="+generate_random_str()+"', show_test_result)";
				setTimeout(str, period);
				Count=Count+1;
			}
			else
			{
				//Count=0;
				if (result[0] == "successed") get_obj("message").innerHTML = "<?=$m_update_success?>";
				else get_obj("message").innerHTML = "<?=$m_update_failed?>";
			}
		}
	}
}
function do_test_ddns()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		var str=new String("<?=$AJAX_NAME?>.xgi?");
		str+="submit=1";
		str+="&random_str="+generate_random_str();
		str+=exe_str("submit DDNS_TEST");
		send_request(str, ddns_test);
	}
}

function select_ddns_type()
{
  var f=get_obj("frm");
	if(f.ddns_server.value==5)
	{	
		get_obj("gen_ddns").style.display = "none";
		get_obj("gen_ddns_test").style.display = "none";
		<? 
		if($LANGCODE == "zhcn")
		{
			if(query("/runtime/func/peanut") == "1")
			{
				echo "get_obj(\"oray_ddns\").style.display = \"\";\n";
				echo "get_obj(\"dsc_oray\").style.display = \"\";\n";
			}
		}
		?>
		get_obj("dsc_gen").style.display = "none";
		
	}
	else{
		get_obj("gen_ddns").style.display = "";
		get_obj("gen_ddns_test").style.display = "";
		<? 
		if($LANGCODE == "zhcn")
		{
			if(query("/runtime/func/peanut") == "1")
			{
				echo "get_obj(\"oray_ddns\").style.display = \"none\";\n";
				echo "get_obj(\"dsc_oray\").style.display = \"none\";\n";
			}
		}
		?>
		get_obj("dsc_gen").style.display = "";
	}
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.en_ddns.checked=<?map("/ddns/enable","1","true",*,"false");?>;
	select_index(f.ddns_server, "<?=$provider?>");
	f.h_name.value="<?=$hostname?>";
	f.u_name.value="<?=$cfg_user?>";
	f.pass.value="<?=$G_DEF_PASSWORD?>";
	<?if($router!="1"){echo "fields_disabled(f,true);\n";}?>
	select_ddns_type();
<?
	if($ddns_test=="true")
	{
		echo "  window.open('tools_ddns_info.php','_updateinfo','width=850, height=380');\n";
		echo "	self.location.href='".$MY_NAME.".php?random_num='+generate_random_str();\n";
	}
?>
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(f.en_ddns.checked)
	{
		if(f.ddns_server.value!=5) /* not Oray.net */
		{
			if(is_blank(f.h_name.value))
			{
				alert("<?=$a_invalid_hostname?>");
				f.h_name.focus();
				return false;
			}
		}
		if(is_blank(f.u_name.value))
		{
			alert("<?=$a_invalid_username?>");
			f.u_name.focus();
			return false;
		}
		if(is_blank(f.pass.value))
		{
			alert("<?=$a_invalid_password?>");
			f.pass.focus();
			return false;
		}
	}
	if(strchk_hostname(f.h_name.value)==false)
	{
		alert("<?=$a_invalid_hostname?>");
		f.h_name.focus();
		return false;
	}
	if(strchk_hostname(f.u_name.value)==false)
	{
		alert("<?=$a_invalid_username?>");
		f.u_name.focus();
		return false;
	}
	if(strchk_unicode(f.pass.value)==true)
	{
		alert("<?=$a_invalid_password?>");
		f.pass.focus();
		return false;
	}
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function do_ddns_test()
{
	var str;
	var f=get_obj("frm");
	var pass_dirty="false";
	if(is_blank(f.h_name.value))
	{
		alert("<?=$a_invalid_hostname?>");
		f.h_name.focus();
		return false;
	}
	if(is_blank(f.u_name.value))
	{
		alert("<?=$a_invalid_username?>");
		f.u_name.focus();
		return false;
	}
	if(is_blank(f.pass.value))
	{
		alert("<?=$a_invalid_password?>");
		f.pass.focus();
		return false;
	}
	if(f.pass.value!="<?=$G_DEF_PASSWORD?>")
	{
		pass_dirty="true";	
	}
	get_obj("message").innerHTML = "<?=$m_testing?> ...";
	f.en_ddns.checked = true;
	if(f.en_ddns.value=="1")
	{
		Count=0;
		str="enable="+f.en_ddns.value;
		str+="&provider="+f.ddns_server.value;
		str+="&host="+f.h_name.value;
		str+="&user="+f.u_name.value;
		str+="&pass="+f.pass.value;
		str+="&pass_dirty="+pass_dirty;
		str+="&random_str="+generate_random_str();

		AjaxReq = createRequest();
		if (AjaxReq != null)
		{
			AjaxReq.open("POST", "/<?=$AJAX_NAME?>.php", true);
			AjaxReq.onreadystatechange = do_test_ddns;
			AjaxReq.send(str);
		}
	}
}
</script>
<body onLoad="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onSubmit="return check()">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
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
			<h2><?=$m_title_app_rules?></h2>
			<table>
			<tr>
			        <td width=170 height=20 class=r_tb><?=$m_enable_ddns?><?=$symbol?></td>
					<td height=20 class=l_tb><?=$symbol5?><input type=checkbox name=en_ddns value=1></td>
			</tr>
			<tr>
					<td height=20 class=r_tb><?=$m_ddns_server?><?=$symbol?></td>
					<td height=20><?=$symbol2?><select name="ddns_server" onChange="select_ddns_type();">
						<option value="14">dlinkddns.com(Free)</option>
						<option value="13">DynDns.org(Custom)</option>
						<option value="1">DynDns.org(Free)</option>
<!--						
						<option value="12">DynDns.org(Static)</option>
-->
						<? if($LANGCODE == "zhcn")
							{
								 if(query("/runtime/func/peanut") == "1")
								 {
								 	echo "<option value=\"5\">".$m_oray."</option>";
								 }
							}
						?>
					</select>
					</td>
			</tr>
			</table>

			<div id="gen_ddns" style="display:none">
			<table>
			<tr>
					<td width=170 height=20 class=r_tb><?=$m_ddns_host_name?><?=$symbol?></td>
					<td height=20><?=$symbol2?><input type="text" name="h_name" size="40" maxlength="60" value=""></td>
			</tr>
			</table>
			</div>
			
			<table>
			<tr>
					<td width=170 height=20 class=r_tb><?=$m_user?><?=$symbol?></td>
					<td height=20><?=$symbol2?><input type="text" name="u_name" size="40" maxlength="16" value=""></td>
			</tr>
			<tr>
					<td height=20 class=r_tb><?=$m_password?><?=$symbol?></td>
					<td height=20><?=$symbol2?><input type="password" name="pass" size="40" maxlength="16" value=""></td>
			</tr>
			</table>

			<div id="gen_ddns_test" style="display:none">
			<table>
			<tr>
					<td width=170></td>	
					<td height=20>&nbsp;&nbsp;<input type=button id="" value="<?=$m_ddns_test?>" onClick="do_ddns_test()"></td>
			</tr>
			<tr>
                	<td class=c_tb colspan=2 height=20><div id="message" ></div></td>
            </tr>  
			</table>
			</div>
			
			<? if($LANGCODE == "zhcn")
			{
				 if(query("/runtime/func/peanut") == "1")
				 {
				 	if(query("/ddns/provider")==5)
				 	{
				 		echo "<meta http-equiv=\"Refresh\" content=\"25\" >";
				 	}
					echo "<div id=\"oray_ddns\" style=\"display:none\">\n";
					echo "<table>\n";
					echo "<tr>\n";
					echo "	<td width=170 height=20 class=r_tb>".$m_server_status."".$symbol."</td>\n";
					echo "	<td height=20>".$symbol2."".$msg_status."</td>\n";
					echo "</tr>\n";
				  	if($status == "successed"){
						echo "<tr>\n";
						echo "	<td height=20 class=r_tb>".$m_server_class."".$symbol."</td>\n";
						echo "	<td height=20>".$symbol2."".$msg_usertype."</td>\n";
						echo "</tr>\n";
						echo "<tr>\n";
						echo "	<td height=20 class=r_tb valign=top>".$m_domain_name."".$symbol."\n";
						echo "	</td>\n";
						echo "	<td>\n";
						echo "		<table width=100%>\n";					
				   		$n=0;	
					   	for ("/runtime/ddns/DomainName"){
					   		$n=$n+1;
						echo "			<tr>\n";								
						echo "				<td height=20 width=10%>\n";								
						if(query("/runtime/ddns/StatusCode:".$n)==1)
						{
							echo "<font color=\"green\">".$symbol2."".$symbol3."</font>";
						}
						else
						{
							echo "<font color=\"red\">".$symbol2."".$symbol4."</font>";
						}	
						echo "				</td>\n";
						echo "				<td height=20 width=90%>\n";
						echo $symbol;
					   	echo .query("/runtime/ddns/DomainName:".$n);
						echo "				</td>\n";
						echo "			</tr>\n";						
					   	}
						echo "		</table>\n"
						echo "	</td>\n";
						echo "</tr>\n";						
				   }
					echo "</table>\n";
					echo "</div>\n";
				}
			}
			?>
			
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
