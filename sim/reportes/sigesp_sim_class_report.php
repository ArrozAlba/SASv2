<?php
class sigesp_sim_class_report {
	var $obj = "";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;
	var $dts_reporte;

	function sigesp_sim_class_report() {
		require_once ("../../shared/class_folder/class_sql.php");
		require_once ("../../shared/class_folder/class_mensajes.php");
		require_once ("../../shared/class_folder/sigesp_include.php");
		require_once ("../../shared/class_folder/class_funciones.php");
		$this->io_msg = new class_mensajes();
		$this->dat_emp = $_SESSION["la_empresa"];
		$this->gestor = $_SESSION["ls_gestor"];
		$in = new sigesp_include();
		$this->con = $in->uf_conectar();
		$this->io_sql = new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds = new class_datastore();
		$this->ds_detalle = new class_datastore();
		$this->ds_detcontable = new class_datastore();
		$this->dts_reporte = new class_datastore();
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////             Funciones de Reportes de Niveles de Existencias de Articulos             ////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------------

	function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie) {

		$add_sql = '';
		if ($ls_tienda_desde=='' or $ls_tienda_hasta=='') {

		$add_sql = "$alias_tabla.codtiend='$ls_codtie'";

		}else {

		$add_sql = "$alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

		}

		return $add_sql;
		}

