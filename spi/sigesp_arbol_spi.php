<?php
$gi_total=20;
$arbol["sistema"][1]="SPI";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=4;

$arbol["sistema"][2]="SPI";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Comprobantes";
$arbol["nombre_fisico"][2]="";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=1;

$arbol["sistema"][3]="SPI";
$arbol["nivel"][3]=2;
$arbol["nombre_logico"][3]="Ejecución Financiera";
$arbol["nombre_fisico"][3]="sigesp_spi_p_comprobante.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="002";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SPI";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Apertura";
$arbol["nombre_fisico"][4]="";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=2;

$arbol["sistema"][5]="SPI";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Mensual";
$arbol["nombre_fisico"][5]="sigesp_spi_p_apertura.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="004";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SPI";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Trimestral";
$arbol["nombre_fisico"][6]="sigesp_spi_p_apertura_trimestral.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="004";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="SPI";
$arbol["nivel"][7]=1;
$arbol["nombre_logico"][7]="Modificaciones Presupuestarias";
$arbol["nombre_fisico"][7]="";
$arbol["id"][7]="007";
$arbol["padre"][7]="001";
$arbol["numero_hijos"][7]=4;

$arbol["sistema"][8]="SPI";
$arbol["nivel"][8]=2;
$arbol["nombre_logico"][8]="Aumentos";
$arbol["nombre_fisico"][8]="sigesp_spi_p_aumento.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="007";
$arbol["numero_hijos"][8]=0;


$arbol["sistema"][9]="SPI";
$arbol["nivel"][9]=2;
$arbol["nombre_logico"][9]="Disminuciones";
$arbol["nombre_fisico"][9]="sigesp_spi_p_disminucion.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="007";
$arbol["numero_hijos"][9]=0;

$arbol["sistema"][10]="SPI";
$arbol["nivel"][10]=1;
$arbol["nombre_logico"][10]="Programación de Reportes";
$arbol["nombre_fisico"][10]="";
$arbol["id"][10]="010";
$arbol["padre"][10]="001";
$arbol["numero_hijos"][10]=2;

$arbol["sistema"][11]="SPI";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Mensual";
$arbol["nombre_fisico"][11]="sigesp_spi_p_progrep.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="010";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SPI";
$arbol["nivel"][12]=2;
$arbol["nombre_logico"][12]="Trimestral";
$arbol["nombre_fisico"][12]="sigesp_spi_p_progrep_trimestral.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="010";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="SPI";
$arbol["nivel"][13]=0;
$arbol["nombre_logico"][13]="Reportes";
$arbol["nombre_fisico"][13]="";
$arbol["id"][13]="013";
$arbol["padre"][13]="000";
$arbol["numero_hijos"][13]=3;

$arbol["sistema"][14]="SPI";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Acumulado por Cuentas";
$arbol["nombre_fisico"][14]="sigesp_spi_r_acum_x_cuentas.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="013";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SPI";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Mayor Analitico";
$arbol["nombre_fisico"][15]="sigesp_spi_r_mayor_analitico.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="013";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="SPI";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Listado de Apertura";
$arbol["nombre_fisico"][16]="sigesp_spi_r_listado_apertura.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="013";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="SPI";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Comprobante";
$arbol["nombre_fisico"][17]="sigesp_spi_r_comprobante_formato1.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="013";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SPI";
$arbol["nivel"][18]=1;
$arbol["nombre_logico"][18]="Listado de Cuenta";
$arbol["nombre_fisico"][18]="sigesp_spi_r_cuentas.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="013";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]="SPI";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Modificaciones Presupuestarias Aprobadas";
$arbol["nombre_fisico"][19]="sigesp_spi_r_modificaciones_presupuestarias_aprobadas.php";
$arbol["id"][19]="019";
$arbol["padre"][19]="013";
$arbol["numero_hijos"][19]=0;

$arbol["sistema"][20]="SPI";
$arbol["nivel"][20]=1;
$arbol["nombre_logico"][20]="Modificaciones Presupuestarias No Aprobadas";
$arbol["nombre_fisico"][20]="sigesp_spi_r_modificaciones_presupuestarias_no_aprobadas.php";
$arbol["id"][20]="020";
$arbol["padre"][20]="013";
$arbol["numero_hijos"][20]=0;

?>