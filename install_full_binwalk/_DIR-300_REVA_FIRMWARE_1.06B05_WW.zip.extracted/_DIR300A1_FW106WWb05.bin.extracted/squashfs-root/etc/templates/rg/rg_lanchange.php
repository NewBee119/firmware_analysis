#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

require($template_root."/rg/flush_readconfig.php");
require($template_root."/rg/flush_ipfilter.php");
require($template_root."/rg/flush_vrtsrv.php");
require($template_root."/rg/flush_urlfilter.php");
require($template_root."/rg/flush_dmz.php");
require($template_root."/rg/flush_main.php");
?>
