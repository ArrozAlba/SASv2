<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cargos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<br>
<?php
require_once("../../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
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
  <table width="192" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="190" align="center">Filas
          <select name="cmbfilas" id="cmbfilas" onChange="javascript:uf_pintar_filas(cmbfilas.value);">
            <option value="0">0</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="35">35</option>
            <option value="40">40</option>
            <option value="45">45</option>
            <option value="50">50</option>
            <option value="55">55</option>
            <option value="60">60</option>
          </select>
          <a href="javascript: uf_aceptar(document.form1.total.value);"><img src="../../shared/imagebank/tools20/aprobado.gif" alt="Aceptar" width="20" height="20" border="0">Aceptar</a>   
    </tr>
  </table>
  <p align="center">
    <?php
$title[1]="Check"; $title[2]="Código"; $title[3]="Denominación"; $title[4]="Porcentaje"; 
$grid1="grid";	
if($ls_operacion=="")
{
    $ls_codemp=$la_emp["codemp"];
    $ls_sql=" SELECT codcar,dencar,formula,codestpro,spg_cuenta,porcar ".
            " FROM sigesp_cargos ".
			" ORDER BY codcar ASC";  

    $rs=$io_sql->select($ls_sql);	
	if($rs==false)
	{
		$msg->message($fun->uf_convertirmsg($io_sql->message));
	}
	else
	{
		$data=$rs;
		if ($row=$io_sql->fetch_row($rs))
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
					   $object[$z][1]="<input name=chk".$z." type=checkbox id=chk".$z." value=1                        class=sin-borde>";
					   $object[$z][2]="<input type=text name=txtcodcar".$z." value='".$ls_codcar."' id=txtcodcar".$z." class=sin-borde readonly style=text-align:center size=15 maxlength=10 >";		
					   $object[$z][3]="<input type=text name=txtdencar".$z." value='".$ls_dencar."' id=txtdencar".$z." class=sin-borde readonly style=text-align:left   size=60 maxlength=254>";
					   $object[$z][4]="<input type=text name=txtporcar".$z." value='".$ld_porcar."' id=txtporcar".$z." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
				     }				
			   }
			else
			   {
			 	 $object[1][1]="<input name=chk1 type=checkbox id=chk1 value=1>";
			  	 $object[1][2]="<input type=text name=txtcodcar value='' id=txtcodcar class=sin-borde readonly style=text-align:center size=15 maxlength=10>";		
				 $object[1][3]="<input type=text name=txtdencar value='' id=txtdencar class=sin-borde readonly style=text-align:left   size=25 maxlength=254>";
                 $object[1][4]="<input type=text name=txtporcar value='' id=txtporcar class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
				 $totrow=1;
			   }
			$grid->makegrid($totrow,$title,$object,520,'Catálogo de Cargos',$grid1);
		}
		else
		{ ?>
			<script language="javascript">
			alert("No se han creado Cargos !!!");
			close();
			</script>
	    <?php
		}
	 }
  }
print "</table>";
?>
    <input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_pintar_filas(fila)
  {
    var antfilas;
    
    antfilas=eval(opener.document.form1.totrows.value);   
    filas=(eval(antfilas)+eval(fila)); 
    opener.document.form1.totrows.value=filas;
    opener.document.form1.operacion.value="PINTAR";
    opener.document.form1.submit();
  }

	
  function uf_aceptar(fil)
  {//1
	  f        =document.form1;
	  fop      =opener.document.form1;
      total    =f.total.value;
      lb_valido=true;
	  li_sel=0;
	  li_row=0;
	  lastrow    =fop.lastrow.value;     
      totalcargos=parseInt(lastrow);
	  
	  totrow =fop.totrows.value;     	  
      totdt=parseInt(totrow);
      moncar=0;

 	  for (i=1;i<=total;i++)	
	  {//2	  	        
		lb_valido=true;
		if (eval("f.chk"+i+".checked==true"))
		   {//3
			 li_sel=li_sel+1;
			 ls_codcar=eval("f.txtcodcar"+i+".value");
			 for (var j=1;j<=totalcargos;j++)
			 {//4
			   txt ="txtcodcar"+j;
		       cargo=eval("opener.document.form1."+txt+".value");
			   if (cargo==ls_codcar) 
			   {//5				
			      alert("El Cargo : "+" "+cargo+" "+ "ya fue incluido !!!");
			      lb_valido=false;
			   }//5
			 }//4
 
		     if (lb_valido)
			 {//6				
			    tot=fop.lastrow.value;
		        tot=parseInt(tot);  
               
		        li_row=tot+1;
                if(totdt>=li_row)
				{
					ls_codcar=eval("f.txtcodcar"+i+".value");
					ls_dencar=eval("f.txtdencar"+i+".value");          
					ls_porcar=eval("f.txtporcar"+i+".value");          
	
					eval("fop.txtcodcar"+li_row+".value='"+ls_codcar+"'");
					eval("fop.txtdencar"+li_row+".value='"+ls_dencar+"'");
					eval("fop.txtporcar"+li_row+".value='"+ls_porcar+"'");		 	   
					fop.lastrow.value=li_row;
                }
				else
				{
				  alert("Por favor, Agregue mas filas para insertar el resto de los detalles");
				}
			 }//6
         }//3		
	 }//2
close();
}//1
 
  function uf_select_all()
  {
	  f=document.form1;
	  fop=opener.document.form1;
	  total=f.total.value;
	  sel_all=f.chkall.value;
	  li_sel=0;
	  li_row=0;
	  if(sel_all=='T')
	  {
		  for(i=1;i<=total&&li_sel<=50;i++)	
		  {
			eval("f.chkcta"+i+".checked=true")
			li_sel=li_sel+1;
		  }
		  if(li_sel>50)
		  {
			alert("Se seleccionaran solo 50 cuentas a procesar");
			return ;
		  }
	   }
   }

  

</script>
</html>
