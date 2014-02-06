<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	Class:        class_datastore
	//  Author:       Ing. Nelson Barraez  
	//  Description:  Clase que permite el almacenamiento y manipulacion temporal de datos(matriz de tipo([columna][fila])), 
	//				  posee metodos de agrupamiento,actualizacion,eliminacion,insercion,ordenamiento,busquedas.
	//  Ultima Modificación : 22/05/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////
class class_datastore
{
    var $data;    
	var $rows=0;
	
    function class_datastore()
    {
        $this->data = array();
    }    
	
   	function insertRow($column, $value = '')
    {
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 insertRow
		// Description : Inserta un nuevo registro en el datastore.
		// Arguments:
		//			     $column   -> Columna de ordenamiento.
		//				 $value    -> Valor a insertar.
		////////////////////////////////////////////////////////////////////////////////////////////
		$pos=$this->getRowCount($column);
		$pos=$pos+1;	
		$this->data[$column][$pos] = $value;
	} 
	   
    function updateRow($column,$value,$row)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 updateRow
		// Description : Actualiza el valor de la columna y fila especificada.
		// Arguments:
		//			     $column	-> Columna a actualizar. 	
		//				 $value     -> Nuevo valor.
		//				 $row		-> Fila a actualizar
		////////////////////////////////////////////////////////////////////////////////////////////
		$this->data[$column][$row]=$value;
	} 
	
    function getValue($column,$pos)
    {
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 getValue
		// Description : Devuelve el valor de la columna en la posicion enviada.
		// Arguments:
		//			     $column	-> Columna a obtener el valor. 	
		//				 $pos		-> Fila a obtener el valor.
		////////////////////////////////////////////////////////////////////////////////////////////
		 if(@array_key_exists($pos,$this->data[$column]))
		 {
			 return $this->data[$column][$pos];
		 }
		 else
		 {
			return ""; 	
		 }
    }
	
	function resetds($column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 resetds
		// Description : Metodo que blanquea el datastore.
		// Arguments:
		//			     $column	-> Columna de referencia. 	
		////////////////////////////////////////////////////////////////////////////////////////////
		$tot=$this->getRowCount($column);
		for($i=$tot;$i>0;$i--)
		{
			$this->deleteRow($column,$i);
		}		
	}
	
	function reset_ds()
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 resetds
		// Description : Metodo que blanquea el datastore.
		// Arguments:
		//			     $column	-> Columna de referencia. 	
		////////////////////////////////////////////////////////////////////////////////////////////
		$this->data = array();
	}
	
	function sortData($column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 sortData
		// Description : Ordena el datastore de forma ascendente filtrando por la columna enviada como parametro
		// Arguments:
		//			     $column	-> Columna de ordenamiento. 	
		////////////////////////////////////////////////////////////////////////////////////////////
		$temp=array();
		natcasesort($this->data[$column]);
		$arrcol=array_keys($this->data);//Arreglo de columnas
		$totcol=count($arrcol);
		$arrindex=array_keys($this->data[$column]);//Arreglo de filas por columna
		$totindex=count($arrindex);
		for($i=0;$i<$totcol;$i++)//For para las columnas
		{
			$col=$arrcol[$i];
			for($a=0;$a<$totindex;$a++)//For para las filas cada columna
			{
				$index=$arrindex[$a];
				$temp[$col][$a+1]=$this->data[$col][$index];
			}
		}
		$this->data= $temp;	
	}
	
	function getRowCount($column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:     getRowCount
		// Description : Devuelve el numero de filas del datastore
		// Arguments:
		//			     $column	-> Columna de referencia del datastore . 	
		////////////////////////////////////////////////////////////////////////////////////////////
		//Verifico si ya se han creado indices
		if(@array_key_exists($column,$this->data))
		{
			$a=count(array_keys($this->data[$column]));
		}
		else
		{
			$a=0;
		}
		return $a;
	}	
	
	function  deleteRow($column,$pos)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   deleteRow
		// Description : -Elimina la fila enviada como parametro
		// Arguments:
		//			 $column	-> Columna de referencia del datastore . 	
		//			 $pos       -> Fila a eliminar.
		////////////////////////////////////////////////////////////////////////////////////////////
		$a=$this->getRowCount($column);//Obtiene el total de filas del datastore.
		$arr=array_keys($this->data);//Obtiene un arreglo con las columnas del datastore.
		$totkeys=count($arr);//Obtiene el total de columnas del datastore.
		if($pos<=$a)
		{
			for($i=$pos;$i<$a;$i++)//For del total de registros del datastore
			{
				for($h=0;$h<$totkeys;$h++)//For del total de columnas del datastore para reorganizar los registros.
				{
					$col=$arr[$h];
					$this->data[$col][$i] = $this->data[$col][$i+1];
				}
			}
			for($l=0;$l<$totkeys;$l++)//For para eliminar el ultimo registro de el datastore que al reorganizar queda repetido por lo que debe ser eliminado.
			{
				array_pop($this->data[$arr[$l]]);//Elimino el ultimo registro de el arreglo en este caso es el ultimo valor para cada columna.
			}
		}
	}
	
	function find($column,$value)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   find
		// Description : Busca un valor especifico en una columna determinada.
		// Arguments:
		//			$value     -> Valor a buscar.
		//			$column	   -> Columna de busqueda.
		// Return : Retorna 0 en caso de no encontrar nada, si encuetnra devuelve la fila donde encontro la primera aparicion		
		////////////////////////////////////////////////////////////////////////////////////////////
		$enc=0;
		$total=$this->getRowCount($column);
		for ($i=1;$i<=$total;$i++)
		{
			$valor=$this->getValue($column,$i);
			$find=stristr($valor,$value);
			if($find===FALSE)
			{
				
			}
			else
			{
				if(!$find=="")
				{
					$enc=$i;
					return $enc;
				}
			}
		}
	return $enc;
	}
	
	function findValues($values,$column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   findValues
		// Description : Busca un conjunto de valores en un conjunto de columnas especificas
		//				(Este conjunto de valores y columnas viene representado en un arreglo
		//				de la siguiente forma  $array[columna1] = valor1 ).	
		// Arguments:
		//			$values    -> Arreglo de las columnas a buscar
		//			$column	   -> Columna de referencia del datastore puede ser la misma que se va a sumar. 
		// Return : Retorna -1 en caso de no encontrar nada , de lo contrario retorna la fila donde encontro los valores.
		////////////////////////////////////////////////////////////////////////////////////////////
		$arr=array_keys($this->data);
		$arr2=array_keys($values);
		$rows=$this->getRowCount($column);//Obtiene el total de columnas del datastore.
		$totkeys=count($values);
		$valor=-1;		
		for($y=1;$y<=$rows;$y++)
		{
			$lb_valido=true;		
			for($i=0;$i<$totkeys;$i++)
			{	
				$colum=$arr2[$i];
				$value1=$values[$colum];
				$value2=$this->data[$colum][$y];
				if(($value1==$value2)&&($lb_valido==true))
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
				}
			}
			if($lb_valido)
			{
				$valor=$y;
			}
			elseif($valor<0)
			{
				$lb_valido=false;
				$valor=-1;
			}
		}	
		return $valor;
	}	
	
	function findNextValues($values,$column,$begin)
	{	//////////////////////////////////////////////////////////////////////////////////////
		// Function : findNextValues
		// Description: -Busca un conjunto de valores en un conjunto de columnas especificas
		//				(Este conjunto de valores y columnas viene representado en un arreglo
		//				de la siguiente forma  $array[columna1] = valor1 ),
		//				a diferencia del findValues este comienza la busqueda a partir de la fila enviada.
		// Arguments:
		//			- $values: Arreglo de valores a buscar.
		//			- $column: Columna de referencia.
		//			- $begin:  Posicion inicial de busqueda. 
		// Return : Retorna -1 en caso de no encontrar nada , de lo contrario retorna la fila donde encontro los valores.
		//////////////////////////////////////////////////////////////////////////////////////
		$arr=array_keys($this->data);
		$arr2=array_keys($values);
		$rows=$this->getRowCount($column);//Obtiene el total de columnas del datastore.
		$totkeys=count($values);
		$valor=-1;		
		for($y=$begin-1;$y<$rows;$y++)
		{
			$lb_valido=true;		
			for($i=0;$i<$totkeys;$i++)
			{	
				$colum=$arr2[$i];
				$value1=$values[$colum];
				$value2=$this->data[$colum][$y+1];				
				if(($value1==$value2)&&($lb_valido==true))
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
				}
			}
			if($lb_valido)
			{
				return $y;					
			}
			else
			{
				$lb_valido=false;
				$valor=-1;
			}
		}	
		return $valor;
	}	
		
	function group_by($aa_items,$sum,$column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   group_by
		// Description : Este metodo Agrupa un conjunto de valores por los campos enviados 
		// 				  en el arreglo como parametro y suma los campo enviados en el arreglo sum.	
		// Arguments:
		//			$aa_itemse -> Arreglo de las columnas a agrupar
		//  		$sum  	   -> Arreglo de campos que se deben acumular(Sumar).
		//			$column	   -> Columna de referencia  	
		////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$this->getRowCount($column);//Obtengo el total de filas.
		$pos=0;//Variable de comparacion de registro encontrado.
		$totcols=count($aa_items);//Arreglo de claves del group.
		$arrcol=array_keys($this->data);//Arreglo de claves(Columnas).
		$totcolsdata=count($arrcol);//Total de columnas.
		$li_aux=0;//Contador auxiliar para buscar registros.
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$pos=0;
			for($li_x=0;$li_x<$totcols;$li_x++)//Lleno un arreglo con los valores a buscar.
			{
				$la_temp[$aa_items[$li_x]]=$this->getValue($aa_items[$li_x],$li_i);	//Arreglo de valores en el datastore para la posicion $li_i	.		
			}
			while($pos>=0)//)//Busco los valores en el datastore.
			{
				$li_aux=$li_aux+1;
				if($pos>0)//Busco el proximo.
				{
					$li_totsum=count($sum);					
					for($li_z=0;$li_z<$li_totsum;$li_z++)
					{
						$col=$sum[$li_z];
						$ldec_monto1=$this->getValue($col,$li_i);//Primera ocurrencia en el datastore.
						$ldec_monto2=$this->getValue($col,$pos+1);//Nueva ocurrencia en el datastore	.			
						$ldec_monto=doubleval($ldec_monto1)+doubleval($ldec_monto2);//Sumo la nueva ocurrencia a la primera.
						$this->updateRow($col,$ldec_monto,$li_i);//Actualizo la Primera ocurrencia en el datastore.
					}
					$this->deleteRow($column,$pos+1);//Elimino la nueva ocurrencia en el datastore.
					$li_aux=$li_aux-1;//Vuelvo el auxiliar al valor anterior por haber eliminado 1.
					$li_total=$li_total-1;//Actualizo el total de filas por haber eliminado 1.	
					$pos=$this->findNextValues($la_temp,$column,$li_aux);////Metodo que busca los valores del arreglo en el datastore desde una posicion especifica.					
				}
				else//Busco el primero.
				{
					$pos=$this->findNextValues($la_temp,$column,$li_aux);//Metodo que busca los valores del arreglo en el datastore desde una posicion especifica.
				}//End if.				
			}//End While.			
		}//End For.		
	}
	function group_by_conformato($aa_items,$sum,$column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   group_by_conformato
		// Description : Este metodo Agrupa un conjunto de valores por los campos enviados 
		// 				  en el arreglo como parametro y suma los campo enviados en el arreglo sum sin importar si los campos tienen formato ###.###.###,00	
		// Arguments:
		//			$aa_itemse -> Arreglo de las columnas a agrupar
		//  		$sum  	   -> Arreglo de campos que se deben acumular(Sumar).
		//			$column	   -> Columna de referencia  	
		////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$this->getRowCount($column);//Obtengo el total de filas.
		$pos=0;//Variable de comparacion de registro encontrado.
		$totcols=count($aa_items);//Arreglo de claves del group.
		$arrcol=array_keys($this->data);//Arreglo de claves(Columnas).
		$totcolsdata=count($arrcol);//Total de columnas.
		$li_aux=0;//Contador auxiliar para buscar registros.
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$pos=0;
			for($li_x=0;$li_x<$totcols;$li_x++)//Lleno un arreglo con los valores a buscar.
			{
				$la_temp[$aa_items[$li_x]]=$this->getValue($aa_items[$li_x],$li_i);	//Arreglo de valores en el datastore para la posicion $li_i	.		
			}
			while($pos>=0)//)//Busco los valores en el datastore.
			{
				$li_aux=$li_aux+1;
				if($pos>0)//Busco el proximo.
				{
					$li_totsum=count($sum);					
					for($li_z=0;$li_z<$li_totsum;$li_z++)
					{
						$col=$sum[$li_z];
						$ldec_monto1=$this->getValue($col,$li_i);//Primera ocurrencia en el datastore.
						$ldec_monto2=$this->getValue($col,$pos+1);//Nueva ocurrencia en el datastore	.			
						$li_pos=strpos($ldec_monto1,",");						
						if($li_pos>0)
						{
							$ldec_monto1=str_replace(".","",$ldec_monto1);
							$ldec_monto1=str_replace(",",".",$ldec_monto1);
						}
						$li_pos=strpos($ldec_monto2,",");						
						if($li_pos>0)
						{
							$ldec_monto2=str_replace(".","",$ldec_monto2);
							$ldec_monto2=str_replace(",",".",$ldec_monto2);
						}
						$ldec_monto=doubleval($ldec_monto1)+doubleval($ldec_monto2);//Sumo la nueva ocurrencia a la primera.
						$this->updateRow($col,number_format($ldec_monto,2,",","."),$li_i);//Actualizo la Primera ocurrencia en el datastore.
					}
					$this->deleteRow($column,$pos+1);//Elimino la nueva ocurrencia en el datastore.
					$li_aux=$li_aux-1;//Vuelvo el auxiliar al valor anterior por haber eliminado 1.
					$li_total=$li_total-1;//Actualizo el total de filas por haber eliminado 1.	
					$pos=$this->findNextValues($la_temp,$column,$li_aux);////Metodo que busca los valores del arreglo en el datastore desde una posicion especifica.					
				}
				else//Busco el primero.
				{
					$pos=$this->findNextValues($la_temp,$column,$li_aux);//Metodo que busca los valores del arreglo en el datastore desde una posicion especifica.
				}//End if.				
			}//End While.			
		}//End For.		
	}
	function group($column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   group
		// Description : Este metodo agrupa los valores por al columna enviada como parametro y 
		//				retorna el datastore ordenado por la columna y sin repetir el valor en caso de que sean iguales
		//				Ejemplo: Un banco que tiene tres cuentas quedaria de la siguiente forma
		//				001 		Cuenta Ahorro
		//							Cuenta Corriente
		//							Cuenta Fideicomiso Plus	
		//				en vez de quedar como es normalmente
		//				001 		Cuenta Ahorro
		//				001			Cuenta Corriente
		//				001			Cuenta Fideicomiso Plus	
		//			Este metodo es mayormente utilizados para los reportes
		// Arguments:
		//			$column	   -> Columna de referencia del datastore puede ser la misma que se va a sumar. 	
		////////////////////////////////////////////////////////////////////////////////////////////
		$this->sortData($column);//Ordeno el datastore por la columna recibida como parametro
		$li_total=$this->getRowCount($column);//Obtengo el total de filas.
		$pos=0;//Variable de comparacion de registro encontrado.
		$arrcol=array_keys($this->data);//Arreglo de claves(Columnas).
		$totcolsdata=count($arrcol);//Total de columnas.
		$li_aux=0;//Contador auxiliar para buscar registros.
		$ls_aux="";
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$pos=0;			
			$ls_value=$this->getValue($column,$li_i);			
			if($li_i==2)
			{				
				$li_a=$li_i-1;
				$ls_anterior=$this->getValue($column,$li_a);							
				if($ls_value==$ls_anterior)
				{
					$ls_value="";
					$ls_aux=$ls_anterior;
					$this->updateRow($column,$ls_value,$li_i);					
				}
				else
				{
					$ls_aux=$ls_value;
				}
			}				
			elseif($li_i>2)
			{
				if($ls_value==$ls_aux)
				{
					$ls_value="";
					$this->updateRow($column,$ls_value,$li_i);					
				}
				else
				{
					$ls_aux=$ls_value;					
				}
			}				
		}//End For.		
	}
	
	function group_noorder($column)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   group_noorder
		// Description : Este metodo agrupa los valores por al columna enviada como parametro y 
		//				retorna el datastore sin repetir el valor en caso de que sean iguales
		//				Ejemplo: Un banco que tiene tres cuentas quedaria de la siguiente forma
		//				001 		Cuenta Ahorro
		//							Cuenta Corriente
		//							Cuenta Fideicomiso Plus	
		//				en vez de quedar como es normalmente
		//				001 		Cuenta Ahorro
		//				001			Cuenta Corriente
		//				001			Cuenta Fideicomiso Plus	
		//			Este metodo es mayormente utilizados para los reportes
		// Arguments:
		//			$column	   -> Columna de referencia del datastore puede ser la misma que se va a sumar. 	
		////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$this->getRowCount($column);//Obtengo el total de filas.
		$pos=0;//Variable de comparacion de registro encontrado.
		$arrcol=array_keys($this->data);//Arreglo de claves(Columnas).
		$totcolsdata=count($arrcol);//Total de columnas.
		$li_aux=0;//Contador auxiliar para buscar registros.
		$ls_aux="";
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$pos=0;			
			$ls_value=$this->getValue($column,$li_i);			
			if($li_i==2)
			{				
				$li_a=$li_i-1;
				$ls_anterior=$this->getValue($column,$li_a);							
				if($ls_value==$ls_anterior)
				{
					$ls_value="";
					$ls_aux=$ls_anterior;
					$this->updateRow($column,$ls_value,$li_i);					
				}
				else
				{
					$ls_aux=$ls_value;
				}
			}				
			elseif($li_i>2)
			{
				if($ls_value==$ls_aux)
				{
					$ls_value="";
					$this->updateRow($column,$ls_value,$li_i);					
				}
				else
				{
					$ls_aux=$ls_value;					
				}
			}				
		}//End For.		
	}
	
	function uf_cuadrar_montos($columna)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   uf_cuadrar_montos
		// Description : Este metodo sustituye los monto que tengan formato 1.000,00 al formato 1000.00 para realizar operaciones matematicas 
		// Arguments:
		//			$columna	   -> Columna de referencia del datastore puede ser la misma que se va a sumar. 	
		////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$this->getRowCount($columna);		
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ldec_monto=$this->getValue($columna,$li_i);
			$ldec_monto=str_replace(".","",$ldec_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			$this->updateRow($columna,$ldec_monto,$li_i);
		}	
	}	
		
}	
?>