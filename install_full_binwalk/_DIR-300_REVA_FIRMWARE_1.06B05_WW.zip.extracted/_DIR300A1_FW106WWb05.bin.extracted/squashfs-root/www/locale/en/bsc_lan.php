<?
/* --------------------------------------------------------- */
//$TITLE=$m_pre_title."SETUP";
/* --------------------------------------------------------- */
$m_title_router_setting	= "Router Settings";
$m_desc_router_setting	=
	"Use this section to configure the internal network settings of your router. ".
	"The IP address that is configured here is the IP address that you use to access ".
	"the Web-based management interface. If you change the IP address here, you may ".
	"need to adjust your PC's network settings to access the network again.";

$m_router_ipaddr	= "Router IP Address";
$m_subnet_mask		= "Default Subnet Mask";
$m_domain_name		= "Local Domain Name";
$m_enable_dnsr		= "Enable DNS Relay";

$m_title_dhcp_svr	= "DHCP Server Settings";
$m_desc_dhcp_svr	=
	"Use this section to configure the built-in DHCP server to assign IP address to ".
	"the computers on your network.";

$m_enable_dhcp		= "Enable DHCP Server";
$m_dhcp_range		= "DHCP IP Address Range";
$m_to			= "to";
$m_range_comment	= "(addresses within the LAN subnet)";
$m_lease_time		= "DHCP Lease Time";
$m_netbios	= "NetBIOS announcement";
$m_netbios_learn	=	"Learn NetBIOS from WAN";
$m_netbios_scope	=	"NetBIOS Scope";
$m_optional	=	"(optional)";
$m_netbios_type	=	"NetBIOS node type";
$m_broadcast	=	"Broadcast only (use when no WINS servers configured)";
$m_ptop	=	"Point-to-Point (no broadcast)";
$m_mix_mode	=	"Mixed-mode (Broadcast then Point-to-Point)";
$m_hybrid	=	"Hybrid (Point-to-Point then Broadcast)";
$m_primary_wins	=	"Primary WINS IP Address";
$m_secondary_wins	=	"Secondary WINS IP Address";

$m_minutes		= "(minutes)";

$m_title_add_client	= "DHCP Reservation";
$m_computer_name	= "Computer Name";
$m_clone_mac		= "Copy Your PC's MAC Address";

$m_title_static_client_list = "DHCP Reservations List";
$remaining_wording	="Remaining number of clients that can be configured";

$m_title_client_list	= "DHCP Client List";
$m_host_name		= "Host Name";
$m_ipaddr		= "IP Address";
$m_macaddr		= "MAC Address";
$m_expired_time		= "Expired Time";

$m_days	= "day(s)";
$m_hrs	= "hr(s)";
$m_mins	= "min(s)";
$m_secs	= "sec(s)";

$a_invalid_ip		= "Invalid IP address !";
$a_invalid_netmask	= "Invalid subnet mask !";
$a_network_conflict	= "Guest zone network must be different from host zone !";
$a_invalid_domain_name	= "Invalid domain name !";
$a_invalid_ip_range	= "Invalid IP address range for DHCP server!";
$a_invalid_lease_time	= "Invalid DHCP Lease Time !";
$a_del_confirm		= "Are you sure that you want to delete this entry?";
$a_same_static_mac	= "There is an existing entry with the same MAC Address.\\n Please change the MAC Address.";
$a_same_static_ip	= "There is a existing entry with the same IP Address.\\n Please change the IP Address.";
$a_same_static_hostname = "There is a existing entry with the same Computer Name.\\n Please change the Computer Name.";
$a_same_with_lan_ip	= "The IP Address can't be the same as the LAN IP Address!";
$a_invalid_computer_name= "Invalid Computer Name !";
$a_invalid_mac		= "Invalid MAC Address !";

$a_invalid_form         = "NetBIOS Scope is not in a valid form !";
$a_invalid_primaywins	= "Invalid netbios primary wins !";
$a_invalid_secondaywins	= "Invalid netbios secondary wins !";
$a_primary_null		= "Primary WINS IP address must be specified if secondary is also specified !";
?>
