<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro de Detalle Contable</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body >
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="780" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td><div align="right"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15"><a href="javascript: uf_procesar();">Procesar Operaci&oacute;n</a> </div></td>
  </tr>
  <tr>
    <td><div id="resultados" align="center"></div></td>
  </tr>
</table>
	
	</p>
    
    <p><br>    
  </p>
</form>  
</body>
<script language="JavaScript">
function uf_procesar(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_dentipdoc,ls_tipproben,ls_codpro,ls_cedbene)
{
	f=opener.document.formulario;
	li_total=document.formulario.totalrows.value;
	li_opener=f.numrowsprenota.value;
	ls_campos="";
	li_selected=0;
	ls_ctaprov=f.txtcuentaprov.value;
	ls_denctascg=f.txtdenctascg.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_codproben   =f.txtcodproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}	
	ls_codtipdoc=f.txttipdoc.value;	
	if(f.tiponota[0].checked)
	{
	    ls_tiponota='NC';
	}
	else
	{
		ls_tiponota='ND';
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Obtengo los registros existentes en el grid de detalle contable
	for(j=1;j<=li_opener;j++)
	{
			cuenta=eval("f.txtscgcuentancnd"+j+".value");
			dencuenta=eval("f.txtdencuentascgncnd"+j+".value");
			mondeb=eval("f.txtdebencnd"+j+".value");
			monhab=eval("f.txthaberncnd"+j+".value");
			if(cuenta!="")
			{
				li_selected=li_selected+1;
				ls_campos=ls_campos+"&txtscgcuenta"+li_selected+"="+cuenta+"&txtdencuenta"+li_selected+"="+dencuenta+
						   "&txtmondeb"+li_selected+"="+mondeb+"&txtmonhab"+li_selected+"="+monhab;					   
			}
	}	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//Obtengo los valores de los campos del grid para enviarlo por POST
	for(j=1;j<=li_total;j++)
	{
		if(eval("document.formulario.chkcont"+j+".checked"))
		{
			lb_existe=false;
			cuenta=eval("document.formulario.txtscgcuenta"+j+".value");
			dencuenta=eval("document.formulario.txtdencuenta"+j+".value");
			mondeb=eval("document.formulario.txtmondeb"+j+".value");
			monhab=eval("document.formulario.txtmonhab"+j+".value");
			ls_debhab=eval("document.formulario.txtdebhab"+j+".value");
			for(k=1;k<=li_opener;k++)
			{
				ls_cuenta_op=eval("f.txtscgcuentancnd"+k+".value");
				if(ls_cuenta_op==cuenta)
				{
					lb_existe=true;
					break;					
				}
			}
			if(ls_debhab=='D')
			{	
				ldec_montoaux=parseFloat(uf_convertir_monto(mondeb));
			}
			else
			{
				ldec_montoaux=parseFloat(uf_convertir_monto(monhab));
			}
			if(lb_existe==false)
			{
				if(ldec_montoaux>0)
				{					
					li_selected=li_selected+1;
					ls_campos=ls_campos+"&txtscgcuenta"+li_selected+"="+cuenta+"&txtdencuenta"+li_selected+"="+dencuenta+
							   "&txtmondeb"+li_selected+"="+mondeb+"&txtmonhab"+li_selected+"="+monhab;					   
				}
				else
				{
					alert("El monto del detalle contable para la cuenta "+cuenta+", debe ser mayor a cero(0,00)");
				}							   
			}
			else
			{
				alert("La cuenta "+cuenta+" ya existe en el detalle de la nota");
			}		   

		}
	}
	ls_campos=ls_campos+"&txtctaprov="+ls_ctaprov+"&denctascg="+ls_denctascg+"&tiponota="+ls_tiponota+"&selected="+li_selected+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc;	
	if(li_selected>0)
		{
		// Cargamos las variables para pasarlas al AJAX
		divgrid = opener.document.getElementById('detallesnota');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
		
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText;
					}
					else
					{
						if(ajax.status==404)
						{
							divgrid.innerHTML = "La página no existe";
						}
						else
						{//mostramos el posible error     
							divgrid.innerHTML = "Error:".ajax.status;
						}
					}
				}
			}
		}	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("funcion=AGREGARDTNOTACON"+ls_campos);
	}	

}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numord="<?php print $_GET["numord"]?>";
	ls_tipo="<?php print $_GET["tipproben"]?>";
	ls_codproben="<?php print $_GET["codproben"]?>";
	ls_numrecdoc="<?php print $_GET["numrecdoc"]?>";
	ls_numncnd="<?php print $_GET["numncnd"]?>";
	ls_codtipdoc="<?php print $_GET["codtipdoc"]?>";
	ls_tiponota="<?php print $_GET["tiponota"]?>";
	ls_ctaprov=opener.document.formulario.txtcuentaprov.value;
	//ls_nomproben="";
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagenes/loading.gif' width='100' height='10'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=DTCONTABLE&numord="+ls_numord+"&tipproben="+ls_tipo+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tiponota="+ls_tiponota+"&ctaprov="+ls_ctaprov+
			  "&orden="+orden+"&campoorden="+campoorden);
}


function uf_valida_monto(li,ls_debhab)
{
	f=document.formulario;
	if(ls_debhab=='D')
	{
		ldec_monto=eval("f.txtmondeb"+li+".value");
		ldec_montooriginal=parseFloat(eval("f.txtmontooriginaldeb"+li+".value"));
		ldec_monto=parseFloat(uf_convertir_monto(ldec_monto));	
		if(ldec_monto>ldec_montooriginal)
		{
			alert("El monto a registrar no puede ser mayor al registrado en la Recepci&oacute;n de Documento");
			eval("f.txtmondeb"+li+".value='0,00'")
		}
	}
	else
	{
		ldec_monto=eval("f.txtmonhab"+li+".value");
		ldec_montooriginal=parseFloat(eval("f.txtmontooriginalhab"+li+".value"));
		ldec_monto=parseFloat(uf_convertir_monto(ldec_monto));	
		if(ldec_monto>ldec_montooriginal)
		{
			alert("El monto a registrar no puede ser mayor al registrado en la Recepci&oacute;n de Documento");
			eval("f.txtmonhab"+li+".value='0,00'")
		}
	}	
}
function uf_format(obj)
{
	ldec_monto=uf_convertir(obj.value);
	obj.value=ldec_monto;
}

function ue_close()
{
	close();
}
ue_search();
</script>
</html>