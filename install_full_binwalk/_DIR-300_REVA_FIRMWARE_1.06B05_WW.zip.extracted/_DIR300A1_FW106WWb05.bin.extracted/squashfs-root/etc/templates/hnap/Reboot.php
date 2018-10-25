HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$result = "REBOOT";
fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] > /dev/console\n");
fwrite2($ShellPath, "/etc/templates/wan.sh stop > /dev/console\n");
fwrite2($ShellPath, "sleep 3 > /dev/console\n");
fwrite2($ShellPath, "reboot > /dev/console\n");
set("/runtime/hnap/dev_status", "ERROR");
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <RebootResponse xmlns="http://purenetworks.com/HNAP1/">
      <RebootResult><?=$result?></RebootResult>
    </RebootResponse>
  </soap:Body>
</soap:Envelope>
