<?
	session_start();
	require_once("../../class_folder/dao/sigesp_srh_c_unidadadmin.php");
	
	$io_unidad= new sigesp_srh_c_unidadadmin('../../../');
    
if (isset($_GET['valor']))
{
	    $evento=$_GET['valor'];

		if($evento=="createXML")
		{
			
			$ls_denuniadm="%%";
			$ls_tipo=$_REQUEST['txttipo'];
						
			header('Content-type:text/xml');			
			print $io_unidad->uf_srh_buscar_unidadadmin($ls_denuniadm,$ls_tipo);
		}
		
		elseif($evento=="buscar")
		{

			$ls_denuniadm="%".utf8_encode($_REQUEST['txtdenuniadm'])."%";			
			$ls_tipo=$_REQUEST['txttipo'];
			header('Content-type:text/xml');	
			print $io_unidad->uf_srh_buscar_unidadadmin($ls_denuniadm,$ls_tipo);
			
		}
			
}



?>
