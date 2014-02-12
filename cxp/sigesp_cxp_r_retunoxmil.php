<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='index.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_folder/class_funciones_cxp.php");
$io_fun_banco= new class_funciones_cxp();
$io_fun_banco->uf_load_seguridad("CXP","sigesp_cxp_r_retunoxmil.php",$ls_permisos,$la_seguridad,$la_permisos);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Comprobantes de Retenci&oacute;n</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?php
if (array_key_exists("txtcodigo1",$_POST))
   {
     $ls_codprov1=$_POST["txtcodigo1"];	   
   }
else
   {
     $ls_codprov1="";
   }
if (array_key_exists("txtcodigo2",$_POST)) 
   {  
     $ls_codprov2 =$_POST["txtcodigo2"];	 
	 print $ls_codprov2."<br>";
   }
else
   {
     $ls_codprov2="";
  }
if  (array_key_exists("radiocategoria",$_POST))
	{
	  $ls_tipo=$_POST["radiocategoria"];
	}
else
	{
	  $ls_tipo="P";
	}			
if	(array_key_exists("txtfechadesde",$_POST))
	{
	  $ls_fechadesde=$_POST["txtfechadesde"];
    }
else
	{
	  $ls_fechadesde="";
	}  
if	(array_key_exists("txtfechahasta",$_POST))
	{
	  $ls_fechahasta=$_POST["txtfechahasta"];
    }
else
	{
	  $ls_fechahasta="";
	}  	
if	(array_key_exists("txtnumrecdoc",$_POST))
	{
	  $ls_numrecdoc=$_POST["txtnumrecdoc"];
    }
else
	{
	  $ls_numrecdoc="";
	} 
if	(array_key_exists("totnum",$_POST))
	{
	  $li_total=$_POST["totnum"];
    }
else
	{
	  $li_total=0;
	}  	
if (array_key_exists("txtnumded",$_POST))
   {
     $ls_numded=$_POST["txtnumded"];	   
   }
else
   {
     $ls_numded="";
   }	 	
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Eliminar" width="20" height="20" border="0"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
           <p>&nbsp;</p>
           <div align="center"></div>
<div align="center">
           <form name="form1" method="post" action="">
             <p align="left">
               <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
             </p>
             <table width="503" border="0" cellpadding="0" cellspacing="0" class="contorno">
               <tr class="titulo-celdanew">
                 <td height="22" colspan="6" class="titulo-celdanew">Comprobantes de Retenci&oacute;n de 1 x Mil </td>
               </tr>
               <tr>
                 <td height="53">&nbsp;</td>
                 <td height="53" colspan="5"><strong>Deducci&oacute;n</strong>
                   <input name="txtnumded" type="text" id="txtnumded" style="text-align:center "  onKeyPress="return keyRestrict(event,'1234567890');" value="<?php print $ls_numded ?>" size="10" maxlength="5"  onBlur="javascript:llenar_cad(this.value,5)">
                   <a href="javascript:uf_catalogo_deducciones();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                   <input name="txtdended" type="text" class="sin-borde" id="txtdended" value="<?php print $ls_dended ?>" size="40" maxlength="40">
                   <strong>
                   <input name="hidcatalogo2" type="hidden" id="hidcatalogo2" value="0">
                   </strong><strong>
                   <input name="operacion2" type="hidden" id="operacion2" value="<?php print $ls_operacion ?>">
                   </strong></td>
               </tr>
               <tr>
                 <td height="53" colspan="6"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
                   <tr>
                     <td style="text-align:left "><strong>Categoria
<?php 	 
  if (($ls_tipo=="P")||($ls_tipo==""))
	 {
		$ls_proveedor   ="checked";		
		$ls_beneficiario="";
		$ls_ambos       ="";		
	 }
   if($ls_tipo=="B")
	 {
	   $ls_proveedor   ="";
	   $ls_beneficiario="checked";
	   $ls_ambos       ="";
	 }
   if ($ls_tipo=="A")
	 {
	   $ls_proveedor   ="";
	   $ls_beneficiario="";
	   $ls_ambos       ="checked";
	 } 	
  ?>
