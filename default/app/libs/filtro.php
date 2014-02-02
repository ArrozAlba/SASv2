<?php

/**
* Creación de filtros sql
*/
class Filtro
{

	protected $_dataFilter = array();
	
	/**
	 * Establece los campos que tendran condiciones en la tabla especificada
	 * @param string $tableName  nombre de la tabla
	 * @param array  $dataFilter para campo => valor por donde se filtrará.
	 */
	function __construct($tableName = NULL,$dataFilter = array())
	{
		$this->_dataFilter[$tableName] = $dataFilter;
	}

	/**
	 * Establece los campos que tendran condiciones en la tabla especificada
	 * @param string $tableName  nombre de la tabla
	 * @param array  $dataFilter para campo => valor por donde se filtrará.
	 */
	public function addDataFilter(string $tableName,array $dataFilter){
		$this->_dataFilter[$tableName] = $dataFilter;
	}

	/**
	 * Genera el query con las condiciones para los campos espeficicados.
	 * @param  string $join metodo por el que se uniran las condiciones (AND, OR)
	 * @return string  sql generado ó TRUE si no hay condiciones.
	 */
	public function getQuery($join = 'AND'){
		$sql = array();
		foreach($this->_dataFilter as $table => $dataFilter){
			foreach ($dataFilter as $field => $value) {
				if ($value){
					$sql[] = "{$table}.{$field} LIKE '%$value%'";					
				}
			}
		}
		return count($sql) ? join(" $join ", $sql) : TRUE;
	}
}