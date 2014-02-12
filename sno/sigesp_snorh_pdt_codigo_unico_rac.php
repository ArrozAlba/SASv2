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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_asignacioncargo.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codunirac,$ls_codasicar,$ls_estcodunirac,$ls_codnom,$ls_desasicar,$ls_operacion,$li_totrows,$ls_titletable,$li_widthtable,$io_fun_nomina;
		global $ls_nametable,$lo_title;
		$ls_codnom="";
		$ls_codunirac="";
		$ls_codasicar="";
		$ls_estcodunirac="";
		$ls_desasicar="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Definición Códigos Únicos RAC";
		$li_widthtable=400;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Estado";
		$lo_title[3]=" ";
		$lo_title[4]=" ";
		
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
		$aa_object[$ai_totrows][1]="<input name=txtcodunirac".$ai_totrows." type=text id=txtcodunirac".$ai_totrows." class=sin-borde size=10 maxlength=10 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][2]="<input name=txtestcod".$ai_totrows." type=text id=txtestcod".$ai_totrows." class=sin-borde size=10 maxlength=20 readonly>";
		$aa_object[$ai_totrows][3]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
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
	require_once("sigesp_snorh_c_asignacioncargo.php");
	$io_asignacioncargo=new sigesp_snorh_c_asignacioncargo();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_codasicar=$_GET["codasicar"];
			$ls_codnom=$_GET["codnom"];
			$ls_desasicar=$_GET["desasicar"];
			$io_asignacioncargo->uf_load_codigo_unico_rac($ls_codasicar,$ls_codnom,$li_totrows,$lo_object);
			break;
	
		case "AGREGARDETALLE":
			$ls_codasicar=$_POST["txtcodasicar"];
			$ls_codnom=$_POST["txtcodnom"];
			$ls_desasicar=$_POST["txtdesasicar"];
			$li_rowguardar=$_POST["filaguardar"];
			$li_totrows=$li_totrows+1;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowguardar)
				{
					$ls_codunirac=$_POST["txtcodunirac".$li_i];
					$ls_estcodunirac=$_POST["txtestcod".$li_i];
					$lo_object[$li_i][1]="<input name=txtcodunirac".$li_i." type=text id=txtcodunirac".$li_i." class=sin-borde size=10 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codunirac."' readOnly>";
					$lo_object[$li_i][2]="<input name=txtestcod".$li_i." type=text id=txtestcod".$li_i." class=sin-borde size=10 maxlength=10 value='".$ls_estcodunirac."' readonly>";
					$lo_object[$li_i][3]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_i][4]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";
				}
				else
				{
					$ls_codunirac=$_POST["txtcodunirac".$li_i];
					if($io_asignacioncargo->uf_select_codigo_unico_rac($ls_asicar,$ls_codunirac)===false)
					{
						$lb_valido=$io_asignacioncargo->uf_insert_codigo_unico_rac($ls_codnom,$ls_codasicar,$ls_codunirac,
																					$la_seguridad);
						if($lb_valido)
						{
							$io_asignacioncargo->io_sql->commit();
							$io_asignacioncargo->io_mensajes->message("El Código Único fue Registrado.");
								
						}
						else
						{
							$io_asignacioncargo->io_sql->rollback();
							$io_asignacioncargo->io_mensajes->message("Ocurrio un error al guardar el Código Único.");
						
						}
					}
					else
					{
						$io_asignacioncargo->io_mensajes->message("El Código Único ".$ls_codunirac." ya se encuentra registrado en la Asignación de Cargo ".$ls_asicar);
						$lb_valido=false;
					}				
					
					$io_asignacioncargo->uf_load_codigo_unico_rac($ls_codasicar,$ls_codnom,$li_totrows,$lo_object);
				}		
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;

		case "ELIMINARDETALLE":
			$ls_codasicar=$_POST["txtcodasicar"];
			$ls_codnom=$_POST["txtcodnom"];
			$ls_desasicar=$_POST["txtdesasicar"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codunirac=$_POST["txtcodunirac".$li_i];
					$ls_estcodunirac=$_POST["txtestcod".$li_i];
					$lo_object[$li_temp][1]="<input name=txtcodunirac".$li_temp." type=text id=txtcodunirac".$li_temp." class=sin-borde size=10 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codunirac."' readOnly>";
					$lo_object[$li_temp][2]="<input name=txtestcod".$li_temp." type=text id=txtestcod".$li_temp." class=sin-borde size=10 maxlength=20 value='".$ls_estcodunirac."' readonly>";
					$lo_object[$li_temp][3]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][4]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";			
				}
				else
				{
					$ls_codunirac=$_POST["txtcodunirac".$li_i];
					$lb_valido=$io_asignacioncargo->uf_delete_codigo_unico_rac($ls_codnom,$ls_codasicar,$ls_codunirac,$la_seguridad);
					$li_rowdelete= 0;
					if(!$lb_valido)
					{
						$li_totrows=$li_totrows+1;
						$li_temp=$li_temp+1;			
						$ls_codunirac=$_POST["txtcodunirac".$li_i];
					    $ls_estcodunirac=$_POST["txtestcod".$li_i];
						$lo_object[$li_temp][1]="<input name=txtcodunirac".$li_temp." type=text id=txtcodunirac".$li_temp." class=sin-borde size=10 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' value='".$ls_codunirac."' readOnly>";
					$lo_object[$li_temp][2]="<input name=txtestcod".$li_temp." type=text id=txtestcod".$li_temp." class=sin-borde size=10 maxlength=20 value='".$ls_estcodunirac."' readonly>";
					$lo_object[$li_temp][3]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][4]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Deshacer width=15 height=15 border=0></a>";		
					}
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
	}
	$io_asignacioncargo->uf_destructor();
	unset($io_asignacioncargo);
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="498" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="555" height="136">
      <p>&nbsp;</p>
      <table width="401" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
           <td width="397" height="20" colspan="6">
            <div align="center">
              <input name="txtdesasicar" type="text" class="sin-borde3" id="txtdesasicar"   value="<?php print $ls_desasicar;?>" size="60" maxlength="100" style="text-align:center" readonly #invalid_attr_id="none">
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
              <input name="txtcodnom" type="hidden" id="txtcodnom" value="<?php print $ls_codnom;?>">
              <input name="txtcodasicar" type="hidden" id="txtcodasicar" value="<?php print $ls_codasicar;?>">
              <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
              <input name="filadelete" type="hidden" id="filadelete">
			   <input name="filaguardar" type="hidden" id="filaguardar">
            
		    <a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Imprimir" width="20" height="20" border="0">Cancelar</a></p></td>
        </tr>
        <tr>
          <td colspan="4"><p>
            <input name="operacion" type="hidden" id="operacion">
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
function ue_cerrar()
{
	close();
}