<input name="hidcatalogo" type="hidden" id="hidcatalogo" value="0">
</strong></td>
                     <td><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>"></td>
                     <td>&nbsp;</td>
                   </tr>
                   <tr class="formato-blanco">
                     <td width="151" height="23"><div align="center">Proveedor
                             <input name="radiocategoria" type="radio"  onClick="javascript:uf_cambio()" value="P" checked  <?php print $ls_proveedor ?>>
                     </div></td>
                     <td width="130">Beneficiarios
                         <input name="radiocategoria" type="radio" value="B" <?php print $ls_beneficiario ?> onClick="javascript:uf_cambio()"></td>
                     <td width="131">Ambos
                         <input name="radiocategoria" type="radio" value="A" <?php print $ls_ambos ?>  onClick="javascript:uf_cambio()"></td>
                   </tr>
                 </table></td>
               </tr>
               <tr>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
               </tr>
               <tr>
                 <td height="44" colspan="6"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                     <tr>
                       <td colspan="4" style="text-align:left">&nbsp;</td>
                   </tr>
                     <tr>
                       <td width="78" align="right" ><strong>N&deg; Solicitud </strong></td>
                       <td width="134"><input name="txtnumsol" type="text" id="txtnumsol" value="<?php print $ls_numsol ?>" size="20" maxlength="10"  style="text-align:center " onKeyPress="return keyRestrict(event,'1234567890');">
                         <a href="javascript:uf_catalogoprov();"></a></td>
                       <td width="74" align="right"><strong>N&deg; Cheque </strong></td>
                       <td width="127"><input name="txtnumche" type="text" id="txtnumche" value="<?php print $ls_numche ?>" size="20" maxlength="10"  style="text-align:center"  onKeyPress="return keyRestrict(event,'1234567890');">
                           <a href="javascript:uf_catalogoprov();"></a></td>
                     </tr>
                   </table>                     </td>
               </tr>
               <tr>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
                 <td height="13">&nbsp;</td>
               </tr>
               <tr>
                 <td height="44" colspan="6"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                   <tr>
                     <td colspan="4" style="text-align:left"><strong>Rango de C&oacute;digos (Proveedores/Beneficiarios) </strong></td>
                   </tr>
                   <tr>
                     <td width="78" align="right" >Desde</td>
                     <td width="134"><input name="txtcodigo1" type="text" id="txtcodigo1" value="<?php print $ls_codprov1 ?>" size="12" maxlength="10"  style="text-align:center "  onBlur="javascript:rellenar_cad(this.value,10,this.name)"  onKeyPress="return keyRestrict(event,'1234567890');">
                         <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif"  id="buscar1" width="15" height="15" border="0" onClick="document.form1.hidrangocodigos.value=1"></a></td>
                     <td width="74" align="right">Hasta</td>
                     <td width="127"><input name="txtcodigo2" type="text" id="txtcodigo2" value="<?php print $ls_codprov2 ?>" size="12" maxlength="10"  style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,10,this.name)"  onKeyPress="return keyRestrict(event,'1234567890');">
                         <a href="javascript:uf_catalogoprov();"><img src="../shared/imagebank/tools15/buscar.gif" id="buscar2" width="15" height="15" border="0"  onClick="document.form1.hidrangocodigos.value=2"></a><strong>
                         <input name="hidrangocodigos" type="hidden" id="hidrangocodigos">
                       </strong></td>
                   </tr>
                 </table></td>
               </tr>
               <tr>
                 <td width="38" height="13">&nbsp;</td>
                 <td width="125" height="13">&nbsp;</td>
                 <td width="3" height="13">&nbsp;</td>
                 <td width="77" height="13">&nbsp;</td>
                 <td width="67" height="13">&nbsp;</td>
                 <td width="191" height="13">&nbsp;</td>
               </tr>
               <tr>
                 <td height="47" colspan="6"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                   <tr>
                     <td colspan="4" style="text-align:left"><strong>Rango de Fechas </strong></td>
                   </tr>
                   <tr>
                     <td width="77" height="24" align="right">Desde</td>
                     <td width="133"><div align="left">
                         <input name="txtfechadesde" type="text" id="txtfechadesde" value="<?php print $ls_fechadesde ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onKeyPress="currencyDate(this);">
                     </div></td>
                     <td width="73" align="right">Hasta</td>
                     <td width="130"><div align="left">
                         <input name="txtfechahasta" type="text" id="txtfechahasta" value="<?php print $ls_fechahasta ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onKeyPress="currencyDate(this);">
                         <span style="text-align:left">
                         <input name="totnum" type="hidden" id="totnum" value="<?php print $li_total ?>">
                         </span></div></td>
                   </tr>
                 </table></td>
               </tr>
               <tr>
                 <td height="13" colspan="6">&nbsp;</td>
               </tr>
               <tr>
                 <td height="22" colspan="6"><div align="right"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar Documentos</a></div></td>
               </tr>
             </table>
             <p>&nbsp;</p>
             <p>
               <?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/grid_param.php");

