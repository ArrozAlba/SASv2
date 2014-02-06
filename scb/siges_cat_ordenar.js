/*****************************************************************
  Funcion usada para ordenar las tablas mostradas en los catalogos
  por los distintos campos que se muestran en ella
******************************************************************/
function ue_ordenar(campo)
{
	f = document.form1;
	posicion = f.hidorden.value.indexOf(' ', 0);
    if (posicion != -1)
    {
		campo_aux = f.hidorden.value.substring(0,posicion);
		if (campo_aux == campo)
		{
			orden_aux = f.hidorden.value.substring(posicion+1,f.hidorden.value.length);
			if (orden_aux == 'ASC')
			{
				f.hidorden.value = campo+" DESC";
			}
			else
			{
				f.hidorden.value = campo+" ASC";
			}
		}
		else
		{
			f.hidorden.value = campo+" ASC";
		}
    }
	else
	{
		if (f.hidorden.value == campo)
		{
			f.hidorden.value = campo+" DESC";
		}
		else
		{
			f.hidorden.value = campo+" ASC";
		}
	}
	f.submit();
}
