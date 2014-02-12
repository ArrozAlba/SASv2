<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
   function uf_agregarlineablanca(&$aa_object,$ai_totrows,$ls_codart)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_agregarlineablanca
	//	Access:    public
	//	Arguments:
	//  aa_object  // arreglo de titulos 
	//  ai_totrows // ultima fila pintada en el grid
	//  ls_codart  // codigo del articulo
	//	Description:  Funcion que agrega una linea en blanco al final del grid
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows."    type=text id=txtcodart".$ai_totrows."    class=sin-borde size=25 maxlength=20 value='".$ls_codart."' readonly='true'>";
		$aa_object[$ai_totrows][2]="<input name=txtcodcom".$ai_totrows."    type=text id=txtcodcom".$ai_totrows."    class=sin-borde size=20 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'  onBlur='javascript: ue_rellenarcampo(this,12)'>";
		$aa_object[$ai_totrows][3]="<input name=txtdescom".$ai_totrows."    type=text id=txtdescom".$ai_totrows."    class=sin-borde size=38 maxlength=254 onKeyUp='javascript: ue_validarcomillas(this);' onBlur='javascript: ue_validarcomillas(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtcodunimed".$ai_totrows." type=text id=txtcodunimed".$ai_totrows." class=sin-borde size=13 maxlength=5 onKeyUp='javascript: ue_validarcomillas(this);' onBlur='javascript: ue_validarcomillas(this);' readonly><a href='javascript: ue_cataunimed(".$ai_totrows.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Unidad de Medida' width='20' height='20' border='0'></a>";
		$aa_object[$ai_totrows][5]="<input name=txtcancom".$ai_totrows."    type=text id=txtcancon".$ai_totrows."    class=sin-borde size=10 maxlength=10 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
		$aa_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
   }
   	//--------------------------------------------------------------
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // as_valor         //  nombre de la variable que desamos obtener
    // as_valordefecto  //  contenido de la variable
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Registro de Componentes</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
	require_once("sigesp_siv_c_componente.php");
	$io_siv= new sigesp_siv_c_componente();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid=new grid_param();

	$arre=$_SESSION["la_empresa"];
	$la_codemp=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SIV";
	$ls_ventanas="sigesp_siv_d_articulo.php";

	$la_seguridad["empresa"]=$la_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	$li_totrows = uf_obtenervalor("totalfilas",1);

	$ls_titletable="Listado de Componentes";
	$li_widthtable=700;
	$ls_nametable="grid";
	$lo_title[1]="Articulo";
	$lo_title[2]="Codigo";
	$lo_title[3]="Denominación";
	$lo_title[4]="Unidad de Medida";
	$lo_title[5]="Cantidad";
	$lo_title[6]="";
	$lo_title[7]="";


	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
//		$ls_codart=    $_POST["txtcodart"];
		$ls_denart=    $_POST["txtdenart"];
