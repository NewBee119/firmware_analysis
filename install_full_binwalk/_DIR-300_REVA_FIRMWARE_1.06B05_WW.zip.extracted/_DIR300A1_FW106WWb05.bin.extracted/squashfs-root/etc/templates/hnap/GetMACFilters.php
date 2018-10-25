HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$enabled="false";
$allow="false";
if(query("/security/macfilter/enable")=="1")
{
	$enabled="true";
}
if(query("/security/macfilter/action")=="1")
{
	$allow="true";
}

?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetMACFiltersResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetMACFiltersResult>OK</GetMACFiltersResult>
      <Enabled><?=$enabled?></Enabled>
      <IsAllowList><?=$allow?></IsAllowList>
      <MACList>
<?
	for("/security/macfilter/entry")
	{
		echo "        <string>".query("sourcemac")."</string>\n";
	}
?>      </MACList>
    </GetMACFiltersResponse>
  </soap:Body>
</soap:Envelope>
