<?php
require_once("../shared/class_folder/class_sql.php");  
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");

class class_generar_id_process_soc
{
   var $dts_empresa; 
   var $is_codemp;
   var $io_fecha;
   var $io_function;
   var $io_siginc;
   var $io_connect;
   var $io_sql;
   var $io_msg;
   function class_generar_id_process_soc()
   {
  	  $this->dts_empresa=$_SESSION["la_empresa"];
	  $this->is_codemp=$this->dts_empresa["codemp"];		
	  $this->io_function=new class_funciones() ;
	  $this->io_siginc=new sigesp_include();
	  $this->io_connect=$this->io_siginc->uf_conectar();
	  $this->io_sql=new class_sql($this->io_connect);		
	  $this->io_msg=new class_mensajes();		
   } // end constructor

    function uf_check_id($as_tabla,$as_columna,$as_colest,$as_tipocompra,&$as_numero)   
	{ ////////////////////////////////////////////////////////////////////////////////////////////////////               
	  //	  Function: uf_sep_check_id
	  //	    Access: public
	  //    Argument: $as_tabla->nombre tabla , $as_columna->nombre columna , $as_numero->numero del documento
	  //              asociado a una SEP o CXP o SOC
	  //     Returns: retorna un booelano que indica si el número está en uso y retorna una variable por 
	  //              valor con el nuevo número si es necesario
	  // Description: Esté método se encarga de validar que un número del docuemnto no sea utilizado por otra
	  //              instancia, y si lo está siendo este debe generar uno nuevo
	  ////////////////////////////////////////////////////////////////////////////////////////////////////               
      $lb_change = false; // si cambio el número de documento
      if ( !$this->uf_verificar_id_liberado($as_tabla,$as_columna,$as_colest,$as_tipocompra,$as_numero))
	  {
	     $as_numero = $this->uf_generar_id_process_soc($as_tabla,$as_columna,$as_colest,$as_tipocompra);
		 $this->io_msg->message("Se le Asignó un nuevo número de documento la cual es :".$as_numero);			
		 $lb_change = false;
	  }
	  return  $lb_change;
	} // end function 
	
