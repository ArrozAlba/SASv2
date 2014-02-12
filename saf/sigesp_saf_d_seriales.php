<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_seriales.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		
		$ls_titletable="Listado de Activos";
		$li_widthtable=840;
		$ls_nametable="grid";
		$lo_title[1]="Activo";
		$lo_title[2]="Serial";
		$lo_title[3]="Chapa";
		$lo_title[4]="Unidad";
		$lo_title[5]="Responsable Primario";
		$lo_title[6]="Responsable por uso";
		$lo_title[7]="Observacion";
		$lo_title[8]="ID Activo";
		$lo_title[9]=" ";
		$lo_title[10]=" ";
		$li_totrows=1;

   }
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_agregarlineablanca
		//	Access:    public
		//	Arguments:
		//  aa_object  // arreglo de titulos 
		//  ai_totrows // ultima fila pintada en el grid
		//	Description:  Funcion que agrega una linea en blanco al final del grid
		//              
		//////////////////////////////////////////////////////////////////////////////		
		/*$aa_object[$ai_totrows][1]="<input name=txtcodactd".$ai_totrows."      type=text   id=txtcodactd".$ai_totrows."      class=sin-borde size=18 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtseractd".$ai_totrows."      type=text   id=txtseractd".$ai_totrows."      class=sin-borde size=22 maxlength=20 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtchaactd".$ai_totrows."      type=text   id=txtchaactd".$ai_totrows."      class=sin-borde size=18 maxlength=15 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtunidadd".$ai_totrows."      type=text   id=txtunidadd".$ai_totrows."      class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtresponsabled".$ai_totrows." type=text   id=txtresponsabled".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtcodres".$ai_totrows."       type=text   id=txtcodres".$ai_totrows."       class=sin-borde size=12 maxlength=10 readonly>".
								   "<input name=txtnomres".$ai_totrows."       type=hidden id=txtnomres".$ai_totrows." ><a href=javascript:ue_responsableuso(".$ai_totrows.");><img src=../shared/imagebank/tools15/buscar.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][7]="<input name=txtobserd".$ai_totrows."       type=text   id=txtobserd".$ai_totrows."       class=sin-borde size=18 maxlength=100 readonly>";
		$aa_object[$ai_totrows][8]="<input name=txtidactivod".$ai_totrows."    type=text   id=txtidactivod".$ai_totrows."    class=sin-borde size=19 maxlength=15 readonly>";			
		$aa_object[$ai_totrows][9]="<img src=../shared/imagebank/tools15/aprobado-off.gif alt=Aceptar width=15 height=15 border=0>";			
		$aa_object[$ai_totrows][10]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	*/		
		
		$aa_object[$ai_totrows][1]="<input name=txtcodactd".$ai_totrows." type=text id=txtcodactd".$ai_totrows." class=sin-borde size=18 maxlength=15 value='' onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtseractd".$ai_totrows." type=text id=txtseractd".$ai_totrows." class=sin-borde size=22 maxlength=20 value='' onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,20)'>";
		$aa_object[$ai_totrows][3]="<input name=txtchaactd".$ai_totrows." type=text id=txtchaactd".$ai_totrows." class=sin-borde size=18 maxlength=15 value='' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
		$aa_object[$ai_totrows][4]="<input name=txtdenunidadd".$ai_totrows." type=text id=txtdenunidadd".$ai_totrows." class=sin-borde size=50 maxlength=50 value='' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
				                           "<input name=txtunidadd".$ai_totrows." type=hidden id=txtunidadd".$ai_totrows." class=sin-borde size=18 maxlength=100 value='' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
	    $aa_object[$ai_totrows][5]="<input name=txtnomrespri".$ai_totrows." type=text id=txtnomrespri".$ai_totrows." class=sin-borde size=50 maxlength=50 value='' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtresponsabled".$ai_totrows." type=hidden id=txtresponsabled".$ai_totrows." class=sin-borde size=12 maxlength=10 value='' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
		$aa_object[$ai_totrows][6]="<input name=txtnomres".$ai_totrows." type=text id=txtnomres".$ai_totrows." class=sin-borde size=50 maxlength=50 value='' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtcodres".$ai_totrows." type=hidden id=txtcodres".$ai_totrows." class=sin-borde size=12 maxlength=10 value='' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)' readonly >";			  	   
	    $aa_object[$ai_totrows][7]="<input name=txtobserd".$ai_totrows." type=text id=txtobserd".$ai_totrows." class=sin-borde size=18 maxlength=100 value='' onKeyUp='javascript: ue_validarcomillas(this);' >";
	    $aa_object[$ai_totrows][8]="<input name=txtidactivod".$ai_totrows." type=text id=txtidactivod".$ai_totrows." class=sin-borde size=18 maxlength=15 value='' onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";			
		$aa_object[$ai_totrows][9]="<img src=../shared/imagebank/tools15/aprobado-off.gif alt=Aceptar width=15 height=15 border=0>";			
		$aa_object[$ai_totrows][10]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	
   }

   function uf_pintardetalle(&$ao_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_pintardetalle
		//	Access:    public
		//	Arguments:
		//  aa_object  // arreglo de titulos 
		//  ai_totrows // ultima fila pintada en el grid
		//	Description:  Funcion que agrega una linea en blanco al final del grid
		//              
		//////////////////////////////////////////////////////////////////////////////		
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
/*			$ls_codact=$_POST["txtcodactd".$li_i];
			$ls_seract=$_POST["txtseractd".$li_i];
			$ls_chaact=$_POST["txtchaactd".$li_i];
			$ls_unidad=$_POST["txtunidadd".$li_i];
			$ls_responsable=$_POST["txtresponsabled".$li_i];
			$ls_responsableuso=$_POST["txtcodres".$li_i];
			$ls_observacion=$_POST["txtobserd".$li_i];
			$ls_idactivo=$_POST["txtidactivod".$li_i];

			$ao_object[$li_i][1]="<input name=txtcodactd".$li_i."      type=text   id=txtcodactd".$li_i."      class=sin-borde size=18 maxlength=15  value='".$ls_codact."'      onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$ao_object[$li_i][2]="<input name=txtseractd".$li_i."      type=text   id=txtseractd".$li_i."      class=sin-borde size=22 maxlength=20  value='".$ls_seract."'      onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";
			$ao_object[$li_i][3]="<input name=txtchaactd".$li_i."      type=text   id=txtchaactd".$li_i."      class=sin-borde size=18 maxlength=15  value='".$ls_chaact."'      onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";
			$ao_object[$li_i][4]="<input name=txtunidadd".$li_i."      type=text   id=txtunidadd".$li_i."      class=sin-borde size=12 maxlength=10  value='".$ls_unidad."'      onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			$ao_object[$li_i][5]="<input name=txtresponsabled".$li_i." type=text   id=txtresponsabled".$li_i." class=sin-borde size=12 maxlength=10  value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			$ao_object[$li_i][6]="<input name=txtcodres".$li_i."       type=text   id=txtcodres".$li_i."       class=sin-borde size=12 maxlength=10  value='".$ls_responsableuso."' readonly>".
								 "<input name=txtnomres".$li_i."       type=hidden id=txtnomres".$li_i." ><a href=javascript:ue_responsableuso(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif alt=Aceptar width=15 height=15 border=0></a>";
			$ao_object[$li_i][7]="<input name=txtobserd".$li_i."       type=text   id=txtobserd".$li_i."       class=sin-borde size=18 maxlength=100 value='".$ls_observacion."' onKeyUp='javascript: ue_validarcomillas(this);'>";
			$ao_object[$li_i][8]="<input name=txtidactivod".$li_i." type=text      id=txtidactivod".$li_i."    class=sin-borde size=18 maxlength=15  value='".$ls_idactivo."'    onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";			
			$ao_object[$li_i][9]="<a href=javascript:uf_agregarpartes(".$li_i.");><img src=../shared/imagebank/tools/nuevo.gif alt='Agregar partes' width=15 height=15 border=0></a>";			
			$ao_object[$li_i][10]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	*/		
			$ls_codact=$_POST["txtcodactd".$li_i];
			$ls_seract=$_POST["txtseractd".$li_i];
			$ls_chaact=$_POST["txtchaactd".$li_i];
			$ls_unidad=$_POST["txtunidadd".$li_i];
			$ls_responsable=$_POST["txtresponsabled".$li_i];
			$ls_responsableuso=$_POST["txtcodres".$li_i];
			$ls_observacion=$_POST["txtobserd".$li_i];
			$ls_idactivo=$_POST["txtidactivod".$li_i];
            $ls_nomrespri=$_POST["txtnomrespri".$li_i];
			$ls_nomresuso=$_POST["txtnomres".$li_i];
			$ls_denunidad=$_POST["txtdenunidadd".$li_i];
			
			$ao_object[$li_i][1]="<input name=txtcodactd".$li_i." type=text id=txtcodactd".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_codact."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$li_i][2]="<input name=txtseractd".$li_i." type=text id=txtseractd".$li_i." class=sin-borde size=22 maxlength=20 value='".$ls_seract."' onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,20)'>";
			$ao_object[$li_i][3]="<input name=txtchaactd".$li_i." type=text id=txtchaactd".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_chaact."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
			$ao_object[$li_i][4]="<input name=txtdenunidadd".$li_i." type=text id=txtdenunidadd".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_denunidad."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
				                           "<input name=txtunidadd".$li_i." type=hidden id=txtunidadd".$li_i." class=sin-borde size=18 maxlength=100 value='".$ls_unidad."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			$ao_object[$li_i][5]="<input name=txtnomrespri".$li_i." type=text id=txtnomrespri".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_nomrespri."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtresponsabled".$li_i." type=hidden id=txtresponsabled".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			$ao_object[$li_i][6]="<input name=txtnomres".$li_i." type=text id=txtnomres".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_nomresuso."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtcodres".$li_i." type=hidden id=txtcodres".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsableuso."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)' readonly >";			  	   
			$ao_object[$li_i][7]="<input name=txtobserd".$li_i." type=text id=txtobserd".$li_i." class=sin-borde size=18 maxlength=100 value='".$ls_observacion."' onKeyUp='javascript: ue_validarcomillas(this);' >";
			$ao_object[$li_i][8]="<input name=txtidactivod".$li_i." type=text id=txtidactivod".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_idactivo."' onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";			
			$ao_object[$li_i][9]="<a href=javascript:uf_agregarpartes(".$li_i.");><img src=../shared/imagebank/tools/nuevo.gif alt='Agregar partes' width=15 height=15 border=0></a>";			
			$ao_object[$li_i][10]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	
		}
		uf_agregarlineablanca($ao_object,$ai_totrows);		
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Registro de Seriales</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("sigesp_saf_c_activo.php");
	$io_saf= new sigesp_saf_c_activo();
	require_once("class_funciones_activos.php");
	$io_fac= new class_funciones_activos();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid=new grid_param();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	uf_limpiarvariables();
	$li_totrows = $io_fac->uf_obtenervalor("totalfilas",1);


	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$io_fac->uf_obtenervalor("operacion","NUEVO");
		$ls_denact=$io_fac->uf_obtenervalor("txtdenact","");
		$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
	}
	else
	{
		$ls_operacion="NUEVO";
	}
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			$ls_denact=$io_fac->uf_obtenervalor_get("denact","");
			$ls_codact=$io_fac->uf_obtenervalor_get("codact","");
			$lb_valido=$io_saf->uf_saf_load_seriales($ls_codemp,$ls_codact,$lo_object,$li_totrows);
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;

		case "GUARDAR":
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codact=$_POST["txtcodactd".$li_i];
				$ls_seract=$_POST["txtseractd".$li_i];
				$ls_chaact=$_POST["txtchaactd".$li_i];
				$ls_unidad=$_POST["txtunidadd".$li_i];
				$ls_responsable=$_POST["txtresponsabled".$li_i];
				$ls_responsableuso=$_POST["txtcodres".$li_i];
				$ls_observacion=$_POST["txtobserd".$li_i];
				$ls_idactivo=$_POST["txtidactivod".$li_i];
				$ls_estact="R";
				$lb_encontrado=$io_saf->uf_saf_select_seriales($ls_codemp,$ls_codact,$ls_idactivo);
				if ($lb_encontrado)
				{
					
					$lb_valido=$io_saf->uf_saf_update_seriales($ls_codemp,$ls_codact,$ls_idactivo,$ls_seract,$ls_chaact,$ls_unidad,
															   $ls_responsable,$ls_observacion,$ls_estact,$ls_responsableuso,$la_seguridad);
				}
				else
				{
					$lb_valido=$io_saf->uf_saf_insert_seriales($ls_codemp,$ls_codact,$ls_idactivo,$ls_seract,$ls_chaact,$ls_unidad,
															   $ls_responsable,$ls_observacion,$ls_estact,$ls_logusr,$ls_responsableuso,
															   $la_seguridad);
				}
				if(!$lb_valido)
				{break;}
			}
			if($lb_valido)
			{
				$io_msg->message("Los seriales fueron registrados");
				uf_pintardetalle($lo_object,$li_totrows);
			}
			else
			{
				$io_msg->message("No se pudieron registrar los seriales");
				uf_pintardetalle($lo_object,$li_totrows);
			}
		break;

		case "AGREGARDETALLE":

			$li_cantidad=$_POST["txtcanact"];
			if ($li_cantidad!="")
			{
				uf_pintardetalle($lo_object,$li_totrows);
				$li_inicio = $li_totrows;
				$li_totrows = $li_totrows + $li_cantidad;
				for($li_i=$li_inicio;$li_i<$li_totrows;$li_i++)
				{	
/*					$ls_codact=$_POST["txtcodact"];
					$ls_unidad=$_POST["txtcoduni"];
					$ls_responsable=$_POST["txtcodres"];

					$lo_object[$li_i][1]="<input name=txtcodactd".$li_i."      type=text id=txtcodactd".$li_i."      class=sin-borde size=18 maxlength=15 value='".$ls_codact."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_i][2]="<input name=txtseractd".$li_i."      type=text id=txtseractd".$li_i."      class=sin-borde size=22 maxlength=20                        onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,15)'>";
					$lo_object[$li_i][3]="<input name=txtchaactd".$li_i."      type=text id=txtchaactd".$li_i."      class=sin-borde size=18 maxlength=15                        onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,15)'>";
					$lo_object[$li_i][4]="<input name=txtunidadd".$li_i."      type=text id=txtunidadd".$li_i."      class=sin-borde size=12 maxlength=10 value='".$ls_unidad."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
					$lo_object[$li_i][5]="<input name=txtresponsabled".$li_i." type=text id=txtresponsabled".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
					$lo_object[$li_i][6]="<input name=txtcodres".$li_i."       type=text id=txtcodres".$li_i."       class=sin-borde size=12 maxlength=10 readonly>".
										 "<input name=txtnomres".$li_i." 	   type=hidden id=txtnomres".$li_i." ><a href=javascript:ue_responsableuso(".$li_i.");><img src=../shared/imagebank/tools15/buscar.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][7]="<input name=txtobserd".$li_i."       type=text id=txtobserd".$li_i."       class=sin-borde size=18 maxlength=100                       onKeyUp='javascript: ue_validarcomillas(this);'>";
					$lo_object[$li_i][8]="<input name=txtidactivod".$li_i."    type=text id=txtidactivod".$li_i."    class=sin-borde size=18 maxlength=15                        onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,15)'>";			
					$lo_object[$li_i][9]="";			
					$lo_object[$li_i][10]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	*/		
					
					$ls_codact=$_POST["txtcodact"];
					$ls_unidad="";
					$ls_seract="";
				    $ls_chaact="";
					$ls_responsable="";
					$ls_nomrespri="";
			        $ls_nomresuso="";
			        $ls_denunidad="";
					$ls_idactivo="";
				    $ls_responsableuso="";
				    $ls_observacion="";
					
					$lo_object[$li_i][1]="<input name=txtcodactd".$li_i." type=text id=txtcodactd".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_codact."' onKeyUp='javascript: ue_validarnumero(this);'>";
			        $lo_object[$li_i][2]="<input name=txtseractd".$li_i." type=text id=txtseractd".$li_i." class=sin-borde size=22 maxlength=20 value='".$ls_seract."' onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,20)'>";
			        $lo_object[$li_i][3]="<input name=txtchaactd".$li_i." type=text id=txtchaactd".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_chaact."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
			        $lo_object[$li_i][4]="<input name=txtdenunidadd".$li_i." type=text id=txtdenunidadd".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_denunidad."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
				                           "<input name=txtunidadd".$li_i." type=hidden id=txtunidadd".$li_i." class=sin-borde size=18 maxlength=100 value='".$ls_unidad."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			        $lo_object[$li_i][5]="<input name=txtnomrespri".$li_i." type=text id=txtnomrespri".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_nomrespri."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtresponsabled".$li_i." type=hidden id=txtresponsabled".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			        $lo_object[$li_i][6]="<input name=txtnomres".$li_i." type=text id=txtnomres".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_nomresuso."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtcodres".$li_i." type=hidden id=txtcodres".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsableuso."' readonly>";			  	   
			        $lo_object[$li_i][7]="<input name=txtobserd".$li_i." type=text id=txtobserd".$li_i." class=sin-borde size=18 maxlength=100 value='".$ls_observacion."' onKeyUp='javascript: ue_validarcomillas(this);' >";
			        $lo_object[$li_i][8]="<input name=txtidactivod".$li_i." type=text id=txtidactivod".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_idactivo."' onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";			
			        $lo_object[$li_i][9]="<a href=javascript:uf_agregarpartes(".$li_i.");><img src=../shared/imagebank/tools/nuevo.gif alt='Agregar partes' width=15 height=15 border=0></a>";			
			        $lo_object[$li_i][10]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	
				}
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
			
		case "AGREGAROTRODETALLE":
			$li_totrows=$li_totrows+1;
			uf_pintardetalle($lo_object,$li_totrows);
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;

		case "ELIMINARDETALLE":
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
/*					$li_temp=$li_temp+1;			
					$ls_codact=$_POST["txtcodactd".$li_i];
					$ls_seract=$_POST["txtseractd".$li_i];
					$ls_chaact=$_POST["txtchaactd".$li_i];
					$ls_unidad=$_POST["txtunidadd".$li_i];
					$ls_responsable=$_POST["txtresponsabled".$li_i];
					$ls_responsableuso=$_POST["txtcodres".$li_i];
					$ls_observacion=$_POST["txtobserd".$li_i];
					$ls_idactivo=$_POST["txtidactivod".$li_i];
	
					$lo_object[$li_temp][1]="<input name=txtcodactd".$li_temp."      type=text id=txtcodactd".$li_temp."      class=sin-borde size=18 maxlength=15  value='".$ls_codact."'      readonly>";
					$lo_object[$li_temp][2]="<input name=txtseractd".$li_temp."      type=text id=txtseractd".$li_temp."      class=sin-borde size=22 maxlength=20  value='".$ls_seract."'      onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";
					$lo_object[$li_temp][3]="<input name=txtchaactd".$li_temp."      type=text id=txtchaactd".$li_temp."      class=sin-borde size=18 maxlength=15  value='".$ls_chaact."'      onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";
					$lo_object[$li_temp][4]="<input name=txtunidadd".$li_temp."      type=text id=txtunidadd".$li_temp."      class=sin-borde size=12 maxlength=10  value='".$ls_unidad."'      onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
					$lo_object[$li_temp][5]="<input name=txtresponsabled".$li_temp." type=text id=txtresponsabled".$li_temp." class=sin-borde size=12 maxlength=10  value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
					$lo_object[$li_temp][6]="<input name=txtcodres".$li_temp."       type=text id=txtcodres".$li_temp."       class=sin-borde size=12 maxlength=10  value='".$ls_responsableuso."' readonly>".
										    "<input name=txtnomres".$li_temp."       type=hidden id=txtnomres".$li_temp." ><a href=javascript:ue_responsableuso(".$li_temp.");><img src=../shared/imagebank/tools15/buscar.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][7]="<input name=txtobserd".$li_temp."       type=text id=txtobserd".$li_temp."       class=sin-borde size=10 maxlength=100 value='".$ls_observacion."' onKeyUp='javascript: ue_validarcomillas(this);'> ";
					$lo_object[$li_temp][8]="<input name=txtidactivod".$li_temp."    type=text id=txtidactivod".$li_temp."    class=sin-borde size=18 maxlength=15  value='".$ls_idactivo."'    onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";			
					$lo_object[$li_temp][9]="<a href=javascript:uf_agregarpartes(".$li_temp.");><img src=../shared/imagebank/tools/nuevo.gif alt='Agregar partes' width=15 height=15 border=0></a>";			 
					$lo_object[$li_temp][10]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	*/		
					
					$li_temp=$li_temp+1;			
					$ls_codact=$_POST["txtcodactd".$li_i];
					$ls_seract=$_POST["txtseractd".$li_i];
					$ls_chaact=$_POST["txtchaactd".$li_i];
					$ls_unidad=$_POST["txtunidadd".$li_i];
					$ls_responsable=$_POST["txtresponsabled".$li_i];
					$ls_responsableuso=$_POST["txtcodres".$li_i];
					$ls_observacion=$_POST["txtobserd".$li_i];
					$ls_idactivo=$_POST["txtidactivod".$li_i];
					$ls_nomrespri=$_POST["txtnomrespri".$li_i];
			        $ls_nomresuso=$_POST["txtnomres".$li_i];
			        $ls_denunidad=$_POST["txtdenunidadd".$li_i];

					
					$lo_object[$li_i][1]="<input name=txtcodactd".$li_i." type=text id=txtcodactd".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_codact."' onKeyUp='javascript: ue_validarnumero(this);'>";
			        $lo_object[$li_i][2]="<input name=txtseractd".$li_i." type=text id=txtseractd".$li_i." class=sin-borde size=22 maxlength=20 value='".$ls_seract."' onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,20)'>";
			        $lo_object[$li_i][3]="<input name=txtchaactd".$li_i." type=text id=txtchaactd".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_chaact."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
			        $lo_object[$li_i][4]="<input name=txtdenunidadd".$li_i." type=text id=txtdenunidadd".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_denunidad."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
				                           "<input name=txtunidadd".$li_i." type=hidden id=txtunidadd".$li_i." class=sin-borde size=18 maxlength=100 value='".$ls_unidad."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			        $lo_object[$li_i][5]="<input name=txtnomrespri".$li_i." type=text id=txtnomrespri".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_nomrespri."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtresponsabled".$li_i." type=hidden id=txtresponsabled".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
			        $lo_object[$li_i][6]="<input name=txtnomres".$li_i." type=text id=txtnomres".$li_i." class=sin-borde size=50 maxlength=50 value='".$ls_nomresuso."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtcodres".$li_i." type=hidden id=txtcodres".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_responsableuso."' readonly>";			  	   
			        $lo_object[$li_i][7]="<input name=txtobserd".$li_i." type=text id=txtobserd".$li_i." class=sin-borde size=18 maxlength=100 value='".$ls_observacion."' onKeyUp='javascript: ue_validarcomillas(this);' >";
			        $lo_object[$li_i][8]="<input name=txtidactivod".$li_i." type=text id=txtidactivod".$li_i." class=sin-borde size=18 maxlength=15 value='".$ls_idactivo."' onKeyPress='return keyrestrictgrid(event)'    onBlur='ue_rellenarcampo(this,15)'>";			
			        $lo_object[$li_i][9]="<a href=javascript:uf_agregarpartes(".$li_i.");><img src=../shared/imagebank/tools/nuevo.gif alt='Agregar partes' width=15 height=15 border=0></a>";			
			        $lo_object[$li_i][10]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";	
				}
				else
				{
					$ls_idactivo=$_POST["txtidactivod".$li_i];
					$ls_seract=$_POST["txtseractd".$li_i];
					$lb_valido=$io_saf->uf_saf_delete_seriales($ls_codemp,$ls_seract,$ls_codact,$ls_idactivo,$la_seguridad);
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
	}

