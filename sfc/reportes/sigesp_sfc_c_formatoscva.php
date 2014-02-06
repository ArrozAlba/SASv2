<?php

class sigesp_sfc_c_formatoscva
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $io_sql_bd;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_formatoscva($con)
{
	require_once ("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");

	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();

	$this->io_sql= new class_sql($con);
	$this->datoemp=$_SESSION["la_empresa"];
	$this->io_database  = $_SESSION["ls_database"];
	$this->io_gestor    = $_SESSION["ls_gestor"];

	require_once("../../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();

	require_once("../../shared/class_folder/class_datastore.php");
	$this->io_datastore1= new class_datastore();

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



function borrar_tabla($as_tabla){


	$ls_sql= " DROP TABLE ".$as_tabla."";
	$rs_data=$this->io_sql->select($ls_sql);
	$lb_borrar=false;

	if($rs_data===false)
	{
		$this->io_msg->message("ERROR en uf_drop_table()");
	}else{
		$lb_borrar=true;
	}
	return $lb_borrar;
}

function crear_tabla(){

	$existe = false;
	$existe = $this->uf_select_table('temporalcoloca');

	if($existe)
	{
		$lb_borro=$this->borrar_tabla('temporalcoloca');
		if ($lb_borro)
		$this->io_msg->message("Temporal borrada");
		$ls_sqlc="create table temporalcoloca " .
							"(dencla character varying(225),den_sun character varying(225),codpro character varying(25)," .
							"denpro character varying(225),deunimed character varying(100),cantf double precision Default 0," .
							"preprof double precision Default 0,ventaf double precision Default 0,cantb double precision Default 0," .
							"preprob double precision Default 0,ventab double precision Default 0,cantc double precision Default 0," .
							"preproc double precision Default 0,ventac double precision Default 0,cants double precision Default 0," .
							"prepros double precision Default 0,ventas double precision Default 0," .
							"canto double precision Default 0,preproo double precision Default 0,ventao double precision Default 0)";


		$rs_datac=$this->io_sql->execute($ls_sqlc);

	}
	else
	{
		$ls_sqlc="create table temporalcoloca " .
							"(dencla character varying(225),den_sun character varying(225),codpro character varying(25)," .
							"denpro character varying(225),deunimed character varying(100),cantf double precision Default 0," .
							"preprof double precision Default 0,ventaf double precision Default 0,cantb double precision Default 0," .
							"preprob double precision Default 0,ventab double precision Default 0,cantc double precision Default 0," .
							"preproc double precision Default 0,ventac double precision Default 0,cants double precision Default 0," .
							"prepros double precision Default 0,ventas double precision Default 0," .
							"canto double precision Default 0,preproo double precision Default 0,ventao double precision Default 0)";


		$rs_datac=$this->io_sql->execute($ls_sqlc);
	}


	if($rs_datac===false)
	{
		$lb_creotabla=false;
		$this->io_msg->message("ERROR en uf_crear_table()");

	}else
	{
		$lb_creotabla=true;
		$this->io_msg->message("Creo Temporal");
	}
	return $lb_creotabla;
}

function uf_select_ventas_x_lineas($as_fechadesde,$as_fechahasta)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          M�todo:  uf_select_ventas_x_lineas
//	          Access:  public
//          Arguments
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.
//       Description:  /*.
//     Elaborado Por:  Ing. Zulheymar Rodriguez
// Fecha de Creaci�n:  26/06/2009
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $this->io_datastore_ventas= new class_datastore();
	$la_datemp=$_SESSION["la_empresa"];
	$ls_codemp=$la_datemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	
	$ls_sql="SELECT cla.dencla,sub.den_sub,a.denart,um.denunimed,".
	" ROUND(CAST(SUM((((df.canpro*df.prepro)*df.porimp/100))+(df.canpro*df.prepro))AS NUMERIC),2) as total_ventas ".
	" FROM sfc_factura f,sfc_detfactura df,sfc_producto pro,sfc_clasificacion cla,sfc_subclasificacion sub, ".
	" sim_articulo a, sim_unidadmedida um WHERE f.codemp=df.codemp AND f.numfac=df.numfac AND f.codemp=pro.codemp AND ".
	" df.codart=pro.codart AND a.cod_sub=sub.cod_sub AND a.codcla=sub.codcla AND ".
	" cla.codcla=sub.codcla AND f.codemp=a.codemp  AND df.codart=a.codart AND df.codemp=a.codemp  AND a.codart=a.codart ".
	" AND pro.codemp=a.codemp  AND a.codunimed=um.codunimed  GROUP BY cla.dencla,sub.den_sub,a.denart,um.denunimed ".
	" ORDER BY cla.dencla,sub.den_sub";
	/*print $ls_sql;
	exit();*/

   $rs_data = $this->io_sql->select($ls_sql);
   //var_dump($rs_data);
   if ($rs_data===false)
      {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_SFC_C_FORMATOSCVA; METODO->uf_select_ventas_x_lineas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		//print $li_numrows.'<BR>';
		if ($li_numrows>0)
		   {
		     $datos=$this->io_sql->obtener_datos($rs_data);
		     $this->io_datastore_ventas->data=$datos;
			 $lb_valido=true;
			 $this->io_sql->free_result($rs_data);
		   }
		else
		   {
		     $lb_valido=false;
		   }
	 }
return $lb_valido;
}


function uf_select_colocacionlineas($ls_fechadesde,$ls_fechahasta)
{

	$ls_sql= "SELECT cla.dencla,sub.den_sub,pro.denpro,SUM(d.canpro) as cantidad,u.denunimed ".
	" FROM sfc_factura f,sfc_detfactura d,sfc_producto pro,sfc_clasificacion cla,sfc_subclasificacion sub,".
	" sim_articulo a, sim_unidadmedida u ".
	" WHERE  f.codemp=d.codemp AND f.numfac=d.numfac AND f.codemp=pro.codemp AND d.codpro=pro.codpro AND ".
	" pro.cod_sub=sub.cod_sub AND pro.codcla=sub.codcla AND cla.codcla=sub.codcla  ".
	" AND f.codemp=a.codemp AND f.codtiend=a.codtiend AND ".
	" d.codpro=a.codart AND d.codemp=a.codemp AND d.codtiend=a.codtiend AND ".
	" pro.codpro=a.codart AND pro.codemp=a.codemp AND a.codtiend=pro.codtiend AND ".
	" a.codunimed=u.codunimed AND a.codtiend=u.codtiend AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A')" .
	" AND (f.fecemi>='2009-04-30' AND f.fecemi<='2009-05-07') ".
	" GROUP BY cla.dencla,sub.den_sub,pro.denpro,u.denunimed ORDER BY cla.dencla,sub.den_sub";

	/*$ls_sql= "SELECT cla.dencla,sub.den_sub,pro.denpro,SUM(d.canpro) as cantidad,u.denunimed ".
	" FROM sfc_factura f,sfc_detfactura d,sfc_producto pro,sfc_clasificacion cla,sfc_subclasificacion sub,".
	" sim_articulo a, sim_unidadmedida u ".
	" WHERE  f.codemp=d.codemp AND f.numfac=d.numfac AND f.codemp=pro.codemp AND d.codpro=pro.codpro AND ".
	" pro.cod_sub=sub.cod_sub AND pro.codcla=sub.codcla AND cla.codcla=sub.codcla  ".
	" AND f.codemp=a.codemp AND f.codtiend=a.codtiend AND ".
	" d.codpro=a.codart AND d.codemp=a.codemp AND d.codtiend=a.codtiend AND ".
	" pro.codpro=a.codart AND pro.codemp=a.codemp AND a.codtiend=pro.codtiend AND ".
	" a.codunimed=u.codunimed AND a.codtiend=u.codtiend AND d.numfac IN (SELECT numfac FROM sfc_factura where estfaccon<>'A')" .
	" AND (f.fecemi>='".$ls_fechadesde."' AND f.fecemi<='".$ls_fechahasta."') ".
	" GROUP BY cla.dencla,sub.den_sub,pro.denpro,u.denunimed ORDER BY cla.dencla,sub.den_sub";*/


	$rs_resultado=$this->io_sql->select($ls_sql);


   if ($rs_resultado===false)
      {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->sigesp_sfc_c_formatos_cva; uf_select_colocacionlineas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	     $li_rows=$this->io_sql->num_rows($rs_resultado);

		if ($li_rows>0)
		   {
		     $datos=$this->io_sql->obtener_datos($rs_resultado);
		     $this->io_datastore1->data=$datos;
			 $lb_valido=true;
			 $this->io_sql->free_result($rs_resultado);
		   }
		else
		   {
		     $lb_valido=false;
		   }
	 }
return $lb_valido;
}




}// Fin Clase
?>