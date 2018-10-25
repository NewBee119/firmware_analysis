<?
/* vi: set sw=4 ts=4: */
$debug="0";
if($debug=="1")
{
	echo "sid=".$sid."\n";
	echo "user=".$user."\n";
	echo "password".$passwd."\n";
	echo "authnum=".query("/proc/web/authnum")."\n";
	echo "sessionum=".query("/proc/web/sessionum")."\n";
}

$prefix			="/var/proc/web/session:".$sid."/user";
$path_name		=$prefix."/name";
$path_password	=$prefix."/password";
$path_ac_auth	=$prefix."/ac_auth";
$path_group		=$prefix."/group";
$path_logout	="/var/proc/web/session_".$sid."_logout";

$sess_logout = fread($path_logout);

$AUTH_RESULT="";

// session id error
if($sid=="-1" || $sid=="" || $sid=="0")
{
	$AUTH_RESULT="full";
}
// no user name
else if($user=="")
{
	fwrite($path_logout, "0");
	fwrite($path_ac_auth,"0");
	$AUTH_RESULT="401";
}
// session logout
else if ($sess_logout==1)
{
	$AUTH_RESULT="timeout";
}
else
{
	$AUTH_RESULT="401";
	for ("/sys/user")
	{
		if ($user == query("name") && $passwd == query("password"))
		{
			$group = query("group");
			$AUTH_RESULT="";
		}
	}
	if ($AUTH_RESULT=="")
	{
		// if session full.
		$ac_auth = fread($path_ac_auth);
		if($ac_auth!="1")
		{
			$authnum=0;
			$max_authnum=query("/proc/web/authnum");
			$max_session=query("/proc/web/sessionum");
			$index=1;
			while($index<=$max_session)
			{
				if(fread("/var/proc/web/session:".$index."/user/ac_auth")=="1"){$authnum++;}
				$index++;
			}
			//echo "authnum=".$authnum."\n";
			if($authnum>=$max_authnum) {$AUTH_RESULT="full";}
		}
	}
}
if ($AUTH_RESULT=="")
{
	// update user info
	fwrite($path_name,	$user);
	fwrite($path_group,	$group);
	fwrite($path_ac_auth,	"1");
}
else
{
	fwrite($path_ac_auth,"0");
	if ($AUTH_RESULT=="401") {echo "401;\n";}
}
?>
