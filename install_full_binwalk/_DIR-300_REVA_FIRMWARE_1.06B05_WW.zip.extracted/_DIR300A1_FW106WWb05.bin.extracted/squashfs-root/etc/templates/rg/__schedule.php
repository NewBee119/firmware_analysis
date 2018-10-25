<?
$TIMESTRING = "";
for ("/sys/schedule/entry")
{
	if ($UNIQUEID==query("id"))
	{
		$COMMA = ""; $DAYS = "";
		if (query("sun")==1) { $DAYS=$DAYS.$COMMA."Sun"; $COMMA=","; }
		if (query("mon")==1) { $DAYS=$DAYS.$COMMA."Mon"; $COMMA=","; }
		if (query("tue")==1) { $DAYS=$DAYS.$COMMA."Tue"; $COMMA=","; }
		if (query("wed")==1) { $DAYS=$DAYS.$COMMA."Wed"; $COMMA=","; }
		if (query("thu")==1) { $DAYS=$DAYS.$COMMA."Thu"; $COMMA=","; }
		if (query("fri")==1) { $DAYS=$DAYS.$COMMA."Fri"; $COMMA=","; }
		if (query("sat")==1) { $DAYS=$DAYS.$COMMA."Sat"; $COMMA=","; }
		$START	= query("starttime");
		$END	= query("endtime");
		if ($START!="" && $END!="" && $DAYS!="")
		{ $TIMESTRING = " -m time --timestart ".$START." --timestop ".$END." --days ".$DAYS; }
	}
}
?>
