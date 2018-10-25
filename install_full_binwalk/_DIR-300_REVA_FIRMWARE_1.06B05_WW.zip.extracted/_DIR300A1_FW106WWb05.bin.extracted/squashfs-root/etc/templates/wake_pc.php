#!/bin/sh
echo [$0] ... > /dev/console
<?
$wakeIp = get("s", "/wol/ip");
$wakeMac = get("s", "/wol/mac");
if( $wakeMac == "" ) { $wakeMac = "ff:ff:ff:ff:ff:ff"; }
$wakeHost = get("s", "/wol/host");
$lanIf = get("s", "/runtime/layout/lanif");
if( $lanif == "" ) { $lanif = "br0"; }
echo "eth_wake -i \"".$lanIf."\" -m \"".$wakeMac."\" -v >/dev/console\n";
?>
