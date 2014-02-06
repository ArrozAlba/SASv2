<?php
class sigesp_scg_c_ctas_oaf
{
	var $io_sql;
	var $io_msg;
	var $io_fun;
	
	function sigesp_scg_c_ctas_oaf()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		$io_siginc=new sigesp_include();
		$io_con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($io_con);
		$this->io_msg=new class_mensajes();
		$this->io_fun=new class_funciones();
	}
	
	function uf_cargar_cuentas($object,$li_rows)
	{
		$i=0;
		$object=array();
		$ls_sql="SELECT trim(sc_cuenta) as sc_cuenta, denominacion,trim(cta_res) as cta_res,tipo  
		           FROM scg_pc_reporte WHERE cod_report='0719'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en cargar cuentas";
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$i++;
				$ls_cuenta=$row["sc_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$ls_cuentares=$row["cta_res"];
				$ls_aum_dis=$row["tipo"];
				//Object que contiene los objetos y valores	iniciales del grid.	
				$object[$i][1]="<input type=text name=txtcuentascg".$i." value='".$ls_cuenta."' id=txtcuentascg".$i."  class=sin-borde style=text-align:center size=20  >";		
				$object[$i][2]="<input type=text name=txtdencuenta".$i." value='".$ls_denominacion."' id=txtdencuenta".$i."  class=sin-borde style=text-align:left size=60 maxlength=254>";
				$object[$i][3]="<input type=text name=txtcuentares".$i." value='".$ls_cuentares."'  id=txtcuentares".$i." class=sin-borde readonly style=text-align:center size=20 maxlength=25 onKeyPress=\"return keyRestrict(event,'1234567890');\"><a href=javascript:cat_plan(".$i.");><img src=../shared/imagebank/tools15/buscar.gif alt='Catalogo de Cuentas Contables ' width=15 height=15 border=0></a>";
				$object[$i][4]="<input type=text name=txtaum_dis".$i."    value='".$ls_aum_dis."' id=txtaum_dis".$i."     readonly class=sin-borde style=text-align:center size=15  >";		
			}
		}
		$li_rows=$i;
	}
	
	function uf_guardar_cuentas_reporte($ls_cuenta,$ls_cuenta_res,$ls_denominacion)
	{
		$ls_sql="UPDATE scg_pc_reporte 
				 SET cta_res='".$ls_cuenta_res."'
				 WHERE cod_report='0719' AND sc_cuenta='".$ls_cuenta."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			return false;
		}
		else
		{
			return true;						
		}		
	}
	


}
?>