#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script=1;

/* Get configuration */
require($template_root."/rg/flush_readconfig.php");

require($template_root."/rg/flush_firewall.php");
require($template_root."/rg/flush_main.php");
?>
