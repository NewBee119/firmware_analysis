HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$status = get("x","/runtime/wan/inf:1/connectstatus");
if( $status == "" || $status == "disconnected" )
{ $statusStr = "DISCONNECTED"; }
elseif( $status == "connecting" )
{ $statusStr = "CONNECTING"; }
elseif( $status == "connected" )
{ $statusStr = "CONNECTED"; }
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<GetWanStatusResponse xmlns="http://purenetworks.com/HNAP1/">
<GetWanStatusResult>OK</GetWanStatusResult>	
<Status><?=$statusStr?></Status>
</GetWanStatusResponse>
</soap:Body></soap:Envelope>
