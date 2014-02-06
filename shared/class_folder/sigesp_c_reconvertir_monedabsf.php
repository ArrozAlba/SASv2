<?php
class sigesp_c_reconvertir_monedabsf
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $io_dscuentas;

	//---------------------------------------------------------------------------------------------------------------------------
	function sigesp_c_reconvertir_monedabsf()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_c_reconvertir_monedabsf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once("class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once("class_datastore.php");
		$this->io_ds_datos= new class_datastore();
		$this->io_ds_filtro= new class_datastore();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_c_reconvertir_monedabsf
	//---------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function uf_redondear_monedabsf($ai_montobsf,$ai_decimales)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_redondear_monedabsf
		//		   Access: private
		//		 Argument: $ai_montobsf   // Monto en Bolivares Actuales
		//				   $ai_decimales // Cantidad de decimales a utilizar
		//	  Description: Función que realiza el redondeo de bolivares fuertes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_monred=$ai_montobsf;
		$li_posdec=stripos($ai_montobsf,".")+1;
		if($li_posdec>1)
		{
			$li_valor1=substr($ai_montobsf,0,$li_posdec+$ai_decimales+1);
			$li_valor2=substr($ai_montobsf,0,$li_posdec+$ai_decimales);
			$li_factor=($li_valor1-$li_valor2)*(pow(10,$ai_decimales));
			$li_factor=round($li_factor,6);
			if($li_factor>=0.5)
			{
				$li_monred=$li_valor2+(1/pow(10,$ai_decimales));
			}
			else
			{
				if($li_factor<=-0.5)
				{
					$li_monred=$li_valor2-(1/pow(10,$ai_decimales));
				}
				else
				{
					$li_monred=$li_valor2;
				}
			}
		}
		return $li_monred;
	}// end function uf_redondear_monedabsf
	//---------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_monedabsf($ai_montobs,$ai_decimales,$ai_operando,$ai_monfac,$ab_redmon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_monedabsf
		//		   Access: private
		//		 Argument: $ai_montobsf  // Monto en Bolivares Actuales
		//				   $ai_decimales // Cantidad de decimales a utilizar
		//				   $as_operando  // Indica si la conversion es de bs. a bs.f. o viceversa
		//				   $ai_monfac    // Monto del factor de conversion (1000 por defecto)
		//				   $ab_redmon    // Indica si el resultado de la conversion se desea redondear
		//	  Description: Función que realiza la conversion de la moneda (bs.->bs.f. ó bs.f.->bs.)
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_montobsf=0;
		if($ai_operando==1)
		{
			$li_montobsf=$ai_montobs/$ai_monfac;
		}
		else
		{
			$li_montobsf=$ai_montobs*$ai_monfac;
		}
		if($ab_redmon)
		{
			$li_montobsf=$this->uf_redondear_monedabsf($li_montobsf,$ai_decimales);
		}
		return $li_montobsf;
	}// end function uf_convertir_monedabsf
	//---------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------
	function uf_reconvertir_datos($as_tabla,$ai_decimales,$ai_operando,$ab_redmon,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_reconvertir_datos
		//		   Access: public 
		//		 Argument: $as_tabla     // Tabla en donde van a ser ingresados los valores en Bs. F.
		//				   $ai_decimales // Indica la cantidad de decimales que se va a utilizar en la operacion
		//				   $as_operando  // Indica si la conversion es de bs. a bs.f. o viceversa
		//				   $ab_redmon    // Indica si el resultado de la conversion se desea redondear
		//				   $aa_seguridad // Arreglo de Seguridad
		//	  Description: Funcion que se encarga de llamar al proceso de conversion y ademas construir la sentencia que
		//                 inserta los datos convertidos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_totrowdatos= $this->io_ds_datos->getRowCount("campo");
		for($li_i=1;$li_i<=$li_totrowdatos;$li_i++)
		{
			$ls_campo= $this->io_ds_datos->data["campo"][$li_i];
			$li_montobs= $this->io_ds_datos->data["monto"][$li_i];
			$li_montobsf= $this->uf_convertir_monedabsf($li_montobs,$ai_decimales,$ai_operando,1000,$ab_redmon);
			$ls_criterio="";
			$li_totrowfiltro= $this->io_ds_filtro->getRowCount("filtro");
			for($li_j=1;$li_j<=$li_totrowfiltro;$li_j++)
			{
				$ls_filtro= $this->io_ds_filtro->data["filtro"][$li_j];
				$ls_valor= $this->io_ds_filtro->data["valor"][$li_j];
				$ls_tipo= $this->io_ds_filtro->data["tipo"][$li_j];
				if(empty($ls_criterio))
				{
					if($ls_tipo=="I")
					{
						$ls_criterio=$ls_criterio." WHERE ".$ls_filtro."=".$ls_valor."";	
					}
					else
					{
						$ls_criterio=$ls_criterio." WHERE ".$ls_filtro."='".$ls_valor."'";	
					}
				}
				else
				{
					if($ls_tipo=="I")
					{
						$ls_criterio=$ls_criterio." AND ".$ls_filtro."=".$ls_valor."";	
					}
					else
					{
						$ls_criterio=$ls_criterio." AND ".$ls_filtro."='".$ls_valor."'";	
					}
				}
			}
			$ls_sql="UPDATE ".$as_tabla."".
					"   SET ".$ls_campo."=".$li_montobsf."".
					" ".$ls_criterio." ";

			$li_row= $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				//print ($this->io_sql->message);
				$this->io_mensajes->message("CLASE->reconvertir_monedabsf MÉTODO->uf_reconvertir_datos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
			}
		}
		/*if($lb_valido)
		{
			if(!empty($aa_seguridad))
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó los valores de Bs. F. de la tabla ".$as_tabla;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}*/
		$this->io_ds_datos->reset_ds();
		$this->io_ds_filtro->reset_ds();
		return $lb_valido;
	}// end function uf_reconvertir_datos
	//---------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_insert_check_scv($as_codsis,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_check_scv
		//		   Access: public
		//     Argumentos: $aa_seguridad  //Arreglo de Seguridad
		//                 $as_codsis     //Codigo de Sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		//    Description: Funcion que se encarga de guardar el indicador de que se le realizo la reconversion monetaria al modulo 
		// Fecha Creación: 19/07/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_encontrado=$this->uf_select_check_scv($as_codsis);
		if(!$lb_encontrado)
		{
			$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, type, value)".
					" VALUES ('".$this->ls_codemp."','RCM','RECONVERSION','".$as_codsis."','I',1)";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_mensajes->message("CLASE->reconvertir_monedabsf MÉTODO->uf_insert_check_scv ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Realizó la Reconversion Monetaria del Modulo SCV, asociado a la Empresa ".$this->ls_codemp;
				$lb_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_convertir_scvdtpersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_select_check_scv($as_codsis)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_check_scv
		//		   Access: public
		//     Argumentos: $as_codsis   // Codigo de Sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		//    Description: Funcion que se encarga de guardar el indicador de que se le realizo la reconversion monetaria al modulo 
		// Fecha Creación: 20/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido=false;
		$ls_sql="SELECT codemp, codsis, seccion, entry".
				"  FROM sigesp_config".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codsis='RCM'".
				"   AND seccion='RECONVERSION'".
				"   AND entry='".$as_codsis."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->reconvertir_monedabsf MÉTODO->uf_select_check_scv ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_convertir_scvdtpersonal
	//-----------------------------------------------------------------------------------------------------------------------------

}
?>