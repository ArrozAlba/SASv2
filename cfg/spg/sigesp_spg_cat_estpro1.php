<?php
session_start();
$arr              = $_SESSION["la_empresa"];
$li_estmodest     = $arr["estmodest"];
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];

$li_longcodestpro1 = (25-$li_loncodestpro1)+1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 1 <?php print $arr["nomestpro1"] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cfg.js"></script>
</head>
<body>
<form name="formulario" method="post" action="" id="formulario">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $arr["nomestpro1"] ?>
        <input name="campoorden" type="hidden" id="campoorden" value="codestpro1">
        <input name="orden" type="hidden" id="orden" value="ASC"></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="85" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="413" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="<?php print $li_loncodestpro1+2 ?>" maxlength="<?php print $li_loncodestpro1 ?>" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="65" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
      <tr>
        <td height="13" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
    </table>
	 <div align="center">
	   <p><br>
	     <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../cfg/class_folder/class_funciones_configuracion.php");
$io_fun_cfg=new class_funciones_configuracion();
$in     	= new sigesp_include();
$con    	= $in->uf_conectar();
$io_msg 	= new class_mensajes();
$ds     	= new class_datastore();
$io_sql 	= new class_sql($con);
$io_funcion = new class_funciones();

$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["codigo"];
	 $ls_denestpro1 = $_POST["denominacion"];
   }
else
   {
	 $ls_codestpro1 = "";
	 $ls_denestpro1 = "";
     $ls_operacion  = "BUSCAR";
   }
   
$ls_destino=$io_fun_cfg->uf_obtenervalor_get("destino",""); 
  
if (array_key_exists("opener",$_GET))
   {
     $ls_opener = $_GET["opener"];
     if ($ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
	    {
		  $ls_operacion = "CODESTPRO1";
		}
   }
elseif ($ls_operacion=="BUSCAR")
   {
	 echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 echo "<tr class=titulo-celda>";
	 echo "<td width=100>Código</td>";
	 echo "<td width=600>Denominación</td>";
	 if ($li_estmodest=='1')
	    { 
		  echo "<td width=200>Tipo</td>";  
	    }
	 echo "</tr>";

	 $ls_sql  = "SELECT denestpro1,estint,sc_cuenta, substr(codestpro1,".$li_longcodestpro1.",25) as codestpro1,estcla,
	                    (SELECT denominacion 
						   FROM scg_cuentas 
						  WHERE scg_cuentas.codemp=spg_ep1.codemp 
						    AND scg_cuentas.sc_cuenta=spg_ep1.sc_cuenta) as denominacion 
						   FROM spg_ep1
	              WHERE codemp='".$ls_codemp."' 
				    AND codestpro1 like '%".$ls_codestpro1."%'
					AND denestpro1 like '%".$ls_denestpro1."%'
					AND codestpro1 <> '-------------------------'  ".
				   "AND codestpro1||estcla IN (SELECT SUBSTR(codintper,1,25)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' 
				  UNION  SELECT SUBSTR(codintper,1,25)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
			   ORDER BY estcla,codestpro1";
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	   {
	     $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	   }
 	else
	   {
	     $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while ($row=$io_sql->fetch_row($rs_data))
			        {
					  print "<tr class=celdas-blancas>";
					  $ls_estcla     = $row["estcla"];
					  $ls_estint     = $row["estint"];
					  $ls_scgcta     = trim($row["sc_cuenta"]);
					  $ls_denscgcta  = $row["denominacion"];
					  $ls_codestpro1 = substr($row["codestpro1"],-$li_loncodestpro1);
			          $ls_denestpro1 = $row["denestpro1"];
					  if ($ls_estcla=='P')
						 {
						   $ls_denestcla = 'Proyecto';
						 }
					  else
					     {
						   $ls_denestcla = 'Acción';
						 }
					  switch ($ls_destino)
				             {  
				               case "":
							     print "<td  style=text-align:center width=150><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$li_estmodest','$ls_estcla','$ls_scgcta');\">".$ls_codestpro1."</a></td>";
							   break;
				               case "destino":
						         print "<td  style=text-align:center width=150><a href=\"javascript: aceptar2('$ls_codestpro1','$ls_denestpro1','$li_estmodest','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							   break;
							   case "inter":
								 print "<td  style=text-align:center width=150><a href=\"javascript: aceptar3('$ls_codestpro1','$ls_denestpro1','$li_estmodest','$ls_estint','$ls_scgcta','$ls_denscgcta','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							   break;
					         }		
					  print "<td  style=text-align:left   width=550>".$ls_denestpro1."</td>";
					  if ($li_estmodest=='1')
					     { 
						   print "<td  style=text-align:center   width=350>".$ls_denestcla."</td>";
						 }
					  print "</tr>";
					}
			}	 
  	     else
		    {
			  $io_msg->message("No se han definido cuentas en ".$arr["nomestpro1"]." para este criterio !!!");			
			}
	   } 
     print "</table>";
   }
?>
</p>
	   <div id="detcodestpro1"></div>
	   <p>&nbsp;       </p>
  </div>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f 	= document.formulario;
