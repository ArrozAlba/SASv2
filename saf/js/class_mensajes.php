<?
class class_mensajes
{
	function class_mensajes()//Constructor de la clase.
	{
	}
	function message($ls_message)
	{
		print "<script language=javascript>";
		print " alert (\"$ls_message\");";
		print "</script>";
	}
	function confirm($ls_message)
	{?>
		<script language=javascript>
			a=confirm("<? print $ls_message?>");
			alert(a);
		</script>
		<?
		print $a;
	}
}
?>