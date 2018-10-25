<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=middle align=center>
	<td>
	<br>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table width=90%>
	<tr>
		<td id="box_header">
			<h1><?=$m_context_title?></h1><br><br>
			<center>
			<?
			if($REQUIRE_FILE == "var/etc/httpasswd" || $REQUIRE_FILE == "var/etc/hnapasswd")
			{
				echo "<title>404 Not Found</title>\n";
				echo "<h1>404 Not Found</h1>\n";
			}
			else
			{
				if($REQUIRE_FILE!="")
				{
					require($LOCALE_PATH."/".$REQUIRE_FILE);
				}
				else
				{
					echo $m_context;
					echo $m_context2;//jana added
					if($m_context_next!="")
					{
						echo $m_context_next;
					}
					echo "<br><br><br>\n";
					if($USE_BUTTON=="1")
					{echo "<input type=button name='bt' value='".$m_button_dsc."' onclick='click_bt();'>\n"; }
				}
			}
			?>
			</center>
			<br>
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