?>
<div align="center">
  <table width="850" height="201" border="0" class="formato-blanco">
 <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <tr>
      <td width="769" height="195"><div align="left">
        <table width="850" height="191" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="3"><textarea name="txtdenact" cols="100" readonly="true" class="sin-borde2" id="txtdenact"><?php print $ls_denact?></textarea></td>
          </tr>
          <tr>
            <td colspan="3" class="titulo-ventana">Registro de Seriales</td>
          </tr>
          <tr class="formato-blanco">
            <td height="19" colspan="3"><div align="left">
                <input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact?>">
                <input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $la_codemp?>">
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="79" height="28"><div align="right">Cantidad</div></td>
            <td width="769" height="22"><input name="txtcanact" type="text" id="txtcanact" size="4" maxlength="2" onKeyPress="return keyRestrict(event,'1234567890');">              <a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/actualizar(1).gif" alt="Incluir Seriales" width="15" height="15" border="0">Incluir</a></td>
            </tr>
          <tr class="formato-blanco">
            <td height="22" colspan="2"><?php	
			$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	?>            </td>
          </tr>
          <tr class="formato-blanco">
            <td height="28"><div align="right">
                <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
                <input name="filadelete" type="hidden" id="filadelete">
            </div></td>
            <td height="22" colspan="2"><div align="right"><a href="javascript: ue_guardar(<?php echo $li_totrows-1;?>);"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar(<?php echo $li_totrows-1;?>);">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
          </tr>
        </table>
        <div align="center"><input name="operacion" type="hidden" id="operacion">
      </div>
      </div></td>
    </tr>
    </form>
  </table>
