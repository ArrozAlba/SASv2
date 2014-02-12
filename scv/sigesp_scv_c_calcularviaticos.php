<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_scv_c_calcularviaticos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_scv_c_calcularviaticos()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();		
		$this->io_sql= new class_sql($this->con);
		$this->io_seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->is_codemp= $_SESSION["la_empresa"]["codemp"];
		
	} // end function sigesp_scv_c_calcularviaticos
		
	function uf_agregarlineablanca(&$aa_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtproasig".$ai_totrows."  type=text   id=txtproasig".$ai_totrows."  class=sin-borde size=16 style='text-align:center'>";
		$aa_object[$ai_totrows][2]="<input name=txtcodasig".$ai_totrows."  type=text   id=txtcodasig".$ai_totrows."  class=sin-borde size=11 >";
		$aa_object[$ai_totrows][3]="<input name=txtdenasig".$ai_totrows."  type=text   id=txtdenasig".$ai_totrows."  class=sin-borde size=55 >";
		$aa_object[$ai_totrows][4]="<input name=txtcantidad".$ai_totrows." type=text   id=txtcantidad".$ai_totrows." class=sin-borde size=12  style='text-align:right'>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt_asignaciones(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
	
	} // end function uf_agregarlineablanca

	function uf_agregarlineablancapersonal(&$aa_objectpersonal,$ai_totrowspersonal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_objectpersonal  // arreglo de titulos 
		//				   $ai_totrowspersonal // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_objectpersonal[$ai_totrowspersonal][1]="<input name=txtcodper".$ai_totrowspersonal."    type=text   id=txtcodper".$ai_totrowspersonal."    class=sin-borde size=15 >";
		$aa_objectpersonal[$ai_totrowspersonal][2]="<input name=txtnomper".$ai_totrowspersonal."    type=text   id=txtnomper".$ai_totrowspersonal."    class=sin-borde size=40 >";
		$aa_objectpersonal[$ai_totrowspersonal][3]="<input name=txtcedper".$ai_totrowspersonal."    type=text   id=txtcedper".$ai_totrowspersonal."    class=sin-borde size=11 >";
		$aa_objectpersonal[$ai_totrowspersonal][4]="<input name=txtcodcar".$ai_totrowspersonal."    type=text   id=txtcodcar".$ai_totrowspersonal."    class=sin-borde size=30 >";
		$aa_objectpersonal[$ai_totrowspersonal][5]="<input name=txtcodclavia".$ai_totrowspersonal." type=text   id=txtcodclavia".$ai_totrowspersonal." class=sin-borde size=10 style='text-align:center'>";
		$aa_objectpersonal[$ai_totrowspersonal][6]="<a href=javascript:uf_delete_dt_personal(".$ai_totrowspersonal.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
	
	} // end function uf_agregarlineablancapersonal
   
	function uf_agregarlineablancapresupuesto(&$aa_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtestpre".$ai_totrows."    type=text   id=txtestpre".$ai_totrows."    class=sin-borde size=60 style='text-align:center' readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtestcla".$ai_totrows."    type=text   id=txtestcla".$ai_totrows."    class=sin-borde size=10 style='text-align:center' readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtspgcuenta".$ai_totrows." type=text   id=txtspgcuenta".$ai_totrows." class=sin-borde size=20 style='text-align:left'   readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonpre".$ai_totrows."    type=text   id=txtmonpre".$ai_totrows."    class=sin-borde size=20 style='text-align:right'  readonly>";
	
	} // end function uf_agregarlineablancapresupuesto

	function uf_agregarlineablancacontable(&$aa_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtsccuenta".$ai_totrows." type=text   id=txtsccuenta".$ai_totrows."    class=sin-borde size=60 style='text-align:center'>";
		$aa_object[$ai_totrows][2]="<input name=txtdebhab".$ai_totrows."   type=text   id=txtspgcuenta".$ai_totrows." class=sin-borde size=30 style='text-align:left'   readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtmoncon".$ai_totrows."   type=text   id=txtmoncon".$ai_totrows."    class=sin-borde size=30 style='text-align:right'>";
	
	} // end function uf_agregarlineablancacontable

	function uf_repintarasignaciones(&$aa_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_repintarasignaciones
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar lo que esta impreso en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_fun_viaticos=new class_funciones_viaticos();
		for($li_i=1;$li_i<=$ai_totrows;$li_i++)
		{
			$ls_proasi= $io_fun_viaticos->uf_obtenervalor("txtproasig".$li_i,"");
			$ls_codasi= $io_fun_viaticos->uf_obtenervalor("txtcodasig".$li_i,"");
			$ls_denasi= $io_fun_viaticos->uf_obtenervalor("txtdenasig".$li_i,"");
			$li_canasi= $io_fun_viaticos->uf_obtenervalor("txtcantidad".$li_i,"");
			
			$aa_object[$li_i][1]="<input name=txtproasig".$li_i."  type=text   id=txtproasig".$li_i."  class=sin-borde size=16 value='". $ls_proasi ."' style='text-align:center'>";
			$aa_object[$li_i][2]="<input name=txtcodasig".$li_i."  type=text   id=txtcodasig".$li_i."  class=sin-borde size=11 value='". $ls_codasi ."'>";
			$aa_object[$li_i][3]="<input name=txtdenasig".$li_i."  type=text   id=txtdenasig".$li_i."  class=sin-borde size=55 value='". $ls_denasi ."'>";
			$aa_object[$li_i][4]="<input name=txtcantidad".$li_i." type=text   id=txtcantidad".$li_i." class=sin-borde size=12 value='". $li_canasi ."' style='text-align:right'>";
			$aa_object[$li_i][5]="<a href=javascript:uf_delete_dt_asignaciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		return true;
	
	} // end function uf_repintarasignaciones
   
	function uf_repintarpersonal(&$aa_objectpersonal,&$ai_totrowspersonal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_repintarpersonal
		//         Access: private
		//      Argumento: $aa_objectpersonal  // arreglo de titulos 
		//				   $ai_totrowspersonal // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar lo que esta impreso en el grid de personal
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 19/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_fun_viaticos=new class_funciones_viaticos();
		for($li_i=1;$li_i<=$ai_totrowspersonal;$li_i++)
		{
			$ls_codper=    $io_fun_viaticos->uf_obtenervalor("txtcodper".$li_i,"");
			$ls_nomper=    $io_fun_viaticos->uf_obtenervalor("txtnomper".$li_i,"");
			$ls_codcar=    $io_fun_viaticos->uf_obtenervalor("txtcodcar".$li_i,"");
			$ls_cedper=    $io_fun_viaticos->uf_obtenervalor("txtcedper".$li_i,"");
			$ls_codclavia= $io_fun_viaticos->uf_obtenervalor("txtcodclavia".$li_i,"");
	
			$aa_objectpersonal[$li_i][1]="<input name=txtcodper".$li_i."    type=text   id=txtcodper".$li_i."    class=sin-borde size=15 value='". $ls_codper ."'>";
			$aa_objectpersonal[$li_i][2]="<input name=txtnomper".$li_i."    type=text   id=txtnomper".$li_i."    class=sin-borde size=40 value='". $ls_nomper ."'>";
			$aa_objectpersonal[$li_i][3]="<input name=txtcedper".$li_i."    type=text   id=txtcedper".$li_i."    class=sin-borde size=11 value='". $ls_cedper ."'>";
			$aa_objectpersonal[$li_i][4]="<input name=txtcodcar".$li_i."    type=text   id=txtcodcar".$li_i."    class=sin-borde size=30 value='". $ls_codcar ."'>";
			$aa_objectpersonal[$li_i][5]="<input name=txtcodclavia".$li_i." type=text   id=txtcodclavia".$li_i." class=sin-borde size=10 value='". $ls_codclavia ."' style='text-align:center'>";
			$aa_objectpersonal[$li_i][6]="<a href=javascript:uf_delete_dt_personal(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		return true;
	} // end function uf_repintarpersonal

	function uf_scv_select_solicitudviaticos($as_codemp,$as_codsolvia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_solicitudviaticos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/10/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsolvia FROM scv_solicitudviatico".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_solicitudviaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end function uf_scv_select_solicitudviaticos

	function uf_scv_update_solicitudviatico($as_codemp,$as_codsolvia,$ai_monsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_update_solicitudviatico
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $as_codsolvia // codigo de solicitud de viaticos
		//                 $ai_monsolvia    // codigo de mision
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un maestro de solicitud de viaticos en la tabla scv_solicitudviatico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql= "UPDATE scv_solicitudviatico".
				 "   SET monsolvia='". $ai_monsolvia ."',".
				 "       estsolvia='P'".
				 " WHERE codemp='" . $as_codemp ."'".
				 "   AND codsolvia='" . $as_codsolvia ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitudviaticos MÉTODO->uf_scv_update_solicitudviatico ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Realizó el calculo de la solicitud de viaticos <b>".$as_codsolvia."</b> con un total de <b>Bs. ".$ai_monsolvia.
							 "</b>, Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
	    return $lb_valido;
	} // end  function uf_scv_update_solicitudviatico

	function uf_scv_load_dt_asignacion($as_codemp,$as_codsolvia,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_load_dt_asignacion
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $ai_totrows   // total de lineas del grid
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que carga el grid con las asignaciones de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT scv_dt_asignaciones.*,".
				 "       (CASE scv_dt_asignaciones.proasi".
				 "        WHEN 'TVS' THEN (SELECT scv_tarifas.dentar".
				 "                           FROM scv_tarifas".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_tarifas.codemp".
				 " 							  AND scv_dt_asignaciones.codasi=scv_tarifas.codtar)".
				 "        WHEN 'TRP' THEN (SELECT scv_transportes.dentra".
				 "                           FROM scv_transportes".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_transportes.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_transportes.codtra)".
				 "        WHEN 'TOA' THEN (SELECT scv_otrasasignaciones.denotrasi".
				 "                           FROM scv_otrasasignaciones".
				 "                          WHERE scv_dt_asignaciones.codemp=scv_otrasasignaciones.codemp".
				 "                            AND scv_dt_asignaciones.codasi=scv_otrasasignaciones.codotrasi)".
				 "		  ELSE (SELECT scv_tarifakms.dentar".
				 "                FROM scv_tarifakms".
				 "               WHERE scv_dt_asignaciones.codemp=scv_tarifakms.codemp".
				 "                 AND scv_dt_asignaciones.codasi=scv_tarifakms.codtar) END) AS denasi".
				 "  FROM scv_solicitudviatico,scv_dt_asignaciones".
				 " WHERE scv_solicitudviatico.codemp='".$as_codemp."'".
				 "   AND scv_solicitudviatico.codsolvia='".$as_codsolvia."'".
				 "   AND scv_solicitudviatico.codsolvia=scv_dt_asignaciones.codsolvia";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_load_dt_asignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codasi=$row["codasi"];
				$ls_proasi=$row["proasi"];
				$ls_denasi=$row["denasi"];
				$li_canasi=$row["canasi"];
				$li_canasi=number_format($li_canasi,2,',','.');
				$ai_totrows++;
				
				$ao_object[$ai_totrows][1]="<input name=txtproasig".$ai_totrows."  type=text   id=txtproasig".$ai_totrows."  class=sin-borde size=16 value='". $ls_proasi ."' style='text-align:center' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodasig".$ai_totrows."  type=text   id=txtcodasig".$ai_totrows."  class=sin-borde size=11 value='". $ls_codasi ."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdenasig".$ai_totrows."  type=text   id=txtdenasig".$ai_totrows."  class=sin-borde size=55 value='". $ls_denasi ."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcantidad".$ai_totrows." type=text   id=txtcantidad".$ai_totrows." class=sin-borde size=12 value='". $li_canasi ."' style='text-align:right' readonly>";
				
			}
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_load_dt_asignacion

	function uf_scv_load_dt_personal($as_codemp,$as_codsolvia,&$ai_totrows,&$ao_objectpersonal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_load_dt_personal
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $ai_totrows   // total de lineas del grid
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que carga el grid con el personal de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_scv_select_categoria_personal($as_codemp,$as_codsolvia);
		if($lb_existe)
		{
			$ls_sql="SELECT (CASE sno_nomina.racnom WHEN 1 THEN sno_asignacioncargo.denasicar ELSE sno_cargo.descar END) AS cargo,".
					"       scv_dt_personal.codclavia,sno_personalnomina.codper,".
					"		(SELECT nomper FROM sno_personal".
					"  		  WHERE sno_personal.codper=sno_personalnomina.codper) as nomper,".
					"		(SELECT apeper FROM sno_personal".
					"   	  WHERE sno_personal.codper=sno_personalnomina.codper) as apeper,".
					"		(SELECT cedper FROM sno_personal".
					"		  WHERE sno_personal.codper=sno_personalnomina.codper) as cedper".
					"  FROM sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo,sno_personal,scv_dt_personal".
					" WHERE scv_dt_personal.codemp='".$as_codemp."'".
					"   AND scv_dt_personal.codsolvia='".$as_codsolvia."'".
					"   AND scv_dt_personal.codemp=sno_personal.codemp".
					"   AND scv_dt_personal.codper=sno_personal.codper".
					"   AND scv_dt_personal.codemp=sno_personalnomina.codemp".
					"   AND scv_dt_personal.codnom=sno_personalnomina.codnom".
					"   AND sno_nomina.espnom=0".
					"   AND sno_personalnomina.codemp = sno_nomina.codemp".
					"   AND sno_personalnomina.codnom = sno_nomina.codnom".
					"   AND sno_personalnomina.codper = sno_personal.codper".
					"   AND sno_personalnomina.codemp = sno_cargo.codemp".
					"   AND sno_personalnomina.codnom = sno_cargo.codnom".
					"   AND sno_personalnomina.codcar = sno_cargo.codcar".
					"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp".
					"   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom".
					"   AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar".
					" GROUP BY sno_personalnomina.codper,  sno_personalnomina.codper, sno_nomina.racnom,  ".
					" sno_asignacioncargo.denasicar, sno_cargo.descar,scv_dt_personal.codclavia".
					" ORDER BY sno_personalnomina.codper,codclavia";
		}
		else
		{
			$ls_sql="SELECT scv_dt_personal.codper,rpc_beneficiario.ced_bene,".
					"       (SELECT nombene ".
					"          FROM rpc_beneficiario".
					"         WHERE scv_dt_personal.codemp=rpc_beneficiario.codemp".
					"           AND scv_dt_personal.codper=rpc_beneficiario.ced_bene) AS nombene,".
					"       (SELECT apebene ".
					"          FROM rpc_beneficiario".
					"         WHERE scv_dt_personal.codemp=rpc_beneficiario.codemp".
					"           AND scv_dt_personal.codper=rpc_beneficiario.ced_bene) AS apebene".
					"  FROM scv_dt_personal,rpc_beneficiario".
					" WHERE scv_dt_personal.codemp='".$as_codemp."'".
					"   AND scv_dt_personal.codsolvia='".$as_codsolvia."'".
					"   AND scv_dt_personal.codper=rpc_beneficiario.ced_bene";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_load_dt_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($lb_existe)
				{
					$ls_codper=$row["codper"];
					$ls_cedper=$row["cedper"];
					$ls_nomper=$row["nomper"]." ".$row["apeper"];
					$ls_codcar= $row["cargo"];				
					$ls_codclavia=$row["codclavia"];			
				}
				else
				{
					$ls_codper=$row["codper"];
					$ls_cedper=$row["ced_bene"];

					$ls_nomper=$row["nombene"]." ".$row["apebene"];
					$ls_codcar="";				
					$ls_codclavia="";			
				}
				$ai_totrows++;
				
				$ao_objectpersonal[$ai_totrows][1]="<input name=txtcodper".$ai_totrows."    type=text   id=txtcodper".$ai_totrows."    class=sin-borde size=15 value='". $ls_codper ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][2]="<input name=txtnomper".$ai_totrows."    type=text   id=txtnomper".$ai_totrows."    class=sin-borde size=40 value='". $ls_nomper ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][3]="<input name=txtcedper".$ai_totrows."    type=text   id=txtcedper".$ai_totrows."    class=sin-borde size=11 value='". $ls_cedper ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][4]="<input name=txtcodcar".$ai_totrows."    type=text   id=txtcodcar".$ai_totrows."    class=sin-borde size=30 value='". $ls_codcar ."'     readonly>";
				$ao_objectpersonal[$ai_totrows][5]="<input name=txtcodclavia".$ai_totrows." type=text   id=txtcodclavia".$ai_totrows." class=sin-borde size=10 value='". $ls_codclavia ."'  readonly style='text-align:center'>";
				$ao_objectpersonal[$ai_totrows][6]="<a href=javascript:uf_delete_dt_personal(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
				
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_load_dt_personal

	function uf_scv_select_categoriaviaticos($as_codemp,$as_codtar,$as_codcatper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_solicitudviaticos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codtar    // codigo de tarifa
		//  			   $as_codcatper // codigo de tarifa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que la tarifa de viaticos se corresponda con la categoria del personal
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codcat".
				"  FROM scv_tarifas".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codtar='". $as_codtar ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_solicitudviaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codcat=$row["codcat"];
				if($ls_codcat==$as_codcatper)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_solicitudviaticos

	function uf_scv_select_tarifasviaticos($as_codemp,$as_codtar,$as_codcatper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_solicitudviaticos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codtar    // codigo de tarifa
		//  			   $as_codcatper // codigo de categoria de personal
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que la tarifa de viaticos se corresponda con la categoria del personal
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codcat".
				"  FROM scv_tarifas".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codtar='". $as_codtar ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_solicitudviaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codcat=$row["codcat"];
				if($ls_codcat==$as_codcatper)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_solicitudviaticos

	function uf_scv_load_tarifasviaticos($as_codemp,$as_proasi,$as_codasi,$ai_canasi,&$ai_monasi,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_load_tarifasviaticos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_proasi    // procedencia de la asignacion
		//  			   $as_codasi    // codigo de la asignacion
		//  			   $ai_canasi    // cantidad de asignaciones
		//  			   $ai_monasi    // monto por asignaciones
		//  			   $as_codsolvia // codigo de la solicitud de viaticos
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los montos de las tarifas de los viaticos incluidos en una solicitud
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_proasi=trim($as_proasi);
		switch ($as_proasi)
		{
			case "TVS":
				$lb_valido=$this->uf_scv_select_tarifas($as_codemp,$as_codasi,$ls_montar);
			break;
			case "TRP":
				$lb_valido=$this->uf_scv_select_tarifastransporte($as_codemp,$as_codasi,$ls_montar);
			break;
			case "TDS":
				$lb_valido=$this->uf_scv_select_tarifasdistancias($as_codemp,$as_codasi,$ls_montar);
			break;
			case "TOA":
				$lb_valido=$this->uf_scv_select_otrasasignaciones($as_codemp,$as_codasi,$ls_montar);
			break;
		}
		if($lb_valido)
		{
			$ai_monasi=($ls_montar*$ai_canasi);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Calculó el monto de la asignacion <b>".$as_codasi."</b> de procedencia  <b>".$as_proasi.
							 "</b> perteneciente a la Solicitud de Viaticos <b>".$as_codsolvia."</b> Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end function uf_scv_load_tarifasviaticos

	function uf_scv_select_tarifas($as_codemp,$as_codtar,&$as_montar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_tarifas
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codtar    // codigo de tarifa
		//  			   $as_montar   // monto de la tarifa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica busca el monto total de la tarifa de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codcat,monbol,mondol,monpas,monhos,monali,monmov".
				"  FROM scv_tarifas".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codtar='". $as_codtar ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_tarifas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_monbol=$row["monbol"];
				$li_mondol=$row["mondol"];
				$li_monpas=$row["monpas"];
				$li_monhos=$row["monhos"];
				$li_monali=$row["monali"];
				$li_monmov=$row["monmov"];
				$as_montar=($li_monbol+$li_mondol+$li_monpas+$li_monali+$li_monhos+$li_monmov);
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_tarifas

	function uf_scv_select_tarifasdistancias($as_codemp,$as_codtar,&$as_montar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_tarifas
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codtar    // codigo de tarifa
		//  			   $as_montar   // monto de la tarifa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica busca el monto de la tarifa por distancias
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT montar".
				"  FROM scv_tarifakms".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codtar='". $as_codtar ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_tarifas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_montar=$row["montar"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_tarifas

	function uf_scv_select_tarifastransporte($as_codemp,$as_codtra,&$as_montar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_tarifastransporte
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codtra    // codigo de transporte
		//  			   $as_montar   // monto de la tarifa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica busca el monto de la tarifa de transporte
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT tartra".
				"  FROM scv_transportes".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codtra='". $as_codtra ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_tarifastransporte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_montar=$row["tartra"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_tarifastransporte

	function uf_scv_select_otrasasignaciones($as_codemp,$as_codotrasi,&$as_montar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_otrasasignaciones
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codotrasi // codigo de otras asignaciones
		//  			   $as_montar    // monto de la tarifa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica busca el monto de otras asignaciones de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT tarotrasi".
				"  FROM scv_otrasasignaciones".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codotrasi='". $as_codotrasi ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_otrasasignaciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_montar=$row["tarotrasi"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_otrasasignaciones

	function uf_scv_load_config($as_codemp,$as_codsis,$as_seccion,$as_entry,&$as_spgcuenta) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_config
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de la Empresa.
		//        			   $as_codsis    //  código de sistema
		//        			   $as_seccion   //  tipo de dato
		//        			   $as_entry     // 
		//        			   $as_spgcuenta // cuenta presupuestaria
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de cargar la cuenta asociada a los viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT value".
				"   FROM sigesp_config".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codsis='".$as_codsis."'".
				"    AND seccion='".$as_seccion."'".
				"    AND entry='".$as_entry."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_spgcuenta=$row["value"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end function uf_scv_load_config

	function uf_scv_load_estructuraunidad($as_codemp,$as_codsolvia,$as_coduniadm,&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,
										  &$as_codestpro5,&$as_estcla) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_estructuraunidad
		//	          Access:  public
		//	       Arguments:  $as_codemp     //  codigo de la Empresa.
		//        			   $as_coduniadm  //  codigo de unidad ejecutora
		//                     $as_codsolvia  //  codigo de la solicutd de viaticos
		//        			   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                     $as_estcla     //  estatus de la estructura programatica
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de cargar la estructura presupuestaria de una unidad ejecutora
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla".
				"   FROM  scv_solicitudviatico ".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codsolvia='".$as_codsolvia."'".
				"    AND coduniadm='".$as_coduniadm."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_estructuraunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codestpro1=$row["codestpro1"];
				$as_codestpro2=$row["codestpro2"];
				$as_codestpro3=$row["codestpro3"];
				$as_codestpro4=$row["codestpro4"];
				$as_codestpro5=$row["codestpro5"];
				$as_estcla=$row["estcla"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end function uf_scv_load_estructuraunidad

	function uf_scv_select_cuentaspg($as_codemp,$as_spgcta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
									 $as_codestpro5,$as_estcla) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_estructuraunidad
		//	          Access:  public
		//	       Arguments:  $as_codemp     //  codigo de la Empresa.
		//        			   $as_spgcta     //  cuenta presupuestaria de gasto
		//        			   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                     $as_estcla     //  estatus de la estructura programatica
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una cuenta presupuestaria en una estructura 
		//                     programatica
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT spg_cuenta".
				"   FROM spg_cuentas".
				"  WHERE codemp='".$as_codemp."'".
				"    AND spg_cuenta='".$as_spgcta."'".
				"    AND codestpro1='".$as_codestpro1."'".
				"    AND codestpro2='".$as_codestpro2."'".
				"    AND codestpro3='".$as_codestpro3."'".
				"    AND codestpro4='".$as_codestpro4."'".
				"    AND codestpro5='".$as_codestpro5."'".
				"    AND estcla='".$as_estcla."' " ;
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_estructuraunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end function uf_scv_load_estructuraunidad
	
	function uf_scv_select_disponibilidad($as_codemp,$as_spgcuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
										  $as_codestpro5,$as_estcla,&$as_sccuenta,&$ai_disponible) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_estructuraunidad
		//	          Access:  public
		//	       Arguments:  $as_codemp     //  codigo de la Empresa.
		//        			   $as_spgcuenta  //  cuenta presupuestaria
		//        			   $as_codestpro1 //  codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 //  codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 //  codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 //  codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 //  codigo de estructura programatica nivel 5
		//                     $as_estcla     //  estatus de la estructura programatica
		//        			   $as_sccuenta   //  cuenta contable asociada
		//        			   $ai_disponible //  disponibilidad en la cuenta
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de cargar la cuenta contable y calcula la disponibilidad
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  16/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql="SELECT sc_cuenta,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible".
				"  FROM spg_cuentas ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND spg_cuenta= '".$as_spgcuenta."'".
				"   AND codestpro1= '".$as_codestpro1."'".
				"   AND codestpro2= '".$as_codestpro2."'".
				"   AND codestpro3= '".$as_codestpro3."'".
				"   AND codestpro4= '".$as_codestpro4."'".
				"   AND codestpro5= '".$as_codestpro5."'".
				"   AND estcla= '".$as_estcla."' ";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_solicitudviaticos METODO->uf_scv_load_estructuraunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_sccuenta=   $row["sc_cuenta"];
				$ai_disponible= $row["disponible"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end function uf_scv_load_estructuraunidad

	function uf_scv_procesar_asientos($as_codemp,$as_coduniadm,$as_codsis,$as_seccion,$as_entry,&$as_spgcuenta,&$as_estpre,
									  &$as_sccuenta,&$ai_disponible,&$as_codestpro1,&$as_codestpro2,&$as_codestpro3,
									  &$as_codestpro4,&$as_codestpro5,&$as_estcla,$as_codsolvia) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_estructuraunidad
		//	          Access:  public
		//	       Arguments:  $as_codemp     // codigo de empresa.
		//        			   $as_coduniadm  //  código de unidad ejecutora
		//        			   $as_codsis     //  código de sistema
		//        			   $as_seccion    //  tipo de dato
		//        			   $as_entry      // 
		//        			   $as_spgcuenta  // cuenta presupuestaria
		//        			   $as_estpre     // estructura presupuestaria
		//        			   $as_sccuenta   // cuenta contable de gasto
		//        			   $ai_disponible // disponibilidad de cuenta
		//        			   $as_codestpro1 // codigo de estructura programatica nivel 1
		//        			   $as_codestpro2 // codigo de estructura programatica nivel 2
		//        			   $as_codestpro3 // codigo de estructura programatica nivel 3
		//        			   $as_codestpro4 // codigo de estructura programatica nivel 4
		//        			   $as_codestpro5 // codigo de estructura programatica nivel 5
		//                     $as_estcla     // estatus de la estructura programatica
		//                     $as_codsolvia  // codigo de la solicitud de viaticos
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una cuenta presupuestaria en una estructura 
		//                     programatica
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  14/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$lb_valido=$this->uf_scv_load_config($as_codemp,$as_codsis,$as_seccion,$as_entry,$as_spgcuenta);
		if($lb_valido)
		{
			$lb_valido=$this->uf_scv_load_estructuraunidad($as_codemp,$as_codsolvia,$as_coduniadm,$as_codestpro1,$as_codestpro2,
			                                               $as_codestpro3,
														   $as_codestpro4,$as_codestpro5,$as_estcla);
			if($lb_valido)
			{
				$as_estpre=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5.$as_estcla;
				$lb_valido=$this->uf_scv_select_disponibilidad($as_codemp,$as_spgcuenta,$as_codestpro1,$as_codestpro2,
															   $as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
															   $as_sccuenta, $ai_disponible);
			}
		}
		return $lb_valido;
	} // end function uf_scv_load_estructuraunidad
	
	function uf_scv_load_presupuesto($as_estpreaux,$as_spgcuenta,$ai_totsolvia,$as_estpre,$as_estcla,&$aa_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_load_presupuesto
		//         Access: private
		//      Argumento: $as_estpre    // estructura presupuestaria de gasto
		//				   $as_spgcuenta // cuenta presupuestaria de viaticos
		//				   $ai_totsolvia // monto total del viatico
		//				   $aa_object    // arreglo de titulos 
		//				   $ai_totrows   // ultima fila pintada en el grid
		//	      Returns: $lb_valido
		//    Description: Funcion que carga un asiento presupuestario 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 16/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_totsolvia= number_format($ai_totsolvia,2,',','.');
		$aa_object[$ai_totrows][1]="<input name=txtestpreaux".$ai_totrows." type=text   id=txtestpreaux".$ai_totrows." class=sin-borde size=60 value='". $as_estpreaux ."' style='text-align:center' readonly>".
								   "<input name=txtestpre".$ai_totrows."    type=hidden id=txtestpre".$ai_totrows."    class=sin-borde size=60 value='". $as_estpre ."'    style='text-align:center' readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtestclaaux".$ai_totrows." type=text   id=txtestclaaux".$ai_totrows." class=sin-borde size=10 value='".$as_estcla."' style='text-align:center' readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtspgcuenta".$ai_totrows." type=text   id=txtspgcuenta".$ai_totrows." class=sin-borde size=20 value='". $as_spgcuenta ."' style='text-align:left'   readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonpre".$ai_totrows."    type=text   id=txtmonpre".$ai_totrows."    class=sin-borde size=20 value='". $li_totsolvia ."' style='text-align:right'  readonly>";
		$ai_totrows=$ai_totrows+1;
		$this->uf_agregarlineablancapresupuesto($aa_object,$ai_totrows);
	
		return true;
	} // end function uf_scv_load_presupuesto

	function uf_scv_load_contable($as_sccuenta,$ai_totsolvia,$as_scben,&$aa_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_totsolvia= number_format($ai_totsolvia,2,',','.');
		for($ai_totrows=1;$ai_totrows<3;$ai_totrows++)
		{
			if($ai_totrows==1)
			{
				$ls_debhab="DEBE";
				$ls_sccuenta=$as_sccuenta;
			}
			else
			{
				$ls_debhab="HABER";
				$ls_sccuenta=$as_scben;
			}
			$aa_object[$ai_totrows][1]="<input name=txtsccuenta".$ai_totrows." type=text   id=txtsccuenta".$ai_totrows."  class=sin-borde size=60 value='". $ls_sccuenta ."'  style='text-align:center' readonly>";
			$aa_object[$ai_totrows][2]="<input name=txtdebhab".$ai_totrows."   type=text   id=txtspgcuenta".$ai_totrows." class=sin-borde size=30 value='". $ls_debhab ."'    style='text-align:left'   readonly>";
			$aa_object[$ai_totrows][3]="<input name=txtmoncon".$ai_totrows."   type=text   id=txtmoncon".$ai_totrows."    class=sin-borde size=30 value='". $li_totsolvia ."' style='text-align:right'  readonly>";
		}
		$this->uf_agregarlineablancacontable($aa_object,$ai_totrows);
	
	} // end function uf_scv_load_contable

	function  uf_scv_insert_dt_spg($as_codemp,$as_codsolvia,$as_codcom,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
								   $as_codestpro5,$as_estcla,$as_spgcuenta,$as_operacion,$as_codpro,$as_cedbene,$as_tipodestino,
								   $as_descripcion,$ai_monto,$as_codfuefin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_insert_dt_spg
		//         Access: public 
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_codsolvia   // codigo de solicitud de viaticos
		//                 $as_codcom      // codigo de comprobante
		//                 $as_codestpro1  // codigo de estructura programatica nivel 1
		//                 $as_codestpro2  // codigo de estructura programatica nivel 2
		//                 $as_codestpro3  // codigo de estructura programatica nivel 3
		//                 $as_codestpro4  // codigo de estructura programatica nivel 4
		//                 $as_codestpro5  // codigo de estructura programatica nivel 5
		//                 $as_estcla      // estatus de la estructura programatica
		//                 $as_spgcuenta   // cuenta de presupuesto de gasto
		//                 $as_operacion   // tipo de operacion
		//                 $as_codpro      // codigo de proveedor
		//                 $as_cedbene     // cedula de beneficiario
		//                 $as_tipodestino // tipo de destino
		//                 $as_descripcion // descripcion del comprobante
		//                 $ai_monto       // monto del comprobante
		//                 $as_codfuefin   // código fuente de financiamiento
		//				   $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el detalle presupuestario de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 21/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO scv_dt_spg (codemp,codsolvia,codcom,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				 "                        estcla,spg_cuenta,operacion,cod_pro,ced_bene,tipo_destino,descripcion,monto,estatus, ".
				 "                        codfuefin) ".
				 "     VALUES('".$as_codemp."','".$as_codsolvia."','".$as_codcom."','".$as_codestpro1."','".$as_codestpro2."',".
				 "            '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."','".$as_spgcuenta."','".$as_operacion."',".
				 "            '".$as_codpro."','".$as_cedbene."','".$as_tipodestino."','".$as_descripcion."','".$ai_monto."',0, ".
				 "            '".$as_codfuefin."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_insert_dt_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Insertó el comprobante presupuestario <b>".$as_codcom."</b> de la Solicitud de Viaticos <b>".$as_codsolvia.
								"</b> al beneficiario <b>".$as_cedbene."</b> en la cuenta <b>".$as_spgcuenta."</b> Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
		}
		return $lb_valido;
	} //end function  uf_scv_insert_dt_spg

	function  uf_scv_insert_dt_scg($as_codemp,$as_codsolvia,$as_codcom,$as_sccuenta,$as_debhab,$as_codpro,$as_cedbene,
								   $as_tipodestino,$as_descripcion,$ai_monto,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_insert_dt_scg
		//         Access: public 
		//      Argumento: $as_codemp      // codigo de empresa 
		//                 $as_codsolvia   // codigo de solicitud de viaticos
		//                 $as_codcom      // codigo de comprobante
		//                 $as_sccuenta    // cuenta contable
		//                 $as_debhab      // indica si el asiento va por el debe o por el haber
		//                 $as_codpro      // codigo de proveedor
		//                 $as_cedbene     // cedula de beneficiario
		//                 $as_tipodestino // tipo de destino
		//                 $as_descripcion // descripcion del comprobante
		//                 $ai_monto       // monto del comprobante
		//				   $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el detalle contable de la solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 21/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "INSERT INTO scv_dt_scg (codemp,codsolvia,codcom,sc_cuenta,debhab,cod_pro,ced_bene,tipo_destino,
													descripcion,monto,estatus) ".
				 "     VALUES('".$as_codemp."','".$as_codsolvia."','".$as_codcom."','".$as_sccuenta."','".$as_debhab."',".
				 "            '".$as_codpro."','".$as_cedbene."','".$as_tipodestino."','".$as_descripcion."','".$ai_monto."',0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_insert_dt_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el comprobante contable <b>".$as_codcom."</b> de la Solicitud de Viaticos <b>".$as_codsolvia.
							"</b> al beneficiario <b>".$as_cedbene."</b> Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											   $aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			
			
		}
		return $lb_valido;
	} //end function  uf_scv_insert_dt_scg
	
	function uf_scv_procesar_recepcion_documento_viatico($as_codsolvia,$as_comprobante,$as_cedbene,$as_codtipdoc,
														 $as_descripcion,$ad_fecha,$ai_monto,$as_codfuefin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_procesar_recepcion_documento_viatico
		//		   Access: private
		//	    Arguments: $as_codsolvia    // codigo de solicitud de viaticos
		//                 $as_comprobante  // Codigo de Comprobante
		//				   $as_cedbene 		// cedula de beneficiario
		//				   $as_codtipdoc	// codigo de tipo de documento
		//				   $as_descripcion	// descripcion del documento
		//				   $ad_fecha  		// Fecha de contabilización
		//				   $ad_fecha  		// Fecha de contabilización
		//                 $as_codfuefin    // Código de la fuente de financiamiento
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
        $ls_tipodestino= "B";			
		$ls_codpro= "----------";	
		$ad_fecha= $this->io_funcion->uf_convertirdatetobd($ad_fecha);
		$ls_sql="INSERT INTO cxp_rd (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,dencondoc,fecemidoc, fecregdoc, fecvendoc,".
 		        "                    montotdoc, mondeddoc,moncardoc,tipproben,numref,estprodoc,procede,estlibcom,estaprord,".
				"                    fecaprord,usuaprord,estimpmun,codcla,codfuefin)".
				"     VALUES ('".$this->is_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$as_cedbene."',".
				"             '".$ls_codpro."','".$as_descripcion."','".$ad_fecha."','".$ad_fecha."','".$ad_fecha."',
				"               .$ai_monto.",0,0,'".$ls_tipodestino."','".$as_comprobante."','R','SCVSOV',0,0,'1900-01-01','',0,'--','".$as_codfuefin."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{  
           	print ($this->io_sql->message);
			$this->io_msg->message("CLASE->sigesp_scv_c_calcularviaticos MÉTODO->uf_scv_procesar_recepcion_documento_viatico ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Generó la Recepción de Documento Solicitud de Viáticos <b>".$as_codsolvia."</b>, ".
							"Comprobante <b>".$as_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											  $aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$li_mondeddoc=0;
			$li_moncardoc=0;
			
		}
		return $lb_valido;
	}  // end function uf_scv_procesar_recepcion_documento_viatico

	function uf_insert_recepcion_documento_gasto($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion_documento_gasto
		//		   Access: private
		//	    Arguments: $as_comprobante // Código de Comprobante
		//				   $as_codtipdoc   // Tipo de Documento
		//				   $as_cedbene     // Cédula del Beneficiario
		//				   $as_codpro      // Código del Proveedor
		//				   $ai_monto       // monto del comprobante
		//	      Returns: $lb_valido True si se inserto los detalles presupuestario en la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_procede="SCVSOV";
		$ls_sql="SELECT codemp, codsolvia, codcom, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, ".
		        " spg_cuenta, operacion, cod_pro, ced_bene, tipo_destino, descripcion, monto, estatus,codfuefin ".
				"  FROM scv_dt_spg ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND codcom='".$as_comprobante."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->sigesp_scv_c_calcularviaticos MÉTODO->uf_insert_recepcion_documento_gasto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_codestpro=$row["codestpro1"].$row["codestpro2"].$row["codestpro3"].$row["codestpro4"].$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_spg_cuenta= $row["spg_cuenta"];
				$ls_documento=  $row["codcom"];								 
				$ls_cedbene=    $row["ced_bene"];								 
				$ls_codpro=     $row["cod_pro"];
				$ls_codfuefin=  $row["codfuefin"];								 
				$ls_documento=$this->io_sigesp_int->uf_fill_comprobante($ls_documento);
				$ls_sql="INSERT INTO cxp_rd_spg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,codestpro,".
						"						 spg_cuenta,monto,estcla,codfuefin)".
						"     VALUES ('".$this->is_codemp."','".$as_comprobante."','".$as_codtipdoc."',".
						"             '".$ls_cedbene."','".$ls_codpro."','".$ls_procede."','".$ls_documento."','".$ls_codestpro."',".
						"             '".$ls_spg_cuenta."',".$ai_monto.",'".$ls_estcla."','".$ls_codfuefin."')";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
           			$this->io_msg->message("CLASE->sigesp_scv_c_calcularviaticos MÉTODO->uf_insert_recepcion_documento_gasto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
				   	$lb_valido=false;
				   	break;
				}
				
			} // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_insert_recepcion_documento_gasto

	function uf_insert_recepcion_documento_contable($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion_documento_contable
		//		   Access: private
		//	    Arguments: $as_comprobante // Código de Comprobante
		//				   $as_codtipdoc   // Tipo de Documento
		//				   $as_cedbene     // Cédula del Beneficiario
		//				   $as_codpro      // Código del Proveedor
		//				   $ai_monto       // monto del comprobante
		//	      Returns: $lb_valido True si se inserto los detalles contables en la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/11/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_procede="SCVSOV";
		$ls_sql="SELECT codemp, codsolvia, codcom, sc_cuenta, debhab, cod_pro, ced_bene, tipo_destino, descripcion, monto, estatus".
				"  FROM scv_dt_scg ".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND codcom='".$as_comprobante."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->sigesp_scv_c_calcularviaticos MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
			while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
			{
				$ls_sccuenta= $row["sc_cuenta"];
				$ls_debhab=     $row["debhab"];				
				$ls_documento=  $row["codcom"];								 
				$ls_cedbene=    $row["ced_bene"];								 
				$ls_codpro=     $row["cod_pro"];								 
				$ls_documento= $this->io_sigesp_int->uf_fill_comprobante($ls_documento);
				$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,".
						"						 sc_cuenta,monto)".
						"     VALUES ('".$this->is_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$ls_cedbene."',".
						"             '".$ls_codpro."','".$ls_procede."','".$ls_documento."','".$ls_debhab."',".
						"             '".$ls_sccuenta."',".$ai_monto.")";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
		           	$this->io_msg->message("CLASE->sigesp_scv_c_calcularviaticos MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
				    $lb_valido=false;
				    break;
				}
				
			} // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_insert_recepcion_documento_contable

	function uf_scv_select_categoria_personal($as_codemp,$as_codsolvia)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_categoria_personal
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codclavia".
		        "  FROM scv_dt_personal".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->solicitud_viaticos MÉTODO->uf_scv_select_categoria_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codclavia=$row["codclavia"];
				if($ls_codclavia!="")
				{$lb_valido=true;}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_scv_select_categoria_personal

} //end class sigesp_scv_c_calcularviaticos  
?>
