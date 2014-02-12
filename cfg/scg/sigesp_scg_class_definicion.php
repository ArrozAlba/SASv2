<?php 
class sigesp_scg_class_definicion
{
  var  $is_msg_error="";
  var  $lb_valido=false;
  
function uf_delete_planunico($as_cuenta,$as_denominacion)
{      
	$inc=new sigesp_include();
	$con=$inc->uf_conectar();
	$SQL=new class_sql($con);
        
		$ls_sql="";
		$lb_valido=true;
		
		$ls_sql = "DELETE FROM sigesp_plan_unico WHERE sc_cuenta='".$as_cuenta."'";
		$numrows=$SQL->execute($ls_sql);
		if($numrows===false)
	    {
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $SQL->rollback();
		   $this->ib_db_error = true ;
        }
	    else
	    {
		    $lb_valido=true;
			$SQL->commit();
	    }

 return $lb_valido;
}

function uf_select_PlanUnico()
{
	$inc=new sigesp_include();
	$con=$inc->uf_conectar();
	$SQL=new class_sql($con);
 
	$rs="";
	$ls_sql="";
	$lb_valido=true;
      	     	     	
     	$ls_sql="SELECT * FROM sigesp_plan_unico";
        $rs=$SQL->select($ls_sql);
		$li_num=$SQL->num_rows($rs);
		if ($li_num===false)
        {
 		   $lb_valido=false;
		   $is_msg_error =  "Error en Select Plan Unico. ";
		}
		else
		{
		   $lb_valido=true;  
		}		       
	return $rs;	
}

function uf_delete_planunicore($as_cuenta,$as_denominacion)
{      

		$inc=new sigesp_include();
		$con=$inc->uf_conectar();
		$SQL=new class_sql($con);
		$ls_sql="";
		$lb_valido=true;

		$ls_sql = "DELETE FROM sigesp_plan_unico_re WHERE sig_cuenta='".$as_cuenta."' AND denominacion='".$as_denominacion."'";
		$SQL->begin_transaction();
		$numrows=$SQL->execute($ls_sql);
		if($numrows===false)
		{
		   $this->is_msg_error="Error al eliminar";
		   $lb_valido = false;
		   $SQL->rollback();
		   $this->ib_db_error = true ;
		}
		else
		{
		    $lb_valido=true;
			$SQL->commit();
        }
	    
	 return $lb_valido;
}

function uf_load_relacion($as_cuenta)
{
  $io_include = new sigesp_include();
  $ls_conect  = $io_include->uf_conectar();
  $io_sql     = new class_sql($ls_conect);

  $lb_valido = false;
  $ls_sql    = "SELECT sc_cuenta FROM sigesp_plan_unico WHERE trim(sc_cuenta) like '".$as_cuenta."%'";
  $rs_data   = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $is_msg_error =  "Error en SELECT delete Plan Unico"; 
	 }
  else
     {
	   if ($row=$io_sql->fetch_row($rs_data))
	      {
		    $lb_valido = true;
		  }
	 }
  return $lb_valido;
}
}
?>