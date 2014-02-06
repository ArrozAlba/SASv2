<?Php
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/

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
<title>Definici&oacute;n de Cajero</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
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
.Estilo2 {font-size: 9}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="542" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturacion</span></td>
    <td width="236" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("class_folder/sigesp_sfc_c_cajero.php");
require_once("../shared/class_folder/class_mensajes.php");
	
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");


$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();

$is_msg=new class_mensajes();
$io_cajero=new sigesp_sfc_c_cajero();
$ls_total_facturado=0;	
	
/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		/*$ls_codusu="%".$_POST["txtcodusu"]."%";
		/*$ls_nomusu=$_POST["txtnomusu"];*/
    }
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion="";
		/*$ls_codusu="";
		$ls_nomusu="";*/
	}
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
/*/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
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
//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////*/
?>	

    <table width="518" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="195"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Cierre de tienda e integraci&oacute;n  de movimientos </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus">			  </td>
              <td >&nbsp;</td>
            </tr>
            
			<tr>
              <td width="76" height="22" align="right">&nbsp;</td>
              <td width="392" ><!-- javascript:ue_catusuario(); -->
                <a href="javascript:ue_procesar();"><img src="Imagenes/ejecutar.gif" width="20" height="20" border="0">Generar archivo de integraci&oacute;n</a><a href="javascript:ue_catusuario();"></a></td>
			</tr>
            <tr>
              <td width="76" height="22" align="right">&nbsp;</td>
              <td width="392" >&nbsp;</td>
            </tr>
            
            <tr>
              <td height="8" colspan="2"><table width="415" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td height="17" colspan="2" align="right" class="titulo-ventana">Resumen</td>
                </tr>
                <tr>
                  <td width="142" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
				<tr>
					<td height="17" align="center" class="titulo-ventana">Forma de pago</td>
					<td height="17" align="center" class="titulo-ventana">total</td>
			      </tr>
				<?PHP
/************************************************************************************************************************/
/***************************   PROCESAR ********************************************************************************/
/************************************************************************************************************************/

if($ls_operacion=="PROCESAR")
{

				 $ls_operacion="";			
				//RECORRE LOS DATOS
				$ls_sql="SELECT fp.codforpag ,fp.denforpag, SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='N' GROUP BY fp.codforpag";
				 /* $ls_sql="SELECT * FROM sfc_formapago";*/
				 
				$rs_data=$io_sql->select($ls_sql);
			   
/*******************************************************************************************************************************
				if($rs_data==false)
				 {
						$lb_valido=false;
						$is_msg->message ("error en select.");
				 }
				else
				 {
						if($row=$io_sql->fetch_row($rs_data))
						 {
							$lb_valido=true;			
						 }
						else
						 {
							$lb_valido=false;
							$is_msg->message ("Registro no encontrado.");
		  				 }
				 }
/*********************************************************************************************************************************/
		       
			   
			   	
				if($row=$io_sql->fetch_row($rs_data))
				{
					$data=$io_sql->obtener_datos($rs_data);
					
					$io_datastore->data=$data;
					$li_totrow=$io_datastore->getRowCount("codforpag");
						
					for($li_z=1;$li_z<=$li_totrow;$li_z++)
					{
				  
				 		$ls_desforpag=$data["denforpag"][$li_z];
						
						$ls_total=number_format($data["total"][$li_z],2, ',', '.'); 
						
						
						$ls_total_facturado=$ls_total_facturado + $data["total"][$li_z];
				?>
				
                <tr>
					<td height="26" align="center" style=""><?php print $ls_desforpag ?></td>
					<td height="26" align="center" td><?php print $ls_total ?></td>
                  </tr>
				<?PHP
				   }	
				?>
				<tr>
                   <td height="22" align="right" class="titulo-ventana">Total facturado </td>
                   <td  class="titulo-ventana"><span class="titulo-ventana"><?php print number_format($ls_total_facturado,2, ',', '.') ?></span></td>
                  </tr>
<?PHP
               }
} /************************************** FIN DE RUTINA "PROCESAR" *****************************************************/
?>
				  
				
               </table>
              <p>&nbsp;</p></td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<?PHP
