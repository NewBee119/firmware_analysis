<?
/* vi: set sw=4 ts=4: */
$href_on="topnavon";
$href_off="topnavoff";
?>
<table bgcolor="#ffffff" cellSpacing="1" cellPadding="2" width="838" align="center" border="0">
<tr id="topnav_container">
	<td><img src="pic/model.gif" width="125" height="25"></td>

	<td id=<?if($CATEGORY=="bsc"){echo $href_on;}	else{echo $href_off;}?>>
	<a href="/bsc_internet.php"><?=$m_menu_top_bsc?></a></td>

	<td id=<?if($CATEGORY=="adv"){echo $href_on;}	else{echo $href_off;}?>>
	<a href="/adv_port.php"><?=$m_menu_top_adv?></a></td>

	<td id=<?if($CATEGORY=="tools"){echo $href_on;}	else{echo $href_off;}?>>
	<a href="/tools_admin.php"><?=$m_menu_top_tools?></a></td>

	<td id=<?if($CATEGORY=="st"){echo $href_on;}	else{echo $href_off;}?>>
	<a href="/st_device.php"><?=$m_menu_top_st?></a></td>

	<td id=<?if($CATEGORY=="spt"){echo $href_on;}	else{echo $href_off;}?>>
	<a href="/spt_menu.php"><?=$m_menu_top_spt?></a></td>
</tr>
</table>
