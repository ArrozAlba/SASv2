<?php
$archivo=$_GET['archivo'];
$enlace=$_GET['enlace'].$archivo;
$tipo=$_GET['tipo'];
switch ($tipo)
{
	case 'abrir':
		header ('Content-Disposition: attachment; filename='.$archivo.'');
		header ('Content-Type: application/octet-stream');
		header ('Content-Length: '.filesize($enlace));
		readfile($enlace);
	break;
	
	case 'eliminar':
		header('Pragma: public');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		@unlink($enlace);
	break;
}
?>
