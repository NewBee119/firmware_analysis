var result=<?
if($submit != "1")
{
	$test_path="/runtime/ddns/test/";
	set($test_path."enable", $enable);
	set($test_path."provider", $provider);
	set($test_path."host", $host);
	set($test_path."user", $user);
	set($test_path."pass", $pass);
	set($test_path."pass_dirty", $pass_dirty);
}
?>new Array("ok");
