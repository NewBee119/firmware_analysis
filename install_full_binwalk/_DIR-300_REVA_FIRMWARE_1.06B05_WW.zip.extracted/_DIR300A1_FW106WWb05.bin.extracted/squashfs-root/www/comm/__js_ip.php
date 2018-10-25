<script>
// vi: set sw=4 ts=4: ************************ ip.js start *************************************

// convert to MAC address string to array.
// myMAC[0] contains the orginal ip string. (dot spereated format).
// myMAC[1~6] contain the 6 parts of the MAC address.
function get_mac(m)
{
	var myMAC=new Array();
	if (m.search(":") != -1)    var tmp=m.split(":");
	else                        var tmp=m.split("-");

	for (var i=0;i <= 6;i++) myMAC[i]="";
	if (m != "")
	{
		for (var i=1;i <= tmp.length;i++) myMAC[i]=tmp[i-1];
		myMAC[0]=m;
	}
	return myMAC;
}

// convert to ip address string to array.
// myIP[0] contains the orginal ip string. (dot spereated format).
// myIP[1~4] contain the 4 parts of the ip address.
function get_ip(str_ip)
{
	var myIP=new Array();

	myIP[0] = myIP[1] = myIP[2] = myIP[3] = myIP[4] = "";
	if (str_ip != "")
	{
		var tmp=str_ip.split(".");
		for (var i=1;i <= tmp.length;i++) myIP[i]=tmp[i-1];
		myIP[0]=str_ip;
	}
	else
	{
		for (var i=0; i <= 4;i++) myIP[i]="";
	}
	return myIP;
}

//Get DHCP range_ip
function get_host_range_ip(ip,mask)
{
	var ipaddr = new Array();
	var submask = new Array();
	ipaddr = get_ip(ip);
	submask = get_ip(mask);
	
	var ip_range = new Array();
	ip_range[0] = (ipaddr[4] & submask[4])+1;
	ip_range[1] = ip_range[0]+(submask[4] ^ 255)-2;
	return ip_range;
}

// return netmask array according to the class of the ip address.
function generate_mask(str)
{
	var mask = new Array();
	var IP1 = decstr2int(str);

	mask[0] = "0.0.0.0";
	mask[1] = mask[2] = mask[3] = mask[4] = "0";

	if		(IP1 > 0 && IP1 < 128)
	{
		mask[0] = "255.0.0.0";
		mask[1] = "255";
	}
	else if	(IP1 > 127 && IP1 < 191)
	{
		mask[0] = "255.255.0.0";
		mask[1] = "255";
		mask[2] = "255";
	}
	else
	{
		mask[0] = "255.255.255.0";
		mask[1] = "255";
		mask[2] = "255";
		mask[3] = "255";
	}
	return mask;
}

// construct a IP array
function generate_ip(str1, str2, str3, str4)
{
	var ip = new Array();

	ip[1] = (str1=="") ? "0" : decstr2int(str1.value);
	ip[2] = (str2=="") ? "0" : decstr2int(str2.value);
	ip[3] = (str3=="") ? "0" : decstr2int(str3.value);
	ip[4] = (str4=="") ? "0" : decstr2int(str4.value);
	ip[0] = ip[1]+"."+ip[2]+"."+ip[3]+"."+ip[4];
	return ip;
}

// return IP array of network ID
function get_network_id(ip, mask)
{
	var id = new Array();
	var ipaddr = get_ip(ip);
	var subnet = get_ip(mask);

	id[1] = ipaddr[1] & subnet[1];
	id[2] = ipaddr[2] & subnet[2];
	id[3] = ipaddr[3] & subnet[3];
	id[4] = ipaddr[4] & subnet[4];
	id[0] = id[1]+"."+id[2]+"."+id[3]+"."+id[4];
	return id;
}

// return IP array of host ID
function get_host_id(ip, mask)
{
	var id = new Array();
	var ipaddr = get_ip(ip);
	var subnet = get_ip(mask);

	id[1] = ipaddr[1] & (subnet[1] ^ 255);
	id[2] = ipaddr[2] & (subnet[2] ^ 255);
	id[3] = ipaddr[3] & (subnet[3] ^ 255);
	id[4] = ipaddr[4] & (subnet[4] ^ 255);
	id[0] = id[1]+"."+id[2]+"."+id[3]+"."+id[4];
	return id;
}

// return IP array of Broadcast IP address
function get_broadcast_ip(ip, mask)
{
	var id = new Array();
	var ipaddr = get_ip(ip);
	var subnet = get_ip(mask);

	id[1] = ipaddr[1] | (subnet[1] ^ 255);
	id[2] = ipaddr[2] | (subnet[2] ^ 255);
	id[3] = ipaddr[3] | (subnet[3] ^ 255);
	id[4] = ipaddr[4] | (subnet[4] ^ 255);
	id[0] = id[1]+"."+id[2]+"."+id[3]+"."+id[4];
	return id;
}

function is_valid_port_str(port)
{
	return is_in_range(port, 1, 65535);
}

// return true if the port is valid.
function is_valid_port(obj)
{
	if (is_valid_port_str(obj.value)==false)
	{
		field_focus(obj, '**');
		return false;
	}
	return true;
}

function is_valid_port_range_str(port1, port2)
{
	if (is_blank(port1)) return false;
	if (!is_valid_port_str(port1)) return false;
	if (is_blank(port2)) return false;
	if (!is_valid_port_str(port2)) return false;
	var i = parseInt(port1, [10]);
	var j = parseInt(port2, [10]);
	if (i > j) return false;
	return true;
}

