<?php
session_start();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Activos </title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">		  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="485" colspan="2" class="titulo-celda">Cat&aacute;logo de Materiales del Edificio </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="85" height="18"><div align="right">C&oacute;digo</div></td>
        <td width="182" height="22"><div align="left">
          <input name="txtcodact" type="text" id="txtcodact">
        </div></td>
        <td colspan="2" rowspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenact" type="text" id="txtdenact">
        </div></td>
        <td width="173">&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
        <td colspan="3"><div align="right"><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools20/aprobado.gif" width="20" height="20" border="0">Aceptar</a></div></td>
      </tr>
    </table> 
<?php	
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codact="%".$_POST["txtcodact"]."%";
	$ls_denact="%".$_POST["txtdenact"]."%";	
	$ls_tipo="";	
}
else
{
	$ls_operacion="";
}
require_once("class_funciones_activos.php");
$io_fac= new class_funciones_activos("../");
$ls_totalgrid=$io_fac->uf_obtenervalor_get("total",""); 
if(array_key_exists("selected",$_POST))
{
	$li_selected= $_POST["selected"];
}
else
{
	$li_selected= 0;
} 	
	function uf_print($ls_tipo,&$totrow)
	{
		require_once("../shared/class_folder/sigesp_include.php");
		$in=     new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_msg= new class_mensajes();
		require_once("../shared/class_folder/class_datastore.php");
		$ds=     new class_datastore();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql= new class_sql($con);
		require_once("../shared/class_folder/class_funciones.php");
		$io_fun= new class_funciones();
		require_once("../shared/class_folder/class_fecha.php");
		$io_fec= new class_fecha();
		$arr=$_SESSION["la_empresa"];
		$ls_codemp=$arr["codemp"];	
		require_once("../shared/class_folder/grid_param.php");
		$grid = new grid_param();		
		$title[1]="Todos <input name=chkall type=checkbox id=chkall value=T style=height:15px;width:15px 
				  onClick=javascript:uf_select_all(); >";	
		$title[2]="Tipo de Material";   
		$title[3]="Código";
		$title[4]="Denominación del componente"; 
		$grid1="grid";
		
		$ls_sql=" SELECT saf_componente.*, saf_tipoestructura.dentipest ".
					"	FROM saf_componente                                 ".
					"	JOIN saf_tipoestructura ON (saf_tipoestructura.codemp=saf_componente.codemp ".
					"                           AND  saf_tipoestructura.codtipest=saf_componente.codtipest) ".
					"   ORDER BY saf_componente.codtipest, saf_componente.codcomp";  
		$rs_data=$io_sql->select($ls_sql);
		if(($rs_data===false))
		{
			$io_msg->message("Error en select");
		}
		else
		{
			$totrow=$io_sql->num_rows($rs_data);
			if ($totrow>0)
			{
				while($row=$io_sql->fetch_row($rs_data))
				{
					$ls_dentipest=$row["dentipest"];
					$ls_codcomp=$row["codcomp"];
					$ls_dencomp=$row["dencomp"];
					$ls_codtipest =$row["codtipest"];
					$z++;
					$object[$z][1]="<input name=chktip".$z." type=checkbox id=chktip".$z." value=1 class=sin-borde onClick=javascript:uf_selected('".$z."');>";
					$object[$z][2]="<input type=text name=txtdentipest".$z." value='".$ls_dentipest."' id=txtdentipest".$z." class=sin-borde readonly style=text-align:center size=30 maxlength=30 >
					<input type=hidden name=txtcodtipest".$z." value='".$ls_codtipest."' id=txtcodtipest".$z." readonly >";		
					$object[$z][3]="<input type=text name=txtcodcomp".$z." value='".$ls_codcomp."' id=txtcodcomp".$z." class=sin-borde readonly style=text-align:left size=12 maxlength=12>";	
					$object[$z][4]="<input type=text name=txtdencomp".$z." value='".$ls_dencomp."' id=txtdencomp".$z." class=sin-borde readonly style=text-align:left size=50 maxlength=50>";
								
				}
			}
			else
			{
				$object[1][1]="<input name=chktip1 type=checkbox id=chktip1 value=1 class=sin-borde onClick=javascript:uf_selected('1');>";
				$object[1][2]="<input type=text name=txtdentipest1 value='' id=txtdentipest1 class=sin-borde readonly style=text-alin:center size=30 maxlength=30>
				<input type=hidden name=txtcodtipest1 value='' id=txtcodtipest1 readonly >";		
				$object[1][3]="<input type=text name=txtcodcomp1 value='' id=txtcodcomp1 class=sin-borde readonly style=text-align:left size=12 maxlength=12>";	
				$object[1][4]="<input type=text name=txtdencomp1 value='' id=txtdencomp1 class=sin-borde readonly style=text-align:left size=50 maxlength=50>";
			}
			$grid->makegrid($totrow,$title,$object,500,'Catalogo de Materiales',$grid1);
			print "</table>";		
		}		
	}// fin de la funcion print

