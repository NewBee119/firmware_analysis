<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_log";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
/* --------------------------------------------------------------------------- */
$row_num="10";  //the row number per page
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.

/* --------------------------------------------------------------------------- */
?>

<script>
dataLists=[<?inclog("[\"%0\",\"%1\"],","/var/log/messages");?>["",""]];

var d_len=dataLists.length-1;
var row_num=parseInt("<?=$row_num?>", [10]);
var max=(d_len%row_num==0? d_len/row_num : parseInt(d_len/row_num, [10])+1);

function showSysLog()
{
	var str=new String("");
	var f=document.getElementById("frmLog");
	var p=parseInt(f.curpage.value, [10]);

	if (max==0 || max==1)
	{
		f.Pp1.disabled=true;
		f.Np1.disabled=true;
	}
	else
	{
		if (p==1)
		{
			f.Pp1.disabled=true;
			f.Np1.disabled=false;
		}
		if (p==max)
		{
			f.Pp1.disabled=false;
			f.Np1.disabled=true;
		}
		if (p > 1 && p < max)
		{
			f.Pp1.disabled=false;
			f.Np1.disabled=false;
		}
	}

	if (document.layers) return true;
	{
		str+="<p><?=$m_page?> "+p+" <?=$m_of?> "+max+"</p>";
		str+="<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width=525 bgColor=#dfdfdf border=1>";
		str+="<tr>";
		str+="<td align=middle><?=$m_time?></td>";
		str+="<td align=middle><?=$m_message?></td>";
		str+="</tr>";

		for (var i=((p-1)*row_num);i < p*row_num;i++)
		{
			if (i>=dataLists.length) break;
			str+="<tr border=1 borderColor='#ffffff' bgcolor='#dfdfdf'>";
			str+="<td>"+dataLists[i][0]+"</td>";
			str+="<td>"+dataLists[i][1]+"</td>";
			str+="</tr>";
		}
		str+="</table>";
	}

	if (document.all)           document.all("sLog").innerHTML=str;
	else if (document.getElementById)   document.getElementById("sLog").innerHTML=str;
}

function ToPage(p)
{
	if (document.layers)
	{
		alert("<?=$a_your_browser_is_not_support_this_function?><?=$a_upgrade_the_browser?>");
	}
	if (dataLists.length==0) return;
	var f=document.getElementById("frmLog");

	switch (p)
	{
		case "0":
			f.curpage.value=max;
		break;
		case "1":
			f.curpage.value=1;
		break;
		case "-1":
			f.curpage.value=(parseInt(f.curpage.value, [10])-1 <=0? 1:parseInt(f.curpage.value, [10])-1);
		break;
		case "+1":
			f.curpage.value=(parseInt(f.curpage.value, [10])+1 >=max? max:parseInt(f.curpage.value, [10])+1);
		break;
	}
	showSysLog();
}
function doClear()
{
<?	if ($AUTH_GROUP=="0")
	{
		echo "var str=new String(\"".$MY_NAME.".xgi?\");\n";
		echo "str+=\"set/runtime/syslog/clear=1\";\n";
		echo "self.location.href=str;\n";
	}?>
}

/* page init functoin */
function init()
{
	showSysLog();
}
/* parameter checking */
function check()
{
	return true;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frmLog" id="frmLog">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<input type=hidden name=curpage value="1">
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
		?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_title_log?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td align="right">
					<div align="left">
					<input type=button value="<?=$m_first_page?>" id="Fp1" name="Fp1" onclick=ToPage("1")>
					<input type=button value="<?=$m_last_page?>" id="Lp1" name="Lp1" onclick=ToPage("0")>
					<input type=button value="<?=$m_previous?>" id="Pp1" name="Pp1" onclick=ToPage("-1")>
					<input type=button value="<?=$m_next?>" id="Np1" name="Np1" onclick=ToPage("+1")>
					<input type=button value="<?=$m_clear?>" id=clear name=clear onclick=doClear()<?if ($AUTH_GROUP!="0"){echo " disabled";}?>>
					<?
						if(query("/runtime/func/log_setting")=="1")
						{
							echo "<input type=button value='".$m_link_log_setting."'";
							echo " onclick=\"javascript:self.location.href='tools_log_setting.php'\">\n";
						}
					?>
					</div>
				</td>
			</tr>
			<tr><td class=l_tb><div id=sLog></div></td></tr>
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
