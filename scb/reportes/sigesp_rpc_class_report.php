<?php
class sigesp_rpc_class_report
{

var $ls_sql;
	
		function sigesp_rpc_class_report($conn)
		{
		  require_once("../shared/class_folder/sigesp_c_seguridad.php");
	      $this->seguridad = new sigesp_c_seguridad();		  
          require_once("../shared/class_folder/class_funciones.php");
		  $this->io_funcion = new class_funciones();
		  require_once("../shared/class_folder/class_mensajes.php");
		  $this->io_sql= new class_sql($conn);
		  $this->io_msg= new class_mensajes();		
		}



function uf_select_proveedor($as_codemp,$ai_orden,$as_tipo,$as_codprov1,$as_codprov2,$as_codigoesp) 
{
//////////////////////////////////////////////////////////////////////////////
//
//	Metodo: uf_select_proveedor
//
//	Access:  public
//
// 	  Arguments:  
//     $as_codemp:  Código de la empresa.
//      $ai_orden:  Parámetro por el cual vamos a ordenar los registros 
//                  obtenidos de la consulta (O= Ordenado por el código del
//                  proveedor; 1= Por Nombre del Proveedor).
//       $as_tipo:  Categoria de la persona (P=Proveedor; C= Contratista) .
//   $as_codprov1:  Código del proveedor a partir del cual se realizara
//                  la búsqueda.
//   $as_codprov2:  Código del proveedor hasta el cual se realizara
//                  la búsqueda.
//  $as_codigoesp:  Código de la Especialida por el cual seran filtrados 
//                  los registros.
//
//	Returns:		
//  $rs_proveedor=  Resulset que contiene el resultado de la consulta en caso de 
//                  conseguir registros y ejecutar la sentencia sql sin errores.           
//
//	Description:  Función que se encarga de realizar la busqueda de proveedores 
//                y/o contratistas dentro de un rango de códigos proveedores,
//                para una especilidad (En caso de que se especifique la misma) y
//                y ordenado por el código o el nombre del proveedor o contratista.  
//
//////////////////////////////////////////////////////////////////////////////
  
  
  	 if ($ai_orden=="0")
	    {
		  $ls_orden="Cod_Pro";
		}
	 else
	    {
		  $ls_orden="NomPro";
		}
     if ($as_tipo=="P")
	    {
		  $ls_categoria="EstPro";
		}
	 else
	    {
		  $ls_categoria="EstCon";
		}		
    if (($as_codprov1=="") || ($as_codprov2==""))//En caso de que no se establezca el rango de búsqueda se asignan por defecto.
	   {
	        $as_codprov1="0000000000";
			$as_codprov2="9999999999";			
	   }
  
  $ls_sql=" SELECT * FROM rpc_proveedor ".
          " WHERE Cod_Pro BETWEEN '".$as_codprov1."' AND '".$as_codprov2."' AND".
	      " CodEsp LIKE '%".$as_codigoesp."%' AND $ls_categoria =1 AND CodEmp='".$as_codemp."'". //(EstPro=1 -> Proveedor;EstCon=1 ->Contratista)
	      " ORDER BY $ls_orden ASC";
 
   $rs_proveedor=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_proveedor);	   
   if ($li_numrows>0)
      {
	    $lb_valido=true;
      }
   else
      {
  	    $lb_valido=false;
        if ($this->io_sql->message!="")
           {                              
             $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
           }           
	  }	
   if ($lb_valido)
      {
        return $rs_proveedor;         
      }
}
}//Fin de la Clase...
?> 