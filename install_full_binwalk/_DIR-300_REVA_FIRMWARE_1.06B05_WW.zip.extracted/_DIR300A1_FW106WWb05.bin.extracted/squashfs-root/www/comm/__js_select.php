function print_select(n, id, start, end, offset)
{
	var str=new String("<select size=1 name="+n+" id="+id+">");
	for(var i=start; i<=end; i+=offset)
	{
		if(i<10)str+="<option value="+i+">0"+i+"</option>";
		else    str+="<option value="+i+">"+i+"</option>";
	}
	str+="</select>";
	document.writeln(str);
}

function print_week(n)
{
	var str=new String("<select size=1 name="+n+">");
	str+="<option value=0><?=$m_sun?></option>";
	str+="<option value=1><?=$m_mon?></option>";
	str+="<option value=2><?=$m_tue?></option>";
	str+="<option value=3><?=$m_wed?></option>";
	str+="<option value=4><?=$m_thu?></option>";
	str+="<option value=5><?=$m_fri?></option>";
	str+="<option value=6><?=$m_sat?></option>";
	str+="</select>";
	document.writeln(str);
}

function print_am(n)
{
	var str=new String("<select size=1 name="+n+">");
	str+="<option value='am'><?=$m_am?></option><option value='pm'><?=$m_pm?></option>";
	str+="</select>";
	document.writeln(str);
	
}
function echo(str){document.write(str);}