fop = opener.document.formulario;

  function aceptar(ls_codestpro1,ls_denestpro1,li_estmodest,ls_estcla,ls_scgcta)
  {
	fop.txtcodestpro1.value    = ls_codestpro1;
    fop.txtdenestpro1.value    = ls_denestpro1;
	fop.operacion.value        = "BUSCAR";
	fop.txtcodestpro1.readOnly = true;
	ls_maestro                 = fop.hidmaestro.value;           
	if (ls_maestro=='Y')
       {
	     if (li_estmodest=='1')
	        {
		 	  if (ls_estcla=='P')
			     {
			       fop.rbclasificacion[0].checked=true;
			     }
		      else
			     {
			       fop.rbclasificacion[1].checked=true;
			     }
		    }
	     fop.status.value='C';	   
       }
    else if(ls_maestro=='P')
    {
    	fop.txtestcla.value 	= ls_estcla;
		fop.hidscgctaint.value  = ls_scgcta;
		fop.txtcodestpro2.value = "";
    	fop.txtdenestpro2.value = "";
    	fop.txtcodestpro3.value = "";
    	fop.txtdenestpro3.value = "";
    	if (li_estmodest=="2")
		   {
			 fop.txtcodestpro4.value = "";
			 fop.txtdenestpro4.value = "";
			 fop.txtcodestpro5.value = "";
			 fop.txtdenestpro5.value = "";		   
		   }
    	for (i=1;i<=50;i++)
    	    {
    		  eval("fop.txtcuentaspg"+i+".value=''");
			  eval("fop.txtdencuenta"+i+".value=''");
			  eval("fop.txtcuentascg"+i+".value=''");	
    	    }    	
    }
    else
    {
   		fop.txtestcla.value = ls_estcla;
    }    
	close();
  }
  
  function aceptar2(as_codestpro1,as_denestpro1,li_estmodest,ls_estcla,estatus)
  {
	fop.txtcodestpro1.value    = as_codestpro1;
    fop.txtdenestpro1.value    = as_denestpro1;
	fop.operacion.value        = "CASTEST";
	fop.txtcodestpro1.readOnly = true;
	fop.txtestcla.value        = ls_estcla;
	close();
  }
  
function aceptar3(codigo,deno,li_estmodest,ls_estint,ls_sccuenta,ls_denoctacont,ls_estcla)
  {
	fop.txtcodestpro1.value    = codigo;
    fop.txtdenestpro1.value    = deno;
	fop.operacion.value        = "BUSCAR";
	fop.txtcodestpro1.readOnly = true; 
	if (ls_estint=="1")
	   {
	     fop.chkintercom.checked=true;
		 fop.txtcuenta.value=ls_sccuenta;  
	     fop.txtdencuenta.value=ls_denoctacont;
	   }
	 if (li_estmodest=='1')
	{
	  if (ls_estcla=='P')
		 {
		   fop.rbclasificacion[0].checked=true;
		 }
	  else
		 {
		   fop.rbclasificacion[1].checked=true;
		 }
	}
    close();
  }

function ue_search()
{
  ls_opener = fop.id;
  if (ls_opener!='sigesp_spg_d_codestpro_codfuefin.php' && ls_opener!='sigesp_cfg_d_consolidacion.php')
	 {
	   f.operacion.value = "BUSCAR";
	   f.action          = "sigesp_spg_cat_estpro1.php?destino=<?php print $ls_destino;?>";
	   f.submit();
	 }
  else
	 {
	   uf_print_codestpro1();
	 }
}

function uf_print_codestpro1()
{
  ls_codestpro1 = f.codigo.value;
  ls_denestpro1 = f.denominacion.value;
  orden      = f.orden.value;
  campoorden = f.campoorden.value;
  divgrid    = document.getElementById("detcodestpro1");
  ajax       = objetoAjax();
  ajax.open("POST","../class_folder/sigesp_cfg_c_catalogo_ajax.php",true);
  ajax.onreadystatechange=function() {
  if (ajax.readyState==1)
	 {
	   divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
	 }
  else if (ajax.readyState==4) {
	   divgrid.innerHTML = ajax.responseText
	 }
  }
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  ajax.send("catalogo=CODESTPRO1&campoorden="+campoorden+"&orden="+orden+"&codestpro1="+ls_codestpro1+"&denestpro1="+ls_denestpro1);
}

function uf_aceptar_codestpro1(as_codestpro1,as_denestpro1,as_estcla)
{
  fop.txtcodestpro1.value = as_codestpro1;
  fop.txtdenestpro1.value = as_denestpro1;
  fop.hidestcla.value     = as_estcla;  
  fop.txtcodestpro2.value = "";
  fop.txtcodestpro3.value = "";
  fop.txtdenestpro2.value = "";
  fop.txtdenestpro3.value = "";
  li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  if (li_estmodest==2)
     {
	   fop.txtcodestpro4.value = "";
	   fop.txtcodestpro5.value = "";
	   fop.txtdenestpro4.value = "";
	   fop.txtdenestpro5.value = "";	 
	 }
  close();
}
</script>
<?php
if ($ls_operacion=="CODESTPRO1")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_print_codestpro1();";
	 echo "</script>";
   }
?>
</html>