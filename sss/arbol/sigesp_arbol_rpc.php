<?php
$i = 0;
$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=0;
$arbol["nombre_logico"][$i]="Proveedores";
$arbol["nombre_fisico"][$i]="";
$arbol["id"][$i]="001";
$arbol["padre"][$i]="000";
$arbol["numero_hijos"][$i]=8;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Parametro de Calificación";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_clasificacion.php";
$arbol["id"][$i]="002";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Maestro de Recaudos";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_documento.php";
$arbol["id"][$i]="003";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Especialidad";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_especialidad.php";
$arbol["id"][$i]="004";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Tipo Empresa";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_tipoempresa.php";
$arbol["id"][$i]="005";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Ficha";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_proveedor.php";
$arbol["id"][$i]="006";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=0;
$arbol["nombre_logico"][$i]="Beneficiario";
$arbol["nombre_fisico"][$i]="";
$arbol["id"][$i]="007";
$arbol["padre"][$i]="000";
$arbol["numero_hijos"][$i]=1;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Ficha";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_beneficiario.php";
$arbol["id"][$i]="008";
$arbol["padre"][$i]="007";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=0;
$arbol["nombre_logico"][$i]="Procesos";
$arbol["nombre_fisico"][$i]="";
$arbol["id"][$i]="015";
$arbol["padre"][$i]="000";
$arbol["numero_hijos"][$i]=1;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Transferencia de Personal";
$arbol["nombre_fisico"][$i]="sigesp_rpc_p_transferencia.php";
$arbol["id"][$i]="016";
$arbol["padre"][$i]="015";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=0;
$arbol["nombre_logico"][$i]="Reportes";
$arbol["nombre_fisico"][$i]="";
$arbol["id"][$i]="009";
$arbol["padre"][$i]="000";
$arbol["numero_hijos"][$i]=2;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Beneficiarios";
$arbol["nombre_fisico"][$i]="sigesp_rpc_r_beneficiario.php";
$arbol["id"][$i]="010";
$arbol["padre"][$i]="009";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Proveedores";
$arbol["nombre_fisico"][$i]="sigesp_rpc_r_provxespecia.php";
$arbol["id"][$i]="011";
$arbol["padre"][$i]="009";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Proveedores-Socios";
$arbol["nombre_fisico"][$i]="sigesp_rpc_d_socio.php";
$arbol["id"][$i]="012";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Proveedores-Clasificacion";
$arbol["nombre_fisico"][$i]="sigesp_rpc_w_proxcla.php";
$arbol["id"][$i]="013";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Proveedores-Documentos";
$arbol["nombre_fisico"][$i]="sigesp_rpc_w_proxdoc.php";
$arbol["id"][$i]="014";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Proveedores-Especialidad";
$arbol["nombre_fisico"][$i]="sigesp_rpc_w_proxesp.php";
$arbol["id"][$i]="017";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Actualizar Estatus de Proveedor";
$arbol["nombre_fisico"][$i]="sigesp_rpc_p_cambioestatus_proveedor.php";
$arbol["id"][$i]="018";
$arbol["padre"][$i]="015";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Proveedores-Deducciones";
$arbol["nombre_fisico"][$i]="sigesp_rpc_w_proxded.php";
$arbol["id"][$i]="019";
$arbol["padre"][$i]="001";
$arbol["numero_hijos"][$i]=0;

$i++;
$arbol["sistema"][$i]="RPC";
$arbol["nivel"][$i]=1;
$arbol["nombre_logico"][$i]="Beneficiarios-Deducciones";
$arbol["nombre_fisico"][$i]="sigesp_rpc_w_benexded.php";
$arbol["id"][$i]="020";
$arbol["padre"][$i]="007";
$arbol["numero_hijos"][$i]=0;


$gi_total=$i;
?>