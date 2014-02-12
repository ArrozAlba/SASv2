<?php
class sigesp_soc_c_detalle_cotizaciones
{
	function sigesp_soc_c_detalle_cotizaciones()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function:  sigesp_soc_c_detalle_cotizaciones
		//	Description:  Constructor de la Clase
		//////////////////////////////////////////////////////////////////////////////
		global $ls_empresa;
		global $io_include;
		global $io_conexion;	
		global $io_sql;
		global $io_mensajes;
		global  $io_funciones;
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();		
		$io_sql=new class_sql($io_conexion);			
		$io_mensajes=new class_mensajes();
		$io_funciones=new class_funciones();	
		$ls_empresa=$_SESSION["la_empresa"]["codemp"];
	}
	
	function uf_select_cotizacion($as_numcot,$as_codpro,$as_numsolcot,&$la_cotizacion,&$la_dt_cotizacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_dt_cotizacion
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_numsol--->Numero de Solicitud de Cotizacion
		//		return	:		Arreglo con datos de la cotizacion, arreglo con los bienes/servicios 
		//	  Description: Metodo que  imprime la informacion de una cotizacion en particular
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 28/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_empresa;//,$io_sql,$io_mensajes,$io_funciones;
		global $io_include;
		global $io_conexion;	
		global $io_sql;
		global $io_mensajes;
		global  $io_funciones;
		$la_cotizacion=array();
		$la_dt_cotizacion=array();
		$lb_valido=false;				
		$ls_sql= "SELECT c.feccot, c.obscot, c.monsubtot, c.monimpcot, c.montotcot, c.diaentcom, c.forpagcom, c.poriva, s.tipsolcot 
					FROM soc_cotizacion c, soc_sol_cotizacion s  
					WHERE c.codemp='$ls_empresa' AND c.codemp=s.codemp AND c.numsolcot='$as_numsolcot' AND c.numcot='$as_numcot' 
					AND c.cod_pro='$as_codpro' AND c.numsolcot=s.numsolcot";		
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$la_cotizacion=$row;									
				$lb_valido=true;			
			}			
		}
		if($lb_valido)
		{
			$this->uf_select_items($as_numcot,$as_codpro,$row["tipsolcot"],$la_dt_cotizacion);
		}	
	}
	
	function uf_select_items($as_numcot,$as_codpro,$as_tipsolcot,&$aa_items)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_tipsolcot--->Si la cotizacion es de bienes o servicios
		//		return	:		arreglo con los bienes/servicios 
		//	  Description: Metodo que  devuelve los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_empresa;//,$io_sql,$io_mensajes,$io_funciones;
		global $io_include;
		global $io_conexion;	
		global $io_sql;
		global $io_mensajes;
		global  $io_funciones;
		$aa_items=array();
		$lb_valido=false;				
		if($as_tipsolcot=='B')//Si la solicitud es de Bienes
		{
			$ls_sql= "SELECT d.codart as codigo, d.canart as cantidad, d.preuniart as preciouni, d.moniva as iva, d.monsubart as subtotal, d.montotart
						as total, a.denart as denominacion
						FROM soc_dtcot_bienes d, siv_articulo a
						WHERE d.codemp='$ls_empresa' AND d.numcot='$as_numcot' AND  d.cod_pro='$as_codpro'
						AND d.codemp=a.codemp AND  a.codart=d.codart  
						ORDER BY d.orden";					
		}
		elseif($as_tipsolcot=='S') //Si la solicitud es de Servicios
		{
			
			$ls_sql= "SELECT d.codser as codigo, d.canser as cantidad, d.monuniser as preciouni, d.moniva as iva, d.monsubser as subtotal,
						d.montotser as total, a.denser as denominacion
						FROM soc_dtcot_servicio d, soc_servicios a WHERE d.codemp='0001' AND d.numcot='$as_numcot'
						AND d.cod_pro='$as_codpro' AND d.codemp=a.codemp AND a.codser=d.codser ORDER BY d.orden";		
		}
		//print $ls_sql;
			$rs_data=$io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				$li_i=0;
				while($row=$io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
				{
					$li_i++;
					$aa_items[$li_i][1]=$row["codigo"];
					$aa_items[$li_i][2]="<div align=left>".$row["denominacion"]."</div>";
					$aa_items[$li_i][3]="<div align=right>".number_format($row["cantidad"],2,",",".")."</div>";		
					$aa_items[$li_i][4]="<div align=right>".number_format($row["preciouni"],2,",",".")."</div>";	
					$aa_items[$li_i][5]="<div align=right>".number_format($row["subtotal"],2,",",".")."</div>";
					$aa_items[$li_i][6]="<div align=right>".number_format($row["iva"],2,",",".")."</div>";
					$aa_items[$li_i][7]="<div align=right>".number_format($row["total"],2,",",".")."</div>";		
				}																
			}		
	}  //Fin funcion uf_select_items	  
}
?>