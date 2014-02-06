  function uf_validarentero(t)
  {
    valor=t.value;
	longitud=valor.length;
	bueno=true;
	if (valor!="")
	{
	  for (i=0;i<longitud;i++)
	  {
	    car=valor.substring(i,i+1);
		if (!((car>="0")&&(car<="9")))
		{
		  bueno=false;
		}		
	  }	  
	}	
	return bueno;
  }
  
  function uf_validarreal(t)
  {
    valor=t.value;
	longitud=valor.length;
	bueno=true;
	haypunto=0;
	if (valor!="")
	{
	  for (i=0;i<longitud;i++)
	  {
	    car=valor.substring(i,i+1);
		if (!((car>="0")&&(car<="9")))
		{
		  if (car==".")
		  {
		    if (haypunto==0)
			{
			  haypunto=1;
			}
			else
			{
			  bueno=false;
			}
		  }
		  else
		  {
		    bueno=false;
		  }
		}		
	  }	  
	}	
	return bueno;
  }  
  
  function uf_fechavalida(dia,mes,ano)
  {
    bueno=true;
	
	if ((mes=="04")||(mes=="06")||(mes=="09")||(mes=="11"))
	{
	  if (dia=="31")
	  {
	    bueno=false;
	  }
	}
	else if (mes=="02")
	{
	  if ((dia=="30")||(dia=="31"))
	  {
	    bueno=false;
	  }
	  else if (dia=="29")
	  {
	    nano=parseInt(ano);
		division=nano/4;
		ddivision=parseInt(division);
		if (ddivision!=division)
		{
	       bueno=false;
		}
	  }
	}
	
	return bueno;
  }

  