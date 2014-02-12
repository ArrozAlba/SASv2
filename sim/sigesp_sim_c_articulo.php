<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_sim_c_articulo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_articulo()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}

	function uf_sim_select_catalogo(&$ai_estnum)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//      Argumento: $ai_estnum //estatus que indica si la codificion es numerica o alfanumerica
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la configuracion del inventario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 28/08/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sim_config  ".
				  " WHERE id=1 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo METODO->uf_sim_select_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_estcatsig= $row["estcatsig"];
				$ai_estnum= $row["estnum"];
				if($li_estcatsig==1)
				{$lb_valido=true;}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_sim_select_articulo

	function uf_sim_select_articulo($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa
		//				   $as_codart //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado articulo en la tabla sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codart, denart FROM sim_articulo  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo METODO->uf_sim_select_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_sim_select_articulo

	function uf_sim_select_tiendadispo_articulo($as_codemp,$as_codart,$filtrar,&$as_disponibles)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tiendadispo_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa
		//				   $as_codart //codigo de articulo
		//	      Returns: Retorna un array $as_dispo
		//    Description: Funcion que devuelve las tiendas disponibles para un articulo determinado
		//	   Creado Por: Ing. Luis A. Alvarez
		// Fecha Creacion: 20/01/2009 								Fecha ultima Modificacion :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;

		if($filtrar){
			$filtro=" AND codtiend NOT IN " .
				  	" (SELECT codtiend FROM sfc_producto WHERE codart like '%".$as_codart."%') ";
		}else{
			$filtro="";
		}

		$ls_sql = "SELECT codtiend,dentie FROM sfc_tienda  ".
				  " WHERE codemp='".$as_codemp."' ".$filtro. " ORDER BY codtiend ASC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo METODO->uf_sim_select_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{

			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_disponibles["codtiend"][$li_pos]=$row["codtiend"];
				$as_disponibles["dentie"][$li_pos]=$row["dentie"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);

		}

		return $lb_valido;
	}// end function uf_sim_select_tiendadispo_articulo

	function uf_sim_select_tiendaasing_articulo($as_codemp,$as_codart,&$as_asignados)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_tiendadispo_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa
		//				   $as_codart //codigo de articulo
		//	      Returns: Retorna un array $as_dispo
		//    Description: Funcion que devuelve las tiendas disponibles para un articulo determinado
		//	   Creado Por: Ing. Luis A. Alvarez
		// Fecha Creacion: 27/01/2009 								Fecha ultima Modificacion :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;

		$ls_sql = "SELECT codtiend,dentie FROM sfc_tienda  ".
				  " WHERE codemp='".$as_codemp."' AND codtiend in " .
				  " (SELECT DISTINCT(codtiend) FROM sfc_producto WHERE codart ='".$as_codart."') ORDER BY codtiend ASC ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo METODO->uf_sim_select_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{

			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_asignados["codtiend"][$li_pos]=$row["codtiend"];
				$as_asignados["dentie"][$li_pos]=$row["dentie"];
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);

		}
		return $lb_valido;
	}// end function uf_sim_select_tiendadispo_articulo

	function  uf_sim_insert_articulo($as_codemp, $as_codart, $as_denart, $as_codtipart, $as_codunimed, $ad_feccreart, $as_obsart,
									 /*$ai_exiart, $ai_exiiniart, $ai_minart, $ai_maxart,*/
									 $ai_tippro, $ai_codcla, $ai_codcla1, $ai_iduso, $ai_coduso, $ai_tipcos,
									 $ai_prearta, $ai_preartb,
									 $ai_preartc, $ai_preartd, $ad_fecvenart, $as_spg_cuenta, $ai_pesart, $ai_altart, $ai_ancart,
									 $ai_proart, $as_fotart, $as_codcatsig, $as_lote,$ai_util,/*$as_sccuenta,*/ $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//     Argumentos: $as_codemp     //codigo de empresa                 $as_codart    // codigo de articulo
		//				   $as_denart     // denominacion del articulo        $as_codtipart // codigo de tipo de articulo
		//			       $as_codunimed  // codigo de unidad de medida       $ad_feccreart // fecha de creacion del articulo
		//				   $as_obsart     // observacion del articulo		  $ai_exiart    // existencia del articulo
		//				   $ai_exiiniart  // existencia inicial del articulo  $ai_minart    // existencia minima del articulo
		//				   $ai_maxart     // existencia maxima del articulo   $ai_prearta   // precio A del articulo
		//				   $ai_preartb    // precio B del articulo		      $ai_preartc   // precio C del articulo
		//				   $ai_preartd    // precio D del articulo			  $ad_fecvenart // fecha de vencimiento del articulo
		//				   $as_spg_cuenta // numero de cuenta presupuestaria  $ai_pesart    // peso del articulo
		//				   $ai_altart     // altura del articulo			  $ai_ancart    // ancho del articulo
		//				   $ai_proart     // profundidad del articulo		  $as_codcatsig // codigo del catalogo sigecof
		//				   $as_sccuenta   // cuenta contable de gasto         $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un articulo en la tabla de  sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 30/08/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		/*if($ai_exiart=="")
		{$ai_exiart=0;}
		if($ai_prearta=="")
		{$ai_prearta=0;}
		if($ai_preartb=="")
		{$ai_preartb=0;}
		if($ai_preartc=="")
		{$ai_preartc=0;}
		if($ai_preartd=="")
		{$ai_preartd=0;}
		if($ai_pesart=="")
		{$ai_pesart=0;}
		if($ai_altart=="")
		{$ai_altart=0;}
		if($ai_ancart=="")
		{$ai_ancart=0;}
		if($ai_proart=="")
		{$ai_proart=0;}*/
		if($ad_fecvenart=="")
		{$ad_fecvenart="1900-01-01";}
		//$this->io_sql->begin_transaction();
		/*$ls_sql="INSERT INTO sim_articulo (codemp,codart,denart,codtipart,codunimed,feccreart,obsart,exiart,exiiniart, ".
				"                          minart,maxart,prearta,preartb,preartc,preartd,fecvenart,spg_cuenta,pesart,altart,".
				"                          ancart, proart,fotart,codcatsig,sc_cuenta)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_denart."','".$as_codtipart."','".$as_codunimed."',".
				"'".$ad_feccreart."','".$as_obsart."',".$ai_exiart.",".$ai_exiiniart.",".$ai_minart.",".$ai_maxart.",".
				" ".$ai_prearta.",".$ai_preartb.",".$ai_preartc.",".$ai_preartd.",'".$ad_fecvenart."','".$as_spg_cuenta."',".
				"".$ai_pesart.",".$ai_altart.",".$ai_ancart.",".$ai_proart.",'".$as_fotart."','".$as_codcatsig."',".
				"'".$as_sccuenta."');";*/

		
		if ($ai_codcla=='') {
		 $ai_codcla='000';				 	
		}
		
		if ($ai_codcla1=='') {
		 $ai_codcla1='000';	
		}
		
		if ($ai_iduso=='') {
	 	 $ai_iduso=0;
		}
		
		if ($ai_coduso=='') {
		 $ai_coduso='000';
		}
		
		if ($as_codtipart=='') {
	 	 $as_codtipart='0';
		}
		
		if ($as_codunimed=='') {
	 	 $as_codunimed='0';
		}
		
		
		
		$ls_sql="INSERT INTO sim_articulo (codemp,codart,denart,codtipart,codunimed,feccreart,obsart,tippro,codcla, ".
				"                          cod_sub,id_uso,codusomac,tipcos,prearta,preartb,preartc,preartd,fecvenart,spg_cuenta, ".
				"                          pesart,altart,ancart, proart,fotart,codcatsig,admlotfabart,vidutiart,estatus)".
				"  VALUES ('".$as_codemp."','".$as_codart."','".$as_denart."','".$as_codtipart."','".$as_codunimed."',".
				"'".$ad_feccreart."','".$as_obsart."','".$ai_tippro."','".$ai_codcla."','".$ai_codcla1."',".$ai_iduso.",'".$ai_coduso."','".$ai_tipcos."',".
				" ".$ai_prearta.",".$ai_preartb.",".$ai_preartc.",".$ai_preartd.",'".$ad_fecvenart."','".$as_spg_cuenta."',".
				"".$ai_pesart.",".$ai_altart.",".$ai_ancart.",".$ai_proart.",'".$as_fotart."','".$as_codcatsig."','".$as_lote."',".$ai_util.",'1')";
	 //print $ls_sql;
	//print (substr($as_codart,4,1));
		/*if (substr($as_codart,4,1)=='V')
		{
		/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/
		//$ls_archivo="C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/trans".$as_numtra."-".date('dmY').".txt";
		/*/$archivo = fopen($ls_archivo, "a+");																/**/
		/*fwrite($archivo,$ls_sql);																			/**/
		/*fclose($archivo);																					/**/
		//------------------------------------------------------------------------------------------------------/**/
		//}*/
		$li_row=$this->io_sql->execute($ls_sql);

		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articulo METODO->uf_sim_insert_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//$this->io_sql->rollback();
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Inserto el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				if($lb_variable)
				{
					$lb_valido=true;
					//$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					//$this->io_sql->rollback();
				}
		}
		return $lb_valido;
	} // end  function  uf_sim_insert_articulo

	function  uf_sim_update_articulo($as_codemp, $as_codart, $as_denart, $as_codtipart, $as_codunimed, $ad_feccreart, $as_obsart,
									 /*$ai_exiart, $ai_exiiniart, $ai_minart, $ai_maxart,*/
									 $ai_tippro, $ai_codcla, $ai_codcla1, $ai_iduso, $ai_coduso, $ai_tipcos, $ai_prearta, $ai_preartb,
									 $ai_preartc, $ai_preartd, $ad_fecvenart, $as_spg_cuenta, $ai_pesart, $ai_altart, $ai_ancart,
									 $ai_proart, $as_fotart, $as_codcatsig, /*$as_sccuenta,*/ $as_lote, $ai_util, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//     Argumentos: $as_codemp     //codigo de empresa                 $as_codart    // codigo de articulo
		//				   $as_denart     // denominacion del articulo        $as_codtipart // codigo de tipo de articulo
		//			       $as_codunimed // codigo de unidad de medida        $ad_feccreart // fecha de creacion del articulo
		//				   $as_obsart    // observacion del articulo		  $ai_exiart    // existencia del articulo
		//				   $ai_exiiniart // existencia inicial del articulo   $ai_minart    // existencia minima del articulo
		//				   $ai_maxart    // existencia maxima del articulo    $ai_prearta   // precio A del articulo
		//				   $ai_preartb   // precio B del articulo		      $ai_preartc   // precio C del articulo
		//				   $ai_preartd   // precio D del articulo			  $ad_fecvenart // fecha de vencimiento del articulo
		//				   $as_spg_cuenta// numero de cuenta presupuestaria   $ai_pesart    // peso del articulo
		//				   $ai_altart    // altura del articulo				  $ai_ancart    // ancho del articulo
		//				   $ai_proart    // profundidad del articulo		  $as_fotart     // foto del articulo
		//                 $as_codcatsig // codgido del catalogo SIGECOF      $aa_seguridad // arreglo de registro de seguridad
		//				   $as_sccuenta  // cuenta contable de gasto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un articulo en la tabla de  sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $li_exce=-1;
		 
		 if ($ai_codcla=='') {
		 $ai_codcla='000';				 	
		}
		
		if ($ai_codcla1=='') {
		 $ai_codcla1='000';	
		}
		
		if ($ai_iduso=='') {
	 	 $ai_iduso=0;
		}
		
		if ($ai_coduso=='') {
		 $ai_coduso='000';
		}
		
		if ($as_codtipart=='') {
	 	 $as_codtipart='0';
		}
		
		if ($as_codunimed=='') {
	 	 $as_codunimed='0';
		}
		
		 $ls_sql = "UPDATE sim_articulo SET   denart='". $as_denart ."',codtipart='". $as_codtipart ."',codunimed='". $as_codunimed ."',".
					" 						  feccreart='". $ad_feccreart ."',obsart='". $as_obsart ."'," .
					"						  tippro='".$ai_tippro."',codcla='".$ai_codcla."',cod_sub='".$ai_codcla1."',id_uso=".$ai_iduso.",codusomac='".$ai_coduso."',".
					" 						  tipcos='".$ai_tipcos."', prearta='". $ai_prearta ."',preartb='". $ai_preartb ."',preartc='". $ai_preartc ."',".
					" 						  preartd='". $ai_preartd ."',fecvenart='". $ad_fecvenart ."',spg_cuenta='". $as_spg_cuenta ."',".
					"						  pesart='". $ai_pesart ."',altart='". $ai_altart ."',ancart='". $ai_ancart ."',        ".
					"						  proart='". $ai_proart ."',fotart='". $as_fotart ."',codcatsig='". $as_codcatsig ."',  ".
					"						  admlotfabart='". $as_lote ."',vidutiart=". $ai_util ."                                ".
					" WHERE codart='" . $as_codart ."'".
					"   AND codemp='" . $as_codemp ."';";

		//$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print ($this->io_sql->message);
			$this->io_msg->message("CLASE->articulo METODO->uf_sim_update_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			if($lb_variable)
			{
				$lb_valido=true;
				//$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				//$this->io_sql->rollback();
			}
		}
	  return $lb_valido;
	} // end function  uf_sim_update_articulo

	function uf_sim_delete_articulo($as_codemp,$as_codart, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_articulo
		//         Access: public (sigesp_sim_d_articulo)
		//      Argumento: $as_codemp    //codigo de empresa
		//				   $as_codart    //codigo de articulo
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que llama a la verificacion de algun articulo en las tablas de sim_componetearticulo y
		//				   en la de sim_dt_recepcion y en caso de no encontrarse procede a su eliminacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_sim_select_componentearticulo($as_codemp,$as_codart);
		$lb_cargos= $this->uf_sim_select_dt_cargos($as_codemp,$as_codart);
		if(($lb_existe)||($lb_cargos))
		{
			$this->io_msg->message("El articulo tiene componentes y/o creditos asociados");
			$lb_valido=false;
		}
		else
		{
			$lb_existe=$this->uf_sim_select_dt_recepcion($as_codemp,$as_codart);
			if($lb_existe)
			{
				$this->io_msg->message("El articulo tiene entradas registradas en la empresa");
				$lb_valido=false;
			}
			else
			{
				$lb_existe=$this->uf_sim_select_dt_articulos_sep($as_codemp,$as_codart);
				if($lb_existe)
				{
					$this->io_msg->message("El articulo tiene por lo menos 1 SEP registrada en la empresa");
					$lb_valido=false;
				}
				else
				{
					$ls_sql = " UPDATE sim_articulo               ".
							  " SET    estatus='0'                ".
							  " WHERE  codemp= '".$as_codemp. "'  ".
							  " AND    codart= '".$as_codart. "'  ";
					//$this->io_sql->begin_transaction();
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->articulo M�TODO->uf_sim_delete_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
						$this->io_sql->rollback();
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						//////////////////////////////////         SEGURIDAD               /////////////////////////////
						if($lb_variable)
						{
							$lb_valido=true;
							//$this->io_sql->commit();
						}
						else
						{
							$lb_valido=false;
							//$this->io_sql->rollback();
						}
					}
				}
			}
		}
		return $lb_valido;
	} // end  function uf_sim_delete_articulo

	function uf_sim_select_componentearticulo($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_componentearticulo
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo tiene o no componentes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_componente  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo M�TODO->uf_sim_select_componentearticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_select_componentearticulo

	function uf_sim_select_dt_recepcion($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_recepcion
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo ha tenido alguna entrada en la empresa
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_dt_recepcion ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo M�TODO->uf_sim_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_select_dt_recepcion

	function uf_sim_select_dt_articulos_sep($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_articulos_sep
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo se le ha realizado una sep
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sep_dt_articulos ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo M�TODO->uf_sim_select_dt_articulos_sep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_select_dt_articulos_sep

	function uf_sim_select_dt_cargos($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_cargos
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo tiene algun cargo asociado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_cargosarticulo ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo M�TODO->uf_sim_select_dt_cargos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_select_dt_cargos

	function uf_sim_select_cuentaspg($as_codemp,&$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_cuentaspg
		//         Access: public (sigesp_sim_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa
		//				   $as_cuenta //numero de cuenta presupuestaria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe una determinada cuenta presupuestaria
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 28/03/2006 								Fecha �ltima Modificaci�n : 28/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT spg_cuenta FROM spg_cuentas  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND spg_cuenta LIKE '".$as_cuenta."%'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo M�TODO->uf_sim_select_cuentaspg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_cuenta=$row["spg_cuenta"];
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_sim_select_cuentaspg

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tama�o Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: Retorna un booleano
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_nomfot!="")
		{
			if (!((strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")) && ($as_tamfot < 100000)))
			{
				$lb_valido=false;
				$as_nomfot="";
				$this->io_msg->message("El archivo de la foto no es v�lido.");
			}
			else
			{
				if (!((move_uploaded_file($as_nomtemfot, "fotosarticulos/".$as_nomfot))))
				{
					$lb_valido=false;
					$as_nomfot="";
		        	$this->io_msg->message("CLASE->articulo M�TODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------



}
?>
