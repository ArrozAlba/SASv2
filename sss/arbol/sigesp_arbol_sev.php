<?php

$filas=1;

$arbol["sistema"][$filas]="SEV";
$arbol["nivel"][$filas]= 0;
$arbol["nombre_logico"][$filas]="Solicitudes";
$arbol["nombre_fisico"][$filas]="";
$arbol["id"][$filas]="001";
$arbol["padre"][$filas]="000";
$arbol["numero_hijos"][$filas]=2;
$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Definiciones";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="002";
  $arbol["padre"][$filas]="001";
  $arbol["numero_hijos"][$filas]=4;
  $filas++;
  
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Ubicación Geográfica";
	$arbol["nombre_fisico"][$filas]="";
	$arbol["id"][$filas]="003";
	$arbol["padre"][$filas]="002";
	$arbol["numero_hijos"][$filas]=5;
	$filas++;

	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Estados";
	  $arbol["nombre_fisico"][$filas]="sev_def_estado.php";
	  $arbol["id"][$filas]="004";
	  $arbol["padre"][$filas]="003";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
	
	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Municipios";
	  $arbol["nombre_fisico"][$filas]="sev_def_municipio.php";
	  $arbol["id"][$filas]="005";
	  $arbol["padre"][$filas]="003";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
			  			
	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Parroquias";
	  $arbol["nombre_fisico"][$filas]="sev_def_parroquia.php";
	  $arbol["id"][$filas]="006";
	  $arbol["padre"][$filas]="003";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
	
	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Sectores";
	  $arbol["nombre_fisico"][$filas]="sev_def_sector.php";
	  $arbol["id"][$filas]="007";
	  $arbol["padre"][$filas]="003";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
	
	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Vialidades";
	  $arbol["nombre_fisico"][$filas]="sev_def_vialidad.php";
	  $arbol["id"][$filas]="008";
	  $arbol["padre"][$filas]="003";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
	
	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Poblaciones";
	  $arbol["nombre_fisico"][$filas]="sev_def_municipio_poblacion.php";
	  $arbol["id"][$filas]="009";
	  $arbol["padre"][$filas]="003";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Profesiones u Oficios";
	$arbol["nombre_fisico"][$filas]="sev_def_profesion.php";
	$arbol["id"][$filas]="010";
	$arbol["padre"][$filas]="002";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Frecuencias Salariales";
	$arbol["nombre_fisico"][$filas]="sev_def_frecuncia_salarial.php";
	$arbol["id"][$filas]="011";
	$arbol["padre"][$filas]="002";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Tenencias de Vivienda";
	$arbol["nombre_fisico"][$filas]="sev_def_tenencia_vivienda.php";
	$arbol["id"][$filas]="012";
	$arbol["padre"][$filas]="002";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Registro";
  $arbol["nombre_fisico"][$filas]="sev_def_solicitud.php";
  $arbol["id"][$filas]="013";
  $arbol["padre"][$filas]="001";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

$arbol["sistema"][$filas]="SEV";
$arbol["nivel"][$filas]=0;
$arbol["nombre_logico"][$filas]="Terrenos";
$arbol["nombre_fisico"][$filas]="";
$arbol["id"][$filas]="014";
$arbol["padre"][$filas]="000";
$arbol["numero_hijos"][$filas]=2;
$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Definiciones";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="015";
  $arbol["padre"][$filas]="014";
  $arbol["numero_hijos"][$filas]=3;
  $filas++;

	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Dueños de Terreno";
	$arbol["nombre_fisico"][$filas]="sev_def_dueno_trreno.php";
	$arbol["id"][$filas]="016";
	$arbol["padre"][$filas]="015";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Tenencias de Tierra";
	$arbol["nombre_fisico"][$filas]="sev_def_tenencia_tierra.php";
	$arbol["id"][$filas]="017";
	$arbol["padre"][$filas]="015";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Situaciones Legales";
	$arbol["nombre_fisico"][$filas]="sev_def_sit_legal_terreno.php";
	$arbol["id"][$filas]="018";
	$arbol["padre"][$filas]="015";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Registro";
  $arbol["nombre_fisico"][$filas]="sev_def_terreno.php";
  $arbol["id"][$filas]="019";
  $arbol["padre"][$filas]="014";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

