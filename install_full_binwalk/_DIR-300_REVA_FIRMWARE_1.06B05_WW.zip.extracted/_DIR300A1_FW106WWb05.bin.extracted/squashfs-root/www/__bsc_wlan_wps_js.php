<?
require($LOCALE_PATH."/bsc_wlan_wps.php");
$cfg_wps_enable = query("/wireless/wps/enable");
$cfg_wps_state  = query("/wireless/wps/configured");
$cfg_wps_locksecurity = query("/wireless/wps/locksecurity");
$cfg_wps_pin    = query("/wireless/wps/pin");
if($cfg_wps_pin == ""){$cfg_wps_pin = query("/runtime/wps/pin");}
?>
<script>
function enable_disable_wps()
{
	var f=get_obj("frm");
	var dis_wps_enable = true;
	var dis_bt_pin = true;
	var dis_bt_wps = true;

	if (f.enable.checked)
	{
		var obj = get_obj("auth_type");
		f.wps_enable.disabled = false;
		if (f.wps_enable.checked)
		{
			f.bt_gen_pin.disabled = f.bt_reset_pin.disabled = false;
			f.bt_do_wps.disabled = false;
			f.bt_reset_wps.disabled = <? if ($cfg_wps_state=="1") { echo "false"; } else { echo "true"; } ?>;
			select_index(obj, "0");
			f.wps_locksecurity.disabled = false;
			obj.disabled = true;
		}
		else
		{
			f.bt_gen_pin.disabled = f.bt_reset_pin.disabled = true;
			f.bt_do_wps.disabled = true;
			f.bt_reset_wps.disabled = true;
			f.wps_locksecurity.disabled = true;
			obj.disabled = false;
		}
	}
}
function gen_pin()
{
	if(!confirm("<?=$a_gen_new_wps_pin?>")) return false;
	self.location="<?=$MY_NAME?>.php?ACTION_WPS=GEN_PIN";
}
function reset_pin()
{
	if(!confirm("<?=$a_reset_wps_pin?>")) return false;
	self.location="<?=$MY_NAME?>.php?ACTION_WPS=RESET_PIN";
}
function reset_wps()
{
	if(!confirm("<?=$a_reset_wps_unconfig?>")) return false;
	self.location="<?=$MY_NAME?>.php?ACTION_WPS=RESET_TO_UNCONFIG";
} 
function add_dev()
{
	<?
	if($cfg_wps_enable!="1")
	{
		echo "alert(\"".$a_enable_wps_first."\");\n";
		echo "return false;\n";
	}
	?>
	self.location.href="do_wps.php";
}
</script>
