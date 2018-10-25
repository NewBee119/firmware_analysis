<?
$AUTH_RESULT="";
if($sid=="-1" || $sid=="" || $sid=="0") { $AUTH_RESULT="full"; }
$ac_auth=fread("/var/proc/web/session:".$sid."/user/ac_auth");
if($ac_auth!="1") { $AUTH_RESULT="401"; }
?>
