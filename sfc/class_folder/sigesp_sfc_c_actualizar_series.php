<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_actualizar_series
 // Autor:       - Ing. Luis A. Alvarez
 // Descripcion: - Clase que realiza los procesos basicos para la actualizaci�n de Series
 //					de Facturas
 // Fecha:       - 13/03/2008
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_actualizar_series
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_actualizar_series()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /* se toma la funcion de convertir cadena a caracteres*/
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	$this->io_database  = $_SESSION["ls_database"];
	$this->io_gestor    = $_SESSION["ls_gestor"];

	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();

	require_once("../shared/class_folder/class_datastore.php");
	$this->io_datastore1= new class_datastore();
}

function uf_buscar_facturas($la_codemp, $la_fdesde, $la_fhasta,$la_tienda,$la_caja, &$la_p, &$la_filasmod, &$la_lobjectordenes)
{
	$la_valido= true;

	$ls_cadenadf="SELECT f.numfac,f.fecemi,f.codemp,f.monto,f.codcli,f.numcon,cl.razcli " .
			"FROM sfc_factura f, sfc_cliente cl " .
			"WHERE f.codemp ='".$la_codemp."' AND f.numfac >='".$la_fdesde."' AND  f.numfac <='".$la_fhasta."' AND f.codcli= cl.codcli " .
			"AND f.codtiend='".$la_tienda."' AND f.cod_caja='".$la_caja."' ".
			"order by f.numfac asc ";

	//print $ls_cadenadf."<br>";

	$arr_transf=$this->io_sql->select($ls_cadenadf);

	if($row=$this->io_sql->fetch_row($arr_transf))
	{
		//$lb_procesar=1;
		$la_orduniadm=$this->io_sql->obtener_datos($arr_transf);
		$this->io_datastore1->data=$la_orduniadm;
		$totrow1=$this->io_datastore1->getRowCount("codemp");

		$error = false;
		for($li_j=1;$li_j<=$totrow1;$li_j++)
		{

			if ($li_j > 1){
				$fecanterior= $this->io_datastore1->getValue("fecemi",$li_j-1);
			}else{
				$fecanterior = $this->io_datastore1->getValue("fecemi",1);
			}

			$ls_fecemi=$this->io_datastore1->getValue("fecemi",$li_j);

			if($fecanterior > $ls_fecemi){
				$error = true;
				$li_j = $totrow1;
				//print $ls_numfac;
			}else{
				$error = false;
			}
		} // For Para verificar si ha errores en fechas

		$la_filasmod = 0;
		if($error){
			$class_caja = "sin-bordeAzulClaro";
			$celdas = "celdas-azules2";
			$dtp = " datepicker=\"true\" ";
			$la_filasmod++;
		}else{
			$class_caja = "sin-borde";
			$celdas = "celdas-blancas";
			$dtp = "";
			$la_filasmod = 0;
		}

		//print $totrow1."<br>";
		//print_r($this->io_datastore1);

		$la_p=1;
		for($li_j=1;$li_j<=$totrow1;$li_j++){

			$ls_numfac=$this->io_datastore1->getValue("numfac",$li_j);
			$ls_numcon=$this->io_datastore1->getValue("numcon",$li_j);
			$ls_codemp=$this->io_datastore1->getValue("codemp",$li_j);
			$ls_fecemi=$this->io_datastore1->getValue("fecemi",$li_j);
			$ls_fecvista = $this->io_funcion->uf_convertirfecmostrar($ls_fecemi);
			$ls_monto=$this->io_datastore1->getValue("monto",$li_j);
			$ls_codcli=$this->io_datastore1->getValue("codcli",$li_j);
			$ls_razcli=$this->io_datastore1->getValue("razcli",$li_j);

				$la_lobjectordenes[$la_p][1]="<input name=txtfecemi".$la_p." type=text id=txtfecemi".$la_p." class=".$class_caja." value='".$ls_fecvista."'  ".$dtp." style= text-align:center size=11 readonly>";

				$la_lobjectordenes[$la_p][2]="<input name=txtnumfac".$la_p." type=text id=txtnumfac".$la_p." class=".$class_caja." value='".$ls_numfac."' style= text-align:center size=30 readonly>";

				$la_lobjectordenes[$la_p][3]="<input name=txtnumcon".$la_p." type=text id=txtnumcon".$la_p." class=".$class_caja." value='".$ls_numcon."' style= text-align:center size=30 readonly>";

				$la_lobjectordenes[$la_p][4]="<input name=txtrazcli".$la_p." type=text id=txtrazcli".$la_p." class=".$class_caja." value='".$ls_razcli."' style= text-align:left size=60 readonly>";

				$la_lobjectordenes[$la_p][5]="<input name=txtmonto".$la_p." type=text id=txtmonto".$la_p." class=".$class_caja." value='".$ls_monto."' style= text-align:right size=15 readonly>";

				$la_lobjectordenes[$la_p][6]=$celdas;

				$la_p++;
				//$li_filasordenes++;

			}///for
		}//if
		else
		{
			$la_valido = false;
			$this->io_msg->message("No existen FACTURAS para Procesar!!");
		}
}

