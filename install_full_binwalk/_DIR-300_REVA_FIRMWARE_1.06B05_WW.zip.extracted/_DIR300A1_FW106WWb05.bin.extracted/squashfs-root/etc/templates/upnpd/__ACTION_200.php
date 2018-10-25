HTTP/1.1 200 OK
CONTENT-TYPE: text/xml; charset="utf-8"
CONTENT-LENGTH:
EXT:

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
	<s:Body>
		<u:<?=$ACTION_NAME?>Response xmlns:u="<?=$SERVICE_TYPE?>"><?
			if ($SOAP_BODY!="") { echo "\n"; require($SOAP_BODY); }
		?></u:<?=$ACTION_NAME?>Response>
	</s:Body>
</s:Envelope>
