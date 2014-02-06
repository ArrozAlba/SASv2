<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
?>

<?php
	$ls_tipdoc=$_GET['id'];

	$ls_database_destino = $_SESSION["ls_data_des"];
	$ls_database_fuente = $_SESSION["ls_database"];

	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	
	$io_conect	= new sigesp_include();
	//$conn		= $io_conect->uf_conectar();
	$io_msg		= new class_mensajes();
	$io_dsprove	= new class_datastore();
	//$io_sql		= new class_sql($conn);
	$io_conexion_destino= $io_conect->uf_conectar($_SESSION["ls_data_des"]);
	$io_sql_destino= new class_sql($io_conexion_destino);

	//If (ThisForm.chk_TipoDoc.value=1)	contable
	//	nCont = 2		1
	//	nPres = 3		3
	//Else								presupuestario
	//	nCont = 2		1
	//	nPres = 2		2
	//Endif

	if ($ls_tipdoc==1)
		{
			$ls_sql="SELECT codtipdoc, dentipdoc".
					"  FROM cxp_documento".
					" WHERE estcon= 1".
					"   AND (estpre = 3 OR estpre = 4) ";
		}
	else
		{
			$ls_sql="SELECT codtipdoc, dentipdoc".
					"  FROM cxp_documento".
					" WHERE estcon= 1".
					"   AND estpre= 2 ";
		}
	$rs_td=$io_sql_destino->select($ls_sql); 

?>
<select name="select_TipDoc" id="select_TipDoc" style="width:150px">
<?php
	while ($row=$io_sql_destino->fetch_row($rs_td))
		{
			$ls_cod = $row["codtipdoc"];
			$ls_nom = $row["dentipdoc"];	
			echo "<option value='$ls_cod'>$ls_nom</option>";
		}
?>
</select>









<?php		//ejemplo de uso del datastore
//print "<table border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
//print "<tr class=titulo-celda>";
//print "<td>Código</td>";
//print "<td>Denominacion</td>";
//print "</tr>";
//$rs_pro=$io_sql->select($ls_sql);
//$data=$rs_pro;
//if ($row=$io_sql->fetch_row($rs_pro))
//{
//		$data=$io_sql->obtener_datos($rs_pro);
//		$arrcols=array_keys($data);
//		$totcol=count($arrcols);
//		$io_dsprove->data=$data;
//		$totrow=$io_dsprove->getRowCount("codtipdoc");
//		for ($z=1;$z<=$totrow;$z++)
//		{
//				print "<tr class=celdas-blancas>";
//				$codtipdoc=$data["codtipdoc"][$z];
//				$dentipdoc=$data["dentipdoc"][$z];
//				print "<td>".$codtipdoc."</td>";
//				print "<td>".$dentipdoc."</td>";
//				print "</tr>";			
//		}
//print "</table>";
//}
?>




















