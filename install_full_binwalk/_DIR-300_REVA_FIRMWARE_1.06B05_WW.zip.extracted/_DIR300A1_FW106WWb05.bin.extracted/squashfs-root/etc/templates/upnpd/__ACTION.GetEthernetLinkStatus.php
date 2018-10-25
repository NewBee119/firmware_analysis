<NewEthernetLinkStatus><?

if (query("/runtime/switch/wan_port")=="0") { echo "Down"; } else { echo "Up"; }

?></NewEthernetLinkStatus>