</div>
<p align="center">&nbsp; </p>
</body>
<script language="javascript">
//Funciones de operaciones  
function uf_agregarpartes(li_row)
{
	f=document.form1;
	ls_codact=eval("f.txtcodactd"+li_row+".value");
	ls_codact=ue_validarvacio(ls_codact);
	ls_seract=eval("f.txtseractd"+li_row+".value");
	ls_seract=ue_validarvacio(ls_seract);
	ls_idactivo=eval("f.txtidactivod"+li_row+".value");
	ls_idactivo=ue_validarvacio(ls_idactivo);

	window.open("sigesp_saf_d_partes.php?codact="+ls_codact+"&seract="+ls_seract+"&id="+ls_idactivo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=350,left=100,top=100,location=no,resizable=yes");
}

function ue_responsable()
{
	window.open("sigesp_saf_cat_personal.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_responsableuso(li_row)
{
	window.open("sigesp_saf_cat_personal.php?row="+li_row+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_unidad()
{
	window.open("sigesp_saf_cat_unidadfisica.php?destino=activo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_agregar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		if((f.txtcanact.value == " ")||(f.txtcanact.value == 0))
		{
		 alert("Cantidad a agregar invalida");
		}
		else
		{
		 f.operacion.value="AGREGARDETALLE";
		 f.action="sigesp_saf_d_seriales.php";
		 f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar(totrow)
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		for (li_row=1; li_row<=totrow ;li_row++)
		{
			ls_codact=eval("f.txtcodactd"+li_row+".value");
			ls_codact=ue_validarvacio(ls_codact);
			ls_seract=eval("f.txtseractd"+li_row+".value");
			ls_seract=ue_validarvacio(ls_seract);
			ls_chaact=eval("f.txtchaactd"+li_row+".value");
			ls_chaact=ue_validarvacio(ls_chaact);
			ls_unidad=eval("f.txtunidadd"+li_row+".value");
			ls_unidad=ue_validarvacio(ls_unidad);
			ls_responsable=eval("f.txtresponsabled"+li_row+".value");
			ls_responsable=ue_validarvacio(ls_responsable);
			ls_obser=eval("f.txtobserd"+li_row+".value");
			ls_obser=ue_validarvacio(ls_obser);
			ls_idactivo=eval("f.txtidactivod"+li_row+".value");
			ls_idactivo=ue_validarvacio(ls_idactivo);
		
			if((ls_codact=="")||(ls_seract=="")||(ls_chaact=="")||(ls_obser=="")||(ls_idactivo==""))
			{
				alert("Debe llenar todos los campos en la linea "+li_row+"");
				lb_valido=true;
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_saf_d_seriales.php";
				f.submit();
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cancelar()
{
	window.close();
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_cambiar=f.cambiar.value;
	if(li_cambiar==1)
	{	
		ls_codidnew=eval("f.txtidactivod"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codid=eval("f.txtidactivod"+li_i+".value");
			if((ls_codid==ls_codidnew)&&(li_i!=li_row))
			{
				alert("El activo ya esta registrado");
				lb_valido=true;
			}
		}
		ls_codact=eval("f.txtcodactd"+li_row+".value");
		ls_codact=ue_validarvacio(ls_codact);
		ls_seract=eval("f.txtseractd"+li_row+".value");
		ls_seract=ue_validarvacio(ls_seract);
		ls_chaact=eval("f.txtchaactd"+li_row+".value");
		ls_chaact=ue_validarvacio(ls_chaact);
		ls_unidad=eval("f.txtunidadd"+li_row+".value");
		ls_unidad=ue_validarvacio(ls_unidad);
		ls_responsable=eval("f.txtresponsabled"+li_row+".value");
		ls_responsable=ue_validarvacio(ls_responsable);
		ls_obser=eval("f.txtobserd"+li_row+".value");
		ls_obser=ue_validarvacio(ls_obser);
		ls_idactivo=eval("f.txtidactivod"+li_row+".value");
		ls_idactivo=ue_validarvacio(ls_idactivo);
	
		if((ls_codact=="")||(ls_seract=="")||(ls_chaact=="")||(ls_unidad=="")||(ls_responsable=="")||(ls_obser=="")||(ls_idactivo==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGAROTRODETALLE";
			f.action="sigesp_saf_d_seriales.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function uf_delete_dt(li_row)
{
	f=document.form1;
	ls_codact=eval("f.txtcodactd"+li_row+".value");
//	ls_codact=ue_validarvacio(ls_codact);
	ls_idact=eval("f.txtidactivod"+li_row+".value");
//	ls_idact=ue_validarvacio(ls_idact);
	if(ls_codact=="")
	{
		alert("la fila a eliminar no debe estar vacio el codigo del activo");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro "+ls_idact+"?"))
		{
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_saf_d_seriales.php";
			f.submit();
		}
	}
}

</script> 
</html>