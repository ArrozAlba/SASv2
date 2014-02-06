<?php

class sigesp_sss_class_report
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;

	function sigesp_sss_class_report()
	{
		require_once("../../shared/class_folder/class_sql.php");
//		require_once("../../shared/class_folder/class_datastore.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
		$this->ds_detalle=new class_datastore();
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////                Funciones del Reporte de Auditoria                            ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_sss_select_auditoria($as_codemp,$as_codusu,$as_evento,$as_codsis,$ad_fecdes,$ad_fechas,&$rs_data)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_auditoria
	//	           Access:   public
	//  		Arguments:   as_codemp    // codigo de empresa
	//  			         as_codusu    // codigo de ususario
	//  			         as_evento    // codigo de evento
	//  			         as_codsis    // codigo de sistema
	//  			         ad_fecdes    // fecha de inicio del periodo de busqueda
	//  			         ad_fecdes    // fecha de cierre del periodo de busqueda
	//						 rs_data    // arreglo con los resultados de la consulta
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Función que se encarga de realizar la busqueda  de las operaciones del sistema registradas en el modulo 
	//						de seguridad.
	//         Creado por:  Ing. Luis Anibal Lang           
	//     Modificado por:  Ing. María Beatriz Unda
	//   Fecha de Cracion:   20/05/2006							Fecha de Ultima Modificación:   25/08/2008
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlint="";
		$ls_sqlusu="";
		$ls_sqleve="";
		$ls_sqlsis="";
		if(!empty($as_codusu)){$ls_sqlusu="  AND codusu ='".$as_codusu."'";}
		if(!empty($as_evento)){$ls_sqleve="  AND evento ='".$as_evento."'";}
		if(!empty($as_codsis)){$ls_sqlsis="  AND codsis ='".$as_codsis."'";}

		if((!empty($ad_fecdes))&&(!empty($ad_fechas)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdes);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechas);
			$ls_min=" 23:59:59";
			$ls_sqlint= " AND fecevetra >= '".$ld_auxdesde."'".
			            " AND fecevetra <='".$ld_auxhasta.$ls_min."'" ;
		}
		$ls_sql="SELECT sss_registro_eventos.*,".
				"      (SELECT titven FROM sss_sistemas_ventanas".
				"        WHERE sss_registro_eventos.nomven=sss_sistemas_ventanas.nomven".
				"          AND sss_registro_eventos.codsis=sss_sistemas_ventanas.codsis) AS titven".
				"  FROM sss_registro_eventos".
				" WHERE codemp='".$as_codemp."' ".
				$ls_sqlint.
				$ls_sqlusu.
				$ls_sqleve.
				$ls_sqlsis.
				" ORDER BY numeve";					
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_sss_select_auditoria ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		return $lb_valido; 
	} // fin function uf_sss_select_auditoria

	function uf_sss_select_sistema($as_codemp,$as_codsis,&$as_nomsis)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_sistema
	//	           Access:   public
	//  		Arguments:   
	//  			         as_codemp     // codigo de empresa
	//  			         as_codsis     // codigo de sistema
	//  			         as_nomsis     // nombre de sistema
	//	         Returns :  $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Funcion que obtiene el nombre de un sistema dado su codigo
	//         Creado por:  Ing. Luis Anibal Lang           
	//   Fecha de Cracion:  20/05/2006							Fecha de Ultima Modificación: 20/05/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_sistemas".
				" WHERE codsis='".$as_codsis."'";
	   $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_sss_select_sistema ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_nomsis=$row["nomsis"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	     
	} // fin function uf_sss_select_sistema


	function uf_sss_select_usuario($as_codemp,$as_codusu,&$as_nomusu)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_sistema
	//	           Access:   public
	//  		Arguments:   
	//  			         as_codemp     // codigo de empresa
	//  			         as_codusu     // codigo de usuario
	//  			         as_nomusu     // nombre de usuario
	//	         Returns :  $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Funcion que obtiene el nombre y apellido de un usuario
	//         Creado por:  Ing. Luis Anibal Lang           
	//   Fecha de Cracion:  20/05/2006							Fecha de Ultima Modificación: 20/05/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" WHERE codemp='".$as_codemp."'".
				"   AND codusu='".$as_codusu."'";
	   $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_sss_select_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_nomusu=$row["nomusu"]." ".$row["apeusu"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	     
	} // fin function uf_sss_select_usuario

	function uf_sss_select_permisos_usuario($as_codemp,$as_codusu,$as_codsis,$ai_orden,$rs_data)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_permisos_usuario
	//	           Access:   public
	//  		Arguments:   
	//  			         as_codemp     // codigo de empresa
	//  			         as_codusu     // codigo de usuario
	//  			         as_codsis     // codigo de sistema
	//  			         ai_orden   // parametro por el cual se ordenara el reporte (sistema ó usuario)
	//	         Returns :  $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Funcion que obtiene el nombre y apellido de un usuario y el nombre de los sistemas
	//         Creado por:  Ing. Luis Anibal Lang           
	//   Fecha de Cracion:  10/06/2006							Fecha de Ultima Modificación: 10/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_orden="codusu";
		/*$ls_sql="SELECT sss_derechos_usuarios.codsis, sss_derechos_usuarios.codusu,".
				"       (SELECT nomsis FROM sss_sistemas".
				"         WHERE sss_derechos_usuarios.codsis=sss_sistemas.codsis) as nomsis,".
				"       (SELECT nomusu FROM sss_usuarios".
				"         WHERE sss_derechos_usuarios.codusu=sss_usuarios.codusu) as nomusu,".
				"       (SELECT apeusu FROM sss_usuarios".
				"         WHERE sss_derechos_usuarios.codusu=sss_usuarios.codusu) as apeusu".
				"   FROM sss_derechos_usuarios".
				"  WHERE sss_derechos_usuarios.codemp='".$as_codemp."'";*/
				
		//Agregado por Nelson Barraez para mostrar los permisos por usuario y/o por grupos
		if($as_codusu!="")
		{
			$ls_sql_aux=" AND sss_derechos_usuarios.codusu='".$as_codusu."'";
			$ls_sql_aux1=" AND sss_usuarios_en_grupos.codusu='".$as_codusu."'";
		}
		if($as_codsis!="")
		{
			$ls_sql_aux=$ls_sql_aux." AND sss_derechos_usuarios.codsis='".$as_codsis."'";
			$ls_sql_aux1=$ls_sql_aux1." AND sss_derechos_grupos.codsis='".$as_codsis."'";
		}
		
		
		$ls_sql="(SELECT sss_derechos_usuarios.codsis, sss_derechos_usuarios.codusu,
						   (SELECT nomsis FROM sss_sistemas WHERE sss_derechos_usuarios.codsis=sss_sistemas.codsis) as nomsis,
						   (SELECT nomusu FROM sss_usuarios WHERE sss_derechos_usuarios.codusu=sss_usuarios.codusu) as nomusu,
						   (SELECT apeusu FROM sss_usuarios	WHERE sss_derechos_usuarios.codusu=sss_usuarios.codusu) as apeusu,'U'
				   FROM sss_derechos_usuarios
				  WHERE sss_derechos_usuarios.codemp='".$as_codemp."' ".$ls_sql_aux." )
				  UNION
				 (SELECT sss_derechos_grupos.codsis, sss_usuarios_en_grupos.codusu,
						   (SELECT nomsis FROM sss_sistemas WHERE sss_derechos_grupos.codsis=sss_sistemas.codsis) as nomsis,
						   (SELECT nomusu FROM sss_usuarios WHERE sss_usuarios_en_grupos.codusu=sss_usuarios.codusu) as nomusu,
						   (SELECT apeusu FROM sss_usuarios	WHERE sss_usuarios_en_grupos.codusu=sss_usuarios.codusu) as apeusu,'G'
				   FROM sss_derechos_grupos,sss_usuarios_en_grupos
				  WHERE sss_derechos_grupos.codemp='".$as_codemp."' ".$ls_sql_aux1." AND sss_usuarios_en_grupos.codgru=sss_derechos_grupos.codgru)";
		if($ai_orden==1)
		{$ls_orden=" codsis ";}
		$ls_sql=$ls_sql." ORDER BY ". $ls_orden ."";

	    $rs_data=$this->io_sql->select($ls_sql);	   	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_sss_select_permisos_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;		
		}
		return $lb_valido; 
	     
	} // fin function uf_sss_select_permisos_usuario

	function uf_sss_select_dt_permisos_usuario($as_codemp,$as_codusu,$as_codsis)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_dt_permisos_usuario
	//	           Access:   public
	//  		Arguments:   
	//  			         as_codemp     // codigo de empresa
	//  			         as_codusu     // codigo de usuario
	//  			         as_codsis     // codigo de sistema
	//	         Returns :  $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Funcion que obtiene el nombre y apellido de un usuario y el nombre de los sistemas
	//         Creado por:  Ing. Luis Anibal Lang           
	//   Fecha de Cracion:  10/06/2006							Fecha de Ultima Modificación: 10/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="(SELECT sss_derechos_usuarios.codemp,sss_derechos_usuarios.codusu,sss_derechos_usuarios.codsis, ".
				"		sss_derechos_usuarios.nomven,sss_derechos_usuarios.visible, ".
				"		sss_derechos_usuarios.enabled,sss_derechos_usuarios.leer,sss_derechos_usuarios.incluir, ".
				"  		sss_derechos_usuarios.cambiar,sss_derechos_usuarios.eliminar,sss_derechos_usuarios.imprimir, ".
				"		sss_derechos_usuarios.administrativo,sss_derechos_usuarios.anular,sss_derechos_usuarios.ejecutar, ".
				"       (SELECT titven FROM sss_sistemas_ventanas ".
				"         WHERE sss_derechos_usuarios.nomven=sss_sistemas_ventanas.nomven ".
				"           AND sss_derechos_usuarios.codsis=sss_sistemas_ventanas.codsis) as titven,'' as grupo ".
				"  FROM sss_derechos_usuarios ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codusu='".$as_codusu."' ".
				"   AND codsis='".$as_codsis."' ".
				"  GROUP BY sss_derechos_usuarios.codemp,sss_derechos_usuarios.codusu,sss_derechos_usuarios.codsis, sss_derechos_usuarios.nomven,
							sss_derechos_usuarios.visible, sss_derechos_usuarios.enabled,sss_derechos_usuarios.leer,sss_derechos_usuarios.incluir, 
							sss_derechos_usuarios.cambiar,sss_derechos_usuarios.eliminar,sss_derechos_usuarios.imprimir, 
							sss_derechos_usuarios.administrativo,sss_derechos_usuarios.anular,sss_derechos_usuarios.ejecutar,
							titven,grupo ".
				" ORDER BY titven) ".
				" UNION ".
				" (SELECT sss_derechos_grupos.codemp,sss_usuarios_en_grupos.codusu,sss_derechos_grupos.codsis, ".
				"		sss_derechos_grupos.nomven,sss_derechos_grupos.visible, ".
				"		sss_derechos_grupos.enabled,sss_derechos_grupos.leer,sss_derechos_grupos.incluir, ".
				"  		sss_derechos_grupos.cambiar,sss_derechos_grupos.eliminar,sss_derechos_grupos.imprimir, ".
				"		sss_derechos_grupos.administrativo,sss_derechos_grupos.anular,sss_derechos_grupos.ejecutar, ".
				"       (SELECT titven FROM sss_sistemas_ventanas ".
				"         WHERE sss_derechos_grupos.nomven=sss_sistemas_ventanas.nomven ".
				"           AND sss_derechos_grupos.codsis=sss_sistemas_ventanas.codsis) as titven,nomgru as grupo ".
				"  FROM sss_derechos_grupos,sss_usuarios_en_grupos,sss_grupos ".
				" WHERE sss_derechos_grupos.codemp='".$as_codemp."' AND sss_usuarios_en_grupos.codusu='".$as_codusu."' ".
				"   AND sss_derechos_grupos.codsis='".$as_codsis."' AND sss_usuarios_en_grupos.codgru=sss_derechos_grupos.codgru ".
				"   AND sss_derechos_grupos.codgru=sss_grupos.codgru ".
				" ORDER BY titven) ";
				
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_sss_select_dt_permisos_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			//print $this->io_sql->message;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			//print_r($this->ds_detalle->data);
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	     
	} // fin function uf_sss_select_dt_permisos_usuario
	
	function uf_sss_select_traspasos($ad_fecdesde,$ad_fechasta,$as_bddestino)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_auditoria
	//	           Access:   public
	//  		Arguments:   ad_fecdesde    // fecha de inicio del periodo de busqueda
	//  			         ad_fechasta    // fecha de cierre del periodo de busqueda
	//  			         as_bddestno    // Base de Datos Destino
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Función que se encarga de realizar la busqueda  de las operaciones del sistema registradas en el modulo 
	//						de seguridad.
	//         Creado por:  Ing. Arnaldo Suárez          
	//   Fecha de Cracion:   07/08/2008							Fecha de Ultima Modificación:   20/05/2006 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlbd="";
		$ls_sql_where="";
		if(!empty($as_bddestino)){$ls_sqlbd="  AND bddestino ='".$as_bddestino."'";}


			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdesde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechasta);
			$ls_min=" 23:59:59";
		
		$ls_sql=" SELECT codres, codproc, codsis, fecha, bdorigen, bddestino, descripcion ".
  				" FROM sigesp_dt_proc_cons".
				" WHERE fecha >= '".$ld_auxdesde."'".
			            " AND fecha <='".$ld_auxhasta.$ls_min."'" .
				" AND codproc = 'SSSTUS' AND codsis = 'SSS' ".		
				$ls_sqlbd.
				" ORDER BY codres";			
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_sss_select_traspasos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} // fin function uf_sss_select_auditoria	
	
} //fin  class sigesp_siv_class_report
?>
