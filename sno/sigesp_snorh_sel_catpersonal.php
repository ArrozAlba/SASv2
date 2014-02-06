<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codper, $as_cedper, $as_nomper, $as_apeper, $as_codnom, &$totrow)
   	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // Código de Personal
		//				   as_cedper  // Cédula de Pesonal
		//				   as_nomper  // Nombre de Personal
		//				   as_apeper // Apellido de Personal
		//				   as_codnom // código de nómina a la que pertenece
		//				   as_tipo  // Tipo de Llamada del catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
   		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();				
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once("../shared/class_folder/grid_param.php");
		$grid = new grid_param();		
		$title[1]="Todos <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px onClick=javascript:uf_select_all(); >";	
		$title[2]="Código";   
		$title[3]="Cedula";
		$title[4]="Nombre y Apellido";
		$title[5]="Estatus"; 
        $grid1="grid";	
		$ls_sql=" SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper,    ".
				"	     sno_personal.apeper, sno_personal.estper                          ".
				"   FROM sno_personal                                                      ".
				" WHERE sno_personal.codemp='".$ls_codemp."'                               ". 
				"   AND sno_personal.codper like '".$as_codper."'                          ".
				"   AND sno_personal.cedper like '".$as_cedper."'                          ".
				"   AND sno_personal.nomper like '".$as_nomper."'                          ".
				"   AND sno_personal.apeper like '".$as_apeper."'                          ".
				"   AND sno_personal.codper IN (SELECT sno_personal.codper                 ". 
				"								  FROM sss_permisos_internos,sno_personal  ".
				"							     WHERE sss_permisos_internos.codsis='SNO'  ".
				"								   AND sss_permisos_internos.codusu='".$_SESSION["la_logusr"]."'   ". 
				"								   AND sno_personal.codtippersss=sss_permisos_internos.codintper ) ".
				"   AND sno_personal.codper IN (SELECT codper FROM sno_personalnomina                            ".
				"							     WHERE sno_personalnomina.codemp='".$ls_codemp."'                ".
				"							       AND sno_personalnomina.codnom='".$as_codnom."')               ".
				"				ORDER BY sno_personal.codper ";		
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$totrow=$io_sql->num_rows($rs_data);
			if ($totrow>0)
			{
				while($row=$io_sql->fetch_row($rs_data))
				{
					$ls_codper=$row["codper"];
					$ls_cedper=$row["cedper"];
					$ls_nomper=$row["nomper"];
					$ls_apeper=$row["apeper"];
					$ls_nomper=$ls_apeper.", ".$ls_nomper;						
					$ls_estper=$row["estper"];					
					switch ($ls_estper)
					{
						case "0":
							$ls_estper="Pre-Ingreso";
							$ls_estatus=0;
							break;
						
						case "1":
							$ls_estper="Activo";
							$ls_estatus=1;
							break;
						
						case "2":
							$ls_estper="N/A";
							$ls_estatus=2;
							break;
						
						case "3":
							$ls_estper="Egresado";
							$ls_estatus=3;
							break;
					}			
					$z++;
					$object[$z][1]="<input name=chkper".$z." type=checkbox id=chkper".$z." value=1 class=sin-borde onClick=javascript:uf_selected('".$z."');>";
					$object[$z][2]="<input type=text name=txtcodigo".$z." value='".$ls_codper."' id=txtcodigo".$z." class=sin-borde readonly style=text-align:center size=18 maxlength=18 >";		
					$object[$z][3]="<input type=text name=txtcedper".$z." value='".$ls_cedper."' id=txtcedper".$z." class=sin-borde readonly style=text-align:left size=12 maxlength=12>";	
					$object[$z][4]="<input type=text name=txtnomper".$z." value='".$ls_nomper."' id=txtnomper".$z." class=sin-borde readonly style=text-align:left size=100 maxlength=100>";	
					$object[$z][5]="<input type=text name=txtestatus".$z." value='".$ls_estper."' id=txtestatus".$z." class=sin-borde readonly style=text-align:left size=10 maxlength=10>";		
				}// fin del while
			}
			else
			{
				$object[1][1]="<input name=chkcta1 type=checkbox id=chkcta1 value=1 onClick=javascript:uf_selected('".$z."');>";
				$object[1][2]="<input type=text name=txtcuenta1 value='' id=txtcuenta1 class=sin-borde readonly style=text-align:center size=20 maxlength=20>";		
				$object[1][3]="<input type=text name=txtdencuenta1 value='' id=txtdencuenta1 class=sin-borde readonly style=text-align:center size=50 maxlength=254>";
				$totrow=1;
			
			}
			$grid->makegrid($totrow,$title,$object,600,'Catalogo del Personal',$grid1);			
			//$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($io_sno);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Personal</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Personal </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431" colspan="2"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">C&eacute;dula</div></td>
        <td colspan="2"><div align="left">
          <input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td colspan="2"><div align="left">
          <input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td colspan="2"><div align="left">
          <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div>
	     <a href="javascript: ue_aceptar();"></a></td>
        <td><a href="javascript: ue_search();"> </a><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" width="20" height="20" border="0">Aceptar</a></td>
      </tr>	  
  </table> 
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion(); 	
	$ls_codnom=$io_fun_nomina->uf_obtenervalor_get("codnom","");
	if(array_key_exists("selected",$_POST))
	{
		$li_selected= $_POST["selected"];
	}
	else
	{
		$li_selected= 0;
	}
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";
		$totrow=0;
		uf_print($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper, $ls_codnom, $totrow);		
	}
	unset($io_fun_nomina);	
?>
</div>
 <input name="total" type="hidden" id="total" value="<?php print $totrow;?>"> 
 <input name="selected" type="hidden" id="selected" value="<?php print $li_selected;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_aceptar()
  {
	  f=document.form1;
	  fop=opener.document.form1;
	  li_total=f.total.value;	  
	  li_selected=f.selected.value;	 
	  parametro="";
	  li_sel=0;	   
	  for(i=1;(i<=parseInt(li_total,10));i++)	
	  {
   		if(li_sel<parseInt(li_selected,10))
		{
			if(eval("f.chkper"+i+".checked==true"))
			{
				li_sel=li_sel+1;				
				ls_codper=eval("f.txtcodigo"+i+".value");
				parametro=parametro+"-"+ls_codper;				
			}			
		}
		else
		{
			break;
			close();			
		}	
	  }
	  fop.txtparametros.value=parametro;
	  close(); 
	  
}


function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_sel_catpersonal.php?codnom=<?php print $ls_codnom;?>";
  	f.submit();
}


function uf_select_all()
{
	  f=document.form1;
	  fop=opener.document.form1;
	  total=f.total.value; 
	  sel_all=f.chkall.value;	  	  
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total;i++)	
		  {
			eval("f.chkper"+i+".checked=true");			
		  }		 
	  }
}

function uf_selected(li_i)
 {
 	f=document.form1;
	li_total=f.total.value;
	li_selected=f.selected.value; 
	if(eval("f.chkper"+li_i+".checked==true"))
	{
		li_selected=parseInt(li_selected,10)+1;
	}
 	f.selected.value=li_selected;
 }	


</script>
</html>
