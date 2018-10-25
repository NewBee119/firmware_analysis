<? /* vi: set sw=4 ts=4: */ ?>
			<tr>
				<td align=middle rowspan=2>
					<input type=checkbox id='enable_<?=$index?>' value='1'<?
					if (query("enable")=="1") {echo " checked";} ?>>
				</td>
				<td rowspan=2 align=middle>
					<input type=text id='desc_<?=$index?>' size=15 maxlength=31 value="<? get("h","description"); ?>">
					<script>get_obj("desc_<?=$index?>").value = "<? get("j","description"); ?>";</script>
				</td>
				<td rowspan=2 align=left>
					<input id='copy_app_<?=$index?>' type=button value="<<" class=button onClick='copy_application(<?=$index?>)'>
					<select id='app_<?=$index?>' style='width:120'>
						<option><?=$m_app_name?></option selected>
						<option value='Battle.net'>Battle.net</option>
						<option value='Dialpad'>Dialpad</option>
						<option value='ICU II'>ICU II</option>
						<option value='MSN Gaming Zone'>MSN Gaming Zone</option>
						<option value='PC-to-Phone'>PC-to-Phone</option>
						<option value='Quick Time 4'>Quick Time 4</option>
					</select>
				</td>
				<td align=middle valign=bottom><div align=left><?=$m_trigger?>
					<input type=text id='trigger_port_<?=$index?>' size=20 maxlength=11 value='<?

						query("trigger/startport");
						if (query("trigger/endport") != "") { echo "-".query("trigger/endport"); }

					?>'></div>
					<input type=hidden id='triggerstart_<?=$index?>' value=''>
					<input type=hidden id='triggerend_<?=$index?>' value=''>
				</td>
				<td align=middle valign=bottom><? $prot = query("trigger/protocol"); ?>
					<select id='trigger_prot_<?=$index?>'>
						<option value=1<? if ($prot == 1) { echo " selected";}?>>TCP</option>
						<option value=2<? if ($prot == 2) { echo " selected";}?>>UDP</option>
						<option value=0<? if ($prot != 1 && $prot != 2) { echo " selected";}?>><?=$m_any?></option>
					</select>
				</td>
<?
if ($HAS_SCHEDULE==1)
{
	$PREFIX		= "\t\t\t\t\t";
	$OBJID		= "schedule_".$index;
	$OBJNAME	= "schedule_".$index;
	$UNIQUEID	= query("schedule/id");
	echo "\t\t\t\t<td align=middle rowspan=\"2\">\n";
	require("/www/__schedule_combobox.php");
	echo "\t\t\t\t</td>\n";
}
?>
			</tr>
			<tr>
				<td height=10 align=middle valign=bottom><div align=left><?=$m_firewall?>
					<input type=text id='pub_port_<?=$index?>' size=20 maxlength=60 value='<?
					query("external/portlist"); ?>'>
				</div></td>
				<td align=middle valign=bottom><? $prot = query("external/protocol"); ?>
					<select id='pub_prot_<?=$index?>'>
						<option value=1<? if ($prot == 1) { echo " selected"; }?>>TCP</option>
						<option value=2<? if ($prot == 2) { echo " selected"; }?>>UDP</option>
						<option value=0<? if ($prot != 1 && $prot != 2) { echo " selected";}?>><?=$m_any?></option>
					</select>
				</td>
			</tr>
