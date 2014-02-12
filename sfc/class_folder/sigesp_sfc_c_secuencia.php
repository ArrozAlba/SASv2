<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cajero
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cajero.
 // Fecha:       - 04/12/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_secuencia
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_secuencia()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}


function uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia)
{
  $ls_serie=$this->io_funcion->uf_cerosderecha($ls_serie,5);
  if($ls_prefijo=="")
   {
     if($ls_serie!="")
	  {
	    $ls_codigo=$ls_serie.$ls_secuencia;
	  }
	  else
	  {
	    $ls_codigo=$ls_secuencia;
	  }
   }
   else
   {
     $ls_codigo=$ls_prefijo."-".$ls_serie.$ls_secuencia;
   }

  return $ls_codigo;
}

function uf_obtener_secuencia($ls_codusu,&$ls_nextval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);

	    $ls_cadena="SELECT nextval('".$ls_codusu."')";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_obtener_secuencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_nextval=$row["nextval"];
				$ls_nextval=$this->io_funcion->uf_cerosizquierda($ls_nextval,16);
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_obtener_secuencia_tipo($ls_codusu,&$ls_nextval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);

	    $ls_cadena="SELECT nextval('".$ls_codusu."')";
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_obtener_secuencia_tipo ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_nextval=$row["nextval"];

				$ls_nextval=$this->io_funcion->uf_cerosizquierda($ls_nextval,0);

			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_obtener_secuencia_cliente($ls_codusu,&$ls_nextval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);
		$ls_cadena="SELECT nextval('".$ls_codusu."')";
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_obtener_secuencia_cliente ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_nextval=$row["nextval"];
				$ls_nextval=$this->io_funcion->uf_cerosizquierda($ls_nextval,0);
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_crear_secuencia($ls_codusu,$ls_valor)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida).
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_codusu=str_replace(" ","",$ls_codusu);

		$ls_cadena= "CREATE SEQUENCE ".$ls_codusu." ".
                     "INCREMENT 1 ".
                     "MINVALUE 0 ".
                     "MAXVALUE 9223372036854775807".
                     "START ".$ls_valor." ".
                     "CACHE 1";

        //$ls_cadena = "SELECT * FROM secuencia('".$ls_codusu."')";
        //print $ls_cadena.'<br>';

		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_crear_secuencia".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
			$this->io_sql->rollback();
       	}
		else
		{
		  $this->io_sql->commit();
		}
		return $lb_valido;
	}

function uf_ver_secuencia($ls_codusu,&$ls_lastval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);

		$ls_cadena="SELECT last_value FROM ".$ls_codusu;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_ver_secuencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_lastval=$row["last_value"];
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}


function uf_ver_secuenciaexiste($ls_codusu,&$ls_lastval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);

		$ls_cadena="SELECT last_value FROM ".$ls_codusu;
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_ver_secuenciaexiste ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_lastval=$row["last_value"];
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_crear_secuencia2($ls_codusu,$ls_valor)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida).
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_codusu=str_replace(" ","",$ls_codusu);

		$ls_cadena= "CREATE SEQUENCE ".$ls_codusu." ".
                     "INCREMENT 1 ".
                     "MINVALUE 0 ".
                     "MAXVALUE 9223372036854775807".
                     "START ".$ls_valor." ".
                     "CACHE 1";

        //$ls_cadena = "SELECT * FROM secuencia('".$ls_codusu."')";
        //print $ls_cadena.'<br>';

		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->select($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{

			//print $ls_cadena;
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_crear_secuencia2 ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
			$this->io_sql->rollback();
			//print $ls_cadena;
       	}
		else
		{
		  $this->io_sql->commit();
		}
		return $lb_valido;
	}


function uf_ver_secuencia_curval($ls_codusu,&$ls_lastval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////


	    $ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);

		$ls_cadena="SELECT currval('".$ls_codusu."')+1 as fila";
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_ver_secuencia_curval ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_lastval=$row["fila"];
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_actualizar_secuencia($ls_codusu,$ls_valor)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);


	    $ls_cadena="SELECT setval('".$ls_codusu."',".$ls_valor.")";

		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			//print $ls_cadena;
			$this->io_msgc="Error en metodo uf_actualizar_secuencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_msg->message($this->io_msgc);
		}
		else
		{

		}

		return $lb_valido;

}

function uf_eliminar_secuencia($ls_codusu)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);

	    $ls_cadena="DROP SEQUENCE ".$ls_codusu;

	    //$ls_cadena="SELECT * FROM dropsecuencia('".$ls_codusu."')";
	    //print $ls_cadena.'<br>';

		$rs_datauni=$this->io_sql->execute($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_eliminar_secuencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	
			$this->io_msg->message($this->io_msgc);		
		}
		else
		{
			
		}
		return $lb_valido;
}

}
?>
