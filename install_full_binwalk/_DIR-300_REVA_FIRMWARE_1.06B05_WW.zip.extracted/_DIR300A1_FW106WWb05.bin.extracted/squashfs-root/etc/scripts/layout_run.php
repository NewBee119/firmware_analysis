#!/bin/sh
echo [$0] $1 ... > /dev/console
<?
/* vi: set sw=4 ts=4:

	PORT	Switch Port	VID
	====	===========	===
	CPU		PORT5		0,2
	WAN		PORT4		2
	LAN1	PORT3		0
	LAN2	PORT2		0
	LAN3	PORT1		0
	LAN4	PORT0		0

	PHY 29, MII 23
	==============
	PHY 29, MII 23[15:11]	- add VLAN tag for port [4-0]
	PHY 29, MII 23[10:6]	- remove VLAN tag for Port [4-0]
	PHY 29, MII 23[1]		- add VLAN tag for port 5
	PHY 29, MII 23[0]		- remove VLAN tag for port 5

	Remove tag for port 0~4, add tag for port 5.
    bit		5432 1098 7654 3210
    value	0000 0111 1100 0010 = 0x07c2

	PHY 30, MII 9
	=============
    bit   2 1098 7654 3210
    value 1 0000 1000 1001 = 0x1089
    [12:8] Set Port [4-0] as WAN port
    [7] Enable tag VLAN function
    [3] Enable router function
    [2:0] LAN group number

NOTE:	We use VLAN 2 for WAN port, VLAN 0 for LAN ports.
		by David Hsieh <david_hsieh@alphanetworks.com>
*/
require("/etc/templates/troot.php");
$mii_dev = "/proc/driver/ae531x";
$bridge = query("/bridge");
if ($bridge!=1)	{ $router_on = 1; }
else			{ $router_on = 0; }

if ($router_on==1)
{
	if ($generate_start==1)
	{
		echo "echo Start router layout ...\n";
		if (query("/runtime/router/enable")==1)
		{
			echo "echo Already in router mode!\n";
			exit;
		}
		echo "echo \"WRITE 29 24 0\"    > ".$mii_dev."\n";	/* PORT0 Default VLAN ID */
		echo "echo \"WRITE 29 25 0\"    > ".$mii_dev."\n";	/* PORT1 Default VLAN ID */
		echo "echo \"WRITE 29 26 0\"    > ".$mii_dev."\n";	/* PORT2 Default VLAN ID */
		echo "echo \"WRITE 29 27 0\"    > ".$mii_dev."\n";	/* PORT3 Default VLAN ID */
		echo "echo \"WRITE 29 28 2\"    > ".$mii_dev."\n";	/* PORT4 Default VLAN ID */
		echo "echo \"WRITE 29 30 0\"    > ".$mii_dev."\n";	/* PORT5 Default VLAN ID (CPU) */
		echo "echo \"WRITE 29 23 07c2\" > ".$mii_dev."\n";
		echo "echo \"WRITE 30 1 002f\"  > ".$mii_dev."\n";	/* Port 5,3,2,1,0 = VLAN 0 */
		echo "echo \"WRITE 30 2 0030\"  > ".$mii_dev."\n";	/* Port 5,4 = VLAN 2 */
		echo "echo \"WRITE 30 3 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN 4,5 */
		echo "echo \"WRITE 30 4 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN 6,7 */
		echo "echo \"WRITE 30 5 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN 8,9 */
		echo "echo \"WRITE 30 6 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN a,b */
		echo "echo \"WRITE 30 7 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN c,d */
		echo "echo \"WRITE 30 8 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN e,f */
		echo "echo \"WRITE 30 9 1089\"  > ".$mii_dev."\n";
		echo "ifconfig eth0.0 up\n";
		echo "ifconfig eth0.2 up\n";
		echo "brctl addif br0 ath0\n";
		echo "brctl addif br0 eth0.0\n";
		echo "brctl setbwctrl br0 ath0 900\n";
		echo "ifconfig br0 up\n";
		echo "rgdb -i -s /runtime/router/enable 1\n";
	}
	else
	{
		echo "brctl delif br0 eth0.0\n";
		echo "brctl delif br0 ath0\n";
		echo "ifconfig eth0.2 down\n";
		echo "ifconfig eth0.0 down\n";
		echo "rgdb -i -s /runtime/router/enable \"\"\n";
	}
}
else
{
	if ($generate_start==1)
	{
		echo "echo Start bridge layout ...\n";
		if (query("/runtime/router/enable")==0)
		{
			echo "echo Already in bridge mode!\n";
			exit;
		}
		echo "echo \"WRITE 29 24 0\"    > ".$mii_dev."\n";	/* PORT0 Default VLAN ID */
		echo "echo \"WRITE 29 25 0\"    > ".$mii_dev."\n";	/* PORT1 Default VLAN ID */
		echo "echo \"WRITE 29 26 0\"    > ".$mii_dev."\n";	/* PORT2 Default VLAN ID */
		echo "echo \"WRITE 29 27 0\"    > ".$mii_dev."\n";	/* PORT3 Default VLAN ID */
		echo "echo \"WRITE 29 28 0\"    > ".$mii_dev."\n";
		echo "echo \"WRITE 29 30 0\"    > ".$mii_dev."\n";	/* PORT5 Default VLAN ID (CPU) */
		echo "echo \"WRITE 29 23 07c2\" > ".$mii_dev."\n";
		echo "echo \"WRITE 30 1 003f\"  > ".$mii_dev."\n";	/* Port 5,4,3,2,1,0 = VLAN 0 */
		echo "echo \"WRITE 30 2 0020\"  > ".$mii_dev."\n";	/* Port 5 = VLAN 2 */
		echo "echo \"WRITE 30 3 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN 4,5 */
		echo "echo \"WRITE 30 4 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN 6,7 */
		echo "echo \"WRITE 30 5 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN 8,9 */
		echo "echo \"WRITE 30 6 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN a,b */
		echo "echo \"WRITE 30 7 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN c,d */
		echo "echo \"WRITE 30 8 0000\"  > ".$mii_dev."\n";  /* donot belong to VLAN e,f */
		echo "echo \"WRITE 30 9 0089\"  > ".$mii_dev."\n";
		echo "ifconfig eth0.0 up\n";
		echo "brctl addif br0 eth0.0\n";
		echo "brctl addif br0 ath0\n";
		echo "brctl setbwctrl br0 ath0 900\n";
		echo "ifconfig br0 up\n";
		echo "rgdb -i -s /runtime/router/enable 0\n";
	}
	else
	{
		echo "echo Stop bridge layout ...\n";
		echo "brctl delif br0 ath0\n";
		echo "brctl delif br0 eth0.0\n";
		echo "ifconfig eth0.0 down\n";
		echo "rgdb -i -s /runtime/router/enable \"\"\n";
	}
}
?>
