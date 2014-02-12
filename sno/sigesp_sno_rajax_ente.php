<?php
session_start();
require_once("sigesp_sno_c_concepto.php");
$obj_principal = new sigesp_sno_c_concepto();
$listado = $_POST['catalogo'];
$obj_principal->io_conexiones->codificacion_navegador();


function css_estilo(){
		?>
		
		<link href="../shared/css/catalogos.css" rel="stylesheet" type="text/css">
		<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
		<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
		<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
		<?php 

}


		
			
			
			$entes = $obj_principal->uf_consulta_entes($_POST['txtcod'],$_POST['txtente'],'por_listado');
			?>
			<table width="456" border="0" cellpadding="0" cellspacing="0" class='formato-blanco'>
              <tr class="titulo-celda">                
                <td width="65" align="center">Cod. Ente</td>
                <td width="319" align="center">Ente</td>
                <td width="72" align="center">Porcentaje</td>              
              </tr>
              <?php do { ?>
              <tr id="consulta">
                
                <td width="65" align="right"><a href="#" onClick="aceptar('<?php echo $entes['fila']['codigo_ente'];?>','<?php echo $entes['fila']['descripcion_ente']; ?>','<?php echo $entes['fila']['porcentaje_ente']; ?>')"><?php echo $entes['fila']['codigo_ente']; ?></a></td>
                <td width="319" align="center"><a href="#" onClick="aceptar('<?php echo $entes['fila']['codigo_ente'];?>','<?php echo $entes['fila']['descripcion_ente']; ?>','<?php echo $entes['fila']['porcentaje_ente']; ?>')"><?php echo $entes['fila']['descripcion_ente']; ?></a></td>
				<td width="72" align="right"><a href="#" onClick="aceptar('<?php echo $entes['fila']['codigo_ente'];?>','<?php echo $entes['fila']['descripcion_ente']; ?>','<?php echo $entes['fila']['porcentaje_ente']; ?>')"><?php echo $entes['fila']['porcentaje_ente'];?></a></td>
              </tr>
              <tr>
                <td height="1" bgcolor="#BBBBBB" colspan="4"></td>
              </tr>
              <?php } while ($entes['fila'] = $obj_sql->fetch_row($entes['rs']));?>
            </table>
			 
	<?php 











?>