/*		$ls_codcom=    $_POST["txtcodcom"];
		$ls_descom=    $_POST["txtdescom"];
		$ls_codunimed= $_POST["txtcodunimed"];
		$ls_cancom=    $_POST["txtcancom"];*/
		
		
	}
	else
	{
		$ls_codart=$_GET["codart"];
		$ls_denart=$_GET["denart"];

		$ls_operacion="";
		uf_agregarlineablanca($lo_object,1,$ls_codart);

		$ls_sql= "SELECT * FROM siv_componente". 
				 " WHERE codemp= '".$la_codemp."'".
				 " AND codart= '".$ls_codart."'";
		$result=$io_sql->select($ls_sql);

		$li_j=1;
		while($row=$io_sql->fetch_row($result))
		{
				$ls_codart=$row["codart"];
				$ls_codcom=$row["codcom"];
				$ls_descom=$row["descom"];
				$ls_codunimed=$row["codunimed"];
				$li_cancom=$row["cancom"];
				$li_cancom=number_format($li_cancom,2,",",".");

				$lo_object[$li_j][1]="<input name=txtcodart".$li_j."    type=text id=txtcodart".$li_j."    class=sin-borde size=25 maxlength=20  value='".$ls_codart."' readonly>";
				$lo_object[$li_j][2]="<input name=txtcodcom".$li_j."    type=text id=txtcodcom".$li_j."    class=sin-borde size=20 maxlength=12  value='".$ls_codcom."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$lo_object[$li_j][3]="<input name=txtdescom".$li_j."    type=text id=txtdescom".$li_j."    class=sin-borde size=38 maxlength=254 value='".$ls_descom."' onKeyUp='javascript: ue_validarcomillas();'>";
				$lo_object[$li_j][4]="<input name=txtcodunimed".$li_j." type=text id=txtcodunimed".$li_j." class=sin-borde size=13 maxlength=5   value='".$ls_codunimed."'><a href='javascript: ue_cataunimed();'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Unidad de Medida' width='20' height='20' border='0'></a>";
				$lo_object[$li_j][5]="<input name=txtcancom".$li_j."    type=text id=txtcancom".$li_j."    class=sin-borde size=10 maxlength=10  value='".$li_cancom."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";			
				$lo_object[$li_j][6]="<a href=javascript:uf_agregar_dt(".$li_j.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_j][7]="<a href=javascript:uf_delete_dt(".$li_j.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			

			$li_j=$li_j + 1;			
		}

			$li_totrows=$li_j;
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
	}
	switch ($ls_operacion) 
	{

		case "GUARDAR":
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codart=$_POST["txtcodart".$li_i];
				$ls_codcom=$_POST["txtcodcom".$li_i];
				$ls_descom=$_POST["txtdescom".$li_i];
				$ls_codunimed=$_POST["txtcodunimed".$li_i];
				$li_cancom=$_POST["txtcancom".$li_i];

				$lo_object[$li_i][1]="<input name=txtcodart".$li_i."    type=text id=txtcodart".$li_i."    class=sin-borde size=25 maxlength=20  value='".$ls_codart."' readonly>";
				$lo_object[$li_i][2]="<input name=txtcodcom".$li_i."    type=text id=txtcodcom".$li_i."    class=sin-borde size=20 maxlength=12  value='".$ls_codcom."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$lo_object[$li_i][3]="<input name=txtdescom".$li_i."    type=text id=txtdescom".$li_i."    class=sin-borde size=38 maxlength=100 value='".$ls_descom."' onKeyUp='javascript: ue_validarcomillas();' onBlur='javascript: ue_validarcomillas(this);'>";
				$lo_object[$li_i][4]="<input name=txtcodunimed".$li_i." type=text id=txtcodunimed".$li_i." class=sin-borde size=13 maxlength=5   value='".$ls_codunimed."' onKeyUp='javascript: ue_validarcomillas();'><a href='javascript: ue_cataunimed(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Unidad de Medida' width='20' height='20' border='0'></a>";
				$lo_object[$li_i][5]="<input name=txtcancom".$li_i."    type=text id=txtcancon".$li_i."    class=sin-borde size=10 maxlength=10  value='".$li_cancom."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			

				$li_cancom=    str_replace(".","",$li_cancom);
				$li_cancom=    str_replace(",",".",$li_cancom);
		
				uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);

				$lb_encontrado=$io_siv->uf_siv_select_componente($la_codemp,$ls_codart,$ls_codcom);
				if ($lb_encontrado)
				{
					$lb_valido=$io_siv->uf_siv_update_componente($la_codemp, $ls_codart, $ls_codcom, $ls_descom, $ls_codunimed, $li_cancom, $la_seguridad);
				}
				else
				{
					$lb_valido=$io_siv->uf_siv_insert_componente($la_codemp, $ls_codart, $ls_codcom, $ls_descom, $ls_codunimed, $li_cancom, $la_seguridad);
				}
			}
			if($lb_valido)
			{
				$io_msg->message("Los componentes fueron actualizados.");
			
			}
			else
			{
				$io_msg->message("No se actualizaron los componentes.");
			}
			break;

		case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{	
				$ls_codart=    $_POST["txtcodart".$li_i];
				$ls_codcom=    $_POST["txtcodcom".$li_i];
				$ls_descom=    $_POST["txtdescom".$li_i];
				$ls_codunimed= $_POST["txtcodunimed".$li_i];
				$li_cancon=    $_POST["txtcancom".$li_i];

				
				$lo_object[$li_i][1]="<input name=txtcodart".$li_i."    type=text id=txtcodart".$li_i."    class=sin-borde size=25 maxlength=20  value='".$ls_codart."' readonly>";
				$lo_object[$li_i][2]="<input name=txtcodcom".$li_i."    type=text id=txtcodcom".$li_i."    class=sin-borde size=20 maxlength=12  value='".$ls_codcom."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$lo_object[$li_i][3]="<input name=txtdescom".$li_i."    type=text id=txtdescom".$li_i."    class=sin-borde size=38 maxlength=100 value='".$ls_descom."' onKeyUp='javascript: ue_validarcomillas();' onBlur='javascript: ue_validarcomillas(this);'>";
				$lo_object[$li_i][4]="<input name=txtcodunimed".$li_i." type=text id=txtcodunimed".$li_i." class=sin-borde size=13 maxlength=5   value='".$ls_codunimed."' onKeyUp='javascript: ue_validarcomillas();' readonly><a href='javascript: ue_cataunimed(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Unidad de Medida' width='20' height='20' border='0'></a>";
				$lo_object[$li_i][5]="<input name=txtcancom".$li_i."    type=text id=txtcancon".$li_i."    class=sin-borde size=10 maxlength=10  value='".$li_cancon."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$lo_object[$li_i][6]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			

			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
			break;

		case "ELIMINARDETALLE":
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codart=    $_POST["txtcodart".$li_i];
					$ls_codcom=    $_POST["txtcodcom".$li_i];
					$ls_descom=    $_POST["txtdescom".$li_i];
					$ls_codunimed= $_POST["txtcodunimed".$li_i];
					$li_cancon=    $_POST["txtcancom".$li_i];
	
					$lo_object[$li_temp][1]="<input name=txtcodart".$li_temp."    type=text id=txtcodart".$li_temp."    class=sin-borde size=25 maxlength=20  value='".$ls_codart."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtcodcom".$li_temp."    type=text id=txtcodcom".$li_temp."    class=sin-borde size=20 maxlength=12  value='".$ls_codcom."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][3]="<input name=txtdescom".$li_temp."    type=text id=txtdescom".$li_temp."    class=sin-borde size=38 maxlength=100 value='".$ls_descom."' onKeyUp='javascript: ue_validarcomillas();' onBlur='javascript: ue_validarcomillas(this);'>";
					$lo_object[$li_temp][4]="<input name=txtcodunimed".$li_temp." type=text id=txtcodunimed".$li_temp." class=sin-borde size=13 maxlength=5   value='".$ls_codunimed."' onKeyUp='javascript: ue_validarcomillas();' readonly><a href='javascript: ue_cataunimed(".$li_temp.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Unidad de Medida' width='20' height='20' border='0'></a>";
					$lo_object[$li_temp][5]="<input name=txtcancom".$li_temp."    type=text id=txtcancon".$li_temp."    class=sin-borde size=10 maxlength=10  value='".$li_cancon."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
					$lo_object[$li_temp][6]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";			
					$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			

				}
				else
				{
					$ls_codart=$_POST["txtcodart".$li_i];
					$ls_codcom=$_POST["txtcodcom".$li_i];
					$lb_valido=$io_siv->uf_siv_delete_componente($la_codemp,$ls_codart,$ls_codcom, $la_seguridad);
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
			break;
			
	}

