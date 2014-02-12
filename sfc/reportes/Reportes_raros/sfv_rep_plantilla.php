<?Php
session_start();

require_once("../../class_folder/sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');

$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);

require_once("C:\archivos de programa\apache group\Apache\htdocs\sigesp_phpCVA\shared\class_folder\sigesp_include.php");
require_once("C:\archivos de programa\apache group\Apache\htdocs\sigesp_phpCVA\shared\class_folder\class_mensajes.php");
require_once("C:\archivos de programa\apache group\Apache\htdocs\sigesp_phpCVA\shared\class_folder\class_sql.php");
require_once("C:\archivos de programa\apache group\Apache\htdocs\sigesp_phpCVA\shared\class_folder\class_funciones.php");
require_once("C:\archivos de programa\apache group\Apache\htdocs\sigesp_phpCVA\shared\class_folder\class_datastore.php");

$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
/*$io_utilidad = new sigesp_sfc_class_utilidades();*/

$io_pdf = $reporte->get_pdf();
 


/******************************************************************************************************************************/

$reporte->add_titulo("center",43,11,"REPORTE DE FACTURAS");
/*$io_pdf->add_linea(0,50,165,50,1); /*(coli,fili,colf,filf,1)*/

			/*$ls_sql=$_SESSION["SQLD"];*/
            $ls_sql=$_GET["sql"];
		    $ls_sql=str_replace("\\","",$ls_sql);
		    /*print $ls_sql;*/
		    $rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
			  
				$io_msg->message("No hay registros");
			}
			else
			{ 
			   $la_factura=$io_sql->obtener_datos($rs_datauni);
	        }
		
			    /*$la_factura=$_SESSION["querysql1"];*/
			
				$la_factura["fecemi"][0]="";
			    $la_factura["numfac"][0]="";
   				$la_factura["codcli"][0]="";
				$la_factura["codusu"][0]="";
				$la_factura["monto"][0]="";	
				$la_factura["estfac"][0]="";	
							
                $li_cuotas=(count($la_factura,COUNT_RECURSIVE)/count($la_factura)) - 1;
			   
				for($i=0;$i<$li_cuotas;$i++)
				{
				 $la_datos[$i]["fecemi"]= $la_factura["fecemi"][$i];
				 $la_datos[$i]["numfac"]= $la_factura["numfac"][$i];
				 $la_datos[$i]["codcli"]= $la_factura["codcli"][$i];
				 $la_datos[$i]["codusu"]= $la_factura["codusu"][$i];
				 $la_datos[$i]["monto"]= $la_factura["monto"][$i];
				 $la_datos[$i]["estfac"]= $la_factura["estfac"][$i];
				 
				 
				}
				
				
				$io_pdf->ezSetY(550);
        
				$la_anchos_col = array(20,40,20,20,20,10);
				$la_justificaciones = array('center','center','center','center','center','center');
		
				$la_titulos[0]["1"]="Fecha";
				$la_titulos[0]["2"]="No. Factura";
				$la_titulos[0]["3"]="Cliente";
				$la_titulos[0]["4"]="Cajero";
				$la_titulos[0]["5"]="Monto";
				$la_titulos[0]["6"]="Status";
				$la_opciones = array(  "color_fondo" => array(201,249,200), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_titulos,$la_opciones);  
			  
				$la_opciones = array(  "color_fondo" => array(229,229,229), 
									   "color_texto" => array(0,0,0),
									   "anchos_col"  => $la_anchos_col,
									   "tamano_texto"=> 7,
									   "lineas"=>1,
									   "alineacion_col"=>$la_justificaciones,
									   "margen_horizontal"=>1);
				$io_pdf->add_tabla(0,$la_datos,$la_opciones);
			
					
		
			
		     					   
/*******************************************************************************************************************************/					 

 
/*$ls_contenido="Prueba reporte";
$reporte->cuerpo_reporte($ls_contenido);*/



$io_pdf->ezStream();


?>