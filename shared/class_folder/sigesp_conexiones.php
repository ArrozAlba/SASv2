<?php
class conexiones {
	
	function conexiones(){
			
			global $ruta;
			if($ruta==''){$ruta=".././/";}
			require_once($ruta."shared/class_folder/class_mensajes.php");
			$this->obj_msj=new class_mensajes();
	}
						
	function codificacion_navegador(){
				
				global $navegador;
				$navegador = strpos($_SERVER['HTTP_USER_AGENT'],'MSIE');
				
				if($navegador === false){
					$navegador = 'FIREFOX';		
					header('Content-Type: text/html; charset=utf-8');
				}
				else{
					
					$navegador = 'INTERNET EXPLORER';				
					header('Content-Type: text/html; charset=ISO-8859-1');
				}
				
	}

	function decodificar_post(){
	
			
			foreach ($_POST as $k=>$v) {
			  $_POST[$k] = utf8_decode($v);
			} 

	
	
	}
							
	function nombre_form(){
					
		$formulario = split('/',$_SERVER['PHP_SELF']);
		$cantidad = count($formulario);
		$formulario = $formulario[$cantidad - 1];
		return $formulario;
	}
							
							
	function conexion($query_rs,$opciones,$informacion = '',$modo='select',$campo_id = '',$base_de_datos = '',$gestor = '',$usuario=''){
			
			global $obj_sql,$msj_error_conex,$ruta;
			
			if($_SESSION["ls_gestor"] == 'POSTGRES' and $modo=='insert'){$query_rs = $query_rs.'; SELECT lastval() AS valor_id; ';}
			
			$in=new sigesp_include();
			
			if($gestor == ''){$gestor = $_SESSION["ls_gestor"];}
			if($usuario==''){$usuario = $_SESSION["ls_login"];}
			
			if($base_de_datos==''){$con=$in->uf_conectar();}
			else{$con=$in->uf_conectar_otra_bd($_SESSION["ls_hostname"],$usuario,$_SESSION["ls_password"],$base_de_datos,$gestor);}
			
			$obj_sql=new class_sql($con);			
			$rs_data = $obj_sql->select($query_rs);			
			
			if($rs_data === false){
						
						if($tipo_mensaje=='clase_msj'){
								$msj_error = $informacion.'<br><br><b>ERROR:</b> <br>'.$obj_sql->message;
								$this->obj_msj->message($msj_error,$mensaje_sigesp=2,$ruta);
						}
						else{
						
								$msj_error_conex = $informacion.'<br><br><b>ERROR:</b> <br>'.$obj_sql->message;
								echo '<input type="hidden" name="txt_msj_error" id="txt_msj_error" value="'.$msj_error_conex.'">';					
								//echo $msj_error_conex;
						
						}
						
			}
			$cantidad = $obj_sql->num_rows($rs_data);
			$row=$obj_sql->fetch_row($rs_data);
				
			switch($modo){
  
					  case "update":					  				
									
							break;
							
					  case "delete":
							
							break;
							
					  case "insert":
								if($_SESSION["ls_gestor"] == 'MYSQLT' or $_SESSION["ls_gestor"] == 'MYSQLT'){ return mysql_insert_id();}
								if($_SESSION["ls_gestor"] == 'POSTGRES'){$insert_id = $row['valor_id']; return $insert_id;}
							break;
							
					   case "select":
								if($opciones == 'arreglo'){return array('rs'=>$rs_data, 'fila'=>$row, 'cantidad'=>$cantidad);}
								elseif($opciones == 'fila'){return $row;}		
							break;
								
			}	
								
			$obj_sql->free_result($rs_data);

	}
	
	function formatea_fecha_bd($var_fecha){// CONVIERTE LA FECHA A MYSQL o POSTGRES
			//para los días que trae un solo dígito se le agrega cero a la izquierda e igual para el mes.
			ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $var_fecha, $lugarx);
			$dia = strlen($lugarx[1]); if($dia==1){$dia_x.="0".$lugarx[1];}else{$dia_x=$lugarx[1];}
			$mes = strlen($lugarx[2]); if($mes==1){$mes_x.="0".$lugarx[2];}else{$mes_x=$lugarx[2];}
			$year_x = $lugarx[3];
			$fechaMySql=$year_x."-".$mes_x."-".$dia_x;
			if(!$fechaMySql){$fechaMySql="0000/00/00";}
			return $fechaMySql;
		} 
	
	function formatea_fecha_normal($fechax){// CONVIERTE LA FECHA A MYSQL A NORMAL.
		ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fechax, $lugarx);
		$dia = strlen($lugarx[3]); if($dia==1){$dia_x.="0".$lugarx[3];}else{$dia_x=$lugarx[3];}
		$mes = strlen($lugarx[2]); if($mes==1){$mes_x.="0".$lugarx[2];}else{$mes_x=$lugarx[2];}
		$year_x = $lugarx[1];
		$fec_normal=$dia_x."/".$mes_x."/".$year_x;
		
		return $fec_normal;
	}
	
	
	function extrae_hora($hora_x){//EXTRAE LA HORA
		$hora = strtotime($hora_x);
		$var_hora = date('h',$hora);
		return $var_hora;
	}
	function extrae_minutos($hora_x){//EXTRAE LOS MINUTOS
		$hora = strtotime($hora_x);
		$var_minuto = date('i',$hora);
		return $var_minuto;
	}
	
	function conforma_hora($hora_x, $minutos_x){//CONFORMA LA HORA
		$hora = $hora_x.':'.$minutos_x.':00';
		return $hora;
	}
	
	
	
}//fin de la clase conexiones


?>