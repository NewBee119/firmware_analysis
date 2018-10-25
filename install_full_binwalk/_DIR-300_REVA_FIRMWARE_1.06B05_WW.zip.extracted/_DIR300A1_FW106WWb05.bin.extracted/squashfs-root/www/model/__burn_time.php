<? /* vi: set sw=4 ts=4: */
$fptime		= query("/runtime/sys/info/fptime");
$bootuptime	= query("/runtime/sys/info/bootuptime");
if ($fptime == "")		{$fptime=9300;}
if ($bootuptime == "")	{$bootuptime="60";}
?>
<script>
function get_burn_time(size)
{
	var burn_time, countdown;
	var bsize = parseInt(size,[10]);

	burn_time = parseInt((bsize+63)/64,[10]) * <?=$fptime?>;
	burn_time = parseInt((burn_time+999)/1000,[10]);
	countdown = burn_time+<?=$bootuptime?>+20;

	return countdown;
}
</script>
