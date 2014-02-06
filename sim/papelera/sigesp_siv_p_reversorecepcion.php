<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor -->valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=    str_replace(".",",",$as_valor);
		$li_poscoma = stripos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // 				as_valor         //  nombre de la variable que desamos obtener
    // 				as_valordefecto  //  contenido de la variable
    // Description: Función que obtiene el valor de una variable si viene de un submit
	//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------
   
   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_seleccionarcombo
	//	Access:    public
	//	Arguments:
	//  			  as_valores      // valores que puede tomar el combo
	//  			  as_seleccionado // item seleccionado
	// 				  aa_parametro    // arreglo de seleccionados
	//  			  li_total        // total de elementos en el combo
	//	Description:  Funcion que mantiene la seleccion de un combo despues de hacer un submit
	//              
	//////////////////////////////////////////////////////////////////////////////		
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }
   //--------------------------------------------------------------

   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_agregarlineablanca
	//	Access:    public
	//	Arguments:
	//  			  aa_object // arreglo de titulos 
	//  			  ai_totrows // ultima fila pintada en el grid
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows." type=text id=txtcodart".$ai_totrows." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:100px '><option value=D>Detal</option><option value=M>Mayor</option></select></div>";
		$aa_object[$ai_totrows][3]="<input name=txtcanart".$ai_totrows." type=text id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtpenart".$ai_totrows." type=text id=txtpenart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][6]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][9]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
   }
   //--------------------------------------------------------------
   function uf_agregarlineablancaorden(&$aa_object,$ai_totrows)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_agregarlineablanca
	//	Access:    public
	//	Arguments:
	//  			  aa_object // arreglo de titulos 
	//  			  ai_totrows // ultima fila pintada en el grid
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//				  cuando es una orden de compra
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows." type=text id=txtcodart".$ai_totrows." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:100px '><option value=D>Detal</option><option value=M>Mayor</option></select></div>";
		$aa_object[$ai_totrows][3]="<input name=txtcanart".$ai_totrows." type=text id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtpenart".$ai_totrows." type=text id=txtpenart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][6]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
   }
   //--------------------------------------------------------------
   function uf_pintartituloorden(&$lo_object,&$lo_title)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_pintartituloorden
	//	Access:    public
	//	Arguments:
	//  			  aa_object // arreglo de titulos 
	//  			  ai_totrows // ultima fila pintada en el grid
	//	Description:  Funcion que pinta el titulo del grid
	//				  cuando es una orden de compra
	//              
	//////////////////////////////////////////////////////////////////////////////		
			$lo_title="";
			$lo_object="";
			$lo_title[1]="Artículo";
			$lo_title[2]="Unidad de Medida";
			$lo_title[3]="Cantidad";
			$lo_title[4]="Pendiente";
			$lo_title[5]="Precio Unitario";
			$lo_title[6]="Cantidad Original";
			$lo_title[7]="Costo Total";
   }
   //--------------------------------------------------------------
   function uf_pintardetalle($ai_totrows,$ls_estpro)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_pintardetalle
	//	Access:    public
	//	Arguments:
	//  		      ai_totrows    // cantidad de filas que tiene el grid
	//				  ls_estpro     // indica que valor tiene el radiobutton O--> Orden de compra F--> Factura
	//  		      ls_checkedord // variable imprime o no "checked" para el radiobutton en la orden de compra
	//				  ls_checkedfac // variable imprime o no "checked" para el radiobutton en la factura
	//	Description:  Funcion que vuelve a pintar el detalle del grid tal cual como estaba.
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $lo_object;

		if($ls_estpro=="O")
		{
			$ls_checkedord="checked";
			$ls_checkedfac="";
		}
		elseif($ls_estpro=="F")
		{
			$ls_checkedord="";
			$ls_checkedfac="checked";
		}
		else
		{
			$ls_checkedord="";
			$ls_checkedfac="";
		}
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{	
			$la_unidad[0]="";
			$la_unidad[1]="";
			$ls_codart=    $_POST["txtcodart".$li_i];
			$ls_unidad=    $_POST["txtunidad".$li_i];
			$li_canart=    $_POST["txtcanart".$li_i];
			$li_penart=    $_POST["txtpenart".$li_i];
			$li_preuniart= $_POST["txtpreuniart".$li_i];
			$li_canoriart= $_POST["txtcanoriart".$li_i];
			$li_montotart= $_POST["txtmontotart".$li_i];
			//uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
					
			$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codart."' readonly>";
			$lo_object[$li_i][2]="<input name=txtunidad".$li_i." type=text id=txtunidad".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
			$lo_object[$li_i][3]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][4]="<input name=txtpenart".$li_i." type=text id=txtpenart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_penart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$lo_object[$li_i][6]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
			$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_montotart."' onKeyUp='javascript: ue_validarnumero(this);'>";
			$lo_object[$li_i][8]="";
			$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
	   } 
	   uf_agregarlineablanca($lo_object,$ai_totrows);
   }
  	//--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_numordcom,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ld_fecrec,$ls_obsrec;
		global $ls_checkedord,$ls_checkedfac,$ls_codusu,$ls_readonly;
		
		$ls_numordcom="";
		$ls_codpro="";
		$ls_denpro="";
		$ls_codalm="";
		$ls_nomfisalm="";
		$ld_fecrec="";
		$ls_obsrec="";
		$ls_checkedord="";
		$ls_checkedfac="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
   }
   
   function uf_obtenervalorunidad($li_i)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalorunidad
	//	Access:    public
	//	Arguments:
    // 				li_i         //  valor del 
    // 				ls_valor     //  nombre de la variable que desamos obtener
    // Description: Función que obtiene el contenido del combo cmbunidad o 
	//				del campo txtunidad deacuerdo sea el caso 
	//////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("cmbunidad".$li_i,$_POST))
		{
			$ls_valor= $_POST["cmbunidad".$li_i];
		}
		else
		{
			$ls_valoraux= $_POST["txtunidad".$li_i];
			if($ls_valoraux=="Mayor")
			{
				$ls_valor="M";
			}
			else
			{
				$ls_valor="D";
			}
		}
   		return $ls_valor; 
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reverso de Entrada de Suministros a Almac&eacute;n</title>
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
<link href="css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/cabecera.css" rel="stylesheet" type="text/css">
<link href="css/general.css" rel="stylesheet" type="text/css">
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?
	include     ("sigesp_sim_c_reversorecepcion.php");
	require_once("sigesp_sim_c_articuloxalmacen.php");
	require_once("sigesp_sim_c_movimientoinventario.php");
	require_once("..\shared\class_folder\class_sql.php");
	require_once("..\shared\class_folder\class_mensajes.php");
	require_once("..\shared\class_folder\class_funciones_db.php");
	require_once("..\shared\class_folder\class_funciones.php");
	require_once("..\shared\class_folder\sigesp_include.php");
	require_once("..\shared\class_folder\grid_param.php");
	$in_grid= new grid_param();
	$in=      new sigesp_include();
	$con=     $in->uf_conectar();
	$io_msg=  new class_mensajes();
	$io_fun=  new class_funciones_db($con);
	$io_func= new class_funciones();
	$io_sql=  new class_sql($con);
	$io_sim=  new sigesp_sim_c_reversorecepcion();
	$io_art=  new sigesp_sim_c_articuloxalmacen();
	$io_mov=  new sigesp_sim_c_movimientoinventario();

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("class_folder\sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["CodEmp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="sim";
	$ls_ventanas="sigesp_sim_p_recepcion.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			print("Bienvenido usuario SIGESP");
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["CodEmp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_count=count($arr);

	$li_totrows = uf_obtenervalor("totalfilas",1);

	$ls_titletable="Detalle de la Entrada";
	$li_widthtable=750;
	$ls_nametable="grid";
	$lo_title[1]="Artículo";
	$lo_title[2]="Unidad de Medida";
	$lo_title[3]="Cantidad";
	$lo_title[4]="Pendiente";
	$lo_title[5]="Precio Unitario";
	$lo_title[6]="Cantidad Original";
	$lo_title[7]="Costo Total";
	$lo_title[8]="";
	$lo_title[9]="";
	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_status=$_POST["hidestatus"];
		if ($ls_status=="C")
		{
			$ls_readonly=$_POST["hidreadonly"];
			if (array_key_exists("catafilas",$_POST))
			{
				$li_catafilas=$_POST["catafilas"];
			}
			else
			{
			$li_catafilas="";
			}
		}
		else
		{
			$ls_status="";
		}
	}
	else
	{
		$ls_operacion="";
		$ls_status="";
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,1);
	}
	switch ($ls_operacion) 
	{

		case "NUEVAFACTURA":
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_checkedord="";
			$ls_checkedfac="checked";

		break;
		case "NUEVAORDEN":
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_checkedord="checked";
			$ls_checkedfac="";
			uf_pintartituloorden($lo_object,$lo_title);
			uf_agregarlineablancaorden($lo_object,1);
		break;
		case "NUEVO":
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$li_totrows=1;

		break;
		case "GUARDAR":
			$ls_estpro=    $_POST["radiotipo"];
			$ls_numordcom= $_POST["txtnumordcom"];
			$ls_codpro=    $_POST["txtcodpro"];
			$ls_denpro=    $_POST["txtdenpro"];
			$ls_codalm=    $_POST["txtcodalm"];
			$ls_nomfisalm= $_POST["txtnomfisalm"];
			$ld_fecrec=    $_POST["txtfecrec"];
			$ls_obsrec=    $_POST["txtobsrec"];
///////////////////////////////////////////
//              REVISAR EL $ls_estpro --Listo--- ***Mosca!!!!
//////////////////////////////////////////
			if($ls_estpro==0)
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
				$ls_codprodoc="ORD";
			}
			else
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
				$ls_codprodoc="FAC";
			}
			$ls_readonly="readonly";
			$ld_fecrecbd=$io_func->uf_convertirdatetobd($ld_fecrec);
			if ($ls_status!="C")
			{
				$lb_encontrado=$io_sim->uf_sim_select_recepcion($ls_codemp,$ls_numordcom);
				if ($lb_encontrado)
				{
					$io_msg->message("Registro ya existe"); 
					uf_pintardetalle($li_totrows+1,$ls_estpro);
				}
				else
				{	
					$ls_estrec="0";  // verifica que es el $ls_estrec
					$io_sql->begin_transaction();
					$lb_valido=$io_sim->uf_sim_insert_recepcion($ls_codemp,$ls_numordcom,$ls_codpro,$ls_codalm,$ld_fecrecbd,
																$ls_obsrec,$ls_codusu,$ls_estpro,$ls_estrec,$la_seguridad);
					
					if ($lb_valido)
					{
						$ls_nummov=0;
						$ls_nomsol="Recepcion";
						$lb_valido=$io_mov->uf_sim_insert_movimiento(&$ls_nummov,$ld_fecrecbd,$ls_nomsol,$ls_codusu,
																     $la_seguridad);
					}
					if ($lb_valido)
					{
						if($ls_estpro==0)
						{
							$li_totrowsaux=$li_totrows+1;
						}
						else
						{
							$li_totrowsaux=$li_totrows;
						}
						for($li_i=1;$li_i<$li_totrowsaux;$li_i++)
						{
							$ls_unidad= uf_obtenervalorunidad($li_i);
							$ls_codart=    $_POST["txtcodart".$li_i];
							$li_canart=    $_POST["txtcanart".$li_i];
							$li_penart=    $_POST["txtpenart".$li_i];
							$li_preuniart= $_POST["txtpreuniart".$li_i];
							$li_canoriart= $_POST["txtcanoriart".$li_i];
							$li_montotart= $_POST["txtmontotart".$li_i];
							$li_monsubart= $_POST["txtmontotart".$li_i];
							$lb_valido=$io_sim->uf_sim_insert_dt_recepcion($ls_codemp,$ls_numordcom,$ls_codart,$ls_unidad,$li_canart,
																		   $li_penart,$li_preuniart,$li_monsubart,$li_montotart,
																		   $li_i,$li_canoriart,$la_seguridad);
							if ($lb_valido)
							{
								$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																					 $li_canart,$la_seguridad);
								if($lb_valido)
								{
									$ls_opeinv="ENT";
									$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecrecbd,
																					$ls_codart,$ls_codalm,$ls_opeinv,
																					$ls_codprodoc,$ls_numordcom,$li_canart,
																					$li_montotart,$la_seguridad);
								}
							}
							if($lb_valido)
							{
								$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$la_seguridad);
							}

						}
					}
					
					
					if($lb_valido)
					{
						$io_sql->commit();
						$io_msg->message("El registro fue incluido con exito");
						uf_agregarlineablanca($lo_object,1);
						uf_limpiarvariables();
						$li_totrows=1;
					}
					else
					{
						$io_sql->rollback();
						$io_msg->message("No se pudo incluir el registro");
						uf_pintardetalle($li_totrowsaux,$ls_estpro);
					}
				}
			}
			break;

		case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			$ls_readonly="";
			$ls_radiotipo= $_POST["radiotipo"];
			if($ls_radiotipo=="0")
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
			}
			if ($ls_radiotipo=="1")
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
			}
			$ls_numordcom= $_POST["txtnumordcom"];
			$ls_codpro=    $_POST["txtcodpro"];
			$ls_denpro=    $_POST["txtdenpro"];
			$ls_codalm=    $_POST["txtcodalm"];
			$ls_nomfisalm= $_POST["txtnomfisalm"];
			$ld_fecrec=    $_POST["txtfecrec"];
			$ls_obsrec=    $_POST["txtobsrec"];

			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{	
				$la_unidad[0]="";
				$la_unidad[1]="";
				$ls_codart=    $_POST["txtcodart".$li_i];
				$ls_unidad=    $_POST["cmbunidad".$li_i];
				$li_canart=    $_POST["txtcanart".$li_i];
				$li_penart=    $_POST["txtpenart".$li_i];
				$li_preuniart= $_POST["txtpreuniart".$li_i];
				$li_canoriart= $_POST["txtcanoriart".$li_i];
				$li_montotart= $_POST["txtmontotart".$li_i];
				uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
				
				if (($ls_status=="C")&&($li_i<=$li_catafilas))
				{
					
				}
				else
				{
					$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codart."' onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][2]="<div align='center'><select name=cmbunidad".$li_i." style='width:100px '><option value=D ".$la_unidad[0].">Detal</option><option value=M ".$la_unidad[1].">Mayor</option></select></div>";
					$lo_object[$li_i][3]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][4]="<input name=txtpenart".$li_i." type=text id=txtpenart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_penart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][6]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_montotart."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][8]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";			
				}

			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
			break;

		case "ELIMINARDETALLE":
			break;
		case "BUSCARDETALLEORDEN":
			$ls_readonly=  $_POST["hidreadonly"];
			$ls_numordcom= $_POST["txtnumordcom"];
			$ls_codpro=    $_POST["txtcodpro"];
			$ls_denpro=    $_POST["txtdenpro"];
			$ls_codalm=    $_POST["txtcodalm"];
			$ls_nomfisalm= $_POST["txtnomfisalm"];
			$ld_fecrec=    $_POST["txtfecrec"];
			$ls_obsrec=    $_POST["txtobsrec"];
			$data="";
			$li_totrows=0;
			$ld_fecrec1=$io_func->uf_convertirdatetobd($ld_fecrec);
			$lb_valido=$io_sim->uf_sim_obtener_dt_recepcion($ls_codemp,$ls_numordcom,&$data,&$li_totrows);
			if ($lb_valido)
			{
				$li_catafilas=$li_totrows;
				uf_pintartituloorden($lo_object,$lo_title);
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$la_unidad[0]="";
					$la_unidad[1]="";
					$ls_codart=    $data["codart"][$li_i];
					$ls_unidad=    $data["unidad"][$li_i];
					$li_preuniart= $data["preuniart"][$li_i];
					$li_canoriart= $data["canart"][$li_i];
					$li_canart=    $data["canart"][$li_i];
					$li_penart=    $data["penart"][$li_i];
					$li_montotart= $data["montotart"][$li_i];
					$li_preuniart= uf_formatonumerico($li_preuniart);
					$li_canoriart= uf_formatonumerico($li_canoriart);
					$li_canart=    uf_formatonumerico($li_canart);
					$li_penart=    uf_formatonumerico($li_penart);
					$li_montotart= uf_formatonumerico($li_montotart);
					switch ($ls_unidad) 
					{
						case "M":
							$ls_unidadaux="Mayor";
							break;
						case "D":
							$ls_unidadaux="Detal";
							break;
					}
					$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=15 maxlength=15 value='".$ls_codart."' readonly>";
					$lo_object[$li_i][2]="<input name=txtunidad".$li_i." type=text id=txtunidad".$li_i." class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly>";
					$lo_object[$li_i][3]="<input name=txtcanart".$li_i." type=text id=txtcanart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canart."' readonly>";
					$lo_object[$li_i][4]="<input name=txtpenart".$li_i." type=text id=txtpenart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_penart."' readonly>";
					$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
					$lo_object[$li_i][6]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' readonly>";
					$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_montotart."' readonly>";
				}
			}
			else
			{
				$li_totrows=1;
				uf_agregarlineablancaorden($lo_object,$li_totrows);
			}

			break;
		case "BUSCARDETALLE":
			break;
			
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="767" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="669" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="755" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="744" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="2" class="titulo-ventana">Reverso de Entrada de Suministros a Almac&eacute;n </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="154" height="19">&nbsp;</td>
                    <td width="578"><input name="hidestatus" type="hidden" id="hidestatus" value="<? print $ls_status?>">
                      <input name="hidreadonly" type="hidden" id="hidreadonly"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right"> </div></td>
                    <td><div align="left">
                          </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Orden de Compra/Factura</div></td>
                    <td>
                      <div align="left">
                        <input name="txtnumordcom" type="text" id="txtnumordcom" value="<? print $ls_numordcom?>" size="16" maxlength="15"<? print $ls_readonly?>>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Proveedor</div></td>
                    <td>
                      <div align="left">
                        <input name="txtcodpro" type="text" id="txtcodpro" value="<? print $ls_codpro?>" size="11" maxlength="10" readonly>
                          <a href="javascript: ue_cataproveedor();"><img src="imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
                          <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<? print $ls_denpro ?>" size="50" readonly>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Almac&eacute;n</div></td>
                    <td>
                      <div align="left">
                        <input name="txtcodalm" type="text" id="txtcodalm" value="<? print $ls_codalm ?>" size="11" maxlength="10" readonly>
                          <a href="javascript: ue_catalmacen();"><img src="imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
                          <input name="txtnomfisalm" type="text" class="sin-borde" id="txtnomfisalm" value="<? print $ls_nomfisalm ?>" size="50" readonly>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Fecha</div></td>
                    <td>
                      <div align="left">
                        <input name="txtfecrec" type="text" id="txtfecrec" onKeyPress="ue_separadores(this,'/',patron,true);" value="<? print $ld_fecrec ?>" size="10" maxlength="10" datepicker="false" readonly>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="29"><div align="right">Observaci&oacute;n</div></td>
                    <td rowspan="2">
                      <div align="left">
                        <textarea name="txtobsrec" cols="40" rows="3" id="txtobsrec" readonly><? print $ls_obsrec ?></textarea>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20">&nbsp;</td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="20">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="13">&nbsp;</td>
                    <td>                      <input name="txtdesalm" type="hidden" id="txtdesalm">
                      <input name="txttelalm" type="hidden" id="txttelalm">
                      <input name="txtubialm" type="hidden" id="txtubialm">
                      <input name="txtnomresalm" type="hidden" id="txtnomresalm">
                      <input name="txttelresalm" type="hidden" id="txttelresalm">
                      <input name="hidstatus" type="hidden" id="hidstatus"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="30" colspan="2"><p>
                      <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                    </p>                      </td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="28" colspan="2"><div align="center">
                      <input name="Submit" type="submit" class="boton" value="Reversar">
                    </div></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><div align="center">
                </div></td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
                    <input name="totalfilas" type="hidden" id="totalfilas" value="<? print $li_totrows;?>">
                    <input name="filadelete" type="hidden" id="filadelete">
                    <input name="catafilas" type="hidden" id="catafilas" value="<? print $li_catafilas;?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
