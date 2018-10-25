<?
/*	require variables ...
 *	$PREFIX		- optional.
 *	$OBJID		- object id.
 *	$OBJNAME	- object name.
 *	$UNIQUEID	- schedule unique id.
 */
echo $PREFIX."<select id='".$OBJID."' name='".$OBJNAME."' onChange=\"".$ON_CHANGE_FUNC."\">\n";
echo $PREFIX."\t<option value=0>".$m_always."</option>\n";

for ("/sys/schedule/entry")
{
	$uid = query("id");
	echo $PREFIX."\t<option value=".$uid;
	if ($UNIQUEID == $uid) { echo " selected"; }
	echo ">".get("h", "description")."</option>\n";
}

echo $PREFIX."</select>\n";
if ($COMBO_ONLY != 1)
{
	echo $PREFIX."&nbsp;\n";
	echo	"<input type=\"button\" id=\"".$OBJID."_btn\" value='".$m_add_new.
			"' onclick=\"javascript:self.location.href='tools_sch.php'\">\n";
}
?>
