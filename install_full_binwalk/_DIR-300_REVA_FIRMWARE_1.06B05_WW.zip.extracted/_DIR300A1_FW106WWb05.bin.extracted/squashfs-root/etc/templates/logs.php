#!/bin/sh
echo [$0] ... > /dev/console
<?
require("/etc/templates/troot.php");
$klogd_only=0;
require($template_root."/misc/logs_run.php");
?>
