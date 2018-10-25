<h1>SUPPORT MENU</h1>
	<table border=0 cellspacing=0 cellpadding=0 width=750 height=1103>
 <tr>
 <td width="750" height=40><font size=4><b>Advanced</b></font></td>
 </tr>
 <tr>
 <td height=20>&nbsp;</td>

 </tr>
<tr>
	<td height=228>
		<p>
			<a name=05><strong>Port Forwarding </strong></a><br>
			The Port Forwarding option gives Internet users access to services on your LAN. This
			feature is useful for hosting online services such as FTP, Web or game servers. For each entry,
			you define a public port on your router for redirection to an internal LAN IP Address and LAN port.
		</p>
		<p>
			Port Forwarding Parameters<br>
			<strong><em>Name</em></strong> -
			Assign a meaningful name to the virtual server, for example Web Server. Several well-known types of virtual
			server are available from the &quot;Application Name&quot; drop-down list. Selecting one of these entries
			fills some of the remaining parameters with standard values for that type of server.<br>
			<strong><em>IP Address </em></strong> -
			The IP address of the system on your internal network that will provide the virtual service, for example
			192.168.0.50. You can select a computer from the list of DHCP clients in the &quot;Computer Name&quot;
			drop-down menu, or you can manually enter the IP address of the server computer.<br>
			<strong><em>Application Name </em></strong> -
			A list of pre-defined popular applications that users can choose from for faster configuration.<br>
			<strong><em>Computer Name </em></strong> - A list of DHCP clients.<br>
			<strong><em>Traffic Type</em></strong> -
			Select the protocol used by the service. The common choices -- UDP, TCP and both UDP and TCP -- can
			be selected from the drop-down menu. To specify any other protocol, select &quot;Other&quot; from the list,
			then enter the corresponding protocol number (as assigned by the IANA) in the Protocol box.<br>
			<strong><em>Private Port</em></strong> -
			The port that will be used on your internal network.<br>
			<strong><em>Public Port</em></strong> -
			The port that will be accessed from the Internet.<br>
		</p>
	</td>
</tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
 <tr>
 <td height=20>&nbsp;</td>

 </tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
 <tr>
 <td height=64> <strong>Application Rules<font size=4><b><a name=06></a></b></font> </strong><br>
Some applications require multiple connections, such as Internet gaming, video conferencing, Internet telephony and others. These applications have difficulties working through NAT (Network Address Translation). If you need to run applications that require multiple connections, specify the port normally associated with an application in the &quot;Trigger Port&quot; field, select the protocol type as TCP (Transmission Control Protocol) or UDP (User Datagram Protocol), then enter the public ports associated with the trigger port in the Firewall Port field to open them for inbound traffic. There are already defined well-known applications in the Application Name drop-down menu. <br>

<strong><em>Name </em></strong> - This is the name referencing the application. <br>
<strong><em>Trigger Port </em></strong>- This is the port used to trigger the application. It can be either a single port or a range of ports. <br>
<strong><em>Traffic Type </em></strong> - This is the protocol used to trigger the application. <br>
<strong><em>Firewall Port </em></strong> - This is the port number on the WAN side that will be used to access the application. You may define a single port or a range of ports. You can use a comma to add multiple ports or port ranges. <br>
<strong><em>Traffic Type </em></strong> - This is the protocol used for the application. </td>

 </tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
<tr>
	<td height=99>
		<p>
			<a name=07><strong>Access control</strong></a><br>
			Filters are used to deny or allow LAN computers from accessing the Internet and also your network.
			Within your network, the unit can be setup to allow or deny Internet access to computers using their MAC addresses.
		</p>
		<p>
			<strong>MAC Filters</strong><br>
			Use MAC Filters to deny computers within the local area network from accessing the Internet.
			You can either manually add a MAC address or select the MAC address from the list of clients
			that are currently connected to the unit.<br>
			Select &quot;Turn MAC Filtering ON and ALLOW computers with MAC address listed below to access
			the network&quot; if you only want selected computers to have network access and all other computers
			not to have network access.<br>
			Select &quot;Turn MAC Filtering ON and DENY computers with MAC addresses listed below to access the
			network&quot; if you want all computers to have network access except those computers in the list.<br>
			<strong><em>MAC Address</em></strong> -
			The MAC address of the network device to be added to the MAC Filter List.<br>
			<strong><em>DHCP Client List</em></strong> -
			DHCP clients will have their hostname listed in the Computer Name drop-down menu. You can select
			the client computer you want to add to the MAC Filter List and click the arrow button. This will
			automatically add that computer's MAC address to the appropriate field.
		</p>
		<p>
			Users can use the <strong>Always</strong> drop-down menu to select a previously defined schedule
			or click the <strong>New Schedule</strong> button to add a new schedule.
		</p>
		<p>
			The check box is used to enable or disable a particular entry.
		</p>
	</td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
