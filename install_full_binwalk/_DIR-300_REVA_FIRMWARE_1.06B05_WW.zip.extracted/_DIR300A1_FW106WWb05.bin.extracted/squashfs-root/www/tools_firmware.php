<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="tools_firmware";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="tools";

$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");

$CURR_BUILD_NO = query("/runtime/sys/info/firmwarebuildno");

$HW_VER	= query("/sys/hwversion");
$FW_VER	= fread("/etc/config/buildver");
?>

<script>

var AjaxReq = null;
var Fw_Request = "";
var Lang_Request = "";
var Fw_Req_Cnt = 0;
var Lang_Req_Cnt = 0;

function display_fw_check_error()
{
	get_obj("message").innerHTML = "<?=$m_no_new_fw?>";
	get_obj("bt_check").disabled = false;
}

function display_latest_fw_info()
{
	get_obj("message").innerHTML = "<p><?=$m_has_new_fw?></p>";
	get_obj("latest_fm").style.display = "block";
	get_obj("bt_check").disabled = false;
}

function display_lang_check_error()
{
	// Display nothing but latest fw info
	display_latest_fw_info();
}

function send_fw_check_req1()
{
	if(AjaxReq!=null)
		delete AjaxReq;
	AjaxReq = null;
	if(Fw_Req_Cnt<3)
	{
		if(Fw_Req_Cnt==0)
		{
			// Get data from server and save into var/fwinfo.xml
			var check_url = "/tools_firmware.xgi?set/runtime/sys/check_fw=";
			var fwver = "<?=$FW_VER?>";
			var hwstr = "<?=$HW_VER?>";
			var hwver = "Ax";

			// Get sw ver
			fwstr = fwver.split(".");
			fwver = "0" + fwstr[0] + fwstr[1]; //0112

			// Get hw revision
			for(i=0; i<hwstr.length; i++)
			{
				char_code = hwstr.charAt(i);
				if ((char_code >= 'a' && char_code <= 'z') ||
					(char_code >= 'A' && char_code <= 'Z'))
				{
					hwver=char_code.toUpperCase()+"x";
					break;
				}
			}

			// Get Data Address
			Fw_Request = check_url+hwver+"_Default_FW_"+fwver+"&date="+generate_random_str();
		}
		Fw_Req_Cnt++;
		AjaxReq = createRequest();
		AjaxReq.open("GET", Fw_Request, true);
		AjaxReq.onreadystatechange = send_fw_check_req2;
		AjaxReq.send(null);
	}
	else
	{
		display_fw_check_error();
	}
}

function send_fw_check_req2()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if (AjaxReq.status == 200)
		{
			delete AjaxReq;

			// Read data from fwinfo.xml
			var address = "/var/fwinfo.xml?date="+generate_random_str();
			AjaxReq = createRequest();
			AjaxReq.open("GET", address, true);
			AjaxReq.onreadystatechange = update_fw_info;
			AjaxReq.send(null);
		}
		else
		{
			delete AjaxReq;
			AjaxReq = null;
			send_fw_check_req1();	// Try again
		}
	}
}

function send_lang_check_req1()
{
	if(AjaxReq!=null)
		delete AjaxReq;
	AjaxReq = null;
	if(Lang_Req_Cnt<3)
	{
		if(Lang_Req_Cnt==0)
		{
			var check_url = "/tools_firmware.xgi?set/runtime/sys/check_fw=";
			var fwver = "<?=$FW_VER?>";
			var hwstr = "<?=$HW_VER?>";
			var hwver = "Ax";
			var LangCode = "EN";

			// Get sw ver
			fwstr = fwver.split(".");
			fwver = "0" + fwstr[0] + fwstr[1]; //0112

			// Get hw revision
			for(i=0; i<hwstr.length; i++)
			{
				char_code = hwstr.charAt(i);
				if ((char_code >= 'a' && char_code <= 'z') ||
					(char_code >= 'A' && char_code <= 'Z'))
				{
					hwver=char_code.toUpperCase()+"x";
					break;
				}
			}

			switch("<?=$LANGCODE?>")
			{
				case "de":
					LangCode="DE";
					break;

				case "fr":
					LangCode="FR";
					break;

				case "ko":
					LangCode="KR";
					break;

				case "zhcn":
					LangCode="CN";
					break;

				case "zhtw":
					LangCode="TW";
					break;

				case "en":
				default:
					break;
			}

			// Get Data Address
			Lang_Request = check_url+hwver+"_"+LangCode+"_FW_"+fwver+"&date="+generate_random_str();
		}
		Lang_Req_Cnt++;
		AjaxReq = createRequest();
		AjaxReq.open("GET", Lang_Request, true);
		AjaxReq.onreadystatechange = send_lang_check_req2;
		AjaxReq.send(null);
	}
	else
	{
		display_lang_check_error();
	}
}

