<?php
class sigesp_sno_c_importardefiniciones
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_importardefiniciones()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_importardefiniciones
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		
	}// end function sigesp_sno_c_importardefiniciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_cargo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existeregistro($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existeregistro
		//		   Access: private
		//      Arguments: as_sql  // sentencia SQL
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que busca si existe un registro
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$rs_data=$this->io_sql->select($as_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_existeregistro ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_existe=false;
		}
		else
		{
			if(!($row=$this->io_sql->fetch_row($rs_data)))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_existeregistro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_nomina($as_codnom,&$aa_personaldisp,&$aa_conceptodisp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_nomina
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnom  // Código de Nómina
		//				   aa_personaldisp  // Personal Disponible
		//				   aa_conceptodisp  // Concepto Disponible
		//	      Returns: $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene las personas y los conceptos que se encuentran en la nòmina Seleccionada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_personal, sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$as_codnom."' ".
				"   AND sno_personal.estper='1'".
				"   AND sno_personalnomina.staper<>'3' ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_personaldisp["codper"][$li_i]=$row["codper"];
				$aa_personaldisp["nomper"][$li_i]=$row["apeper"].", ".$row["nomper"];
				$li_i=$li_i+1;
			}
			$this->io_sql->free_result($rs_data);		
		}
		if($lb_valido)
		{
			$ls_sql="SELECT codconc, nomcon ".
					"  FROM sno_concepto ".
					" WHERE sno_concepto.codemp='".$this->ls_codemp."' ".
					"   AND sno_concepto.codnom='".$as_codnom."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_load_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				$li_i=0;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$aa_conceptodisp["codconc"][$li_i]=$row["codconc"];
					$aa_conceptodisp["nomcon"][$li_i]=$row["nomcon"];
					$li_i=$li_i+1;
				}
				$this->io_sql->free_result($rs_data);		
			}
		}
		return $lb_valido;
	}// end function uf_load_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardefiniciones($as_codnombus,$aa_personalsele,$ai_totper,$aa_conceptosele,$ai_totcon,$as_codcar,
	                                 $as_codasicar,$as_codtab,$as_codpas,$as_codgra,$ai_sueper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardefiniciones
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnombus  // Código de Nómina donde se va a importar la información
		//				   aa_personalsele  // Personal Seleccionado que se va a importar
		//				   ai_totper  // total del personal selecionado
		//				   aa_conceptosele  // Concepto Seleccionado que se va a importar
		//				   ai_totcon  // total de conceptos selecionado
		//				   as_codcar  // código de Cargo selecionado
		//				   as_codasicar  // Código de Asignación de Cargo
		//				   as_codtab  // Código de Tabulador
		//				   as_codpas  // Código de Paso
		//				   as_codgra  // código de Grado
		//				   ai_sueper  // sueldo según la asiganción de cargo
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el importar completo ó False si hubo error en el importar
		//	  Description: Función que importa toda la información referente a Tablas, grado, cargo, asignación de cargo, subnómina
		//				   que el personal seleccionado tiene asociado. y las constantes que el concepto selecionado tiene asociado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		// Importar la información referente al personal seleccionado		
		for($li_i=0;(($li_i<$ai_totper)&&($lb_valido));$li_i++) 
		{
			$ls_codper=$aa_personalsele[$li_i];
			$lb_valido=$this->uf_importar_tabla($as_codnombus,$ls_codper);
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_cargo($as_codnombus,$ls_codper);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_asignacioncargo($as_codnombus,$ls_codper);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_subnomina($as_codnombus,$ls_codper);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_personal($as_codnombus,$ls_codper,$as_codcar,$as_codasicar,$as_codtab,
	                                 				   $as_codpas,$as_codgra,$ai_sueper);
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Importó la información relacionada (Tabla, Grado, Cargo, Asignación Cargo, Subnómina, personal) ".
								 " del personal ".$ls_codper. " de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		// Importar la informaciòn referente al concepto seleccionado
		for($li_i=0;(($li_i<$ai_totcon)&&($lb_valido));$li_i++) 
		{
			$ls_codconc=$aa_conceptosele[$li_i];
			$lb_valido=$this->uf_importar_concepto($as_codnombus,$ls_codconc,true);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Importó la información relacionada (conceptos, constantes ) ".
								 " del concepto ".$ls_codconc. " de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		
		if($lb_valido)
		{
			$this->io_mensajes->message("La Información fue Importada.");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al importar la información.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_importardefiniciones
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importardefiniciones_lote($as_codnombus,$aa_personalsele,$ai_totper,$aa_conceptosele,$ai_totcon,$as_codcar,
	                                 	  $as_codasicar,$as_codtab,$as_codpas,$as_codgra,$ai_sueper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importardefiniciones_lote
		//		   Access: public (sigesp_sno_p_importardefiniciones.php)
		//	    Arguments: as_codnombus  // Código de Nómina donde se va a importar la información
		//				   aa_personalsele  // Personal Seleccionado que se va a importar
		//				   ai_totper  // total del personal selecionado
		//				   aa_conceptosele  // Concepto Seleccionado que se va a importar
		//				   ai_totcon  // total de conceptos selecionado
		//				   as_codcar  // código de Cargo selecionado
		//				   as_codasicar  // Código de Asignación de Cargo
		//				   as_codtab  // Código de Tabulador
		//				   as_codpas  // Código de Paso
		//				   as_codgra  // código de Grado
		//				   ai_sueper  // sueldo según la asiganción de cargo
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el importar completo ó False si hubo error en el importar
		//	  Description: Función que importa toda la información referente a Tablas, grado, cargo, asignación de cargo, subnómina
		//				   que la nómina fuente tiene asociado, el personal seleccionado, las constantes que la nómina fuente 
		//				   tiene asociado y el concepto selecionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		// Importar la informaciòn referente al personal
		$lb_valido=$this->uf_importar_tabla($as_codnombus,"");
		if($lb_valido)
		{
			$lb_valido=$this->uf_importar_cargo($as_codnombus,"");
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_importar_asignacioncargo($as_codnombus,"");
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_importar_subnomina($as_codnombus,"");
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Importó toda la información de Tabla, Grado, Cargo, Asignación Cargo, Subnómina ".
							 "de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		for($li_i=0;(($li_i<$ai_totper)&&($lb_valido));$li_i++) 
		{
			$ls_codper=$aa_personalsele[$li_i];
			$lb_valido=$this->uf_importar_personal($as_codnombus,$ls_codper,$as_codcar,$as_codasicar,$as_codtab,$as_codpas,
	                                 	  		   $as_codgra,$ai_sueper);
			if($lb_valido)
			{			
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Importó la información del personal ".$ls_codper. " de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		// Importar la información referente al concepto seleccionado
		if($ai_totcon>0)
		{		
			if($lb_valido)
			{
				$lb_valido=$this->uf_importar_constantes($as_codnombus,"");
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Importó Toda la información de constantes de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		for($li_i=0;(($li_i<$ai_totcon)&&($lb_valido));$li_i++) // Importo la informaciòn referente al concepto
		{
			$ls_codconc=$aa_conceptosele[$li_i];
			$lb_valido=$this->uf_importar_concepto($as_codnombus,$ls_codconc,false);
			if($lb_valido)
			{			
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Importó la información relacionada del concepto ".$ls_codconc. " de la nómina ".$as_codnombus." a la nómina ".$this->ls_codnom." ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("La Información fue Importada.");
			$this->io_sql->commit();
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un error al importar la información.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_importardefiniciones_lote
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_tabla($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_tabla
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codper  // Còdigo de personal
		//	      Returns:	$lb_valido True si se importó la tabla correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la tabla del personal y la inserta en la nómina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_tabulador.codtab, sno_tabulador.destab ".
					"  FROM sno_personalnomina, sno_tabulador ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_tabulador.codemp ".
					"   AND sno_personalnomina.codnom = sno_tabulador.codnom ".				
					"   AND sno_personalnomina.codtab = sno_tabulador.codtab ";
		}
		else
		{
			$ls_sql="SELECT codtab, destab ".
					"  FROM sno_tabulador ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";		
		}
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_tabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codtab=$row["codtab"];
				$ls_destab=$row["destab"];
				$ls_sql="SELECT codtab ".
						"  FROM sno_tabulador ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codtab='".$ls_codtab."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codtab."','".$ls_destab."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_tabla ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_importar_grado($as_codnombus,$ls_codtab);
					}					
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_grado($as_codnombus,$as_codtab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_grado
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codtab  // Còdigo de tabla
		//	      Returns:	$lb_valido True si se importó el grado correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de los grados de la tabla y la inserta en la nómina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_grado.codgra, sno_grado.codpas, sno_grado.monsalgra, sno_grado.moncomgra ".
				"  FROM sno_tabulador, sno_grado ".
				" WHERE sno_tabulador.codemp='".$this->ls_codemp."' ".
				"   AND sno_tabulador.codnom='".$as_codnombus."' ".
				"   AND sno_tabulador.codtab='".$as_codtab."' ".
				"   AND sno_tabulador.codemp = sno_grado.codemp ".
				"   AND sno_tabulador.codnom = sno_grado.codnom ".				
				"   AND sno_tabulador.codtab = sno_grado.codtab ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codgra=$row["codgra"];
				$ls_codpas=$row["codpas"];
				$li_monsalgra=$row["monsalgra"];
				$li_moncomgra=$row["moncomgra"];
				$ls_sql="SELECT codgra ".
						"  FROM sno_grado ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codtab='".$as_codtab."'".
						"   AND codgra='".$ls_codgra."'".
						"   AND codpas='".$ls_codpas."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_grado(codemp,codnom,codtab,codgra,codpas,monsalgra,moncomgra)VALUES('".$this->ls_codemp."',".
							"'".$this->ls_codnom."','".$as_codtab."','".$ls_codgra."','".$ls_codpas."',".$li_monsalgra.",".$li_moncomgra.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_cargo($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_cargo
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codper  // Còdigo de personal
		//	      Returns:	$lb_valido True si se importó el cargo correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn del cargo del personal y lo inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_cargo.codcar, sno_cargo.descar ".
					"  FROM sno_personalnomina, sno_cargo ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					"   AND sno_personalnomina.codnom = sno_cargo.codnom ".				
					"   AND sno_personalnomina.codcar = sno_cargo.codcar ";
		}
		else
		{
			$ls_sql="SELECT codcar, descar  ".
					"  FROM sno_cargo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codcar=$row["codcar"];
				$ls_descar=$row["descar"];
				$ls_sql="SELECT codcar ".
						"  FROM sno_cargo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codcar='".$ls_codcar."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_cargo(codemp,codnom,codcar,descar)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codcar."','".$ls_descar."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_insert_grado($codtab,$codpas,$codgra)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_grado
		//		   Access: private
		//      Arguments:
		//	      Returns: 
		//	  Description: función que busca los tabuladores y si no existelos inserta
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 28/01/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= " SELECT codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra".
				 "   FROM sno_grado ".
				 "  WHERE codemp='".$this->ls_codemp."'".
				 "    AND codnom='".$this->ls_codnom."'".
				 "    AND codtab='".$codtab."'".
				 "    AND codpas='".$codpas."'".
				 "    AND codgra='".$codgra."'"; 
		$rs_data2=$this->io_sql->select($ls_sql);
		if($rs_data2===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_insert_grado ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}	
		else
		{
		 	$ls_numero=0;
			$ls_numero=	$this->io_sql->num_rows($rs_data2);
			
			if ($ls_numero==0)
			{
				$ls_sql=" INSERT INTO sno_grado(codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra, ".
                        " moncomgraaux, monsalgraaux) VALUES ('".$this->ls_codemp."', '".$this->ls_codnom."', 
						'".$codtab."', '".$codpas."', '".$codgra."',0,0,0,0);";
				
				$li_row2=$this->io_sql->execute($ls_sql);
				if($li_row2===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_insert_grado(insert) ERROR->".
					                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}// fin del if			
		}// fin del else
		return 	$lb_valido;
	}// uf_insert_tabulador	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_asignacioncargo($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_asignacioncargo
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codper  // Còdigo de personal
		//	      Returns:	$lb_valido True si se importó la asignación de cargo correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la asignación de cargo del personal y lo inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_asignacioncargo.codasicar, sno_asignacioncargo.denasicar, sno_asignacioncargo.claasicar, sno_asignacioncargo.codtab, ".
					"		sno_asignacioncargo.codpas, sno_asignacioncargo.codgra, sno_asignacioncargo.codded, sno_asignacioncargo.codtipper, ".
					"		sno_asignacioncargo.numvacasicar, sno_asignacioncargo.numocuasicar, sno_asignacioncargo.codproasicar, ".
					" 		sno_asignacioncargo.minorguniadm, sno_asignacioncargo.ofiuniadm, sno_asignacioncargo.uniuniadm, sno_asignacioncargo.depuniadm, ".
					"		sno_asignacioncargo.prouniadm, sno_asignacioncargo.estcla	".
					"  FROM sno_personalnomina, sno_asignacioncargo ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					"   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".				
					"   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ";
		}
		else
		{
			$ls_sql="SELECT codasicar, denasicar, claasicar, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, codtab, ".
					"       codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar, estcla ".
					"  FROM sno_asignacioncargo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codasicar=$row["codasicar"];
				$ls_denasicar=$row["denasicar"];
				$ls_minorguniadm=$row["minorguniadm"];
				$ls_ofiuniadm=$row["ofiuniadm"];
				$ls_uniuniadm=$row["uniuniadm"];
				$ls_depuniadm=$row["depuniadm"];
				$ls_prouniadm=$row["prouniadm"];
				$ls_claasicar=$row["claasicar"];
				$ls_estcla=$row["estcla"];
				$ls_codtab=$row["codtab"];
				$ls_codpas=$row["codpas"];
				$ls_codgra=$row["codgra"];
				$lb_valido=$this->uf_insert_grado($ls_codtab,$ls_codpas,$ls_codgra);
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];		
				$li_numvacasicar=$row["numvacasicar"];
				$li_numocuasicar=$row["numocuasicar"];
				$ls_codproasicar=$row["codproasicar"];
				$ls_sql="SELECT codasicar ".
						"  FROM sno_asignacioncargo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codasicar='".$ls_codasicar."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_asignacioncargo".
							"(codemp,codnom,codasicar,denasicar,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,claasicar,codtab,codpas,codgra,".
							"codded,codtipper,numvacasicar,numocuasicar,codproasicar,estcla)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codasicar."','".$ls_denasicar."','".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."',".
							"'".$ls_prouniadm."','".$ls_claasicar."','".$ls_codtab."','".$ls_codpas."','".$ls_codgra."','".$ls_codded."','".$ls_codtipper."',".
							"".$li_numvacasicar.",".$li_numocuasicar.",'".$ls_codproasicar."','".$ls_estcla."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_asignacioncargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_asignacioncargo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_subnomina($as_codnombus,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_subnomina
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codper  // Còdigo de personal
		//	      Returns:	$lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la subnòmina del personal y lo inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_i=0;
		if($as_codper<>"")
		{
			$ls_sql="SELECT sno_subnomina.codsubnom, sno_subnomina.dessubnom ".
					"  FROM sno_personalnomina, sno_subnomina ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
					"   AND sno_personalnomina.codper='".$as_codper."' ".
					"   AND sno_personalnomina.codemp = sno_subnomina.codemp ".
					"   AND sno_personalnomina.codnom = sno_subnomina.codnom ".				
					"   AND sno_personalnomina.codsubnom = sno_subnomina.codsubnom ";
		}
		else
		{
			$ls_sql="SELECT codsubnom, dessubnom ".
					"  FROM sno_subnomina ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codsubnom=$row["codsubnom"];
				$ls_dessubnom=$row["dessubnom"];
				$ls_sql="SELECT codsubnom ".
						"  FROM sno_subnomina ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codsubnom='".$ls_codsubnom."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$li_i=1;
					$ls_sql="INSERT INTO sno_subnomina(codemp,codnom,codsubnom,dessubnom)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codsubnom."','".$ls_dessubnom."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_subnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
			}
			if(($lb_valido)&&($li_i==1))
			{
				$lb_valido=$this->uf_update_nomina($as_codnombus);
			}
			
			$this->io_sql->free_result($rs_data);	
			
		}
		return $lb_valido;
	}// end function uf_importar_subnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_nomina($as_codnombus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_nomina
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//	      Returns: lb_valido True si se importó la subnòmina correctamente ó False si falló
		//	  Description: Funcion que actualiza que la nómina actual tenga subnòmina ó no
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_nomina ".
				"   SET subnom = 1 ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_update_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_update_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_personal($as_codnombus,$as_codper,$as_codcar,$as_codasicar,$as_codtab,$as_codpas,$as_codgra,$ai_sueper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_importar_personal
		//		   Access : private
		//      Arguments : as_codnombus  // Còdigo de Nòmina a buscar
		//      			as_codper  // Còdigo de personal
		//				    as_codcar  // código de Cargo selecionado
		//				    as_codasicar  // Código de Asignación de Cargo
		//				    as_codtab  // Código de Tabulador
		//				    as_codpas  // Código de Paso
		//				    as_codgra  // código de Grado
		//				    ai_sueper  // sueldo según la asiganción de cargo
		//	      Returns :	$lb_valido True si se importó el personal correctamente ó False si falló
		//	  Description : Funcion que busca la informaciòn del personal y lo inserta en la nòmina actual
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 23/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, ".
				"		depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, ".
				"		fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, ".
				"		fecsusper, cauegrper, codescdoc, codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, ".
				"		codunirac, fecascper, pagtaqper, grado, descasicar,coddep,salnorper,estencper ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$as_codnombus."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_codsubnom=$row["codsubnom"];
				$ls_codcar=$as_codcar;
				if($as_codcar=="0000000000")
				{
					$ls_codcar=$row["codcar"];
				}
				$ls_codasicar=$as_codasicar;
				
				if (array_key_exists('session_activa',$_SESSION))
				{	
					if($as_codasicar=="0000000000")
					{
						$ls_codasicar=$row["codasicar"];
					}
				}
				else
				{
					if($as_codasicar=="0000000")
					{
						$ls_codasicar=$row["codasicar"];
					}
				}	
				
				$ls_codtab=$as_codtab;
				if($as_codtab=="00000000000000000000")
				{
					$ls_codtab=$row["codtab"];
				}				
				$ls_codgra=$as_codgra;
				if($as_codgra=="00")
				{
					$ls_codgra=$row["codgra"];
				}
				$ls_codpas=$as_codpas;
				if($as_codpas=="00")
				{
					$ls_codpas=$row["codpas"];
				}
				$li_sueper=$ai_sueper;
				$li_sueper=str_replace(".","",$li_sueper);
				$li_sueper=str_replace(",",".",$li_sueper);
				if($ai_sueper=="0")
				{
					$li_sueper=$row["sueper"];
				}
				$li_horper=$row["horper"];			
				$ls_minorguniadm=$row["minorguniadm"];			
				$ls_ofiuniadm=$row["ofiuniadm"];			
				$ls_uniuniadm=$row["uniuniadm"];			
				$ls_depuniadm=$row["depuniadm"];			
				$ls_prouniadm=$row["prouniadm"];			
				$li_pagbanper=$row["pagbanper"];
				$ls_codban=$row["codban"];
				$ls_codcueban=$row["codcueban"];
				$ls_tipcuebanper=$row["tipcuebanper"];
				$ld_fecingper=$row["fecingper"];				
				$ls_estper=$row["staper"];
				$ls_cueaboper=$row["cueaboper"];
				$ld_fecculcontr=$row["fecculcontr"];
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				$ls_codtabvac=$row["codtabvac"];
				$li_sueintper=$row["sueintper"];
				$li_salnorper=$row["salnorper"];			
				$li_pagefeper=$row["pagefeper"];
				$li_sueproper=$row["sueproper"];			
				$ls_codage=$row["codage"];
				$ld_fecegrper=$row["fecegrper"];
				if($ld_fecegrper=="")
				{
					$ld_fecegrper="1900-01-01";
				}
				$ld_fecsusper=$row["fecsusper"];				
				if($ld_fecsusper=="")
				{
					$ld_fecsusper="1900-01-01";
				}
				$ls_cauegrper=$row["cauegrper"];
				$ls_codescdoc=$row["codescdoc"];
				$ls_codcladoc=$row["codcladoc"];
				$ls_codubifis=$row["codubifis"];
				$ls_tipcestic=$row["tipcestic"];
				$ls_quivacper=$row["quivacper"];
				$ls_conjub=$row["conjub"];
				$ls_catjub=$row["catjub"];
				$ls_codclavia=$row["codclavia"];
				$ls_codunirac=$row["codunirac"];
				$ld_fecascper=$row["fecascper"];
				$li_pagtaqper=$row["pagtaqper"];
				$ls_grado=$row["grado"];
				$ls_descasicar=$row["descasicar"];
				$ls_coddep=$row["coddep"];
				$ls_estencper=$row["estencper"];
				$ls_sql="SELECT codper ".
						"  FROM sno_personalnomina ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$ls_codper."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_personalnomina(codemp,codnom,codper,codsubnom,codtab,codasicar,codgra,codpas,sueper,horper,".
							"minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,pagbanper,codban,codcueban,tipcuebanper,codcar,fecingper,".
							"staper,cueaboper,fecculcontr,codded,codtipper,quivacper,codtabvac,sueintper,pagefeper,sueproper,codage,fecegrper,".
							"fecsusper,cauegrper,codescdoc,codcladoc,codubifis,tipcestic,conjub,catjub,codclavia,codunirac,fecascper, pagtaqper, grado, descasicar,salnorper,coddep, estencper)VALUES".
							"('".$this->ls_codemp."','".$this->ls_codnom."',".
							"'".$ls_codper."','".$ls_codsubnom."','".$ls_codtab."','".$ls_codasicar."','".$ls_codgra."','".$ls_codpas."',".
							"".$li_sueper.",".$li_horper.",'".$ls_minorguniadm."','".$ls_ofiuniadm."','".$ls_uniuniadm."','".$ls_depuniadm."',".
							"'".$ls_prouniadm."',".$li_pagbanper.",'".$ls_codban."','".$ls_codcueban."','".$ls_tipcuebanper."','".$ls_codcar."',".
							"'".$ld_fecingper."','".$ls_estper."','".$ls_cueaboper."','".$ld_fecculcontr."','".$ls_codded."','".$ls_codtipper."',".
							"'".$ls_quivacper."','".$ls_codtabvac."',".$li_sueintper.",".$li_pagefeper.",".$li_sueproper.",'".$ls_codage."',".
							"'".$ld_fecegrper."','".$ld_fecsusper."','".$ls_cauegrper."','".$ls_codescdoc."','".$ls_codcladoc."','".$ls_codubifis."',".
							"'".$ls_tipcestic."','".$ls_conjub."','".$ls_catjub."','".$ls_codclavia."','".$ls_codunirac."','".$ld_fecascper."',".$li_pagtaqper.",'".$ls_grado."','".$ls_descasicar."',".$li_salnorper.",'".$ls_coddep."','".$ls_estencper."')";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_conceptopersonal($ls_codper);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_constantepersonal($ls_codper);
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_constantes($as_codnombus,$as_codcons)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_constantes
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codcons  // Còdigo de la constante
		//	      Returns: lb_valido True si se importó la constante correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la constante de la nómina seleccionada y la inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codcons<>"")
		{
			$ls_sql="SELECT codemp, codnom, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon,conespseg, ".
			        " esttopmod, conperenc ".
					"  FROM sno_constante ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ".
					"   AND codcons='".$as_codcons."' ";
		}
		else
		{
			$ls_sql="SELECT codemp, codnom, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon,conespseg, ". 
			        " esttopmod, conperenc ".
					"  FROM sno_constante ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$as_codnombus."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codcons=$row["codcons"];
				$ls_nomcon=$row["nomcon"];
				$ls_unicon=$row["unicon"];
				$li_equcon=$row["equcon"];
				$li_topcon=$row["topcon"];
				$li_valcon=$row["valcon"];
				$li_reicon=$row["reicon"];
				$ls_tipnumcon=$row["tipnumcon"];
				$ls_conespseg=$row["conespseg"];
				$ls_esttopmod=$row["esttopmod"];
				$ls_perenc=$row["conperenc"];
				$ls_sql="SELECT codcons ".
						"  FROM sno_constante ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codcons='".$ls_codcons."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_constante(codemp,codnom,codcons,nomcon,unicon, equcon,topcon, valcon, ".
					        " reicon,tipnumcon,conespseg,esttopmod,conperenc) VALUES(".
							"'".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codcons."','".$ls_nomcon."','".$ls_unicon."', ".
							" ".$li_equcon.",".
							"".$li_topcon.",".$li_valcon.",".$li_reicon.",'".$ls_tipnumcon."','".$ls_conespseg."', ".
							" '".$ls_esttopmod."', '".$ls_perenc."' )";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_constantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_importar_constantespersonal($as_codnombus,$ls_codcons);
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_constantes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_constantespersonal($as_codnombus,$as_codcons)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_constantespersonal
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codcons  // Còdigo de la constante
		//	      Returns: lb_valido True si se importó las constante personal correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn de la constante personal de la nómina seleccionada y la inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper, codcons, moncon, montopcon ".
				"  FROM sno_constantepersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnombus."' ".
				"   AND codcons='".$as_codcons."' ".
				"   AND codper IN (SELECT codper ".
				"					  FROM sno_personalnomina ".
				"					 WHERE codemp='".$this->ls_codemp."' ".
				"					   AND codnom='".$this->ls_codnom."') ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_constantespersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$li_moncon=$row["moncon"];
				$li_topcon=$row["montopcon"];
				$ls_sql="SELECT codcons ".
						"  FROM sno_constantepersonal ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codcons='".$as_codcons."'".
						"   AND codper='".$ls_codper."'";
						
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_constantepersonal(codemp,codnom,codper,codcons,moncon,montopcon)VALUES('".$this->ls_codemp."',".
							"'".$this->ls_codnom."','".$ls_codper."','".$as_codcons."',".$li_moncon.",".$li_topcon.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_constantespersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_constantespersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_concepto($as_codnombus,$as_codconc,$ab_impcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_concepto
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codconc  // Còdigo del concepto
		//      		   ab_impcon  // valor que me indica si voy a importar las constantes relacionadas ó si ya se importaron
		//	      Returns: lb_valido True si se importó el concepto correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn del concepto seleccionado y lo inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, ".
				"		cueconcon, aplisrcon, sueintcon, intprocon, codpro, forpatcon, cueprepatcon, cueconpatcon, titretempcon, ".
				"		titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, conprenom, sueintvaccon, aplarccon, conprocon, ".
				"		estcla, intingcon, spi_cuenta, poringcon ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnombus."' ".
				"   AND codconc='".$as_codconc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconc=$row["codconc"];
				$ls_nomcon=$row["nomcon"];
				$ls_titcon=$row["titcon"];
				$ls_forcon=$row["forcon"];
				$li_acumaxcon=$row["acumaxcon"];
				$li_valmincon=$row["valmincon"];
				$li_valmaxcon=$row["valmaxcon"];
				$ls_concon=$row["concon"];
				$ls_cueprecon=$row["cueprecon"];
				$ls_cueconcon=$row["cueconcon"];
				$ls_codpro=$row["codpro"];
				$ls_sigcon=$row["sigcon"];
				$ls_glocon=$row["glocon"];
				$ls_aplisrcon=$row["aplisrcon"];
				$ls_sueintcon=$row["sueintcon"];
				$ls_intprocon=$row["intprocon"];
				$ls_forpatcon=$row["forpatcon"];
				$ls_cueprepatcon=$row["cueprepatcon"];
				$ls_cueconpatcon=$row["cueconpatcon"];
				$ls_titretempcon=$row["titretempcon"];
				$ls_titretpatcon=$row["titretpatcon"];
				$li_valminpatcon=$row["valminpatcon"];
				$li_valmaxpatcon=$row["valmaxpatcon"];
				$li_conprenom=$row["conprenom"];
				$li_sueintvaccon=$row["sueintvaccon"];
				$ls_codprov=$row["codprov"];
				$ls_cedben=$row["cedben"];
				$ls_conprocon=$row["conprocon"];
				$ls_estcla=$row["estcla"];
				$li_intingcon=$row["intingcon"];
				$ls_spicuenta=$row["spi_cuenta"];
				$li_poringcon=$row["poringcon"];
				$ls_sql="SELECT codconc ".
						"  FROM sno_concepto ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codconc='".$ls_codconc."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_concepto(codemp,codnom,codconc,nomcon,titcon,forcon,acumaxcon,valmincon,valmaxcon,concon,cueprecon,".
							"cueconcon,codpro,sigcon,glocon,aplisrcon,sueintcon,intprocon,forpatcon,cueprepatcon,cueconpatcon,titretempcon,".
							"titretpatcon,valminpatcon,valmaxpatcon,conprenom,sueintvaccon,codprov,cedben,conprocon,estcla, intingcon, ".
							"spi_cuenta, poringcon)VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codconc."',".
							"'".$ls_nomcon."','".$ls_titcon."','".$ls_forcon."',".$li_acumaxcon.",".$li_valmincon.",".$li_valmaxcon.",'".$ls_concon."',".
							"'".$ls_cueprecon."','".$ls_cueconcon."','".$ls_codpro."','".$ls_sigcon."','".$ls_glocon."','".$ls_aplisrcon."',".
							"'".$ls_sueintcon."','".$ls_intprocon."','".$ls_forpatcon."','".$ls_cueprepatcon."','".$ls_cueconpatcon."','".$ls_titretempcon."',".
							"'".$ls_titretpatcon."',".$li_valminpatcon.",".$li_valmaxpatcon.",".$li_conprenom.",".$li_sueintvaccon.",".
							"'".$ls_codprov."','".$ls_cedben."','".$ls_conprocon."','".$ls_estcla."',".$li_intingcon.",'".$ls_spicuenta."',".$li_poringcon.")";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_importar_conceptopersonal($as_codnombus,$ls_codconc);
					}
					if($lb_valido)// se importan los conceptos que tiene asociado este concepto
					{
						$la_valores="";
						$lb_valido=$this->uf_select_constantesconcepto("CN[",$ls_forcon,$la_valores);
						if(!empty($la_valores))
						{
							$li_total=count($la_valores);
							for($li_i=1;(($li_i<=$li_total)&&($lb_valido));$li_i++)
							{
								$ls_codconc=$la_valores[$li_i];
								$lb_valido=$this->uf_importar_concepto($as_codnombus,$ls_codconc,$ab_impcon);
							}
						}
					}
					if(($lb_valido)&&($ab_impcon))// si solo se van a importar las constantes que tiene asociada este concepto
					{
						$la_valores="";
						$lb_valido=$this->uf_select_constantesconcepto("CT[",$ls_forcon,$la_valores);
						if(!empty($la_valores))
						{
							$li_total=count($la_valores);
							for($li_i=1;(($li_i<=$li_total)&&($lb_valido));$li_i++)
							{
								$ls_codcons=$la_valores[$li_i];
								$lb_valido=$this->uf_importar_constantes($as_codnombus,$ls_codcons);
							}
						}
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_importar_conceptopersonal($as_codnombus,$as_codconc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_importar_conceptopersonal
		//		   Access: private
		//      Arguments: as_codnombus  // Còdigo de Nòmina a buscar
		//      		   as_codconc  // Còdigo del concepto
		//	      Returns: lb_valido True si se importó el concepto personal correctamente ó False si falló
		//	  Description: Funcion que busca la informaciòn del concepto personal de la nómina seleccionada y la inserta en la nòmina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
				"  FROM sno_conceptopersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnombus."' ".
				"   AND codconc='".$as_codconc."' ".
				"   AND codper IN (SELECT codper ".
				"					  FROM sno_personalnomina ".
				"					 WHERE codemp='".$this->ls_codemp."' ".
				"					   AND codnom='".$this->ls_codnom."') ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_conceptopersonal select ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_aplcon=$row["aplcon"];
				$li_valcon=$row["valcon"];
				$ls_sql="SELECT codconc ".
						"  FROM sno_conceptopersonal ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codconc='".$as_codconc."'".
						"   AND codper='".$ls_codper."'";
				if(!$this->uf_existeregistro($ls_sql))
				{
					$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)".
							"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_codper."','".$as_codconc."','".$ls_aplcon."',".
							"".$li_valcon.",0,0,0,0)";
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_importar_conceptopersonal insert ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_importar_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_constantesconcepto($as_exp,$as_formula,&$aa_valores)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_constantesconcepto
		//		   Access: private
		//	    Arguments: as_exp // Expresión que me identifica que tipo de valor se va a buscar
		//				   as_formula // fórmula del concepto
		//				   aa_valores // arreglo de todas los valores obtenidos
		//	      Returns: lb_valido True si se obtiene correctamente las constantes ó False si hubo error 
		//	  Description: función que dado una formula obtiene los códigos de las constantes y conceptos requeridos por este concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_formula=trim($as_formula);
		$li_pos=strpos($as_formula,$as_exp);
		$li_total=0;
		if($li_pos===false)
		{
			$li_pos=-1;
		}
		while (($li_pos>=0)&&($lb_valido))
		{
			$li=$li_pos;
			$ls_valor="";
			while (($li<strlen($as_formula))&&(substr($as_formula,$li,1)<>"]"))
			{
				$li=$li+1;
			}
			if($li==0)
			{
				$lb_valido=false;
				$li_pos=-1;
				break;
			}
			$ls_token=substr($as_formula,(strlen($as_exp)+$li_pos),($li-strlen($as_exp)-$li_pos));
			switch ($as_exp)
			{
				case "CN["://Valor de Concepto
					$ls_token=str_pad($ls_token,10,"0",0);
					$ls_valor=$ls_token;
					break;

				case "CT["://Valor de Constante
					$ls_token=str_pad($ls_token,10,"0",0);
					$ls_valor=$ls_token;
					break;
			}
			if($lb_valido)
			{
				$ls_token=substr($as_formula,$li_pos,$li-$li_pos+1);
				$as_formula=str_replace($ls_token,$ls_valor,$as_formula);
				if(strlen($as_formula)>$li_pos)
				{
					$li_pos=strpos($as_formula,$as_exp,$li_pos);
					if($li_pos===false)
					{
						$li_pos=-1;
					}				
				}
				else
				{
					$li_pos=-1;
				}
				if($ls_valor!="")
				{
					$li_total=$li_total+1;
					$aa_valores[$li_total]=$ls_valor;
				}				
			}
		}
		return $lb_valido;
	}// end function uf_select_constantesconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que graba los conceptos a personal nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconc=$row["codconc"];
				$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codconc."',1,0,0,0,0,0)";
	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constantepersonal($as_codper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_constantepersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que graba las constantes a personal nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codcons,valcon,topcon ".
				"  FROM sno_constante ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codcons=$row["codcons"];
				$li_valcon=$row["valcon"];
				$li_topcon=$row["topcon"];
				
				$ls_sql="INSERT INTO sno_constantepersonal(codemp,codnom,codper,codcons,moncon,montopcon)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codcons."','".$li_valcon."',".$li_topcon.")";

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_insert_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ocupados($as_codnom,$as_codasicar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_ocupados
		//		   Access: private
		//	    Arguments: as_codnom  // código de Nómina
		//				   as_codasicar // Código de la Asignación de Cargo	
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Función que le suma ó le resta a el número de puestos ocupados en la asignación de cargo a la nómina correspondiente
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/10/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_asignacioncargo ".
				"   SET numocuasicar= (SELECT COUNT(codper) ".
				"						 FROM sno_personalnomina ".
				"                       WHERE codemp='".$this->ls_codemp."' ".
				"                         AND codnom='".$as_codnom."' ".
				"                         AND codasicar='".$as_codasicar."') ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"  AND codnom='".$as_codnom."'".
				"  AND codasicar='".$as_codasicar."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar Definiciones MÉTODO->uf_update_ocupados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Actualizó el número de puestos ocupados a la asignación de cargo  asociado a la nómina ".$as_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_ocupados
	//-----------------------------------------------------------------------------------------------------------------------------------	

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>