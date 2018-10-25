<? /* vi: set sw=4 ts=4: */ ?>
			<tr>
				<td align=middle>
			    	<input type=checkbox id='entry_enable_<?=$index?>' value="1" <?
					if (query("enable")=="1") {echo " checked";} ?>>
				</td>
				<td>
					<input type=text id=mac_<?=$index?> size=18 maxlength=17 value="<?get(h,"mac");?>">
				</td>
				<td>
					<input type=button id=copy_<?=$index?> value="<<" onclick="copy_mac(<?=$index?>)">
				</td>
				<td>
					<select id='dhcp_<?=$index?>'>
						<option value=0><?=$m_computer_name?></option selected>
<?
for ("/runtime/dhcpserver/lease") { echo
"						<option value=\"".query("mac")."\">".get(h,"hostname")."</option>\n";
}
?>					</select>
				</td>
<?
if ($HAS_SCHEDULE==1)
{
	$PREFIX		= "\t\t\t\t\t";
	$OBJID		= "schedule_".$index;
	$OBJNAME	= "schedule_".$index;
	$UNIQUEID	= query("schedule/id");
	echo "\t\t\t\t<td align=middle>\n";
	require("/www/__schedule_combobox.php");
	echo "\t\t\t\t</td>\n";
}
?>
			</tr>
