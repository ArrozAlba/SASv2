
<script>

hexadecimal = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F")

function convierteHexadecimal(num)
{
    var hexaDec = Math.floor(num/16)
    var hexaUni = num - (hexaDec * 16)
    return hexadecimal[hexaDec] + hexadecimal[hexaUni]
}

function Encriptar(pass)
{
	ls_acumini='';
	ls_acumfin='';
	cadena=null;
	Tam = pass.length;
	for(i=0;i<=Tam-1;i++)
	{
		Ascii = pass.substr(i,1);
		AuxAs = Ascii.charCodeAt(0);
		ls_temp=convierteHexadecimal(AuxAs);
		//alert(ls_temp);
		left = ls_temp.substr(0,1);
		right= ls_temp.substr(ls_temp.length-1,1);	
		//alert(left);
		//alert(right);
		ls_acumini =ls_acumini+right;
		ls_acumfin =left+ls_acumfin;
		
	}
	cadena=ls_acumini+ls_acumfin;
	return cadena;
}



Encriptar()
</script>


