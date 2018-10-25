<?
$m_title_ap_mode	= "Access Point Mode";
$m_desc_ap_mode		= "Use this to disable NAT on the router and turn it into an Access Point.";
$m_enable_ap_mode	= "Enable Access Point Mode";

$m_title_wan_type	= "Internet Connection Type";
$m_desc_wan_type	= "Choose the mode to be used by the router to connect to the Internet.";

$m_wan_type	= "My Internet Connection is";
$m_static_ip	= "Static IP";
$m_dhcp		= "Dynamic IP (DHCP)";
$m_pppoe	= "PPPoE (Username / Password)";
$m_pptp		= "PPTP (Username / Password)";
$m_l2tp		= "L2TP (Username / Password)";
$m_russia_pptp		= "Russia PPTP (Dual Access)";
$m_russia_pppoe		= "Russia PPPoE (Dual Access)";
$m_mppe		="MPPE";

$m_title_static	= "Static IP Address Internet Connection Type";
$m_desc_static	= "Enter the static IP address information provided by your Internet Service Provider (ISP).";

$m_comment_isp	= "(assigned by your ISP)";
$m_subnet	= "Subnet Mask";
$m_isp_gateway	= "ISP Gateway Address";
$m_macaddr	= "MAC Address";
$m_optional	= "(optional)";
$m_clone_mac	= "Clone MAC Address";
$m_primary_dns	= "Primary DNS Address";
$m_secondary_dns= "Secondary DNS Address";
$m_mtu		= "MTU";

$m_title_dhcp	= "Dynamic IP (DHCP) Internet Connection Type";
$m_desc_dhcp	= "Use this Internet connection type if your Internet Service Provider (ISP) ".
		"didn't provide you with IP Address information and/or a username and password.";

$m_host_name		= "Host Name";
$m_use_unicast	=	"Use Unicasting";
$m_unicast_dsc	=	"(compatibility for some DHCP Servers)";
$m_ppp_idle_time	= "Maximum Idle Time";
$m_ppp_connect_mode	= "Connect mode select";
$m_always_on		= "Always-on";
$m_manual		= "Manual";
$m_on_demand		= "Connect-on demand";

$__info_from_isp	= "Enter the information provided by your Internet Service Provider (ISP).";

$m_title_pppoe	= "PPPoE";
$m_title_russia_pppoe	= "Russia PPPoE (DUAL Access)";
$m_desc_pppoe	= $__info_from_isp;
$m_title_physical	= "WAN Physical Settings";

$m_dynamic_pppoe	= "Dynamic PPPoE";
$m_static_pppoe		= "Static PPPoE";
$m_retype_pwd		= "Confirm Password";
$m_pppoe_svc_name	= "Service Name";
$m_minutes		= "Minutes";
$m_auto_dns		= "Receive DNS from ISP";
$m_manual_dns	= "Enter DNS Manually";

$m_title_pptp	= "PPTP";
$m_title_russia_pptp	= "Russia PPTP (DUAL Access)";
$m_desc_pptp	= $__info_from_isp;

$m_title_l2tp	= "L2TP";
$m_desc_l2tp	= $__info_from_isp;

$m_dynamic_ip		= "Dynamic IP";
$m_static_ip		= "Static IP";
$m_gateway		= "Gateway";
$m_dns			= "DNS";
$m_server_ip		= "Server IP/Name";
$m_pptp_account		= "PPTP Account";
$m_pptp_password	= "PPTP Password";
$m_pptp_retype_pwd	= "PPTP Confirm Password";
$m_l2tp_account		= "L2TP Account";
$m_l2tp_password	= "L2TP Password";
$m_l2tp_retype_pwd	= "L2TP Confirm Password";

$m_auth_server	= "Auth Server";
$m_login_server = "Login Server IP/Name";

/*----------802.1x------------*/

$m_8021x_setting	   	= "802.1x";
$m_8021x_setting_description = "Enter the information provided by your Internet Service Provider (ISP).";

$m_8021x_eap_type		= "EAP Type";
$m_8021x_md5			= "MD5";
$m_8021x_peap			= "PEAP";
$m_8021x_ttls			= "TTLS";

$m_8021x_authentication	= "Authentication";
$m_8021x_pap			= "PAP";
$m_8021x_chap			= "CHAP";
$m_8021x_mschap			= "MSCHAP";
$m_8021x_mschapv2		= "MSCHAP Version 2";

$m_8021x_identity		= "User Name";
$m_8021x_pass		= "Password";
$m_8021x_pass2		= "Confirmed Password";

/*------------------------------*/


$a_invalid_ip		= "Invalid IP address !";
$a_invalid_netmask	= "Invalid subnet mask !";
$a_invalid_mac		= "Invalid MAC address !";
$a_invalid_mtu		= "Invalid MTU value !";
$a_invalid_hostname	= "Invalid host name !";
$a_invalid_username	= "Invalid user name !";
$a_password_mismatch	= "The confirm password does not match the new password !";
$a_invalid_idletime	= "Invalid idle time !";

$a_srv_in_different_subnet	= "Invalid server IP address ! The server and router addresses should be in the same network.";
$a_gw_in_different_subnet	= "Invalid gateway IP address ! The gateway and router addresses should be in the same network.";
$a_server_empty		= "Server IP/Name can not be empty !";
$a_account_empty	= "Account can not be empty !";
$a_ip_equal_gateway = "The IP address can not be equal to the gateway address!";

/*---------------802.1x alert message---------------*/
$a_empty_username		= "The user name can not be empty !";
$a_empty_password		= "The password can not be empty !";
$a_empty_password_v		= "The confirmed password can not be empty !";
$a_diff_password		= "The two passwords are different !";
$a_empty_field			= "The field can not be empty !";
/*--------------------------------------------------*/
$a_wan_type_changed		= "Any alteration of Internet Connection Type will disable the logging of Internet Usage Meter. Are you sure you want to continue and save?";
$a_switch_to_ap			= "Switching to AP mode will disable the logging of Internet Usage Meter. Are you sure you want to continue and save?";
?>
