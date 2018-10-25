<? /* vi: set sw=4 ts=4: */ ?>
		<tr>
			<td align=middle rowspan="2">
				<input type=checkbox id='fw_enable_<?=$index?>' value="1" <?
				if (query("enable")=="1") {echo " checked";} ?>>
			</td>
			<td align=left valign="bottom"><?=$m_name?>
				<input type="text" id='fw_description_<?=$index?>' size=8 maxlength="31" value="">
				<script>get_obj("fw_description_<?=$index?>").value = "<?get("j","description");?>";</script>
			</td>
			<td align=left valign="bottom"><? $src_inf = query("src/inf"); ?>
				<select id='src_inf_<?=$index?>'>
					<option value="0"<? if ($src_inf != 1 && $src_inf != 2) { echo " selected";}?>>Source</option>
					<option value="1"<? if ($src_inf == 1) { echo " selected";}?>>LAN</option>
					<option value="2"<? if ($src_inf == 2) { echo " selected";}?>>WAN</option>
				</select>
			</td>
			<td align=middle valign="bottom">
				<input type=text id="src_startip_<?=$index?>" maxlength=15 size=16 
				value=<?query("src/startip");?>>
				<input type=text id="src_endip_<?=$index?>" maxlength=15 size=16
				value=<?query("src/endip");?>>
			</td>
			<td align=left valign="bottom"><?=$m_protocol?><? $fw_pro = query("protocol"); ?>
				<select id='fw_pro_<?=$index?>' onchange="chg_fw_pro(<?=$index?>)">
					<option value="1"<? if ($fw_pro == 1) { echo " selected";}?>>ALL</option>
					<option value="2"<? if ($fw_pro != 1 && $fw_pro != 3 && $fw_pro != 4) { echo " selected";}?>>TCP</option>
					<option value="3"<? if ($fw_pro == 3) { echo " selected";}?>>UDP</option>
					<option value="4"<? if ($fw_pro == 4) { echo " selected";}?>>ICMP</option>
				</select>
			</td>
<?
if($HAS_SCHEDULE=="1")
{
	$PREFIX		="\t\t\t\t";
	$OBJID		= "schedule_".$index;
	$OBJNAME	= "schedule_".$index;
	$UNIQUEID	= query("schedule/id");

	echo "\t\t\t<td align=left valign=middle rowspan=\"2\">\n";
	require("/www/__schedule_combobox.php");
	echo "\t\t\t</td>\n";
}
?>
		</tr>
		<tr>
			<td align=left valign="bottom"><?=$m_action?><br><? $fw_action = query("action"); ?>
				<select id="fw_action_<?=$index?>">
					<option value="1"<? if ($fw_action != 0) { echo " selected";}?>><?=$m_allow?></option>
					<option value="0"<? if ($fw_action == 0) { echo " selected";}?>><?=$m_deny?></option>
				</select>
			</td>
			<td align=left valign="bottom"><? $dst_inf = query("dst/inf"); ?>
				<select id='dst_inf_<?=$index?>' >
					<option value="0"<? if ($dst_inf != 1 && $dst_inf != 2) { echo " selected";}?>>Dest</option>
					<option value="1"<? if ($dst_inf == 1) { echo " selected";}?>>LAN</option>
					<option value="2"<? if ($dst_inf == 2) { echo " selected";}?>>WAN</option>
				</select>
			</td>
			<td align=middle valign="bottom">
				<input type=text id="dst_startip_<?=$index?>" maxlength=15 size=16 
				value=<?query("dst/startip");?>>
				<input type=text id="dst_endip_<?=$index?>" maxlength=15 size=16
				value=<?query("dst/endip");?>>
			</td>
			<td align=left valign="bottom"><?=$m_port_range?><br>
				<? $dst_startport = query("dst/startport"); ?>
				<? $dst_endport = query("dst/endport"); ?>
				<input type=text id="dst_startport_<?=$index?>" maxlength=5 size=6 
				<?
					if ($fw_pro=="2" || $fw_pro=="3"){echo " value='".$dst_startport."'";} 
					if ($fw_pro=="1" || $fw_pro=="4"){echo " disabled";} 
				?>>
				<input type=text id="dst_endport_<?=$index?>" maxlength=5 size=6
				<?
					if ($fw_pro=="2" || $fw_pro=="3"){echo " value='".$dst_endport."'";} 
					if ($fw_pro=="1" || $fw_pro=="4"){echo " disabled";} 
				?>>
			</td>
		
		</tr>
