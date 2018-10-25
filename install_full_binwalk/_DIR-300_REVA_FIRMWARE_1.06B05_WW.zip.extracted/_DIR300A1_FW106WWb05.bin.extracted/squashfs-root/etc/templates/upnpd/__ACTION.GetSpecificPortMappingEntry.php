<? /* vi: set sw=4 ts=4: */
anchor("/runtime/upnp/wan:".$WID."/entry:".$target);
?><NewInternalPort><?			query("port1");		?></NewInternalPort>
<NewInternalClient><?			query("ip");		?></NewInternalClient>
<NewEnabled><?					query("enable");	?></NewEnabled>
<NewPortMappingDescription><?	query("description"); ?></NewPortMappingDescription>
<NewLeaseDuration>0</NewLeaseDuration>