?>
<div align="center">
  <table width="632" height="143" border="0" class="formato-blanco">
 <form name="form1" method="post" action="">
    <tr>
      <td width="624" height="137"><div align="left">
<table width="624" height="92" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="13" colspan="3" class="titulo-ventana">Registro de Componentes</td>
  </tr>
  <tr class="formato-blanco">
    <td height="22" colspan="3">          <div align="left">
              <input name="txtdenart" type="text" class="sin-borde2" id="txtdenart" value="<?php print $ls_denart?>" size="70" readonly="true">
                  <input name="txtdenunimed" type="hidden" id="txtdenunimed">
                  <input name="txtunidad" type="hidden" id="txtunidad">
                  <input name="txtobsunimed" type="hidden" id="txtobsunimed">
                  <input name="hidstatus" type="hidden" id="hidstatus">
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
    <td height="22" colspan="2"><div align="right"><a href="javascript: ue_guardar(<?php echo $li_totrows-1;?>);"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar(<?php echo $li_totrows-1;?>);">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
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
function ue_cataunimed(li_linea)
{
	window.open("sigesp_catdinamic_unidadmedida.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_unidad()
{
	window.open("sigesp_catdinamic_unidad.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_rotulacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_siv_d_componentes.php";
	f.submit();
}
function ue_agregar()
{
	f=document.form1;
	f.operacion.value="AGREGARDETALLE";
	f.action="sigesp_siv_d_componentes.php";
	f.submit();
}
function ue_guardar(totrow)
{
	f=document.form1;
	for (li_row=1; li_row<=totrow ;li_row++)
	{
		ls_codart=eval("f.txtcodart"+li_row+".value");
		ls_codart=ue_validarvacio(ls_codart);
		ls_codcom=eval("f.txtcodcom"+li_row+".value");
		ls_codcom=ue_validarvacio(ls_codcom);
		ls_dencom=eval("f.txtdescom"+li_row+".value");
		ls_dencom=ue_validarvacio(ls_dencom);
		ls_codunimed=eval("f.txtcodunimed"+li_row+".value");
		ls_codunimed=ue_validarvacio(ls_codunimed);
		ls_cancom=eval("f.txtcancom"+li_row+".value");
		ls_cancom=ue_validarvacio(ls_cancom);
	
		if((ls_codcom=="")||(ls_cancom==""))
		{
			alert("Debe llenar todos los campos en la linea "+li_row+"");
			lb_valido=true;
		}
		else
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_siv_d_componentes.php";
			f.submit();
		}
	}
}
function ue_cancelar()
{
	window.close();
}

function uf_agregar_dt(li_row)
{
	f=document.form1;
	ls_codnew=eval("f.txtcodcom"+li_row+".value");
	li_total=f.totalfilas.value;
	lb_valido=false;
	if(li_total==li_row)
	{
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codid=eval("f.txtcodcom"+li_i+".value");
			if((ls_codid==ls_codnew)&&(li_i!=li_row))
			{
				alert("El componente ya esta registrado");
				lb_valido=true;
			}
		}
		ls_codart=eval("f.txtcodart"+li_row+".value");
		ls_codart=ue_validarvacio(ls_codart);
		ls_codcom=eval("f.txtcodcom"+li_row+".value");
		ls_codcom=ue_validarvacio(ls_codcom);
		ls_dencom=eval("f.txtdescom"+li_row+".value");
		ls_dencom=ue_validarvacio(ls_dencom);
		ls_codunimed=eval("f.txtcodunimed"+li_row+".value");
		ls_codunimed=ue_validarvacio(ls_codunimed);
		ls_cancom=eval("f.txtcancom"+li_row+".value");
		ls_cancom=ue_validarvacio(ls_cancom);
	
		if((ls_codcom=="")||(ls_dencom=="")||(ls_codunimed=="")||(ls_cancom==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_siv_d_componentes.php";
			f.submit();
		}
	}
}
function uf_delete_dt(li_row)
{
	f=document.form1;
	ls_codcom=eval("f.txtcodcom"+li_row+".value");
	ls_codcom=ue_validarvacio(ls_codcom);
	li_fila=f.totalfilas.value;
	if(li_fila!=li_row)
	{
		if(ls_codcom=="")
		{
			alert("No deben tener campos vacios");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro "+ls_codcom+"?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_siv_d_componentes.php";
				f.submit();
			}
		}
	}
}


</script> 
</html>