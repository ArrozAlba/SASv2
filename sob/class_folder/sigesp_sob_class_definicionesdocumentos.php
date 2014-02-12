<?PHP

class sigesp_sob_class_definicionesdocumentos
{
	var  $la_obra;
	var  $la_asignacion;
	var  $la_contrato;
	var  $la_anticipo;
	var  $la_acta;	
	var  $la_inspector;
	var  $la_residente;
	
	function sigesp_sob_class_definicionesdocumentos(){}	
	
	function uf_acta($as_codcon,$as_codact,$as_tipact)
	{
		//----------------Clases y Objetos--------------------------------------//
			require_once("class_folder/sigesp_sob_c_contrato.php");
			$this->io_contrato=new sigesp_sob_c_contrato();
			require_once("class_folder/sigesp_sob_class_obra.php");
			$this->io_obra=new sigesp_sob_class_obra();
			require_once("class_folder/sigesp_sob_c_acta.php");
			$this->io_acta=new sigesp_sob_c_acta();
			require_once("class_folder/sigesp_sob_c_supervisores.php");
			$this->io_supervisores=new sigesp_sob_c_supervisores();
			//---------------cargando la data en arreglos----------------//
			$this->io_contrato->uf_select_contrato($as_codcon,$this->la_contrato);
			$this->io_acta->uf_select_acta($as_codcon,$as_codact,$as_tipact,$this->la_acta);
			$this->io_obra->uf_select_obra($this->la_contrato["codobr"][1],$this->la_obra);
			$this->io_supervisores->uf_select_supervisor($this->la_acta["cedinsact"][1],$this->la_inspector);
			$this->io_supervisores->uf_select_supervisor($this->la_acta["cedresact"][1],$this->la_residente);
	}
}
?>