$io_in      = new sigesp_include();
$con        = $io_in->uf_conectar();
$io_ds      = new class_datastore();
$io_sql     = new class_sql($con);
$io_sql2    = new class_sql($con);
$io_msg     = new class_mensajes();
$io_funcion = new class_funciones(); 
$io_grid    = new grid_param();
$la_emp     = $_SESSION["la_empresa"];
  
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
   }
else
   {
	 $ls_operacion="";	
   }
$ls_color = '#FF5500';
//Titulos de la Tabla
      $title[1]="<input name=chktodos type=checkbox id=chktodos value=1 style=height:15px;width:15px onClick=javascript:uf_select_all(); >Todos";
	  $title[2]="Nro Documento"; 
	  $title[3]="Concepto"; 
	  $title[4]="Procede"; 
	  $grid    ="grid_comprobante";
//Fin Titulos de La Tabla
   
if ($ls_operacion=="BUSCAR")
   {
	  $z=0;
	  $ls_numsol = $_POST["txtnumsol"];
	  $ls_numche = $_POST["txtnumche"];
	  $ls_str ="";
	  $ls_str1="";
	  $ls_str2="";
	  $ls_str3="";
	  $ls_str4="";
	  $ls_str5="";
	  $ls_str6="";
	  $ls_fechadesde=$io_funcion->uf_convertirdatetobd($ls_fechadesde);
	  $ls_fechahasta=$io_funcion->uf_convertirdatetobd($ls_fechahasta);
	  $ls_codemp    =$la_emp["codemp"];
	  if (!empty($ls_fechadesde) && empty($ls_fechahasta))
	     {
		   $ls_str1= " AND XSP.fecemisol='".$ls_fechadesde."'";
		 }
	  if (!empty($ls_fechadesde) && !empty($ls_fechahasta))
	     {
		   $ls_str1= " AND XSP.fecemisol BETWEEN '".$ls_fechadesde."' AND '".$ls_fechahasta."'";
		 }
		 
	  if ($ls_tipo=='P')
	     {
		   if (!empty($ls_codprov1) && empty($ls_codprov2))
		      {
			   $ls_str2=" AND XSP.cod_pro='".$ls_codprov1."'";
			  }
		   elseif(!empty($ls_codprov1) && !empty($ls_codprov2))
		      {
			    $ls_str2=" AND XSP.cod_pro BETWEEN '".$ls_codprov1."' AND '".$ls_codprov2."'";
			  }   
		 }
	  elseif($ls_tipo=='B')
	     {
		   if (!empty($ls_codprov1) && empty($ls_codprov2))
		      {
			    $ls_str2=" AND XSP.ced_bene='".$ls_codprov1."'";
			  }
		   elseif(!empty($ls_codprov1) && !empty($ls_codprov2))
		      {
			    $ls_str2=" AND XSP.ced_bene BETWEEN '".$ls_codprov1."' AND '".$ls_codprov2."'";
			  }   
		 }
	  if (!empty($ls_numrecdoc))
	     {
		   $ls_str3=" AND XRD.numrecdoc ='".$ls_numrecdoc."'";
		 }
	  $ls_cadena=$ls_str.$ls_str1.$ls_str2.$ls_str3;
	  $ls_orden=" ORDER BY XSP.numsol ASC ";
	  $ls_sql  =" SELECT DISTINCT ".
	            " 0 AS seleccionado,XSP.numsol, XSP.consol, XSP.fecemisol, XSP.tipproben, XSP.cod_pro, XSP.ced_bene, XSP.procede ".
			    " FROM cxp_solicitudes XSP, cxp_dt_solicitudes XDS,cxp_rd XRD,cxp_rd_deducciones XDC,sigesp_deducciones ded ".
			    " WHERE ".
			    "   (XSP.numsol=XDS.numsol   AND XSP.cod_pro=XDS.cod_pro   AND XSP.ced_bene=XDS.ced_bene)  AND ".
			    "   (XDS.cod_pro=XRD.cod_pro AND XDS.ced_bene=XRD.ced_bene AND XDS.codtipdoc=XRD.codtipdoc AND XDS.numrecdoc=XRD.numrecdoc) AND ".
			    "   (XRD.cod_pro=XDC.cod_pro AND XRD.ced_bene=XDC.ced_bene AND XRD.codtipdoc=XDC.codtipdoc AND XRD.numrecdoc=XDC.numrecdoc) AND 
				    XDC.codded=ded.codded    AND ded.estretmun=1  AND XDC.codded='".$ls_numded."' AND XDC.codemp='".$ls_codemp."' AND XSP.numsol LIKE '%".$ls_numsol."%'";
					
	  $ls_sql=$ls_sql.$ls_cadena.$ls_orden;  
	  $rs_data=$io_sql->select($ls_sql);
	  $data=$rs_data;
	  $li_total = 0;
	  
	  if ($row=$io_sql->fetch_row($rs_data))
	     {
		   $data=$io_sql->obtener_datos($rs_data);
		   $io_dsdeducciones->data=$data;
		   $li_tota=$io_sql->num_rows($rs_data); 
	       for ($i=1;$i<=$li_tota;$i++)
		       {
				 $li_total++;
				 $ls_numsol   = $data["numsol"][$i];
		         $ls_concepto = $data["consol"][$i];
				 $object[$i][1]="<input type=checkbox  name=checkcmp".$i."     id=checkcmp".$i."     value=1                   size=10  style=text-align:left    class=sin-borde >"; 
		  	     $object[$i][2]="<input type=text      name=txtnumsol".$i."    id=txtnumsol".$i."    value='".$ls_numsol."'    size=15  style=text-align:center  class=sin-borde  readonly>"; 
			     $object[$i][3]="<input type=text      name=txtconcepto".$i."  id=txtconcepto".$i."  value='".$ls_concepto."'  size=130 style=text-align:left    class=sin-borde  readonly   title='".$ls_concepto."' bgColor=#FF5500>";
                 $object[$i][4]="<input type=text      name=txtprocede".$i."   id=txtprocede".$i."   value='CXPRCD'            size=5   style=text-align:center  class=sin-borde  readonly>";
			   }
			$io_grid->makegrid($li_total,$title,$object,650,'Comprobantes de Retenci&oacute;n',$grid);
		    ?>
            <script language="javascript">
		    document.form1.totnum.value="<?php print $li_total ?>";
		    </script>
            <?php
	    }
     else
       { ?>
</p>
           </form>
           <p>&nbsp;           </p>
           <p>
             <script language="javascript">
             alert("No se han creado Comprobantes de Retención para éste criterio de búsqueda !!!");
 	         </script>
           </p>
      <?php
	  }
}
	  ?>
