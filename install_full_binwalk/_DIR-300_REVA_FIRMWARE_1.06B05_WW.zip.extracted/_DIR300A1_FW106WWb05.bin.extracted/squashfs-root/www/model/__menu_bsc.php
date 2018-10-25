<!-- === BEGIN SIDENAV === -->
<ul>
<?
$link_on="sidenavoff";

if($SUB_CATEGORY=="bsc_internet")
		{echo "<li><div id='".$link_on."'>".			$m_menu_bsc_internet.	"</div></li>\n";		}
else	{echo "<li><div><a href='/bsc_internet.php'>".	$m_menu_bsc_internet.	"</a></div></li>\n";	}

if($SUB_CATEGORY=="bsc_wlan")
		{echo "<li><div id='".$link_on."'>".			$m_menu_bsc_wlan.		"</div></li>\n";		}
else	{echo "<li><div><a href='/bsc_wlan_main.php'>".	$m_menu_bsc_wlan.		"</a></div></li>\n";	}

if($MY_NAME=="bsc_lan")
		{echo "<li><div id='".$link_on."'>".			$m_menu_bsc_lan.		"</div></li>\n";		}
else	{echo "<li><div><a href='/bsc_lan.php'>".		$m_menu_bsc_lan.		"</a></div></li>\n";	}

if($MY_NAME=="tools_time")
		{echo "<li><div id='".$link_on."'>".			$m_menu_tools_time.			"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_time.php'>".	$m_menu_tools_time.			"</a></div></li>\n";	}

if($MY_NAME=="adv_url_filter")
		{echo "<li><div id='".$link_on."'>".			$m_menu_adv_url_filter.	"</div></li>\n";		}
else	{echo "<li><div><a href='/adv_url_filter.php'>".$m_menu_adv_url_filter.	"</a></div></li>\n";	}

?>
<li><div><a href="logout.php"><?=$m_logout?></a></div></li>
</ul>
<!-- === END SIDENAV === -->