function ue_catarticulo(li_linea)
{
	window.open("sigesp_catdinamic_articulom.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_catalmacen()
{
	window.open("sigesp_catdinamic_almacen.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cataproveedor()
{
	f=document.form1;
	if(f.radiotipo[1].checked)
	{
		window.open("sigesp_catdinamic_prov.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}
function ue_cataorden()
{
	f=document.form1;
	if(f.radiotipo[0].checked)
	{
		window.open("sigesp_catdinamic_ordenes.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
		f.operacion.value="NUEVAORDEN";
		f.action="sigesp_sim_p_recepcion.php";
		f.submit();
	}
	if(f.radiotipo[1].checked)
	{
		f.txtnumordcom.value="";
		f.txtcodpro.value="";
		f.txtdenpro.value="";
		f.operacion.value="NUEVAFACTURA";
		f.action="sigesp_sim_p_recepcion.php";
		f.submit();
	}
	
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_movimiento.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_nuevo()
{
	window.open("sigesp_catdinamic_revrecepcion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function uf_agregar_dt(li_row)
{
	f=document.form1;
	ls_codnewart= eval("f.txtcodart"+li_row+".value");
	ls_codnewuni= eval("f.cmbunidad"+li_row+".value");
	ls_codnewcan= eval("f.txtcanart"+li_row+".value");
	ls_codnewpen= eval("f.txtpenart"+li_row+".value");
	ls_codnewpre= eval("f.txtpreuniart"+li_row+".value");
	ls_codnewori= eval("f.txtcanoriart"+li_row+".value");
	ls_codnewmon= eval("f.txtmontotart"+li_row+".value");
	li_total=f.totalfilas.value;
	lb_valido=false;
	
	for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
	{
		ls_codart=    eval("f.txtcodart"+li_i+".value");
		ls_unidad=    eval("f.cmbunidad"+li_i+".value");
		ls_canart=    eval("f.txtcanart"+li_i+".value");
		ls_penart=    eval("f.txtpenart"+li_i+".value");
		ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
		ls_canoriart= eval("f.txtcanoriart"+li_i+".value");
		ls_montotord= eval("f.txtmontotart"+li_i+".value");
		if((ls_codart==ls_codnewart)&&(ls_unidad==ls_codnewuni)&&(li_i!=li_row))
		{
			alert("El detalle ya esta registrado");
			lb_valido=true;
		}
	}
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_penart=eval("f.txtpenart"+li_row+".value");
	ls_penart=ue_validarvacio(ls_penart);
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	ls_montotord=eval("f.txtmontotart"+li_row+".value");
	ls_montotord=ue_validarvacio(ls_montotord);

	if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_penart=="")||(ls_preuniart=="")||(ls_canoriart=="")||(ls_montotord==""))
	{
		alert("Debe llenar todos los campos");
		lb_valido=true;
	}

	

	ls_aux=(parseFloat(ls_canart) + parseFloat(ls_penart));
	if (ls_aux!=ls_canoriart)
	{
		alert("No concuerdan las cantidades de articulos");
		lb_valido=true;	
	}

	ls_numordcom=eval("f.txtnumordcom.value");
	ls_numordcom=ue_validarvacio(ls_numordcom);
	ls_codpro=eval("f.txtcodpro.value");
	ls_codpro=ue_validarvacio(ls_codpro);
	ls_codalm=eval("f.txtcodalm.value");
	ls_codalm=ue_validarvacio(ls_codalm);
	ls_fecrec=eval("f.txtfecrec.value");
	ls_fecrec=ue_validarvacio(ls_fecrec);
	
	if((ls_numordcom=="")||(ls_codpro=="")||(ls_codalm=="")||(ls_fecrec==""))
	{
		alert("Debe llenar los campos principales");
		lb_valido=true;
	}

	if(!lb_valido)
	{
		f.operacion.value="AGREGARDETALLE";
		f.action="sigesp_sim_p_recepcion.php";
		f.submit();
	}
}

function uf_delete_dt(li_row)
{
/*	f=document.form1;
	li_lappervac=eval("f.txtlappervac"+li_row+".value");
	li_lappervac=ue_validarvacio(li_lappervac);
	if(li_lappervac=="")
	{
		alert("la fila a eliminar no debe estar vacio el lapso");
	}
	else
	{*/
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	f=document.form1;
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_sim_p_movimiento.php";
			alert(f.filadelete.value);
			f.submit();
		}
//	}
}

function ue_guardar()
{
	f=document.form1;
	ls_numordcom=eval("f.txtnumordcom.value");
	ls_numordcom=ue_validarvacio(ls_numordcom);
	ls_codpro=eval("f.txtcodpro.value");
	ls_codpro=ue_validarvacio(ls_codpro);
	ls_codalm=eval("f.txtcodalm.value");
	ls_codalm=ue_validarvacio(ls_codalm);
	ls_fecrec=eval("f.txtfecrec.value");
	ls_fecrec=ue_validarvacio(ls_fecrec);
	
	if ((ls_numordcom=="")||(ls_codpro=="")||(ls_codalm=="")||(ls_fecrec==""))
	{
		alert("Debe llenar los campos principales");
	}
	else
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_sim_p_recepcion.php";
		f.submit();
	}
}

function ue_eliminar()
{
	if(confirm("¿Seguro desea eliminar el Usuario?"))
	{
		f=document.form1;
		f.operacion.value="ELIMINAR";
		f.action="sigesp_sim_p_tipoarticulo.php";
		f.submit();
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if(texto != "'")
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9")||(texto=="."))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumerosinpunto(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Función que valida que el texto no esté vacio
//--------------------------------------------------------
function ue_validarvacio(valor)
{
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}

</script> 
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>