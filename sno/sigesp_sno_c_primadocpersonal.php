<?php
class sigesp_sno_c_primadocpersonal
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_primadocpersonal()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_primadocpersonal
		//		   Access: public (sigesp_snorh_d_proyecto)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
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
		$this->io_seguridad=new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_codnom="0000";
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
	}// end function sigesp_sno_c_primadocpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_proyecto)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_proyecto($as_codproy)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_proyecto
		//		   Access: private
 		//	    Arguments: as_codproy  // código del proyecto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el proyecto existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql= "SELECT codemp ".
				 "  FROM sno_proyecto ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codproy='".$as_codproy."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_select_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proyecto($as_codproy,$as_nomproy,$as_codpro,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proyecto
		//		   Access: private
		//	    Arguments: as_codproy  // código del Proyecto
		//				   as_nomproy  // descripción del Proyecto
		//				   as_codpro  // Estructura programática 5 niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO sno_proyecto (codemp,codproy,nomproy,estproproy,estcla)VALUES('".$this->ls_codemp."','".$as_codproy."','".$as_nomproy."','".$as_codpro."','".$as_estcla."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_insert_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Proyecto ".$as_codproy;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Proyecto fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_insert_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_proyecto($as_codproy,$as_nomproy,$as_codpro,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_proyecto
		//		   Access: private
		//	    Arguments: as_codproy  // código del Proyecto
		//				   as_nomproy  // descripción del Proyecto
		//				   as_codpro  // Estructura programática  5 niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "UPDATE sno_proyecto ".
				 "   SET nomproy='".$as_nomproy."', ".
				 "       estproproy='".$as_codpro."', ".
				 "        estcla= '".$as_estcla."'".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codproy='".$as_codproy."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_update_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Proyecto ".$as_codproy;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Proyecto fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_update_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codproy,$as_nomproy,$as_codpro,$as_estcla,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_proyecto)
		//	    Arguments: as_codproy  // código del Proyecto
		//				   as_nomproy  // descripción del Proyecto
		//				   as_codpro  // Estructura programática 5 Niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_proyecto($as_codproy)===false)
				{
					$lb_valido=$this->uf_insert_proyecto($as_codproy,$as_nomproy,$as_codpro,$as_estcla,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Proyecto ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_proyecto($as_codproy)))
				{
					$lb_valido=$this->uf_update_proyecto($as_codproy,$as_nomproy,$as_codpro,$as_estcla,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Proyecto no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_proyectopersonal($as_codproy)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_proyectopersonal
		//		   Access: private
		//	    Arguments: as_codproy  // código del Proyecto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal tiene asociado este Proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_proyectopersonal ".
				" WHERE codemp='".$this->ls_codemp."'".
				 "  AND codproy='".$as_codproy."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_select_proyectopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_proyectopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_proyecto($as_codproy,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_proyecto
		//		   Access: public (sigesp_snorh_d_proyecto)
		//	    Arguments: as_codproy  // código del Proyecto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_proyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if($this->uf_select_proyectopersonal($as_codproy)===false)
		{
			$ls_sql="DELETE ".
			        "  FROM sno_proyecto ".
				    " WHERE codemp='".$this->ls_codemp."' ".
				    "   AND codproy='".$as_codproy."' ";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_delete_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Proyecto ".$as_codproy;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Proyecto fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_delete_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el Proyecto, hay personal relacionado a este.");
		}       
		return $lb_valido;
    }// end function uf_delete_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personalprima($as_codper,&$ai_totrows,&$aa_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personalprima
		//		   Access: public (sigesp_sno_d_personaproyecto.php)
		//	    Arguments: as_codper  // Código de personal
		//				   ai_totdiaper  // Total de Días del periodo
		//				   ai_porcentaje  // Total de Porcentaje 
		//				   ai_totrows  // Total de Filas
		//				   aa_object  //  Arreglo de objectos que se van a imprimir
		//	      Returns: $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene los proyectos asociados a la persona
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_totdiaper=0;
		$ai_porcentaje=0;
		$ls_sql="SELECT sno_primadocentepersonal.codper, sno_primadocentepersonal.codnom, sno_primadocentepersonal.codpridoc, sno_primasdocentes.tippridoc, sno_primasdocentes.despridoc".
				"  FROM sno_primadocentepersonal, sno_primasdocentes".
				" WHERE sno_primadocentepersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_primadocentepersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_primadocentepersonal.codper='".$as_codper."' ".
				"   AND sno_primadocentepersonal.codemp = sno_primasdocentes.codemp ".
				"   AND sno_primadocentepersonal.codpridoc = sno_primasdocentes.codpridoc ".
				" ORDER BY codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_load_personalprima ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codpridoc=$row["codpridoc"];  
				$li_tippridoc=$row["tippridoc"];
				$ls_despridoc=$row["despridoc"];				
				switch ($li_tippridoc)
				{
					case "0":
						$ls_tippridoc='Jerarquia';
					break;
					case "1":
						$ls_tippridoc='Antiguedad';
					break;
					case "2":
						$ls_tippridoc='Hogar e Hijos';
					break;
				}
				$aa_object[$ai_totrows][1]="<div align='center'><a href=javascript:ue_eliminarproyecto('$ls_codpridoc');><img src='../shared/imagebank/tools20/eliminar.gif' alt='Eliminar' width='15' height='15' border='0'></a></div>";
				$aa_object[$ai_totrows][2]="<input type=text   name=txtcodpridoc".$ai_totrows."   id=txtcodpridoc".$ai_totrows."   value='".$ls_codpridoc."'   size=12 class=sin-borde readonly><input type=hidden   name=hidtippridoc".$ai_totrows."   id=hidtippridoc".$ai_totrows."   value='".$li_tippridoc."'>";
				$aa_object[$ai_totrows][3]="<input type=text   name=txtdespridoc".$ai_totrows."   id=txtdespridoc".$ai_totrows."   value='".$ls_despridoc."'   size=50 class=sin-borde readonly >";
				$aa_object[$ai_totrows][4]="<input type=text   name=txttippridoc".$ai_totrows."   id=txttippridoc".$ai_totrows."   value='".$ls_tippridoc."'   size=50 class=sin-borde readonly >";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_personalprima	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porcentajeproyecto($as_codper,$as_codproy,&$ai_porcentaje)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porcentajeproyecto
		//		   Access: private
 		//	    Arguments: as_codper  // código del personal
		//				   as_codproy  // código del proyecto
		//				   ai_porcentaje  // Porcentaje
		//	      Returns: lb_valido True si la función es válida ó false si ocurre algún error
		//	  Description: Funcion que obtiene la suma de los porcentajes
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sum(pordiames) as porcentaje ".
				 "  FROM sno_proyectopersonal ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codper<>'".$as_codper."' ".
				 "   AND codproy='".$as_codproy."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_select_personalproyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_porcentaje=$row["porcentaje"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_porcentajeproyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_personalprima($as_codper,$as_codpridoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_personalproyecto
		//		   Access: private
 		//	    Arguments: as_codproy  // código del proyecto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la persona tiene el proyecto asociado existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql= "SELECT codemp ".
				 "  FROM sno_primadocentepersonal ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codnom='".$this->ls_codnom."' ".
				 "   AND codper='".$as_codper."' ".
				 "   AND codpridoc='".$as_codpridoc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_select_personalprima ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_personalproyecto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_personalprima($as_codper,$as_codpridoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_personalprima
		//		   Access: private
		//	    Arguments: as_codper  // código del Personal
		//				   as_codproy  // Código del proyecto
		//				   ai_totdiaper  // total de días Habiles del periodo
		//				   ai_totdiames  // total de días hábiles de las personas en el proyecto
		//				   ai_pordiames  // procentaje del total de días hábiles del proyecto con respecto a los días habiles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_proyectopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_primadocentepersonal (codemp,codper,codnom,codpridoc) VALUES ".
				"('".$this->ls_codemp."','".$as_codper."','".$this->ls_codnom."','".$as_codpridoc."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_insert_personalprima ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		    print ($this->io_sql->message);
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Prima Personal ".$as_codpridoc." - ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_personalprima
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalprima($as_codper,$as_codpridoc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_personalprima
		//		   Access: private
		//	    Arguments: as_codper  // código del Personal
		//				   as_codproy  // Código del proyecto
		//				   ai_totdiaper  // total de días Habiles del periodo
		//				   ai_totdiames  // total de días hábiles de las personas en el proyecto
		//				   ai_pordiames  // procentaje del total de días hábiles del proyecto con respecto a los días habiles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_proyectopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "UPDATE sno_primadocentepersonal ".
				 "   SET codpridoc=".$as_codpridoc.", ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codnom='".$this->ls_codnom."' ".
				 "   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_update_personalprima ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Prima Personal ".$as_codpridoc." - ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_personalprima
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_personalprima($as_codper,$as_codpridoc,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_personalprima
		//		   Access: public (sigesp_sno_d_personaproyecto)
		//	    Arguments: as_codper  // código del Personal
		//				   as_codproy  // Código del proyecto
		//				   ai_totdiaper  // total de días Habiles del periodo
		//				   ai_totdiames  // total de días hábiles de las personas en el proyecto
		//				   ai_pordiames  // procentaje del total de días hábiles del proyecto con respecto a los días habiles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_proyectopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$lb_valido=$this->uf_insert_personalprima($as_codper,$as_codpridoc,$aa_seguridad);
		return $lb_valido;
	}// end function uf_guardar_personalprima
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_personalprima($as_codper,$as_codpridoc,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_personalprima
		//		   Access: public (sigesp_sno_d_personaproyecto)
		//	    Arguments: as_codper  // código del Personal
		//				   as_codproy  // Código del proyecto
		//				   ai_totdiaper  // total de días Habiles del periodo
		//				   ai_totdiames  // total de días hábiles de las personas en el proyecto
		//				   ai_pordiames  // procentaje del total de días hábiles del proyecto con respecto a los días habiles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_proyectopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(!$this->uf_select_personalprima($as_codper,$as_codpridoc))
		{
		$ls_sql= "DELETE FROM sno_primadocentepersonal ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codnom='".$this->ls_codnom."' ".
				 "   AND codper='".$as_codper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_delete_personalprima ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
            print($this->io_sql->message);  		
		}
		else
		 { 
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino la prima  ".$as_codpridoc." - ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		 }
		}
		return $lb_valido;
	}// end function uf_guardar_personalprima
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_personalproyecto($as_codper,$as_codproy,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_personalproyecto
		//		   Access: public (sigesp_sno_d_personaproyecto)
		//	    Arguments: as_codper  // código del Personal
		//				   as_codproy  // Código del proyecto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_proyectopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->uf_select_personalproyecto($as_codper,$as_codproy))
		{
			$ls_sql="DELETE ".
					"  FROM sno_proyectopersonal ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codproy='".$as_codproy."' ".
					"   AND codper='".$as_codper."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_delete_personalproyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Proyecto Personal ".$as_codproy." - ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
    }// end function uf_delete_personalproyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_proyecto_historico($as_codproy,$as_nomproy,$as_codpro,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_proyecto_historico
		//		   Access: private
		//	    Arguments: as_codproy  // código del proyecto
		//				   as_nomproy  // nombre del proyecto
		//				   as_codpro  // Estructura programática  5 niveles
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_hproyecto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "UPDATE sno_hproyecto ".
				 "   SET nomproy='".$as_nomproy."', ".
				 "       estproproy='".$as_codpro."', ".
				 "       estcla='".$as_estcla."' ".
				 " WHERE codemp='".$this->ls_codemp."' ".
				 "   AND codproy='".$as_codproy."' ".
				 "   AND codnom='".$_SESSION["la_nomina"]["codnom"]."'".
				 "	 AND anocur='".$_SESSION["la_nomina"]["anocurnom"]."'".
				 "	 AND codperi='".$_SESSION["la_nomina"]["peractnom"]."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_update_proyecto_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			$ls_sql= "UPDATE sno_thproyecto ".
					 "   SET nomproy='".$as_nomproy."', ".
					 "       estproproy='".$as_codpro."', ".
					 "       estcla='".$as_estcla."' ".
					 " WHERE codemp='".$this->ls_codemp."' ".
					 "   AND codproy='".$as_codproy."' ".
					 "   AND codnom='".$_SESSION["la_nomina"]["codnom"]."'".
					 "	 AND anocur='".$_SESSION["la_nomina"]["anocurnom"]."'".
					 "	 AND codperi='".$_SESSION["la_nomina"]["peractnom"]."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_update_proyecto_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				////////////////////////////////         SEGURIDAD               //////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el proyecto histórico ".$as_codproy;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Proyecto fue Actualizado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Proyecto MÉTODO->uf_update_proyecto_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_update_proyecto_historico
	//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_validarcierre_gastos_ingreso(&$as_statusg,&$as_statusi)
	{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validarcierre_gastos_ingreso
		//		   Access: private
		//     Argumentos: 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que buscas los estatus de cierre del presuepuesto de gastos i de ingreso
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 28/08/2008 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT estciespg, estciespi FROM sigesp_empresa where codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->SELECT->uf_validarcierre_gastos_ingreso ERROR->"
			                            .$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$as_statusg= $row["estciespg"];
				$as_statusi= $row["estciespi"];				
			}
		}
		return 	$lb_valido;
	}//fin de uf_validarcierre_gastos_ingreso
//--------------------------------------------------------------------------------------------------------------------------------------
}
?>