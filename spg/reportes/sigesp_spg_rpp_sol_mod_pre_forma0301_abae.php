<?php		
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');	
	print("<script language=JavaScript>");
	print "window.open('sigesp_spg_rpp_sol_mod_pre_forma0301_abae01.php?comprobante={$_GET['comprobante']}&procede={$_GET['procede']}&fecha={$_GET['fecha']}','catalogo2','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";
	print "window.open('sigesp_spg_rpp_sol_mod_pre_forma03012.php?comprobante={$_GET['comprobante']}&procede={$_GET['procede']}&fecha={$_GET['fecha']}','catalogo3','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";
	print "window.open('sigesp_spg_rpp_sol_mod_pre_forma03013.php?comprobante={$_GET['comprobante']}&procede={$_GET['procede']}&fecha={$_GET['fecha']}','catalogo4','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";
	print "window.close()";	
	print("</script>");
?>	