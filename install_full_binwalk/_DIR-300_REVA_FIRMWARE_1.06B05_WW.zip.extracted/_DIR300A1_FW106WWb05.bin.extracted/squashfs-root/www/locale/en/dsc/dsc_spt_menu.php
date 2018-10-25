<?
$spt_bsc_url	= "spt_bsc.php";
$spt_adv_url	= "spt_adv.php";
$spt_tools_url	= "spt_tools.php";
$spt_st_url		= "spt_st.php";
$spt_faq_url	= "spt_faq.php";
$font_color		= "#000000";
?>
<h1>SUPPORT MENU</h1>
<table width=75% border=0 cellspacing=0 cellpadding=0>
<tr><td><span class="style6"><?=$m_menu_top_bsc?></span></td></tr>
<tr><td>
	<ul>
	<li><a href=<?=$spt_bsc_url?>#01 target=_blank><font color=<?=$font_color?>><?=$m_menu_bsc_internet?></font></a></li>
	<li><a href=<?=$spt_bsc_url?>#02 target=_blank><font color=<?=$font_color?>><?=$m_menu_bsc_wlan?></font></a></li>
	<li><a href=<?=$spt_bsc_url?>#03 target=_blank><font color=<?=$font_color?>><?=$m_menu_bsc_lan?></font></a></li>
	<li><a href=<?=$spt_bsc_url?>#04 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_time?></font></a></li>
	<li><a href=<?=$spt_bsc_url?>#05 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_url_filter?></font></a></li>
	</ul>
</td></tr>
<tr><td><span class="style6"><?=$m_menu_top_adv?></span></td></tr>
<tr><td>
	<ul>
	<li><a href=<?=$spt_adv_url?>#05 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_port?></font></a></li>
	<li><a href=<?=$spt_adv_url?>#06 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_app?></font></a></li>
	<li><a href=<?=$spt_adv_url?>#07 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_mac_filter?></font></a></li>
	<li><a href=<?=$spt_adv_url?>#08 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_firewall?></font></a></li>
	<li><a href=<?=$spt_adv_url?>#09 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_wlan?></font></a></li>
	<li><a href=<?=$spt_adv_url?>#10 target=_blank><font color=<?=$font_color?>><?=$m_menu_adv_network?></font></a></li>
	<?
	if( query("/runtime/func/dis_routing") != "1" )
	{
	echo "<li><a href=";
	echo $spt_adv_url;
	echo "#11 target=_blank><font color=";
	echo $font_color.">".$m_menu_adv_routing."</font></a></li>\n";
	}
	?>
	</ul>
</td></tr>
<tr><td><span class="style6"><?=$m_menu_top_tools?></span></td></tr>
<tr><td>
	<ul>
	<li><a href=<?=$spt_tools_url?>#12 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_admin?></font></a></li>
	<li><a href=<?=$spt_tools_url?>#14 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_system?></font></a></li>
	<li><a href=<?=$spt_tools_url?>#15 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_firmware?></font></a></li>
	<li><a href=<?=$spt_tools_url?>#16 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_ddns?></font></a></li>
	<li><a href=<?=$spt_tools_url?>#17 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_vct?></font></a></li>
	<li><a href=<?=$spt_tools_url?>#18 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_sch?></font></a></li>
	<li><a href=<?=$spt_tools_url?>#19 target=_blank><font color=<?=$font_color?>><?=$m_menu_tools_log_setting?></font></a></li>
	</ul>
</td></tr>
<tr><td><span class="style6"><?=$m_menu_top_st?></span></td></tr>
<tr><td>
	<ul>
	<li><a href=<?=$spt_st_url?>#20 target=_blank><font color=<?=$font_color?>><?=$m_menu_st_device?></font></a></li>
	<li><a href=<?=$spt_st_url?>#21 target=_blank><font color=<?=$font_color?>><?=$m_menu_st_log?></font></a></li>
	<li><a href=<?=$spt_st_url?>#22 target=_blank><font color=<?=$font_color?>><?=$m_menu_st_stats?></font></a></li>
	<li><a href=<?=$spt_st_url?>#23 target=_blank><font color=<?=$font_color?>><?=$m_menu_st_session?></font></a></li>
	<li><a href=<?=$spt_st_url?>#24 target=_blank><font color=<?=$font_color?>><?=$m_menu_st_wlan?></font></a></li>
	</ul>
</td></tr>
</table>
