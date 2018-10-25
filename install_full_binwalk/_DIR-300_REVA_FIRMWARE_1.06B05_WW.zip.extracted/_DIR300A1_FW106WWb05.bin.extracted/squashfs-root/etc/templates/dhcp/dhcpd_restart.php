#!/bin/sh
echo [$0] ... > /dev/console
<?
require("/etc/templates/troot.php");

$dhcpd_if=query("/runtime/layout/lanif");
$dhcpd_clearleases=0;

$generate_start=0;
require($template_root."/dhcp/dhcpd.php");
$generate_start=1;
require($template_root."/dhcp/dhcpd.php");
?>
