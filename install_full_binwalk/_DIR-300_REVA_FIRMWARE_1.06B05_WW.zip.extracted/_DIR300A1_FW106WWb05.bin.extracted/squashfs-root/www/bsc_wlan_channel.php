<?
$regdomain	=query("/sys/regdomain");
$ccode		=query("/runtime/nvram/countrycode");
if($regdomain!="")
{
	if($regdomain=="fcc")		{$ch_start=1;	$ch_end=11;}
	else if($regdomain=="etsi")	{$ch_start=1;	$ch_end=13;}
	else if($regdomain=="spain")	{$ch_start=10;	$ch_end=11;}
	else if($regdomain=="france")	{$ch_start=10;	$ch_end=13;}
	else if($regdomain=="mkk")	{$ch_start=1;	$ch_end=14;}
	else if($regdomain=="israel")	{$ch_start=3;	$ch_end=9;}
	else				{$ch_start=1;	$ch_end=11;}
}
else
{
	if($ccode=="840" || $ccode=="US")		{$ch_start=1;	$ch_end=11;}
	else if($ccode=="826"|| $ccode=="GB")	{$ch_start=1;	$ch_end=13;}
	else									{$ch_start=1;	$ch_end=11;}
}
$INDEX=$ch_start;
while($INDEX<=$ch_end)
{
	echo "<option value='".$INDEX."'>".$INDEX."</option>\n";
	$INDEX++;
}
?>