	function uf_select_almacen($as_codemp, $as_codalm, $as_codart, $ai_orden,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_almacen
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_codalm    // codigo de almacen
		//  			       $as_codart    // codigo de articulo
		//  			       $ai_orden     // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta   0-> Por codigo de almacen 1-> Por nombre de almacen
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda  de los almacenes en los que existen
		//				       articulos por el codigo o por el nombre del almacen.
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 02/02/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlalm = "";
		$ls_sqlart = "";
		if (!empty ($as_codart)) {
			$ls_sqlart = " AND codart='" . $as_codart . "'";
		}
		if (!empty ($as_codalm)) {
			$ls_sqlalm = " AND codalm='" . $as_codalm . "'";
		}
		if ($ai_orden == 0) {
			$ls_order = "codalm";
		} else {
			$ls_order = "nomfisalm";
		}

		if( !empty($ls_tienda_desde) AND !empty($ls_tienda_hasta)){
			$ls_filtro_tienda = "substr(sim_almacen.codalm,7,4) BETWEEN '".$ls_tienda_desde."' AND '".$ls_tienda_hasta."'  AND";
			$ls_where = " WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articuloalmacen',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'";
		}else{
			$ls_filtro_tienda = "";
			$ls_where = " WHERE codemp='" . $as_codemp . "' ";
		}
		 $ls_sql = "SELECT sim_articuloalmacen.codalm," .
		"       (SELECT nombre FROM sigesp_empresa" .
		"         WHERE sim_articuloalmacen.codemp=sigesp_empresa.codemp) AS nombre," .
		"       (SELECT nomfisalm FROM sim_almacen" .
		//"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_almacen',$_SESSION["ls_codtienda"])." AND sim_articuloalmacen.codalm=sim_almacen.codalm) AS nomfisalm " .
		"         WHERE ".$ls_filtro_tienda." sim_articuloalmacen.codalm=sim_almacen.codalm) AS nomfisalm " .
		"  FROM sim_articuloalmacen" .
		$ls_where .
		$ls_sqlalm .
		$ls_sqlart .
		" GROUP BY codemp,codalm" .
		" ORDER BY " . $ls_order . "";

		//print $ls_sql;

		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_almacen ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			//exit;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_almacen

	function uf_select_articuloxalmacen($as_codemp, $as_codalm, $as_codart, $ai_ordenalm, $ai_ordenart,$as_tienda_desde,$as_tienda_hasta,$as_codpro,$rs_data) 
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
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de los articulos que estan en cada uno de los almacenes
		//				       � en que almacenes esta determinado articulo en ambos casos con sus respectivas existencias y ordenados
		//			           por el codigo o por el nombre del almacen o articulo.
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 02/02/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlart = "";
		$ls_sqlalm = "";
		$ls_sqlpro = "";
		if (!empty ($as_codart)) {
			$ls_sqlart = " AND sim_articuloalmacen.codart='" . $as_codart . "'";
		}
		if (!empty ($as_codalm)) {
			$ls_sqlalm = " AND sim_articuloalmacen.codalm='" . $as_codalm . "'";
		}
		if ($ai_ordenalm == 0) {
			$ls_orderalm = "sim_articuloalmacen.codalm";
		} else {
			$ls_orderalm = "sim_articuloalmacen.nomfisalm";
		}
		if ($ai_ordenart == 0) {
			$ls_orderart = "sim_articuloalmacen.codart";
		} else {
			$ls_orderart = "sim_articulo.denart";
		}

		if( !empty($as_tienda_desde) AND !empty($as_tienda_hasta)){
			$ls_filtro_tienda = "sim_articuloalmacen.codtiend BETWEEN '".$as_tienda_desde."' AND '".$as_tienda_hasta."' AND";
		}else{
			$ls_filtro_tienda ="";
		}
		
		if ($as_codpro != '') {
			$ls_sqlpro = " AND sim_articuloalmacen.cod_pro = '".$as_codpro."' ";
		}
		
		/*$ls_sql = "SELECT sim_articuloalmacen.*,sim_unidadmedida.unidad AS unidades,sim_unidadmedida.denunimed as denuni," .
		"       (SELECT denart FROM sim_articulo" .
		"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articulo',$_SESSION["ls_codtienda"])." AND sim_articuloalmacen.codart=sim_articulo.codart) AS denart," .
		"       (SELECT nomfisalm FROM sim_almacen" .
		//"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_almacen',$_SESSION["ls_codtienda"])." AND sim_articuloalmacen.codalm=sim_almacen.codalm) AS nomfisalm " .
		"         WHERE substr(sim_almacen.codalm,7,4) BETWEEN '".$ls_tienda_desde."' AND '".$ls_tienda_hasta."' AND sim_articuloalmacen.codalm=sim_almacen.codalm) AS nomfisalm " .
		"  FROM sim_articuloalmacen,sim_articulo,sim_unidadmedida" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articuloalmacen',$_SESSION["ls_codtienda"])." AND sim_articuloalmacen.codemp='" . $as_codemp . "'" .
		"   AND sim_articuloalmacen.codart=sim_articulo.codart" .
		"   AND sim_articulo.codunimed=sim_unidadmedida.codunimed" .
		$ls_sqlart .
		$ls_sqlalm .
		" ORDER BY " . $ls_orderalm . ", " . $ls_orderart . "";*/

		$ls_sql = "SELECT sim_articuloalmacen.codemp, sim_articuloalmacen.codart, sim_articuloalmacen.codemp, sim_articuloalmacen.existencia, ".
				"sim_articuloalmacen.codtiend, sim_articuloalmacen.cod_pro, sim_unidadmedida.unidad AS unidades,sim_unidadmedida.denunimed as denuni, ".
				"sim_articulo.denart, sim_almacen.nomfisalm ".
				"FROM sim_articuloalmacen,sim_articulo,sim_unidadmedida,sim_almacen ".
				"WHERE ".$ls_filtro_tienda." sim_articuloalmacen.codemp='". $as_codemp."' ".
				"AND sim_articuloalmacen.codalm = sim_almacen.codalm AND sim_articuloalmacen.codart=sim_articulo.codart AND sim_articulo.codunimed=sim_unidadmedida.codunimed ".
				$ls_sqlart .
				$ls_sqlalm .
				$ls_sqlpro .
				" ORDER BY " . $ls_orderalm . ", " . $ls_orderart . "";

		
		//print $ls_sql;
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_articuloxalmacen ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			exit;
		} else {
			if ($li_numrows > 0) {				
				$lb_valido = true;
				//@pg_result_seek($rs_data,0);
			}
		}
		return $lb_valido;
	} //fin  function uf_select_articuloxalmacen

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////       Funciones del Reporte de Movimientos de Articulos        ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_articulos($as_codemp, $as_codalm, $as_codart, $ad_desde, $ad_hasta, & $ai_total, $ai_ordenart,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_articulos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_codalm    // codigo de almacen
		//  			         as_codart    // codigo de articulo
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta 0-> Por codigo de articulo 1-> Por denominaci�n
		//	         Returns :   lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de los articulos que han tenido
		//				        movimientos de inventario en el intervalo solicitado.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   03/02/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlalm = "";
		$ls_sqlart = "";
		$ls_sqlint = "";
		if (!empty ($as_codart)) {
			$ls_sqlart = " AND codart='" . $as_codart . "'";
		}
		if (!empty ($as_codalm)) {
			$ls_sqlalm = " AND codalm='" . $as_codalm . "'";
		}
		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint = " AND fecmov >='" . $ld_auxdesde . "'" .
			" AND fecmov <='" . $ld_auxhasta . "'";
		}
		if ($ai_ordenart == 0) {
			$ls_order = "codart";
		} else {
			$ls_order = "denart";
		}
		$ls_sql = "SELECT sim_dt_movimiento.codart," .
		"       (SELECT nombre FROM sigesp_empresa" .
		"         WHERE sim_dt_movimiento.codemp=sigesp_empresa.codemp) as nombre," .
		"       (SELECT denart FROM sim_articulo" .
		//"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articulo',$_SESSION["ls_codtienda"])." AND sim_dt_movimiento.codart=sim_articulo.codart) as denart, count(nummov) as total " .
		"         WHERE sim_dt_movimiento.codart=sim_articulo.codart) as denart, count(nummov) as total " .
		" FROM sim_dt_movimiento" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND  codemp='" . $as_codemp . "'" .
		"   AND canart > 0" .
		"   AND codprodoc <> 'REV'" .
		"   AND numdocori NOT IN (SELECT numdoc FROM sim_dt_movimiento" .
		"                          WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
		$ls_sqlart .
		"                            AND canart > 0" .
		"                            AND codprodoc ='REV')" .
		$ls_sqlalm .
		$ls_sqlart .
		$ls_sqlint .
		" GROUP BY sim_dt_movimiento.codemp,sim_dt_movimiento.codart,sim_dt_movimiento.codtiend" .
		" ORDER BY " . $ls_order . "";

		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_articulos ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			exit;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->reset_ds();
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_articulos

	function uf_select_movimientosxarticulos($as_codemp, $as_codalm, $as_codart, $ad_desde, $ad_hasta, & $ai_total, $ai_ordenart, $ai_ordenfec,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_movimientosxarticulos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_codalm    // codigo de almacen
		//  			         as_codart    // codigo de articulo
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta 0-> Por codigo de articulo 1-> Por denominaci�n
		//  			         ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns :   lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de los movimientos de inventario de los articulos
		//				        en el intervalo solicitado.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   03/02/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlalm = "";
		$ls_sqlart = "";
		$ls_sqlint = "";
		if (!empty ($as_codart)) {
			$ls_sqlart = " AND codart='" . $as_codart . "'";
		}
		if (!empty ($as_codalm)) {
			$ls_sqlalm = " AND codalm='" . $as_codalm . "'";
		}
		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint = " AND fecmov >='" . $ld_auxdesde . "' AND fecmov <='" . $ld_auxhasta . "'";
		}
		if ($ai_ordenart == 0) {
			$ls_order = "codart";
		} else {
			$ls_order = "denart";
		}
		if ($ai_ordenfec == 0) {
			$ls_order = "fecmov";
		} else {
			$ls_order = "fecmov DESC";
		}

		if( !empty($ls_tienda_desde) AND !empty($ls_tienda_hasta)){
			$ls_filtro_tienda = "substr(sim_almacen.codalm,7,4) BETWEEN '".$ls_tienda_desde."' AND '".$ls_tienda_hasta."'  AND";
		}else{
			$ls_filtro_tienda = "";
		}

		$ls_sql = "SELECT sim_dt_movimiento.*," .
		"       (SELECT nomfisalm FROM sim_almacen" .
		//"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_almacen',$_SESSION["ls_codtienda"])." AND sim_dt_movimiento.codalm=sim_almacen.codalm) AS nomfisalm " .
		"         WHERE ".$ls_filtro_tienda." sim_dt_movimiento.codalm=sim_almacen.codalm) AS nomfisalm " .
		"  FROM sim_dt_movimiento" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
		"   AND canart > 0" .
			//"   AND codprodoc <> 'REV'".
	"   AND numdocori NOT IN (SELECT numdoc FROM sim_dt_movimiento" .
		"                          WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
		"                            AND canart > 0" .
		"                            AND codart='" . $as_codart . "'" .
		"                            AND codprodoc ='REV')" .
		$ls_sqlalm .
		$ls_sqlart .
		$ls_sqlint .
		" ORDER BY " . $ls_order . ",opeinv";

		//print $ls_sql.' jkdbjk <br>';

		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_movimientosxarticulos ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->reset_ds();
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_movimientosxarticulos

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////         Funciones de Reporte de Articulos por Tipo            ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_tipos($as_coddesde, $as_codhasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_tipos
		//	           Access:   public
		//  		Arguments:
		//  			         as_coddesde  // codigo de tipo de articulo para inicio de la busqueda
		//  			         as_codhasta  // codigo de tipo de articulo para fin de la busqueda
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de los tipos de articulos que existen
		//				        en un rango indicado, ordenados por el codigo de tipo de articulo
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   20/02/2006							Fecha de Ultima Modificaci�n:   20/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta))) {
			$ls_sqlint = " WHERE codtipart >='" . $as_coddesde . "' AND codtipart <='" . $as_codhasta . "'";
		}
		$ls_sql = "SELECT sim_tipoarticulo.*," .
		" (SELECT count(codart) FROM sim_articulo" .
		"   WHERE sim_tipoarticulo.codtipart=sim_articulo.codtipart)total" .
		" FROM sim_tipoarticulo" .
		$ls_sqlint .
		" GROUP BY codtipart";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_tipos ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_tipos

	function uf_select_articuloxtipo($as_codtipart, $as_coddesde, $as_codhasta, $ai_ordenart) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_articuloxtipo
		//	           Access:   public
		//  		Arguments:
		//  			         ls_codtipart   // codigo de tipo de articulo
		//  			         as_coddesde    // codigo de tipo de articulo para inicio de la busqueda
		//  			         as_codhasta    // codigo de tipo de articulo para fin de la busqueda
		//  			         ai_ordenart    // parametro por el cual vamos a ordenar los resultados
		//								           obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda de los articulos que pertenecen a cada uno de los tipos
		//				        de articulos dentro de un intervalo de codigos indicados.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   20/02/2006							Fecha de Ultima Modificaci�n: 20/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta))) {
			$ls_sqlint = " WHERE codtipart >='" . $as_coddesde . "' AND codtipart <='" . $as_codhasta . "'";
		}
		if ($ai_ordenart == 0) {
			$ls_orderart = "codart";
		} else {
			$ls_orderart = "denart";
		}
		$ls_sql = " SELECT sim_articulo.*, " .
						" (SELECT denunimed FROM sim_unidadmedida " .
						"   WHERE sim_unidadmedida.codunimed=sim_articulo.codunimed) denunimed, " .
						" (SELECT unidad FROM sim_unidadmedida " .
						"   WHERE sim_unidadmedida.codunimed=sim_articulo.codunimed) unidades " .
						" FROM sim_articulo " .
						" WHERE codtipart='".$as_codtipart."' ".
							// $ls_sqlint.
				  " ORDER BY ".$ls_orderart;
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_articuloxtipo ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_articuloxalmacen

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////       Funciones del Reporte de Ordenes de Despachos           ///////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_despachos($as_codemp, $as_numorddes, $ad_desde, $ad_hasta, $ai_ordenfec,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_despachos
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_numorddes // numero de orden de despacho
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de las ordedes de despacho emitidas
		//				        en un rango de fecha indicado, ordenados por fecha.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   20/02/2006							Fecha de Ultima Modificaci�n:   20/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		$ls_sqlord = "";
		if (!empty ($as_numorddes)) {
			$ls_sqlord = " AND numorddes='" . $as_numorddes . "'";
		}

		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint = " AND fecdes >='" . $ld_auxdesde . "'" .
			" AND fecdes <='" . $ld_auxhasta . "'";
		}
		if ($ai_ordenfec == 0) {
			$ls_order = "fecdes";
		} else {
			$ls_order = "fecdes DESC";
		}
		$ls_sql = "SELECT sim_despacho.numorddes,sim_despacho.numsol,sim_despacho.coduniadm,sim_despacho.obsdes,sim_despacho.fecdes," .
		"       (SELECT nombre FROM sigesp_empresa" .
		"         WHERE sim_despacho.codemp=sigesp_empresa.codemp) AS nombre," .
		"       (SELECT count(codart) FROM sim_dt_despacho" .
		"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_despacho',$_SESSION["ls_codtienda"])." AND sim_dt_despacho.numorddes=sim_despacho.numorddes) AS total," .
		"       (SELECT spg_unidadadministrativa.denuniadm FROM spg_unidadadministrativa" .
		"         WHERE spg_unidadadministrativa.coduniadm=sim_despacho.coduniadm) AS denuniadm" .
		"  FROM sim_despacho" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_despacho',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "' " .
		"   AND estrevdes = 1" .
		$ls_sqlint .
		$ls_sqlord .
		" GROUP BY sim_despacho.codemp,sim_despacho.numorddes,sim_despacho.numsol,sim_despacho.coduniadm,sim_despacho.obsdes,sim_despacho.fecdes" .
		" ORDER BY " . $ls_order . "";
