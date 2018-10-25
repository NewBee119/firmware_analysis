<script>
function apply(fn)
{
	if(fn=="")
		document.write("<input type='submit' name='apply' value=\"<?=$m_save_settings?>\">");
	else
		document.write("<input type='button' name='apply' value=\"<?=$m_save_settings?>\" onClick='"+fn+"'>");
}
function cancel(fn)
{
	if(fn=="") fn="do_cancel()";
	document.write("<input type='button' name='cancel' value=\"<?=$m_no_save_settings?>\" onClick='"+fn+"'>");
}

// button for wizard ---------------------------------------------------------
function prev(fn)
{
	if(fn=="") fn="go_prev()";
	document.write("<input type='button' name='prev' value=\"<?=$m_prev?>\" onClick='"+fn+"'>&nbsp;");
}
function next(fn)
{
	if(fn=="")
		document.write("<input type='submit' name='next' value=\"<?=$m_next?>\">&nbsp;");
	else
		document.write("<input type='button' name='next' value=\"<?=$m_next?>\" onClick='return "+fn+"'>&nbsp;");
}

function exit()
{
	document.write("<input type='button' name='exit' value=\"<?=$m_cancel?>\" onClick=\"exit_confirm('bsc_internet.php')\">&nbsp;");
}
function exit_wlan_wiz()
{
	document.write("<input type='button' name='exit' value=\"<?=$m_cancel?>\" onClick=\"exit_confirm('bsc_wlan_main.php')\">&nbsp;");
}
function exit_confirm(href)
{
	//if(confirm("<?=$a_quit_wiz?>")==true)
	self.location.href=href;
}
function wiz_save(fn)
{
	if(fn=="") fn="do_save()";
	document.write("<input type='submit' name='save' value=\"<?=$m_save?>\" onClick='"+fn+"'>&nbsp;");
	
}
function wiz_connect(fn)
{
	if(fn=="") fn="do_save()";
	document.write("<input type='button' name='save' value=\"<?=$m_connect?>\" onClick='"+fn+"'>&nbsp;");
	
}
// --------------------------------------------------------------------------
</script>