// return true if the port range is valid.
function is_valid_port_range(obj1, obj2)
{
	return is_valid_port_range_str(obj1.value, obj2.value);
}

// return true if the IP address is valid.
function is_valid_ip(ipaddr, optional)
{
	var ip = get_ip(ipaddr);

	if (optional!=0 && is_blank(ipaddr)) return true;
	if (is_in_range(ip[1], 1, 223)==false) return false;
	if (decstr2int(ip[1]) == 127) return false;
	if (is_in_range(ip[2], 0, 255)==false) return false;
	if (is_in_range(ip[3], 0, 255)==false) return false;
	if (is_in_range(ip[4], 0, 255)==false) return false;

	ip[0] = parseInt(ip[1],[10])+"."+parseInt(ip[2],[10])+"."+parseInt(ip[3],[10])+"."+parseInt(ip[4],[10]);
	if (ip[0] != ipaddr) return false;

	return true;
}
//Kwest++, 2008/06/06,check if IP address is valid according to IP address and Netmask.
function is_valid_ip2(ipaddr, netmask)
{
	var ip_broadcast = get_broadcast_ip(ipaddr, netmask);
	var ip_networkid = get_network_id(ipaddr, netmask);
	if(ip_broadcast[0] == ipaddr) return false; 
	if(ip_networkid[0] == ipaddr) return false; 
	return true;
}
//Ella++, 2008/01/11, fix ip netmask bug temporarily.
function is_valid_gateway(ipaddr, netmask, gateway, optional)
{
	var ip = get_ip(gateway);
	var ip_broadcast = get_broadcast_ip(ipaddr, netmask);
	var ip_networkid = get_network_id(ipaddr, netmask);

	if (optional!=0 && is_blank(gateway)) return true;
	if (is_in_range(ip[1], 1, 223)==false) return false;
	if (decstr2int(ip[1]) == 127) return false;
	if (is_in_range(ip[2], 0, 255)==false) return false;
	if (is_in_range(ip[3], 0, 255)==false) return false;
	if (is_in_range(ip[4], 1, 255)==false) return false;

	ip[0] = parseInt(ip[1],[10])+"."+parseInt(ip[2],[10])+"."+parseInt(ip[3],[10])+"."+parseInt(ip[4],[10]);
	if (ip[0] != gateway) return false;

	if(ip_networkid[0] == gateway) return false;
	if(ip_broadcast[0] == gateway) return false;
	
	return true;
	
}
// return true if the network address is valid.
function is_valid_network(ipaddr, mask)
{
	var ip = get_network_id(ipaddr, mask);
	if (ip[0] != ipaddr)	return false;
	return true;
}

//+++Teresa, return true if the network mask both the same.
function is_both_equal_network(ipaddr1, mask1, ipaddr2, mask2)
{
	var ip1 = get_network_id(ipaddr1, mask1);
	var ip2 = get_network_id(ipaddr2, mask2);
	if (ip1[0] != ip2[0])	return false;
	return true;
}

// return false if the value is not a valid netmask value.
function __is_mask(str)
{
	d = decstr2int(str);
	if (d==0 || d==128 || d==192 || d==224 || d==240 || d==248 || d==252 || d==254 || d==255) return true;
	return false;
}

// return true if the netmask is valid.
function is_valid_mask(mask)
{
   var sMask=mask.split(".");
   
   if (sMask.length!=4) return false;
   
   for(var i=0; i< sMask.length; i++)
   {
      if (!is_digit(sMask[i])) return false;
      if (parseInt(sMask[i],10) < 0 || parseInt(sMask[i],10) > 255) return false;
   }

   for (var i =0 ; i< sMask.length; i++)
      sMask[i] = parseInt(sMask[i], 10);
   
   U32ip = sMask[0]*0x1000000+sMask[1]*0x10000+sMask[2]*0x100+sMask[3];

   if(U32ip==0) return false;
		
   for(var i=0; i<32;i++)
   {
      if(U32ip & (0x1<<i))
      {
         var myvalue = Math.pow(2,i)-1;
         myvalue = Math.pow(2,32) -1 -myvalue;
			
         if (myvalue == U32ip)
   			return true;	
         else
            return false;
      }
   }
	
   return false;
}

function is_valid_mac(mac)
{
	return is_hexdigit(mac);
}
function is_valid_mac_str(mac)
{
	var tmp_mac=get_mac(mac);
	var cmp_mac="";
	var cmp_mac1="";
	var i, sub_mac, sub_dec_mac;
	for(i=1;i<=6;i++)
	{
		sub_mac=eval("tmp_mac["+i+"]");
		sub_dec_mac=hexstr2int(sub_mac);
		if(sub_dec_mac>255 ||sub_dec_mac<0)	return false;
		else if(sub_dec_mac<=15)
		{
			cmp_mac +="0"+sub_dec_mac.toString(16);
			cmp_mac1+="0"+sub_dec_mac.toString(16);
		}
		else
		{
			cmp_mac +=sub_dec_mac.toString(16);
			cmp_mac1+=sub_dec_mac.toString(16);
		}
		if(i!=6)
		{
			cmp_mac +=":";
			cmp_mac1+="-";
		}
	}
	if(cmp_mac!=mac.toLowerCase() && cmp_mac1!=mac.toLowerCase())	return false;
	return true;
}

// *********************************** ip.js stop *************************************
</script>
