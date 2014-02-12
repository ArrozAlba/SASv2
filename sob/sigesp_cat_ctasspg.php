<?php
session_start();

require_once("class_folder/sigesp_sob_c_reportes.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
$io_getdata=new sigesp_sob_c_reportes();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_datastore=new class_datastore();
$io_msg=new class_mensajes();
$io_funcion=new class_funciones();
$io_sql=new class_sql($io_connect);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$ls_estmodest=$arr["estmodest"];

if($ls_estmodest==2)
 { 				
	if(array_key_exists("operacion",$_POST))
 	 {
			$ls_operacion=$_POST["operacion"];
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
			$ls_estpro4=$_POST["codestpro4"];
			$ls_estpro5=$_POST["codestpro5"];
			$ls_estcla=$_POST["cmbestcla"];
 
 	 }
    else
    {
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro3="";
			$ls_estpro4="";
			$ls_estpro5="";
			$ls_codscg="";
			$ls_estcla="";
     }
 }
elseif(array_key_exists("operacion",$_POST))	 
	{
			$ls_operacion=$_POST["operacion"];
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];
			$ls_estcla=$_POST["cmbestcla"];
	}
	else
    {
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro3="";
			$ls_codscg="";
			$ls_estcla="";
    }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Presupuestarias</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <table width="715" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="629" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Cuentas Presupuestarias de Gastos </div></td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="170" height="22" align="right"><?php print $arr["nomestpro1"] ?></td>
        <td width="191" height="22">
		
		  <div align="left">
		    <select name="codestpro1" size="1" id="codestpro1" onChange="ue_llenarcmb();">
		      <option value="">---seleccione---</option>
		 <?Php
		    $ls_sql="SELECT codestpro1 ".
			        "  FROM spg_cuentas".
					" GROUP BY codestpro1".
					" ORDER BY codestpro1 ASC";
		    $rs_data=$io_sql->select($ls_sql);
	        $data=$rs_data;
      	    if($rs_data===false)
	         {
		       $is_msg_error="Error en select estpro1".$io_funcion->uf_convertirmsg($io_sql->message);
	         }      
	         else
	         {
		      if($row=$io_sql->fetch_row($rs_data))
		       {
			     $data=$io_sql->obtener_datos($rs_data);
			     $io_datastore->data=$data;
			     $li_totrow=$io_datastore->getRowCount("codestpro1");
				 
				 for($li_z=1;$li_z<=$li_totrow;$li_z++)
			      {
				   $ls_codestpro1=$data["codestpro1"][$li_z];
				   
				    if ($ls_estpro1==$ls_codestpro1)
			         {
				       print "<option value='$ls_codestpro1' selected>$ls_codestpro1</option>";
			         }
		             else
			         {
				       print "<option value='$ls_codestpro1'>$ls_codestpro1</option>";
			         }
			      }
			   }
		       else
		       {
		            $io_msg->message("No hay Registros!!");
		       }
		     $io_sql->free_result($rs_data);
		     $io_sql->close();
	       }
			?>
            </select>
        </div></td>
        <td width="68" height="22"><div align="right"></div></td>
        <td width="108" height="22">
	    <div align="left"></div></td>
        <td width="63" height="22"><div align="right"></div></td>
        <td width="113" height="22">
	    <div align="left"></div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right"><?php print $arr["nomestpro2"] ?></td>
        <td height="22"><?php
			   if($ls_estpro1=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codestpro2 ".
			                     "  FROM spg_cuentas ".
								 " WHERE codestpro1='".$ls_estpro1."' ".
								 " GROUP BY codestpro2 ORDER BY codestpro2 ASC";
			  	         $lb_valido=$io_getdata->uf_datacombo($ls_sql,$la_estpro2);
					 } 	
						
					if($lb_valido)
					{
						$io_datastore->data=$la_estpro2;
						$li_totalfilas=$io_datastore->getRowCount("codestpro2");
					}
					else{$li_totalfilas=0;}		
			   ?>
          <select name="codestpro2" size="1" id="codestpro2" onChange="ue_llenarcmb();">
          <option value="">---seleccione---</option>
          <?php
		        for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
				 {
				   $ls_codestpro2=$io_datastore->getValue("codestpro2",$li_i);
				   
				   if ($ls_estpro2==$ls_codestpro2)
					{
					  print "<option value='$ls_codestpro2' selected>$ls_codestpro2</option>";
					}
					else
					{
					  print "<option value='$ls_codestpro2'>$ls_codestpro2</option>";
					}
				} 
			  ?>
                  </select></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right"><?php print $arr["nomestpro3"] ?></td>
        <td height="22"><?php 
		          if($ls_estpro2=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codestpro3".
			                     "  FROM spg_cuentas ".
					             " WHERE codestpro1='".$ls_estpro1."'".
								 "   AND codestpro2='".$ls_estpro2."'".
								 " GROUP BY codestpro3 ORDER BY codestpro3";
		   	  	         $lb_valido=$io_getdata->uf_datacombo($ls_sql,$la_estpro3);
					 } 	
						
					if($lb_valido)
					{
						$io_datastore->data=$la_estpro3;
						$li_totalfilas=$io_datastore->getRowCount("codestpro3");
					}
					else{$li_totalfilas=0;}		
			   ?>
          <select name="codestpro3" size="1" id="codestpro3" onChange="ue_llenarcmb();">
          <option value="">---seleccione---</option>
          <?php
		        for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
				 {
				   $ls_codestpro3=$io_datastore->getValue("codestpro3",$li_i);
				   
				   if ($ls_estpro3==$ls_codestpro3)
					{
					  print "<option value='$ls_codestpro3' selected>$ls_codestpro3</option>";
					}
					else
					{
					  print "<option value='$ls_codestpro3'>$ls_codestpro3</option>";
					}
				} 
			  ?>
        </select></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <?php
	    if ($ls_estmodest=='2')
	    {	
      ?>
	  <tr>
        <td height="22" style="text-align:right"><?php print $arr["nomestpro4"] ?></td>		
        <td height="22"><?php 
		           if($ls_estpro3=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codestpro4".
			                     "  FROM spg_cuentas ".
					             " WHERE codestpro1='".$ls_estpro1."'".
								 "   AND codestpro2='".$ls_estpro2."'".
								 "   AND codestpro3='".$ls_estpro3."'".
								 " GROUP BY codestpro4 ORDER BY codestpro4";
		   	  	         $lb_valido=$io_getdata->uf_datacombo($ls_sql,$la_estpro4);
					 } 	
						
					if($lb_valido)
					{
						$io_datastore->data=$la_estpro4;
						$li_totalfilas=$io_datastore->getRowCount("codestpro4");
					}
					else{$li_totalfilas=0;}		
			   ?><label>
          <select name="codestpro4" id="codestpro4" onChange="ue_llenarcmb();">
            <option  value="">---seleccione---</option>
			   <?php
		        for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
				 {
				   $ls_codestpro4=$io_datastore->getValue("codestpro4",$li_i);
				   
				   if ($ls_estpro4==$ls_codestpro4)
					{
					  print "<option value='$ls_codestpro4' selected>$ls_codestpro4</option>";
					}
					else
					{
					  print "<option value='$ls_codestpro4'>$ls_codestpro4</option>";
					}
				} 
			  ?>
		  </select>
        </label></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right"><?php print $arr["nomestpro5"] ?></td>
        <td height="22"> <?php 
		          if($ls_estpro4=="")
					{
						$lb_valido=false;
					}	
					else
					 {			
						 $ls_sql="SELECT codestpro5".
			                     "  FROM spg_cuentas ".
					             " WHERE codestpro1='".$ls_estpro1."'".
								 "   AND codestpro2='".$ls_estpro2."'".
								 "   AND codestpro3='".$ls_estpro3."'".
								 "   AND codestpro4='".$ls_estpro4."'".
								 " GROUP BY codestpro5 ORDER BY codestpro5";
		   	  	         $lb_valido=$io_getdata->uf_datacombo($ls_sql,$la_estpro5);
					 } 	
						
					if($lb_valido)
					{
						$io_datastore->data=$la_estpro5;
						$li_totalfilas=$io_datastore->getRowCount("codestpro5");
					}
					else{$li_totalfilas=0;}		
			   ?><label>
          <select name="codestpro5" id="codestpro5">
            <option value="">---seleccione---</option>
          <?php
		        for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
				 {
				   $ls_codestpro5=$io_datastore->getValue("codestpro5",$li_i);
				   
				   if ($ls_estpro5==$ls_codestpro5)
					{
					  print "<option value='$ls_codestpro5' selected>$ls_codestpro5</option>";
					}
					else
					{
					  print "<option value='$ls_codestpro5'>$ls_codestpro5</option>";
					}
				} 
			  ?>
	    </select>
        </label></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
	  <?php		
		}
	  ?>
      <tr>
        <td height="13"><div align="right">Estatus de Clasificaci&oacute;n </div></td>
        <td><select name="cmbestcla" id="cmbestcla">
          <option value="P">Proyecto</option>
          <option value="A">Accion</option>
        </select>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="35">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></td>
      </tr>
    </table>
	<br>
    <?
