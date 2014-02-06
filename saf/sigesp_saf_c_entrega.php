<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_entrega
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_entrega()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");		
		require_once("sigesp_saf_c_activo.php");
		$this->io_msg = new class_mensajes();
		$in = new sigesp_include();
		$this->con = $in->uf_conectar();
		$this->io_sql = new class_sql($this->con);
		$this->seguridad = new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->io_activo = new sigesp_saf_c_activo();
		
	}
	
	function uf_saf_select_entrega($as_codemp,$as_cmpent,$as_coduniadm,$ad_feccmp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_entrega
		//         Access: public 
		//      Argumento: $as_codemp       // codigo de empresa
		//  			   $as_cmpent       // No de comprobante de entrega
		//  			   $as_coduniadm    // codigo de la unidad Administrativa
		//  			   $ad_feccmp       // fecha del Comprobante
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una entrega en la tabla saf_entrega
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_entrega".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpent='". $as_cmpent ."'".
				" AND coduniadm='". $as_coduniadm ."'".
				" AND feccmp='". $ad_feccmp ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_select_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	}  // end function uf_saf_select_entrega


	function  uf_saf_insert_entrega($as_codemp,$as_cmpent,$ad_feccmp,$ad_fecent,$as_coduniadm,$as_codunisol,$as_codres,$as_codrec,
	                                $as_coddes,$as_tipres,$as_tiprec,$as_tipdes,$as_descmp,$as_estproent,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_entrega
		//         Access: public 
		//      Argumento: $as_codemp    // Codigo de Empresa 
		//                 $as_cmpent    // Nº del Comprobante de Entrega
		//                 $ad_feccmp    // Fecha en que se genero el comprobante
		//                 $ad_fecent    // Fecha en que se entregan los Activos
		//                 as_coduniadm  // Codigo de la Unidad Administrativa donde se va entregar el Activo
		//                 $as_codres    // Codigo del Responsable
		//                 $as_codrec    // Codigo del Receptor
		//                 $as_coddes    // Codigo del Despachador
		//                 as_tipres     // Tipo de Responsable (personal o beneficiario) 
		//                 as_tiprec     // Tipo de Receptor    (personal o beneficiario)
		//                 as_tipdes     // Tipo de Despachador (personal o beneficiario)
		//                 $as_descmp    // Observaciones del comprobante
		//                 $as_estproent // Estatus de procesamiento de la Entrega
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un registro de entrega de activos en la tabla saf_entrega
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(empty($ad_fecent))
		{
		 $ad_fecent = $ad_feccmp;
		}
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$ls_sql = " INSERT INTO saf_entrega(codemp, cmpent, feccmp, fecent, coduniadm, codunisol, estproent, codres,  ".
                  "                         coddes, codrec, tipres, tipdes, tiprec, obsent) ".
                  " VALUES ('".$as_codemp."','".$as_cmpent."','".$ad_feccmp."','".$ad_fecent."','".$as_coduniadm."','".$as_codunisol."',".$as_estproent.",'".$as_codres."','".$as_coddes."',".
	              "         '".$as_codrec."','".$as_tipres."','".$as_tipdes."','".$as_tiprec."','".$as_descmp."')";		  	  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_insert_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			print $this->io_sql->message;
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Comprobante de Entrega ".$as_cmpent." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_entrega

	function  uf_saf_insert_dt_entrega($as_codemp,$as_cmpent,$as_coduniadm,$ad_feccmp,$as_codact,$as_ideact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_entrega
		//         Access: public  
		//      Argumento: $as_codemp     //Codigo de Empresa 
		//                 $as_cmpent     //Nº del Comprobante de Entrega
		//                 $as_coduniadm  //Codigo de la Unidad Administrativa
		//                 $ad_feccmp     //fecha en que se genero el comprobante
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de la Entrega de Activos Fijos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$ls_sql = " INSERT INTO saf_dt_entrega(codemp, cmpent, feccmp, coduniadm, codact, ideact) ".
                  "       VALUES ('".$as_codemp."','".$as_cmpent."','".$ad_feccmp."','".$as_coduniadm."','".$as_codact."','".$as_ideact."')";	  	  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_insert_dt_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Activo ". $as_codact ." al Comprobante de Entrega ".$as_cmpent." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_dt_entrega

	
	function  uf_saf_delete_dt_entrega($as_codemp,$as_cmpent,$as_coduniadm,$ad_feccmp,$as_codact,$as_ideact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_dt_entrega
		//         Access: public  
		//      Argumento: $as_codemp     //Codigo de Empresa 
		//                 $as_cmpent     //Nº del Comprobante de Entrega
		//                 $as_coduniadm  //Codigo de la Unidad Administrativa
		//                 $ad_feccmp     //fecha en que se genero el comprobante
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle de la Entrega de Activos Fijos
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$ls_sql = " DELETE FROM saf_dt_entrega ".
		          "        WHERE codemp    = '".$as_codemp."' ".
				  "        AND   cmpent    = '".$as_cmpent."' ".
				  "        AND   feccmp    = '".$ad_feccmp."' ".
				  "        AND   coduniadm = '".$as_coduniadm."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_delete_dt_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino el Activo ". $as_codact ." del Comprobante de Entrega ".$as_cmpent." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_delete_dt_entrega
	
	
	function uf_saf_update_procesarentrega($as_codemp,$as_cmpent,$ad_feccmp,$as_coduniadm,$as_estproent,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_procesarentrega
		//         Access: public  
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_cmpent       //numero de comprobante
		//                 $as_fecent       //Fecha de comprobante
		//                 $as_coduniadm    //codigo de la Unidad Administrativa
		//                 $as_estproent    //Estatus de procesamiento de Entrega
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus del proceso de entrega en la tabla saf_entrega
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 10/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_entrega SET   estproent='". $as_estproent ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND cmpent='" . $as_cmpent ."'".
					" AND feccmp='" . $ad_feccmp ."'".
					" AND coduniadm='" . $as_coduniadm ."'";			
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_update_procesarentrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Procesó la Entrega  ".$as_cmpent." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_procesarentrega

	function uf_saf_load_dt_entrega($as_codemp,$as_cmpent,$ad_feccmp,$as_coduniadm,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_dt_entrega
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpent   // No de comprobante de Entrega
		//  			   $ad_feccmp    // fecha de emision del comprobante
		//  			   $ad_coduniadm // Codigo de la Unidad Administrativa
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a una entrega de activos de la tabla saf_dt_entrega
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT dt_ent.codact, dt_ent.ideact, activo.denact, activo.costo ".
                "     FROM saf_dt_entrega dt_ent ".
                " JOIN saf_dta dta ON dta.codemp = dt_ent.codemp ".
                "                 AND dta.codact = dt_ent.codact ".
                "                 AND dta.ideact = dt_ent.ideact ".
                " JOIN saf_activo activo ON activo.codemp = dta.codemp ".
                "                       AND activo.codact = dta.codact".
                " WHERE dt_ent.codemp   ='". $as_codemp ."' ".
				"   AND dt_ent.cmpent   ='". $as_cmpent ."' ".
				"   AND dt_ent.coduniadm='". $as_coduniadm ."' ".
				"   AND dt_ent.feccmp   ='". $ad_feccmp ."' ".
				" ORDER BY dt_ent.codact,dt_ent.ideact";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_load_dt_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codact= $row["codact"];
				$ls_denact= $row["denact"];
				$ls_idact=  $row["ideact"];
				$li_monact= $row["costo"];
				$li_monact=number_format($li_monact,2,",",".");
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodact".$ai_totrows." type=text   id=txtcodact".$ai_totrows." style=text-align:center class=sin-borde size=15 maxlength=15 value='". $ls_codact ."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  style=text-align:center class=sin-borde size=15 maxlength=15 value='". $ls_idact ."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." style=text-align:left class=sin-borde size=25 maxlength=150 value='". $ls_denact ."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." style=text-align:right class=sin-borde size=15 value='". $li_monact ."' readonly>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_saf_load_dt_entrega

    function uf_saf_update_entrega($as_codemp,$as_cmpent,$ad_feccmp,$ad_fecent,$as_coduniadm,$as_codres,$as_codrec,
	                               $as_coddes,$as_tipres,$as_tiprec,$as_tipdes,$as_descmp,$as_estproent,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_entrega
		//         Access: public  
		//      Argumento: $as_codemp       //código de empresa 
		//                 $as_cmpent    // Nº del Comprobante de Entrega
		//                 $ad_feccmp    // Fecha en que se genero el comprobante
		//                 $ad_fecent    // Fecha en que se entregan los Activos
		//                 as_coduniadm  // Codigo de la Unidad Administrativa donde se va entregar el Activo
		//                 $as_codres    // Codigo del Responsable
		//                 $as_codrec    // Codigo del Receptor
		//                 $as_coddes    // Codigo del Despachador
		//                 as_tipres     // Tipo de Responsable (personal o beneficiario) 
		//                 as_tiprec     // Tipo de Receptor    (personal o beneficiario)
		//                 as_tipdes     // Tipo de Despachador (personal o beneficiario)
		//                 $as_descmp    // Observaciones del comprobante
		//                 $as_estproent // Estatus de procesamiento de la Entrega
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza la cabecera del Comprobante de Entrega
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " UPDATE saf_entrega ".
                  "       SET  fecent= '".$ad_fecent."', codres= '".$as_codres."', coddes= '".$as_coddes."',  ".
                  "       codrec= '".$as_codrec."', tipres= '".$as_tipres."', tipdes= '".$as_tipdes."', tiprec= '".$as_tiprec."', obsent= '".$as_descmp."' ".
                  "  WHERE codemp    = '".$as_codemp."' AND ".
                  "        cmpent    = '".$as_cmpent."' AND ".
                  "        feccmp    = '".$ad_feccmp."' AND ".
                  "        coduniadm = '".$as_coduniadm."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entrega MÉTODO->uf_saf_update_entrega ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Se Actualizó el Comprobante de Entrega ".$as_cmpent." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_entrega
//---------------------------------------------------------------------------------------------------------------------------------
}//fin de la clase 
?>
