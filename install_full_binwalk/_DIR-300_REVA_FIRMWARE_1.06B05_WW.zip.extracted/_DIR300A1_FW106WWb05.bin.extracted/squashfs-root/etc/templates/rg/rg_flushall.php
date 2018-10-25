#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script=1;

/* Get configuration */
require($template_root."/rg/flush_readconfig.php");

require($template_root."/rg/flush_blocking.php");
require($template_root."/rg/flush_dmz.php");
require($template_root."/rg/flush_firewall.php");
require($template_root."/rg/flush_ipfilter.php");
require($template_root."/rg/flush_macfilter.php");
require($template_root."/rg/flush_misc.php");
require($template_root."/rg/flush_passthrough.php");
require($template_root."/rg/flush_portt.php");
require($template_root."/rg/flush_vrtsrv.php");
require($template_root."/rg/flush_main.php");
?>

