<?php

class sigesp_siv_class_reportbsf
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;
	var $dts_reporte;

	function sigesp_siv_class_reportbsf()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$this->gestor=$_SESSION["ls_gestor"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->ds_detcontable=new class_datastore();
		$this->dts_reporte=new class_datastore();
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////             Funciones de Reportes de Niveles de Existencias de Articulos             ////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_almacen($as_codemp,$as_codalm,$as_codart,$ai_orden)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_almacen
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_codalm    // codigo de almacen
		//  			       $as_codart    // codigo de articulo
		//  			       $ai_orden     // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta   0-> Por codigo de almacen 1-> Por nombre de almacen
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de los almacenes en los que existen
		//				       articulos por el codigo o por el nombre del almacen.  
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 02/02/2006							Fecha de Ultima Modificación: 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlalm="";
		$ls_sqlart="";
		if(!empty($as_codart))
		{$ls_sqlart=" AND codart='". $as_codart ."'";}
		if(!empty($as_codalm))
		{$ls_sqlalm=" AND codalm='". $as_codalm ."'";}
		if($ai_orden==0)
		{$ls_order="codalm";}
		else
		{$ls_order="nomfisalm";}
		$ls_sql="SELECT siv_articuloalmacen.codalm,".
				"       (SELECT nombre FROM sigesp_empresa".
				"         WHERE siv_articuloalmacen.codemp=sigesp_empresa.codemp) AS nombre,".
				"       (SELECT nomfisalm FROM siv_almacen".
				"         WHERE siv_articuloalmacen.codalm=siv_almacen.codalm) AS nomfisalm ".
				"  FROM siv_articuloalmacen".
				" WHERE codemp='". $as_codemp ."'".
				$ls_sqlalm.
				$ls_sqlart.
				" GROUP BY codemp,codalm".
				" ORDER BY ". $ls_order ."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_almacen

	function uf_select_articuloxalmacen($as_codemp,$as_codalm,$as_codart,$ai_ordenalm,$ai_ordenart)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_articuloxalmacen
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_codalm    // codigo de almacen
		//  			       $as_codart    // codigo de articulo
		//  			       $ai_ordenalm  // parametro por el cual vamos a ordenar los resultados
		//						   	            obtenidos en la consulta   0-> Por codigo de almacen 1-> Por nombre de almacen
		//  			       $ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								        obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de los articulos que estan en cada uno de los almacenes
		//				       ó en que almacenes esta determinado articulo en ambos casos con sus respectivas existencias y ordenados
		//			           por el codigo o por el nombre del almacen o articulo.  
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 02/02/2006							Fecha de Ultima Modificación: 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlart="";
		$ls_sqlalm="";
		if(!empty($as_codart))
		{$ls_sqlart=" AND siv_articuloalmacen.codart='". $as_codart ."'";}
		if(!empty($as_codalm))
		{$ls_sqlalm=" AND siv_articuloalmacen.codalm='". $as_codalm ."'";}
		if($ai_ordenalm==0)
		{$ls_orderalm="codalm";}
		else
		{$ls_orderalm="nomfisalm";}
		if($ai_ordenart==0)
		{$ls_orderart="codart";}
		else
		{$ls_orderart="denart";}
		$ls_sql="SELECT siv_articuloalmacen.*,siv_unidadmedida.unidad AS unidades,".
				"       (SELECT denart FROM siv_articulo".
				"         WHERE siv_articuloalmacen.codart=siv_articulo.codart) AS denart,".
				"       (SELECT nomfisalm FROM siv_almacen".
				"         WHERE siv_articuloalmacen.codalm=siv_almacen.codalm) AS nomfisalm ".
				"  FROM siv_articuloalmacen,siv_articulo,siv_unidadmedida".
				" WHERE siv_articuloalmacen.codemp='". $as_codemp ."'".
				"   AND siv_articuloalmacen.codart=siv_articulo.codart".
				"   AND siv_articulo.codunimed=siv_unidadmedida.codunimed".
				$ls_sqlart.
				$ls_sqlalm.
				" ORDER BY ". $ls_orderalm .", ". $ls_orderart ."";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
		  $this->io_sql->free_result($rs_data);
		}
	return $lb_valido; 
	} //fin  function uf_select_articuloxalmacen

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////       Funciones del Reporte de Movimientos de Articulos        ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_articulos($as_codemp,$as_codalm,$as_codart,$ad_desde,$ad_hasta,&$ai_total,$ai_ordenart)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_articulos
		//	           Access: public
		//  		Arguments: as_codemp    // codigo de empresa
		//  			       as_codalm    // codigo de almacen
		//  			       as_codart    // codigo de articulo
		//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			       ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//						         obtenidos en la consulta 0-> Por codigo de articulo 1-> Por denominación
		//	         Returns : lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de los articulos que han tenido
		//				       movimientos de inventario en el intervalo solicitado.  
		//         Creado por: Ing. Luis Anibal Lang  
		//     Modificado por: Ing. Yozelin Barragan        
		//   Fecha de Cracion: 03/02/2006					Fecha de Ultima Modificación: 01/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlalm="";
		$ls_sqlart="";
		$ls_sqlint="";
		if(!empty($as_codart))
		{
			$ls_sqlart=" AND codart='". $as_codart ."'";
		}
		if(!empty($as_codalm))
		{
			$ls_sqlalm=" AND codalm='". $as_codalm ."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=" AND fecmov >='". $ld_auxdesde ."'".
					   " AND fecmov <='". $ld_auxhasta ."'";
		}
		if($ai_ordenart==0)
		{
			$ls_order="codart";
		}
		else
		{
			$ls_order="denart";
		}
		$ls_sql="SELECT siv_dt_movimiento.codart,".
				"       (SELECT nombre FROM sigesp_empresa".
				"         WHERE siv_dt_movimiento.codemp=sigesp_empresa.codemp) as nombre,".
				"       (SELECT denart FROM siv_articulo".
				"         WHERE siv_dt_movimiento.codart=siv_articulo.codart) as denart, count(nummov) as total ".
				" FROM siv_dt_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				"   AND canart > 0".
				//"   AND codprodoc <> 'REV'".
				"   AND numdocori NOT IN (SELECT numdoc FROM siv_dt_movimiento".
				"                          WHERE codemp='". $as_codemp ."'".
				$ls_sqlart.
   				"                            AND canart > 0".
				"                            AND codprodoc ='REV')".
				$ls_sqlalm.
				$ls_sqlart.
				$ls_sqlint.
				" GROUP BY siv_dt_movimiento.codemp,siv_dt_movimiento.codart".
				" ORDER BY ". $ls_order ."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->reset_ds();
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_articulos

	function uf_select_movimientosxarticulos($as_codemp,$as_codalm,$as_codart,$ad_desde,$ad_hasta,&$ai_total,$ai_ordenart,$ai_ordenfec)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_movimientosxarticulos
		//	           Access: public
		//  		Arguments: as_codemp    // codigo de empresa
		//  			       as_codalm    // codigo de almacen
		//  			       as_codart    // codigo de articulo
		//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			       ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta 0-> Por codigo de articulo 1-> Por denominación
		//  			       ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de los movimientos de inventario de los articulos 
		//				       en el intervalo solicitado.  
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 03/02/2006							Fecha de Ultima Modificación: 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlalm="";
		$ls_sqlart="";
		$ls_sqlint="";
		if(!empty($as_codart))
		{
			$ls_sqlart=" AND codart='". $as_codart ."'";
		}
		if(!empty($as_codalm))
		{
			$ls_sqlalm=" AND codalm='". $as_codalm ."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=" AND fecmov >='". $ld_auxdesde ."' AND fecmov <='". $ld_auxhasta ."'";
		}
		if($ai_ordenart==0)
		{
			$ls_order="codart";
		}
		else
		{
			$ls_order="denart";
		}
		if($ai_ordenfec==0)
		{
			$ls_order="fecmov";
		}
		else
		{
			$ls_order="fecmov DESC";
		}
		$ls_sql="SELECT siv_dt_movimiento.*, siv_dt_movimiento.cosartaux as cosart ,".
				"       (SELECT nomfisalm FROM siv_almacen".
				"         WHERE siv_dt_movimiento.codalm=siv_almacen.codalm) AS nomfisalm ".
				"  FROM siv_dt_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				"   AND canart > 0".
				//"   AND codprodoc <> 'REV'".
				"   AND numdocori NOT IN (SELECT numdoc FROM siv_dt_movimiento ".
				"                          WHERE codemp='". $as_codemp ."'".
   				"                            AND canart > 0".
			    "                            AND codart='". $as_codart ."'".
				"                            AND codprodoc ='REV')".
				$ls_sqlalm.
				$ls_sqlart.
				$ls_sqlint.
				" ORDER BY ". $ls_order ."";
			//print $ls_sql."<br>";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_movimientosxarticulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->reset_ds();
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_movimientosxarticulos
 
 	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////         Funciones de Reporte de Articulos por Tipo            ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_tipos($as_coddesde,$as_codhasta)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_tipos
		//	           Access: public
		//  		Arguments: as_coddesde  // codigo de tipo de articulo para inicio de la busqueda
		//  			       as_codhasta  // codigo de tipo de articulo para fin de la busqueda
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de los tipos de articulos que existen
		//				       en un rango indicado, ordenados por el codigo de tipo de articulo
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 20/02/2006							Fecha de Ultima Modificación:   20/02/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" WHERE codtipart >='". $as_coddesde ."' AND codtipart <='". $as_codhasta ."'";
		}
		$ls_sql="SELECT siv_tipoarticulo.*,".
				" (SELECT count(codart) FROM siv_articulo".
				"   WHERE siv_tipoarticulo.codtipart=siv_articulo.codtipart)total".
				" FROM siv_tipoarticulo".
				$ls_sqlint.
				" GROUP BY codtipart";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_tipos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} //fin  function uf_select_tipos

	function uf_select_articuloxtipo($as_codtipart,$as_coddesde,$as_codhasta,$ai_ordenart)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_articuloxtipo
		//	           Access: public
		//  		Arguments: ls_codtipart   // codigo de tipo de articulo
		//  			       as_coddesde    // codigo de tipo de articulo para inicio de la busqueda
		//  			       as_codhasta    // codigo de tipo de articulo para fin de la busqueda
		//  			       ai_ordenart    // parametro por el cual vamos a ordenar los resultados
		//								           obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de los articulos que pertenecen a cada uno de los tipos
		//				       de articulos dentro de un intervalo de codigos indicados.
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 20/02/2006							Fecha de Ultima Modificación: 20/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" WHERE codtipart >='". $as_coddesde ."' AND codtipart <='". $as_codhasta ."'";
		}
		if($ai_ordenart==0)
		{
			$ls_orderart="codart";
		}
		else
		{
			$ls_orderart="denart";
		}
		$ls_sql="SELECT siv_articulo.*,".
				 " (SELECT denunimed FROM siv_unidadmedida".
				 "   WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) denunimed,".
				 " (SELECT unidad FROM siv_unidadmedida".
				 "   WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) unidades".
				 " FROM siv_articulo".
				 " WHERE codtipart='". $as_codtipart ."' ".
				// $ls_sqlint.
				 " ORDER BY ". $ls_orderart ." ";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_articuloxtipo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_articuloxalmacen

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////       Funciones del Reporte de Ordenes de Despachos           ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_despachos($as_codemp,$as_numorddes,$ad_desde,$ad_hasta,$ai_ordenfec)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_despachos
		//	           Access: public
		//  		Arguments: as_codemp    // codigo de empresa
		//  			       as_numorddes // numero de orden de despacho
		//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			       ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de las ordedes de despacho emitidas 
		//				       en un rango de fecha indicado, ordenados por fecha.
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 20/02/2006							Fecha de Ultima Modificación:   20/02/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlord="";		
		if(!empty($as_numorddes))
		{
			$ls_sqlord=" AND numorddes='". $as_numorddes ."'";
		}
		
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=" AND fecdes >='". $ld_auxdesde ."'".
					   " AND fecdes <='". $ld_auxhasta ."'";
		}
		if($ai_ordenfec==0)
		{
			$ls_order="fecdes";
		}
		else
		{
			$ls_order="fecdes DESC";
		}
		$ls_sql="SELECT siv_despacho.numorddes,siv_despacho.numsol,siv_despacho.coduniadm,siv_despacho.obsdes,siv_despacho.fecdes,".
				"       (SELECT nombre FROM sigesp_empresa".
				"         WHERE siv_despacho.codemp=sigesp_empresa.codemp) AS nombre,".
				"       (SELECT count(codart) FROM siv_dt_despacho".
				"         WHERE siv_dt_despacho.numorddes=siv_despacho.numorddes) AS total,".
				"       (SELECT spg_unidadadministrativa.denuniadm FROM spg_unidadadministrativa".
				"         WHERE spg_unidadadministrativa.coduniadm=siv_despacho.coduniadm) AS denuniadm".
				"  FROM siv_despacho".
				" WHERE codemp='".$as_codemp."' ".
				"   AND estrevdes = 1".
				$ls_sqlint.
				$ls_sqlord.
				" GROUP BY siv_despacho.codemp,siv_despacho.numorddes,siv_despacho.numsol,siv_despacho.coduniadm,siv_despacho.obsdes,siv_despacho.fecdes".
				" ORDER BY ". $ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_despachos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} //fin  function uf_select_tipos

	function uf_select_dt_despacho($as_codemp,$as_numorddes,$ad_desde,$ad_hasta,$ai_ordenfec)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_despacho
		//	           Access: public
		//  		Arguments: ls_codtipart   // codigo de tipo de articulo
		//  			       ad_desde       // codigo de tipo de articulo para inicio de la busqueda
		//  			       ad_hasta       // codigo de tipo de articulo para fin de la busqueda
		//  			       ai_ordenfec    // parametro por el cual vamos a ordenar los resultados
		//							           obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de los articulos que pertenecen a cada uno de los tipos
		//				       de articulos dentro de un intervalo de codigos indicados.
		//         Creado por: Ing. Luis Anibal Lang           
		//     Modificado por: Ing. Yozelin Barragan        
		//   Fecha de Cracion: 20/02/2006					Fecha de Ultima Modificación: 03/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT siv_dt_despacho.*, siv_dt_despacho.preuniartaux as preuniart, ".
		         "       siv_dt_despacho.monsubartaux as monsubart, siv_dt_despacho.montotartaux as montotart, ".
				 "       (SELECT denart FROM siv_articulo".
				 "         WHERE siv_articulo.codart=siv_dt_despacho.codart) AS denart,".
				 "       (SELECT nomfisalm FROM siv_almacen".
				 "         WHERE siv_almacen.codalm=siv_dt_despacho.codalm) AS nomfisalm".
				 "  FROM siv_dt_despacho".
				 " WHERE codemp='". $as_codemp ."' ".
				 "   AND numorddes='". $as_numorddes ."'".
				 " ORDER BY siv_dt_despacho.orden ";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_dt_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_articuloxalmacen

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////         Funciones del Reporte de Entradas de Suministros a Almacén           ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_recepcion($as_codemp,$as_numconrec,$ad_desde,$ad_hasta,$ai_ordenfec)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_recepcion
		//	           Access: public
		//  		Arguments: as_codemp    // codigo de empresa
		//  			       as_numconrec // numero consecutivo de recepcion
		//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			       ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de las entradas desuministros a los almacenes emitidas 
		//				       en un rango de fecha indicado, ordenados por fecha.
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 20/02/2006							Fecha de Ultima Modificación:   05/05/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlnum="";
		if(!empty($as_numconrec))
		{
			$ls_sqlnum=" AND numconrec ='".$as_numconrec."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=" AND fecrec >='". $ld_auxdesde ."'".
					   " AND fecrec <='". $ld_auxhasta ."'";
		}
		if($ai_ordenfec==0)
		{
			$ls_order="fecrec";
		}
		else
		{
			$ls_order="fecrec DESC";
		}
		$ls_sql="SELECT siv_recepcion.numconrec,siv_recepcion.numordcom,siv_recepcion.cod_pro,".
				"       siv_recepcion.codalm,siv_recepcion.obsrec,siv_recepcion.fecrec,".
				"       (SELECT nombre FROM sigesp_empresa".
				"         WHERE siv_recepcion.codemp=sigesp_empresa.codemp) AS nombre,".
				"       (SELECT nomfisalm FROM siv_almacen".
				"         WHERE siv_recepcion.codalm=siv_almacen.codalm) AS nomfisalm,".
				"       (SELECT nompro FROM rpc_proveedor".
				"         WHERE siv_recepcion.cod_pro=rpc_proveedor.cod_pro) AS nompro".
				"  FROM siv_recepcion".
				" WHERE codemp='".$as_codemp."' ".
				"   AND estrevrec=1 ".
				$ls_sqlnum.
				$ls_sqlint.
				" GROUP BY siv_recepcion.codemp,siv_recepcion.numconrec,siv_recepcion.numordcom,siv_recepcion.cod_pro,".
				"          siv_recepcion.codalm,siv_recepcion.obsrec,siv_recepcion.fecrec".
				" ORDER BY ". $ls_order ."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} 

	function uf_select_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_recepcion
		//	           Access: public
		//  		Arguments: as_codemp     // codigo de empresa
		//  			       as_numordcom  // numero de orden de compra / Factura
		//  			       as_numconrec  // numero consecutivo de recepcion
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de los articulos que pertenecen a una entrada de suministros
		//				       a almacen referente al maestro indicado.
		//         Creado por: Ing. Luis Anibal Lang           
		//     Modificado por: Ing. Yozelin Barragan        
		//   Fecha de Cracion: 22/02/2006					Fecha de Ultima Modificación: 03/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT siv_dt_recepcion.*,siv_unidadmedida.unidad AS unidades, siv_dt_recepcion.preuniartaux as preuniart, ".
		         "      siv_dt_recepcion.monsubartaux as monsubart, siv_dt_recepcion.montotartaux as montotart, ".
				 "     (SELECT siv_articulo.denart FROM siv_articulo ".
				 "       WHERE siv_articulo.codart=siv_dt_recepcion.codart) AS denart ".
				 "  FROM siv_dt_recepcion,siv_articulo,siv_unidadmedida ".
				 " WHERE siv_dt_recepcion.codemp='".$as_codemp."' ".
				 "   AND siv_dt_recepcion.numordcom='".$as_numordcom."' ".
				 "   AND siv_dt_recepcion.numconrec='".$as_numconrec."' ".
				 "   AND siv_articulo.codart=siv_dt_recepcion.codart ".
				 "   AND siv_unidadmedida.codunimed=siv_articulo.codunimed ".
				 " ORDER BY siv_dt_recepcion.orden ";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} 

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////           Funciones del Reporte de Transferencia entre Almacenes             ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_transferencia($as_codemp,$as_numtra,$ad_desde,$ad_hasta,$ai_ordenfec)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_transferencia
		//	           Access: public
		//  		Arguments: as_codemp    // codigo de empresa
		//  			       as_numtra    // numero de transferencia
		//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			       ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//						               obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda  de las entradas desuministros a los almacenes emitidas 
		//				       en un rango de fecha indicado, ordenados por fecha.
		//         Creado por: Ing. Luis Anibal Lang           
		//     Modificado por: Ing. Yozelin Barragan        
		//   Fecha de Cracion: 20/02/2006					Fecha de Ultima Modificación: 03/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlnum="";
		if(!empty($as_numtra))
		{
			$ls_sqlnum=" AND numtra ='". $as_numtra ."'";
		}
		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint=" AND fecemi >='". $ld_auxdesde ."' AND fecemi <='". $ld_auxhasta ."'";
		}
		if($ai_ordenfec==0)
		{
			$ls_order="fecemi";
		}
		else
		{
			$ls_order="fecemi DESC";
		}
		$ls_sql="SELECT siv_transferencia.numtra,siv_transferencia.codalmori,siv_transferencia.codalmdes,".
				"       siv_transferencia.obstra,siv_transferencia.fecemi,".
				"      (SELECT nombre FROM sigesp_empresa".
				"        WHERE siv_transferencia.codemp=sigesp_empresa.codemp) AS nombre,".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_transferencia.codalmori=siv_almacen.codalm) AS nomfisalmori,".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_transferencia.codalmdes=siv_almacen.codalm) AS nomfisalmdes".
				"  FROM siv_transferencia".
				" WHERE codemp='".$as_codemp."' ".
				$ls_sqlnum.
				$ls_sqlint.
				" GROUP BY siv_transferencia.codemp,siv_transferencia.numtra,siv_transferencia.codalmori,siv_transferencia.codalmdes,".
				"          siv_transferencia.obstra,siv_transferencia.fecemi".
				" ORDER BY ". $ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} //fin  function uf_select_transferencia

	function uf_select_dt_transferencia($as_codemp,$as_numtra)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_transferencia
		//	           Access: public
		//  		Arguments: as_codemp     // codigo de empresa
		//  			       as_numtrea    // numero de transferencia
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de los articulos que pertenecen a una transferencia 
		//				       entre almacenes referente al maestro indicado.
		//         Creado por: Ing. Luis Anibal Lang           
		//     Modificado por: Ing. Yozelin Barragan        
		//   Fecha de Cracion: 22/02/2006					Fecha de Ultima Modificación: 03/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT siv_dt_transferencia.*, siv_articulo.codunimed, siv_unidadmedida.unidad AS unidades, ".
		        "       siv_dt_transferencia.cosuniaux AS cosuni, siv_dt_transferencia.costotaux AS costot,  ".
				"      (SELECT denart FROM siv_articulo ".
				"        WHERE siv_dt_transferencia.codart=siv_articulo.codart) AS denart ".
				"  FROM siv_dt_transferencia,siv_articulo,siv_unidadmedida ".
				" WHERE siv_dt_transferencia.codemp='".$as_codemp."' ".
				"   AND siv_dt_transferencia.numtra='".$as_numtra."' ".
				"   AND siv_dt_transferencia.codart=siv_articulo.codart ".
				"   AND siv_articulo.codunimed=siv_unidadmedida.codunimed";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_transferencia

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones del Reporte de Resumen de Inventario                 ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_inventario($as_codemp,$as_coddesde,$as_codhasta,$ad_desde,$ad_hasta,$ai_ordenart)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function: uf_select_inventario
	//	           Access: public
	//  		Arguments: as_codemp    // codigo de empresa
	//  			       as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
	//  			       as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
	//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
	//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
	//  			       ai_ordenart  // parametro por el cual vamos a ordenar los resultados
	//								       obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description: Función que se encarga de realizar la busqueda  de los articulo que estan registrados en la tabla 
	//				       siv_articulos ordenando los resultados por codigo de articulo o por denominacion segun sea lo indicado
	//					   ademas de buscar los otros datos necesarios para generar el reporte.
	//         Creado por: Ing. Luis Anibal Lang           
	//     Modificado por: Ing. Yozelin Barragan        
	//   Fecha de Cracion: 23/02/2006					Fecha de Ultima Modificación: 03/09/2007
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlintfec="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND codart >='". $as_coddesde ."' AND codart <='". $as_codhasta ."'";
		}

		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlintfec=" AND fecmov >='". $ld_auxdesde ."' AND fecmov <='". $ld_auxhasta ."'";
		}
		if($ai_ordenart==0)
		{
			$ls_order="codart";
		}
		else
		{
			$ls_order="denart";
		}
		$ls_sql=" SELECT siv_articulo.*, siv_articulo.ultcosartaux AS ultcosart, siv_articulo.cosproartaux AS cosproart, ".
				"        (SELECT count(codart) FROM siv_articulo) AS total, ".
				"        (SELECT denunimed FROM siv_unidadmedida ".
				"          WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) AS denunimed, ".
				"        (SELECT unidad FROM siv_unidadmedida ".
				"          WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) AS unidad, ".
				"        (SELECT count(codart) FROM siv_dt_movimiento ".
				"          WHERE siv_dt_movimiento.codart=siv_articulo.codart ".
				"            AND siv_dt_movimiento.opeinv='ENT' ".
				"            AND siv_dt_movimiento.codprodoc<>'REV' ".
				"            AND siv_dt_movimiento.canart > 0 ". $ls_sqlintfec ." ".
				"		     AND numdocori NOT IN (SELECT numdoc FROM siv_dt_movimiento ".
				"                                   WHERE codemp='". $as_codemp ."' ".
   				"                                     AND canart > 0 ".
				"                                     AND codprodoc ='REV')) AS entradas, ".
				"        (SELECT count(codart) FROM siv_dt_movimiento ".
				"          WHERE siv_dt_movimiento.codart=siv_articulo.codart ".
				"            AND siv_dt_movimiento.opeinv='SAL' ". $ls_sqlintfec ." ".
				"            AND siv_dt_movimiento.codprodoc<>'REV' ".
				"		     AND numdocori NOT IN (SELECT numdoc FROM siv_dt_movimiento ".
				"                                   WHERE codemp='". $as_codemp ."' ".
   				"                                     AND canart > 0 ".
				"                                     AND codprodoc ='REV')) AS salidas ".
				" FROM siv_articulo ".
				" WHERE codemp='".$as_codemp."' ".$ls_sqlint.
				" ORDER BY ". $ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_inventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} //fin  function uf_select_inventario

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones del Reporte de Almacenes                 ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_almacenes($as_codemp,$as_coddesde,$as_codhasta,$ai_ordenart)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_select_almacenes
	//	           Access:   public
	//  		Arguments:  
	//						 as_codemp    // codigo de empresa
	//  			         as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
	//  			         as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
	//  			         ai_ordenart  // parametro por el cual vamos a ordenar los resultados
	//								         obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Función que se encarga de realizar la busqueda  de los articulo que estan registrados en la tabla 
	//				        siv_articulos ordenando los resultados por codigo de articulo o por denominacion segun sea lo indicado
	//						ademas de buscar los otros datos necesarios para generar el reporte.
	//         Creado por:  Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   23/02/2006							Fecha de Ultima Modificación:   23/02/2006 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND codalm >='". $as_coddesde ."' AND codalm <='". $as_codhasta ."'";
		}
		if($ai_ordenart==0)
		{
			$ls_order="codalm";
		}
		else
		{
			$ls_order="nomfisalm";
		}
		$ls_sql=" SELECT siv_almacen.*,".
				"        (SELECT count(codalm) FROM siv_almacen) AS total ".
				"  FROM siv_almacen".
				" WHERE codemp='".$as_codemp."' ".
				$ls_sqlint.
				" ORDER BY ". $ls_order."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_almacenes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} //fin  function uf_select_transferencia
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones de la Toma de Inventario                             ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_toma($as_codemp,$as_numtom,$ad_desde,$ad_hasta)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_toma
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numtom     // numero de toma de inventario
		//  			       $ad_desde      // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta      // fecha de cierre del periodo de busqueda
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de  una toma de inventario
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 31/02/2006							Fecha de Ultima Modificación: 15/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT siv_toma.*,".
				"       (SELECT nomfisalm FROM siv_almacen ".
				"         WHERE siv_toma.codalm=siv_almacen.codalm) AS nomfisalm".
				" FROM siv_toma,siv_almacen".
				" WHERE siv_toma.codemp='". $as_codemp ."'";
		if($as_numtom!="")
		{
			$ls_sql=$ls_sql." AND siv_toma.numtom='". $as_numtom ."'";

		}
		if(($ad_desde!="")&&($ad_hasta!=""))
		{
			$ld_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_fechas=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sql=$ls_sql." AND siv_toma.fectom<='".$ld_fechas."'".
							" AND siv_toma.fectom>='".$ld_fecdes."'";
		}
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_toma ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	} //fin  function uf_select_toma
	
	function uf_select_dt_toma($as_codemp,$as_numtom)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function: uf_select_dt_toma
	//	           Access: public
	//  		Arguments: $as_codemp     // codigo de empresa
	//  			       $as_numtom     // numero de toma de inventario
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description: Función que se encarga de realizar la busqueda de los articulos que pertenecen a una toma de inventario
	//				       referente al maestro indicado.
	//         Creado por: Ing. Luis Anibal Lang           
	//   Fecha de Cracion: 31/02/2006							Fecha de Ultima Modificación: 31/02/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT siv_dt_toma.*,".
				"       (SELECT denart FROM siv_articulo ".
				"         WHERE siv_dt_toma.codart=siv_articulo.codart) AS denart".
				"  FROM siv_dt_toma,siv_articulo".
				" WHERE siv_dt_toma.codemp='". $as_codemp ."'".
				"   AND siv_dt_toma.numtom='". $as_numtom ."'".
				"   AND siv_dt_toma.codart=siv_articulo.codart";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_dt_toma ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_toma
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////              Funciones de la valoracion de Inventario                        ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_articulosmovimientos($as_codemp,$as_coddesde,$as_codhasta,$ad_desde,$ad_hasta,$ai_ordenart)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function: uf_select_articulosmovimientos
	//	           Access: public
	//  		Arguments: as_codemp    // codigo de empresa
	//  			       as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
	//  			       as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
	//  			       ad_desde     // fecha de inicio del intervalo de dias para la busqueda
	//  			       ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
	//  			       ai_ordenart  // parametro por el cual vamos a ordenar los resultados
	//								         obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description: Función que se encarga de realizar la busqueda de los articulos que han entrado al inventario 
	//					   en un periodo de tiempo
	//					   ademas de buscar los otros datos necesarios para generar el reporte.
	//         Creado por: Ing. Luis Anibal Lang           
	//     Modificado por: Ing. Yozelin Barragan        
	//   Fecha de Cracion: 23/02/2006					Fecha de Ultima Modificación: 03/09/2007
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlintfec="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND codart >='". $as_coddesde ."' AND codart <='". $as_codhasta ."'";
		}

		if((!empty($ad_desde))&&(!empty($ad_hasta)))
		{
			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			//$ls_sqlintfec=" AND fecmov >='". $ld_auxdesde ."' AND fecmov <='". $ld_auxhasta ."'";
		}
		if($ai_ordenart==0)
		{
			$ls_order="codart";
		}
		else
		{
			$ls_order="denart";
		}
		switch ($this->gestor)
		{
			case("MYSQL"):
				$ls_sql="SELECT codart FROM siv_dt_movimiento ".
						" WHERE codemp ='".$as_codemp."' ".
						"   AND fecmov >='".$ld_auxdesde."' ".
						"   AND fecmov <='".$ld_auxhasta."' ".
						"   AND CONCAT(promov,numdocori) NOT IN ".
						"       (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento ".
						"         WHERE opeinv ='REV')".$ls_sqlint.
						"  GROUP BY codart ";
			break;

			case("POSTGRE"):
				$ls_sql="SELECT codart FROM siv_dt_movimiento ".
						" WHERE codemp ='". $as_codemp ."' ".
						"   AND fecmov >='". $ld_auxdesde ."' ".
						"   AND fecmov <='". $ld_auxhasta ."' ".
						"   AND (promov||numdocori) NOT IN ".
						"       (SELECT (promov || numdocori) FROM siv_dt_movimiento ".
						"         WHERE opeinv ='REV')".$ls_sqlint.
						"  GROUP BY codart ";
			break;
		}
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_articulosmovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	} //fin  function uf_select_articulosmovimientos

	function uf_select_promedio($as_codemp,$as_codart,$ad_desde,$ad_hasta,&$li_cosprom)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_promedio
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codart     // codigo de articulo
		//  			       $ad_desde      // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta      // fecha de cierre del periodo de busqueda
		//  			       $li_cosprom    // costo promedio del articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de calcular los costos promedios de los articulos
		//					   en un periodo de tiempo
		//         Creado por: Ing. Luis Anibal Lang           
		//     Modificado por: Ing. Yozelin Barragan        
		//   Fecha de Cracion: 23/02/2006					Fecha de Ultima Modificación: 03/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ld_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_desde);
		$ld_fechas=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
		switch ($this->gestor)
		{
			case("MYSQL"):
				$ls_sql="SELECT siv_dt_movimiento.*, siv_dt_movimiento.cosartaux AS cosart, ".
						"       (SELECT denart FROM siv_articulo ".
						"         WHERE codemp='".$as_codemp."' ".
						"           AND codart='".$as_codart."') AS denart, ".
						"       (SELECT exiart FROM siv_articulo ".
						"         WHERE codemp='".$as_codemp."' ".
						"           AND codart='".$as_codart."') AS exiart, ".
						"       (SELECT denunimed FROM siv_unidadmedida,siv_articulo ".
						"         WHERE siv_articulo.codunimed=siv_unidadmedida.codunimed ".
						"           AND siv_articulo.codart=siv_dt_movimiento.codart) AS denunimed, ".				
						"       (SELECT cosartaux  FROM siv_dt_movimiento ".
						"         WHERE codemp='".$as_codemp."'  ".
						"           AND fecmov>='".$ld_fecdes."' ".
						"           AND fecmov<='".$ld_fechas."' ".
						"           AND codart='".$as_codart."'  ".
						"           AND opeinv='ENT' ".
						"           AND (codprodoc='ORD' OR codprodoc='FAC') ".
						"           AND CONCAT(promov,numdocori) NOT IN ".
						"               (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento ".
						"                 WHERE opeinv ='REV') ".
						"                 ORDER BY fecmov DESC, nummov DESC LIMIT 1) as ultimo ".				
						"  FROM siv_dt_movimiento ".
						" WHERE codemp='".$as_codemp."' ".
						"   AND fecmov>='".$ld_fecdes."' ".
						"   AND fecmov<='".$ld_fechas."' ".
						"   AND codart='".$as_codart."' ".
						"   AND opeinv='ENT' ".
						"   AND (codprodoc='ORD' OR codprodoc='FAC') ".
						"   AND CONCAT(promov,numdocori) NOT IN ".
						"       (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento ".
						"         WHERE opeinv ='REV')";
			break;

			case("POSTGRE"):
				$ls_sql="SELECT siv_dt_movimiento.*, siv_dt_movimiento.cosartaux AS cosart, ".
						"       (SELECT denart FROM siv_articulo ".
						"         WHERE codemp='".$as_codemp."' ".
						"           AND codart='".$as_codart."') AS denart, ".
						"       (SELECT exiart FROM siv_articulo ".
						"         WHERE codemp='".$as_codemp."' ".
						"           AND codart='".$as_codart."') AS exiart, ".
						"       (SELECT denunimed FROM siv_unidadmedida,siv_articulo ".
						"         WHERE siv_articulo.codunimed=siv_unidadmedida.codunimed ".
						"           AND siv_articulo.codart=siv_dt_movimiento.codart) AS denunimed, ".				
						"       (SELECT cosartaux  FROM siv_dt_movimiento ".
						"         WHERE codemp='".$as_codemp."' ".
						"           AND fecmov>='".$ld_fecdes."' ".
						"           AND fecmov<='".$ld_fechas."' ".
						"           AND codart='".$as_codart."' ".
						"           AND opeinv='ENT' ".
						"           AND (codprodoc='ORD' OR codprodoc='FAC') ".
						"           AND (promov || numdocori) NOT IN ".
						"               (SELECT (promov || numdocori) FROM siv_dt_movimiento ".
						"                 WHERE opeinv ='REV') ".
						"                 ORDER BY fecmov DESC, nummov DESC LIMIT 1) as ultimo ".				
						"  FROM siv_dt_movimiento ".
						" WHERE codemp='".$as_codemp."' ".
						"   AND fecmov>='".$ld_fecdes."' ".
						"   AND fecmov<='".$ld_fechas."' ".
						"   AND codart='".$as_codart."' ".
						"   AND opeinv='ENT' ".
						"   AND (codprodoc='ORD' OR codprodoc='FAC') ".
						"   AND (promov || numdocori) NOT IN ".
						"       (SELECT (promov || numdocori) FROM siv_dt_movimiento ".
						"         WHERE opeinv ='REV')";
			break;
		}
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_promedio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$li_cantotart=0;
				$li_mulcostot=0;	
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_canart= $row["canart"];
					$li_cosart=	$row["cosart"];
					$li_mulcos= ($li_canart * $li_cosart);
					$li_cantotart= $li_cantotart + $li_canart;
					$li_mulcostot= $li_mulcostot + $li_mulcos;
				}
				$li_cosprom=($li_mulcostot/$li_cantotart);
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_promedio

	function uf_siv_load_dt_contable($as_codemp,$as_cmpmov,$ad_feccmp)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_siv_load_dt_contable
	//	           Access:   public
	//  		Arguments:   as_codemp  // codigo de empresa
	//  			         as_cmpmov  // comprobante de movimiento
	//  			         as_codcau  // codigo de causa de movimiento
	//  			         ad_feccmp  // fecha del comprobante 
	//	         Returns :   Retorna un Booleano
	//    	 Description :   Función que obtiene los detalles contables de un movimiento
	//         Creado por:   Ing. Luis Anibal Lang           
	//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificación: 09/06/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT siv_dt_scg.*,siv_articulo.codart,".
				 "      (SELECT denart FROM siv_articulo ".
				 "        WHERE siv_articulo.codart=siv_dt_scg.codart) AS denart".
				 "  FROM siv_dt_scg,siv_articulo".
				 " WHERE siv_dt_scg.codart=siv_articulo.codart".
				 "   AND siv_dt_scg.codemp='". $as_codemp ."'".
				 "   AND siv_dt_scg.codcmp='". $as_cmpmov ."'".
				 "   AND siv_dt_scg.feccmp='". $ad_feccmp ."'".
				 " ORDER BY denart,debhab";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_siv_load_dt_contable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detcontable->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// fin function uf_siv_load_dt_contable
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////              Funciones del reporte de Articulos a Solicitar            ///////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_articulossolicitar($as_codemp,$as_coddesde,$as_codhasta,$ai_ordenart)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function: uf_select_articulosmovimientos
	//	           Access: public
	//  		Arguments: $as_codemp    // codigo de empresa
	//  			       $as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
	//  			       $as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
	//  			       $ai_ordenart  // parametro por el cual vamos a ordenar los resultados
	//						                obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description: Función que se encarga de realizar la busqueda de los articulos que estan por debajo del punto de reoeden
	//         Creado por: Ing. Luis Anibal Lang           
	//   Fecha de Cracion: 12/09/2006							Fecha de Ultima Modificación:   
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND codart >='". $as_coddesde ."' AND codart <='". $as_codhasta ."'";
		}

		if($ai_ordenart==0)
		{
			$ls_order="codart";
		}
		else
		{
			$ls_order="denart";
		}
		$ls_sql="SELECT codart,denart,exiart,minart,reoart".
				"  FROM siv_articulo".
				" WHERE codemp ='". $as_codemp ."'".
				$ls_sqlint.
				" ORDER BY ". $ls_order ."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_articulosmovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_articulosmovimientos

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////             Funciones del  Listado de Articulos Parametrizado          ///////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_listadoarticulos($as_codemp,$as_coddesde,$as_codhasta,$ai_orden,$as_codalm,$as_codtipart,$as_codsigecof)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_listadoarticulos
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
		//  			       $as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
		//  			       $ai_orden     // parametro por el cual vamos a ordenar los resultados
		//  			       $as_codalm    // codigo de almacen
		//  			       $as_codtipart // codigo de tipo de articulo
		//  			       $as_codsigecof// codigo de sigecof
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de los articulos que estan por debajo del punto de reoeden
		//         Creado por: Ing. Luis Anibal Lang     
		//     Modificado Por: Ing. Yozelin Barragan   
		//   Fecha de Cracion: 12/09/2006							Fecha de Ultima Modificación: 11/07/2007   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		if((!empty($as_coddesde))&&(!empty($as_codhasta)))
		{
			$ls_sqlint=" AND siv_articulo.codart >='". $as_coddesde ."'".
					   " AND siv_articulo.codart <='". $as_codhasta ."'";
		}
		if(!empty($as_codalm))
		{
			$ls_sqlint=$ls_sqlint." AND siv_articuloalmacen.codalm='". $as_codalm ."'";
		}
		if(!empty($as_codtipart))
		{
			$ls_sqlint=$ls_sqlint." AND siv_articulo.codtipart='". $as_codtipart ."'";
		}
		if(!empty($as_codsigecof))
		{
			$ls_sqlint=$ls_sqlint." AND siv_articulo.codcatsig='". $as_codsigecof ."'";
		}
		switch ($ai_orden)
		{
			case 0:
				$ls_order="siv_articulo.codart";
			break;
			case 1:
				$ls_order="siv_articuloalmacen.codalm";
			break;
			case 2:
				$ls_order="siv_articulo.codtipart";
			break;
			case 3:
				$ls_order="siv_articulo.denart";
			break;
			case 4:
				$ls_order="siv_articulo.codcatsig";
			break;
		}


		$ls_sql="SELECT siv_articulo.codart,siv_articulo.denart,siv_articulo.codtipart,siv_articuloalmacen.codalm,siv_articulo.codcatsig,".
				" (SELECT dentipart FROM siv_tipoarticulo WHERE siv_tipoarticulo.codtipart=siv_articulo.codtipart) as dentipart,".
				" (SELECT nomfisalm FROM siv_almacen WHERE siv_articuloalmacen.codalm=siv_almacen.codalm) as nomfisalm".
				" FROM siv_articulo,siv_articuloalmacen".
				" WHERE siv_articulo.codemp ='". $as_codemp ."'".
				" AND siv_articuloalmacen.codart=siv_articulo.codart".
				$ls_sqlint.
				" ORDER BY ". $ls_order ."";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_articulosmovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_listadoarticulos

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////              Funciones del reporte de Cierre de Ordenes de Compra            ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_cierreordenes($as_codemp,$ad_desde,$ad_hasta,$ai_orden)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_cierreordenes
		//	           Access: public
		//  		Arguments: $as_codemp // codigo de empresa
		//  			       $ad_desde  // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta  // fecha de cierre del periodo de busqueda
		//  			       $ai_orden  // parametro por el cual vamos a ordenar los resultados
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar los datos asociados a un cierre de ordendes de compra
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ld_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_desde);
		$ld_fechas=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
		$ls_sql="SELECT soc_ordencompra.numordcom,soc_ordencompra.cod_pro,soc_ordencompra.fecordcom,".
				"       (SELECT rpc_proveedor.nompro FROM rpc_proveedor".
				"         WHERE soc_ordencompra.cod_pro=rpc_proveedor.cod_pro) as nompro".
				"  FROM soc_ordencompra".
				" WHERE soc_ordencompra.codemp='". $as_codemp ."'".
				"   AND (soc_ordencompra.estcondat='B' OR soc_ordencompra.estcondat='-')".
				"   AND soc_ordencompra.estpenalm=1";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_cierreordenes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_feccierre="";
				$ls_numordcom= $row["numordcom"];
				$ls_codpro=    $row["cod_pro"];
				$ld_fecordcom= $row["fecordcom"];
				$ls_nompro=    $row["nompro"];
				$lb_valido=$this->uf_select_fechacierrecmp($as_codemp,$ls_numordcom,$ld_fecdes,$ld_fechas,$ai_orden,$ad_feccierre);
				if($ad_feccierre=="")
				{
					$lb_valido=$this->uf_select_fechacierrerec($as_codemp,$ls_numordcom,$ls_codpro,$ld_fecdes,$ld_fechas,
															   $ai_orden,$ad_feccierre);
					if($lb_valido)
					{$li_parcial=0;}
				}
				else
				{$li_parcial=1;}
				if($lb_valido)
				{
					$this->ds->insertRow("numordcom",$ls_numordcom);
					$this->ds->insertRow("fecordcom",$ld_fecordcom);
					$this->ds->insertRow("nompro",$ls_nompro);
					$this->ds->insertRow("feccierre",$ad_feccierre);
					$this->ds->insertRow("parcial",$li_parcial);

				}
			}
			if($lb_valido)
			{
				if($ai_orden==0)
				{
					$this->ds->sortData("numordcom");
				}
				else
				{
					$this->ds->sortData("feccierre");
				}
			}			
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_cierreordenes

	function uf_select_fechacierrecmp($as_codemp,$as_numordcom,$ad_fecdes,$ad_fechas,$ai_orden,&$ad_feccierre)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_fechacierrecmp
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numordcom  // numero de orden de compra
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//  			       $ad_feccierre  // fecha de cierre de la orden de compra
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar los datos asociados a un cierre de ordendes de compra
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT fecha".
				"  FROM  spg_dt_cmp".
				" WHERE codemp='". $as_codemp ."'".
				"   AND procede='SPGCMP'".
				"   AND procede_doc='SOCCOC'".
				"   AND operacion='CS'".
				"   AND documento='".$as_numordcom."'".
				"   AND fecha<='".$ad_fechas."'".
				"   AND fecha>='".$ad_fecdes."'".
				" GROUP BY documento,fecha";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_fechacierrecmp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_feccierre= $row["fecha"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_fechacierrecmp

	function uf_select_fechacierrerec($as_codemp,$as_numordcom,$as_codpro,$ad_fecdes,$ad_fechas,$ai_orden,&$ad_feccierre)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_fechacierrerec
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numordcom  // numero de orden de compra
		//  			       $as_codpro     // codigo de proveedor
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//  			       $ad_feccierre  // fecha de cierre de la orden de compra
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de buscar los datos asociados a un cierre de ordendes de compra
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT fecrec".
				"  FROM siv_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numordcom='".$as_numordcom."'".
				"   AND cod_pro='".$as_codpro."'".
				"   AND fecrec<='".$ad_fechas."'".
				"   AND fecrec>='".$ad_fecdes."'";
				
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_fechacierrerec ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_feccierre= $row["fecrec"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_fechacierrerec

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////             Funciones del reporte de Valoracion de Toma de Inventario          ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_dt_valtoma($as_codemp,$as_numtom)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function: uf_select_dt_valtoma
	//	           Access: public
	//  		Arguments: $as_codemp     // codigo de empresa
	//  			       $as_numtom     // numero de toma de inventario
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description: Función que se encarga de realizar la busqueda de los articulos que pertenecen a una toma de inventario
	//				       referente al maestro indicado incluyendo los costos promedios.
	//         Creado por: Ing. Luis Anibal Lang           
	//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificación: 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT siv_dt_toma.*,".
				"       (SELECT denart FROM siv_articulo ".
				"         WHERE siv_dt_toma.codart=siv_articulo.codart) AS denart,".
				"       (SELECT denunimed FROM siv_unidadmedida ".
				"         WHERE siv_unidadmedida.codunimed=siv_articulo.codunimed) AS denunimed,".
				"       (SELECT cosproart FROM siv_articulo ".
				"         WHERE siv_dt_toma.codart=siv_articulo.codart) AS cospro".
				"  FROM siv_dt_toma,siv_articulo".
				" WHERE siv_dt_toma.codemp='". $as_codemp ."'".
				"   AND siv_dt_toma.numtom='". $as_numtom ."'".
				"   AND siv_dt_toma.codart=siv_articulo.codart";
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_dt_valtoma ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_dt_toma
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////                 Funciones de los Ajustes  de Inventario                  /////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_ajuste($as_codemp,$ad_desde,$ad_hasta)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_ajuste
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numtom     // numero de toma de inventario
		//  			       $ad_desde      // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta      // fecha de cierre del periodo de busqueda
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de  una toma de inventario que han sido procesadas
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 15/09/2006							Fecha de Ultima Modificación:  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT siv_toma.*,".
				"       (SELECT nomfisalm FROM siv_almacen ".
				"         WHERE siv_toma.codalm=siv_almacen.codalm) AS nomfisalm".
				"  FROM siv_toma,siv_almacen".
				" WHERE siv_toma.codemp='". $as_codemp ."'".
				"   AND siv_toma.estpro=1";
		if(($ad_desde!="")&&($ad_hasta!=""))
		{
			$ld_fecdes=$this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_fechas=$this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sql=$ls_sql." AND siv_toma.fectom<='".$ld_fechas."'".
							" AND siv_toma.fectom>='".$ld_fecdes."'";
		}
	   $rs_data=$this->io_sql->select($ls_sql);
	   $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_ajuste ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
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
	} //fin  function uf_select_toma
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_siv_acta_recepcion_bienes($as_codemp,$as_numordcom)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_siv_acta_recepcion_bienes
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $ls_numordcom     // numero de orden de compra
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de las recepciones de inventario
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 06/02/2007							Fecha de Ultima Modificación:  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql=" SELECT * ".
                 " FROM  siv_dt_movimiento ".
				 " WHERE codemp='".$as_codemp."' AND numdoc='".$as_numordcom."' AND opeinv='ENT' ".
                 " ORDER BY nummov DESC LIMIT 1 ";
		$rs_data1=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data1);	
		if($rs_data1===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_siv_acta_recepcion_bienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
		  if($row=$this->io_sql->fetch_row($rs_data1))
		  {
		 	 $ls_nummov = $row["nummov"];
			 $ldt_fecmov = $row["fecmov"];
		     $ls_sql=" SELECT siv_dt_movimiento.*, siv_dt_movimiento.cosartaux AS cosart ".
					 " FROM   siv_dt_movimiento ".
					 " WHERE  codemp='".$as_codemp."' AND ".
					 "   	  nummov='".$ls_nummov."' AND ".
					 "		  opeinv='ENT' AND ".
				     " 		  fecmov='".$ldt_fecmov."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			$li_numrows=$this->io_sql->num_rows($rs_data);	
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->Report MÉTODO->uf_siv_acta_recepcion_bienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_numordcom = $row["numdoc"];
					$ls_codalm    = $row["codalm"];
					$ls_codart    = $row["codart"];
					$ls_canart    = $row["canart"];
					$ls_preuniart = $row["cosart"];
					$ldt_fecmov   = $row["fecmov"];
					$ls_nompro="";
					$ls_cedrep="";
					$ls_nomreppro="";
					$lb_valido=$this->uf_select_proveedor($as_codemp,$ls_cod_pro,$ls_nompro,$ls_cedrep,$ls_nomreppro,$ls_numordcom);					
					if($lb_valido)
					{
						$ls_denart="";
						$lb_valido=$this->uf_select_denominacion_articulo($as_codemp,$ls_codart,$ls_denart);	
						if($lb_valido)
						{
							$ls_nomresalm="";
							$lb_valido=$this->uf_select_encargado_almacen($as_codemp,$ls_codalm,$ls_nomresalm);	
							if($lb_valido)
							{
								$ls_montotart=0;
								$lb_valido=$this->uf_select_monto_total($as_codemp,$ls_numordcom,$ls_montotart);	
								if($lb_valido)
								{
									$ls_ordfac=substr($ls_numordcom,0,1);
									if($ls_ordfac=='F')
									{
									  $ls_estpro=1;
									}
									else
									{
									  $ls_estpro=0;
									}
									$this->dts_reporte->insertRow("numordcom",$ls_numordcom);
									$this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
									$this->dts_reporte->insertRow("codalm",$ls_codalm);
									$this->dts_reporte->insertRow("nomresalm",$ls_nomresalm);			
									$this->dts_reporte->insertRow("codart",$ls_codart);			
									$this->dts_reporte->insertRow("canart",$ls_canart);			
									$this->dts_reporte->insertRow("preuniart",$ls_preuniart);	
									$this->dts_reporte->insertRow("nompro",$ls_nompro);			
									$this->dts_reporte->insertRow("cedrep",$ls_cedrep);			
									$this->dts_reporte->insertRow("nomreppro",$ls_nomreppro);	
									$this->dts_reporte->insertRow("denart",$ls_denart);	
									$this->dts_reporte->insertRow("estpro",$ls_estpro);	
									$this->dts_reporte->insertRow("fecrec",$ldt_fecmov);	
									$this->dts_reporte->insertRow("montotart",$ls_montotart);	
									$lb_valido=true;
								}//if	
							}//if	
						}//if	
					}//if
				}//while
			 }//else
		   }//if
		  $this->io_sql->free_result($rs_data);
		 }//else	
		return $lb_valido; 
	} //fin  function uf_select_toma
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_proveedor($as_codemp,&$as_cod_pro,&$as_nompro,&$as_cedrep,&$as_nomreppro,$as_numordcom)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_proveedor
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_cod_pro     //  codigo del proveedor (referencia)
		//                     $as_nompro      //  nombre del proveedor (referencia)
		//                     $as_cedrep      //  cedula del representante del proveedor (referencia)
		//                     $as_nomreppro   // nombre del representante del proveedor
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda del proveedor segun el codigo
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 06/02/2007							Fecha de Ultima Modificación:  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT rpc_proveedor.cod_pro,rpc_proveedor.nompro,rpc_proveedor.cedrep,rpc_proveedor.nomreppro ".
                " FROM  soc_ordencompra,rpc_proveedor ".
                " WHERE soc_ordencompra.codemp='".$as_codemp."' AND ".
                "       soc_ordencompra.numordcom='".$as_numordcom."' AND ".
                "       soc_ordencompra.estcondat='B' AND ".
                "       soc_ordencompra.cod_pro=rpc_proveedor.cod_pro ";
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_proveedor ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_cod_pro   = $row["cod_pro"];
				$as_nompro    = $row["nompro"];
				$as_cedrep    = $row["cedrep"];
				$as_nomreppro = $row["nomreppro"];
		        $lb_valido=true;
			}//while
			$this->io_sql->free_result($rs_data);
		}//else	
		return $lb_valido;
   }//fin uf_select_proveedor
