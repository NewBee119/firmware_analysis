<!-- === BEGIN SIDENAV === -->
<ul>
<?
$link_on="sidenavoff";

if($MY_NAME=="adv_port")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_port.		"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_port.php'>".		$m_menu_adv_port.		"</a></div></li>\n";	}

if($MY_NAME=="adv_app")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_app.		"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_app.php'>".		$m_menu_adv_app.		"</a></div></li>\n";	}

if($MY_NAME=="adv_mac_filter")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_mac_filter.	"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_mac_filter.php'>".$m_menu_adv_mac_filter.	"</a></div></li>\n";	}

if($MY_NAME=="adv_firewall")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_firewall.	"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_firewall.php'>".	$m_menu_adv_firewall.	"</a></div></li>\n";	}

if($MY_NAME=="adv_wlan")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_wlan.		"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_wlan.php'>".		$m_menu_adv_wlan.		"</a></div></li>\n";	}

if($MY_NAME=="adv_network")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_network.	"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_network.php'>".	$m_menu_adv_network.	"</a></div></li>\n";	}

if( query("/runtime/func/dis_routing") != "1" )
{
if($MY_NAME=="adv_routing")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_routing.	"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_routing.php'>".	$m_menu_adv_routing.	"</a></div></li>\n";	}
}

if( query("/runtime/func/dis_qos") != "1" )
{
if($MY_NAME=="adv_qos")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_qos.		"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_qos.php'>".		$m_menu_adv_qos.		"</a></div></li>\n";	}
}

?>
<li><div><a href="logout.php"><?=$m_logout?></a></div></li>
</ul>
<!-- === END SIDENAV === -->