$arbol["sistema"][$filas]="SEV";
$arbol["nivel"][$filas]=0;
$arbol["nombre_logico"][$filas]="Proyectos";
$arbol["nombre_fisico"][$filas]="";
$arbol["id"][$filas]="020";
$arbol["padre"][$filas]="000";
$arbol["numero_hijos"][$filas]=2;
$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Definiciones";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="021";
  $arbol["padre"][$filas]="020";
  $arbol["numero_hijos"][$filas]=5;
  $filas++;

	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Viviendas";
	$arbol["nombre_fisico"][$filas]="";
	$arbol["id"][$filas]="022";
	$arbol["padre"][$filas]="021";
	$arbol["numero_hijos"][$filas]=5;
	$filas++;

	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Tipos de Piso";
	  $arbol["nombre_fisico"][$filas]="sev_def_tipo_piso.php";
	  $arbol["id"][$filas]="023";
	  $arbol["padre"][$filas]="022";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;

	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Tipos de Techo";
	  $arbol["nombre_fisico"][$filas]="sev_def_tipo_techo.php";
	  $arbol["id"][$filas]="024";
	  $arbol["padre"][$filas]="022";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;

	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Tipos de Pared";
	  $arbol["nombre_fisico"][$filas]="sev_def_tipo_pared.php";
	  $arbol["id"][$filas]="025";
	  $arbol["padre"][$filas]="022";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;

	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Tipos de Vivienda";
	  $arbol["nombre_fisico"][$filas]="sev_def_tipo_vivienda.php";
	  $arbol["id"][$filas]="026";
	  $arbol["padre"][$filas]="022";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;

	  $arbol["sistema"][$filas]="SEV";
	  $arbol["nivel"][$filas]=3;
	  $arbol["nombre_logico"][$filas]="Modelos de Vivienda";
	  $arbol["nombre_fisico"][$filas]="sev_def_modelo_vivienda.php";
	  $arbol["id"][$filas]="027";
	  $arbol["padre"][$filas]="022";
	  $arbol["numero_hijos"][$filas]=0;
	  $filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Modalidades de Construcción";
	$arbol["nombre_fisico"][$filas]="sev_def_modalidad_construccion.php";
	$arbol["id"][$filas]="028";
	$arbol["padre"][$filas]="021";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Entes Financieros";
	$arbol["nombre_fisico"][$filas]="sev_def_ente_financiero.php";
	$arbol["id"][$filas]="029";
	$arbol["padre"][$filas]="021";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Organismos Ejecutores";
	$arbol["nombre_fisico"][$filas]="sev_def_organismo_ejecutor.php";
	$arbol["id"][$filas]="030";
	$arbol["padre"][$filas]="021";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;
	
	$arbol["sistema"][$filas]="SEV";
	$arbol["nivel"][$filas]=2;
	$arbol["nombre_logico"][$filas]="Programas";
	$arbol["nombre_fisico"][$filas]="sev_def_programa.php";
	$arbol["id"][$filas]="031";
	$arbol["padre"][$filas]="021";
	$arbol["numero_hijos"][$filas]=0;
	$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Registro";
  $arbol["nombre_fisico"][$filas]="sev_def_proyecto.php";
  $arbol["id"][$filas]="032";
  $arbol["padre"][$filas]="020";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

$arbol["sistema"][$filas]="SEV";
$arbol["nivel"][$filas]= 0;
$arbol["nombre_logico"][$filas]="Obras";
$arbol["nombre_fisico"][$filas]="";
$arbol["id"][$filas]="033";
$arbol["padre"][$filas]="000";
$arbol["numero_hijos"][$filas]=7;
$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Inicios";
  $arbol["nombre_fisico"][$filas]="sev_def_obras_inicio.php";
  $arbol["id"][$filas]="034";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Contrataciones";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="035";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=2;
  $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Registro";
    $arbol["nombre_fisico"][$filas]="sev_def_obras_contratacion_registro.php";
    $arbol["id"][$filas]="036";
    $arbol["padre"][$filas]="035";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Anulación";
    $arbol["nombre_fisico"][$filas]="sev_def_obras_contratacion_anulacion.php";
    $arbol["id"][$filas]="037";
    $arbol["padre"][$filas]="035";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Paralizaciones";
  $arbol["nombre_fisico"][$filas]="sev_def_obras_paralizacion.php";
  $arbol["id"][$filas]="038";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Reinicios";
  $arbol["nombre_fisico"][$filas]="sev_def_obras_reinicio.php";
  $arbol["id"][$filas]="039";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Variaciones";
  $arbol["nombre_fisico"][$filas]="sev_def_obras_variacion.php";
  $arbol["id"][$filas]="040";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Valuaciones";
  $arbol["nombre_fisico"][$filas]="sev_def_obras_valuacion.php";
  $arbol["id"][$filas]="041";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Culminaciones";
  $arbol["nombre_fisico"][$filas]="sev_def_obras_culminacion.php";
  $arbol["id"][$filas]="042";
  $arbol["padre"][$filas]="033";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

