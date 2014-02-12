<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
class sigesp_sim_c_articulo_transf
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_articulo_transf()
	{
		
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		
		$in=new sigesp_include();
		$this->archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO");
		//$this->archivo= new sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO");
		$this->con=$in->uf_conectar();
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	
	function  uf_sim_insert_articulo_transf($as_codemp, $as_codart, $as_denart, $as_codtipart, $as_codunimed, $ad_feccreart, $as_obsart,
									 $ai_exiart, $ai_exiiniart, $ai_minart, $ai_maxart, $ai_prearta, $ai_preartb, 
									 $ai_preartc, $ai_preartd, $ad_fecvenart, $as_spg_cuenta, $ai_pesart, $ai_altart, $ai_ancart,
									 $ai_proart, $as_fotart, $as_codcatsig, $as_sccuenta, $aa_seguridad)
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
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 30/08/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="INSERT INTO sim_articulo (codemp,codart,denart,codtipart,codunimed,feccreart,obsart,exiart,exiiniart,      minart,maxart,prearta,preartb,preartc,preartd,fecvenart,spg_cuenta,pesart,altart,ancart, proart,fotart,codcatsig,sc_cuenta)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_denart."','".$as_codtipart."','".$as_codunimed."','".$ad_feccreart."','".$as_obsart."',".$ai_exiart.",".$ai_exiiniart.",".$ai_minart.",".$ai_maxart.",".$ai_prearta.",".$ai_preartb.",".$ai_preartc.",".$ai_preartd.",'".$ad_fecvenart."','".$as_spg_cuenta."',".$ai_pesart.",".$ai_altart.",".$ai_ancart.",".$ai_proart.",'".$as_fotart."','".$as_codcatsig."','".$as_sccuenta."');";
		

		/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/
		if (substr($as_codart,4,1)=='V')
		{
		$ls_nomarchivo="trans";
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		}
		
		//$archivo = fopen($ls_archivo, "a+");															
		//fwrite($archivo,$ls_sql);																			
		//fclose($archivo);	
		
	} // end  function  uf_sim_insert_articulo

	function  uf_sim_update_articulo_transf($as_codemp, $as_codart, $as_denart, $as_codtipart, $as_codunimed, $ad_feccreart, $as_obsart,$ai_exiart, $ai_exiiniart, $ai_minart, $ai_maxart, $ai_prearta, $ai_preartb,$ai_preartc, $ai_preartd, $ad_fecvenart, $as_spg_cuenta, $ai_pesart, $ai_altart, $ai_ancart,$ai_proart, $as_fotart, $as_codcatsig, $as_sccuenta, $aa_seguridad)
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
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $li_exce=-1;
		 $ls_sql = "UPDATE sim_articulo SET   denart='". $as_denart ."',codtipart='". $as_codtipart ."',codunimed='". $as_codunimed ."',".
					" 						  feccreart='". $ad_feccreart ."',obsart='". $as_obsart ."',exiart='". $ai_exiart ."',".
					" 						  exiiniart='". $ai_exiiniart ."',minart='". $ai_minart ."',maxart='". $ai_maxart ."',". 
					" 						  prearta='". $ai_prearta ."',preartb='". $ai_preartb ."',preartc='". $ai_preartc ."', ". 
					" 						  preartd='". $ai_preartd ."',fecvenart='". $ad_fecvenart ."',spg_cuenta='". $as_spg_cuenta ."',".
					"						  pesart='". $ai_pesart ."',altart='". $ai_altart ."',ancart='". $ai_ancart ."',".
					"						  proart='". $ai_proart ."',fotart='". $as_fotart ."',codcatsig='". $as_codcatsig ."',".
					"						  sc_cuenta='". $as_sccuenta ."'".
					" WHERE codart='" . $as_codart ."'".
					"   AND codemp='" . $as_codemp ."' ;";
        
		/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/
		if (substr($as_codart,4,1)=='V')
		{
		$ls_nomarchivo="trans";
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		}		
				
	} // end function  uf_sim_update_articulo

	function uf_sim_delete_articulo_transf($as_codemp,$as_codart,$aa_seguridad)
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
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
					$ls_sql = " DELETE FROM sim_articulo".
							  " WHERE codemp= '".$as_codemp. "'".
							  " AND codart= '".$as_codart. "';"; 
		/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/
		if (substr($as_codart,4,1)=='V')
		{
		$ls_nomarchivo="trans";
		$this->archivo->crear_archivo($ls_nomarchivo);
		$this->archivo->escribir_archivo($ls_sql);
		$this->archivo->cerrar_archivo();
		}
	} // end  function uf_sim_delete_articulo

} 
?>
