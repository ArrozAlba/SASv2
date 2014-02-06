//--------------------------------------------------------
//	Función que verifica qel formato de la fecha
//--------------------------------------------------------

function ue_validar_formatofecha(fecha)
{

    if (fecha != undefined && fecha.value != "" )
	{

        if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value))
		{

            alert("Formato de Fecha No Válido (dd/mm/aaaa)");
			fecha.value="";
            return false;

        }
	}
}
//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------

function ue_formato_fecha(d,sep,pat,nums,e)
{
	
	if(e.keyCode==46)
	{
		d.value="";	
	}
	else
	{
		if(d.valant != d.value)
		{
			val = d.value
			largo = val.length
			val = val.split(sep)
			val2 = ''
			for(r=0;r<val.length;r++)
			{
				val2 += val[r]	
			}
			if(nums)
			{
				for(z=0;z<val2.length;z++)
				{
					if(isNaN(val2.charAt(z)))
					{
						letra = new RegExp(val2.charAt(z),"g")
						val2 = val2.replace(letra,"")
					}
				}
			}
			val = ''
			val3 = new Array()
			for(s=0; s<pat.length; s++)
			{
				val3[s] = val2.substring(0,pat[s])
				val2 = val2.substr(pat[s])
			}
			for(q=0;q<val3.length; q++)
			{
				if(q ==0)
				{
					val = val3[q]
				}
				else
				{
					if(val3[q] != "")
					{
						val += sep + val3[q]
					}
				}
			}
			d.value = val
			d.valant = val
		}
	}
}