<tr>
	<td height=20>
		<p>
			<a name=08><strong>Firewall & DMZ</strong></a><br>
			The Firewall Settings section contains an option to configure a DMZ Host.
		</p>
		<p>
			<strong>Enable SPI</strong><br>
			SPI (&quot;stateful packet inspection&quot; also known as &quot;dynamic packet filtering&quot;) helps
			to prevent cyber attacks by tracking more states per session. It validates that the traffic passing through
			that session conforms to the protocol. When the protocol is TCP, SPI checks that the packet sequence numbers
			are within the valid range for the session, discarding those packets that do not have valid sequence numbers.
			Whether SPI is enabled or not, the router always tracks TCP connection states and ensures that each
			TCP packet's flags are valid for the current state.
		</p>
		<p>
			<strong>DMZ </strong><br>
			If you have a computer that cannot run Internet applications properly from behind the
			<?query("/sys/modelname");?>, then you can allow the computer to have unrestricted Internet access.
			Enter the IP address of that computer as a DMZ (Demilitarized Zone) host with unrestricted Internet access.
			Adding a client to the DMZ may expose that computer to a variety of security risks; so only use this
			option as a last resort.
		</p>
		<p>
			<strong>Firewall Rules</strong><br>
			Firewall Rules are used to allow or deny traffic coming in to or going out of the router based
			on the source and destination IP addresses as well as the traffic type and the specific port
			the data runs on.<br>
			<strong><em>Name</em></strong> - Users can specify a name for a Firewall Rule.<br>
			<strong><em>Action</em></strong> - Users can choose to allow or deny traffic.<br>
			<strong>Interface</strong><br>
			<strong><em>Source</em></strong> -
			Use the <strong>Source</strong> drop-down menu to select if the starting point of the traffic that's
			to be allowed or denied is from the LAN or WAN interface.<br>
			<strong><em>Dest</em></strong> -
			Use the <strong>Dest</strong> drop-down menu to select if the ending point of the traffic
			that's to be allowed or denied is arriving on the LAN or WAN interface.<br>
			<strong><em>IP Address</em></strong> -
			Here you can specify a single source or dest IP address by entering the IP address in the top box
			or enter a range of IP addresses by entering the first IP address of the range in the top box and the
			last IP adress of the range in the bottom one.<br>
			<strong><em>Protocol</em></strong> -
			Use the <strong>Protocol</strong> drop-down menu to select the traffic type.<br>
			<strong><em>Port Range</em></strong> -
			Enter the same port number in both boxes to specify a single port or enter the first port of
			the range in the top box and last port of the range in the bottom one to specify a range of ports.<br>
			<strong><em>Schedule</em></strong> -
			Use the <strong>Always</strong> drop-down menu to select a previously defined schedule or click on
			<strong>New Schedule</strong> button to add a new schedule.
 </p>
	</div></td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
 <tr>
 <td height=20><p><strong>Advanced Wireless <a name=09></a></strong></p>

 <p>The options on this page should be changed by advanced users or if you are instructed to by one of our support personnel, as this can negatively affect the performance of your router if configured incorrectly. </p>
 <p><strong><em>Transmission (TX) Rates - </em></strong> Select the basic transfer rates based on the speed of wireless adapters on the WLAN (wireless local area network). </p>
 <p><strong><em>Transmit Power - </em></strong>You can lower the output power of the <?query("/sys/modelname");?> by selecting a lower percentage of Transmit Power values from the drop-down menu. Your choices are: 100%, 50%, 25%, and 12.5%. </p>
 <p><strong><em>Beacon Interval - </em></strong> Beacons are packets sent by an Access Point to synchronize with the wireless network. Specify a Beacon interval value between 20 and 1000. The default value is set to 100 milliseconds. </p>

 <p><strong><em>RTS Threshold - </em></strong> This value should remain at its default setting of 2346. If you encounter inconsistent data flow, only minor modifications to the value range between 256 and 2346 are recommended. The default value for RTS Threshold is set to 2346. </p>
 <p><strong><em>Fragmentation - </em></strong> This value should remain at its default setting of 2346. If you experience a high packet error rate, you may slightly increase your &quot;Fragmentation&quot; value within the value range of between 1500 and 2346. Setting the Fragmentation value too low may result in poor performance. </p>
 <p><strong><em>DTIM Interval - </em></strong> Enter a value between 1 and 255 for the Delivery Traffic Indication Message (DTIM). A DTIM is a countdown informing clients of the next window for listening to broadcast and multicast messages. When the Access Point has buffered broadcast or multicast messages for associated clients, it sends the next DTIM with a DTIM Interval value. AP clients hear the beacons and awaken to receive the broadcast and multicast messages. The default value for DTIM interval is set to 1. </p>

 <p><strong><em>Preamble Type - </em></strong> The Preamble Type defines the length of the CRC (Cyclic Redundancy Check) block for communication between the Access Point and roaming wireless adapters. Make sure you select the appropriate preamble type and click the Apply button. </p>
 <p><span class="style2">Note: </span>High network traffic areas should use the shorter preamble type. CRC is a common technique for detecting data transmission errors. </p>
 <p><strong><em>CTS Mode - </em></strong>Select None to disable this feature. Select Always to force the router to require each wireless device on the network to perform an RTS/CTS handshake before they are allowed to transmit data. Select Auto to allow the router to decide when RTS/CTS handshakes are necessary. </p>
