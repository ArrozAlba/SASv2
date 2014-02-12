<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_constpersonal.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codigo, $ls_nomcon, $ls_operacion, $li_totrows, $ls_titletable, $li_widthtable;
		global $ls_nametable, $lo_title, $io_fun_nomina, $ls_desnom,$ls_desper, $li_calculada,$ls_esttopmod;
		global $li_registros, $li_pagina, $li_inicio, $li_totpag;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_esttopmod=$_GET["esttopmod"];
	 	$ls_codigo="";
		$ls_nomcon="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Personal";
		$li_widthtable=700;
		$ls_nametable="grid";
		if ($ls_esttopmod==0)
		{
			$lo_title[1]="Codigo";
			$lo_title[2]="Unidad Administrativa";
			$lo_title[3]="Nombre";
			$lo_title[4]="Valor";  
		}
		else
		{
			$lo_title[1]="Codigo";
			$lo_title[2]="Unidad Administrativa";
			$lo_title[3]="Nombre";
			$lo_title[4]="Tope";
			$lo_title[5]="Valor"; 
			  
		}   
		$li_registros = 100;
		$li_pagina=$io_fun_nomina->uf_obtenervalor_get("pagina",0);
		if (!$li_pagina) { 
			$li_inicio = 0; 
			$li_pagina = 1; 
		} 
		else { 
			$li_inicio = ($li_pagina - 1) * $li_registros; 
		} 
		$li_totpag=0;
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Constante Por Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
</head>
<body>
<?php 
	require_once("sigesp_sno_c_constantes.php");
	$io_constante=new sigesp_sno_c_constantes();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_codigo=$_GET["txtcodcons"];
			$ls_tope=$_GET["txttopcon"];
			$ls_nomcon=$_GET["txtnomcon"];
			$ls_esttopmod=$_GET["esttopmod"];
			$lb_valido=$io_constante->uf_load_constantepersonal($ls_codigo,$li_inicio,$li_registros,$li_totrows,$lo_object,$li_totpag,$ls_esttopmod);
			break;
			
		case "GUARDAR":
			$ls_tope=$_POST["tope"];
			$ls_codigo=$_POST["txtcodcons"];
			$ls_nomcon=$_POST["txtnomcon"];
			$ls_esttopmod=$_POST["txtesttopmod"];
			$ls_tope=$_POST["tope"];
			$lb_valido=true;
			$ls_descripcionpersonal="";
			$io_constante->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_totrows&&$lb_valido;$li_i++)
			{
				 $ls_codper=$_POST["txtcodper".$li_i]; 
				 $ls_moncon=$_POST["txtmoncon".$li_i]; 
				 $ls_moncon=str_replace('.','',$ls_moncon);
				 $ls_moncon=str_replace(',','.',$ls_moncon);
				 $ld_moncon=number_format($ls_moncon,2,",",".");
				 
				 if(trim($ls_esttopmod)==1)
				 {
				 	 $ls_topcon=$_POST["txttopcon".$li_i]; 
					 $ls_topcon=str_replace('.','',$ls_topcon);
					 $ls_topcon=str_replace(',','.',$ls_topcon);
					 $ld_topcon=number_format($ls_topcon,2,",",".");
				}
				else
				{
					
					$ls_tope=str_replace('.','',$ls_tope);
					$ls_tope=str_replace(',','.',$ls_tope);
					$ls_topcon=$ls_tope;
				}	
				 
				 $lb_valido=$io_constante->update_const_personal($ls_codigo,$ls_moncon,$ls_topcon,$ls_codper);
				 $ls_descripcionpersonal=$ls_descripcionpersonal." - personal ".$ls_codper;
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la constantepersonal constante ".$ls_codigo." ".$ls_descripcionpersonal.", asociado a la nómina ".$ls_codnom;
				$lb_valido= $io_constante->io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
												$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
												$la_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}		
			if($lb_valido)
			{
				$io_constante->io_sql->commit();
				$io_constante->io_mensajes->message("El Personal fue actualizado.");
			}
			else
			{
				$io_constante->io_sql->rollback();
				$io_constante->io_mensajes->message("Ocurrio un error al actualizar el personal.");
			}
			
			$lb_valido=$io_constante->uf_load_constantepersonal($ls_codigo,$li_inicio,$li_registros,$li_totrows,$lo_object,$li_totpag,$ls_esttopmod);
			break;
	}
	$io_constante->uf_destructor();
	unset($io_constante);	
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  </tr>
    <tr>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: uf_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: uf_volver();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<div align="center">
  <form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_sno_d_constantes.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
     ?>		  
    <p>&nbsp;</p>
