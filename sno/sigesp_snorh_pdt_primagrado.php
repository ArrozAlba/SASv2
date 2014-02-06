<?php
   session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_pdt_primagrado.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codtab,$ls_destab,$ls_codpas,$ls_codgra,$ls_operacion,$li_totrows,$ls_titletable,$li_widthtable,$io_fun_nomina;
		global $ls_nametable,$lo_title,$li_calculada,$ls_codnom,$io_tabulador;
		
		$ls_codtab="";
		$ls_destab="";
		$ls_codpas="";
		$ls_codgra="";
		$ls_codnom="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Definición de Primas";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Descripción";
		$lo_title[3]="Monto";
		$lo_title[4]=" ";
		$lo_title[5]=" ";
		$li_calculada=0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//		   Access: private
		//	    Arguments: aa_object  // arreglo de Objetos
		//			       ai_totrows  // total de Filas
		//	  Description: Función que agrega una linea mas en el grid
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodpri".$ai_totrows." type=text id=txtcodpri".$ai_totrows." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtdespri".$ai_totrows." type=text id=txtdespri".$ai_totrows." class=sin-borde size=50 maxlength=100 >";
		$aa_object[$ai_totrows][3]="<input name=txtmonpri".$ai_totrows." type=text id=txtmonpri".$ai_totrows." class=sin-borde size=15 maxlength=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
   }
   //--------------------------------------------------------------
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
<title >Definici&oacute;n de Primas</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #333333}
-->
</style>
</head>