<?if(query("/runtime/func/ieee80211n")!="1"){
 echo "<p><strong><em>802.11g Only Mode - </em></strong>Enable this mode if your network is made up of purely 802.11g devices. If you have both 802.11b and 802.11g wireless clients, disable this mode. </p> </td>";
}?>

 </tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
 <tr>
 <td height=20><p><strong>Advanced Network <a name=10></a></strong></p>

 <p>This section contains settings which can change the way the router handles certain types of traffic. We recommend that you don't change any of these settings unless you are already familiar with them or have been instructed to change them by one of our support personnel. </p>

<!--
 <p><strong>VPN Passthrough </strong><br>The device supports VPN (Virtual Private Network) passthrough for PPTP (Point-to-Point Tunneling Protocol) and IPSec (IP Security). Once VPN passthrough is enabled, there is no need to create any Virtual Server or Port Forwarding entries in order for outbound VPN sessions to be established properly. Multiple VPN connections can be made through the device. This is useful when you have many VPN clients on the Local Area Network. </p>
-->

 <p><strong>UPnP </strong><br>UPnP is short for Universal Plug and Play which is a networking architecture that provides compatibility between networking equipment, software, and peripherals. The <?query("/sys/modelname");?> is a UPnP enabled router, meaning that it will work with other UPnP devices/software. If you do not want to use the UPnP function, it can be disabled by selecting &quot;Disabled&quot;. </p>
 <p><strong>WAN Ping </strong><br>When you Enable WAN Ping respond, you are causing the public WAN (Wide Area Network) IP address on the device to respond to ping commands sent by Internet users. Pinging public WAN IP addresses is a common method used by hackers to test whether your WAN IP address is valid. </p>
 <p><strong>WAN Port Speed </strong><br>This allows you to select the speed of the WAN interface of the <?query("/sys/modelname");?>: Choose 100Mbps, 10Mbps, or 10/100/1000Mbps Auto. </p>

<!--
 <p><strong>Gaming Mode </strong><br>If you are experiencing difficulties when playing online games or even certain applications that use voice data, you may need to enable Gaming Mode in order for these applications to work correctly. When not playing games or using these voice applications, it is recommended that Gaming Mode is disabled. </p>
--> 

<?if(query("/runtime/func/dis_multicast_stream")!="1"){
echo " <p><strong>Multicast Streams</strong><br>Enable this option to allow Multicast traffic to pass from the Internet to your network more efficiently. </p> </td>\n";
}
?>
 </tr>

<?if(query("/runtime/func/dis_multicast_stream")!="1"){
echo "<tr><td height=20>&nbsp;</td></tr>\n";
echo "<tr>\n";
echo "	<td height=20>\n";
echo "		<p>\n";
echo "			<strong>Enable Multicast Streams</strong><br>\n";
echo "			Enable this option if you are receiving a Video On Demand type service from the Internet.\n";
echo "			The router uses the IGMP protocol to support efficient multicasting -- transmission of identical content,\n";
echo "			such as multimedia, from a source to a number of recipients.\n"; 
echo "			This option must be enabled if any applications on the LAN participate in a multicast group.\n"; 
echo "			If you have a multimedia LAN application that is not receiving content as expected, try enabling this option.\n";
echo "		</p>\n";
echo "	</td>\n";
echo "</tr>\n";
}
?>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
 <tr>
 <td height=20>&nbsp;</td>
 </tr>
<?if(query("/runtime/func/dis_routing")!="1"){
echo "<tr>\n";
echo "	<td height=99><strong>Routing </strong><a name=11></a><br>\n";
echo "		The Routing option allows you to define fixed routes to defined destinations.<br>\n";
echo "		<strong><em>Enable </em></strong> - Specifies whether the entry will be enabled or disabled.<br>\n";
echo "		<strong><em>Interface </em></strong> - Specifies the interface -- WAN or WAN Physical -- that\n";
echo "		the IP packet must use to transit out of the router, when this route is used.<br>\n";
echo "		<strong><em>Interface (WAN)</em></strong> - This is the interface to receive the IP Address\n";
echo "		on from the ISP to access the Internet.<br>\n";
echo "		<strong><em>Interface (WAN Physical)</em></strong> - This is the interface to receive the IP Address\n";
echo "		on from the ISP to access the ISP's.<br>\n";
echo "		<strong><em>Destination </em></strong> - The IP address of packets that will take this route.<br>\n";
echo "		<strong><em>Subnet Mask </em></strong> - One bit in the mask specify which bits\n";
echo "		of the IP address must match. <br>\n";
echo "		<strong><em>Gateway </em></strong> - Specifies the next hop to be taken if this route is used.\n";
echo "		A gateway of 0.0.0.0 implies there is no next hop, and the IP address matched is directly\n";
echo "		connected to the router on the interface specified: WAN or WAN Physical. \n";
echo " </td>\n";
echo " </tr>\n";
}
?>

 <tr>
 <td height=20>&nbsp;</td>
 </tr>

 </table>