function send_lang_check_req2()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if (AjaxReq.status == 200)
		{
			delete AjaxReq;
			// Read data from fwinfo.xml
			var address = "/var/fwinfo.xml?date="+generate_random_str();
			AjaxReq = createRequest();
			AjaxReq.open("GET", address, true);
			AjaxReq.onreadystatechange = update_lang_info;
			AjaxReq.send(null);
		}
		else
		{
			delete AjaxReq;
			AjaxReq = null;
			send_lang_check_req1();	// Try again
		}
	}
}

function check_firmware()
{
	get_obj("message").innerHTML = "<?=$m_connecting?> ...";
	if(get_obj("latest_fm").style.display != "none")
		get_obj("latest_fm").style.display = "none";
	get_obj("latest_fm_lang").style.display = "none";
	get_obj("latest_fm_langdiv").style.display = "none";
	get_obj("bt_check").disabled = true;

	Fw_Req_Cnt = 0;
	Lang_Req_Cnt = 0;
	send_fw_check_req1();
}

function update_fw_info()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if (AjaxReq.status == 200)
		{
			if (AjaxReq.responseText.length <= 1) /* No data */
			{
				//-----try again
				delete AjaxReq;
				AjaxReq = null;
				send_fw_check_req1();
			}
			else
			{
				var xmlDoc = AjaxReq.responseXML;
				/* Parse xml */
				var xmlFirmwareVerMajor = xmlDoc.getElementsByTagName("Major")[0].childNodes[0].nodeValue;
				var xmlFirmwareVerMinor = xmlDoc.getElementsByTagName("Minor")[0].childNodes[0].nodeValue;
				var fwver = "<?=$FW_VER?>";
				var cfw_maj=0, cfw_min=0;
				var fw_maj=parseInt(xmlFirmwareVerMajor, [10]);
				var fw_min=parseInt(xmlFirmwareVerMinor, [10]);

				var xmlFirmwareDate = xmlDoc.getElementsByTagName("Date")[0].childNodes[0].nodeValue;
				var i=1, j=0;
				var ie = is_IE();
				var Loc_list=get_obj("fw_dl_locs");

				// Get sw ver
				fwstr  = fwver.split(".");
				cfw_maj = parseInt(fwstr[0], [10]);
				cfw_min = parseInt(fwstr[1], [10]);

				if((fw_maj < cfw_maj) || (fw_maj == cfw_maj && fw_min <= cfw_min))
				{
					get_obj("message").innerHTML = "<?=$m_no_new_fw?>";
					return;
				}

				while(Loc_list.firstChild != null) /* check whether select box has member inside, if yes remove them */
					Loc_list.removeChild(Loc_list.firstChild);

				if (ie) /* browser is IE */
				{
					for (i=1; (xmlDoc.getElementsByTagName("Download_Site")[0].lastChild != xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i-2]); ++i)
					{
						var xmlDownloadLocation = xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i-1].nodeName;
						var xmlFirmwareDownload = xmlDoc.getElementsByTagName("Firmware")[i-1].childNodes[0].nodeValue;
						var opt = document.createElement("option");
						opt.text = xmlDownloadLocation;
						opt.value = xmlFirmwareDownload;
						Loc_list.options.add(opt);
					}
				}
				else /* besides IE */
				{
					do
					{
						var xmlDownloadLocation = xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i].nodeName;
						var xmlFirmwareDownload = xmlDoc.getElementsByTagName("Firmware")[j].childNodes[0].nodeValue;
						var opt = document.createElement("option");
						opt.text = xmlDownloadLocation;
						opt.value = xmlFirmwareDownload;
						Loc_list.appendChild(opt);
						i+=2, j++;
					} while ((xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i-1].nextSibling) != null)
				}

				/* Put xml data to html */
				var serverFirmwareVer = ("v"+ xmlFirmwareVerMajor.substring(1) + "." + xmlFirmwareVerMinor);

				get_obj("latest_fw_ver").innerHTML	= serverFirmwareVer;
				get_obj("latest_fw_date").innerHTML	= xmlFirmwareDate;

				// Next, check the language pack
				delete AjaxReq;
				AjaxReq = null;
				send_lang_check_req1();
			}
		}
		else
		{
			//-----try again
			delete AjaxReq;
			AjaxReq = null;
			send_fw_check_req1();
		}
	}
}

