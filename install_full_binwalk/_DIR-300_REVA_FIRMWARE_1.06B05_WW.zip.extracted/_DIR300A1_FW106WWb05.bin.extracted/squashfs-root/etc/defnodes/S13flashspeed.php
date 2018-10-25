<?
/* flash programming speed */
$flashspeed = query("/runtime/nvram/flashspeed");
if ($flashspeed == "")  { $flashspeed=1800; }
if ($flashspeed < 1000) { $flashspeed=1000; }
$flashspeed = $flashspeed * 13 / 10;
set("/runtime/sys/info/fptime", $flashspeed);
set("/runtime/sys/info/bootuptime", 50);
?>
