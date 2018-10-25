<h1>WAN Acceleration<?
$appex_ver=fread("/proc/net/appex/version");
if($appex_ver == "") { $appex_ver=query("/apx/version"); }
if($appex_ver != "") { echo " (ver. ".$appex_ver.")"; }
?></h1>
<p>
These options are for users to configure AppEx WAN acceleration settings.
</p>