if($ls_operacion=="BUSCAR")
{
	if($ls_estmodest==2)
		{
			print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td>Presupuestaria</td>";
			print "<td>Denominación</td>";
			print "<td>Disponible</td>";
			print "</tr>";
			$ls_sql ="";
			$ls_sql =" SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,denominacion,".
						"        (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible,status ".
						"   FROM spg_cuentas".
						"  WHERE codestpro1 like '%".$ls_estpro1."%'".
						"    AND codestpro2 like '%".$ls_estpro2."%'".
						"    AND codestpro3 like '%".$ls_estpro3."%'".
						"    AND codestpro4 like '%".$ls_estpro4."%'".
						"    AND codestpro5 like '%".$ls_estpro5."%'".
						"    AND estcla='".$ls_estcla."'".
						"    AND spg_cuenta LIKE '404%' ORDER BY spg_cuenta ASC";
			$lb_valido=$io_getdata->uf_datacombo($ls_sql,$la_cuenta);
			if($lb_valido)
			 {
				$io_datastore->data=$la_cuenta;
				$li_totalfilas=$io_datastore->getRowCount("spg_cuenta");
			 }
			 else{$li_totalfilas=0;}
        }
	else
		{
			print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td>Presupuestaria</td>";
			print "<td>Denominación</td>";
			print "<td>Disponible</td>";
			print "</tr>";
			$ls_sql ="";
			$ls_sql=" SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,denominacion,".
					"       (asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible,status ".
					"  FROM spg_cuentas".
					" WHERE codestpro1='".$ls_estpro1."'".
					"   AND codestpro2='".$ls_estpro2."'".
					"   AND codestpro3='".$ls_estpro3."'".
					"   AND spg_cuenta LIKE '404%'".
					" ORDER BY spg_cuenta ASC";
			$lb_valido=$io_getdata->uf_datacombo($ls_sql,$la_cuenta);
			if($lb_valido)
			 {
				$io_datastore->data=$la_cuenta;
				$li_totalfilas=$io_datastore->getRowCount("spg_cuenta");
			 }
			 else{$li_totalfilas=0;}
		}	
	for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
	 {
	   print "<tr class=celdas-blancas>";
	   $codestpro1=$io_datastore->getValue("codestpro1",$li_i);
	   $codestpro2=$io_datastore->getValue("codestpro2",$li_i);
	   $codestpro3=$io_datastore->getValue("codestpro3",$li_i);
	   $codestpro4=$io_datastore->getValue("codestpro4",$li_i);
	   $codestpro5=$io_datastore->getValue("codestpro5",$li_i);
	   $cuenta=$io_datastore->getValue("spg_cuenta",$li_i);	   	   	   	   	   	   
	   $denominacion=$io_datastore->getValue("denominacion",$li_i);
	   $denominacion=str_replace("'"," ",$denominacion);
	   $denominacion=str_replace("|"," ",$denominacion);
	   $denominacion=str_replace("^"," ",$denominacion);					
	   $li_pos=true;
	   while($li_pos!==false)
		{
		 $li_pos=strpos($denominacion,'"');
		 if($li_pos)
		  $denominacion=substr_replace($denominacion," ",$li_pos,1);
		}	
	  $disponible=$io_datastore->getValue("disponible",$li_i);
	  $status=$io_datastore->getValue("status",$li_i);
	  if($status=="S")
	   {
		  print "<tr class=celdas-blancas>";
		  print "<td>".$cuenta."</td>";
		  print "<td  align=left>".$denominacion."</td>";
		  print "<td  align=right>".$io_funcsob->uf_convertir_numerocadena($disponible)."</td>";				
	   }
	   else
	   {
		  print "<tr class=celdas-azules>";
		  print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$codestpro1','$codestpro2','$codestpro3','$codestpro4','$codestpro5','$disponible');\">".$cuenta."</a></td>";
		  print "<td  align=left>".$denominacion."</td>";
	  	  print "<td  align=right>".$io_funcsob->uf_convertir_numerocadena($disponible)."</td>";				
       }
	  print "</tr>";	
	}
	print "</table>";
  }

?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,denominacion,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,disponible)
  {
    opener.ue_cargarcuenta(cuenta,denominacion,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,disponible);	
    close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspg.php";
	  f.submit();
  }
  
  function ue_llenarcmb()
  {
      f=document.form1;
      f.action="sigesp_cat_ctasspg.php";
      f.operacion.value="";
      f.submit();
  }
</script>
</html>
