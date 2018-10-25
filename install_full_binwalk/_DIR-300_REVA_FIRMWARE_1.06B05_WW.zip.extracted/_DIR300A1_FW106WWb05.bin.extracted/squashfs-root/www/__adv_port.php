<? /* vi: set sw=4 ts=4: */ ?>
		<tr>
	        <td align=middle rowspan="2">
		    	<input type=checkbox id='enable_<?=$index?>' value="1" <?
				if (query("enable")=="1") {echo " checked";}
				?>>
		    </td>
		    <td valign="bottom"><?=$m_name?><br>
			    <input type="text" id='name_<?=$index?>' size="16" maxlength="31" value="">
				<script>get_obj("name_<?=$index?>").value = "<?get("j","description");?>";</script>
		    </td>
		    <td align=left valign="bottom">
				<input id='copy_app_<?=$index?>' type=button value="<<" class="button" onClick='copy_application(<?=$index?>)'>
				<select id='app_<?=$index?>' style="width:120">
					<option><?=$m_app_name?></option selected>
					<option value='FTP'>FTP</option>
					<option value='HTTP'>HTTP</option>
					<option value='HTTPS'>HTTPS</option>
					<option value='DNS'>DNS</option>
					<option value='SMTP'>SMTP</option>
					<option value='POP3'>POP3</option>
					<option value='Telnet'>Telnet</option>
					<option value='IPSec'>IPSec</option>
					<option value='PPTP'>PPTP</option>
					<!--option value='NetMeeting'>NetMeeting</option-->
					<option value='DCS-1000'>DCS-1000</option>
					<option value='DCS-2000/DCS-5300'>DCS-2000/DCS-5300</option>
					<option value='i2eye'>i2eye</option>
				</select>
			</td>
			<td align=middle valign="bottom"><div align="left"><?=$m_public_port?><br>
				<input type="text" id='start_port_<?=$index?>' size=5 maxlength="5" value='<?
					$sport = query("external/startport");
					if($sport!="") {echo $sport;}
				?>' onchange=fill_priv_port("pub_start",<?=$index?>)>
				~
				<input type="text" id='end_port_<?=$index?>' size=5 maxlength="5" value='<?
					$port = query("external/endport");
					if ($port=="")	{ query("external/startport"); }
					else			{ echo $port; }
				?>' onchange=fill_priv_port("pub_end",<?=$index?>)>
			</div></td>
			<td align=middle rowspan="2"><? $prot = query("protocol"); ?>
				<select id='protocol_<?=$index?>'>
					<option value=1<? if ($prot == 1) { echo " selected";}?>>TCP</option>
					<option value=2<? if ($prot == 2) { echo " selected";}?>>UDP</option>
					<option value=0<? if ($prot != 1 && $prot != 2) { echo " selected";}?>><?=$m_any?></option>
				</select>
			</td>
<?
if($HAS_SCHEDULE=="1")
{
	$PREFIX		="\t\t\t\t";
	$OBJID		= "schedule_".$index;
	$OBJNAME	= "schedule_".$index;
	$UNIQUEID	= query("schedule/id");

	echo "\t\t\t<td align=middle rowspan=\"2\">\n";
	require("/www/__schedule_combobox.php");
	echo "\t\t\t</td>\n";
}
?>
		</tr>
		<tr>
			<td valign="bottom"><?=$m_ip?>
				<input type="text" id='ip_<?=$index?>' size="16" maxlength="15" value='<?
					$ipaddr = query("internal/ip");
					if($ipaddr!="" && $ipaddr!="0.0.0.0") {echo $ipaddr;}
				?>'>
			</td>
			<td align=left valign="bottom">
			<input id="copy_ip_<?=$index?>" type=button value="<<" class="button" onClick="copy_ip(<?=$index?>)">
				<select id="ip_list_<?=$index?>" style="width:120">
				<option><?=$m_pc_name?></option selected>
				<?
					for("/runtime/dhcpserver/lease")
					{
						$tempname=get(h,"hostname");
						$tempip=query("ip");
						echo "<option value=".$tempip.">".$tempname."</option>";
					}
				?>
				</select>
			</td>
			<td align=middle valign="bottom"><div align="left"><?=$m_private_port?><br>
				<input type="text" id='priv_sport_<?=$index?>' size=5 maxlength="5" value='<?
					$sport = query("internal/startport");
					if($sport!="") {echo $sport;}
				?>' onchange=fill_priv_port("priv_start",<?=$index?>)>
				~
				<input type="text" id='priv_eport_<?=$index?>' size=5 maxlength="5" value='<?
					$port = query("internal/endport");
					if ($port=="")	{ query("internal/startport"); }
					else			{ echo $port; }
				?>' disabled>
			</div></td>
		</tr>
