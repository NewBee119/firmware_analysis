<script>
function do_timeout()
{
	// logout or something else to do.
	self.location.href="/logout.php";
}
setTimeout("do_timeout()",<?map("/sys/sessiontimeout","","300");?>*1000);
</script>
