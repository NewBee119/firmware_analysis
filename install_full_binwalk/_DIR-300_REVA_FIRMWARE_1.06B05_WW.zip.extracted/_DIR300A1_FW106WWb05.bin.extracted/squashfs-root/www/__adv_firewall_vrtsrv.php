<tr>
	<td align=middle rowspan=2><input type='checkbox' checked disabled></td>
	<td align=left valign=bottom><?=$m_name?><br><input type=text size=8 maxlength=31 value="<?
		get("h","description");
	?>" disabled></td>
	<td align=left valign=bottom><select disabled><option>WAN</option></select></td>
	<td align=center valign=bottom>*</td>
	<td align=left valign=bottom><?=$m_protocol?><br>
		<select disabled>
			<option><? map("protocol", "1","TCP", "2","UDP", *,"Any"); ?></option>
		</select>
	</td>
	<td align=middle rowspan=2>&nbsp;</td>
</tr>
<tr>
	<td align=left valign=bottom><?=$m_action?><br>
		<select disabled><option><?=$m_allow?></option></select>
	</td>
	<td align=left valign=bottom>
		<select disabled><option>LAN</option></select>
	</td>
	<td align=center valign=bottom><? query("internal/ip"); ?></td>
	<td align=left valign=bottom><?=$m_port?><br>
		<input type=text size=6 value="<? query("external/startport"); ?>" disabled>
	</td>
</tr>
