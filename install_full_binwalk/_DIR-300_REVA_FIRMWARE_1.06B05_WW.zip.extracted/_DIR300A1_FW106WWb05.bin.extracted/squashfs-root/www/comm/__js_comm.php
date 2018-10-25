<? /* vi: set sw=4 ts=4: */ ?>
<script>
// ********************************* commjs start *****************************************************

// if the characters of "char_code" is in following ones: 0~9, A~Z, a~z, some control key and TAB.
function __is_comm_chars(char_code)
{
	if (char_code == 0)  return true;						/* some control key. */
	if (char_code == 8)  return true;						/* TAB */
	if (char_code >= 48 && char_code <= 57)  return true;	/* 0~9 */
	if (char_code >= 65 && char_code <= 90)  return true;	/* A~Z */
	if (char_code >= 97 && char_code <= 122) return true;	/* a~z */
	
	return false;
}
function __is_char_in_string(target, pattern)
{
	var len = pattern.length;
	var i;
	for (i=0; i<len; i++)
	{
		if (target == pattern.charCodeAt(i)) return true;
	}
	return false;
}
//if the evt is in the allowed characters.
function __is_evt_in_allow_chars(evt, allow_comm_chars, allow_chars)
{
	var char_code;
	var i;

	if (navigator.appName == 'Netscape'){char_code=evt.which;	}
	else								{char_code=evt.keyCode;	}

	if (allow_comm_chars == "1" && __is_comm_chars(char_code)==true) return true;
	if (allow_chars.length > 0 && __is_char_in_string(char_code, allow_chars)==true) return true;

	return false;
}
//if the characters of "str" are all in the allowed "allow_chars".
function __is_str_in_allow_chars(str, allow_comm_chars, allow_chars)
{
	var char_code;
	var i;

	for (i=0; i<str.length; i++)
	{
		char_code=str.charCodeAt(i);
		if (allow_comm_chars == "1" && __is_comm_chars(char_code) == true) continue;
		if (allow_chars.length > 0 && __is_char_in_string(char_code, allow_chars) == true) continue;
		return false;
	}
	return true;
}
//if the characters of "str" are all in the denied "deny_chars".
function __is_str_in_deny_chars(str, deny_comm_chars, deny_chars)
{
	var char_code;
	var i;

	for (i=0; i<str.length; i++)
	{
		char_code=str.charCodeAt(i);
		if (deny_comm_chars == "1" && __is_comm_chars(char_code) == true) continue;
		if (deny_chars.length > 0 && __is_char_in_string(char_code, deny_chars) == false) continue;
		return true;
	}
	return false;
}
// -------------------------------------------------------------
// this function is used to check if the inputted string include all "invalid chars" or not.
function is_include_special_chars(str,denychars)
{
	if (__is_str_in_deny_chars(str, 1, denychars)) 
	{
		alert("Cannot include invalid char: \n"+denychars+" ");			
		return true;
	}
	return false;
}
// Get Object by ID.
function get_obj(name)
{
	if (document.getElementById)	return document.getElementById(name);//.style;
	if (document.all)				return document.all[name].style;
	if (document.layers)			return document.layers[name];
}
// generate the radmon str by date.
function generate_random_str()
{
	var d = new Date();
	var str=d.getFullYear()+"."+(d.getMonth()+1)+"."+d.getDate()+"."+d.getHours()+"."+d.getMinutes()+"."+d.getSeconds();
	return str;
}
// this function is used to check if the inputted string is blank or not.
function is_blank(s)
{
	var i=0;
	for(i=0;i<s.length;i++)
	{
		c=s.charAt(i);
		if((c!=' ')&&(c!='\n')&&(c!='\t'))return false;
	}
	return true;
}

// this function is used to check if the string is blank or zero.
function is_blank_or_zero(s)
{
	if (is_blank(s)==true) return true;
	if (is_digit(s))
	{
		var i = parseInt(s, 10);
		if (i==0) return true;
	}
	return false;
}

