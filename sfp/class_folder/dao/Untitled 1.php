
<script>
hex = new Array(16);
hex[0] = "0";
hex[1] = "1";
hex[2] = "2";
hex[3] = "3";
hex[4] = "4";
hex[5] = "5";
hex[6] = "6";
hex[7] = "7";
hex[8] = "8";
hex[9] = "9";
hex[10] = "A";
hex[11] = "B";
hex[12] = "C";
hex[13] = "D";
hex[14] = "E";
hex[15] = "F";
tmp = "";

function f_long_to_hex(num)
{
	if(num =="")
	{
		return false;
	}
	if(num >= 16)
	{
		tmp = hex[num % 16] + tmp;
	return dec(Math.floor(num / 16));
	}
	else
	{
		tmp = hex[num] + tmp;
	}
	if(tmp.length == 1)
	{
		tmp = "0" + tmp;
	}
	h = tmp;
	tmp = "";
	return "0x" + h;
}

function Encriptar(pass)
{
	Tam = pass.legnth();
	for(i=0;i0<=Tam;i++)
	{
		
		
	}
	
	
}




</script>


