#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

/* detect interface */
$WANDEV = query("/runtime/wan/inf:1/interface");
$LANDEV = query("/runtime/layout/lanif");
echo "echo -n Interface is wanif=".$WANDEV." lanif=".$LANDEV." \n";
if ( $WANDEV=="" || $LANDEV=="" ){ echo "echo ... Error!!! \n";exit; }else{ echo "echo ... OK \n"; }

/* tc debug */
$TC="echo tc";
$TC="tc";
$K=kbps;
$K=kbit;

/* main */
if ($generate_start==1)
{
	echo "echo Start QOS system ... \n";
	
	/* process node */
	$QOS_ENABLE  = query("/qos/mode");
	$UPSTREAM    = query("/qos/bandwidth/upstream");
	$DOWNSTREAM  = query("/qos/bandwidth/downstream");
	echo "echo QOS=".$QOS_ENABLE." UPSTREAM=".$UPSTREAM." DOWNSTREAM=".$DOWNSTREAM." \n";
	if ( $DOWNSTREAM==0 || $DOWNSTREAM=="" ){ $DOWNSTREAM=102400; }
	if ( $UPSTREAM==0   || $UPSTREAM==""   ){ $UPSTREAM=102400; }
	if ( $QOS_ENABLE!=1 && $QOS_ENABLE!=2 ){ echo "echo QOS is disabled. \n";exit; }

	/*----------------------------------------------------------------------------*/
	$PRIO_ALL=$UPSTREAM ;
	$PRIO0_MAX=$PRIO_ALL * 90 / 100 ;
	$PRIO1_MAX=$PRIO_ALL * 80 / 100 ;
	$PRIO2_MAX=$PRIO_ALL * 75 / 100 ;
	$PRIO3_MAX=$PRIO_ALL * 70 / 100 ;
	$PRIO0_MIN=$PRIO_ALL * 70 / 100 ;
	$PRIO1_MIN=$PRIO_ALL * 50 / 100 ;
	$PRIO2_MIN=$PRIO_ALL * 40 / 100 ;
	$PRIO3_MIN=$PRIO_ALL * 30 / 100 ;

	echo $TC." qdisc add dev ".$WANDEV." handle 1: root htb default 3 \n";
	echo $TC." class add dev ".$WANDEV." parent 1:0 classid 1:1 htb rate ".$PRIO_ALL.$K." burst 100k cburst 100k \n";
	echo $TC." class add dev ".$WANDEV." parent 1:1 classid 1:40 htb prio 0  rate ".$PRIO0_MIN.$K." ceil ".$PRIO0_MAX.$K." burst 50k cburst 50k \n";
	echo $TC." class add dev ".$WANDEV." parent 1:1 classid 1:41 htb prio 3  rate ".$PRIO1_MIN.$K." ceil ".$PRIO1_MAX.$K." burst 50k cburst 50k \n";
	echo $TC." class add dev ".$WANDEV." parent 1:1 classid 1:42 htb prio 5  rate ".$PRIO2_MIN.$K." ceil ".$PRIO2_MAX.$K." \n";
	echo $TC." class add dev ".$WANDEV." parent 1:1 classid 1:43 htb prio 7  rate ".$PRIO3_MIN.$K." ceil ".$PRIO3_MAX.$K." \n";
	echo $TC." qdisc add dev ".$WANDEV." parent 1:40 handle 400: pfifo limit 50 \n";
	echo $TC." qdisc add dev ".$WANDEV." parent 1:41 handle 410: pfifo limit 40 \n";
	echo $TC." qdisc add dev ".$WANDEV." parent 1:42 handle 420: pfifo limit 30 \n";
	echo $TC." qdisc add dev ".$WANDEV." parent 1:43 handle 430: pfifo limit 10 \n";

	$PRIO_ALL=$DOWNSTREAM ;
	$PRIO0_MAX=$PRIO_ALL * 90 / 100 ;
	$PRIO1_MAX=$PRIO_ALL * 80 / 100 ;
	$PRIO2_MAX=$PRIO_ALL * 75 / 100 ;
	$PRIO3_MAX=$PRIO_ALL * 70 / 100 ;
	$PRIO0_MIN=$PRIO_ALL * 70 / 100 ;
	$PRIO1_MIN=$PRIO_ALL * 50 / 100 ;
	$PRIO2_MIN=$PRIO_ALL * 40 / 100 ;
	$PRIO3_MIN=$PRIO_ALL * 30 / 100 ;

	echo $TC." qdisc add dev ".$LANDEV." handle 2: root htb default 3 \n";
	echo $TC." class add dev ".$LANDEV." parent 2:0 classid 2:1 htb rate ".$PRIO_ALL.$K." burst 100k cburst 100k \n";
	echo $TC." class add dev ".$LANDEV." parent 2:1 classid 2:40 htb prio 0  rate ".$PRIO0_MIN.$K." ceil ".$PRIO0_MAX.$K." burst 50k cburst 50k \n";
	echo $TC." class add dev ".$LANDEV." parent 2:1 classid 2:41 htb prio 3  rate ".$PRIO1_MIN.$K." ceil ".$PRIO1_MAX.$K." burst 50k cburst 50k \n";
	echo $TC." class add dev ".$LANDEV." parent 2:1 classid 2:42 htb prio 5  rate ".$PRIO2_MIN.$K." ceil ".$PRIO2_MAX.$K." \n";
	echo $TC." class add dev ".$LANDEV." parent 2:1 classid 2:43 htb prio 7  rate ".$PRIO3_MIN.$K." ceil ".$PRIO3_MAX.$K." \n";
	echo $TC." qdisc add dev ".$LANDEV." parent 2:40 handle 400: pfifo limit 50 \n";
	echo $TC." qdisc add dev ".$LANDEV." parent 2:41 handle 410: pfifo limit 40 \n";
	echo $TC." qdisc add dev ".$LANDEV." parent 2:42 handle 420: pfifo limit 30 \n";
	echo $TC." qdisc add dev ".$LANDEV." parent 2:43 handle 430: pfifo limit 10 \n";
	/*----------------------------------------------------------------------------*/

	/*----------------------------------------------------------------------------*/
	echo .$TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x00 0xE0 flowid 1:40 \n";
	echo .$TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x00 0xE0 flowid 2:40 \n";

	echo .$TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x80 0xE0 flowid 1:41 \n";
	echo .$TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x80 0xE0 flowid 2:41 \n";
	echo .$TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x40 0xE0 flowid 1:42 \n";
	echo .$TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x40 0xE0 flowid 2:42 \n";
	echo .$TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x20 0xE0 flowid 1:43 \n";
	echo .$TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x20 0xE0 flowid 2:43 \n";
	/*----------------------------------------------------------------------------*/

	/*----------------------------------------------------------------------------*/
	echo .$TC." filter add dev ".$WANDEV." parent 1: protocol ip prio 100 u32  match ip src 0.0.0.0/0 flowid 1:43 \n";
	echo .$TC." filter add dev ".$LANDEV." parent 2: protocol ip prio 100 u32  match ip dst 0.0.0.0/0 flowid 2:43 \n";
	/*----------------------------------------------------------------------------*/

	/*-----------------------------------------------------*/
	echo "echo ".$QOS_ENABLE." ".$UPSTREAM." ".$DOWNSTREAM." > /proc/fastnat/qos \n";
	echo "echo -1 > /proc/fastnat/formqossupport \n";
	/*-----------------------------------------------------*/

	
	/*-----------------------------------------------------*/
	for("/qos/entry")
	{
		$ENABLE		= query("enable");
		$STARTIP	= query("startip");
		$ENDIP		= query("endip");
		$STARTPORT	= query("startport");
		$ENDPORT	= query("endport");
		$PRIORITY	= query("priority");

		/* 
		echo "echo  ENABLE=$ENABLE STARTIP=$STARTIP ENDIP=$ENDIP STARTPORT=$STARTPORT ENDPORT=$ENDPORT PRIORITY=$PRIORITY \n";
		*/

		if ( $ENABLE == 1 )
		{
		if( $STARTIP == "*" ){ $STARTIP="0.0.0.0"; }
		if( $STARTPORT == "*"  ||  $ENDPORT == "*" ){ $STARTPORT=0;$ENDPORT=0; }

		echo "echo ".$STARTIP.":".$STARTPORT."-".$ENDPORT.":".$PRIORITY." > /proc/fastnat/formqossupport \n";
		}

	}
	/*-----------------------------------------------------*/

}
else
{
	echo "echo Stop QOS system ... \n";
	echo .$TC." qdisc del dev ".$WANDEV." root \n";
	echo .$TC." qdisc del dev ".$LANDEV." root \n";
	echo "echo 0 > /proc/fastnat/qos \n";
}

?>