//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_denominacion_articulo($as_codemp,$as_codart,&$as_denart)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_denominacion_articulo
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_codart     //  codigo del proveedor (referencia)
		//                     $as_denart      //  nombre del proveedor (referencia)
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda de la denominacion del articulo
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 08/02/2007							Fecha de Ultima Modificación:  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT denart FROM siv_articulo WHERE  codemp='".$as_codemp."' AND codart='".$as_codart."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_denominacion_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_denart   = $row["denart"];
		        $lb_valido=true;
			}//while
			$this->io_sql->free_result($rs_data);
		}//else	
		return $lb_valido;
   }//fin uf_select_denominacion_articulo
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_encargado_almacen($as_codemp,$as_codalm,$as_nomresalm)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_encargado_almacen
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_codalm     //  codigo del almacen 
		//                     $as_nomresalm      //  nombre del encargado del almacen (referencia)
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda del encargado del almacen
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 08/02/2007							Fecha de Ultima Modificación:  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT nomresalm FROM siv_almacen WHERE codemp='".$as_codemp."' AND codalm='".$as_codalm."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_encargado_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_nomresalm   = $row["nomresalm"];
		        $lb_valido=true;
			}//while
			$this->io_sql->free_result($rs_data);
		}//else	
		return $lb_valido;
   }//fin uf_select_encargado_almacen
//---------------------------------------------------------------------------------------------------------------------------------	

//---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_monto_total($as_codemp,$as_numordcom,&$as_monsubtot)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_monto_total
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_numordcom     //  numero de la orden de  compra
		//                     $as_montotart      //  monto total del articulo (referencia)
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se encarga de realizar la busqueda del encargado del almacen
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 08/02/2007							Fecha de Ultima Modificación:  
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" SELECT monsubtotaux AS monsubtot  ".
		        " FROM   soc_ordencompra ".
				" WHERE  codemp='".$as_codemp."' AND numordcom='".$as_numordcom."' AND  estcondat='B' "; 
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_monto_total ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_monsubtot   = $row["monsubtot"];
		        $lb_valido=true;
			}//while
			$this->io_sql->free_result($rs_data);
		}//else	
		return $lb_valido;
   }//fin uf_select_monto_total
//---------------------------------------------------------------------------------------------------------------------------------	
} //fin  class sigesp_siv_class_report
?>
