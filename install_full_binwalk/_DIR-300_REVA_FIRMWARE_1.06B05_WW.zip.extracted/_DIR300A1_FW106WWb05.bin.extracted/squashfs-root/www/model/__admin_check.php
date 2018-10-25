<?
require("/www/model/__auth_check.php");
if ($AUTH_GROUP!="0") {require("/www/permission_deny.php");exit;}
?>
