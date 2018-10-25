<?
$NewPortMappingIndex = query("/runtime/upnp/GetGenericPortMappingEntry/NewPortMappingIndex");
$index = $NewPortMappingIndex + 1;
anchor("/runtime/upnp/wan:".$WID."/entry:".$index);
?>
<NewRemoteHost><?		query("remoteip");	?></NewRemoteHost>
<NewExternalPort><?		query("port2");		?></NewExternalPort>
<NewProtocol><?			map("protocol","1","TCP","*","UDP"); ?></NewProtocol>
<NewInternalPort><?		query("port1");		?></NewInternalPort>
<NewInternalClient><?	query("ip");		?></NewInternalClient>
<NewEnabled><?			query("enable");	?></NewEnabled>
<NewPortMappingDescription><? query("description"); ?></NewPortMappingDescription>
<NewLeaseDuration>0</NewLeaseDuration>