// this function is used to check if the "str" is a decimal number or not.
function is_digit(str)
{
	if (str.length==0) return false;
	for (var i=0;i < str.length;i++)
	{
		if (str.charAt(i) < '0' || str.charAt(i) > '9') return false;
	}
	return true;
}

// this function is used to check if the value "str" is a hexcimal number or not.
function is_hexdigit(str)
{
	if (str.length==0) return false;
	for (var i=0;i < str.length;i++)
	{
		if (str.charAt(i) <= '9' && str.charAt(i) >= '0') continue;
		if (str.charAt(i) <= 'F' && str.charAt(i) >= 'A') continue;
		if (str.charAt(i) <= 'f' && str.charAt(i) >= 'a') continue;
		return false;
	}
	return true;
}

// convert dec integer string
function decstr2int(str)
{
	var i = -1;
	if (is_digit(str)==true) i = parseInt(str, [10]);
	return i;
}

// convert hex integer string
function hexstr2int(str)
{
	var i = 0;
	if (is_hexdigit(str)==true) i = parseInt(str, [16]);
	return i;
}

// if min <= value <= max, than return true,
// otherwise return false.
function is_in_range(str_val, min, max)
{
	var d = decstr2int(str_val);
	if ( d > max || d < min ) return false;
	return true;
}

// this function convert second to day/hour/min/sec
function second_to_daytime(str_second)
{
	var result = new Array();
	var t;

	result[0] = result[1] = result[2] = result[3] = 0;

	if (is_digit(str_second)==true)
	{
		t = parseInt(str_second, [10]);
		result[0] = parseInt(t/(60*60*24), [10]);	// day
		result[1] = parseInt(t/(60*60), [10]) % 24; // hr
		result[2] = parseInt(t/60, [10]) % 60;		// min
		result[3] = t % 60;							// sec
	}

	return result;
}

// construct xgi string for doSubmit()
function exe_str(str_shellPath)
{
	var str="";
	myShell = str_shellPath.split(";");
	for(i=0; i<myShell.length; i++)
	{
		if (!is_blank(myShell[i])) str+="&"+"exeshell="+myShell[i];
	}
	return str;
}

// return true is brower is IE.
function is_IE()
{
	if (navigator.userAgent.indexOf("MSIE")>-1) return true;
	return false
}

// make docuement.write shorter
function echo(str)
{
	document.write(str);
}

