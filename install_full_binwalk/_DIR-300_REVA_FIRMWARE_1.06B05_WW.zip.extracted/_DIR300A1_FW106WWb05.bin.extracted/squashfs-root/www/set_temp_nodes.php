var result =<?
$i=$start;

while ($i <= $end)
{
	$d=0;
	while ($d < $data)
	{
		$data_name = "d_".$i."_".$d;
		set($TEMP_NODES."/entry:".$i."/data_".$d, $$data_name);
		$d++;
	}
	$i++;
}
?> new Array("OK", "", "");
