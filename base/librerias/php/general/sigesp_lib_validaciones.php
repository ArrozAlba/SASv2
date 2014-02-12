<?php
/***********************************************************************************
* @librerÌa que contiene las validaciones generales
* @fecha de creaciÛn: 14/07/2008 
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/

function validaciones($var,$long,$tipo)
{
	$arregloval = explode("|",$tipo);
	for ($i=0;$i<count($arregloval);$i++)
	{
		switch ($arregloval[$i])
		{
			//validar dato vacio
			case 'novacio':
				$correcto = false;
				if ($var!='')
				{					
					$correcto = true;
				}
				return $correcto;
			break;
			
			//validar dato numÈrico
			case 'numero':
				$correcto = false;
				$longitud = strlen($var);
				if ($longitud <= $long)
				{
					if (ereg('/^\d+$/', $var))					
					{
						//echo $var.' no es un dato numerico';
					}
					else
					{
						$correcto = true;
					}
				}				
				return $correcto;
			break;			
						
			//validar datos de tipo entero
			case 'entero':
				$correcto = false;
				if (filter_var($var,FILTER_VALIDATE_INT))
				{
					$correcto = true;			
				}				
				return $correcto;
			break;
			
			//validar datos de tipo float	
			case 'float':
				$correcto = false;
				if (filter_var($var,FILTER_VALIDATE_FLOAT))
				{
					$correcto = true;
				}				
				return $correcto;		
			break;
			
			//validar datos de tipo alfanumerico:letras,n˙meros,comas, espacios, guiones
			case 'alfanumerico':
				$correcto = false;
				$longitud = strlen($var);
				if ($longitud <= $long)
				{
					//if (!eregi('^[a-z Ò·ÈÌÛ˙ 0-9]*$',$var))
				//	if (preg_match('/^[a-zA-Z0-9\s.\-]{2,60}+$/', $var))
				 	if (preg_match('/^[a-zA-Z0-9Ò·ÈÌÛ˙—¡…Õ”⁄()_@\s.\-]+$/', $var))				
					{
						$correcto = true;
					}
					else
					{
						
					}					
				}				
				return $correcto;		
			break;
			
			case 'vacioalfanumerico':
				$correcto = false;
				$longitud = strlen($var);
				if (($longitud <= $long) && ($longitud>0))
				{
				//	if (preg_match('/^[a-zA-Z0-9\s.\-]{2,60}+$/', $var))
				 	if (preg_match('/^[a-zA-Z0-9Ò·ÈÌÛ˙—¡…Õ”⁄\s.\-]+$/', $var))	
					{
						$correcto = true;
					}				
				
				}
				elseif ($longitud==0)
				{
				 	$correcto = true;
				}
				return $correcto;
			break;
			
			//validar que no tenga caracteres especiales
			case 'vaciocaracteres':
				$correcto = false;
				$longitud = strlen($var);
				if (($longitud <= $long) && ($longitud>0))
				{
					if (filter_var($var,FILTER_SANITIZE_SPECIAL_CHARS))
					{
						$correcto = true;
					}					
				}
				elseif ($longitud==0)
				{
					 $correcto = true;
				}				
				return $correcto;		
			break;			
			
			case 'caracteres':
				$correcto = false;
				if (filter_var($var,FILTER_SANITIZE_SPECIAL_CHARS))
				{
					$correcto = true;
				}
				return $correcto;		
			break;
			
			//validar datos de email		
			case 'email':
				$correcto = false;
				if (filter_var($var, FILTER_VALIDATE_EMAIL))
				{   		
					$correcto = true;
				}
				return $correcto;
			break;
			
			case 'vacioemail':
				$correcto = false;
				$longitud = strlen($var);
				if (($longitud <= $long) && ($longitud>0))
				{
					if (filter_var($var, FILTER_VALIDATE_EMAIL))
					{   		
						$correcto = true;
					}					
				}	
				elseif ($longitud==0)
				{
				 	$correcto = true;
				}
				return $correcto;
			break;
			
			//validar datos de telÈfono con formato 5555-5555555
			case 'telefonoFormato':
				$correcto = false;
				if (preg_match('/^\d{4}-\d{7}$/', $var))
				{
					$correcto = true;
				}			
				return $correcto;		
			break;
			
			case 'vaciotelefono':
				$correcto = false;
				$longitud = strlen($var);
				if (($longitud <= $long) && ($longitud>0))
				{
					if (preg_match('/^\d{4}-\d{7}$/', $var))
					{
						$correcto = true;
					}					
				}	
				elseif ($longitud==0)
				{
				 	$correcto = true;
				}
				return $correcto;		
			break;
			
			case 'telefono':
				$correcto = false;
				$longitud = strlen($var);
				if (($longitud <= $long) && ($longitud>0))
				{
				 	if (preg_match('/^[0-9-\s.\-]+$/', $var))	
					{
						$correcto = true;
					}				
				
				}
				elseif ($longitud==0)
				{
				 	$correcto = true;
				}
				return $correcto;
			break;
			
			//validar datos de nombre y/o apellido
			case 'nombre':				
				$correcto = false;
				$longitud = strlen($var);				
				//if (ereg('^[a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò—]{2,60}([ ]{1})?([a-zA-Z·ÈÌÛ˙¡…Õ”⁄Ò—]{2,60})?$',$var))
				if (($longitud <= $long) && ($longitud>0))
				{
					if (!eregi('^[a-z Ò·ÈÌÛ˙.]*$', $var))
					{
						//echo $var.' contiene caracteres no validos';
					}
					else
					{
						$correcto = true;
					}
				}	
				/*elseif ($longitud>$long)
				{
					echo $var. ' no tiene la longitud correcta';
				}	*/			
				return $correcto;					
			break;			
			
			//validar datos de nombre y/o apellido con longitud exacta
			case 'longexacta':
				$correcto = false;
				$longitud = strlen($var);
				if ($longitud==long)
				{
					if (!eregi('^[a-z Ò·ÈÌÛ˙]*$', $var))
					{
						//echo $var.' contiene caracteres no validos';
					}
					else
					{
						$correcto = true;
					}
				}	
				/*else 
				{
					echo $var. ' no tiene la longitud correcta';
				}*/
				return $correcto;					
			break;
					
			//validar dato login de usuario de 4 hasta 30 caracteres de longitud, alfanumericos y guiones bajos.
			case 'login':
				$correcto = false;
				if (preg_match('/^[a-zd_]{4,20}$/i', $var))
				{
					$correcto = true;
				}
				/*else
				{
					echo $var.' no es un usuario correcto';
				}*/
				return $correcto;		
			break;
			
			//validar contraseÒa de usuario
			case 'contraseÒa':
				$correcto = false;	
				if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,8}$/', $var))
				{
					$correcto = true;			
				}
				/*else
				{
					echo $var.' no es una contraseÒa segura';
				}*/
				return $correcto;			
			break;
			
			case 'cedula':
		//	'/^\d{8}$/'     
			/*	$correcto = false;	
				if (preg_match('^[a-zA-Z]\w{3,14}$', $var))
				{
					$correcto = true;			
				}
				else
				{
					echo $var.' no es una contraseÒa segura';
				}
				return $correcto;*/			
			break;	
			
			
			default:
				echo 'No se estan ejecutando validaciones';		
			break;
		}
	}	
	return $correcto;					
}

?>