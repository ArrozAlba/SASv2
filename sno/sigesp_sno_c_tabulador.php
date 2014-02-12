<?php
class sigesp_sno_c_tabulador
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_asignacioncargo;
	var $ls_codemp;
	var $ls_codnom;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_tabulador()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_tabulador
		//		   Access: public (sigesp_sno_d_tabla)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_sno_c_asignacioncargo.php");
		$this->io_asignacioncargo= new sigesp_sno_c_asignacioncargo();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		
	}// end function sigesp_sno_c_tabulador
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_tabla)
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
		unset($this->io_fun_nomina);
		unset($this->io_asignacioncargo);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tabulador($as_codtab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tabulador
		//		   Access: private
		//	    Arguments: as_codtab  // Código de Tabla
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la tabla está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codtab ".
				"  FROM sno_tabulador ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_select_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_tabulador
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tabulador($as_codtab,$as_destab,$ai_maxpasgra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tabulador
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_destab  // descripción
		//				   ai_maxpasgra // Maximo de Pasos por Grado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tabulador
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tabulador(codemp,codnom,codtab,destab,maxpasgra)VALUES".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codtab."','".$as_destab."',".$ai_maxpasgra.")";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_insert_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Tabla ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_tabulador
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tabulador($as_codtab,$as_destab,$ai_maxpasgra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tabulador
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_destab  // descripción
		//				   ai_maxpasgra // Maximo de Pasos por Grado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//     	  Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la tabla
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_tabulador ".
				"	SET destab='".$as_destab."', ".
				"		maxpasgra=".$ai_maxpasgra."".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_update_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Tabla ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_tabulador
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codtab,$as_destab,$ai_maxpasgra,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_tabla)
		//	    Arguments: as_codtab  // código de tabla
		//				   as_destab  // descripción
		//				   ai_maxpasgra // Maximo de Pasos por Grado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda la tabla
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_tabulador($as_codtab)))
				{
					$lb_valido=$this->uf_insert_tabulador($as_codtab,$as_destab,$ai_maxpasgra,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El tabulador ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_tabulador($as_codtab)))
				{
					$lb_valido=$this->uf_update_tabulador($as_codtab,$as_destab,$ai_maxpasgra,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El tabulador no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_tabulador($as_codtab,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tabulador
		//		   Access: public (sigesp_sno_d_tabla)
		//	    Arguments: as_codtab  // código de tabla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la tabla
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (!$this->io_asignacioncargo->uf_select_asignacioncargo("codtab",$as_codtab,"0"))
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_delete_primagrado_lote($as_codtab,"","",$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_grado_lote($as_codtab,$aa_seguridad);
			}
			if($lb_valido)
			{
				$ls_sql="DELETE ".
						"  FROM sno_tabulador ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codtab='".$as_codtab."'";
						
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			} 
			
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la tabla ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Tabulador fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_tabulador ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
			else
			{
				$this->io_sql->rollback();
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar el tabulador. Hay Asignación de Cargo asociado a este Tabulador.");
		}       
		return $lb_valido;
    }// end function uf_delete_tabulador
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_grado_lote($as_codtab,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_grado_lote
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina los grados en lote
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_grado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'";
		
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla MÉTODO->uf_delete_grado_lote ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los grados asociados al tabulador ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_grado_lote
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_grado($as_codtab,$as_comauto,$ai_maxpasgra,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_grado
		//		   Access: public (sigesp_sno_d_tabla)
		//	    Arguments: as_codtab  // código de tabla
		//				   ai_totrows  // total de fila
		//				   ao_object  // arreglo de objetos
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene todos los grados de la tabla
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_totrows=0;
		$ls_sql="SELECT codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra ".
				"  FROM sno_grado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				" ORDER BY codemp, codnom, codtab, codgra, moncomgra ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_load_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$ls_codgraant="";
			$li_contador=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codgra=trim($row["codgra"]);
				$ls_codpas=trim($row["codpas"]);
				$li_monsalgra=$row["monsalgra"];
				$li_moncomgra=$row["moncomgra"];
				$li_sueldo=$li_monsalgra+$li_moncomgra;
				$li_monsalgra=$this->io_fun_nomina->uf_formatonumerico($li_monsalgra);
				$li_moncomgra=$this->io_fun_nomina->uf_formatonumerico($li_moncomgra);
				$li_sueldo=$this->io_fun_nomina->uf_formatonumerico($li_sueldo);
				$ls_readonly="";
				if($as_comauto=="1")
				{
					$ls_readonly="readonly";
				}
				$ls_estilo = "sin-borde";
				if($ai_maxpasgra>0)
				{
					if($ls_codgra!=$ls_codgraant)
					{
						$ls_codgraant=$ls_codgra;
						$li_contador=1;
					}
					else
					{
						$li_contador++;
						if($li_contador>$ai_maxpasgra)
						{
							$ls_readonly="";
							$ls_estilo = "sin-borderesaltado";
						}
					}
				}
				$ao_object[$ai_totrows][1]="<input class=".$ls_estilo." name=txtcodgra".$ai_totrows." type=text id=txtcodgra".$ai_totrows." size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codgra."' readOnly>";
				$ao_object[$ai_totrows][2]="<input class=".$ls_estilo." name=txtcodpas".$ai_totrows." type=text id=txtcodpas".$ai_totrows." size=18 maxlength=15 onKeyUp='javascript: ue_validarcomillas(this);' value='".$ls_codpas."' readOnly><input name='existe".$ai_totrows."' type='hidden' id='existe".$ai_totrows."' value='1'>";
				$ao_object[$ai_totrows][3]="<input class=".$ls_estilo." name=txtmonsalgra".$ai_totrows." type=text id=txtmonsalgra".$ai_totrows." size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monsalgra."' onBlur='javascript: ue_sumarcompensacion(".$ai_totrows.");' style=text-align:right>";
				$ao_object[$ai_totrows][4]="<input class=".$ls_estilo." name=txtmoncomgra".$ai_totrows." type=text id=txtmoncomgra".$ai_totrows." size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_moncomgra."' style=text-align:right onBlur='javascript: uf_sumarsueldo(".$ai_totrows.");'  ".$ls_readonly.">";
				$ao_object[$ai_totrows][5]="<input class=".$ls_estilo." name=txtsueldo".$ai_totrows." type=text id=txtsueldo".$ai_totrows." class=sin-borde size=16 maxlength=13 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_sueldo."' style=text-align:right readonly>";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$ao_object[$ai_totrows][8]="<div align='center'><a href=javascript:uf_abrir_prima('".$ai_totrows."');><img src=../shared/imagebank/mas.gif alt=Definir primas border=0></a></div>";			
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_grado
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_grado($as_codtab,$as_codgra,$as_codpas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_grado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // código de grado
		//				   as_codpas  // código de paso
		//	      Returns: lb_existe True si se encontro ó False si no se encontró
		//	  Description: Funcion que verifica si el grado está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codgra ".
				"  FROM sno_grado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpas='".$as_codpas."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_select_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_grado
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_grado($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$ai_moncomgra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_grado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   ai_monsalgra  // Monto Salario
		//				   ai_moncomgra  // Monto compensación
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que inserta en la tabla de grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_grado".
				"(codemp,codnom,codtab,codgra,codpas,monsalgra,moncomgra)VALUES".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codtab."','".$as_codgra."',".
				"'".$as_codpas."',".$ai_monsalgra.",".$ai_moncomgra.")";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_insert_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el paso ".$as_codpas." grado ".$as_codgra." asociados al tabulador ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_grado($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$ai_moncomgra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_grado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   ai_monsalgra  // Monto Salario
		//				   ai_moncomgra  // Monto compensación
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la tabla de grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_grado ".
				"	SET monsalgra = ".$ai_monsalgra.", ".
				"		moncomgra = ".$ai_moncomgra." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpas='".$as_codpas."'";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_update_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el paso ".$as_codpas." grado ".$as_codgra." asociados al tabulador ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnomina($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnomina
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   ai_monsalgra  // Monto Salario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el sueldo en personal nómina a todo personal que tenga asociada esa tabla, paso y grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalnomina ".
				"	SET sueper = ".$ai_monsalgra." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpas='".$as_codpas."'";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el sueldo del personal nómina que esté asociado a el paso ".$as_codpas.",grado ".$as_codgra.", tabla ".$as_codtab.", nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_personalnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_grado($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$ai_moncomgra,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_grado
		//		   Access: public (sigesp_sno_d_tabla)
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   ai_monsalgra  // Monto Salario
		//				   ai_moncomgra  // Monto compensación
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena el grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monsalgra=str_replace(".","",$ai_monsalgra);
		$ai_monsalgra=str_replace(",",".",$ai_monsalgra);
		$ai_moncomgra=str_replace(".","",$ai_moncomgra);
		$ai_moncomgra=str_replace(",",".",$ai_moncomgra);
		if(!($this->uf_select_grado($as_codtab,$as_codgra,$as_codpas)))
		{
			$lb_valido=$this->uf_insert_grado($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$ai_moncomgra,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_update_grado($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$ai_moncomgra,$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_update_personalnomina($as_codtab,$as_codgra,$as_codpas,$ai_monsalgra,$aa_seguridad);
			}
		}
		return $lb_valido;
	}// end function uf_guardar_grado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_integridad_asignacioncargo_grado($as_codtab,$as_codgra,$as_codpas)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_integridad_asignacioncargo_grado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // código de grado
		//				   as_codpas  // código de paso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún asignación de cargo tenga asociada este paso y grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=true;
       	$ls_sql="SELECT codtab ".
				"  FROM sno_asignacioncargo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpas='".$as_codpas."'";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
			$this->msg->message("CLASE->Tabulador MÉTODO->uf_integridad_asignacioncargo_grado ERROR->".$this->fun->uf_convertirmsg($this->SQL->message)); 
       	}
       	else
       	{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_integridad_asignacioncargo_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_grado($as_codtab,$as_codgra,$as_codpas,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_grado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el grado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        if ($this->uf_integridad_asignacioncargo_grado($as_codtab,$as_codgra,$as_codpas)===false)
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_delete_primagrado_lote($as_codtab,$as_codgra,$as_codpas,$aa_seguridad);
			if($lb_valido)
			{
				$ls_sql="DELETE ".
						"  FROM sno_grado ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codtab='".$as_codtab."'".
						"   AND codgra='".$as_codgra."'".
						"   AND codpas='".$as_codpas."'";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el grado ".$as_codgra.", paso ".$as_codpas." asociado al tabulador ".$as_codtab." asociado a la nómina nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Grado fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_grado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}			
		}
		else
		{
			$this->io_mensajes->message("No se puede eliminar el grado. Hay Asignación de Cargo asociado a este grado.");
		}
		return $lb_valido;
	}// end function uf_delete_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_primagrado_lote($as_codtab,$as_codgra,$as_codpas,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_primagrado_lote
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las primas del grado, paso y tabla seleccionada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE ".
				"  FROM sno_primagrado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'";
		if(($as_codpas<>"")&&($as_codgra<>""))
		{
			$ls_sql=$ls_sql."   AND codpas='".$as_codpas."'".
							"   AND codgra='".$as_codgra."'";
		}
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_primagrado_lote ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las primas del grado ".$as_codgra.", paso ".$as_codpas." asociado al tabulador ".$as_codtab." asociado a la nómina nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_primagrado_lote
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_primagrado($as_codtab,$as_codpas,$as_codgra,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_primagrado
		//		   Access: public (sigesp_sno_pdt_primagrado)
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codpas  // Código de paso
		//				   as_codgra  // Código de grado
		//				   ai_totrows  // total de fila
		//				   ao_object  // arreglo de objetos
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene todas las primasgrados de la tabla, paso y grado seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtab, codpas, codgra, codpri, despri, monpri ".
				"  FROM sno_primagrado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codpas='".$as_codpas."'".
				"   AND codgra='".$as_codgra."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_load_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codpri=$row["codpri"];
				$ls_despri=$row["despri"];
				$li_monpri=$row["monpri"];
				$li_monpri=$this->io_fun_nomina->uf_formatonumerico($li_monpri);
				$ao_object[$ai_totrows][1]="<input name=txtcodpri".$ai_totrows." type=text id=txtcodpri".$ai_totrows." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codpri."' readOnly>";
				$ao_object[$ai_totrows][2]="<input name=txtdespri".$ai_totrows." type=text id=txtdespri".$ai_totrows." class=sin-borde size=50 maxlength=100 value='".$ls_despri."'>";
				$ao_object[$ai_totrows][3]="<input name=txtmonpri".$ai_totrows." type=text id=txtmonpri".$ai_totrows." class=sin-borde size=15 maxlength=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monpri."') style=text-align:right>";
				$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
			}
			$ai_totrows=$ai_totrows+1;
			$ao_object[$ai_totrows][1]="<input name=txtcodpri".$ai_totrows." type=text id=txtcodpri".$ai_totrows." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][2]="<input name=txtdespri".$ai_totrows." type=text id=txtdespri".$ai_totrows." class=sin-borde size=50 maxlength=100 >";
			$ao_object[$ai_totrows][3]="<input name=txtmonpri".$ai_totrows." type=text id=txtmonpri".$ai_totrows." class=sin-borde size=15 maxlength=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
			$ao_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
			$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_grado
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_primagrado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // código de grado
		//				   as_codpas  // código de paso
		//				   as_codpri  // código de prima
		//	      Returns: lb_existe True si se encontro ó False si no se encontró
		//	  Description: Funcion que verifica si la primagrado está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codpri ".
				"  FROM sno_primagrado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codpas='".$as_codpas."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpri='".$as_codpri."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_select_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_primagrado
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri,$as_despri,$ai_monpri,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_primagrado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   as_codpri  // código de prima
		//				   as_despri  // descripción de la prima
		//				   ai_monpri  // Monto de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de primagrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_primagrado".
				"(codemp,codnom,codtab,codpas,codgra,codpri,despri,monpri)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
				"'".$as_codtab."','".$as_codpas."','".$as_codgra."','".$as_codpri."','".$as_despri."',".$ai_monpri.")";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_insert_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la prima grado ".$as_codpri." del paso ".$as_codpas." grado ".$as_codgra." asociados al tabulador ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_primagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri,$as_despri,$ai_monpri,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_primagrado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   as_codpri  // código de prima
		//				   as_despri  // descripción de la prima
		//				   ai_monpri  // Monto de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza la tabla de primagrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_primagrado ".
				"	SET despri = '".$as_despri."', ".
				"		monpri = ".$ai_monpri." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codpas='".$as_codpas."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpri='".$as_codpri."'";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_update_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la prima grado ".$as_codpri." del paso ".$as_codpas." grado ".$as_codgra." asociados al tabulador ".$as_codtab." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_grado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri,$as_despri,$ai_monpri,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_grado
		//		   Access: public (sigesp_sno_d_tabla)
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   as_codpri  // código de prima
		//				   as_despri  // descripción de la prima
		//				   ai_monpri  // Monto de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena la primagrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_monpri=str_replace(".","",$ai_monpri);
		$ai_monpri=str_replace(",",".",$ai_monpri);
		if($this->uf_select_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri)===false)
		{
			$lb_valido=$this->uf_insert_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri,$as_despri,$ai_monpri,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_update_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri,$as_despri,$ai_monpri,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_guardar_grado
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_primagrado($as_codtab,$as_codgra,$as_codpas,$as_codpri,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_primagrado
		//		   Access: private
		//	    Arguments: as_codtab  // código de tabla
		//				   as_codgra  // Código de Grado
		//				   as_codpas  // Código de Paso
		//				   as_codpri  // Código de Prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las primas del grado, paso y tabla seleccionada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE ".
				"  FROM sno_primagrado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codtab='".$as_codtab."'".
				"   AND codpas='".$as_codpas."'".
				"   AND codgra='".$as_codgra."'".
				"   AND codpri='".$as_codpri."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la prima ".$as_codpri." del grado ".$as_codgra.", paso ".$as_codpas." asociado al tabulador ".$as_codtab." asociado a la nómina nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Prima fue Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tabulador MÉTODO->uf_delete_primagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete_primagrado
	//-----------------------------------------------------------------------------------------------------------------------------------

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>