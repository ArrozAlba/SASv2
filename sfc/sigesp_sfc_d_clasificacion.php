<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Clasificacion</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<style type="text/css">
<!--
.style6 {color: #000000}
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="537" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="241" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_clasificacion.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
	{

			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];

	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("class_folder/sigesp_sfc_c_clasificacion.php");
	require_once("class_folder/sigesp_sfc_c_subclasificacion.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	$io_clasificacion = new sigesp_sfc_c_clasificacion();
	$io_subclasificacion = new sigesp_sfc_c_subclasificacion();
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$is_msg=new class_mensajes();
	$io_function=new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);

	/************************************************************************************************************************
	*                                   	Datos definidos para la estructura del grid                                     *
	*************************************************************************************************************************/
	$ls_titulosublineas="Subl&uacute;neas Asignadas";
    $li_anchosublineas=600;
    $ls_nametable="grid";
    $la_columsublineas[1]="C&oacute;digo";
    $la_columsublineas[2]="Descripci&oacute;n";
	$la_columsublineas[3]="Eliminar";
   /*************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codcla=$_POST["txtcodcla"];
		$ls_dencla=$_POST["txtdencla"];
		$hiddesc="vacio";
		/******************************************************************************************************
		***********************************Definici�n de las subl�neas del grid********************************
		******************************************************************************************************/
		$li_filassublineas=$_POST["filassublineas"];
		$li_removersublineas=$_POST["removersublineas"];
		$ls_hidsub=$_POST["hidsub"];
		$ls_hidstatus=$_POST["hidstatus"];

		if ($ls_operacion != "ue_cargarsublineas" && $ls_operacion != "ue_removersublineas")
	     {

		 /*******************************************************************************************************
		 *************************************recorrido del grid Sublineas***************************************
		 *******************************************************************************************************/
		  for($li_i=1;$li_i<$li_filassublineas;$li_i++)
		   {
			$ls_codsub=$_POST["txtcodsub".$li_i];
			$ls_descsub=$_POST["txtdescsub".$li_i];
			$la_objectsublineas[$li_i][1]="<input name=txtcodsub".$li_i." type=text id=txtcodsub".$li_i." class=sin-borde value='".$io_function->uf_cerosizquierda($ls_codsub,'3')."' style= text-align:center size=3 maxlength=3 readonly>";
			$la_objectsublineas[$li_i][2]="<input name=txtdescsub".$li_i." type=text id=txtdescsub".$li_i." class=sin-borde value='".$ls_descsub."' style= text-align:left size=35 maxlength=100>";
			$la_objectsublineas[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removersublineas(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
		}
		$la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde style= text-align:center size=3 maxlength=3 readonly>";
		$la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde style= text-align:left size=25 maxlength=100 readonly>";
		$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

		/**************************************************************************************************************/
	}
	else
	{
		$ls_operacion="";
		$ls_codcla="";
		$ls_dencla="";
		$hiddesc="vacio";
		$ls_hidstatus="";
		//Pinta el grid sin datos
		$li_filassublineas=1; //Nro de filas que mostrar� el grid al cargar el formulario
	    $la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde style= text-align:center size=3 maxlength=3 readonly>";
	    $la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde style= text-align:left size=35 maxlength=100 readonly>";
		$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

	/************************************************************************************************************************/
	/***************************   NUEVO-> Limpia cajas de textos para nueva linea   ****************************************/
	/************************************************************************************************************************/
	if($ls_operacion=="ue_nuevo")
	{
	    require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_codcla=$io_funcdb->uf_generar_codigo(false,0,"sfc_clasificacion","codcla",3); // correlativo incrementa automaticamente
		$ls_dencla="";
		$ls_hidstatus="";
		/*********************************************************************************************************************
		*****************************************  Pinta el grid sin datos  **************************************************
		**********************************************************************************************************************/
		$li_filassublineas=1;
	    $la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde style= text-align:center size=3 maxlength=3 readonly>";
	    $la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde style= text-align:left size=35 maxlength=100 readonly>";
		$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
/*******************************************************************************************************************************/
/******************************************   GUARDAR   ***********************************************************************/
/*******************************************************************************************************************************/
	elseif($ls_operacion=="ue_guardar")
	{
		$la_detalles["cod_sub"][1]="";
		$la_detalles["descsub"][1]="";
		$cantidad_pro=true;
		//Carga en una matriz la información registrada en el grid para luego guardarla
	    for ($li_i=1;$li_i<$li_filassublineas;$li_i++)
	     {
		   $la_detalles["cod_sub"][$li_i]=$_POST["txtcodsub".$li_i];
		   $la_detalles["descsub"][$li_i]=$_POST["txtdescsub".$li_i];
			 if ($la_detalles["descsub"][$li_i]=="")
			    {
				  echo("<script>alert('Debe colocar la Descripción de la Sublinea!!!');</script>");
				  $cantidad_pro=false;
				}
		 }
		 if ($cantidad_pro==true)
		{
			 $lb_valido=$io_clasificacion->uf_guardar_clasificacion($ls_codcla,$ls_dencla,$la_seguridad);
			 $io_clasificacion->uf_update_lineassublineas($ls_codcla, $la_detalles,$li_filassublineas,$la_seguridad);
				$ls_mensaje=$io_clasificacion->io_msgc;
				if($lb_valido===true)
				{
					$is_msg->message ($ls_mensaje);
					/*print("<script language=JavaScript>");
					print("pagina='sigesp_sfc_d_clasificacion.php';");
				    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
				    print("</script>");*/
				}
				else
				{
					if($lb_valido===0)
					{
						/*print("<script language=JavaScript>");
						print("pagina='sigesp_sfc_d_clasificacion.php';");
					    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
					    print("</script>");*/
				  	}
					else
					{
						$is_msg->message ($ls_mensaje);
					}

				}
			}
	}
/*******************************************************************************************************************************/
/******************************************   ELIMINAR   ***********************************************************************/
/*******************************************************************************************************************************/
elseif($ls_operacion=="ue_eliminar")
{
	/*******************************************************************************************************************************
***********************************  verificar si la linea tiene productos asociados  ******************************************
********************************************************************************************************************************/
	     $ls_sql="SELECT codart
                   FROM sim_articulo
                  WHERE codemp='".$la_datemp["codemp"]."' AND codcla='".$ls_codcla."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_prod=false;
			$is_msg="Error en uf_select_producto ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_prod=true; //Registro encontrado
		        $is_msg->message ("Un producto pertenece a esta Línea no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_prod=false; //"Registro no encontrado"
			}
		}
	/****************************************************************************************************************************/
	/*******************************************************************************************************************************
***********************************  verificar si la linea tiene productos asociados  ******************************************
********************************************************************************************************************************/
	     $ls_sql="SELECT codart
                   FROM sim_articulo
                   WHERE cod_sub in (Select cod_sub From sfc_subclasificacion Where codcla='".$ls_codcla."') ";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_sub=false;
			$is_msg="Error en uf_select_producto ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_sub=true; //Registro encontrado
		        $is_msg->message ("Una sublinea pertenece a esta Línea de Articulos, no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_sub=false; //"Registro no encontrado"
			}
		}
	/****************************************************************************************************************************/
	if ($lb_valido_prod==false && $lb_valido_sub==false)
	 {
		$lb_valido=$io_clasificacion->uf_delete_clasificacion($ls_codcla,$la_seguridad);
		$ls_mensaje=$io_clasificacion->io_msgc;
		if ($lb_valido===true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_codcla="";
		    $ls_dencla="";
			$li_filassublineas=1;

			$la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde size=3 maxlength=3 style= text-align:center readonly>";
			$la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde size=35 style= text-align:left maxlength=100 readonly>";
			$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

		}
	  }
}

/************************************************************************************************************************/
/************************* CARGAR UNA SUBLINEA EN GRID DESDE Agregar SUBLINEA ******************************************/
/************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarsublineas")
{
	 require_once("../shared/class_folder/class_funciones_db.php");
	 require_once ("../shared/class_folder/sigesp_include.php");
	 $io_include=new sigesp_include();
	 $io_connect=$io_include->uf_conectar();
	 $io_funcdb=new class_funciones_db($io_connect);
	 $ls_clase=$_POST["cmbclase"];
	  // $li_filassublineas++;
	for($li_i=1;$li_i<$li_filassublineas;$li_i++)
	{
		$ls_codsub=$_POST["txtcodsub".$li_i];
		$ls_descsub=$_POST["txtdescsub".$li_i];
		$hiddesc=$_POST["txtdescsub".$li_i];
		$la_objectsublineas[$li_i][1]="<input name=txtcodsub".$li_i." type=text id=txtcodsub ".$li_i." value='".$ls_codsub."' class=sin-borde size=3 maxlength=3 style= text-align:center readonly>";
		$la_objectsublineas[$li_i][2]="<input name=txtdescsub".$li_i." type=text id=txtdescsub".$li_i." value='".$ls_descsub."' class=sin-borde size=35 maxlength=100 style= text-align:left>";
		$la_objectsublineas[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removersublineas(".$li_i.");><img src=../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}
	$la_detalles["cod_sub"][1]="";
	$la_detalles["descsub"][1]="";
	//Carga en una matriz la informaci�n registrada en el grid para luego guardarla
    for ($li_i=1;$li_i<$li_filassublineas;$li_i++)
     {
	   $la_detalles["cod_sub"][$li_i]=$_POST["txtcodsub".$li_i];
	   $la_detalles["descsub"][$li_i]=$_POST["txtdescsub".$li_i];
	 }
		$io_clasificacion->uf_update_lineassublineas($ls_codcla, $la_detalles,$li_filassublineas,$la_seguridad);

		$lb_valido=$io_clasificacion->uf_guardar_clasificacion($ls_codcla,$ls_dencla,$la_seguridad);
		//Linea de Grid con nuevo codigo para agregar
		$li_filassublineas++;
		$ls_codsub=$io_funcdb->uf_generar_codigo(false,0,"sfc_subclasificacion","cod_sub",3); // correlativo incrementa automaticamente
		$ls_descsub="";
		$la_objectsublineas[$li_i][1]="<input name=txtcodsub".$li_i." type=text id=txtcodsub".$li_i." value='".$ls_codsub."' class=sin-borde size=3 maxlength=3 style= text-align:center onKeyPress=return(validaCajas(this,'i',event)) readonly>";
		$la_objectsublineas[$li_i][2]="<input name=txtdescsub".$li_i." type=text id=txtdescsub".$li_i." value='".$ls_descsub."' class=sin-borde size=35 maxlength=100 style= text-align:left>";
		$la_objectsublineas[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removersublineas(".$li_i.");><img src=../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";

		$la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".   $li_filassublineas." class=sin-borde size=3 maxlength=3 style= text-align:center onKeyPress=return(validaCajas(this,'i',event)) readonly>";
		$la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde size=35 maxlength=100 style= text-align:left readonly>";
		$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";


}
/************************************************************************************************************************/
/***************************             ********************************************************************************/
/************************************************************************************************************************/

elseif($ls_operacion=="ue_cargarclasificacion")
	{

		$li_filassublineas=1; //Nro de filas que mostrar� el grid al cargar el formulario
	    $la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde style= text-align:center size=3 maxlength=3 readonly>";
	    $la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde style= text-align:left size=35 maxlength=100 readonly>";
		$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
/********************************************************************************************************************************/
$ls_cadena="SELECT * FROM sfc_subclasificacion WHERE codcla='".$ls_codcla."'";

			$arr_sublineas=$io_sql->select($ls_cadena);
			if($arr_sublineas==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de retenciones");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_sublineas))
 				  {
					$la_sublineas=$io_sql->obtener_datos($arr_sublineas);
					$io_datastore->data=$la_sublineas;
					$totrow=$io_datastore->getRowCount("codcla");

					for($li_i=1;$li_i<=$totrow;$li_i++)
					{

						$ls_codigo=$io_datastore->getValue("cod_sub",$li_i);
		                $ls_descripcion=$io_datastore->getValue("den_sub",$li_i);

		$la_objectsublineas[$li_i][1]="<input name=txtcodsub".$li_i." type=text id=txtcodsub".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=3 maxlength=3 onKeyPress=return(validaCajas(this,'i',event)) readonly>";
		$la_objectsublineas[$li_i][2]="<input name=txtdescsub".$li_i." type=text id=txtdescsub".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 maxlength=100>";
		$la_objectsublineas[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removersublineas(".$li_i.");><img src=../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		   	 	   }

	$li_filassublineas=$li_i;

	$la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde style= text-align:center size=3 maxlength=3 onKeyPress=return(validaCajas(this,'i',event)) readonly>";
	$la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde style= text-align:left size=35 maxlength=100 readonly>";
	$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
            }
		}
		}
/************************************************************************************************************************/
/********************************************* REMOVER SUBLINEA EN GRID *************************************************/
/************************************************************************************************************************/
elseif($ls_operacion=="ue_removersublineas")
{
$la_detalles["cod_sub"][$li_removersublineas]=$_POST["txtcodsub".$li_removersublineas];
	$ls_sql="SELECT * FROM sim_articulo WHERE codemp='".$la_datemp["codemp"]."' AND codcla='".$ls_codcla."' AND cod_sub='".$la_detalles["cod_sub"][$li_removersublineas]."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_prod=false;
			$is_msg="Error en remover Sublinea ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_prod=true; //Registro encontrado
		        $is_msg->message ("Un producto pertenece a la sublinea seleccionada no se puede remover!!!");
			}
			else
			{
				$lb_valido_prod=false; //"Registro no encontrado"
			}
		}
	if ($lb_valido_prod==false)
	 {
	$li_filassublineas=$li_filassublineas-1;
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filassublineas;$li_i++)
	{
		if($li_i!=$li_removersublineas)
		{
			$li_temp=$li_temp+1;
			$ls_codsub=$_POST["txtcodsub".$li_i];
			$ls_descsub=$_POST["txtdescsub".$li_i];
			$la_objectsublineas[$li_temp][1]="<input name=txtcodsub".$li_temp." type=text id=txtcodsub".$li_temp." class=sin-borde value='".$ls_codsub."' style= text-align:center size=3 maxlength=3 readonly>";
			$la_objectsublineas[$li_temp][2]="<input name=txtdescsub".$li_temp." type=text id=txtdescsub".$li_temp." class=sin-borde value='".$ls_descsub."' style= text-align:left size=35 maxlength=100>";
			$la_objectsublineas[$li_temp][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removersublineas(".$li_temp.");><img src=../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectsublineas[$li_filassublineas][1]="<input name=txtcodsub".$li_filassublineas." type=text id=txtcodsub".$li_filassublineas." class=sin-borde style= text-align:center size=3 maxlength=3 readonly>";
	$la_objectsublineas[$li_filassublineas][2]="<input name=txtdescsub".$li_filassublineas." type=text id=txtdescsub".$li_filassublineas." class=sin-borde style= text-align:left size=35 maxlength=100>";
	$la_objectsublineas[$li_filassublineas][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
else
{
	require_once("../shared/class_folder/class_funciones_db.php");
	 require_once ("../shared/class_folder/sigesp_include.php");
	 $io_include=new sigesp_include();
	 $io_connect=$io_include->uf_conectar();
	 $io_funcdb=new class_funciones_db($io_connect);
    // $li_filassublineas++;
	for($li_i=1;$li_i<$li_filassublineas;$li_i++)
	{
		$ls_codsub=$_POST["txtcodsub".$li_i];
		$ls_descsub=$_POST["txtdescsub".$li_i];
		$hiddesc=$_POST["txtdescsub".$li_i];
		$la_objectsublineas[$li_i][1]="<input name=txtcodsub".$li_i." type=text id=txtcodsub ".$li_i." value='".$ls_codsub."' class=sin-borde size=3 maxlength=3 style= text-align:center readonly>";
		$la_objectsublineas[$li_i][2]="<input name=txtdescsub".$li_i." type=text id=txtdescsub".$li_i." value='".$ls_descsub."' class=sin-borde size=35 maxlength=100 style= text-align:left>";
		$la_objectsublineas[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removersublineas(".$li_i.");><img src=../shared/imagebank/tools20/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		     }
}
}
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if ($ls_permisos)
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{

	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="2" class="titulo-ventana">L&iacute;nea de Productos </td>
              </tr>
              <tr>
                <td >
				<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $hidstatus?>">
				<input name="hiddesc" type="hidden" id="hiddesc" value="<? print $hiddesc?>">
				<input name="filassublineas" type="hidden" id="filassublineas" value="<? print $li_filassublineas?>">
				<input name="removersublineas" type="hidden" id="removersublineas" value="<? print $li_removersublineas?>">
				</td>
                <td >&nbsp;</td>
              </tr>
              <tr>
                <td width="134" height="22" align="right"><span class="style2">Codigo </span></td>
                <td width="343" ><input name="txtcodcla" type="text" id="txtcodcla" value="<? print  $ls_codcla?>" size="4" maxlength="3" readonly="true"></td>
              </tr>
              <tr>
                <td width="134" height="22" align="right">Descripci&oacute;n</td>
                <td width="343" ><input name="txtdencla" type="text" id="txtdencla"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  $ls_dencla?>" size="65" maxlength="225" >                </td>
              </tr>

			  <!--********************************************************************************************
			  **********************************GRID SUBLINEAS DE ARTICULOS***********************************
			  **********************************************************************************************-->

			   <tr>
                <td height="8" colspan="2"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="13" colspan="2" align="right" class="titulo-ventana">Sublineas de Productos </td>
                  </tr>
                  <tr>
                    <td height="13" align="right">&nbsp;</td>
                    <td >&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="111" height="13" align="right"><div align="left"><a href="javascript:ue_catsublineas();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catsublineas();">Agregar Sublinea</a></div></td>
                    <td width="302" >&nbsp;</td>
                  </tr>
                  <tr>
				  <!--Aqui se llama al metodo que dibuja la grid, y se la pasan los parametros correpondientes-->
                    <td height="13" colspan="2" align="right"><?php $io_grid->makegrid($li_filassublineas,$la_columsublineas,$la_objectsublineas,$li_anchosublineas,$ls_titulosublineas,$ls_nametable);?></td>
                  </tr>
                  <tr>
                    <td height="13" align="right">&nbsp;</td>
                    <td >&nbsp;</td>
                  </tr>

                </table></td>
              </tr>

			  <!--***************************************************************************************************************
			  *******************************************************************************************************************
			  *****************************************************************************************************************-->

            </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

/***********************************************************************************************************************************/

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
if(li_incluir==1)
{
	f.operacion.value="ue_nuevo";
	f.txtdencla.value="";
	f.action="sigesp_sfc_d_clasificacion.php";
	f.submit();
}
else
{
	alert("No tiene permiso para realizar esta operacion");
}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
	if (lb_status!="C")
	{
	f.hidstatus.value="C";
	}
	with(f)
		 {
		  if (ue_valida_null(txtcodcla,"Codigo")==false)
		   {
			 txtcodcla.focus();
		   }
		   else
		   {
			if (ue_valida_null(txtdencla,"Descripcion")==false)
			 {
			  txtdencla.focus();
			 }
			else
			 {
				f.operacion.value="ue_guardar";
				f.action="sigesp_sfc_d_clasificacion.php";
				f.submit();
			 }
		   }
		 }
	}
		else
	{
	alert("No tiene permiso para realizar esta operacion");
	}

}

function ue_guardarsublineas()
{
	f=document.form1;
	with(f)
		 {
		  if (ue_valida_null(txtcodcla,"Codigo")==false)
		   {
			 txtcodcla.focus();
		   }
		   else
		   {
			if (ue_valida_null(txtdencla,"Descripcion")==false)
			 {
			  txtdencla.focus();
			 }
			 else
			 {
				f.operacion.value="ue_guardarsublineas";
				f.action="sigesp_sfc_d_clasificacion.php";
				f.submit();
			 }
		   }
		 }

}
function ue_eliminar()
{
f=document.form1;
li_eliminar=f.eliminar.value;
if(li_eliminar==1)
{
if (f.txtcodcla.value=="")
	   {
		 alert("No ha seleccionado ningún registro para eliminar !!!");
	   }
		else
		{
		 if (confirm("Esta seguro de eliminar este registro ?"))
			   {
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="sigesp_sfc_d_clasificacion.php";
				 f.submit();
			   }
			else
			   {
				 f=document.form1;
				 f.action="sigesp_sfc_d_clasificacion.php";
				 alert("Eliminación Cancelada !!!");
				 f.txtcodtie.value="";
				 f.txtdentie.value="";
				 f.txtdirtie.value="";
				 f.txtteltie.value="";
				 f.txtriftie.value="";
				 f.submit();
			   }
		}
}
else
{
	alert("No tiene permiso para realizar esta operacion");
}
}
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
	f.operacion.value="";
	pagina="sigesp_cat_clasificacion.php";
	popupWin(pagina,"catalogo",600,250);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
	}

/***********************************************************************************************************************************/

function ue_cargarclasificacion(codcla,nomcla)
{
	f=document.form1;
	f.hidstatus.value="C"
	f.txtcodcla.value=codcla;
	f.txtdencla.value=nomcla;
	f.operacion.value="ue_cargarclasificacion";
	f.submit();
}

/***********************************************************************************************************************************/

function EvaluateText(cadena, obj)
{
opc = false;

	if (cadena == "%d")
	  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))
	  opc = true;
	if (cadena == "%f"){
	 if (event.keyCode > 47 && event.keyCode < 58)
	  opc = true;
	 if (obj.value.search("[.*]") == -1 && obj.value.length != 0)
	  if (event.keyCode == 46)
	   opc = true;
	}
	 if (cadena == "%s") // toma numero y letras
	 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46))
	  opc = true;
	 if (cadena == "%c") // toma numero y punto
	 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
	  opc = true;
	if(opc == false)
	 event.returnValue = false;
   }

/********************************************************************************************************************************
*************************************************  MANEJO DEL GRID SUBLINEA  ****************************************************
*********************************************************************************************************************************/
function ue_catsublineas()
{
	f=document.form1;
	if(f.txtcodcla.value!="")
	{
		codcla=f.txtcodcla.value;
		f.operacion.value="ue_cargarsublineas";
		f.submit();
	}
}

function ue_cargarsublineas(codsub,descsub)
{
	f=document.form1;
	f.operacion.value="ue_cargarsublineas";
	lb_existe=false;

	for(li_i=1;li_i<=f.filassublineas.value && !lb_existe;li_i++)
	{
		ls_codsub=eval("f.txtcodsub"+li_i+".value");

		if(ls_codsub===codsub)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}

	if(!lb_existe)
	{
		eval("f.txtcodsub"+f.filassublineas.value+".value='"+codsub+"'");
		eval("f.txtdescsub"+f.filassublineas.value+".value='"+descsub+"'");
		f.submit();
	}

}

function ue_removersublineas(li_fila)
{
	f=document.form1;
	f.removersublineas.value=li_fila;
	f.operacion.value="ue_removersublineas"
	f.action="sigesp_sfc_d_clasificacion.php";
	f.submit();
}

function ue_validar()
        {
	        f=document.form1;
	        f.action="sigesp_sfc_d_clasificacion.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }


</script>

</html>
