# flush_netsniper.php >>>
<? /* vi: set sw=4 ts=4: */
if(query("/runtime/func/netsniper") == 1){
	if ($wanif!="")	
	{ 
		$use_netsniper=query("/wan/rg/inf:1/netsniper_enable");
		$mode=query("/wan/rg/inf:1/mode");
		if($use_netsniper == 1 && $mode == 3)
		{
			/* POSTROUTING MASQUERADE */
			$use_stun = query("/runtime/func/stun/enabled");
			$stun_type= query("/runtime/func/stun/type");
			if ($use_stun==1 && $stun_type>0 && $stun_type<5) 
			{ 
				$MASQ_target = "STUN --type ".$stun_type; 
				$MASQ_port = ":1024-65535";	
			}
			else 
			{ 
				$MASQ_target = "MASQUERADE"; 
				$MASQ_port = " --to-ports 1024-65535";	
			}
			
			echo "iptables -t nat -F POSTROUTING\n";

			echo "echo 1024 > /proc/sys/net/ipv4/ip_personality_sport\n";
			echo "echo 1 > /proc/sys/net/ipv4/ip_personality_enable\n";
			
			echo "iptables -t nat -A POSTROUTING -o ".$wanif." -p tcp -j ".$MASQ_target."".$MASQ_port."\n"; 
			echo "iptables -t nat -A POSTROUTING -o ".$wanif." -p udp -j ".$MASQ_target."".$MASQ_port."\n"; 
			echo "iptables -t nat -A POSTROUTING -o ".$wanif." -j ".$MASQ_target."\n"; 
			
			echo "iptables -t mangle -I POSTROUTING -o ".$wanif." -j PERS --tweak src --conf /etc/netsniper/pers.conf\n";
/*			echo "iptables -t mangle -I FORWARD -j PERS --tweak src --conf /etc/netsniper/pers_isn.conf\n"; */
		}
		else
		{
			echo "echo 0 > /proc/sys/net/ipv4/ip_personality_sport\n";
			echo "echo 0 > /proc/sys/net/ipv4/ip_personality_enable\n";
		}
	}
	/* Add the MASQUERADE rule for other wan interface. */
	if ($wan2if!="")
	{ 
		$use_netsniper=query("/wan/rg/inf:2/netsniper_enable");
		$mode=query("/wan/rg/inf:1/mode");
		if($use_netsniper == 1 && $mode == 3)
		{
			/* POSTROUTING MASQUERADE */
			$use_stun = query("/runtime/func/stun/enabled");
			$stun_type= query("/runtime/func/stun/type");
			if ($use_stun==1 && $stun_type>0 && $stun_type<5) 
			{ 
				$MASQ_target = "STUN --type ".$stun_type; 
				$MASQ_port = ":1024-65535";	
			}
			else 
			{ 
				$MASQ_target = "MASQUERADE"; 
				$MASQ_port = " --to-ports 1024-65535";	
			}
			
			echo "iptables -t nat -F POST_ROUTING\n";
			echo "iptables -t mangle -F\n";

			echo "echo 1024 > /proc/sys/net/ipv4/ip_personality_sport\n";
			echo "iptables -t nat -A POSTROUTING -o ".$wan2if." -p tcp -j ".$MASQ_target."".$MASQ_port."\n"; 
			echo "iptables -t nat -A POSTROUTING -o ".$wan2if." -p udp -j ".$MASQ_target."".$MASQ_port."\n"; 
			echo "iptables -t nat -A POSTROUTING -o ".$wan2if." -j ".$MASQ_target."\n"; 

			echo "iptables -t mangle -I POSTROUTING -o ".$wan2if." -j PERS --tweak src --conf /etc/netsniper/pers.conf\n";
/*			echo "iptables -t mangle -I FORWARD -j PERS --tweak src --conf /etc/netsniper/pers_isn.conf\n"; */
		}
	}
}
?>
# flush_netsniper.php <<<