function uf_select_table($as_tabla)
{
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //	     Function: uf_select_table
   //		   Access: public
   //		Argumento: $as_tabla   // nombre de la tabla
   //	  Description: deternima si existe una columna en una tabla
   //	   Creado Por: Ing. Wilmer Brice�o
   //  Fecha Creaci�n: 06/07/2006
   //  Modificado Por: Ing. Luis Anibal Lang
   //    Fecha Modif.: 27/10/2006
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   $lb_existe = false;
   switch ($this->io_gestor)
   {
		case "MYSQL":
		   $ls_sql= " SELECT * FROM ".
					" INFORMATION_SCHEMA.TABLES ".
					" WHERE TABLE_SCHEMA='".$this->io_database."' AND (UPPER(TABLE_NAME)=UPPER('".$as_tabla."'))";
		break;
		case "POSTGRE":
		   $ls_sql= " SELECT * FROM ".
					" INFORMATION_SCHEMA.TABLES ".
					" WHERE table_catalog='".$this->io_database."' AND (UPPER(table_name)=UPPER('".$as_tabla."'))";
		break;
   }

   $rs_data=$this->io_sql->select($ls_sql);
   if($rs_data===false)
   {
	  $lb_existe = false;
	  $this->io_msg->message("ERROR en uf_select_table()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
   else
   {
	  if ($row=$this->io_sql->fetch_row($rs_data)){
	  	$lb_existe=true;
	  }
	  //print $row=$this->io_sql->fetch_row($rs_data)."<br>";
	  $this->io_sql->free_result($rs_data);
   }
   return $lb_existe;
} // end function uf_select_table


function uf_drop_table($as_tabla){

	$ls_sql= " DROP TABLE ".$as_tabla."";
	$rs_data=$this->io_sql->select($ls_sql);
	$lb_drop=false;

	if($rs_data===false)
	{
		$this->io_msg->message("ERROR en uf_drop_table()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}else{
		$lb_drop=true;
	}
	return $lb_drop;
} // end function uf_drop_table

function uf_crear_table($as_tmp, $as_tabla, $as_key){

	$ls_sql= " CREATE TABLE ".$as_tmp." AS SELECT * FROM ".$as_tabla." WHERE ".$as_key."='99999999999999999999'";
	//print $ls_sql.'<br>';
	$rs_data=$this->io_sql->select($ls_sql);
	$lb_crear=false;

	if($rs_data===false)
	{
		$this->io_msg->message("ERROR en uf_crear_table()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}else{
		$lb_crear=true;
	}
	return $lb_crear;
} // end function uf_crear_table

function uf_crear_tmp(){

	$this->io_sql->begin_transaction();

	$validotemp = false;
	$validotemp = $this->uf_select_table('tmp_factura');

	if($validotemp){
		$validotemp = $this->uf_drop_table('tmp_factura');
		if($validotemp){
			//$is_msg->message ("Elimin� tmp_factura!!");
		}
	}else{
		//$is_msg->message ("No exite tmp_factura!!");
	}

	// Crear tmp_factura
	$validotemp = $this->uf_crear_table('tmp_factura','sfc_factura','numfac');
	if($validotemp){
		//$is_msg->message ("Se cre� la tabla tmp_factura!!");
	}else{
		//$is_msg->message ("Error, creando la tabla Temporal!!");
	}

	$validodet = false;
	//verifico si existe tmp_detfactura
	$validodet = $this->uf_select_table('tmp_detfactura');
	if($validodet){
		$this->uf_drop_table('tmp_detfactura');
		$this->uf_drop_table('aux_detfactura');
	}

	//crear tmp_detfactura
	$validodet= $this->uf_crear_table('tmp_detfactura','sfc_detfactura','numfac');
	if(!$validodet){
		$this->uf_drop_table('tmp_factura');
	}// crea tmp_detfactura
	else{
		$validodet= $this->uf_crear_table('aux_detfactura','sfc_detfactura','numfac');
	}

	$validopago = false;
	//verifico si existe tmp_instpago
	$validopago = $this->uf_select_table('tmp_instpago');
	if($validopago){
		$this->uf_drop_table('tmp_instpago');
		$this->uf_drop_table('aux_instpago');
	}

	//crear tmp_instpago
	$validopago= $this->uf_crear_table('tmp_instpago','sfc_instpago','numfac');
	if(!$validopago){
		$this->uf_drop_table('aux_detfactura');
		$this->uf_drop_table('tmp_detfactura');
		$this->uf_drop_table('tmp_factura');
	}// crea tmp_instpago
	else{
		$validopago= $this->uf_crear_table('aux_instpago','sfc_instpago','numfac');
	}

	$validonota = false;
	//verifico si existe tmp_nota
	$validonota = $this->uf_select_table('tmp_nota');
	if($validonota){
		$this->uf_drop_table('tmp_nota');
		$this->uf_drop_table('aux_nota');
	}

	//crear tmp_nota
	$validonota= $this->uf_crear_table('tmp_nota','sfc_nota','nro_documento');
	if(!$validonota){
		$this->uf_drop_table('aux_instpago');
		$this->uf_drop_table('tmp_instpago');
		$this->uf_drop_table('aux_detfactura');
		$this->uf_drop_table('tmp_detfactura');
		$this->uf_drop_table('tmp_factura');
	}// crear tmp_nota
	else{
	$validonota= $this->uf_crear_table('aux_nota','sfc_nota','nro_documento');
	}

	$validodev = false;
	//verifico si existe tmp_devolucion
	$validodev = $this->uf_select_table('tmp_devolucion');
	if($validodev){
		$this->uf_drop_table('tmp_devolucion');
		$this->uf_drop_table('aux_devolucion');
	}

	//crear tmp_devolucion
	$validodev= $this->uf_crear_table('tmp_devolucion','sfc_devolucion','numfac');
	if(!$validodev){
		$this->uf_drop_table('tmp_nota');
		$this->uf_drop_table('aux_nota');
		$this->uf_drop_table('tmp_instpago');
		$this->uf_drop_table('aux_instpago');
		$this->uf_drop_table('tmp_detfactura');
		$this->uf_drop_table('aux_detfactura');
		$this->uf_drop_table('tmp_factura');
	}else{
		$validodev= $this->uf_crear_table('aux_devolucion','sfc_devolucion','numfac');
		//$validodev= $this->uf_crear_table('tmp_detdevolucion','sfc_detdevolucion','coddev');
		$validodev= $this->uf_crear_table('aux_detdevolucion','sfc_detdevolucion','coddev');
	}

	//crear tmp_facretencion
	$validofacret= $this->uf_crear_table('tmp_facturaretencion','sfc_facturaretencion','numfac');
	if(!$validofacret){
		$this->uf_drop_table('tmp_nota');
		$this->uf_drop_table('aux_nota');
		$this->uf_drop_table('tmp_instpago');
		$this->uf_drop_table('aux_instpago');
		$this->uf_drop_table('tmp_detfactura');
		$this->uf_drop_table('aux_detfactura');
		$this->uf_drop_table('tmp_factura');
		$this->uf_drop_table('aux_detdevolucion');
		$this->uf_drop_table('aux_devolucion');
	}else{
		$validofacret= $this->uf_crear_table('aux_facturaretencion','sfc_facturaretencion','numfac');
	}

	//crear tmp_dt_cobrocliente
	$validodtcobro= $this->uf_crear_table('tmp_dt_cobrocliente','sfc_dt_cobrocliente','numfac');
	if(!$validodtcobro){
		$this->uf_drop_table('tmp_facturaretencion');
		$this->uf_drop_table('aux_facturaretencion');
		$this->uf_drop_table('tmp_nota');
		$this->uf_drop_table('aux_nota');
		$this->uf_drop_table('tmp_instpago');
		$this->uf_drop_table('aux_instpago');
		$this->uf_drop_table('tmp_detfactura');
		$this->uf_drop_table('aux_detfactura');
		$this->uf_drop_table('tmp_factura');
		$this->uf_drop_table('aux_detdevolucion');
		$this->uf_drop_table('aux_devolucion');
	}else{
		$validodtcobro= $this->uf_crear_table('aux_dt_cobrocliente','sfc_dt_cobrocliente','numfac');
	}

	if($validotemp and $validodet and $validopago and $validonota and $validodev and $validofacret and $validodtcobro){
		$this->io_sql->commit();
		return true;
	}else{
		$this->io_msg->message("Error en uf_crear_temp()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$this->io_sql->rollback();
		return false;
	}
} // end CREAR TEMPORALES

function uf_borrar_tmp(){
	$this->uf_drop_table('tmp_dt_cobrocliente');
	$this->uf_drop_table('aux_dt_cobrocliente');
	$this->uf_drop_table('aux_facturaretencion');
	$this->uf_drop_table('tmp_facturaretencion');
	$this->uf_drop_table('aux_detdevolucion');
	$this->uf_drop_table('tmp_nota');
	$this->uf_drop_table('aux_nota');
	$this->uf_drop_table('tmp_devolucion');
	$this->uf_drop_table('aux_devolucion');
	$this->uf_drop_table('tmp_instpago');
	$this->uf_drop_table('aux_instpago');
	$this->uf_drop_table('tmp_detfactura');
	$this->uf_drop_table('aux_detfactura');
	$this->uf_drop_table('tmp_factura');
}// end uf_crear_tmp

}// Fin Clase