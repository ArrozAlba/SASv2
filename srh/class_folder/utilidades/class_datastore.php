<?php

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
		//Inserto un nuevo registro en la columna enviada.
		$pos=$this->getRowCount($column);
		$pos=$pos+1;	
		$this->data[$column][$pos] = $value;
	} 
	   
    function updateRow($column,$value,$row)
	{
		$this->data[$column][$row]=$value;
	} 
	
    function getValue($column,$pos)
    {
		//Retorno el valor para la columna e indice enviado.
	     return $this->data[$column][$pos];
    }
	function resetds($column)
	{
		$tot=$this->getRowCount($column);
		for($i=$tot;$i>0;$i--)
		{
			$this->deleteRow($column,$i);
		}
		
	}
	function getRowCount($column)
	{
		//Verifico si ya se han creado indices
		if(array_key_exists($column,$this->data))
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
		// - Descripción del metodo : Este metodo elimina una determinada fila del datastore(Matriz)
		// $name -> Una columna (clave) de referencia que este dentro del datastore ( Matriz ).
		// $pos  -> La posición a eliminar en el datastore.
		////////////////////////////////////////////////////////////////////////////////////////////
		//Obtiene el total de filas del datastore.
		$a=$this->getRowCount($column);
		//Obtiene un arreglo con las colujmnas del datastore.
		$arr=array_keys($this->data);
		//Obtiene el total de columnas del datastore.
		$totkeys=count($arr);
		if($pos<=$a)
		{
			//For del total de registros del datastore
			for($i=$pos;$i<$a;$i++)
			{
				//For del total de columnas del datastore para reorganizar los registros.
				for($h=0;$h<$totkeys;$h++)
				{
					$col=$arr[$h];
					$this->data[$col][$i] = $this->data[$col][$i+1];
				}
			}

		    //For para eliminar el ultimo registro de el datastore que al reorganizar queda repetido por lo que debe ser eliminado.
			for($l=0;$l<$totkeys;$l++)
			{
				//Elimino el ultimo registro de el arreglo en este caso es el ultimo valor para cada columna.
				array_pop($this->data[$arr[$l]]);
			}
		}
	}
	
	
	function find($column,$value)
	{
		//Busco un valor especifico en una columna especifica.
		$enc=0;
		$total=$this->getRowCount($column);
		for ($i=1;$i<=$total;$i++)
		{
			$valor=$this->getValue($column,$i);
			$find=stristr($valor,$value);
			if($find==FALSE)
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
		//Busco un conjunto de valores en un conjunto de columnas especificas
		//(Este conjunto de valores y columnas viene representado en un arreglo).
		//El arreglo debe ser de la siguiente forma  $arr["nombre_columna"]=valor_a_buscar
		$enc=0;
		$arr=array_keys($this->data);
		$arr2=array_keys($values);
		//Obtiene el total de columnas del datastore.
		$rows=$this->getRowCount($column);
		$totkeys=count($values);
		$valor=-1;		
		for($y=0;$y<$rows;$y++)
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
					$valor=$y;
				}
				else
				{
					$lb_valido=false;
					$valor=-1;
				}
		}	
		return $valor;
	}
		
}	
?>