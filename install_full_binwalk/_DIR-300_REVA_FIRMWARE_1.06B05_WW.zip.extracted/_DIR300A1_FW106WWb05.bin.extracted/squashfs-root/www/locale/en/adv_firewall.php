<?
$m_title_firewall = "Firewall Setting";
$m_enable_spi="Enable SPI";
$m_title_rtsp="APPLICATION LEVEL GATEWAY(ALG) CONFIGURATION";
$m_enable_rtsp="Enable RTSP";
$m_enable_dos="Enable DoS Prevention";
$m_title_attack_prevention="Internal Attack Prevention";
$m_desc_attack_prevention="Prevent Attack Type";
$m_enable_arp_attack="ARP Attack";
$m_enable_fake_dhcp="Fake DHCP Server";
$m_title_firewall_rules = "Firewall Rules";
$m_action = "Action";
$m_allow = "Allow";
$m_deny = "Deny";
$m_inf = "Interface";
$m_start_ip = "Start IP Address";
$m_end_ip	="End IP Address";
$m_protocol	="Protocol";
$m_port_range ="Port Range";
$m_src	="Source";
$m_dst = "Dest";
$m_schedule = "Schedule";
$m_always_on = "Always On";
$m_add_new_sch = "Add New Schedule";
$m_title_fw_list="Firewall Rules List";
$m_allow_ping = "PING from WAN";
$m_remote_management="Remote Management";


$m_title_dmz_rule = "DMZ HOST";
$m_desc_dmz =
	"The DMZ(Demilitarized Zone) option allows you ".
	"to set up a single computer on your network to be outside ".
	"of the router. If you have a computer that cannot run Internet ".
	"applications successfully from behind the router, then place ".
	"the computer into the DMZ for unrestricted Internet access.";
$m_note_dmz =
	"<strong>Note:</strong> Putting a computer in the DMZ ".
	"may expose that computer to a variety of security risks. Use of this option ".
	"is only recommended as a last resort.";
$m_enable_dmz_host = "Enable DMZ Host";
$m_ip_addr_dmz = "DMZ IP Address"; 
$m_computer_name = "Computer Name";


$a_no_ip_selected	= "Please select a machine first !";
$a_invalid_ip		= "Invalid IP address !";
$a_confirm_to_del_fw= "Are you sure you want to DELETE this rule?";
$a_invalid_port="Invaild Port !";
$a_invalid_port_range="Invaild Port Range !";

$a_invalid_src_startip="Invalid Source Start IP Address !";
$a_src_startip_in_different_subnet   = "Invalid source start IP address ! The source start IP address and router address should be in the same network subnet.";

$a_invalid_src_endip="Invalid Source End IP Address !";
$a_src_endip_in_different_subnet   = "Invalid source end IP address ! The source end IP address and router address should be in the same network subnet.";

$a_invalid_dst_startip="Invalid destination start IP address !";
$a_dst_startip_in_different_subnet   = "Invalid destination start IP address ! The destination start IP address and router address should be in the same network subnet.";

$a_invalid_dst_endip="Invalid Destination End IP Address !";
$a_dst_endip_in_different_subnet   = "Invalid destination end IP address ! The destination end IP address and router address should be in the same network subnet.";

$a_fw_name_cant_be_empty="The Firewall Name can not be empty !";
$a_not_support_same_direction="The Source Interface and Destination Interface can not be the same !";
$a_invalid_src_ip_range="Invalid Source IP Address Range !";
$a_invalid_dst_ip_range="Invalid Destination IP Address Range !";
$a_confirm_swap_fw="Are you sure to CHANGE the priority?";
$a_dmzip_in_different_subnet = "Invalid DMZ IP Address ! The DMZ IP Address and router address should be in the same network subnet.";
$a_same_rule_exist = "Name '\"+get_obj(\"fw_description_\"+i).value+\"' is already used.";
?>
