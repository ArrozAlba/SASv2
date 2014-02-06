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
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_partes.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_agregarlineablanca(&$aa_object,$ai_totrows,$ls_seract)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
	//  aa_object // arreglo de titulos 
	//  ai_totrows // ultima fila pintada en el grid
	//  ls_seract // serial del activo
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$aa_object[$ai_totrows][1]="<input name=txtseract".$ai_totrows." type=text id=txtseract".$ai_totrows." class=sin-borde size=17 maxlength=15 value='".$ls_seract."' readonly='true'>";
		$aa_object[$ai_totrows][2]="<input name=txtcodpar".$ai_totrows." type=text id=txtcodpar".$ai_totrows." class=sin-borde size=17 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
		$aa_object[$ai_totrows][3]="<input name=txtdenpar".$ai_totrows." type=text id=txtdenpar".$ai_totrows." class=sin-borde size=40 maxlength=100 onKeyUp='javascript: ue_validarcomillas(this);'>";
		$aa_object[$ai_totrows][4]="<select name=cmbestatus".$ai_totrows."><option value=0>Bueno</option><option value=1>Malo</option></select>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
   }
   	//--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Registro de Partes</title>
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
	$la_codemp=$arre["codemp"];
	$li_totrows= $io_fac->uf_obtenervalor("totalfilas",1);

	$ls_titletable="Listado de Activos";
	$li_widthtable=620;
	$ls_nametable="grid";
	$lo_title[1]="Serial de Activo";
	$lo_title[2]="Codigo";
	$lo_title[3]="Denominación";
	$lo_title[4]="Status";
	$lo_title[5]="";
	$lo_title[6]="";


	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_denact=   $_POST["txtdenact"];
		$ls_codact=   $_POST["txtcodact"];
		$ls_idact=    $_POST["txtidact"];
		
	}
	else
	{
		$ls_codact=$_GET["codact"];
		$ls_seract=$_GET["seract"];
		$ls_idact= $_GET["id"];
		$ls_operacion="";
		uf_agregarlineablanca($lo_object,1,$ls_seract);
		$la_estpar[0]="";
		$la_estpar[1]="";

		$ls_sql= "SELECT * FROM saf_partes". 
				 " WHERE codemp= '".$la_codemp."'".
				 " AND codact= '".$ls_codact."'".
				 " AND ideact= '".$ls_idact."'";
		$result=$io_sql->select($ls_sql);

		$li_j=1;
		while($row=$io_sql->fetch_row($result))
		{
				$la_estpar[0]="";
				$la_estpar[1]="";
				$ls_codpar=$row["codpar"];
				$ls_denpar=$row["denpar"];
				$ls_estpar=$row["estpar"];
				$io_fac->uf_seleccionarcombo("1-0",$ls_estpar,$la_estpar,2);
				
				$lo_object[$li_j][1]="<input name=txtseract".$li_j." type=text id=txtseract".$li_j." class=sin-borde size=17 maxlength=15 value='".$ls_seract."' onKeyUp='javascript: ue_validarnumero(this);' readonly='true'>";
				$lo_object[$li_j][2]="<input name=txtcodpar".$li_j." type=text id=txtcodpar".$li_j." class=sin-borde size=17 maxlength=15 value='".$ls_codpar."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_j][3]="<input name=txtdenpar".$li_j." type=text id=txtdenpar".$li_j." class=sin-borde size=40 maxlength=100 value='".$ls_denpar."'>";
				$lo_object[$li_j][4]="<select name=cmbestatus".$li_j."><option value=1 ".$la_estpar[0].">Bueno</option><option value=0 ".$la_estpar[1].">Malo</option></select>";
				$lo_object[$li_j][5]="<a href=javascript:uf_agregar_dt(".$li_j.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_j][6]="<a href=javascript:uf_delete_dt(".$li_j.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			

			$li_j=$li_j + 1;			
		}

			$li_totrows=$li_j;
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_seract);
	}
	switch ($ls_operacion) 
	{

		case "GUARDAR":
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$la_estpar[0]="";
				$la_estpar[1]="";
				$ls_idact=$_POST["txtidact"];
				$ls_seract=$_POST["txtseract".$li_i];
				$ls_codpar=$_POST["txtcodpar".$li_i];
				$ls_denpar=$_POST["txtdenpar".$li_i];
				$ls_estpar=$_POST["cmbestatus".$li_i];
				$io_fac->uf_seleccionarcombo("1-0",$ls_estpar,$la_estpar,2);
				
				$lo_object[$li_i][1]="<input name=txtseract".$li_i." type=text id=txtseract".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_seract."' onKeyUp='javascript: ue_validarnumero(this);' readonly='true'>";
				$lo_object[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_codpar."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][3]="<input name=txtdenpar".$li_i." type=text id=txtdenpar".$li_i." class=sin-borde size=40 maxlength=100 value='".$ls_denpar."'>";
				$lo_object[$li_i][4]="<select name=cmbestatus".$li_i."><option value=1 ".$la_estpar[0].">Bueno</option><option value=0 ".$la_estpar[1].">Malo</option></select>";
				$lo_object[$li_i][5]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_i][6]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
		
				uf_agregarlineablanca($lo_object,$li_totrows,$ls_seract);
				$lb_encontrado=$io_saf->uf_saf_select_partes($la_codemp,$ls_codact,$ls_idact,$ls_codpar);
				if ($lb_encontrado)
				{
					$lb_valido=$io_saf->uf_saf_update_partes($la_codemp,$ls_codact,$ls_idact,$ls_codpar,$ls_denpar,$ls_estpar,$la_seguridad);
				}
				else
				{
					$lb_valido=$io_saf->uf_saf_insert_partes($la_codemp,$ls_codact,$ls_idact,$ls_codpar,$ls_denpar,$ls_estpar,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_msg->message("La operación se realizó con éxito.");
//					$li_totrows=1;
//					uf_agregarlineablanca($lo_object,$li_totrows);
			
			}
			else
			{
				$io_msg->message("Error al ejecutar la operación.");
			}
			break;

		case "AGREGAROTRODETALLE":
			$li_totrows=$li_totrows+1;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{	
				$la_estpar[0]="";
				$la_estpar[1]="";
				$ls_seract=$_POST["txtseract".$li_i];
				$ls_codpar=$_POST["txtcodpar".$li_i];
				$ls_denpar=$_POST["txtdenpar".$li_i];
				$ls_estpar=$_POST["cmbestatus".$li_i];
				$io_fac->uf_seleccionarcombo("1-0",$ls_estpar,$la_estpar,2);
				
				$lo_object[$li_i][1]="<input name=txtseract".$li_i." type=text id=txtseract".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_seract."' onKeyUp='javascript: ue_validarnumero(this);' readonly='true'>";
				$lo_object[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." class=sin-borde size=17 maxlength=15 value='".$ls_codpar."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$lo_object[$li_i][3]="<input name=txtdenpar".$li_i." type=text id=txtdenpar".$li_i." class=sin-borde size=40 maxlength=100 value='".$ls_denpar."'>";
				$lo_object[$li_i][4]="<select name=cmbestatus".$li_i."><option value=1 ".$la_estpar[0].">Bueno</option><option value=0 ".$la_estpar[1].">Malo</option></select>";
				$lo_object[$li_i][5]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_i][6]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_seract);
			break;

		case "ELIMINARDETALLE":
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_seract=$_POST["txtseract".$li_i];
				if($li_i!=$li_rowdelete)
				{		
					$la_estpar[0]="";
					$la_estpar[1]="";
					$li_temp=$li_temp+1;			
					$ls_seract=$_POST["txtseract".$li_i];
					$ls_codpar=$_POST["txtcodpar".$li_i];
					$ls_denpar=$_POST["txtdenpar".$li_i];
					$ls_estpar=$_POST["cmbestatus".$li_i];
					$io_fac->uf_seleccionarcombo("1-0",$ls_estpar,$la_estpar,2);
	
					$lo_object[$li_i][1]="<input name=txtseract".$li_temp." type=text id=txtseract".$li_temp." class=sin-borde size=17 maxlength=15 value='".$ls_seract."' onKeyUp='javascript: ue_validarnumero(this);' readonly='true'>";
					$lo_object[$li_i][2]="<input name=txtcodpar".$li_temp." type=text id=txtcodpar".$li_temp." class=sin-borde size=17 maxlength=15 value='".$ls_codpar."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$lo_object[$li_i][3]="<input name=txtdenpar".$li_temp." type=text id=txtdenpar".$li_temp." class=sin-borde size=40 maxlength=100 value='".$ls_denpar."'>";
					$lo_object[$li_i][4]="<select name=cmbestatus".$li_temp."><option value=1 ".$la_estpar[0].">Bueno</option><option value=0 ".$la_estpar[1].">Malo</option></select>";
					$lo_object[$li_i][5]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
					$lo_object[$li_i][6]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
				}
				else
				{
					$ls_idact=$_POST["txtidact"];
					$ls_codpar=$_POST["txtcodpar".$li_i];
					$lb_valido=$io_saf->uf_saf_delete_partes($la_codemp,$ls_codact,$ls_idact,$ls_codpar,$la_seguridad);
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_seract);
			break;
			
	}

?>
<div align="center">
  <table width="632" height="143" border="0" class="formato-blanco">
 <form name="form1" method="post" action="">
    <tr>
      <td width="624" height="137"><div align="left">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="624" height="92" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="22" colspan="3" class="titulo-celdanew">Registro de Partes</td>
  </tr>
  <tr class="formato-blanco">
    <td height="22" colspan="3">          <div align="left">
              <input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact?>">
              <input name="txtcodemp" type="hidden" id="txtempresa2" value="<?php print $la_codemp?>">
              <input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact?>">
              <input name="textfield2" type="text" class="sin-borde2" value="Serial:" size="7" readonly="true">
              <input name="txtdenact" type="text" class="sin-borde2" id="txtdenact" value="<?php print $ls_seract?>" readonly="true">
                  <input name="txtidact" type="hidden" id="txtidact" value="<?php print $ls_idact?>">
          </div></td>
    </tr>
  <tr class="formato-blanco">
    <td height="22" colspan="3">
	<?php	
			$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	?>			</td>
    </tr>
  <tr class="formato-blanco">
    <td width="122" height="28"><div align="right">
      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
      <input name="filadelete" type="hidden" id="filadelete">
</div></td>
    <td height="22" colspan="2"><div align="right"><a href="javascript: ue_guardar(<?php echo $li_totrows-1;?>);"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar(<?php echo $li_totrows-1;?>);">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Cancelar" width="20" height="20" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
    </tr>
</table>

<div align="center">
  <input name="operacion" type="hidden" id="operacion">
      </div>
      </div></td>
    </tr>
    </form>
  </table>
</div>
<p align="center">&nbsp; </p>
</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
function ue_guardar(totrow)
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		for (li_row=1; li_row<=totrow ;li_row++)
		{
			ls_codpar=eval("f.txtcodpar"+li_row+".value");
			ls_codpar=ue_validarvacio(ls_codpar);
			ls_denpar=eval("f.txtdenpar"+li_row+".value");
			ls_denpar=ue_validarvacio(ls_denpar);
		
			if((ls_codpar=="")||(ls_denpar==""))
			{
				alert("Debe llenar todos los campos en la linea "+li_row+"");
				lb_valido=true;
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_saf_d_partes.php";
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
	ls_codnew=eval("f.txtcodpar"+li_row+".value");
	li_total=f.totalfilas.value;
	lb_valido=false;
	for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
	{
		ls_codid=eval("f.txtcodpar"+li_i+".value");
		if((ls_codid==ls_codnew)&&(li_i!=li_row))
		{
			alert("La parte ya esta registrado");
			lb_valido=true;
		}
	}
	ls_seract=eval("f.txtseract"+li_row+".value");
	ls_seract=ue_validarvacio(ls_seract);
	ls_codpar=eval("f.txtcodpar"+li_row+".value");
	ls_codpar=ue_validarvacio(ls_codpar);
	ls_denpar=eval("f.txtdenpar"+li_row+".value");
	ls_denpar=ue_validarvacio(ls_denpar);

	if((ls_seract=="")||(ls_codpar=="")||(ls_denpar==""))
	{
		alert("Debe llenar todos los campos");
		lb_valido=true;
	}
	
	if(!lb_valido)
	{
		f.operacion.value="AGREGAROTRODETALLE";
		f.action="sigesp_saf_d_partes.php";
		f.submit();
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	ls_codpar=eval("f.txtcodpar"+li_row+".value");
	ls_codpar=ue_validarvacio(ls_codpar);
	if(ls_codpar=="")
	{
		alert("La fila a eliminar no debe estar vacio el codigo de la parte");
	}
	else
	{
		if(confirm("¿Desea eliminar el Registro "+ls_codpar+"?"))
		{
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_saf_d_partes.php";
			f.submit();
		}
	}
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
</html>