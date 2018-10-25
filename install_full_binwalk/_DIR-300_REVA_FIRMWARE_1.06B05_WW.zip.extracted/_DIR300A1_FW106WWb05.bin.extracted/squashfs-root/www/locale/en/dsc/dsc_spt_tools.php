<h1>SUPPORT MENU</h1>
<table border=0 cellspacing=0 cellpadding=0 width=750>
<tr>
<td height=2><b><font size=4>Maintenance</font></b></td>
</tr>
<tr>
<td height=16><p><br>
</td>
</tr>
<tr>
	<td height=20>
		<strong>Device Administration</strong><a name=12></a><br>
		<p>
			<strong><em>Administrator Login Name</em></strong> -
			Enter the name that will be used to login to the router with Admin access.
		</p>
		<p>
			<strong><em>Administrator password</em></strong> -
			Enter and confirm the password that the <strong>admin </strong> account will use to access the router's management interface.
		</p>
		<p>
			<strong>Remote Management</strong><br>
			Remote Management allows the device to be configured through the WAN (Wide Area Network) port
			from the Internet using a web browser. A username and password is still required to access the
			router's management interface.<br>
			<strong><em>IP Allowed to Access</em></strong> -
			This option allows users to specify a particular IP address from the Internet to be allowed to access the router
			remotely. This field is left blank by default which means any IP address from the Internet can access the router
			remotely once remote management is enabled.<br>
			<strong><em>Port</em></strong> - Select the port which will be used to access the <?query("/sys/modelname");?>.
		</p>
		<p>
			<strong><em>Example: </em></strong><br>
			http://x.x.x.x:8080 whereas x.x.x.x is the
			WAN IP address of the <?query("/sys/modelname");?> and 8080 is the port used for the Web-Management interface.
		</p>
	</td>
</tr>
<tr><td height=10>&nbsp;</td></tr>
<tr><td height=10>&nbsp;</td></tr>
<tr>
	<td height=40>
		<a name=14><strong>Save and Restore</strong></a><br>
		The current system settings can be saved as a file onto the local hard drive.
		The saved file or any other saved setting file created by device can be uploaded into the unit.
		To reload a system settings file, click on <strong>Browse</strong> to search the local hard drive
		for the file to be used. The device can also be reset back to factory default settings by clicking
		on <strong>Restore Device</strong>. Use the restore feature only if necessary. This will erase previously
		save settings for the unit. Make sure to save your system settings before carrying out a factory restore.<br>
		<strong><em>Save</em></strong> - Click this button to save the configuration file from the router.<br>
		<strong><em>Browse</em></strong> -
		Click Browse to locate a saved configuration file and then click to Load and apply the
		saved settings to the router.<br>
		<strong><em>Restore Device</em></strong> -
		Click this button to restore the router to its factory default settings.
	</td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
<tr>
	<td height=51>
		<b>Firmware Update</b><a name=15></a><br> 
		You can upgrade the firmware of the device using this tool. Make sure that the firmware
		you want to use is saved on the local hard drive of the computer. Click on <strong>Browse </strong>
		to search the local hard drive for the firmware to be used for the update. Upgrading the firmware
		will not change any of your system settings but it is recommended that you save your system settings
		before carrying out a firmware upgrade. Please check the D-Link <a href=<?query("/sys/supporturl");?>>support site</a>
		for firmware updates or you can click on the <strong>Check Now</strong> button to have the router check the
		new firmware automatically.
	</td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
<tr>
	<td height=2>
		<p>
			<strong>DDNS Setting </strong><a name=16></a><br>
			Dynamic DNS (Domain Name Service) is a method of keeping a domain name linked to a
			changing (dynamic) IP address. With most Cable and DSL connections, you are assigned
			a dynamic IP address and that address is used only for the duration of that specific
			connection. With the <?query("/sys/modelname");?>, you can setup your DDNS service and
			the <?query("/sys/modelname");?> will automatically update your DDNS server every time
			it receives a new WAN IP address.<br>
			<strong><em>Server Address</em></strong> - Choose your DDNS provider from the drop down menu.<br>
			<strong><em>Host Name</em></strong> - Enter the Host Name that you registered with your DDNS service provider.<br>
			<strong><em>Username</em></strong> - Enter the username for your DDNS account.<br>
			<strong><em>Password</em></strong> - Enter the password for your DDNS account.
		</p>
	</td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
<tr>
	<td height="197">
		<p>
			<strong>System Check</strong><a name=17></a><br>
			This tool can be used to verify physical connectivity on both the LAN and Internet (WAN) interfaces.
			This is an advanced feature that integrates a LAN cable tester on every Ethernet port on the router.
			Through the graphical user interface (GUI), a Cable Test can be carried out to remotely diagnose and report
			cable faults such as open circuits, short circuits, swapped pairs, and impedance mismatch. This feature significantly reduces
			service calls and returns by allowing users to easily troubleshoot their cable connections.
		</p>
		<p>
			<strong>Ping Test</strong><br>
			This useful diagnostic utility can be used to check if a computer is on the Internet.
			It sends ping packets and listens for replies from the specific host. Enter in a host name or the IP
			address that you want to ping (Packet Internet Groper) and click <strong>Ping</strong>. The status of
			your Ping attempt will be displayed in the Ping Result box.
		</p>
	</td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
<tr>
	<td>
		<p>
			<strong>Schedules<a name=18></a></strong></p>
			This page is used to configure global schedules for the router. Once defined,
			these schedules can later be applied to the features of the router that support scheduling.<br>
			<strong><em>Name</em></strong> - The name of the schedule being defined.<br>
			<strong><em>Day(s)</em></strong> -
			Select a day, range of days, or select the All Week checkbox to have this schedule apply every day.<br>
			<strong><em>All Day - 24 hrs</em></strong> -
			Check this box to have the schedule active the entire 24 hours on the days specified.<br>
			<strong><em>Start Time</em></strong> -
			Select the time at which you would like the schedule being defined to become active.<br>
			<strong><em>End Time</em></strong> -
			Select the time at which you would like the schedule being defined to become inactive.<br>
			<strong><em>Schedule Rules List</em></strong> -
			This displays all the schedules that have been defined.
		</p>
	</td>
</tr>
<tr><td height=20>&nbsp;</td></tr>
<tr><td height=20>&nbsp;</td></tr>
<tr>
	<td>
		<p>
			<strong>Log Settings</strong><strong><a name=19></a></strong><br>
			You can save the log file to a local drive which can later be used to send to a network
			administrator for troubleshooting.<br>
			<strong><em>Save</em></strong> - Click this button to save the log entries to a text file.<br>
			<strong><em>Log Type</em></strong> - Select the type of information you would like the <?query("/sys/modelname");?> to log.
		</p>
	</td>
</tr>

</table>
						   
