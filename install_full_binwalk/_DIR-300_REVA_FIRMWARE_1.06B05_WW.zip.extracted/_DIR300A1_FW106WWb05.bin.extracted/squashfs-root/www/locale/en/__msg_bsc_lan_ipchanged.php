<p>
The LAN IP address of this device is changing.<br><br>
It needs several seconds for the changes to take effect.<br><br>
You may need to change the IP address of your computer to access the device.<br><br>
You can access the device by clicking the link below.
</p>
<br>
<?
$lan_ip=query("/lan/ethernet/ip");
$lan_url="http:\/\/".$lan_ip;
?>
<a href="<?=$lan_url?>"><?=$lan_url?></a>
<br><br>
