<? /* vi: set sw=4 ts=4: */
echo "NOTIFY ".$HDR_URL." HTTP/1.1\r\n";
echo "HOST: ".$HDR_HOST."\r\n";
echo "CONTENT-TYPE: text/xml\r\n";
echo "CONTENT-LENGTH: ".$HDR_CONTENT_LENGTH."\r\n";
echo "NT: upnp:event\r\n";
echo "NTS: upnp:propchange\r\n";
echo "SID: ".$HDR_SID."\r\n";
echo "SEQ: ".$HDR_SEQ."\r\n";
?>
