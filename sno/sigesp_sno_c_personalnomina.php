<?php
class sigesp_sno_c_personalnomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_prestamo;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_personalnomina()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_personalnomina
		//		   Access: public (sigesp_sno_d_personalnomina)
		//	  Description: Constructor de la Clase
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
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
		require_once("sigesp_sno_c_prestamo.php");
		$this->io_prestamo= new sigesp_sno_c_prestamo();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
			$this->li_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
			$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		}
		else
		{
			$this->ls_codnom="0000";
			$this->li_anocurnom="0000";
			$this->ls_peractnom="000";
		}
		
	}// end function sigesp_sno_c_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_personalnomina)
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
		unset($this->io_prestamo);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuenta($as_codper,$ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuenta
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_pagbanper  // Si se paga por banco
		//				   as_codban  // Código de banco
		//				   as_codcueban  // Código de Cuenta bancaria
		//				   as_tipcuebanper  // Código de tipo de cuenta bancaria
		//	      Returns: lb_existe True si no existe ó False si existe
		//	  Description: Funcion que verifica si la cuenta está asociada a otro personal 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		if($ai_pagbanper=="1")
		{
			$ls_sql="SELECT codcueban ".
					"  FROM sno_personalnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND codban='".$as_codban."'".
					"   AND codcueban='".$as_codcueban."'".
					"   AND codper<>'".$as_codper."'";

			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_select_cuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_existe=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$lb_existe=false;
					$this->io_mensajes->message("El código de cuenta ya está asociado a otro personal");
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		return $lb_existe;
	}// end function uf_select_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personalnomina($as_campo,$as_valor,$as_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_personalnomina
		//		   Access: public (sigesp_snorh_d_tablavacacion, uf_guardar)
		//	    Arguments: as_campo // Campo por el cual se quiere filtrar
		//				   as_valor // Valor del campo filtro
		//				   as_tipo  // Tipo de llamada si se toma en cuenta la nómina ó si no importa
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ".$as_campo."='".$as_valor."'";

		if($as_tipo=="1") //Importa la nómina
		{
			$ls_sql=$ls_sql."   AND (codnom='".$this->ls_codnom."')";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_select_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalnomina($as_codper,$as_codsubnom,$as_codasicar,$as_codcar,$as_codtab,$as_codpas,
								 	  $as_codgra,$as_coduniadm,$ai_sueper,$ai_horper,$ai_sueintper,$ai_sueproper,
								 	  $ad_fecingper,$ad_fecculcontr,$as_codded,$as_codtipper,$as_codtabvac,$ai_pagefeper,
									  $ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper,$as_cueaboper,$as_codage,
								 	  $as_tipcestic,$as_codescdoc,$as_codcladoc,$as_codubifis,$as_conjub,$as_catjub,$as_codclavia,
									  $as_codunirac,$ai_pagtaqper,$ad_fecascper,$as_grado, $ai_descasicar, 
									  $as_coddep, $ai_salnorper, $aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalnomina
		//		   Access: private
		//	    Arguments: as_codper  // código de personal                 as_codsubnom  // código de subnomina
		//				   as_codasicar  // código de asignación de cargo   as_codcar  // código de cargo
		//				   as_codtab  // código de tabla                    as_codpas  // código de paso
		//				   as_codgra  // código de grado                    as_coduniadm  // código de unidad administrativa
		//				   ai_sueper  // Sueldo                             ai_horper  // hora 
		//				   ai_sueintper  // sueldo integral                 ai_sueproper  // sueldo promedio  
		//				   ad_fecingper  // fecha de ingreso                ad_fecculcontr  // fecha de culminación de contrato
		//				   as_codded  // código de dedicación               as_codtipper  // código de tipo de personal
		//				   as_codtabvac  // código de tabla de vacaciones   ai_pagefeper  // pago en efectivo 
		//				   ai_pagbanper  // pago en banco                   as_codban  // código de banco
		//				   as_codcueban  // código de cuenta bancaria       as_tipcuebanper  // tipo de cuenta bancaria 
		//				   as_cueaboper  // cuenta de abono                 as_codage  // código de agencia 
		//				   as_tipcestic  // tipo de cesta ticket            as_codescdoc  // código escala docente
		//				   as_codcladoc  // código clasificación docente    as_codubifis  // código ubicación física 
		//				   as_conjub  // condición de jubilacion			as_catjub // Categoría de jubilación
		//				   ai_descasicar  // denominación de la asiganción de cargo	
		//				   as_coddep	   // codigo del departamento adscripto a la unidad administrativa
		//                 ai_salnorper    // salario normal de la personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el personalnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_minorguniadm=substr($as_coduniadm,0,4);
		$ls_ofiuniadm=substr($as_coduniadm,5,2);
		$ls_uniuniadm=substr($as_coduniadm,8,2);
		$ls_depuniadm=substr($as_coduniadm,11,2);
		$ls_prouniadm=substr($as_coduniadm,14,2);
		$ls_sql="INSERT INTO sno_personalnomina(codemp,codnom,codper,codsubnom,codasicar,codcar,codtab,codpas,codgra,minorguniadm,".
				"ofiuniadm,uniuniadm,depuniadm,prouniadm,sueper,horper,sueintper,sueproper,fecingper,fecculcontr,codded,codtipper,".
				"codtabvac,pagefeper,pagbanper,codban,codcueban,tipcuebanper,cueaboper,codage,tipcestic,codescdoc,codcladoc,codubifis,".
				"staper,conjub,catjub,codclavia,codunirac,pagtaqper,fecascper,grado,descasicar, coddep,salnorper)VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$as_codsubnom."','".$as_codasicar."',".
				"'".$as_codcar."','".$as_codtab."','".$as_codpas."','".$as_codgra."','".$ls_minorguniadm."','".$ls_ofiuniadm."',".
				"'".$ls_uniuniadm."','".$ls_depuniadm."','".$ls_prouniadm."',".$ai_sueper.",".$ai_horper.",".$ai_sueintper.",".
				"".$ai_sueproper.",'".$ad_fecingper."','".$ad_fecculcontr."','".$as_codded."','".$as_codtipper."','".$as_codtabvac."',".
				"".$ai_pagefeper.",".$ai_pagbanper.",'".$as_codban."','".$as_codcueban."','".$as_tipcuebanper."','".$as_cueaboper."',".
				"'".$as_codage."','".$as_tipcestic."','".$as_codescdoc."','".$as_codcladoc."','".$as_codubifis."','1','".$as_conjub."',".
				"'".$as_catjub."','".$as_codclavia."','".$as_codunirac."',".$ai_pagtaqper.",'".$ad_fecascper."','".
				$as_grado."','".$ai_descasicar."','".$as_coddep."', ".$ai_salnorper.")";
		$this->io_sql->begin_transaction();		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el personal nómina ".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		

			if($lb_valido)
			{			
				$lb_valido=$this->uf_insert_conceptopersonal($as_codper,$aa_seguridad);
			}

			if($lb_valido)
			{			
				$lb_valido=$this->uf_insert_constantepersonal($as_codper,$aa_seguridad);
			}
			if($lb_valido)
			{			
				$lb_valido=$this->uf_update_estcodunirac($as_codasicar,$aa_seguridad);
			}
			if($lb_valido)
			{			
				$lb_valido=$this->uf_update_ocupados($as_codasicar,$as_codper,"+",$aa_seguridad);
			}
			if($lb_valido)
			{
				$this->io_mensajes->message("El Personal fue registrado en la nómina.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("Ocurrio un Error al Registrar el personal en la nómina.");
			}
		}
		return $lb_valido;
	}// end function uf_insert_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codper,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que graba los conceptos a personal nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconc=$row["codconc"];
				$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat)".
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codconc."',0,0,0,0,0,0)";
	
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el conceptopersonal concepto ".$ls_codconc." personal nómina ".$as_codper." asociado a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_constantepersonal($as_codper,$aa_seguridad)
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
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
						"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$ls_codcons."','".$li_valcon."','".$li_topcon."')";

				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_insert_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la constantepersonal constante ".$ls_codcons." personal nómina ".$as_codper." asociado a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_insert_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ocupados($as_codasicar,$as_codper,$as_signo,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_ocupados
		//		   Access: private
		//	    Arguments: as_codasicar  // código de asignación de cargo
		//	    		   as_codper  // código de personal
		//	    		   as_signo  // Signo si se van a sumar ó restar los cargos
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Función que le suma ó le resta a el número de puestos ocupados en la asignación de cargo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_signo=="-")
		{
			$ls_sql="UPDATE sno_asignacioncargo ".
					"   SET numocuasicar=(numocuasicar".$as_signo."1) ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codasicar IN (SELECT codasicar ".
					"					     FROM sno_personalnomina ".
					" 						WHERE codemp='".$this->ls_codemp."' ".
					"                         AND codnom='".$this->ls_codnom."' ".
					"                         AND codper='".$as_codper."')";
		}
		else
		{
			$ls_sql="UPDATE sno_asignacioncargo ".
					"   SET numocuasicar=(numocuasicar".$as_signo."1) ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codasicar='".$as_codasicar."' ";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_ocupados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Actualizó el número de puestos ocupados a la asignación de cargo ".$as_codasicar." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_ocupados
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnomina($as_codper,$as_codsubnom,$as_codasicar,$as_codcar,$as_codtab,$as_codpas,
								 	  $as_codgra,$as_coduniadm,$ai_sueper,$ai_horper,$ai_sueintper,$ai_sueproper,
								 	  $ad_fecingper,$ad_fecculcontr,$as_codded,$as_codtipper,$as_codtabvac,$ai_pagefeper,
									  $ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper,$as_cueaboper,$as_codage,
								 	  $as_tipcestic,$as_codescdoc,$as_codcladoc,$as_codubifis,$as_conjub,$as_catjub,$as_codclavia,
									  $as_codunirac,$ai_pagtaqper,$ad_fecascper,$as_grado,$ai_descasicar,
									  $as_coddep,$ai_salnorper, $aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnomina
		//		   Access: private
		//	    Arguments: as_codper  // código de personal                 as_codsubnom  // código de subnomina
		//				   as_codasicar  // código de asignación de cargo   as_codcar  // código de cargo
		//				   as_codtab  // código de tabla                    as_codpas  // código de paso
		//				   as_codgra  // código de grado                    as_coduniadm  // código de unidad administrativa
		//				   ai_sueper  // Sueldo                             ai_horper  // hora 
		//				   ai_sueintper  // sueldo integral                 ai_sueproper  // sueldo promedio  
		//				   ad_fecingper  // fecha de ingreso                ad_fecculcontr  // fecha de culminación de contrato
		//				   as_codded  // código de dedicación               as_codtipper  // código de tipo de personal
		//				   as_codtabvac  // código de tabla de vacaciones   ai_pagefeper  // pago en efectivo 
		//				   ai_pagbanper  // pago en banco                   as_codban  // código de banco
		//				   as_codcueban  // código de cuenta bancaria       as_tipcuebanper  // tipo de cuenta bancaria 
		//				   as_cueaboper  // cuenta de abono                 as_codage  // código de agencia 
		//				   as_tipcestic  // tipo de cesta ticket            as_codescdoc  // código escala docente
		//				   as_codcladoc  // código clasificación docente    as_codubifis  // código ubicación física 
		//				   as_conjub  // condición de jubilacion			as_catjub  // categoria de jubilación
		//				   ai_descasicar  // denominación de la asiganción de cargo
		//                 as_coddep  // codigo del departamento adscrito a la unidad administrativa
		//                 ai_salnorper // salario normal de la personal
 		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el personalnomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$lb_valido=$this->uf_update_ocupados($as_codasicar,$as_codper,"-",$aa_seguridad);
		if($lb_valido)
		{									
			$ls_minorguniadm=substr($as_coduniadm,0,4);
			$ls_ofiuniadm=substr($as_coduniadm,5,2);
			$ls_uniuniadm=substr($as_coduniadm,8,2);
			$ls_depuniadm=substr($as_coduniadm,11,2);
			$ls_prouniadm=substr($as_coduniadm,14,2);
			$ls_sql="UPDATE sno_personalnomina ".
					"   SET codsubnom='".$as_codsubnom."',".
					"		codasicar='".$as_codasicar."',".
					"		codcar='".$as_codcar."',".
					"		codtab='".$as_codtab."',".
					"		codpas='".$as_codpas."',".
					"		codgra='".$as_codgra."',".
					"		minorguniadm='".$ls_minorguniadm."',".
					"		ofiuniadm='".$ls_ofiuniadm."',".
					"		uniuniadm='".$ls_uniuniadm."',".
					"		depuniadm='".$ls_depuniadm."',".
					"		prouniadm='".$ls_prouniadm."',".
					"		sueper=".$ai_sueper.",".
					"		horper=".$ai_horper.",".
					"		sueintper=".$ai_sueintper.",".
					"		sueproper=".$ai_sueproper.",".
					"		fecingper='".$ad_fecingper."',".
					"		fecculcontr='".$ad_fecculcontr."',".
					"		codded='".$as_codded."',".
					"		codtipper='".$as_codtipper."',".
					"		codtabvac='".$as_codtabvac."',".
					"		pagefeper=".$ai_pagefeper.",".
					"		pagbanper=".$ai_pagbanper.",".
					"		codban='".$as_codban."',".
					"		codcueban='".$as_codcueban."',".
					"		tipcuebanper='".$as_tipcuebanper."',".
					"		cueaboper='".$as_cueaboper."',".
					"		codage='".$as_codage."',".
					"		tipcestic='".$as_tipcestic."', ".
					"		codescdoc='".$as_codescdoc."', ".
					"		codcladoc='".$as_codcladoc."', ".
					"		codubifis='".$as_codubifis."', ".
					"		conjub='".$as_conjub."', ".
					"		catjub='".$as_catjub."', ".
					"		codclavia='".$as_codclavia."', ".
					"       codunirac='".$as_codunirac."', ".
					"       pagtaqper=".$ai_pagtaqper.", ".
					"		fecascper= '".$ad_fecascper."', ".
					"       grado='".$as_grado."', ".
					"       descasicar='".$ai_descasicar."', ".
					"       coddep='".$as_coddep."', ".
					"       salnorper=".$ai_salnorper." ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND codper='".$as_codper."'";
	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el Personal Nomina ".$as_codper." asociado a la nómina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{			
					$lb_valido=$this->uf_update_estcodunirac($as_codasicar,$aa_seguridad);
				}
				if($lb_valido)
				{			
					$lb_valido=$this->uf_update_ocupados($as_codasicar,$as_codper,"+",$aa_seguridad);
				}
				if($lb_valido)
				{	
					$this->io_mensajes->message("El personal fue Actualizado en la nómina.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_update_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_codsubnom,$as_codasicar,$as_codcar,$as_codtab,$as_codpas,$as_codgra,$as_coduniadm,
						$ai_sueper,$ai_horper,$ai_sueintper,$ai_sueproper,$ad_fecingper,$ad_fecculcontr,$as_codded,$as_codtipper,
						$as_codtabvac,$ai_pagefeper,$ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper,$as_cueaboper,$as_codage,
						$as_tipcestic,$as_codescdoc,$as_codcladoc,$as_codubifis,$as_conjub,$as_catjub,$as_codclavia,$as_codunirac,
						$ai_pagtaqper,$ad_fecascper,$as_grado,$ai_suebasper,$ai_priespper,$ai_pritraper,$ai_priproper,$ai_prianoserper,
						$ai_pridesper,$ai_porpenper,$ai_prinoascper,$ai_monpenper,$ai_subtotper,$ai_descasicar,
						$as_coddep, $ad_fecvid, $ai_primrem, $ai_segrem, $as_tippen,$ai_salnorper, $aa_seguridad)
	{		
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_personalnomina)
		//	    Arguments: as_codper  // código de personal                 as_codsubnom  // código de subnomina
		//				   as_codasicar  // código de asignación de cargo   as_codcar  // código de cargo
		//				   as_codtab  // código de tabla                    as_codpas  // código de paso
		//				   as_codgra  // código de grado                    as_coduniadm  // código de unidad administrativa
		//				   ai_sueper  // Sueldo                             ai_horper  // hora 
		//				   ai_sueintper  // sueldo integral                 ai_sueproper  // sueldo promedio  
		//				   ad_fecingper  // fecha de ingreso                ad_fecculcontr  // fecha de culminación de contrato
		//				   as_codded  // código de dedicación               as_codtipper  // código de tipo de personal
		//				   as_codtabvac  // código de tabla de vacaciones   ai_pagefeper  // pago en efectivo 
		//				   ai_pagbanper  // pago en banco                   as_codban  // código de banco
		//				   as_codcueban  // código de cuenta bancaria       as_tipcuebanper  // tipo de cuenta bancaria 
		//				   as_cueaboper  // cuenta de abono                 as_codage  // código de agencia 
		//				   as_tipcestic  // tipo de cesta ticket            as_codescdoc  // código escala docente
		//				   as_codcladoc  // código clasificación docente    as_codubifis  // código ubicación física 
		//				   as_conjub  // condición de jubilacion			as_catjub  // Categoría de Jubilación
		//				   as_codunirac // Código ünico en el RAC           ai_pagtaqper // Pago por taquilla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//				   ad_fecascper  // Fecha de ascendo del personal
		//				   ai_suebasper  // Sueldo Basico del personal para pensiones
		//				   ai_priespper  // Prima especial del personal para pensiones
		//				   ai_pritraper  // prima transporte del personal para pensiones
		//				   ai_priproper  // Prima profesionalizacion del personal para pensiones
		//				   ai_prianoserper  // Prima Años Servicios del personal para pensiones
		//				   ai_pridesper  // Prima Descendencia del personal para pensiones
		//				   ai_porpenper  // Procentaje  del personal para pensiones
		//				   ai_prinoascper  // Prima No ascenso del personal para pensiones
		//				   ai_monpenper  // Monto pension del personal para pensiones
		//				   ai_subtotper  // Sub total  del personal	para pensiones
		//				   $ai_descasicar  // descripcion de la asiganciòn del cargo
		//                 as_coddep // codigo del departamento adscrito a la unidad administrativa
		//                 ad_fecvid // fecha de e de vida
		//                 ai_primrem // primera remuneración
		//                 ai_segrem  // segunda remuneración
		//                 as_tippen // tipo de pensión
		//                 ai_salnorper // salario normal de la persona
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda el personal nomina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ad_fecingper=$this->io_funciones->uf_convertirdatetobd($ad_fecingper);
		$ad_fecculcontr=$this->io_funciones->uf_convertirdatetobd($ad_fecculcontr);
		$ad_fecascper=$this->io_funciones->uf_convertirdatetobd($ad_fecascper);
		$ad_fecvid=$this->io_funciones->uf_convertirdatetobd($ad_fecvid);
		if ($ad_fecvid=="aaaa-mm-dd")
		{
			$ad_fecvid='1900-01-01';
		}
		$ai_sueper=str_replace(".","",$ai_sueper);
		$ai_sueper=str_replace(",",".",$ai_sueper);				
		$ai_horper=str_replace(".","",$ai_horper);
		$ai_horper=str_replace(",",".",$ai_horper);				
		$ai_sueintper=str_replace(".","",$ai_sueintper);
		$ai_sueintper=str_replace(",",".",$ai_sueintper);		
		$ai_salnorper=str_replace(".","",$ai_salnorper);
		$ai_salnorper=str_replace(",",".",$ai_salnorper);						
		$ai_sueproper=str_replace(".","",$ai_sueproper);
		$ai_sueproper=str_replace(",",".",$ai_sueproper);	
		$ai_suebasper=str_replace(".","",$ai_suebasper);
		$ai_suebasper=str_replace(",",".",$ai_suebasper);				
		$ai_priespper=str_replace(".","",$ai_priespper);
		$ai_priespper=str_replace(",",".",$ai_priespper);				
		$ai_pritraper=str_replace(".","",$ai_pritraper);
		$ai_pritraper=str_replace(",",".",$ai_pritraper);				
		$ai_priproper=str_replace(".","",$ai_priproper);
		$ai_priproper=str_replace(",",".",$ai_priproper);				
		$ai_prianoserper=str_replace(".","",$ai_prianoserper);
		$ai_prianoserper=str_replace(",",".",$ai_prianoserper);				
		$ai_pridesper=str_replace(".","",$ai_pridesper);
		$ai_pridesper=str_replace(",",".",$ai_pridesper);				
		$ai_porpenper=str_replace(".","",$ai_porpenper);
		$ai_porpenper=str_replace(",",".",$ai_porpenper);				
		$ai_prinoascper=str_replace(".","",$ai_prinoascper);
		$ai_prinoascper=str_replace(",",".",$ai_prinoascper);				
		$ai_monpenper=str_replace(".","",$ai_monpenper);
		$ai_monpenper=str_replace(",",".",$ai_monpenper);				
		$ai_subtotper=str_replace(".","",$ai_subtotper);
		$ai_subtotper=str_replace(",",".",$ai_subtotper);				
		$li_implementarcodunirac=trim($this->io_sno->uf_select_config("SNO","CONFIG","CODIGO_UNICO_RAC","0","I"));
		if(($li_implementarcodunirac=="1")&&($_SESSION["la_nomina"]["racnom"]=="1"))
		{
			$lb_valido=$this->uf_verificar_codigo_rac($as_codunirac,$as_codper);
		}
		if(($this->uf_select_cuenta($as_codper,$ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper))&&($lb_valido))
		{			
			switch ($as_existe)
			{
				case "FALSE":
					if($this->uf_select_personalnomina("codper",$as_codper,"1")===false)
					{
						$lb_valido=$this->uf_insert_personalnomina($as_codper,$as_codsubnom,$as_codasicar,$as_codcar,$as_codtab,$as_codpas,$as_codgra,$as_coduniadm,
							$ai_sueper,$ai_horper,$ai_sueintper,$ai_sueproper,$ad_fecingper,$ad_fecculcontr,$as_codded,$as_codtipper,
							$as_codtabvac,$ai_pagefeper,$ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper,$as_cueaboper,$as_codage,
							$as_tipcestic,$as_codescdoc,$as_codcladoc,$as_codubifis,$as_conjub,$as_catjub,$as_codclavia,$as_codunirac,
							$ai_pagtaqper,$ad_fecascper,$as_grado,$ai_descasicar,$as_coddep,$ai_salnorper, $aa_seguridad);
							
						
					}
					else
					{
						$this->io_mensajes->message("El personal ya existe en la nómina, no lo puede incluir");
					}
					break;
	
				case "TRUE":
					if(($this->uf_select_personalnomina("codper",$as_codper,"1")))
					{
						$lb_valido=$this->uf_update_personalnomina($as_codper,$as_codsubnom,$as_codasicar,$as_codcar,$as_codtab,$as_codpas,$as_codgra,$as_coduniadm,
							$ai_sueper,$ai_horper,$ai_sueintper,$ai_sueproper,$ad_fecingper,$ad_fecculcontr,$as_codded,$as_codtipper,
							$as_codtabvac,$ai_pagefeper,$ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper,$as_cueaboper,$as_codage,
							$as_tipcestic,$as_codescdoc,$as_codcladoc,$as_codubifis,$as_conjub,$as_catjub,$as_codclavia,$as_codunirac,
							$ai_pagtaqper,$ad_fecascper,$as_grado,$ai_descasicar, $as_coddep,$ai_salnorper, $aa_seguridad);
					}
					else
					{
						$this->io_mensajes->message("El personal no existe en la nómina, no lo puede actualizar");
					}
					break;
			}
			
			if(($lb_valido)&&($_SESSION["la_nomina"]["tipnom"]=="12"))// Nómina de pensiones
			{
			    $ai_primrem=str_replace(",",".",$ai_primrem);
				$ai_segrem=str_replace(",",".",$ai_segrem);
				$lb_valido=$this->uf_insert_pension($as_codper,$ai_suebasper,$ai_priespper,$ai_pritraper,$ai_priproper,
				                                    $ai_prianoserper,
													$ai_pridesper,$ai_porpenper,$ai_prinoascper,$ai_monpenper,$ai_subtotper,
													$ad_fecvid, $ai_primrem, $ai_segrem, $as_tippen, $aa_seguridad);
			}
			if($lb_valido)
			{
				$li_incluirbeneficiario=trim($this->io_sno->uf_select_config("SNO","CONFIG","INCLUIR_A_BENEFICIARIO","0","I"));
				if($li_incluirbeneficiario=="1")// Pasa de personal a beneficiarios.
				{
					$lb_valido=$this->uf_update_beneficiario($as_codper,$as_codban,$as_codcueban,$aa_seguridad);
				}
			}
		}
		return $lb_valido;
	}// end function uf_guardar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_salida($as_codper)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ninguna salida tenga asociada este personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codper ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_select_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_salida	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_resumen($as_codper)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_resumen
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún resumen tenga asociada este pesonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codper ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_select_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
	       		$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_resumen	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_conceptopersonal($as_codper,$aa_seguridad)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el conceptopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_conceptopersonal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'";
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó los conceptopersonal asociados al personal ".$as_codper." asociada a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
    }// end function uf_delete_conceptopersonal	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_constantepersonal($as_codper,$aa_seguridad)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_constantepersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el constantepersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_constantepersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'";
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_constantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));		
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las constantepersonal asociados al personal ".$as_codper." asociada a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}	
		return $lb_valido;
    }// end function uf_delete_constantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_personalnomina($as_codper,$as_codasicar,$aa_seguridad)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_personalnomina
		//		   Access: public (sigesp_sno_d_personalnomina)
		//	    Arguments: as_codper  // código de personal
		//				   as_codasicar  // código de Asignación de cargo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el personal nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (($this->uf_select_salida($as_codper)===false)&&($this->uf_select_resumen($as_codper)===false)
		  &&($this->io_prestamo->uf_select_prestamo("codper",$as_codper)===false))
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_delete_conceptopersonal($as_codper,$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_constantepersonal($as_codper,$aa_seguridad);
			}
			if($lb_valido)
			{	
				$lb_valido=$this->uf_update_ocupados($as_codasicar,$as_codper,"-",$aa_seguridad);
			}
			if($lb_valido)
			{			
				$ls_sql="DELETE ".
						"  FROM sno_personalnomina ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$as_codper."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el personalnomina personal ".$as_codper." asociada a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El personal fue Eliminado en la nómina.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_delete_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el personal.Hay Prestamos asociados a este ó ya se calculó la nómina.");
		}       
		return $lb_valido;
    }// end function uf_delete_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus($as_codper,$as_estper,$ad_fecegrper,$as_obsegrper,$as_tipo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus
		//		   Access: public (sigesp_snorh_p_personalcambioestatus,sigesp_sno_p_personalcambioestatus)
		//	    Arguments: as_codper  // Código de Personal
		//				   as_estper  // Estatus de Personal
		//				   ad_fecegrper  // Fecha de Egreso/Suspensión
		//				   as_obsegrper // Observación del Egreso/Suspensión		
		//				   as_tipo // Tipo de llamada al método se si cambió desde Personal==1, Desde la Nómina==2 
		//							  ó Desde movimiento entre nóminas==3
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el cambio ó False si hubo error al ejecutar el cambio
		//	  Description: Funcion que actualiza el estatus del personal
		//				    esta función es llamada de la pantalla sigesp_snorh_p_personalcambioestatus.php	
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecegrper=$this->io_funciones->uf_convertirdatetobd($ad_fecegrper);
		$ls_criterio="";
		$ls_signo="";
		if($as_tipo=="2")// Desde Nómina solo se le cambia en la nómina actual
		{
			$ls_criterio="	AND codnom='".$this->ls_codnom."'";
			$this->io_sql->begin_transaction();
		}
		switch($as_estper)
		{
			case "0": // NO ASIGNADO
				$ls_sql="UPDATE sno_personalnomina ".
						"   SET staper='".$as_estper."', ".
						"		fecegrper='".$ad_fecegrper."', ".
						"		cauegrper='".$as_obsegrper."' ".
						" WHERE codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"	AND codper='".$as_codper."'";
				$ls_signo="+";
				break;

			case "1": // ACTIVO
				$ls_sql="UPDATE sno_personalnomina ".
						"   SET staper='".$as_estper."', ".
						"		fecegrper='1900-01-01', ".
						"		cauegrper='' ".
						" WHERE codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"	AND codper='".$as_codper."'";
				$ls_signo="+";
				break;
				
			case "2": // VACACIONES
				$ls_sql="UPDATE sno_personalnomina ".
						"   SET staper='".$as_estper."', ".
						"		fecegrper='1900-01-01', ".
						"		cauegrper='' ".
						" WHERE codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"	AND codper='".$as_codper."'";
				break;
								
			case "3": // EGRESADO
				$ls_sql="UPDATE sno_personalnomina ".
						"   SET staper='".$as_estper."', ".
						"		fecegrper='".$ad_fecegrper."', ".
						"		cauegrper='".$as_obsegrper."' ".
						" WHERE codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"	AND codper='".$as_codper."'";
				$ls_signo="-";
				break;
				
			case "4": // SUSPENDIDO
				$ls_sql="UPDATE sno_personalnomina ".
						"   SET staper='".$as_estper."', ".
						"		fecsusper='".$ad_fecegrper."', ".
						"		cauegrper='".$as_obsegrper."' ".
						" WHERE codemp='".$this->ls_codemp."'".
						$ls_criterio.
						"	AND codper='".$as_codper."'";
				$ls_signo="-";				
				break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_estatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			if($ls_signo!="")
			{
				$ls_sql="UPDATE sno_asignacioncargo ".
						"   SET numocuasicar=(numocuasicar".$ls_signo."1) ".
						" WHERE codemp='".$this->ls_codemp."' ".
						$ls_criterio.
						"   AND codasicar IN (SELECT codasicar ".
						"					     FROM sno_personalnomina ".
						" 						WHERE sno_personalnomina.codemp=sno_asignacioncargo.codemp ".
						"                         AND sno_personalnomina.codnom=sno_asignacioncargo.codnom ".
						"                         AND sno_personalnomina.codper='".$as_codper."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_estatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
			if($lb_valido)
			{	
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Cambió el Estatus del personal nómina ".$as_codper." Nómina ".$this->ls_codnom." Estatus ".$as_estper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			if($lb_valido)
			{	
				if($as_tipo=="2")
				{
					$this->io_mensajes->message("El personal fue Actualizado en la nómina.");
					$this->io_sql->commit();
				}
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_estatus ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				if($as_tipo=="2")
				{
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_transferenciadatos($as_codnomdes,$as_codnomhas,$ai_tabulador,$ai_cargos,$ai_rac,$ai_sueldo,$ai_unidadadmin,
											$ai_banco,$ai_cuentabancaria,$ai_tipocuenta,$ai_cuentacontable,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_transferenciadatos
		//		   Access: public (sigesp_snorh_p_tranferenciadatos)
		//	    Arguments: as_codnomdes  // Código Nómina Desde
		//				   as_codnomhas  // Código Nómina Hasta
		//				   ai_tabulador  // Si se va a importar la información de Tabulador
		//				   ai_cargos // Si se va a importar la información de Cargos
		//				   ai_rac // Si se va a importar la información de registro de asignación de cargos
		//				   ai_sueldo  // Si se va a importar la información de Sueldo
		//				   ai_unidadadmin // Si se va a importar la información de unidad administrativa
		//				   ai_banco // Si se va a importar la información de banco
		//				   ai_cuentabancaria  // Si se va a importar la información de cuenta bnacaria
		//				   ai_tipocuenta // Si se va a importar la información de tipo de cuenta	
		//				   ai_cuentacontable // Si se va a importar la información de cuenta contable
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto la transferencia de datos ó False si hubo error al ejecutar la transferencia
		//	  Description: Funcion que actualiza los campos del personal de una nómina en otra
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codper, codtab, codpas, codgra, codcar, codasicar, sueper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, ".
				"		prouniadm, codban, codcueban, tipcuebanper, cueaboper ".
				"  FROM sno_personalnomina ".
				" WHERE	codemp='".$this->ls_codemp."' ".
				"	AND	codnom='".$as_codnomdes."' ";
		$this->io_sql->begin_transaction();
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_procesar_transferenciadatos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
       	}
       	else
       	{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
         	{
				$ls_campo_update="";
				$ls_codper=$row["codper"];
				$ls_codtab=$row["codtab"];
				$ls_codpas=$row["codpas"];
				$ls_codgra=$row["codgra"];
				$ls_codcar=$row["codcar"];
				$ls_codasicar=$row["codasicar"];
				$li_sueper=$row["sueper"];
				$ls_minorguniadm=$row["minorguniadm"];
				$ls_ofiuniadm=$row["ofiuniadm"];
				$ls_uniuniadm=$row["uniuniadm"];
				$ls_depuniadm=$row["depuniadm"];
				$ls_prouniadm=$row["prouniadm"];
				$ls_codban=$row["codban"];
				$ls_codcueban=$row["codcueban"];
				$ls_tipcuebanper=$row["tipcuebanper"];
				$ls_cueaboper=$row["cueaboper"];
				if($ai_tabulador=="1")
				{
					$ls_campo_update=$ls_campo_update." codtab='".$ls_codtab."', codpas='".$ls_codpas."', codgra='".$ls_codgra."', ";
				}
				if($ai_cargos=="1")
				{
					$ls_campo_update=$ls_campo_update." codcar='".$ls_codcar."', ";
				}
				if($ai_rac=="1")
				{
					$ls_campo_update=$ls_campo_update." codasicar='".$ls_codasicar."', ";
				}
				if($ai_sueldo=="1")
				{
					$ls_campo_update=$ls_campo_update." sueper=".$li_sueper.", ";
				}
				if($ai_unidadadmin=="1")
				{
					$ls_campo_update=$ls_campo_update." minorguniadm='".$ls_minorguniadm."', ofiuniadm='".$ls_ofiuniadm."', ".
									 " uniuniadm='".$ls_uniuniadm."', depuniadm='".$ls_depuniadm."', prouniadm='".$ls_prouniadm."', ";
				}
				if($ai_banco=="1")
				{
					$ls_campo_update=$ls_campo_update." codban='".$ls_codban."', ";
				}
				if($ai_cuentabancaria=="1")
				{
					$ls_campo_update=$ls_campo_update." codcueban='".$ls_codcueban."', ";
				}
				if($ai_tipocuenta=="1")
				{
					$ls_campo_update=$ls_campo_update." tipcuebanper='".$ls_tipcuebanper."', ";
				}
				if($ai_cuentacontable=="1")
				{
					$ls_campo_update=$ls_campo_update." cueaboper='".$ls_cueaboper."', ";
				}
				$ls_campo_update=substr($ls_campo_update,0,strlen($ls_campo_update)-2);				
				$ls_sql="UPDATE sno_personalnomina ".
						"   SET ".$ls_campo_update." ".
						" WHERE	codemp='".$this->ls_codemp."' ".
						"	AND	codnom='".$as_codnomhas."' ".
						"   AND codper='".$ls_codper."' ";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_procesar_transferenciadatos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Hizo una transferencia de datos de la nómina ".$as_codnomdes." a la nómina ".$as_codnomhas."".
								 	 "del personal nómina ".$ls_codper;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		if($lb_valido)
		{	
			$this->io_mensajes->message("La transferencia entre RAC fue realizada.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al hacer la transferencia."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_procesar_transferenciadatos	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personalnomina($as_codper,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personalnomina
		//		   Access: public (sigesp_snorh_p_buscarpersonal)
		//	    Arguments: as_codper  // código de personal
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todas las nóminas donde se encuentra el personal y el estatus
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_nomina.codnom, sno_nomina.desnom, sno_personalnomina.staper, sno_personalnomina.fecingper ".
				"  FROM sno_personalnomina, sno_nomina ".
				" WHERE sno_personalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_personalnomina.codper = '".$as_codper."' ".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_nomina.codnom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_load_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codnom=$row["codnom"];
				$ls_desnom=$row["desnom"];
				$ls_staper=$row["staper"];
				switch($ls_staper)
				{
					case "0":
						$ls_staper="N/A";
						break;

					case "1":
						$ls_staper="ACTIVO";
						break;

					case "2":
						$ls_staper="VACACIONES";
						break;

					case "3":
						$ls_staper="EGRESADO";
						break;

					case "4":
						$ls_staper="SUSPENDIDO";
						break;
				}
				$ld_fecingper=$this->io_funciones->uf_convertirfecmostrar($row["fecingper"]);
				$ao_object[$ai_totrows][1]="<div align='center'>".$ls_codnom."</div>";
				$ao_object[$ai_totrows][2]=" ".$ls_desnom." ";
				$ao_object[$ai_totrows][3]="<div align='center'>".$ls_staper."</div>";
				$ao_object[$ai_totrows][4]="<div align='center'>".$ld_fecingper."</div>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnominahistorico($as_codper,$ai_pagefeper,$ai_pagbanper,$as_codban,$as_codcueban,$as_tipcuebanper,
											   $as_cueaboper,$as_codage,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnominahistorico
		//		   Access: private
		//	    Arguments: as_codper  // código de personal  
		//				   ai_pagefeper  // pago en efectivo 
		//				   ai_pagbanper  // pago en banco
		//                 as_codban  // código de banco
		//				   as_codcueban  // código de cuenta bancaria       
		//				   as_tipcuebanper  // tipo de cuenta bancaria 
		//				   as_cueaboper  // cuenta de abono                 
		//				   as_codage  // código de agencia 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el personalnomina en los históricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/11/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_hpersonalnomina ".
				"   SET pagefeper=".$ai_pagefeper.",".
				"		pagbanper=".$ai_pagbanper.",".
				"		codban='".$as_codban."',".
				"		codcueban='".$as_codcueban."',".
				"		tipcuebanper='".$as_tipcuebanper."',".
				"		cueaboper='".$as_cueaboper."',".
				"		codage='".$as_codage."'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND anocur='".$this->li_anocurnom."'".
				"   AND codperi='".$this->ls_peractnom."'".
				"   AND codper='".$as_codper."'";
		$this->io_sql->begin_transaction();		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_personalnominahistorico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		if ($lb_valido)
		{
			$ls_sql="UPDATE sno_thpersonalnomina ".
					"   SET pagefeper=".$ai_pagefeper.",".
					"		pagbanper=".$ai_pagbanper.",".
					"		codban='".$as_codban."',".
					"		codcueban='".$as_codcueban."',".
					"		tipcuebanper='".$as_tipcuebanper."',".
					"		cueaboper='".$as_cueaboper."',".
					"		codage='".$as_codage."'".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND anocur='".$this->li_anocurnom."'".
					"   AND codperi='".$this->ls_peractnom."'".
					"   AND codper='".$as_codper."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_personalnominahistorico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		if ($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el personal nómina ".$as_codper." asociado a la nómina ".$this->ls_codnom." Año ".$this->li_anocurnom." Periodo ".$this->ls_peractnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_mensajes->message("El Personal fue modificado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_personalnominahistorico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
	}// end function uf_update_personalnominahistorico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_beneficiario($as_codper,$as_codban,$as_cueban,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // Código del personal
		//			  	   as_codban  // Código del Banco
		//			  	   as_cueban  // Cuenta del Banco
		//			  	   aa_seguridad  // Arreglo de las Variables de Seguridad
		//	      Returns: lb_valido True si el select no tuvo errores ó False si hubo error
		//	  Description: Funcion que actualiza el código de banco y la cuenta de banco el personal en la definición de beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE rpc_beneficiario ".
				"	SET codban = '".$as_codban."', ".
				"		ctaban = '".$as_cueban."' ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND ced_bene IN (SELECT cedper FROM sno_personal WHERE codemp ='".$this->ls_codemp."' AND codper = '".$as_codper."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_update_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Personal ".$as_codper." el código de banco y la cuenta de banco en la definición de beneficiario";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		 }	  	
		return $lb_valido;	
	}// end function uf_update_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_verificar_codigo_rac($as_codunirac,$as_codper)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_codigo_rac
		//		   Access: private
		//	    Arguments: as_codunirac  // código único de rac
		//	      Returns: lb_valido False si existe ó True si no existe
		//	  Description: Funcion que valida que ningun personal tenga asociado el codigo único
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/08/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
       	$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codunirac='".$as_codunirac."' ".
				"   AND sno_personalnomina.codper <>'".$as_codper."' ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper  ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_verificar_codigo_rac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_valido=false;
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
	        	$this->io_mensajes->message("ERROR-> Este Código de RAC esta asociado a ".$ls_codper." ".$ls_apeper.", ".$ls_nomper); 
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_valido;    
	}// end function uf_verificar_codigo_rac	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_pension($as_codper,$ai_suebasper,$ai_priespper,$ai_pritraper,$ai_priproper,$ai_prianoserper,
							   $ai_pridesper,$ai_porpenper,$ai_prinoascper,$ai_monpenper,$ai_subtotper,
							   $ad_fecvid, $ai_primrem, $ai_segrem, $as_tippen, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_pension
		//		   Access: private
		//	    Arguments: as_codper  // Código del personal
		//				   ai_suebasper  // Sueldo Basico del personal para pensiones
		//				   ai_priespper  // Prima especial del personal para pensiones
		//				   ai_pritraper  // prima transporte del personal para pensiones
		//				   ai_priproper  // Prima profesionalizacion del personal para pensiones
		//				   ai_prianoserper  // Prima Años Servicios del personal para pensiones
		//				   ai_pridesper  // Prima Descendencia del personal para pensiones
		//				   ai_porpenper  // Procentaje  del personal para pensiones
		//				   ai_prinoascper  // Prima No ascenso del personal para pensiones
		//				   ai_monpenper  // Monto pension del personal para pensiones
		//				   ai_subtotper  // Sub total  del personal	para pensiones
		//			  	   aa_seguridad  // Arreglo de las Variables de Seguridad
		//	      Returns: lb_valido True si el select no tuvo errores ó False si hubo error
		//	  Description: Funcion que elimina la información de las pensiones y las vuelve a crear
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/05/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_personalpension ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_pension ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$ls_sql="INSERT INTO sno_personalpension (codemp,codnom,codper,suebasper,priespper,pritraper,priproper,prianoserper,pridesper,porpenper,".
					"prinoascper,monpenper,subtotper, tipjub, fecvid, prirem, segrem) VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."',".$ai_suebasper.",".
					"".$ai_priespper.",".$ai_pritraper.",".$ai_priproper.",".$ai_prianoserper.",".$ai_pridesper.",".$ai_porpenper.",".$ai_prinoascper.",".
					"".$ai_monpenper.",".$ai_subtotper.",'".$as_tippen."','".$ad_fecvid."',".$ai_primrem.",".$ai_segrem.")";										
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Personal MÉTODO->uf_insert_pension ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó los valores de la pension del Personal ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		 }	  	
		return $lb_valido;	
	}// end function uf_insert_pension
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estcodunirac($as_codasicar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estcodunirac
		//		   Access: private
		//	    Arguments: as_codasicar  // código de asignación de cargo
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Función que actualiza el estatus del codigo unico de RAC
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 03/11/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_codigounicorac ".
					"   SET estcodunirac='0' ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codasicar='".$as_codasicar."' ";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_estcodunirac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ls_sql2="UPDATE sno_codigounicorac ".
						"   SET estcodunirac='1' ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND codnom='".$this->ls_codnom."' ".
						"   AND codasicar='".$as_codasicar."' ".
						"   AND codunirac IN (SELECT codunirac ".
					    "					   FROM sno_personalnomina ".
					    " 						WHERE codemp='".$this->ls_codemp."' ".
					    "                         AND codnom='".$this->ls_codnom."' ".
					    "                         AND codasicar='".$as_codasicar."')";
			
			$li_row2=$this->io_sql->execute($ls_sql2);
			if($li_row2===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Personal Nómina MÉTODO->uf_update_estcodunirac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
			
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Actualizó el estado de los códigos único de RAC asociados a la asignación de cargo ".$as_codasicar." asociado a la nómina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		return $lb_valido;
	}// end function uf_update_ocupados
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>