$arbol["sistema"][$filas]="SEV";
$arbol["nivel"][$filas]= 0;
$arbol["nombre_logico"][$filas]="Reportes";
$arbol["nombre_fisico"][$filas]="";
$arbol["id"][$filas]="043";
$arbol["padre"][$filas]="000";
$arbol["numero_hijos"][$filas]=4;
$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Solicitudes";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="044";
  $arbol["padre"][$filas]="043";
  $arbol["numero_hijos"][$filas]=3;
  $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Deficit Habitacional";
    $arbol["nombre_fisico"][$filas]="sev_rep_datos_anos_dh.php";
    $arbol["id"][$filas]="045";
    $arbol["padre"][$filas]="044";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Resumen de Solicitudes";
    $arbol["nombre_fisico"][$filas]="sev_rep_datos_anos_rs.php";
    $arbol["id"][$filas]="046";
    $arbol["padre"][$filas]="044";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Detalle de Solicitudes";
    $arbol["nombre_fisico"][$filas]="sev_rep_datos_fechas_ds.php";
    $arbol["id"][$filas]="047";
    $arbol["padre"][$filas]="044";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Terrenos";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="048";
  $arbol["padre"][$filas]="043";
  $arbol["numero_hijos"][$filas]=2;
  $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Resumen de Terrenos Disponibles";
    $arbol["nombre_fisico"][$filas]="sev_rep_resumen_terrenos_disponibles_rtd.php";
    $arbol["id"][$filas]="049";
    $arbol["padre"][$filas]="048";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Detalle de Terrenos Dispoinibles";
    $arbol["nombre_fisico"][$filas]="sev_rep_detalle_terrenos_disponibles_dtd.php";
    $arbol["id"][$filas]="050";
    $arbol["padre"][$filas]="048";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Proyectos";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="051";
  $arbol["padre"][$filas]="043";
  $arbol["numero_hijos"][$filas]=0;
  $filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Obras";
  $arbol["nombre_fisico"][$filas]="";
  $arbol["id"][$filas]="052";
  $arbol["padre"][$filas]="043";
  $arbol["numero_hijos"][$filas]=2;
  $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Resumen de Ejecución de Viviendas";
    $arbol["nombre_fisico"][$filas]="sev_rep_datos_anos_rev.php";
    $arbol["id"][$filas]="053";
    $arbol["padre"][$filas]="052";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;

    $arbol["sistema"][$filas]="SEV";
    $arbol["nivel"][$filas]=2;
    $arbol["nombre_logico"][$filas]="Avance Físico Financiero";
    $arbol["nombre_fisico"][$filas]="sev_rep_datos_fecha_aff.php";
    $arbol["id"][$filas]="054";
    $arbol["padre"][$filas]="052";
    $arbol["numero_hijos"][$filas]=0;
    $filas++;
    
$arbol["sistema"][$filas]="SEV";
$arbol["nivel"][$filas]= 0;
$arbol["nombre_logico"][$filas]="Mantenimiento";
$arbol["nombre_fisico"][$filas]="";
$arbol["id"][$filas]="055";
$arbol["padre"][$filas]="000";
$arbol["numero_hijos"][$filas]=1;
$filas++;

  $arbol["sistema"][$filas]="SEV";
  $arbol["nivel"][$filas]=1;
  $arbol["nombre_logico"][$filas]="Seguridad";
  $arbol["nombre_fisico"][$filas]="sigespwindow_blank.php";
  $arbol["id"][$filas]="056";
  $arbol["padre"][$filas]="055";
  $arbol["numero_hijos"][$filas]=0;
  
$gi_total=$filas
?>
