<?
/* ---------------------------------- */
//$TITLE=$m_pre_title."SETUP";
/* ---------------------------------- */
$a_empty_ssid		= "The SSID field cannot be blank.";
$a_invalid_ssid		= "There are some invalid characters in the SSID field. Please check it.";

$a_invalid_wep_128_wep_key	= "The Key is invalid. The Key must be 13 characters or 26 hexadecimal numbers.";
$a_invalid_wep_64_wep_key	= "The Key is invalid. The Key must be 5 characters or 10 hexadecimal numbers.";
$a_invalid_wep_128_ascii_wep_key= "The Key is invalid. The Key must be 13 characters.";
$a_invalid_wep_128_hex_wep_key	= "The Key is invalid. The Key must be 26 hexadecimal numbers.";
$a_invalid_wep_64_ascii_wep_key	= "The Key is invalid. The Key must be 5 characters.";
$a_invalid_wep_64_hex_wep_key	= "The Key is invalid. The Key must be 10 hexadecimal numbers.";

$a_empty_defkey			= "The default WEP Key cannot be empty.";
$a_valid_hex_char		= "The legal characters are 0~9, A~F or a~f.";
$a_valid_asc_char		= "The legal characters are ASCII.";

$a_invalid_radius_ip1		= "The IP Address of the RADIUS Server is invalid.";
$a_invalid_radius_port1		= "The Port of the RADIUS Server is invalid.";
$a_empty_radius_sec1		= "The Shared Secret of the RADIUS Server cannot be empty.";
$a_invalid_radius_sec1		= "The Shared Secret of the RADIUS Server should be made up of ASCII characters.";
$a_invalid_passphrase_len	= "The length of the Passphrase should be 8~63.";
$a_invalid_psk_len		= "The length of PSK should be 64.";
$a_psk_not_match		= "The Confirmed Passphrase does not match the Passphrase.";
$a_invalid_passphrase	= "The Passphrase should be made of ASCII characters.";
$a_invalid_psk			= "The PSK should be Hex.";

$a_reset_wps_pin		= "Are you sure you want to Reset the PIN to the Factory Default?";
$a_gen_new_wps_pin		= "Are you sure you want to generate a New PIN?";
$a_reset_wps_unconfig	= "Are you sure you want to Reset the device to be unconfigured?";
$a_enable_wps_first		= "WPS is not enabled yet.  Please press the \\\"Save Settings\\\" to enable WPS first.";

$m_title_wireless_setting	= "Wireless Network Settings";

$m_enable_wireless	= "Enable Wireless";
$m_wlan_name		= "Wireless Network Name";
$m_wlan_name_comment	= "(Also called the SSID)";
$m_wlan_channel		= "Wireless Channel";
$m_enable_auto_channel	= "Enable Auto Channel Selection";
$m_super_g		= "Super G Mode";
$m_super_g_without_turbo= "Super G without Turbo";
$m_super_g_with_d_turbo = "Super G with Dynamic Turbo";
$m_xr			= "Enable Extended Range Mode";
$m_11g_only		= "802.11g Only Mode";
$m_txrate		= "Transmission Rate";
$m_mcrate		= "Multicast Rate";
$m_best_auto	= "Best (automatic)";
$m_mbps			= "(Mbit/s)";
$m_wmm_enable	= "WMM Enable";
$m_wlan_qos		= "(Wireless QoS)";
$m_enable_ap_hidden	= "Enable Hidden Wireless";
$m_ap_hidden_comment	= "(Also called the SSID Broadcast)";

$m_title_wireless_security	= "Wireless Security Mode";

$m_security_mode	= "Security Mode";
$m_disable_security	= "Disable Wireless Security (not recommended)";
$m_enable_wep		= "Enable WEP Wireless Security (basic)";
$m_wpa_security		= "Enable WPA Only Wireless Security (enhanced)";
$m_wpa2_security	= "Enable WPA2 Only Wireless Security (enhanced)";
$m_wpa2_auto_security	= "Enable WPA/WPA2 Wireless Security (enhanced)";

$m_title_wep		= "WEP";
$m_auth_type		= "Authentication";
$m_open			= "Open";
$m_shared_key		= "Shared Key";
$m_wep_key_len		= "WEP Encryption";
$m_64bit_wep		= "64Bit";
$m_128bit_wep		= "128Bit";
$m_hex			= "HEX";
$m_ascii		= "ASCII";
$m_key_type		= "Key Type";
$m_default_wep_key	= "Default WEP Key";
$m_wep_key		= "WEP Key";
$m_wep64_hint_wording	="(5 ASCII or 10 HEX)";
$m_wep128_hint_wording	="(13 ASCII or 26 HEX)";

$m_title_wpa		="WPA Only";
$m_dsc_wpa		="WPA Only requires stations to use high grade encryption and authentication.";
$m_title_wpa2		="WPA2 Only";
$m_dsc_wpa2		="WPA2 Only requires stations to use high grade encryption and authentication.";
$m_title_wpa2_auto	="WPA/WPA2";
$m_dsc_wpa2_auto	="WPA/WPA2 requires stations to use high grade encryption and authentication.";

$m_cipher_type		="Cipher Type";
$m_tkip			="TKIP";
$m_aes			="AES";
$m_psk_eap		="PSK / EAP";
$m_psk			="PSK";
$m_eap			="EAP";
$m_passphrase		="Network Key";
$m_confirm_passphrase	="Confirmed Network Key";
$m_psk_hint_wording		="(8~63 ASCII or 64 HEX)";

$m_8021x		="802.1X";
$m_radius1		="RADIUS Server";
$m_shared_sec		="Shared Secret";
?>
