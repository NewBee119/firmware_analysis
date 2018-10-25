<?
$curr_time = query("/runtime/sys/uptime");
$count = 0;
for ("subscription") { $count++; }
while ($count > 0)
{
	if (query("subscription:".$count."/timeout") < $curr_time)
	{
		del("subscription:".$count);
	}
	$count--;
}
?>
