<? /* vi: set sw=4 ts=4: */ ?>
		<tr>
			<td align=middle>
				<input type=checkbox id='entry_enable_<?=$index?>' value="1" <?
				if (query("enable")=="1") {echo " checked";}?>>
			</td>
			<td valign="bottom">
				<input type="text" id='url_<?=$index?>' size="41" maxlength="40" value="<?get("h","url");?>">
			</td>
<?
if($HAS_SCHEDULE=="1")
{
	$PREFIX		="\t\t\t\t";
	$OBJID		= "schedule_".$index;
	$OBJNAME	= "schedule_".$index;
	$UNIQUEID	= query("schedule/id");

	echo "\t\t\t<td align=middle noWrap>\n";
	require("/www/__schedule_combobox.php");
	echo "\t\t\t</td>\n";
}
?>
		</tr>