// same as echo() but replace special characters
function echosc(str)
{
	str=str.replace(/&/g,"&amp;");
	str=str.replace(/</g,"&lt;");
	str=str.replace(/>/g,"&gt;");
	str=str.replace(/"/g,"&quot;");
	str=str.replace(/'/g,"\'");
	str=str.replace(/ /g,"&nbsp;");
	document.write(str);
}

function replace_spec_chars(str)
{
//	alert("before Replace "+str);
	str=str.replace(/&/g,"&amp;");
	str=str.replace(/</g,"&lt;");
	str=str.replace(/>/g,"&gt;");
	str=str.replace(/"/g,"&quot;");
	//str=str.replace(/'/g,"&prime;");
	str=str.replace(/'/g,"&acute;");
	str=str.replace(/ /g,"&nbsp;");
//	alert("after Replace "+str);
	return str;
}


// return false if keybaord event is not decimal number.
function dec_num_only(evt)
{
	if (navigator.appName == 'Netscape')
	{
		if (evt.which == 8) return true;	/* TAB */
		if (evt.which == 0) return true;
		if (evt.which >= 48 && evt.which <= 57) return true;
	}
	else
	{
		if (evt.keyCode == 8) return true;
		if (evt.keyCode == 0) return true;
		if (evt.keyCode >= 48 && evt.keyCode <= 57) return true;
	}
	return false;
}

// return false if keyboard event is not hex number.
function hex_num_only(evt)
{
	if (navigator.appName == 'Netscape')
	{
		if (evt.which == 8) return true;	/* TAB */
		if (evt.which == 0) return true;
		if (evt.which >= 48 && evt.which <= 57) return true;
		if (evt.which > 64 && evt.which < 71) return true;
		if (evt.which > 96 && evt.which < 103) return true;
	}
	else
	{
		if (evt.keyCode == 8) return true;	/* TAB */
		if (evt.keyCode == 0) return true;
		if (evt.keyCode >= 48 && evt.keyCode <= 57) return true;
		if (evt.keyCode > 64 && evt.keyCode < 71) return true;
		if (evt.keyCode > 96 && evt.keyCode < 103) return true;
	}
	return false;
}

// return false if keyboard event is not readable character
function readable_char_only(evt)
{
	if (navigator.appName == 'Netscape')
	{
	if (evt.which == 8) return true;	/* TAB */
	if (evt.which == 0) return true;
	if (evt.which < 33 || evt.which > 126) return false;
	}
	else
	{
	if (evt.keyCode == 8) return true;	/* TAB */
	if (evt.keyCode == 0) return true;
	if (evt.keyCode < 33 || evt.keyCode > 126) return false;
	}
	return true;
}


// make the obj selected, if the value of obj is empty, 'def' will be set as value.
function field_select(obj, def)
{
	if (obj.value == '') obj.value = def;
	obj.select();
}

// make the object be focused, and set the value to 'val'.
function field_focus(obj, val)
{
	if (val != '**') obj.value = val;
	obj.focus();
	obj.select();
}

// make all fields which id is between star_name and end_name as disabled/enabled.
// "dis" will be true or false.
function fields_range_disabled(obj, start_name, end_name, dis)
{
	var i=0;
	for(i=0; i<obj.length; i++)
	{
		if (obj[i].id==start_name)
		{
			while(obj[i].id!=end_name && i<obj.length)
			{
				eval("obj["+i+"].disabled="+dis);
				i++;
			}
			get_obj(end_name).disabled=dis;
		}
	}
}
// make all fields of the obj disabled/enabled. "dis" will be true or false.
function fields_disabled(obj, dis)
{
	var i=0;
	for(i=0; i<obj.length; i++)
	{
		if (obj[i].name!="never_disabled")
			eval("obj["+i+"].disabled="+dis);
	}
}

// for safari select loop
function select_index(obj, val)
{
	var i=0;
	for(i=0; i<obj.length;i++)
	{
		if(eval("obj["+i+"].value")==val)
		{
			obj.selectedIndex=i;
			break;
		}
	}
}

// check if any unicode characters in the string.
function strchk_unicode(str)
{
	var strlen=str.length;
	if(strlen>0)
	{
		var c = '';
		for(var i=0;i<strlen;i++)
		{
			c = escape(str.charAt(i));
			if(c.charAt(0) == '%' && c.charAt(1)=='u')
				return true;
		}
	}
	return false;
}

function strchk_url(str)
{
	if (__is_str_in_allow_chars(str, 1, "/.:_-?&=")) return true;
	return false;
}

function strchk_hostname(str)
{
	if (__is_str_in_allow_chars(str, 1, "._-")) return true;
	return false;
}

function strchk_email(str)
{
	if (__is_str_in_allow_chars(str, 1, "._-@")) return true;
	return false;
}

function evtchk_url(evt)
{
	if (__is_evt_in_allow_chars(str, 1, "/.:_-?&=")) return true;
	return false;
}

function evtchk_hostname(evt)
{
	if (__is_evt_in_allow_chars(str, 1, "._-")) return true;
	return false;
}

function evtchk_email(evt)
{
	if (__is_evt_in_allow_chars(str, 1, "._-@")) return true;
	return false;
}

function createRequest()
{
	var request = null;
	try { request = new XMLHttpRequest(); }
	catch (trymicrosoft)
	{
		try { request = new ActiveXObject("Msxml2.XMLHTTP"); }
		catch (othermicrosoft)
		{
			try { request = new ActiveXObject("Microsoft.XMLHTTP"); }
			catch (failed) { request = null; }
		}
	}
	if (request == null) alert("Error creating request object !");
	return request;
}

// *********************************** commjs end *****************************************************
</script>
