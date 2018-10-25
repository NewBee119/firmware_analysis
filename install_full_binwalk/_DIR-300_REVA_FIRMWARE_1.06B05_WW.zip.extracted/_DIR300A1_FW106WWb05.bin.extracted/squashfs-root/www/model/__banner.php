<?$WAN_PAGE="/bsc_wan.php";?>
<table id=header_container cellSpacing="0" cellPadding="5" width=838 align="center" border="0">
<tr>
	<td width="100%">
	<table id=banner_text cellSpacing="0">
	<tr>
		<td>
			<?=$m_product_page?>&nbsp;:&nbsp;
			<a href='<?query("/sys/supporturl");?>'  target=_blank><font class=l_tb><? query("/sys/modelname"); ?></font></a>
			&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	</table>
	</td>
	<!--
	<td noWrap align="right"><?=$m_hw_ver?>&nbsp;:&nbsp;<? query("/runtime/sys/info/hardwareversion"); ?>&nbsp;</td>
	-->
	<td noWrap align="right"><?=$m_fw_ver?>&nbsp;:&nbsp;<? query("/runtime/sys/info/firmwareversion"); ?>&nbsp;</td>
</tr>
</table>
<?require("/www/model/__logo.php");?>
