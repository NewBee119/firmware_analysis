<NewWANAccessType>Ethernet</NewWANAccessType>
<NewLayer1UpstreamMaxBitRate>100000000</NewLayer1UpstreamMaxBitRate>
<NewLayer1DownstreamMaxBitRate>100000000</NewLayer1DownstreamMaxBitRate>
<NewPhysicalLinkStatus><?

if (query("/runtime/switch/wan_port")=="0") { echo "Down"; } else { echo "Up"; }

?></NewPhysicalLinkStatus>
