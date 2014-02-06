<?php 
class sigesp_scg_class_definicion
{
  var  $is_msg_error="";
  var  $lb_valido=false;
  
function uf_delete_planunico($as_cuenta,$as_denominacion)
{      
	require_once("../shared/class_folder/sigesp_include.php");
	$inc=new sigesp_include();
	$con=$inc->uf_conectar();
	$SQL=new class_sql($con);
        
		$ls_sql="";
		$lb_valido=true;
		
		$ls_sql = "DELETE FROM sigesp_plan_unico WHERE sc_cuenta='".$as_cuenta."' AND denominacion='".$as_denominacion."'";
		$numrows=$SQL->execute($ls_sql);
		if($numrows>0)
	    {
		    $lb_valido=true;
			$SQL->commit();
        }
	    else
	    {
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $SQL->rollback();
		   $this->ib_db_error = true ;
	    }

 return $lb_valido;

}

function uf_select_PlanUnico()
{
//require_once("sigesp_include.php");
	$inc=new sigesp_include();
	$con=$inc->uf_conectar();
	$SQL=new class_sql($con);
 
	$rs="";
	$ls_sql="";
	$lb_valido=true;
      	     	     	
     	$ls_sql="SELECT * FROM sigesp_plan_unico";
        $rs=$SQL->select($ls_sql);
		$li_num=$SQL->num_rows($rs);
		if ($li_num>0)
        {
		   $lb_valido=true;  
		}
		else
		{
		   $lb_valido=false;
		   $is_msg_error =  "Error en Select Plan Unico. ";
		}		       
	return $rs;	
}

function uf_delete_planunicore($as_cuenta,$as_denominacion)
{      
		require_once("../shared/class_folder/sigesp_include.php");
		$inc=new sigesp_include();
		$con=$inc->uf_conectar();
		$SQL=new class_sql($con);
		$ls_sql="";
		$lb_valido=true;

		$ls_sql = "DELETE FROM sigesp_plan_unico WHERE sig_cuenta='".$as_cuenta."' AND denominacion='".$as_denominacion."'";
		$SQL->begin_transaction();
		$numrows=$SQL->execute($ls_sql);
		if($numrows>0)
	    {
		    $lb_valido=true;
			$SQL->commit();
        }
	    else
	    {
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $SQL->rollback();
		   $this->ib_db_error = true ;
	    }
	 return $lb_valido;
}

}
?>