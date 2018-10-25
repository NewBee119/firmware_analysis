# flush_arp_attack.php >>>
<?
/* vi: set sw=4 ts=4:
 */
$arp_attack_count	= 0;
$arp_attack_enable	= query("/security/attack/arp");
if ($arp_attack_enable==1)
{
	echo "echo 5 > /proc/sys/net/ipv4/arp_flood_burst\n";
	echo "echo 20 > /proc/sys/net/ipv4/arp_flood_cost\n";
	$arp_attack_count++;
}
else
{
	echo "echo 0 > /proc/sys/net/ipv4/arp_flood_burst\n";
	echo "echo 0 > /proc/sys/net/ipv4/arp_flood_cost\n";
	$arp_attack_count--;
}

set("/runtime/rgfunc/arp_attack", $arp_attack_count);
?>
# flush_arp_attack.php <<<
