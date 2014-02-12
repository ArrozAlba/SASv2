<?Php
//-------------------------------------------------------------------------------------------------------------//
		/////////////////////////////////////////////////////////////////////////////////
		//       Function: sigesp_sob_r_plantillapdf                    			   // 													   //
		//    Description: Llamadas a funciones de la clase sigesp_sob_r_plantillapdf  //
		//	   Creado Por: Ing. Laura Cabre											   //		
		// Fecha Creación: 02/06/2006 												   //		
		/////////////////////////////////////////////////////////////////////////////////
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	require_once("sigesp_sob_c_plantillapdf.php");
	//Se toman de la sesion los arreglos y demas variables que contienen la data a ser mostrada en los reportes
	$la_data=$_SESSION["data"];//Arreglo con los datos a ser mostrados 
	$la_titulosdetalle=$_SESSION["titulosdetalle"];	//Arreglo con los titulos de las columnas a ser mostrados en el detalle
	$ls_tituloencabezado=$_SESSION["tituloencabezado"];//Titulo del Reporte
	$ls_fechadesde=$_SESSION["fechadesde"];//Fecha de inicio del periodo a reportar 
	$ls_fechahasta=$_SESSION["fechahasta"];	//Fecha de fin del periodo a reportar
	$ls_orientacion=$_SESSION["orientacion"];//Orientacion del reporte: landscape o portrait
	if(isset($_SESSION["tituloscabecera"]))//Esto sucede solo si el reporte es estilo cabecera-detalle
	{
		$ls_titulodetalle=$_SESSION["titulodetalle"];//Titulo que llevara el Detalle del reporte
		$la_tituloscabecera=$_SESSION["tituloscabecera"];//arreglo con titulos de los campos que seran mostrados en la cabecera del reporte
		$io_classpdf=new sigesp_sob_c_plantillapdf($la_data,$la_tituloscabecera,$la_titulosdetalle,$ls_tituloencabezado,
													$ls_fechadesde,$ls_fechahasta,
													"C:/sitioweb/sigesp_php/SOB/Imagenes/LogoGobOficial8estrellas.jpg"
													,250,50,40,40,70,40,$ls_orientacion);//Se instancia objeto de la clase plantillapdf
		$x=6+($io_classpdf->li_anchopagina/2);//posicion x de los numeros de las paginas
		$y=$io_classpdf->li_margeninferior-11;//posicion y de los numeros de las paginas
		$io_classpdf->io_pdf->ezStartPageNumbers($x,$y,11,'','',1);//Se inicia numeración de paginas
		$io_classpdf->uf_print_encabezado_pagina();//Se imprime el encabezado
		//Se crea arreglo la_codaux, el cual contiene los CAMPOS CLAVES de la data que sera mostrada en el encabezado
		for($li_i=1;$li_i<=$io_classpdf->li_filasdata;$li_i++)
		{
			$la_codaux[$li_i]=$io_classpdf->la_data[$io_classpdf->la_tituloscabecera["campo"][1]][$li_i];
		}	
		$la_codaux=array_unique($la_codaux);//la_codaux ahora contiene los campos clave sin repeticiones
		sort($la_codaux);//se ordenan los campos clave en orden creciente
		$li_cantidadcabeceras=count($la_codaux);//cantidad de cabeceras que se imprimiran en el reporte
		
		for($li_i=0;$li_i<$li_cantidadcabeceras;$li_i++)//ciclo que controla la cantidad de veces que se imprimira la combinacion cabecera-detalle
		{
			//Al finalizar este ciclo se obtiene la_datacabecera, arreglo que contiene solo la data de la cabecera a ser mostrada en esta iteracion 
			for ($li_j=1;$li_j<=$io_classpdf->li_filasdata;$li_j++)
			{
				if($io_classpdf->la_data[$io_classpdf->la_tituloscabecera["campo"][1]][$li_j]==$la_codaux[$li_i])
				{
					for($li_k=1;$li_k<=$io_classpdf->li_filastituloscabecera;$li_k++)
					{
						$la_datacabecera[$io_classpdf->la_tituloscabecera["campo"][$li_k]]=$io_classpdf->la_data[$io_classpdf->la_tituloscabecera["campo"][$li_k]][$li_j];
					}
					break;
				}
			}			
			$li_index=1;
			$la_datadetalle["campo"][0]="";
			unset($la_datadetalle);			
			//Al finalizar este ciclo se obtiene la_datadetalle, arreglo que contiene solo la data del detalle que corresponde a la cabecera a ser mostrada en esta iteracion 
			for($li_j=1;$li_j<=$io_classpdf->li_filasdata;$li_j++)
			{
				if($io_classpdf->la_data[$io_classpdf->la_tituloscabecera["campo"][1]][$li_j]==$la_datacabecera[$io_classpdf->la_tituloscabecera["campo"][1]])
				{
					for($li_k=1;$li_k<=$io_classpdf->li_filastitulosdetalle;$li_k++)
					{
						$la_datadetalle[$io_classpdf->la_titulosdetalle["campo"][$li_k]][$li_index]=$io_classpdf->la_data[$io_classpdf->la_titulosdetalle["campo"][$li_k]][$li_j];											
					}	
					$li_index++;				
				}
			}			
			//Inicializamos variables de la clase plantillapdf
			$io_classpdf->la_datacabecera=$la_datacabecera;
			$io_classpdf->la_datadetalle=$la_datadetalle;
			$io_classpdf->li_filasdatacabecera=(count($la_datacabecera, COUNT_RECURSIVE) / count($la_datacabecera)) - 1;
			$io_classpdf->li_filasdatadetalle=(count($la_datadetalle, COUNT_RECURSIVE) / count($la_datadetalle)) - 1;
			
			$io_classpdf->uf_print_cabecera();//Se imprime la cabecera correspondiente a esta iteracion
			$io_classpdf->uf_print_detalle($ls_titulodetalle);//Se imprime el detalle de la cabecera correspondiente a esta iteracion
			if(isset($_SESSION["acumulado"]))
			{
				$la_acumnulador=$_SESSION["acumulado"];
				$io_classpdf->uf_print_total($la_acumnulador);
			}
			
			if ($li_i+1<$li_cantidadcabeceras)//si hay aun cabecera-detalle por imprimir, se crea una nueva pagina
				$io_classpdf->io_pdf->ezNewPage();			
		}		
	}
	else//si el reporte es estilo listado, sin cabecera
	{
		$io_classpdf=new sigesp_sob_c_plantillapdf($la_data,"",$la_titulosdetalle,$ls_tituloencabezado,$ls_fechadesde,
													$ls_fechahasta,"C:/sitioweb/sigesp_php/SOB/Imagenes/LogoGobOficial8estrellas.jpg",
													250,50,40,40,70,40,$ls_orientacion);//Se instancia oobjeto de la clase plantillapdf
		//Inicializamos variables de la clase plantillapdf
		$io_classpdf->la_datadetalle=$la_data;
		$io_classpdf->li_filasdatadetalle=(count($la_data, COUNT_RECURSIVE) / count($la_data)) - 1;
		
		$io_classpdf->uf_print_encabezado_pagina();//Se imprime encabezado
		$io_classpdf->uf_print_detalle();//Se imprime detalle		
		$x=6+($io_classpdf->li_anchopagina/2);//'x' de la numeración
		$y=$io_classpdf->li_margeninferior-11;//'Y' de la numeración
		$io_classpdf->io_pdf->ezStartPageNumbers($x,$y,11,'','',1);	//Inicia la numeracion de las paginas	
	}
	
	$io_classpdf->io_pdf->transaction('commit');
	$io_classpdf->io_pdf->ezStopPageNumbers(1,1);
	$io_classpdf->io_pdf->ezStream();
	unset($io_classpdf->io_pdf);
	if(isset($_SESSION["tituloscabecera"]))
	{
		unset($_SESSION["titulodetalle"]);
		unset($_SESSION["tituloscabecera"]);
	}
	if(isset($_SESSION["acumulador"]))
		unset($_SESSION["acumulador"]);
	if(isset($_SESSION["keys"]))
		unset($_SESSION["keys"]);
	unset($_SESSION["data"]);
	unset($_SESSION["titulosdetalle"]);	
	unset($_SESSION["tituloencabezado"]);
	unset($_SESSION["fechadesde"]);
	unset($_SESSION["fechahasta"]);	
	unset($_SESSION["orientacion"]);
	
?>