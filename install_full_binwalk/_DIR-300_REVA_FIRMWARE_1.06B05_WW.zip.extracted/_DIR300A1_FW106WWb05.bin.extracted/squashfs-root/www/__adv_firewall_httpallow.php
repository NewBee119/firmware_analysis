<tr>
	<td align=middle rowspan=2><input type='checkbox' checked disabled></td>
	<td align=left valign=bottom><?=$m_name?><br>
		<input type=text size=8 maxlength=31 disabled value="<?=$m_remote_management?>">
	</td>
	<td align=left valign=bottom><select disabled><option>WAN</option></select></td>
	<td align=center valign=bottom><? map("/security/firewall/httpremoteip", "", "*"); ?></td>
	<td align=left valign=bottom><?=$m_protocol?><br>
		<select disabled><option>TCP</option></select>
	</td>
	<td align=middle rowspan=2>&nbsp;</td>
</tr>
<tr>
	<td align=left valign=bottom><?=$m_action?><br><select disabled><option><?=$m_allow?></option></select></td>
	<td align=left valign=bottom><select disabled><option>LAN</option></select></td>
	<td align=center valign=bottom><? query("/lan/ethernet/ip"); ?></td>
	<td align=left valign=bottom><?=$m_port?><br>
		<input type=text size=6 value="<? query("/security/firewall/httpremoteport"); ?>" disabled>
	</td>
</tr>