function update_lang_info()
{
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if (AjaxReq.status == 200)
		{
			if (AjaxReq.responseText.length <= 1) /* No data */
			{
				//-----try again
				delete AjaxReq;
				AjaxReq = null;
				send_lang_check_req1();
			}
			else
			{
				var xmlDoc = AjaxReq.responseXML;
				/* Parse xml */
				var xmlFirmwareVerMajor = xmlDoc.getElementsByTagName("Major")[0].childNodes[0].nodeValue;
				var xmlFirmwareVerMinor = xmlDoc.getElementsByTagName("Minor")[0].childNodes[0].nodeValue;
				var fwver = "<?=$FW_VER?>";
				var cfw_maj=0, cfw_min=0;
				var fw_maj=parseInt(xmlFirmwareVerMajor, [10]);
				var fw_min=parseInt(xmlFirmwareVerMinor, [10]);

				var xmlFirmwareDate = xmlDoc.getElementsByTagName("Date")[0].childNodes[0].nodeValue;
				var i=1, j=0;
				var ie = is_IE();
				var Loc_list=get_obj("lang_dl_locs");

				// Get sw ver
				fwstr  = fwver.split(".");
				cfw_maj = parseInt(fwstr[0], [10]);
				cfw_min = parseInt(fwstr[1], [10]);

				if((fw_maj > cfw_maj) || (fw_maj == cfw_maj && fw_min > cfw_min))
				{ /* TODO: has new language pack */ }
				else
				{ /* TODO: no new language pack */ }

				while(Loc_list.firstChild != null) /* check whether select box has member inside, if yes remove them */
					Loc_list.removeChild(Loc_list.firstChild);

				if (ie) /* browser is IE */
				{
					for (i=1; (xmlDoc.getElementsByTagName("Download_Site")[0].lastChild != xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i-2]); ++i)
					{
						var xmlDownloadLocation = xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i-1].nodeName;
						var xmlFirmwareDownload = xmlDoc.getElementsByTagName("Firmware")[i-1].childNodes[0].nodeValue;
						var opt = document.createElement("option");
						opt.text = xmlDownloadLocation;
						opt.value = xmlFirmwareDownload;
						Loc_list.options.add(opt);
					}
				}
				else /* besides IE */
				{
					do
					{
						var xmlDownloadLocation = xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i].nodeName;
						var xmlFirmwareDownload = xmlDoc.getElementsByTagName("Firmware")[j].childNodes[0].nodeValue;
						var opt = document.createElement("option");
						opt.text = xmlDownloadLocation;
						opt.value = xmlFirmwareDownload;
						Loc_list.appendChild(opt);
						i+=2, j++;
					} while ((xmlDoc.getElementsByTagName("Download_Site")[0].childNodes[i-1].nextSibling) != null)
				}

				/* Put xml data to html */
				var serverFirmwareVer = ("v"+ xmlFirmwareVerMajor.substring(1) + "." + xmlFirmwareVerMinor);

				get_obj("latest_lang_ver").innerHTML	= serverFirmwareVer;
				get_obj("latest_lang_date").innerHTML	= xmlFirmwareDate;
				get_obj("latest_fm_lang").style.display = "block";
				get_obj("latest_fm_langdiv").style.display = "block";
				display_latest_fw_info();
				delete AjaxReq;
				AjaxReq = null;
			}
		}
		else
		{
			//-----try again
			delete AjaxReq;
			AjaxReq = null;
			send_lang_check_req1();
		}
	}
}

function download_fw()
{
	var downloadSelect = get_obj("fw_dl_locs");
	var selectBox = downloadSelect.selectedIndex;
	var path = downloadSelect.options[selectBox].value;
	self.location.href=path;
}

function download_lang()
{
	var downloadSelect = get_obj("lang_dl_locs");
	var selectBox = downloadSelect.selectedIndex;
	var path = downloadSelect.options[selectBox].value;
	self.location.href=path;
}