//print $ls_sql;
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_despachos ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_tipos

	function uf_select_dt_despacho($as_codemp, $as_numorddes, $ad_desde, $ad_hasta, $ai_ordenfec,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_dt_despacho
		//	           Access:   public
		//  		Arguments:
		//  			         ls_codtipart   // codigo de tipo de articulo
		//  			         ad_desde       // codigo de tipo de articulo para inicio de la busqueda
		//  			         ad_hasta       // codigo de tipo de articulo para fin de la busqueda
		//  			         ai_ordenfec    // parametro por el cual vamos a ordenar los resultados
		//								           obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda de los articulos que pertenecen a cada uno de los tipos
		//				        de articulos dentro de un intervalo de codigos indicados.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   20/02/2006							Fecha de Ultima Modificaci�n: 20/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;

		if( !empty($ls_tienda_desde) AND !empty($ls_tienda_hasta)){
			$ls_filtro_tienda = "substr(sim_almacen.codalm,7,4) BETWEEN '".$ls_tienda_desde."' AND '".$ls_tienda_hasta."'  AND";
		}else{
			$ls_filtro_tienda = "";
		}

		$ls_sql = "SELECT sim_dt_despacho.*," .
		"       (SELECT denart FROM sim_articulo" .
		"         WHERE sim_articulo.codart=sim_dt_despacho.codart) AS denart," .
		"       (SELECT nomfisalm FROM sim_almacen" .
		"         WHERE ".$ls_filtro_tienda." sim_almacen.codalm=sim_dt_despacho.codalm) AS nomfisalm" .
		"  FROM sim_dt_despacho" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_despacho',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "' " .
		"   AND numorddes='" . $as_numorddes . "'" .
		" ORDER BY sim_dt_despacho.orden ";
//print $ls_sql;
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_dt_despacho ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_articuloxalmacen

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////         Funciones del Reporte de Entradas de Suministros a Almac�n           ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_recepcion($as_codemp, $as_numconrec, $ad_desde, $ad_hasta, $ai_ordenfec,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_recepcion
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_numconrec // numero consecutivo de recepcion
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de las entradas desuministros a los almacenes emitidas
		//				        en un rango de fecha indicado, ordenados por fecha.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   20/02/2006							Fecha de Ultima Modificaci�n:   05/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		$ls_sqlnum = "";
		if (!empty ($as_numconrec)) {
			$ls_sqlnum = " AND numconrec ='" . $as_numconrec . "'";
		}
		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint = " AND fecrec >='" . $ld_auxdesde . "'" .
			" AND fecrec <='" . $ld_auxhasta . "'";
		}

		if( !empty ($ls_tienda_desde)){
			$ls_codtienda = $ls_tienda_desde;
		}else{
			$ls_codtienda = $_SESSION["ls_codtienda"];
		}

		if ($ai_ordenfec == 0) {
			$ls_order = "fecrec";
		} else {
			$ls_order = "fecrec DESC";
		}

		if( !empty($ls_tienda_desde) AND !empty($ls_tienda_hasta)){
			$ls_filtro_tienda = "substr(sim_almacen.codalm,7,4) BETWEEN '".$ls_tienda_desde."' AND '".$ls_tienda_hasta."'  AND";
		}else{
			$ls_filtro_tienda = " substr(sim_almacen.codalm,7,4) ilike '%".$ls_codtienda."%' AND ";
		}

		$ls_sql = "SELECT sim_recepcion.numconrec,sim_recepcion.numordcom,sim_recepcion.cod_pro," .
		"       sim_recepcion.codalm,sim_recepcion.obsrec,sim_recepcion.fecrec," .
		"       (SELECT nombre FROM sigesp_empresa" .
		"         WHERE sim_recepcion.codemp=sigesp_empresa.codemp) AS nombre," .
		"       (SELECT nomfisalm FROM sim_almacen" .
		//"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_almacen',$_SESSION["ls_codtienda"])." AND sim_recepcion.codalm=sim_almacen.codalm) AS nomfisalm," .
		"         WHERE ".$ls_filtro_tienda." sim_recepcion.codalm=sim_almacen.codalm) AS nomfisalm," .
		"       (SELECT nompro FROM rpc_proveedor" .
		"         WHERE sim_recepcion.cod_pro=rpc_proveedor.cod_pro) AS nompro" .
		"  FROM sim_recepcion" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_recepcion',$ls_codtienda)." AND codemp='" . $as_codemp . "' " .
		"   AND estrevrec=1 " .
		$ls_sqlnum .
		$ls_sqlint .
		" GROUP BY sim_recepcion.codemp,sim_recepcion.numconrec,sim_recepcion.numordcom,sim_recepcion.cod_pro," .
		"          sim_recepcion.codalm,sim_recepcion.obsrec,sim_recepcion.fecrec" .
		" ORDER BY " . $ls_order . "";

		//print $ls_sql.'<br>';
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_recepcion ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			//exit;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}

	function uf_select_dt_recepcion($as_codemp, $as_numordcom, $as_numconrec,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_dt_recepcion
		//	           Access:   public
		//  		Arguments:
		//  			         as_codemp     // codigo de empresa
		//  			         as_numordcom  // numero de orden de compra / Factura
		//  			         as_numconrec  // numero consecutivo de recepcion
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda de los articulos que pertenecen a una entrada de suministros
		//				        a almacen referente al maestro indicado.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   22/02/2006							Fecha de Ultima Modificaci�n: 22/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if( !empty ($ls_tienda_desde)){
			$ls_codtienda = $ls_tienda_desde;
		}else{
			$ls_codtienda = $_SESSION["ls_codtienda"];
		}

		$lb_valido = false;
		$ls_sql = "SELECT sim_dt_recepcion.*,sim_unidadmedida.unidad AS unidades," .
		"     (SELECT sim_articulo.denart FROM sim_articulo" .
		//"       WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articulo',$_SESSION["ls_codtienda"])." AND sim_articulo.codart=sim_dt_recepcion.codart) AS denart" .
		"       WHERE sim_articulo.codart=sim_dt_recepcion.codart) AS denart" .
		"  FROM sim_dt_recepcion,sim_articulo,sim_unidadmedida" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_recepcion',$ls_codtienda)." AND sim_dt_recepcion.codemp='" . $as_codemp . "' " .
		"   AND sim_dt_recepcion.numordcom='" . $as_numordcom . "'" .
		"   AND sim_dt_recepcion.numconrec='" . $as_numconrec . "'" .
		"   AND sim_articulo.codart=sim_dt_recepcion.codart" .
		"   AND sim_unidadmedida.codunimed=sim_articulo.codunimed" .
		" ORDER BY sim_dt_recepcion.orden ";

		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_dt_recepcion ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;

	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////           Funciones del Reporte de Transferencia entre Almacenes             ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_transferencia($as_codemp, $as_numtra, $ad_desde, $ad_hasta, $ai_ordenfec,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_transferencia
		//	           Access:   public
		//  		Arguments:   as_codemp    // codigo de empresa
		//  			         as_numtra    // numero de transferencia
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenfec  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta por fecha 0-> Ascendentemente 1-> Descendentemente.
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de las entradas desuministros a los almacenes emitidas
		//				        en un rango de fecha indicado, ordenados por fecha.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   20/02/2006							Fecha de Ultima Modificaci�n:   20/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		$ls_sqlnum = "";
		if (!empty ($as_numtra)) {
			$ls_sqlnum = " AND sim_transferencia.numtra ='" . $as_numtra . "'";
		}
		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlint = " AND sim_transferencia.fecemi >='" . $ld_auxdesde . "' AND sim_transferencia.fecemi <='" . $ld_auxhasta . "'";
		}
		if ($ai_ordenfec == 0) {
			$ls_order = "sim_transferencia.fecemi";
		} else {
			$ls_order = "sim_transferencia.fecemi DESC";
		}
		$ls_sql = "SELECT sim_transferencia.numtra,sim_transferencia.codalmori,sim_transferencia.codalmdes," .
		"       sim_transferencia.obstra,sim_transferencia.fecemi," .
		"      (SELECT nombre FROM sigesp_empresa" .
		"        WHERE sim_transferencia.codemp=sigesp_empresa.codemp) AS nombre," .
		"      (SELECT nomfisalm FROM sim_almacen" .
		"        WHERE sim_transferencia.codalmori=sim_almacen.codalm) AS nomfisalmori," .
		"      (SELECT nomfisalm FROM sim_almacen" .
		"        WHERE sim_transferencia.codalmdes=sim_almacen.codalm) AS nomfisalmdes" .
		"  FROM sim_transferencia,sim_dt_transferencia" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_transferencia',$_SESSION["ls_codtienda"])." AND sim_transferencia.codemp='" . $as_codemp . "' " .
		$ls_sqlnum .
		$ls_sqlint .
		" AND sim_transferencia.numtra=sim_dt_transferencia.numtra GROUP BY sim_dt_transferencia.numtra,sim_transferencia.codemp,sim_transferencia.numtra,sim_transferencia.codalmori,sim_transferencia.codalmdes," .
		"          sim_transferencia.obstra,sim_transferencia.fecemi" .
		" ORDER BY " . $ls_order . "";

	$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_transferencia ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_transferencia

	function uf_select_dt_transferencia($as_codemp, $as_numtra,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_dt_transferencia
		//	           Access:   public
		//  		Arguments:
		//  			         as_codemp     // codigo de empresa
		//  			         as_numtrea    // numero de transferencia
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda de los articulos que pertenecen a una transferencia
		//				        entre almacenes referente al maestro indicado.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   22/02/2006							Fecha de Ultima Modificaci�n: 22/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT sim_dt_transferencia.*,sim_articulo.codunimed,sim_unidadmedida.unidad AS unidades," .
		"      (SELECT denart FROM sim_articulo " .
		"        WHERE sim_dt_transferencia.codart=sim_articulo.codart) AS denart" .
		"  FROM sim_dt_transferencia,sim_articulo,sim_unidadmedida" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_transferencia',$_SESSION["ls_codtienda"])." AND sim_dt_transferencia.codemp='" . $as_codemp . "'" .
		"   AND sim_dt_transferencia.numtra='" . $as_numtra . "'" .
		"   AND sim_dt_transferencia.codart=sim_articulo.codart" .
		"   AND sim_articulo.codunimed=sim_unidadmedida.codunimed";


		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);

		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_dt_transferencia ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;

			}
			else
			$lb_valido = false;
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_dt_transferencia

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones del Reporte de Resumen de Inventario                 ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_inventario($as_codemp, $as_coddesde, $as_codhasta, $ad_desde, $ad_hasta, $ai_ordenart,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_inventario
		//	           Access:   public
		//  		Arguments:
		//						 as_codemp    // codigo de empresa
		//  			         as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
		//  			         as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de los articulo que estan registrados en la tabla
		//				        sim_articulos ordenando los resultados por codigo de articulo o por denominacion segun sea lo indicado
		//						ademas de buscar los otros datos necesarios para generar el reporte.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   23/02/2006							Fecha de Ultima Modificaci�n:   24/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		$ls_sqlintfec = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta))) {
			$ls_sqlint = " AND sim_articulo.codart >='" . $as_coddesde . "' AND sim_articulo.codart <='" . $as_codhasta . "'";
		}

		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sqlintfec = " AND fecmov >='" . $ld_auxdesde . "' AND fecmov <='" . $ld_auxhasta . "'";
		}
		if ($ai_ordenart == 0) {
			$ls_order = "codart";
		} else {
			$ls_order = "denart";
		}

		$ls_sql = " SELECT sfc_producto.ultcosart,sfc_producto.cosproart,sim_articulo.*,sim_articuloalmacen.existencia,sim_articuloalmacen.codtiend,sim_articuloalmacen.codalm," .
		"        (SELECT count(sim_articulo.codart) FROM sim_articulo,sfc_producto WHERE sfc_producto.codart=sim_articulo.codart AND ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sfc_producto',$_SESSION["ls_codtienda"]).") AS total, " .
		"        (SELECT denunimed FROM sim_unidadmedida" .
		"          WHERE sim_unidadmedida.codunimed=sim_articulo.codunimed) AS denunimed," .
		"        (SELECT unidad FROM sim_unidadmedida" .
		"          WHERE sim_unidadmedida.codunimed=sim_articulo.codunimed) AS unidad," .
		"        (SELECT count(codart) FROM sim_dt_movimiento" .
		"          WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND sim_dt_movimiento.codart=sim_articulo.codart" .
		"            AND sim_dt_movimiento.opeinv='ENT'" .
		"            AND sim_dt_movimiento.codprodoc<>'REV'" .
		"            AND sim_dt_movimiento.canart > 0 " . $ls_sqlintfec . "" .
		"		     AND numdocori NOT IN (SELECT numdoc FROM sim_dt_movimiento" .
		"                                   WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
		"                                     AND canart > 0" .
		"                                     AND codprodoc ='REV')) AS entradas," .
		"        (SELECT count(codart) FROM sim_dt_movimiento" .
		"          WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND sim_dt_movimiento.codart=sim_articulo.codart" .
		"            AND sim_dt_movimiento.opeinv='SAL' " . $ls_sqlintfec . "" .
		"            AND sim_dt_movimiento.codprodoc<>'REV'" .
		"		     AND numdocori NOT IN (SELECT numdoc FROM sim_dt_movimiento" .
		"                                   WHERE codemp='" . $as_codemp . "'" .
		"                                     AND canart > 0" .
		"                                     AND codprodoc ='REV')) AS salidas" .
		" FROM sim_articulo,sim_articuloalmacen,sfc_producto" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articuloalmacen',$_SESSION["ls_codtienda"])." " .
		" AND ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sfc_producto',$_SESSION["ls_codtienda"])."" .
		" AND sim_articulo.codemp='" . $as_codemp . "' AND sim_articuloalmacen.codemp='" . $as_codemp . "'" .
		" AND sim_articulo.codemp=sim_articuloalmacen.codemp AND sim_articulo.codart=sim_articuloalmacen.codart  " .
		" AND sim_articulo.codemp=sfc_producto.codemp AND sim_articulo.codart=sfc_producto.codart" .
		" AND sim_articuloalmacen.codemp=sfc_producto.codemp AND sim_articuloalmacen.codart=sfc_producto.codart  " .
		" AND sim_articuloalmacen.codtiend=sfc_producto.codtiend" .
		$ls_sqlint .
		" ORDER BY " . $ls_order . "";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);

		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_inventario ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {


				$data = $this->io_sql->obtener_datos($rs_data);
				//print_r($data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;

			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_inventario

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones del Reporte de Almacenes                 ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_almacenes($as_codemp, $as_coddesde, $as_codhasta, $ai_ordenart) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_almacenes
		//	           Access:   public
		//  		Arguments:
		//						 as_codemp    // codigo de empresa
		//  			         as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
		//  			         as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
		//  			         ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description:  Funci�n que se encarga de realizar la busqueda  de los articulo que estan registrados en la tabla
		//				        sim_articulos ordenando los resultados por codigo de articulo o por denominacion segun sea lo indicado
		//						ademas de buscar los otros datos necesarios para generar el reporte.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   23/02/2006							Fecha de Ultima Modificaci�n:   23/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta))) {
			$ls_sqlint = " AND codalm >='" . $as_coddesde . "' AND codalm <='" . $as_codhasta . "'";
		}
		if ($ai_ordenart == 0) {
			$ls_order = "codalm";
		} else {
			$ls_order = "nomfisalm";
		}
		$ls_sql = " SELECT sim_almacen.*," .
		"        (SELECT count(codalm) FROM sim_almacen) AS total " .
		"  FROM sim_almacen" .
		" WHERE codemp='" . $as_codemp . "' " .
		$ls_sqlint .
		" ORDER BY " . $ls_order . "";
		$rs_data = $this->io_sql->select($ls_sql);
		
		
		if ($rs_data === false) 
		{
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_almacenes ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else 
		{
			$li_numrows = $this->io_sql->num_rows($rs_data);
			if ($li_numrows > 0) 
			{
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_transferencia

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////               Funciones de la Toma de Inventario                             ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_toma($as_codemp, $as_numtom, $ad_desde, $ad_hasta,$ls_tienda_desde,$ls_tienda_hasta) {
	//function uf_select_toma($as_codemp, $as_numtom) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_toma
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numtom     // numero de toma de inventario
		//  			       $ad_desde      // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta      // fecha de cierre del periodo de busqueda
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de  una toma de inventario
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 31/02/2006							Fecha de Ultima Modificaci�n: 15/09/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT sim_toma.*," .
		"       (SELECT nomfisalm FROM sim_almacen " .
		"         WHERE sim_toma.codalm=sim_almacen.codalm) AS nomfisalm" .
		" FROM sim_toma,sim_almacen" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_toma',$_SESSION["ls_codtienda"])." AND sim_toma.codemp='" . $as_codemp . "'";
		if ($as_numtom != "") {
			$ls_sql = $ls_sql . " AND sim_toma.numtom='" . $as_numtom . "'";

		}
		if (($ad_desde != "") && ($ad_hasta != "")) {
			$ld_fecdes = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_fechas = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sql = $ls_sql . " AND sim_toma.fectom<='" . $ld_fechas . "'" .
			" AND sim_toma.fectom>='" . $ld_fecdes . "'";
		}
       // print $ls_sql."<br>";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_toma ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_toma

	function uf_select_dt_toma($as_codemp, $as_numtom,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_toma
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numtom     // numero de toma de inventario
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de los articulos que pertenecen a una toma de inventario
		//				       referente al maestro indicado.
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 31/02/2006							Fecha de Ultima Modificaci�n: 31/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT sim_dt_toma.*," .
		"       (SELECT denart FROM sim_articulo " .
		"         WHERE sim_dt_toma.codart=sim_articulo.codart) AS denart" .
		"  FROM sim_dt_toma,sim_articulo" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_toma',$_SESSION["ls_codtienda"])." AND sim_dt_toma.codemp='" . $as_codemp . "'" .
		"   AND sim_dt_toma.numtom='" . $as_numtom . "'" .
		"   AND sim_dt_toma.codart=sim_articulo.codart";
		//print $ls_sql."<br>";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_dt_toma ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_dt_toma
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////              Funciones de la valoracion de Inventario                        ///////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_articulosmovimientos($as_codemp, $as_coddesde, $as_codhasta, $ad_desde, $ad_hasta, $ai_ordenart,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_select_articulosmovimientos
		//	           Access:   public
		//  		Arguments:
		//						 as_codemp    // codigo de empresa
		//  			         as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
		//  			         as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
		//  			         ad_desde     // fecha de inicio del intervalo de dias para la busqueda
		//  			         ad_hasta     // fecha de cierre del intervalo de dias para la busqueda
		//  			         ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//								         obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de los articulos que han entrado al inventario
		//					   en un periodo de tiempo
		//						ademas de buscar los otros datos necesarios para generar el reporte.
		//         Creado por:  Ing. Luis Anibal Lang
		//   Fecha de Cracion:   23/02/2006							Fecha de Ultima Modificaci�n:   24/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido = false;
		$ls_sqlint = "";
		$ls_sqlintfec = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta))) {
			$ls_sqlint = " AND codart >='" . $as_coddesde . "' AND codart <='" . $as_codhasta . "'";
		}

		if ((!empty ($ad_desde)) && (!empty ($ad_hasta))) {
			$ld_auxdesde = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_auxhasta = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			//$ls_sqlintfec=" AND fecmov >='". $ld_auxdesde ."' AND fecmov <='". $ld_auxhasta ."'";
		}
		if ($ai_ordenart == 0) {
			$ls_order = "codart";
		} else {
			$ls_order = "denart";
		}
		switch ($this->gestor) {
			case ("MYSQL") :
				$ls_sql = "SELECT codart FROM sim_dt_movimiento" .
				" WHERE codemp ='" . $as_codemp . "'" .
				"   AND fecmov >='" . $ld_auxdesde . "'" .
				"   AND fecmov <='" . $ld_auxhasta . "'" .
				"   AND CONCAT(promov,numdocori) NOT IN" .
				"       (SELECT CONCAT(promov,numdocori) FROM sim_dt_movimiento" .
				"         WHERE opeinv ='REV')" .
				$ls_sqlint .
				"  GROUP BY codart";
				break;

			case ("POSTGRE") :
				$ls_sql = "SELECT codart FROM sim_dt_movimiento" .
				" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND  codemp ='" . $as_codemp . "'" .
				"   AND fecmov >='" . $ld_auxdesde . "'" .
				"   AND fecmov <='" . $ld_auxhasta . "'" .
				"   AND (codart||numdocori) NOT IN" .
				"       (SELECT (codart || numdocori) FROM sim_dt_movimiento" .
				"         WHERE opeinv ='SAL')" .
				$ls_sqlint .
				"  GROUP BY codart";
				break;
		}
        // print $ls_sql;
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			print ($this->io_sql->message);
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_articulosmovimientos ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_articulosmovimientos

	function uf_select_promedio($as_codemp, $as_codart, $ad_desde, $ad_hasta, & $li_cosprom,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_promedio
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_codart     // codigo de articulo
		//  			       $ad_desde      // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta      // fecha de cierre del periodo de busqueda
		//  			       $li_cosprom    // costo promedio del articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de calcular los costos promedios de los articulos
		//					   en un periodo de tiempo
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 31/02/2006							Fecha de Ultima Modificaci�n: 31/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ld_fecdes = $this->io_funcion->uf_convertirdatetobd($ad_desde);
		$ld_fechas = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
						$ls_sql = "SELECT sim_dt_movimiento.*, " .
				"       (SELECT denart FROM sim_articulo" .
				"         WHERE codemp='" . $as_codemp . "'" .
				"           AND codart='" . $as_codart . "') AS denart," .
				"       (SELECT existencia FROM sim_articuloalmacen" .
				"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articuloalmacen',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
				"           AND codart='" . $as_codart . "' AND codalm=sim_dt_movimiento.codalm) AS exiart," .
				"       (SELECT denunimed FROM sim_unidadmedida,sim_articulo" .
				"         WHERE sim_articulo.codunimed=sim_unidadmedida.codunimed" .
				"           AND sim_articulo.codart='" . $as_codart . "') AS denunimed," .
				"       (SELECT cosart  FROM sim_dt_movimiento" .
				"         WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
				"           AND fecmov>='" . $ld_fecdes . "'" .
				"           AND fecmov<='" . $ld_fechas . "'" .
				"           AND codart='" . $as_codart . "'" .
				"           AND opeinv='ENT'" .
				"           AND (codprodoc='ORD' OR codprodoc='FAC')" .
				"           AND (codart || numdocori) NOT IN" .
				"               (SELECT (codart || numdocori) FROM sim_dt_movimiento" .
				"                 WHERE opeinv ='SAL') " .
				"                 ORDER BY fecmov DESC, nummov DESC LIMIT 1) as ultimo " .
				"  FROM sim_dt_movimiento " .
				" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "'" .
				"   AND fecmov>='" . $ld_fecdes . "'" .
				"   AND fecmov<='" . $ld_fechas . "'" .
				"   AND codart='" . $as_codart . "'" .
				"   AND opeinv='ENT'" .
				"   AND (codprodoc='ORD' OR codprodoc='FAC')" .
				"   AND (codart || numdocori) NOT IN" .
				"       (SELECT (codart || numdocori) FROM sim_dt_movimiento" .
				"         WHERE opeinv ='SAL')";
        //print  $ls_sql.'<br><br>';
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);

		if ($rs_data === false) {

			//print ($this->io_sql->message);
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_promedio ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$li_cantotart = 0;
				$li_mulcostot = 0;
				while ($row = $this->io_sql->fetch_row($rs_data)) {
					$li_canart = $row["canart"];
					$li_cosart = $row["cosart"];
					$li_mulcos = ($li_canart * $li_cosart);
					$li_cantotart = $li_cantotart + $li_canart;
					$li_mulcostot = $li_mulcostot + $li_mulcos;
				}
				$li_cosprom = ($li_mulcostot / $li_cantotart);
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_promedio

	function uf_sim_load_dt_contable($as_codemp, $as_cmpmov, $ad_feccmp) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function:   uf_sim_load_dt_contable
		//	           Access:   public
		//  		Arguments:   as_codemp  // codigo de empresa
		//  			         as_cmpmov  // comprobante de movimiento
		//  			         as_codcau  // codigo de causa de movimiento
		//  			         ad_feccmp  // fecha del comprobante
		//	         Returns :   Retorna un Booleano
		//    	 Description :   Funci�n que obtiene los detalles contables de un movimiento
		//         Creado por:   Ing. Luis Anibal Lang
		//   Fecha de Cracion:   09/06/2006						Fecha de Ultima Modificaci�n: 09/06/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT sim_dt_scg.*,sim_articulo.codart," .
		"      (SELECT denart FROM sim_articulo " .
		"        WHERE sim_articulo.codart=sim_dt_scg.codart) AS denart" .
		"  FROM sim_dt_scg,sim_articulo" .
		" WHERE sim_dt_scg.codart=sim_articulo.codart" .
		"   AND sim_dt_scg.codemp='" . $as_codemp . "'" .
		"   AND sim_dt_scg.codcmp='" . $as_cmpmov . "'" .
		"   AND sim_dt_scg.feccmp='" . $ad_feccmp . "'" .
		" ORDER BY denart,debhab";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_sim_load_dt_contable ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$this->ds_detcontable->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // fin function uf_sim_load_dt_contable

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////              Funciones del reporte de Articulos a Solicitar            ///////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_articulossolicitar($as_codemp, $as_coddesde, $as_codhasta, $ai_ordenart,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_articulosmovimientos
		//	           Access: public
		//  		Arguments: $as_codemp    // codigo de empresa
		//  			       $as_coddesde  // codigo de inicio del intervalo de articulos para la busqueda
		//  			       $as_codhasta  // codigo de cierre del intervalo de articulos para la busqueda
		//  			       $ai_ordenart  // parametro por el cual vamos a ordenar los resultados
		//						                obtenidos en la consulta   0-> Por codigo de articulo 1-> Por denominacion de articulo
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de los articulos que estan por debajo del punto de reoeden
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 12/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//MALA!!! una duda!

		$lb_valido = false;
		$ls_sqlint = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta))) {
			$ls_sqlint = " AND aa.codart >='" . $as_coddesde . "' AND aa.codart <='" . $as_codhasta . "'";
		}

		if ($ai_ordenart == 0) {
			$ls_order = "aa.codart";
		} else {
			$ls_order = "a.denart";
		}

		if( !empty($ls_tienda_desde) AND !empty($ls_tienda_hasta)){
			$ls_filtro_tienda = " AND aa.codtiend BETWEEN '".$ls_tienda_desde."' AND '".$ls_tienda_hasta."' ";
		}else{
			$ls_filtro_tienda = " AND aa.codtiend ilike '%".$_SESSION["ls_codtienda"]."%' ";
		}

		/*$ls_sql = "SELECT codart,denart,exiart,minart,reoart" .
		"  FROM sim_articulo" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articulo',$_SESSION["ls_codtienda"])." AND codemp ='" . $as_codemp . "'" .
		$ls_sqlint .
		" ORDER BY " . $ls_order . "";*/

		$ls_sql = "SELECT aa.codart, a.denart, sum(aa.existencia) as existencia, aa.codtiend, p.minart, p.reoart, t.dentie " .
		"  FROM sim_articulo a,sim_articuloalmacen aa, sfc_producto p, sfc_tienda t" .
		" WHERE aa.codart=a.codart AND aa.codart=p.codart AND aa.codtiend=p.codtiend AND a.codemp ='" . $as_codemp . "'" .
		" AND aa.codtiend = t.codtiend ". $ls_sqlint . $ls_filtro_tienda .
		" GROUP BY aa.codart, a.denart, aa.codtiend, p.minart, p.reoart, t.dentie ".
		" HAVING SUM(aa.existencia) <= p.reoart ".
		" ORDER BY " . $ls_order . "";

		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_articulosolicitar ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_articulossolicitar

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////             Funciones del  Listado de Articulos Parametrizado          ///////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_listadoarticulos($as_codemp, $as_coddesde, $as_codhasta, $ai_orden, $as_codalm, $as_codtipart, $as_codsigecof,$ls_tienda_desde,$ls_tienda_hasta) {
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
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de los articulos que estan por debajo del punto de reoeden
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 12/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sqlint = "";
		if ((!empty ($as_coddesde)) && (!empty ($as_codhasta)))
		 {
			$ls_sqlint = " AND sim_articulo.codart >='" . $as_coddesde . "'" .
			" AND sim_articulo.codart <='" . $as_codhasta . "'";
		}
		if (!empty ($as_codalm))
		{
			$ls_sqlint = $ls_sqlint . " AND sim_articuloalmacen.codalm='" . $as_codalm . "'";
		}
		if (!empty ($as_codtipart))
		{
			$ls_sqlint = $ls_sqlint . " AND sim_articulo.codtipart='" . $as_codtipart . "'";
		}
		/*if (!empty ($as_codsigecof))
		{
			$ls_sqlint = $ls_sqlint . " AND sim_articulo.codcatsig='" . $as_codsigecof . "'";
		}*/
		switch ($ai_orden) {
			case 0 :
				$ls_order = "sim_articulo.denart";
				break;
			case 1 :
				$ls_order = "sim_articuloalmacen.codalm";
				break;
			case 2 :
				$ls_order = "sim_articulo.codtipart";
				break;
			case 3 :
				$ls_order = "sim_articulo.codcatsig";
				break;
		}

		$ls_sql = "SELECT sim_articulo.codart,sim_articulo.denart,sim_articulo.codtipart,sim_articuloalmacen.codalm,sim_articulo.fecvenart," .
		" (SELECT dentipart FROM sim_tipoarticulo WHERE  sim_tipoarticulo.codtipart=sim_articulo.codtipart) as dentipart," .
		" (SELECT nomfisalm FROM sim_almacen WHERE sim_articuloalmacen.codalm=sim_almacen.codalm) as nomfisalm" .
		" FROM sim_articulo,sim_articuloalmacen" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_articuloalmacen',$_SESSION["ls_codtienda"])." AND sim_articulo.codart=sim_articuloalmacen.codart" .
		" AND sim_articulo.codemp ='" . $as_codemp . "'" .
		$ls_sqlint .
		" ORDER BY " . $ls_order . " ASC";
		//print $ls_sql;
		//exit;
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_articulosmovimientos ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_listadoarticulos

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////              Funciones del reporte de Cierre de Ordenes de Compra            ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_cierreordenes($as_codemp, $ad_desde, $ad_hasta, $ai_orden) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_cierreordenes
		//	           Access: public
		//  		Arguments: $as_codemp // codigo de empresa
		//  			       $ad_desde  // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta  // fecha de cierre del periodo de busqueda
		//  			       $ai_orden  // parametro por el cual vamos a ordenar los resultados
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de buscar los datos asociados a un cierre de ordendes de compra
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ld_fecdes = $this->io_funcion->uf_convertirdatetobd($ad_desde);
		$ld_fechas = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
		$ls_sql = "SELECT soc_ordencompra.numordcom,soc_ordencompra.cod_pro,soc_ordencompra.fecordcom," .
		"       (SELECT rpc_proveedor.nompro FROM rpc_proveedor" .
		"         WHERE soc_ordencompra.cod_pro=rpc_proveedor.cod_pro) as nompro" .
		"  FROM soc_ordencompra" .
		" WHERE soc_ordencompra.codemp='" . $as_codemp . "'" .
		"   AND (soc_ordencompra.estcondat='B' OR soc_ordencompra.estcondat='-')" .
		"   AND soc_ordencompra.estpenalm=1";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_cierreordenes ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			while ($row = $this->io_sql->fetch_row($rs_data)) {
				$ad_feccierre = "";
				$ls_numordcom = $row["numordcom"];
				$ls_codpro = $row["cod_pro"];
				$ld_fecordcom = $row["fecordcom"];
				$ls_nompro = $row["nompro"];
				$lb_valido = $this->uf_select_fechacierrecmp($as_codemp, $ls_numordcom, $ld_fecdes, $ld_fechas, $ai_orden, $ad_feccierre);
				if ($ad_feccierre == "") {
					$lb_valido = $this->uf_select_fechacierrerec($as_codemp, $ls_numordcom, $ls_codpro, $ld_fecdes, $ld_fechas, $ai_orden, $ad_feccierre);
					if ($lb_valido) {
						$li_parcial = 0;
					}
				} else {
					$li_parcial = 1;
				}
				if ($lb_valido) {
					$this->ds->insertRow("numordcom", $ls_numordcom);
					$this->ds->insertRow("fecordcom", $ld_fecordcom);
					$this->ds->insertRow("nompro", $ls_nompro);
					$this->ds->insertRow("feccierre", $ad_feccierre);
					$this->ds->insertRow("parcial", $li_parcial);

				}
			}
			if ($lb_valido) {
				if ($ai_orden == 0) {
					$this->ds->sortData("numordcom");
				} else {
					$this->ds->sortData("feccierre");
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_cierreordenes

	function uf_select_fechacierrecmp($as_codemp, $as_numordcom, $ad_fecdes, $ad_fechas, $ai_orden, & $ad_feccierre) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_fechacierrecmp
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numordcom  // numero de orden de compra
		//  			       $ad_fecdes     // fecha de inicio del periodo de busqueda
		//  			       $ad_fechas     // fecha de cierre del periodo de busqueda
		//  			       $ai_orden      // parametro por el cual vamos a ordenar los resultados
		//  			       $ad_feccierre  // fecha de cierre de la orden de compra
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de buscar los datos asociados a un cierre de ordendes de compra
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT fecha" .
		"  FROM  spg_dt_cmp" .
		" WHERE codemp='" . $as_codemp . "'" .
		"   AND procede='SPGCMP'" .
		"   AND procede_doc='SOCCOC'" .
		"   AND operacion='CS'" .
		"   AND documento='" . $as_numordcom . "'" .
		"   AND fecha<='" . $ad_fechas . "'" .
		"   AND fecha>='" . $ad_fecdes . "'" .
		" GROUP BY documento,fecha";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_fechacierrecmp ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($row = $this->io_sql->fetch_row($rs_data)) {
				$ad_feccierre = $row["fecha"];
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_fechacierrecmp

	function uf_select_fechacierrerec($as_codemp, $as_numordcom, $as_codpro, $ad_fecdes, $ad_fechas, $ai_orden, & $ad_feccierre) {
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
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de buscar los datos asociados a un cierre de ordendes de compra
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT fecrec" .
		"  FROM sim_recepcion" .
		" WHERE codemp='" . $as_codemp . "'" .
		"   AND numordcom='" . $as_numordcom . "'" .
		"   AND cod_pro='" . $as_codpro . "'" .
		"   AND fecrec<='" . $ad_fechas . "'" .
		"   AND fecrec>='" . $ad_fecdes . "'";

		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_fechacierrerec ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		} else {
			if ($row = $this->io_sql->fetch_row($rs_data)) {
				$ad_feccierre = $row["fecrec"];
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_fechacierrerec

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////             Funciones del reporte de Valoracion de Toma de Inventario          ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_dt_valtoma($as_codemp, $as_numtom,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_dt_valtoma
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numtom     // numero de toma de inventario
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de los articulos que pertenecen a una toma de inventario
		//				       referente al maestro indicado incluyendo los costos promedios.
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 13/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT sim_dt_toma.*," .
		"       (SELECT denart FROM sim_articulo " .
		"         WHERE sim_dt_toma.codart=sim_articulo.codart) AS denart," .
		"       (SELECT denunimed FROM sim_unidadmedida " .
		"         WHERE sim_unidadmedida.codunimed=sim_articulo.codunimed) AS denunimed," .
		"       (SELECT cosproart FROM sim_articulo " .
		"         WHERE sim_dt_toma.codart=sim_articulo.codart) AS cospro" .
		"  FROM sim_dt_toma,sim_articulo" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_toma',$_SESSION["ls_codtienda"])." AND sim_dt_toma.codemp='" . $as_codemp . "'" .
		"   AND sim_dt_toma.numtom='" . $as_numtom . "'" .
		"   AND sim_dt_toma.codart=sim_articulo.codart";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_dt_valtoma ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds_detalle->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_dt_toma

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////                 Funciones de los Ajustes  de Inventario                  /////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_select_ajuste($as_codemp, $ad_desde, $ad_hasta,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_ajuste
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $as_numtom     // numero de toma de inventario
		//  			       $ad_desde      // fecha de inicio del periodo de busqueda
		//  			       $ad_hasta      // fecha de cierre del periodo de busqueda
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de  una toma de inventario que han sido procesadas
		//         Creado por: Ing. Luis Anibal Lang
		//   Fecha de Cracion: 15/09/2006							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = "SELECT sim_toma.*," .
		"         (SELECT nomfisalm 
		           FROM   sim_almacen " .
		"          WHERE  sim_toma.codalm=sim_almacen.codalm) AS nomfisalm" .
		"  FROM sim_toma,sim_almacen" .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_toma',$_SESSION["ls_codtienda"])." AND sim_toma.codemp='" . $as_codemp . "'" .
		"   AND sim_toma.estpro=1";
		if (($ad_desde != "") && ($ad_hasta != "")) {
			$ld_fecdes = $this->io_funcion->uf_convertirdatetobd($ad_desde);
			$ld_fechas = $this->io_funcion->uf_convertirdatetobd($ad_hasta);
			$ls_sql = $ls_sql . " AND sim_toma.fectom<='" . $ld_fechas . "'" .
			" AND sim_toma.fectom>='" . $ld_fecdes . "'";
		}
		//print $ls_sql."<br>";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_ajuste ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($li_numrows > 0) {
				$data = $this->io_sql->obtener_datos($rs_data);
				$arrcols = array_keys($data);
				$totcol = count($arrcols);
				$this->ds->data = $data;
				$lb_valido = true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} //fin  function uf_select_toma
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_sim_acta_recepcion_bienes($as_codemp, $as_numordcom,$ls_tienda_desde,$ls_tienda_hasta) {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_sim_acta_recepcion_bienes
		//	           Access: public
		//  		Arguments: $as_codemp     // codigo de empresa
		//  			       $ls_numordcom     // numero de orden de compra
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de las recepciones de inventario
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 06/02/2007							Fecha de Ultima Modificaci�n:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = " SELECT * " .
		" FROM  sim_dt_movimiento " .
		" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "' AND numdoc='" . $as_numordcom . "' AND opeinv='ENT' " .
		" ORDER BY nummov DESC LIMIT 1 ";

		$rs_data1 = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data1);
		if ($rs_data1 === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_sim_acta_recepcion_bienes ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			if ($row = $this->io_sql->fetch_row($rs_data1)) {
				$ls_nummov = $row["nummov"];
				$ldt_fecmov = $row["fecmov"];
				$ls_sql = " SELECT * " .
				" FROM sim_dt_movimiento " .
				" WHERE ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sim_dt_movimiento',$_SESSION["ls_codtienda"])." AND codemp='" . $as_codemp . "' AND " .
				"   	  nummov='" . $ls_nummov . "' AND " .
				"		  opeinv='ENT' AND " .
				" 		  fecmov='" . $ldt_fecmov . "' ";

				$rs_data = $this->io_sql->select($ls_sql);
				$li_numrows = $this->io_sql->num_rows($rs_data);
				if ($rs_data === false) {
					$this->io_msg->message("CLASE->Report METODO->uf_sim_acta_recepcion_bienes ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido = false;
				} else {
					while ($row = $this->io_sql->fetch_row($rs_data)) {
						$ls_numordcom = $row["numdoc"];
						$ls_codalm = $row["codalm"];
						$ls_codart = $row["codart"];
						$ls_canart = $row["canart"];
						$ls_preuniart = $row["cosart"];
						$ldt_fecmov = $row["fecmov"];

						$ls_cod_pro = "";//$row["cod_pro"];
                        $ls_nompro = "";
						$ls_cedrep = "";
						$ls_nomreppro = "";

						$lb_valido = $this->uf_select_proveedor($as_codemp, $ls_cod_pro, $ls_nompro,  $ls_cedrep, $ls_nomreppro, $ls_numordcom);

						if ($lb_valido) {
							$ls_denart = "";
							$lb_valido = $this->uf_select_denominacion_articulo($as_codemp, $ls_codart, $ls_denart,$ls_tienda_desde,$ls_tienda_hasta);
							if ($lb_valido) {
								$ls_nomresalm = "";
								$lb_valido = $this->uf_select_encargado_almacen($as_codemp, $ls_codalm, $ls_nomresalm,$ls_tienda_desde,$ls_tienda_hasta);
								if ($lb_valido) {
									$ls_montotart = 0;
									$lb_valido = $this->uf_select_monto_total($as_codemp, $ls_numordcom, $ls_montotart,$ls_tienda_desde,$ls_tienda_hasta);
									if ($lb_valido) {
										$ls_ordfac = substr($ls_numordcom, 0, 1);
										if ($ls_ordfac == 'F') {
											$ls_estpro = 1;
										} else {
											$ls_estpro = 0;
										}
										$this->dts_reporte->insertRow("numordcom", $ls_numordcom);
										$this->dts_reporte->insertRow("cod_pro", $ls_cod_pro);
										$this->dts_reporte->insertRow("codalm", $ls_codalm);
										$this->dts_reporte->insertRow("nomresalm", $ls_nomresalm);
										$this->dts_reporte->insertRow("codart", $ls_codart);
										$this->dts_reporte->insertRow("canart", $ls_canart);
										$this->dts_reporte->insertRow("preuniart", $ls_preuniart);
										$this->dts_reporte->insertRow("nompro", $ls_nompro);
										$this->dts_reporte->insertRow("cedrep", $ls_cedrep);
										$this->dts_reporte->insertRow("nomreppro", $ls_nomreppro);
										$this->dts_reporte->insertRow("denart", $ls_denart);
										$this->dts_reporte->insertRow("estpro", $ls_estpro);
										$this->dts_reporte->insertRow("fecrec", $ldt_fecmov);
										$this->dts_reporte->insertRow("montotart", $ls_montotart);
										$lb_valido = true;
									} //if
								} //if
							} //if
						} //if
					} //while
				} //else
			} //if
			$this->io_sql->free_result($rs_data);
		} //else
		return $lb_valido;
	} //fin  function uf_select_toma
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_select_proveedor($as_codemp, & $as_cod_pro, & $as_nompro, & $as_cedrep, & $as_nomreppro, $as_numordcom) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_proveedor
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_cod_pro     //  codigo del proveedor (referencia)
		//                     $as_nompro      //  nombre del proveedor (referencia)
		//                     $as_cedrep      //  cedula del representante del proveedor (referencia)
		//                     $as_nomreppro   // nombre del representante del proveedor
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda del proveedor segun el codigo
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 06/02/2007							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = " SELECT rpc_proveedor.cod_pro,rpc_proveedor.nompro,rpc_proveedor.cedrep,rpc_proveedor.nomreppro " .
		" FROM  soc_ordencompra,rpc_proveedor " .
		" WHERE soc_ordencompra.codemp='" . $as_codemp . "' AND " .
		"       soc_ordencompra.numordcom='" . $as_numordcom . "' AND " .
		"       soc_ordencompra.estcondat='B' AND " .
		"       soc_ordencompra.cod_pro=rpc_proveedor.cod_pro ";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report METODO->uf_select_proveedor ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			while ($row = $this->io_sql->fetch_row($rs_data)) {
				$as_cod_pro = $row["cod_pro"];
				$as_nompro = $row["nompro"];
				$as_cedrep = $row["cedrep"];
				$as_nomreppro = $row["nomreppro"];
				$lb_valido = true;
			} //while
			$this->io_sql->free_result($rs_data);
		} //else
		return $lb_valido;
	} //fin uf_select_proveedor
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_select_denominacion_articulo($as_codemp, $as_codart, & $as_denart,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_denominacion_articulo
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_codart     //  codigo del proveedor (referencia)
		//                     $as_denart      //  nombre del proveedor (referencia)
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda de la denominacion del articulo
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 08/02/2007							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = " SELECT denart FROM sim_articulo WHERE codemp='" . $as_codemp . "' AND codart='" . $as_codart . "' ";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_denominacion_articulo ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			while ($row = $this->io_sql->fetch_row($rs_data)) {
				$as_denart = $row["denart"];
				$lb_valido = true;
			} //while
			$this->io_sql->free_result($rs_data);
		} //else
		return $lb_valido;
	} //fin uf_select_denominacion_articulo
	//---------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_select_encargado_almacen($as_codemp, $as_codalm, $as_nomresalm,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_encargado_almacen
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_codalm     //  codigo del almacen
		//                     $as_nomresalm      //  nombre del encargado del almacen (referencia)
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda del encargado del almacen
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 08/02/2007							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = " SELECT nomresalm FROM sim_almacen WHERE codemp='" . $as_codemp . "' AND codalm='" . $as_codalm . "' ";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_encargado_almacen ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			while ($row = $this->io_sql->fetch_row($rs_data)) {
				$as_nomresalm = $row["nomresalm"];
				$lb_valido = true;
			} //while
			$this->io_sql->free_result($rs_data);
		} //else
		return $lb_valido;
	} //fin uf_select_encargado_almacen
	//---------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_select_monto_total($as_codemp, $as_numordcom, & $as_monsubtot,$ls_tienda_desde,$ls_tienda_hasta) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_monto_total
		//	           Access: public
		//  		Arguments: $as_codemp      //  codigo de empresa
		//  			       $as_numordcom     //  numero de la orden de  compra
		//                     $as_montotart      //  monto total del articulo (referencia)
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se encarga de realizar la busqueda del encargado del almacen
		//         Creado por: Ing. Yozelin Barragan.
		//   Fecha de Cracion: 08/02/2007							Fecha de Ultima Modificaci�n:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;
		$ls_sql = " SELECT monsubtot  " .
		" FROM   soc_ordencompra " .
		" WHERE  codemp='" . $as_codemp . "' AND numordcom='" . $as_numordcom . "' AND  estcondat='B' ";
		$rs_data = $this->io_sql->select($ls_sql);
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($rs_data === false) {
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_monto_total ERROR->" . $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		} else {
			while ($row = $this->io_sql->fetch_row($rs_data)) {
				$as_monsubtot = $row["monsubtot"];
				$lb_valido = true;
			} //while
			$this->io_sql->free_result($rs_data);
		} //else
		return $lb_valido;
	} //fin uf_select_monto_total
	//---------------------------------------------------------------------------------------------------------------------------------
} //fin  class sigesp_sim_class_report
?>