    function uf_verificar_id_liberado($as_tabla,$as_columna,$as_colest,$as_tipocompra,&$as_numero)   
	{ ////////////////////////////////////////////////////////////////////////////////////////////////////               
	  //    Function: uf_verificar_id_liberado
	  //	  Access: public
	  //    Argument: $as_codemp->codigo empresa  $as_numero->numero de la solicitud
	  //     Returns: retorna un booelano (true=si esta liberado y false=todo lo contrario )
	  // Description: Esté método se encarga de validar que un número de la sep se encuantra en la base de dato
	  ////////////////////////////////////////////////////////////////////////////////////////////////////               
      $lb_existe = true;
	  if( ($as_tipocompra=="S") || ($as_tipocompra=="B") )
      {
		  $ls_sql  = "SELECT ".$as_columna." FROM ".$as_tabla." 
					  WHERE codemp='".$this->is_codemp."' AND ".$as_columna."='".$as_numero."'  AND ".$as_colest."='".$as_tipocompra."'";
      }
      else
      {
           $ls_sql = "SELECT ".$as_columna." FROM ".$as_tabla." WHERE codemp='".$this->is_codemp."' AND ".$as_columna."='".$as_numero."'";					 
      }
	  $rs_data   = $this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { 
         $this->io_msg->message("Error en consulta uf_verificar_id_liberado ".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		 return false;
	  }
	  else  {  if($row=$this->io_sql->fetch_row($rs_data))  {  $lb_existe = false;  }  }
  	  $this->io_sql->free_result($rs_data);
	  return $lb_existe;
	} // end function 

	function uf_generar_id_process_soc($as_tabla,$as_columna,$as_colest,$as_tipocompra)
	{ ////////////////////////////////////////////////////////////////////////////////////////////////////               
	  //    Function: uf_generar_id_process_soc
	  //	  Access: public
	  //    Argument: $as_tabla->nombre de la tabla , $as_columna->nombre columna
	  //     Returns: retorna el codigo
	  // Description: Esté método global genera un número concecutivo asociada a una tabla y columna específica,
	  //              tambien verifica si esta liberado o no para generar su número posterior y asi concecutivamente
	  //              hasta que encuantre el número liberado.
	  ////////////////////////////////////////////////////////////////////////////////////////////////////               
	  $lb_valido=true;
      if( ($as_tipocompra=="S") || ($as_tipocompra=="B") )
      {
			  $ls_sql = " SELECT ".$as_columna." FROM ".$as_tabla.
						" WHERE codemp='".$this->is_codemp."' AND ".$as_colest."='".$as_tipocompra."' ORDER BY ".$as_columna." DESC limit 1";
      }
      else
      {
           $ls_sql = " SELECT ".$as_columna." FROM ".$as_tabla.
					 " WHERE codemp='".$this->is_codemp."' ORDER BY ".$as_columna." DESC limit 1";
      } 
	  $rs_data=$this->io_sql->select($ls_sql);
	  if ($row=$this->io_sql->fetch_row($rs_data))
      { 
		  $ls_id = $row[$as_columna];	
		  if($ls_id<>'000000000000000')  
		  {
					  while($lb_valido)
					  {
						 settype($ls_id,'int');
						 $ls_id = $ls_id + 1;
						 settype($ls_id,'string');
						 $ls_id = $this->io_function->uf_cerosizquierda($ls_id,15);
						 $lb_valido = (!$this->uf_verificar_id_liberado($as_tabla,$as_columna,$as_colest,$as_tipocompra,$ls_id));
					  }
		  }
		  else
		  {		  
					  if($as_tipocompra=="B") 
					  {
					 
						  $ls_sql = " SELECT numordcom FROM sigesp_empresa
									  WHERE codemp='".$this->is_codemp."' ";
			
						  $rs_orddat=$this->io_sql->select($ls_sql);
						  if ($row=$this->io_sql->fetch_row($rs_orddat))
						  { 
							  $ls_id = $row["numordcom"];	
							  if($ls_id=='0')  
							  {
							         settype($ls_id,'int');
									 $ls_id = $ls_id + 1;
									 settype($ls_id,'string');
							  }
						  }	  
					   }
					
					   if($as_tipocompra=="S") 
					   {
					 
						  $ls_sql = " SELECT numordser FROM sigesp_empresa
									  WHERE codemp='".$this->is_codemp."' ";
			
						  $rs_orddat=$this->io_sql->select($ls_sql);
						  if ($row=$this->io_sql->fetch_row($rs_orddat))
						  { 
							  $ls_id = $row["numordser"];	 
							  if($ls_id=='0')  
							  {
							         settype($ls_id,'int');
									 $ls_id = $ls_id + 1;
									 settype($ls_id,'string');
							  } 
						  }	  
					   }
				   
					   if( ($as_tipocompra=="-")  ||  ($as_tipocompra=="")  )
					   {
					 
						  $ls_sql = " SELECT numordcom FROM sigesp_empresa
									  WHERE codemp='".$this->is_codemp."' ";
			
						  $rs_orddat=$this->io_sql->select($ls_sql);
						  if ($row=$this->io_sql->fetch_row($rs_orddat))
						  { 
							  $ls_id = $row["numordcom"];
							  if($ls_id=='0')  
							  {
							         settype($ls_id,'int');
									 $ls_id = $ls_id + 1;
									 settype($ls_id,'string');
							  }
						  }	  
					   }
					   
					   $ls_id = $this->io_function->uf_cerosizquierda($ls_id,15);					  
		  }
 	  }
	  else
	  {
        	  if($as_tipocompra=="B") 
              {
	         
				  $ls_sql = " SELECT numordcom FROM sigesp_empresa
							  WHERE codemp='".$this->is_codemp."' ";
	
				  $rs_orddat=$this->io_sql->select($ls_sql);
				  if ($row=$this->io_sql->fetch_row($rs_orddat))
				  { 
					  $ls_id = $row["numordcom"];	
					  if($ls_id=='0')  
					  {
							 settype($ls_id,'int');
							 $ls_id = $ls_id + 1;
							 settype($ls_id,'string');
					  }  
				  }	  
               }
				
			   if($as_tipocompra=="S") 
               {
	         
				  $ls_sql = " SELECT numordser FROM sigesp_empresa
							  WHERE codemp='".$this->is_codemp."' ";
	
				  $rs_orddat=$this->io_sql->select($ls_sql);
				  if ($row=$this->io_sql->fetch_row($rs_orddat))
				  { 
					  $ls_id = $row["numordser"];	
					  if($ls_id=='0')  
					  {
							 settype($ls_id,'int');
							 $ls_id = $ls_id + 1;
							 settype($ls_id,'string');
					  }    
				  }	  
               }
			   
			   if( ($as_tipocompra=="-")  ||  ($as_tipocompra=="")  )
               {
	         
				  $ls_sql = " SELECT numordcom FROM sigesp_empresa
							  WHERE codemp='".$this->is_codemp."' ";
	
				  $rs_orddat=$this->io_sql->select($ls_sql);
				  if ($row=$this->io_sql->fetch_row($rs_orddat))
				  { 
				      $ls_id = $row["numordcom"];
					  if($ls_id=='0')  
					  {
							 settype($ls_id,'int');
							 $ls_id = $ls_id + 1;
							 settype($ls_id,'string');
					  }  
				  }	  
               }
			   
			   $ls_id = $this->io_function->uf_cerosizquierda($ls_id,15);				  
	  }
  	  $this->io_sql->free_result($rs_data);
      return $ls_id;
   } // end function()
   
} // end class
?>