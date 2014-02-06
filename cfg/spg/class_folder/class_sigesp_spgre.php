<?php 
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_mensajes.php");
 class class_sigesp_spgre
 {
	

	var $SQL;
	var $msg;
	var $dat_emp;//Datos empresa.

	function class_sigesp_spgre()
	{	
		$this->msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		require_once("../../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->SQL=new class_sql($this->con);
	} 
	
	function uf_insert_plan_unico_cuenta($as_cuenta,$as_denominacion)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_insert_plan_unico_cuenta
	//	Access:    public
	//	Arguments:
	//  as_cuenta         // inserta la cuenta 
	//  as_denominacion   // denominacion de la cuenta
	//	Returns:		li_rtn-----> 0-no hizo nada,1-inserto,2-actualizo
	//	Description:  Este método inserta la cuenta y la denominacion en la tabla 
	//               plan unico de cuenta
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="";
	$li_exec=-1;$li_rtn=0;
	if($this->uf_select_plan_unico($as_cuenta))
	{
		
		$ls_sql= "UPDATE SIGESP_Plan_unico_re SET denominacion='".$as_denominacion+"'".
				" WHERE SIG_cuenta='".trim($as_cuenta)."'" ;
		
			
		$li_exec=$SQL->execute($ls_sql);
		$this->is_msg_error = "";
		if($li_exec<=0)
		{
			$this->is_msg_error = "Error en método uf_insert_plan_unico_cuenta ";
			$li_rtn=0;
		}
		else
		{
			$li_rtn=2;
		}
						
	}
	else
	{
		
		$ls_sql= "INSERT INTO SIGESP_Plan_unico_re (SIG_cuenta,denominacion)".
			   " VALUES('".trim($as_cuenta)."' , '".trim($as_denominacion)."')" ;
		
			$li_exec=$this->SQL->execute($ls_sql);
			$this->is_msg_error = "";
			if($li_exec<=0)
			{
				$this->is_msg_error = "Error en método uf_insert_plan_unico_cuenta ";
				print $this->SQL->message;
				$li_rtn=0;
			}
			else
			{
				$li_rtn=1;
			}
	}
	
	return $li_rtn;
		
	}	//Fin uf_insert_plan_unico_cuenta
	
	
	function  uf_select_plan_unico_cuenta($as_sc_cuenta,$as_denominacion)
	{//	Function:  uf_select_plan_unico_cuenta
	 //	Access:  public
	 //	Arguments:
	 // as_sc_cuenta     // cuenta contable
	 //	Returns:	  lb_valido -> variable boolean
	 //	Description:  Verifica si existe o no en la tabla de SIGESP_Plan_Unico
	
	 $ls_sc_cuenta="";$ls_cadena="";
	 $lb_existe = false;
	
	 $ib_db_error = false;
	
	 $ls_cadena = "SELECT SIG_cuenta,denominacion".
				 " FROM SIGESP_Plan_unico_re ".
				 " WHERE SIG_cuenta='". $as_sc_cuenta ."'"; 
	
		$rs_scg = $this->SQL->select($ls_cadena);
		
							
		if ($row=$this->SQL->fetch_row($rs_scg))
		{
		  $lb_existe=true;
		  $is_den_plan_cta=$row["denominacion"];
		  $as_denominacion=$row["denominacion"];
		  
		}
		else
		{
		  $lb_existe=false;
		}
		 
	 return $lb_existe;
	
	} // Fin de uf_select_plan_unico_cuenta
	
	
	
	function uf_cargar_plan_unico_cuenta_general()
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_cargar_plan_unico_cuenta_general
	//	Access:  public
	//	Description:  Este método accesa la información del código y denominación de las 
	//      cuentas del plan único y procede a insertarla en la tabla SIGESP_Plan_Unico
	/////////////////////////////////////////////////////////////////////////////
	
	$ls_Name_File="";$ls_linea="";$ls_cadena_linea="";$ls_cuenta="";$ls_denominacion="";$ls_denominacion_plan="";
	$li_NumFile=0;$li_Read_Result=0;$li_valid=0; 
	$lb_valido=true;
	
	
	
			$archivo = file("SIGESP_PURE.txt");
    		$lineas = count($archivo);
			for($i=0; $i < $lineas ; $i++)
			{
			// Reemplazar por el procesamiento
				  $ls_cadena_linea = trim($archivo[$i]);
				  
				  $ls_cuenta       = substr(trim($ls_cadena_linea),0,9 );
				  $ls_denominacion = substr(trim($ls_cadena_linea),9,99 );
				  print $ls_cuenta;
			if(!$this->uf_select_plan_unico_cuenta( $ls_cuenta,$ls_denominacion ))
			{
				$li_valid = $this->uf_insert_plan_unico_cuenta( $ls_cuenta ,$ls_denominacion );
			}
			
			}
		return ;
	}
 
 
	function uf_select_plan_unico($as_cuenta)
	{
		$ls_sql="";
		$lb_valido=false;
		
		$ls_sql="SELECT * FROM SIGESP_Plan_Unico_re".
			   " WHERE SIG_cuenta='".$as_cuenta."'";	
			   
	
			$rs_plan=$this->SQL->select($ls_sql);
			if($row=$this->SQL->fetch_row($rs_plan))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}	
	
		return $lb_valido;
		
	}

 } 
?>