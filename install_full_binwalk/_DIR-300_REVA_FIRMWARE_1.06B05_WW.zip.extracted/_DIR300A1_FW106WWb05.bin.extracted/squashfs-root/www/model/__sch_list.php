<select name="sch_list">
	<option value="0"><?=$m_always?></option>
	<option value="-1"><?=$m_never?></option>
	<?
	for("/sys/schedule/entry")
	{
		echo "<option value='".query("id")."'>".query("name")."</option>\n";
	}
	?>
</select>