if($ls_operacion=="BUSCAR")
{
    $totrow=0;
	uf_print($ls_tipo,$totrow);		
}					
?>
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">  
 <input name="selected" type="hidden" id="selected" value="<?php print $li_selected;?>">
 <input name="totalgrid" type="hidden" id="totalgrid" value="<?php print $ls_totalgrid;?>"> 
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
	  li_totalgrid=f.totalgrid.value;  
	  li_selected=f.selected.value;    
	  parametros="";
	  parametros1="";
	  parametros2="";
	  li_sel=0;	
	  li_row=0;
	  lb_valido=true;  
	  for(i=1;(i<=parseInt(li_total,10));i++)	
	  {	    
			if(li_sel<parseInt(li_selected,10))
			{
				if(eval("f.chktip"+i+".checked==true"))
				{
					li_sel=li_sel+1;				
					ls_dentipest=eval("f.txtdentipest"+i+".value"); 
					ls_codtipest=eval("f.txtcodtipest"+i+".value");
					ls_codcomp= eval("f.txtcodcomp"+i+".value");
					ls_dencomp= eval("f.txtdencomp"+i+".value");
					if (i<10)
					{
					  j="0"+i;
					}			
					else
					{
						j=i;
					}	
					parametros= parametros+"&txtcodcomp"+j+"= "+ls_codcomp+"&txtdencomp"+j+"= "+ls_dencomp+"&txtdentipest"+j+"= "+ls_dentipest+"&txtcodtipest"+j+"= "+ls_codtipest; 								
				}			
			}
			else
			{
				break;
				close();			
			}	
	  }//fin del for
	  if (li_totalgrid>1)
	  {     
	        li_totalgrid=li_totalgrid-1;						 
			for(li=1; (li<=li_totalgrid); li++)
			{ 
			    li_sel=li_sel+1;
				ls_dentipest=eval("opener.document.form1.txtdentipest"+li+".value");
				ls_codtipest=eval("opener.document.form1.txtcodtipest"+li+".value");
				ls_codcomp=eval("opener.document.form1.txtcodcomp"+li+".value"); 
				ls_dencomp=eval("opener.document.form1.txtdencomp"+li+".value"); 
				parametros1= parametros1+"&txtcodcomp"+li+"= "+ls_codcomp+"&txtdencomp"+li+"= "+ls_dencomp+"&txtdentipest"+li+"= "+ls_dentipest+"&txtcodtipest"+li+"= "+ls_codtipest;	
				
			}
			
			for(li_j=1; (li_j<=li_totalgrid); li_j++)
			{ 
					if(eval("f.chktip"+li_j+".checked==true"))
					{
						ls_dentipest= eval("f.txtdentipest"+li_j+".value");
						ls_codcomp= eval("f.txtcodcomp"+li_j+".value");
						ls_dencomp= eval("f.txtdencomp"+li_j+".value");
						ls_dentipestgrid=eval("opener.document.form1.txtdentipest"+li_j+".value");
						ls_codcompgrid=eval("opener.document.form1.txtcodcomp"+li_j+".value"); 
						ls_dencomgrid=eval("opener.document.form1.txtdencomp"+li_j+".value");  
						if((ls_dentipest==ls_dentipestgrid)&&((ls_codcomp==ls_codcompgrid))&&((ls_dencomp==ls_dencomgrid)))
						{
							alert("El Material : "+" "+ls_dencomgrid+" "+ "ya fue incluida !!!");
							lb_valido=false;					
						}
						if (lb_valido)
						{
							li_sel=li_sel+1;
							parametros2= parametros2+"&txtcodcomp"+li_sel+"= "+ls_codcomp+"&txtdencomp"+li_sel+"= "+ls_dencomp+"&txtdentipest"+li_sel+"= "+ls_dentipest+"&txtcodtipest"+li_sel+"= "+ls_codtipest; 								
						}
					}								
			}// frin del for
			parametros=parametros1+parametros2+parametros; //alert (parametros2)
	  }	// fin del if
	  fop.htotal.value= li_sel; 
	  fop.hparam1.value= parametros;
	  fop.operacion.value="AGREGAR";
	  fop.action="sigesp_saf_d_inmueble_edificio.php";
	  fop.submit();
	  close(); 	  
}


function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR"; 
	f.action="sigesp_saf_cat_material_edificio.php?total=<?PHP print $ls_totalgrid;?>";
	f.submit();
}

function uf_select_all()
{
	  f=document.form1;
	  fop=opener.document.form1;
	  total=f.total.value; 
	  sel_all=f.chkall.value; 
	  if(f.chkall.checked==true)
	  {
		  for(i=1;i<=total;i++)	
		  {
			eval("f.chktip"+i+".checked=true");			
		  }		 
	  }
	  else
	  {
	  	for(i=1;i<=total;i++)	
		  {
			eval("f.chktip"+i+".checked=false");				
		  }	
	  } 
	  li_selected=f.selected.value=total;		   	  
}

function uf_selected(li_i)
 {
 	f=document.form1;
	li_total=f.total.value;
	li_selected=f.selected.value; 
	if(eval("f.chktip"+li_i+".checked==true"))
	{
		li_selected=parseInt(li_selected,10)+1;
	}
 	f.selected.value=li_selected; 
 }	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