<body>
<?php 
	require_once("sigesp_snorh_c_tabulador.php");
	$io_tabulador=new sigesp_snorh_c_tabulador();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_codtab=$_GET["codtab"];
			$ls_destab=$_GET["destab"];
			$ls_codpas=$_GET["codpas"];
			$ls_codgra=$_GET["codgra"];
			$ls_codnom=$_GET["codnom"];
			$li_calculada=$_GET["calculada"];
			$io_tabulador->uf_load_primagrado($ls_codtab,$ls_codpas,$ls_codgra,$ls_codnom,$li_totrows,$lo_object);
			break;

		case "GUARDAR":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$ls_codpas=$_POST["txtcodpas"];
			$ls_codgra=$_POST["txtcodgra"];
			$ls_codnom=$_POST["txtcodnom"];
			$li_calculada=$_POST["calculada"];
			$io_tabulador->io_sql->begin_transaction();
			$lb_valido=true;
			for($li_i=1;(($li_i<$li_totrows)&&($lb_valido));$li_i++)
			{
				$ls_codpri=$_POST["txtcodpri".$li_i];
				$ls_despri=$_POST["txtdespri".$li_i];
				$li_monpri=$_POST["txtmonpri".$li_i];
			
				$lb_valido=$io_tabulador->uf_guardar_primagrado($ls_codtab,$ls_codgra,$ls_codpas,$ls_codpri,$ls_despri,$li_monpri,$ls_codnom,$la_seguridad);
			}
			if($lb_valido)
			{
				$io_tabulador->io_sql->commit();
				$io_tabulador->io_mensajes->message("Las Primas fueron Registrada.");
			}
			else
			{
				$io_tabulador->io_sql->rollback();
				$io_tabulador->io_mensajes->message("Ocurrio un error al guardar las Primas.");
			}
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$ls_codpas=$_POST["txtcodpas"];
			$ls_codgra=$_POST["txtcodgra"];			
			$ls_codnom=$_POST["txtcodnom"];
			$io_tabulador->uf_load_primagrado($ls_codtab,$ls_codpas,$ls_codgra,$ls_codnom,$li_totrows,$lo_object);
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "AGREGARDETALLE":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$ls_codpas=$_POST["txtcodpas"];
			$ls_codgra=$_POST["txtcodgra"];
			$ls_codnom=$_POST["txtcodnom"];
			$li_calculada=$_POST["calculada"];
			$li_totrows=$li_totrows+1;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codpri=$_POST["txtcodpri".$li_i];
				$ls_despri=$_POST["txtdespri".$li_i];
				$li_monpri=$_POST["txtmonpri".$li_i];
				
				$lo_object[$li_i][1]="<input name=txtcodpri".$li_i." type=text id=txtcodpri".$li_i." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codpri."' readOnly>";
				$lo_object[$li_i][2]="<input name=txtdespri".$li_i." type=text id=txtdespri".$li_i." class=sin-borde size=50 maxlength=100 value='".$ls_despri."'>";
				$lo_object[$li_i][3]="<input name=txtmonpri".$li_i." type=text id=txtmonpri".$li_i." class=sin-borde size=15 maxlength=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monpri."' style=text-align:right>";
				$lo_object[$li_i][4]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
			$ls_codtab=$_POST["txtcodtab"];
			$ls_destab=$_POST["txtdestab"];
			$ls_codpas=$_POST["txtcodpas"];
			$ls_codgra=$_POST["txtcodgra"];
			$ls_codnom=$_POST["txtcodnom"];
			$li_calculada=$_POST["calculada"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codpri=$_POST["txtcodpri".$li_i];
					$ls_despri=$_POST["txtdespri".$li_i];
					$li_monpri=$_POST["txtmonpri".$li_i];
					
					$lo_object[$li_temp][1]="<input name=txtcodpri".$li_temp." type=text id=txtcodpri".$li_temp." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codpri."' readOnly>";
					$lo_object[$li_temp][2]="<input name=txtdespri".$li_temp." type=text id=txtdespri".$li_temp." class=sin-borde size=50 maxlength=100 value='".$ls_despri."'>";
					$lo_object[$li_temp][3]="<input name=txtmonpri".$li_temp." type=text id=txtmonpri".$li_temp." class=sin-borde size=15 maxlength=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monpri."' style=text-align:right>";
					$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
				}
				else
				{
					$ls_codpri=$_POST["txtcodpri".$li_i];
					$lb_valido=$io_tabulador->uf_delete_primagrado($ls_codtab,$ls_codgra,$ls_codpas,$ls_codpri,$ls_codnom,$la_seguridad);
					$li_rowdelete= 0;
					if(!$lb_valido)
					{
						$li_totrows=$li_totrows+1;
						$li_temp=$li_temp+1;			
						$ls_codpri=$_POST["txtcodpri".$li_i];
						$ls_despri=$_POST["txtdespri".$li_i];
						$li_monpri=$_POST["txtmonpri".$li_i];
						
						$lo_object[$li_temp][1]="<input name=txtcodpri".$li_temp." type=text id=txtcodpri".$li_temp." class=sin-borde size=15 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codpri."' readOnly>";
						$lo_object[$li_temp][2]="<input name=txtdespri".$li_temp." type=text id=txtdespri".$li_temp." class=sin-borde size=50 maxlength=100 value='".$ls_despri."'>";
						$lo_object[$li_temp][3]="<input name=txtmonpri".$li_temp." type=text id=txtmonpri".$li_temp." class=sin-borde size=15 maxlength=20 onKeyPress=return(ue_formatonumero(this,'.',',',event)) value='".$li_monpri."'>";
						$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
						$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
					}
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
	$io_tabulador->uf_destructor();
	unset($io_tabulador);
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="111" height="22"><div align="right">Tabulador</div></td>
          <td height="20" colspan="3">
            <div align="left">
              <input name="txtdestab" type="text" class="sin-borde3" id="txtdestab" style="cursor:text; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-style: italic;" value="<?php print $ls_destab;?>" size="60" maxlength="100" readonly #invalid_attr_id="none">
              <input name="txtcodtab" type="hidden" id="txtcodtab" value="<?php print $ls_codtab;?>">          
              <input name="txtcodnom" type="hidden" id="txtcodnom" value="<?php print $ls_codnom;?>">          
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Paso</div></td>
          <td width="128" height="20">
            <div align="left">
              <input name="txtcodpas" type="text" class="sin-borde3" id="txtcodpas" style="cursor:text; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-style: italic;" value="<?php print $ls_codpas;?>" size="10" maxlength="3" readonly #invalid_attr_id="none">          
            </div></td>
          <td width="36"><div align="right">Grado</div></td>
          <td width="265"><div align="left">
            <input name="txtcodgra" type="text" class="sin-borde3" id="txtcodgra" style="cursor:text; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-style: italic;" value="<?php print $ls_codgra;?>" size="10" maxlength="3" readonly #invalid_attr_id="none">
          </div></td>
        </tr>
        <tr>
          <td colspan="4"><div align="center">
            <?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
          </div>
            <p align="right">
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
            <a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Buscar" width="20" height="20" border="0">Grabar</a>
		    <a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Imprimir" width="20" height="20" border="0">Cancelar</a></p></td>
        </tr>
        <tr>
          <td colspan="4"><p>
            <input name="operacion" type="hidden" id="operacion">
			<input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
          </p></td>
          </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		if((li_cambiar==1)||(li_incluir==1))
		{
			codtab = ue_validarvacio(f.txtcodtab.value);
			destab = ue_validarvacio(f.txtdestab.value);
			codpas = ue_validarvacio(f.txtcodpas.value);
			codgra = ue_validarvacio(f.txtcodgra.value);
			if ((codtab!="")&&(destab!="")&&(codpas!="")&&(codgra!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_pdt_primagrado.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}		
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_cerrar()
{
	close();
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_total=f.totalfilas.value;
		if(li_total==li_row)
		{
			ls_codprinew=ue_validarvacio(eval("f.txtcodpri"+li_row+".value"));
			ls_desprinew=ue_validarvacio(eval("f.txtdespri"+li_row+".value"));
			ls_monprinew=ue_validarvacio(eval("f.txtmonpri"+li_row+".value"));
			lb_valido=false;
			for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
			{
				ls_codpri=ue_validarvacio(eval("f.txtcodpri"+li_i+".value"));
				if((ls_codpri==ls_codprinew)&&(li_i!=li_row))
				{
					alert("El Código de la Prima ya existe.");
					lb_valido=true;
				}
			}
			ls_codtab=ue_validarvacio(f.txtcodtab.value);
			ls_codpas=ue_validarvacio(f.txtcodpas.value);
			ls_codgra=ue_validarvacio(f.txtcodgra.value);
			ls_codpri=ue_validarvacio(eval("f.txtcodpri"+li_row+".value"));
			ls_despri=ue_validarvacio(eval("f.txtdespri"+li_row+".value"));
			ls_monpri=ue_validarvacio(eval("f.txtmonpri"+li_row+".value"));
			if((ls_codtab=="")||(ls_codpas=="")||(ls_codgra=="")||(ls_codpri=="")||(ls_despri=="")||(ls_monpri==""))
			{
				alert("Debe llenar todos los campos");
				lb_valido=true;
			}
			
			if(!lb_valido)
			{
				f.operacion.value="AGREGARDETALLE";
				f.action="sigesp_snorh_pdt_primagrado.php";
				f.submit();
			}
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_total=f.totalfilas.value;
		if(li_total>li_row)
		{
			ls_codpri=ue_validarvacio(eval("f.txtcodpri"+li_row+".value"));
			if(ls_codpri=="")
			{
				alert("la fila a eliminar no debe tener el Código vacio.");
			}
			else
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.filadelete.value=li_row;
					f.operacion.value="ELIMINARDETALLE"
					f.action="sigesp_snorh_pdt_primagrado.php";
					f.submit();
				}
			}
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}
</script> 
</html>