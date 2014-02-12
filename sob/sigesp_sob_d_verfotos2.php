<?
session_start();
$ls_codigo=$_GET["codigo"];
$ls_foto=$_SESSION["foto".$ls_codigo];
print base64_decode($ls_foto);	
?>