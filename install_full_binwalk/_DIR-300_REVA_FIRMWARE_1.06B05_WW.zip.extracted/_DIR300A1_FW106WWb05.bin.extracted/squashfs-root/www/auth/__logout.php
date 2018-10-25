<?
if(query("/runtime/web_debug")=="1"){echo "session id=".$SESSION_ID;}
if($SESSION_ID>0)
{
	unlink("/var/proc/web/session:".$SESSION_ID."/user/ac_auth");
	unlink("/var/proc/web/session:".$SESSION_ID."/ip");
	unlink("/var/proc/web/session:".$SESSION_ID."/time");
}
if(query("/runtime/web_debug")=="1"){echo "ip=".fread("/var/proc/web/session:".$SESSION_ID."/ip");}
?>
