<!-- === BEGIN SIDENAV === -->
<ul>
<?
$link_on="sidenavoff";

if($MY_NAME=="tools_admin")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_admin.		"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_admin.php'>".		$m_menu_tools_admin.		"</a></div></li>\n";	}

/*
if($MY_NAME=="tools_time")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_time.			"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_time.php'>".		$m_menu_tools_time.			"</a></div></li>\n";	}
*/

if($MY_NAME=="tools_system")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_system.		"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_system.php'>".		$m_menu_tools_system.		"</a></div></li>\n";	}

if($MY_NAME=="tools_firmware")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_firmware.		"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_firmware.php'>".	$m_menu_tools_firmware.		"</a></div></li>\n";	}

if($MY_NAME=="tools_ddns")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_ddns.			"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_ddns.php'>".		$m_menu_tools_ddns.			"</a></div></li>\n";	}

if($MY_NAME=="tools_vct")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_vct.			"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_vct.php'>".			$m_menu_tools_vct.			"</a></div></li>\n";	}

if($MY_NAME=="tools_sch")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_sch.			"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_sch.php'>".			$m_menu_tools_sch.			"</a></div></li>\n";	}

if($MY_NAME=="tools_log_setting")
		{echo "<li><div id='".$link_on."'>".				$m_menu_tools_log_setting.	"</div></li>\n";		}
else	{echo "<li><div><a href='/tools_log_setting.php'>".	$m_menu_tools_log_setting.	"</a></div></li>\n";	}
?>
<li><div><a href="logout.php"><?=$m_logout?></a></div></li>
</ul>
<!-- === END SIDENAV === -->
