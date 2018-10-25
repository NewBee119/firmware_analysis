HTTP/1.1 500 Internal Server Error
CONTENT-TYPE: text/xml; charset="utf-8"
CONTENT-LENGTH:
EXT:

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
	<s:Body>
		<s:Fault>
			<faultcode>s:Client</faultcode>
			<faultstring>UPnPError</faultstring>
			<detail>
				<UPnPError xmlns="urn:schemas-upnp-org:control-1-0">
					<errorCode><?=$errorCode?></errorCode>
					<errorDescription><?=$errorDescription?></errorDescription>
				</UPnPError>
			</detail>
		</s:Fault>
	</s:Body>
</s:Envelope>
