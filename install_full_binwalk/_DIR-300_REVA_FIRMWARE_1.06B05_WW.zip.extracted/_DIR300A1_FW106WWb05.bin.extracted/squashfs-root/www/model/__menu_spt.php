<!-- === BEGIN SIDENAV === -->
<ul>
<?
$link_on="sidenavoff";

if($MY_NAME=="spt_menu")
		{echo "<li><div id='".$link_on."'>".		$m_menu_spt_menu."</div></li>\n";		}
else	{echo "<li><div><a href='/spt_menu.php'>".	$m_menu_spt_menu."</a></div></li>\n";	}
?>
<li><div><a href="logout.php"><?=$m_logout?></a></div></li>
</ul>
<!-- === END SIDENAV === -->