function uf_agregar_dt(li_row)
{
	f=document.form1;		
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		ls_coduniracnew=ue_validarvacio(eval("f.txtcodunirac"+li_row+".value"));
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codunirac=ue_validarvacio(eval("f.txtcodunirac"+li_i+".value"));
			if((ls_codunirac==ls_coduniracnew)&&(li_i!=li_row))
			{
				alert("El Código Unico de RAC ya existe.");
				lb_valido=true;
			}
		}
		ls_codnom=ue_validarvacio(f.txtcodnom.value);
		ls_codasicar=ue_validarvacio(f.txtcodasicar.value);
		ls_codunirac=ue_validarvacio(eval("f.txtcodunirac"+li_row+".value"));
		if((ls_codnom=="")||(ls_codasicar=="")||(ls_codunirac==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			li_vacantes=opener.document.form1.txtnumvacasicar.value;
			if (li_row>li_vacantes)
			{
				alert ('La cantidad de códigos únicos es mayor a la cantidad de vacantes de la asignación de cargos');
			}
			
			f.filaguardar.value=li_row;
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_snorh_pdt_codigo_unico_rac.php";
			f.submit();
		}
	}

	
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		ls_codunirac=ue_validarvacio(eval("f.txtcodunirac"+li_row+".value"));
		if(ls_codunirac=="")
		{
			alert("la fila a eliminar no debe tener el Código vacio.");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_snorh_pdt_codigo_unico_rac.php";
				f.submit();
			}
		}
	}
	
}
</script> 
</html>