</div>
</body>
<script language="JavaScript">
function ue_showouput()
{
	f               = document.form1;
    ls_comprobantes = "";
	ls_procedencias = "";
	li_total        = f.totnum.value;

	     for (i=1;i<=li_total;i++)
	         {
			   if (eval("f.checkcmp"+i+".checked==true"))
				  {
				    ls_documento = eval("f.txtnumsol"+i+".value");
				    ls_procede   = eval("f.txtprocede"+i+".value");
					if (ls_comprobantes.length>0)
					   {
					     ls_comprobantes = ls_comprobantes+"-"+ls_documento;
					   }
					else
					   {
						 ls_comprobantes = ls_documento;
				 	   }
                    if (ls_procedencias.length>0)
					   {
					     ls_procedencias = ls_procedencias+"-"+ls_procede;
					   }
					else
					   {
						 ls_procedencias = ls_procede;
				 	   }
				  }
	         }
		 if (ls_comprobantes!="")
		    {
			  pagina="reportes/sigesp_cxp_rpp_retunoxmil.php?hidcomprobantes="+ls_comprobantes+"&hidprocedencias="+ls_procedencias;
			  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=0,top=0");
		    }
		 else
		    {
			  alert("Debe seleccionar un Número de Documento Previamente !!!");	   
		    }
  
}