<table width="762" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
    <table width="712" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td><div align="center">
          <input name="txtnomcon" type="text" class="sin-borde2" id="txtnomcon" value="<?php print $ls_nomcon ?>" style="text-align:center" size="60">
          <input name="txtcodcons" type="hidden"  id="txtcodcons" value="<?php print $ls_codigo ?>">
		  <input name="txtesttopmod" type="hidden"  id="txtesttopmod" value="<?php print $ls_esttopmod ?>">
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="22">Definici&oacute;n de Constante Personal </td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
      </tr>
        <tr>
		<?php
			print "<center>";
			if(($li_pagina - 1) > 0) 
			{
				print "<a href='sigesp_sno_d_constpersonal.php?txtcodcons=".$ls_codigo."&txttopcon=".$ls_tope."&txtnomcon=".$ls_nomcon."&esttopmod=".$ls_esttopmod."&pagina=".($li_pagina-1)."'>< Anterior</a> ";
			}
			for ($li_i=1; $li_i<=$li_totpag; $li_i++)
			{ 
				if ($li_pagina == $li_i) 
				{
					print "<b>".$li_pagina."</b> "; 
				}
				else
				{
					print "<a href='sigesp_sno_d_constpersonal.php?txtcodcons=".$ls_codigo."&txttopcon=".$ls_tope."&txtnomcon=".$ls_nomcon."&esttopmod=".$ls_esttopmod."&pagina=".($li_i)."'>$li_i</a> "; 
				}
			}
			if(($li_pagina + 1)<=$li_totpag) 
			{
				print " <a href='sigesp_sno_d_constpersonal.php?txtcodcons=".$ls_codigo."&txttopcon=".$ls_tope."&txtnomcon=".$ls_nomcon."&esttopmod=".$ls_esttopmod."&pagina=".($li_pagina+1)."'>Siguiente ></a>";
			}
			
			print "</center>";
		?>
          </tr>
      <tr>
        <td height="22"><div align="center">
 <?php	
		$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
		unset($io_grid);
?>
		</div>		</td>
      </tr>
      <tr>
        <td height="22">
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
          <input name="txtcodigo" type="hidden" id="txtcodigo" value="<?php print $ls_codigo ?>">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows; ?>">
          <input name="tope" type="hidden" id="tope" value="<?php print $ls_tope; ?>">
		  <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>"></td>
      </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
  </form>
</div>
</body>
<script language="javascript">
function uf_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_cambiar=f.cambiar.value;
		if(li_cambiar==1)
		{
			lb_valido=true;
			li_total=f.totalfilas.value
			tope=f.tope.value;
			while(tope.indexOf('.')>0)
			{
				tope=tope.replace(".","");
			}
			tope=tope.replace(",",".");		
			if(tope!=0)
			{
				for(li_i=1;((li_i<li_total)&&(lb_valido));li_i++)
				{
					valor=eval("f.txtmoncon"+li_i+".value");
					while(valor.indexOf('.')>0)
					{
						valor=valor.replace(".","");
					}
					valor=valor.replace(",",".");		
					if(parseFloat(valor)>parseFloat(tope))
					{
						lb_valido=false;
						alert("El valor "+valor+" no es valido, es mayor que el tope.");
					}
				}
			}
			if(lb_valido)
			{
				f=document.form1;
				f.operacion.value ="GUARDAR";
				ls_esttopmod=f.txtesttopmod.value;
				f.action="sigesp_sno_d_constpersonal.php?pagina=<?php print $li_pagina; ?>"+"&esttopmod="+ls_esttopmod;
				f.submit();
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function uf_mayor(obj)
{
	fop=document.form1;
	valor2=fop.tope.value;
	while(valor2.indexOf('.')>0)
	{
		valor2=valor2.replace(".","");
	}
	valor2=valor2.replace(",",".");		
	valor=obj.value
	while(valor.indexOf('.')>0)
	{
		valor=valor.replace(".","");
	}
	valor=valor.replace(",",".");		
	if(valor2!=0)
	{
		if(parseFloat(valor)>parseFloat(valor2))
		{
		   alert("El valor no es valido");
		   obj.value="0"; 
		}
	}
}

function uf_mayor_tope(obj,num)
{
	fop=document.form1;
	valor2=eval('document.form1.txttopcon'+num);
	valor2=valor2.value;
	while(valor2.indexOf('.')>0)
	{
		valor2=valor2.replace(".","");
	}
	valor2=valor2.replace(",",".");		
	valor=obj.value
	while(valor.indexOf('.')>0)
	{
		valor=valor.replace(".","");
	}
	valor=valor.replace(",",".");		
	if(valor2!=0)
	{
		if(parseFloat(valor)>parseFloat(valor2))
		{
		   alert("El valor no es valido");
		   obj.value="0"; 
		}
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank_nomina.php";
	f.submit();
}

function uf_volver()
{
	  f=document.form1;
	  cod=f.txtcodcons.value;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_sno_d_constantes.php?txtcodcons="+cod;
	  f.submit(); 
}
</script>
</html>
