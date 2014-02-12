<?php
$ls_file=$_GET["file"];
$ls_enlace=$_GET["enlace"]."/".$ls_file;
header ("Content-Disposition: attachment; filename=".$ls_file."\n\n");
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($ls_enlace));
readfile($ls_enlace);
?>