function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }

function uf_catalogoprov()
{
    f=document.form1;
    if (f.radiocategoria[0].checked==true)
	   {
	     pagina="sigesp_cxp_cat_proveedores.php";
         window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   }
    if (f.radiocategoria[1].checked==true)
	   {
	     pagina="sigesp_cxp_cat_beneficiarios.php";
         window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   }   
}

function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
    
	if (document.form1.radiocategoria[0].checked==true)
	   {
	     total=longitud-lencad;
	     if (cadena!="")
	        {
		      for (i=1;i<=total;i++)
			      {
			        cadena_ceros=cadena_ceros+"0";
			      }
		      cadena=cadena_ceros+cadena;
		      if (objeto=="txtcodigo1")
		         {
			       document.form1.txtcodigo1.value=cadena;
		         }
	 	      else
		         {
		           document.form1.txtcodigo2.value=cadena;
		         }
            }
	   }
}

function uf_cambio()
{
   f=document.form1;
   if (f.radiocategoria[2].checked==true)
      {
        f.txtcodigo1.value="";
        f.txtcodigo2.value="";
        f.txtcodigo1.readOnly=true;
        f.txtcodigo2.readOnly=true;
	    eval("document.images['buscar1'].style.visibility='hidden'");
	    eval("document.images['buscar2'].style.visibility='hidden'");
      }
    
   if ((f.radiocategoria[1].checked==true)||(f.radiocategoria[0].checked==true))
      {
        f.txtcodigo1.value="";
        f.txtcodigo2.value="";
	    f.txtcodigo1.readOnly=false;
        f.txtcodigo2.readOnly=false;
	    eval("document.images['buscar1'].style.visibility='visible'");
	    eval("document.images['buscar2'].style.visibility='visible'");
      }
}

function uf_select_all()
{
	f=document.form1;
	total  =f.totnum.value;
	if (document.form1.chktodos.checked==true)
	   {
         sel_all='T';	
	   }
	else
	   {
	     sel_all='F';
	   }
	if (sel_all=='T')
	   {
	     for (i=1;i<=total;i++)	
		     {
			   eval("f.checkcmp"+i+".checked=true");
		     }
	   }
     else
	   {
         for (i=1;i<=total;i++)	
		     {
			   eval("f.checkcmp"+i+".checked=false");
		     }
  	   } 
}


function ue_buscar()
{
	  f= document.form1;	
	  f.action="sigesp_cxp_r_retunoxmil.php";
	  document.form1.operacion.value="BUSCAR";
	  f.submit();
}

function uf_catalogo_deducciones()
{
	pagina="sigesp_cxp_cat_deducciones.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
}

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 

	f=document.form1;
   	ld_desde="f.txtdesde";
   	ld_hasta="f.txthasta";
	var valido = false; 
    var diad = f.txtfechadesde.value.substr(0, 2); 
    var mesd = f.txtfechadesde.value.substr(3, 2); 
    var anod = f.txtfechadesde.value.substr(6, 4); 
    var diah = f.txtfechahasta.value.substr(0, 2); 
    var mesh = f.txtfechahasta.value.substr(3, 2); 
    var anoh = f.txtfechahasta.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido !!!");
	} 
	return valido;
   } 
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>