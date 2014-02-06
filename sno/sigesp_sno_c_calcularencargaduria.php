<?php
class sigesp_sno_c_calcularencargaduria
{
	var $io_sql;
	var $io_mensajes;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_funciones;
	var $io_eval;
	var $io_prestamo;
	var $io_vacacion;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	var $ls_conpronom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_calcularencargaduria()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_calcularencargaduria
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();	
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once("sigesp_sno_c_evaluador.php");
		$this->io_eval=new sigesp_sno_c_evaluador();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$this->ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
		$this->lb_sobregiro=false;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_buscar_concepto_encargado($as_codperenc, $as_codcon, &$ad_valconenc)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_concepto_encargado
		//		   Access: private
		//	    Arguments: as_codnomenc // código de nómina original del personal encargado
		//                 as_codperenc // código del personal encargado
		//                 as_codcon    // código del concepto
		//				   ad_valconenc  // valor del concepto
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que busca el concepto asociado a una personal encargada
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_conceptopersonal.codconc, sno_concepto.nomcon, sno_concepto.forcon,".
		        "   sno_concepto.valmincon,  sno_concepto.valmaxcon".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codper='".$as_codperenc."' ".
				"   AND sno_conceptopersonal.codconc='".$as_codcon."' ".
				"   AND sno_conceptopersonal.aplcon ='1'".				
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom ".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ";
		$rs_dato=$this->io_sql->select($ls_sql);
		if($rs_dato===false)
		{
			$this->io_mensajes->message("CLASE->Calculo Encargaduria MÉTODO->uf_buscar_concepto_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($rs_dato->RecordCount()>0)
			{
				$lb_valido=$this->io_eval->uf_crear_personalnomina($as_codperenc);
				while((!$rs_dato->EOF)&&($lb_valido))
				{
					$ls_codcon=$rs_dato->fields["codconc"];
					$ls_nomcon=$rs_dato->fields["nomcon"];
					$ls_forcon=$rs_dato->fields["forcon"];					
					$ld_valmincon=$la_conceptopersonal["valmincon"];
					$ld_valmaxcon=$la_conceptopersonal["valmaxcon"];
					$_SESSION["la_conceptopersonal"]["codconc"]=$ls_codcon;
					if ($lb_valido)
					{
					
						$lb_valido=$this->io_eval->uf_evaluar($as_codperenc,$ls_forcon,$ld_valcon);
						if($lb_valido)
						{
							if($ad_valmincon>0)//verifico el minimo del concepto 
							{
								if($ld_valcon<$ld_valmincon)
								{
									$ld_valcon=$ld_valmincon;
								}
							}
							if($ad_valmaxcon>0)//verifico el maximo del concepto
							{
								if($ld_valcon>$ld_valmaxcon)
								{
									$ld_valcon=$ld_valmaxcon;
								}
							}
							
						}
					}
					$rs_dato->MoveNext();
				} // Fin del While
				$ad_valconenc=$ld_valcon;
			}			
			else
			{
				$ad_valconenc=0;
			}
		}// fin del else
		return $lb_valido;

}// end function uf_buscar_concepto_encargado

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>