var chk_number=0;
function chk_num(num)
{
  if(num==1)
  chk_number=1;
  if(num==2)
  chk_number=2;
}

/* page init functoin */
function init(){}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(chk_number==1)
	{ 
	   f.langpack.disabled=true;
	   if(is_blank(f.firmware.value))
	   {
	     f.langpack.disabled=false;
	     alert("<?=$a_blank_fw_file?>");
 	     f.firmware.focus();
	     return false;
	   }
	}
	if(chk_number==2)
	{
	   f.firmware.disabled=true;
	   if(is_blank(f.langpack.value))
	   {
	     f.firmware.disabled=false;
	     alert("<?=$a_blank_lp_file?>");
	     f.langpack.focus();
	     return false;
	   }
	}
	<?
	if($AUTH_GROUP!="0")
	{
		echo "self.location.href='permission_deny.php?NEXT_LINK=".$MY_NAME.".php';\n";
	}
	else
	{
		echo "return true;\n";
	}
	?>
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="upload_image._int" enctype=multipart/form-data onsubmit="return check();">
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
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box">
			<h2><?=$m_fw_title?></h2>
			<table width=500>
			<tr>
				<td class=br_tb width=230><?=$m_cur_fw_ver?> :</td>
				<td class=l_tb width=355><?query("/runtime/sys/info/firmwareversion");?></td>
			</tr>
			<tr>
				<td class=br_tb width=230><?=$m_cur_fw_date?> :</td>
				<td class=l_tb width=355><?query("/runtime/sys/info/firmwarebuildate");?></td>
			</tr>
			<tr><td height=20 colspan=2></td></tr>
			</table>
			<table width=500>
			<tr>
				<td class=br_tb width=325><?=$m_check_last_fw?> :</td>
				<td><input type="button" id="bt_check" onclick="check_firmware();" value="<?=$m_bt_check_fw?>"></td>
			</tr>
			<tr>
				<td class=bc_tb colspan=2><div id="message"></div></td>
			</tr>
			</table>
		</div>
		<div id="latest_fm" class="box" style="display: none">
			<h2><?=$m_last_fw_title?></h2>
			<table>
			<tr>
				<td class=br_tb width=230><?=$m_last_fw_ver?> :  </td>
				<td class=l_tb width=355 id=latest_fw_ver></td>
			</tr>
			<tr>
				<td class=br_tb width=230><?=$m_last_fw_date?> :  </td>
				<td class=l_tb id=latest_fw_date></td>
			</tr>
			<tr>
				<td class=br_tb width=230><?=$m_fw_dl_site?> :  </td>
				<td class=l_tb>
					<select id=fw_dl_locs size=1></select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="button" value="<?=$m_download?>" onclick="download_fw();">
				</td>
			</tr>
			</table>
			<table id="latest_fm_lang" style="display: none">
			<tr>
				<td class=br_tb width=230><?=$m_last_lang_ver?> :  </td>
				<td class=l_tb width=355 id=latest_lang_ver></td>
			</tr>
			<tr>
				<td class=br_tb width=230><?=$m_last_lang_date?> :  </td>
				<td class=l_tb id=latest_lang_date></td>
			</tr>
			<tr>
				<td class=br_tb width=230><?=$m_lang_dl_site?> :  </td>
				<td class=l_tb>
					<select id=lang_dl_locs size=1></select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="button" value="<?=$m_download?>" onclick="download_lang();">
				</td>
			</tr>
			</table>
			<div id=latest_fm_langdiv class=bl_tb><br><?=$m_lang_dl_hint?></div>
		</div>
		<div class="box">
			<h2><?=$m_upgrade_fw_title?></h2>
			<div class=bl_tb>
			<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME."_fw_upgrade.php"); ?>
			</div>
			<table>
			<tr>
				<td class=bc_tb><?=$m_upload?> : </td>
				<td><input type=file name=firmware size=30></td>
			</tr>
			<tr>
				<td class=bc_tb></td>
				<td><input type="submit" name="apply_fw" value="<?=$m_upload?>" onclick="chk_num(1);"></td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?=$m_upgrade_lp_title?></h2>
			<table>
			<tr>
				<td class=bc_tb><?=$m_upload?> : </td>
				<td><input type=file name=langpack size=30></td>
			</tr>
			<tr>
				<td class=bc_tb></td>
				<td><input type="submit" name="apply_lp" value="<?=$m_upload?>" onclick="chk_num(2);"></td>
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
