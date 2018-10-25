<?
if ($ROUTER_ON==1) { $Type="IP_Routed"; } else { $Type="IP_Bridged"; }
?><NewConnectionType><?=$Type?></NewConnectionType>
<NewPossibleConnectionTypes><?=$Type?></NewPossibleConnectionTypes>
