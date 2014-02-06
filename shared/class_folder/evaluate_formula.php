<?php
class evaluate_formula
{
	var $io_msg;
	function evaluate_formula()
	{
		require_once("class_mensajes.php");
		$this->io_msg=new class_mensajes();
	}
	
	
	function iif($ad_condicional,$ad_true,$ad_false)
	{
		if(eval("return $ad_condicional;"))
		{
			$ad_return=doubleval($ad_true);
		}
		else
		{
			$ad_return=doubleval($ad_false);
		}
		return $ad_return;
	}
	
	
	function uf_evaluar($ls_formula,$ldec_monto,&$lb_valido)
	{
	  $ls_form = str_replace("IIF","\$this->iif",$ls_formula);
	  $ls_form = str_replace("\$LD_MONTO",$ldec_monto,$ls_form);
	  $result  = @eval("return $ls_form;");
	  if ($result===false)
	     {
		   $lb_valido = false;
		   return 0;
		 }
	  else
	     {
	       $lb_valido = true;
		 }
	  return $result;
	}

	function uf_evaluar_nomina($ls_formula,&$result)
	{
		$ls_codconc="";
		if(array_key_exists("la_conceptopersonal",$_SESSION))
		{
			$ls_codconc=$_SESSION["la_conceptopersonal"]["codconc"];
		}
		$ls_form=str_replace("IIF","\$this->iif",$ls_formula);
		$result=@eval("return $ls_form;");
		if ($result===false)
		   {
			 $result=0;
			 $lb_valido=false;
			 $this->io_msg->message("Fórmula Inválida ".$ls_form." CONCEPTO ".$ls_codconc);
		   }
		else
		   {
			 if ($result>=0)
			    {
				  $result=doubleval($result);
				  $lb_valido=true;
		 	    }
			 else
			    {
				  $result=0;
				  $lb_valido=false;
				  $this->io_msg->message("Fórmula Inválida ".$ls_form." CONCEPTO ".$ls_codconc);
			    }		
		   }
		return $lb_valido;
	}

    function uf_evaluar_formula($ls_formula,$ldec_monto)
	{
	  $ls_form = str_replace("IIF","\$this->iif",$ls_formula);
	  $ls_form = str_replace("\$LD_MONTO",doubleval($ldec_monto),$ls_form);
	  $result  = @eval("return $ls_form;");
	  if ($result===false)
		 {
		   $result=0; 
                //$lb_valido = false;
		 }
	  else
	     {
		  $result = doubleval($result); 
                //$lb_valido = true;
		 }
	  return $result;
	}

 function uf_validar_formula($ls_formula,$ldec_monto)
 {
   $ls_form = str_replace("IIF","\$this->iif",$ls_formula);
   $ls_form = str_replace("\$LD_MONTO",doubleval($ldec_monto),$ls_form);
   $result  = @eval("return $ls_form;");
   if ($result===false)
 	  {
	    return -1;
      }
   else
      {
		$lb_valido = true;
	  }
   return $lb_valido;
 }
}	
?>