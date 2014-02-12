<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Bienes y Materiales</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<style type="text/css">
<!--
.Estilo2 {font-size: 11px}
-->
</style>
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/grid_param.php");

$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
$io_msg=new class_mensajes();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$io_fun=new class_funciones(); 
$grid=new grid_param();

$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];  
	 
}
else
{
	$ls_operacion = "";	    
	/*$ls_basimp    = $_GET["basimp"];
	$ls_basimp    = str_replace(".","",$ls_basimp);
	$ls_basimp=str_replace(",",".",$ls_basimp);
	$ls_fila      = $_GET["fila"];*/
}
if  (array_key_exists("total",$_POST))
{
   $totrow=$_POST["total"];	  
}
else
{
   $totrow="";
}
?>
<form name="form1" method="post" action="">
  <table width="534" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="532" colspan="6" align="center" bordercolor="#FFFFFF">
        <div align="center" class="Estilo2">
          <p align="right">            &nbsp;&nbsp;&nbsp;<a href="javascript: uf_aceptar(document.form1.total.value);"><img src="../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0" onClick="ue_search()">Agregar Cr&eacute;dito</a></p>
      </div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
  <p align="center">
    <?php
	$title[1]=""; $title[2]="Código"; $title[3]="Denominación"; $title[4]="Porcentaje"; $title[5]="Formula";  
	$grid1="grid";	

    $ls_codemp=$la_emp["codemp"];
	
    $ls_sql="SELECT * FROM sigesp_cargos WHERE codemp='".$ls_codemp."' ORDER BY codcar ASC  ";
							
    $rs=$io_sql->select($ls_sql);	
	if($rs==false)
	{
		$io_msg->message($io_fun->uf_convertirmsg($io_sql->message));
	}
	else
	{
		$data=$rs;
		
		if($row=$io_sql->fetch_row($rs))		
		{          
		
			$data=$io_sql->obtener_datos($rs);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$io_ds->data=$data;
			$totrow=$io_ds->getRowCount("codcar");
			if ($totrow>0)
			   {
				for ($z=1;$z<=$totrow;$z++)
				    {
					  $ls_codcar=$data["codcar"][$z];
					  $ls_dencar=$data["dencar"][$z];					  
					  $ld_porcar=$data["porcar"][$z];
					  $ls_forcar=$data["formula"][$z];
					  $ld_porcar=number_format($ld_porcar,2,",",".");	
					  
					  $object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde >";
					  $object[$z][2]="<input type=text name=txtcodcar".$z."  value='".$ls_codcar."' id=txtcodcar".$z."  class=sin-borde readonly  style=text-align:center  size=7  >";		
					  $object[$z][3]="<input type=text name=txtdencar".$z."  value='".$ls_dencar."' id=txtdencar".$z."  class=sin-borde readonly  style=text-align:left    size=30 >";
					  $object[$z][4]="<input type=text name=txtporcar".$z."  value='".$ld_porcar."' id=txtporcar".$z."  class=sin-borde readonly  style=text-align:right   size=7  >";
					  $object[$z][5]="<input type=text name=txtforcar".$z."  value='".$ls_forcar."' id=txtforcar".$z."  class=sin-borde readonly  style=text-align:right   size=20 >";
				    }				
			   }
			else
			   {
					  $object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1 class=sin-borde >";
					  $object[$z][2]="<input type=text name=txtcodcar".$z."  value='' id=txtcodcar".$z."  class=sin-borde readonly  style=text-align:center  size=7  >";		
					  $object[$z][3]="<input type=text name=txtdencar".$z."  value='' id=txtdencar".$z."  class=sin-borde readonly  style=text-align:left    size=30 >";
					  $object[$z][4]="<input type=text name=txtporcar".$z."  value='' id=txtporcar".$z."  class=sin-borde readonly  style=text-align:right   size=7  >";
					  $object[$z][5]="<input type=text name=txtforcar".$z."  value='' id=txtforcar".$z."  class=sin-borde readonly  style=text-align:right   size=20 >";
					  $totrow=1;
			   }
			$grid->makegrid($totrow,$title,$object,600,'Catálogo de Créditos',$grid1);
			print "</table>";
	  }
	  else
	  {
	  ?>
	    <script language="javascript">
		  alert("No se han creado Créditos");			 
		</script>
      <?php
	  }
 }
 
?>
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
    <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>"> 
	<input name="basimp"   type="hidden"   id="basimp"   value="<?php print $ls_basimp;?>"> 
	<input name="fila"   type="hidden"   id="fila"   value="<?php print $ls_fila;?>">  
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cxp_cat_iva.php";
	  f.submit();
  }	
  function uf_aceptar(fil)
  {
	  f     =document.form1;
	  fop   =opener.document.form1;
      total =f.total.value;
	  filsel=fop.filsel.value;	       	  	
	  
	  total=parseInt(total);	        	 	 
	  filsel=parseInt(filsel);	        	 	
	  li_sel=0;	 
	  lb_valido=true;
	  
	  for (i=1;i<=total;i++)	
	  {  	
		lb_valido=true;
		if (eval("f.chk"+i+".checked==true"))
		 {
			 li_sel=li_sel+1;
             if(li_sel>1)
			 {
			   alert ("Solo puede seleccinar un Crédito");
			   lb_valido=false
			 }
		 }
  	  }  
	  if(lb_valido)
	  {	    
	     	for (i=1;i<=total;i++)	
			{  	
				lb_valido=true;
				if (eval("f.chk"+i+".checked==true"))
				 {  
				    ls_porcar=eval("f.txtporcar"+i+".value");
				    ls_forcar=eval("f.txtforcar"+i+".value");
					
					eval("fop.txtporiva"+filsel+".value='"+ls_porcar+"'");
					eval("fop.hidforcar"+filsel+".value='"+ls_forcar+"'");
				 }
			} 
	  }	  	  
  fop.operacion.value="CALCULAR";
  fop.submit();
  close();
}
</script>
</html>