/************************************************************************************************************************/
/***************************   VER -> HACE UN QUERY STRING AL REPORTE ***************************************************/
/************************************************************************************************************************/

if($ls_operacion=="VER")
{
        $ls_operacion="";
		
  	    $ls_sql="SELECT f.numfac,f.codcli,c.razcli,fp.codforpag ,fp.denforpag, i.monto FROM sfc_cliente c,sfc_formapago fp,sfc_instpago i,sfc_factura f WHERE f.codcli=c.codcli AND i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='N' ORDER BY numfac ASC;";
/*print $ls_sql;*/
?>
       
     <script language="JavaScript">  
   	 	var ls_sql="<?php print $ls_sql; ?>"; 
	   	pagina="reportes/sigesp_sfc_rep_cierrecaja.php?sql="+ls_sql;
	  	popupWin(pagina,"catalogo",580,700);
     </script> 
       
<?PHP
} 
/************************************************************************************************************************/
/***************************************   FIN DEL FORMULARIO  **********************************************************/
/************************************************************************************************************************/
?>
</form>
</body>

<script language="JavaScript">


/*******************************************************************************************************************************/
function ue_procesar()
{

  f=document.form1;
  f.operacion.value="PROCESAR";
  f.action="sigesp_sfc_d_cierretienda.php";
  f.submit();
  
  }
 function ue_ver()
  {

  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_cierretienda.php";
  f.submit();
  
  } 
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="ue_nuevo";
	f.txtcodusu.value="";
	f.txtnomusu.value="";
	f.txtcodtie.value="";
	f.txtnomtie.value="";
	f.action="sigesp_sfc_d_cajero.php";
	f.submit();
/*	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{		
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}*/	
}


function ue_guardar()
{
	f=document.form1;
	with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{ 
				if (ue_valida_null(txtcodtie,"Tienda")==false)
				{
				  txtcodtie.focus();
				}
				else
				{
					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cajero.php";
					f.submit();
				} 
			}
		}
	/*li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}*/
}					
					
function ue_eliminar()
{
	f=document.form1;
	with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (confirm("¿ Esta seguro de eliminar este registro ?"))
				{ 		
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_cajero.php";
					f.submit();
				}
				else
				{ 
					f=document.form1;
					f.action="sigesp_sob_d_cajero.php";
					alert("Eliminación Cancelada !!!");
					f.txtcodusu.value="";
					f.txtnomusu.value="";
					f.txtcodtie.value="";
					f.txtnomuni.value="";
					f.operacion.value="";
					f.submit();
				}
			}	   
		}
	/*li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
    }
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}*/
}

function ue_buscar()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_cajero.php";
	popupWin(pagina,"catalogo",650,300);
	/*li_leer=f.leer.value;
	if(li_leer==1)
	{
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}*/
}

/***********************************************************************************************************************************/        
		
		function ue_catusuario()
		{
            f=document.form1;
			f.operacion.value="";						
		    pagina="../sss/sigesp_sss_cat_usuarios.php?destino=Reporte";
	    	popupWin(pagina,"catalogo",520,200);
		}
		
		function ue_cattienda()
		{
            f=document.form1;
			f.operacion.value="";						
		    pagina="sigesp_cat_tienda.php";
	    	popupWin(pagina,"catalogo",520,200);
		}
		
		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar)
		{
 	   	    f=document.form1;
			f.txtcodtie.value=codtie;
        	f.txtnomtie.value=nomtie;

		}
		
		function ue_cargarcajero(codusu,nomusu,codtie,nomtie)
		{
		    f=document.form1;
			f.txtcodusu.value=codusu;
			f.txtnomusu.value=nomusu;
			f.txtcodtie.value=codtie;
			f.txtnomtie.value=nomtie;
       	}
/***********************************************************************************************************************************/

</script>
</html>