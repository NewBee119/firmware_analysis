<?
/* vi: set sw=4 ts=4: */
for ("/sys/user")
{
	if (query("name")!="")
	{
		echo query("name").":".query("password")."\n";
	}
}
?>
