<?Php
session_start();

require_once("../../class_folder/sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
require_once("../../../shared/class_folder/sigesp_include.php");
require_once("../../../shared/class_folder/class_mensajes.php");
require_once("../../../shared/class_folder/class_sql.php");
require_once("../../../shared/class_folder/class_funciones.php");
require_once("../../../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
/******************************************************************************************************************************/

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo "<table border=1>\n";
	echo "<tr>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<th></th>\n";
		echo "<th></th>\n";
		echo "<th>Nombre</th>\n";
		echo "<th>Email</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td><font color=green>Manuel Gomez</font></td>\n";
		echo "<td>manuel@gomez.com</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td><font color=blue>Pago gomez</font></td>\n";
		echo "<td>paco@gomez.com</td>\n";
	echo "</tr>\n";
echo "</table>\n";
 
			


?>