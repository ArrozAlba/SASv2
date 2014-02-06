<?php
$li_i=000;

$li_i++; // 001
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Definiciones";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 002
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Procesos";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 003
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Sistemas";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 004
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Mantenimiento";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; // 005
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=0;
$arbol["nombre_logico"][$li_i]="Reportes";
$arbol["nombre_fisico"][$li_i]="";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="000";
$arbol["numero_hijos"][$li_i]=1;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Sistemas";
$arbol["nombre_fisico"][$li_i]="sigespwindow_sss_sistemas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Grupos";
$arbol["nombre_fisico"][$li_i]="sigespwindow_sss_grupos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Definición de Usuarios";
$arbol["nombre_fisico"][$li_i]="sigespwindow_sss_usuarios.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="001";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar usuarios a grupos";
$arbol["nombre_fisico"][$li_i]="sigespwindow_sss_usuarios_grupos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar Nóminas a Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_usuariosnominas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar Presupuesto a Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_usuariospresupuesto.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar Cuentas Bancarias a Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_usuarioscuentasbancarias.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar Unidades Ejecutoras a Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_usuariosunidad.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Asignar Constantes a Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_usuariosconstantes.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Transferir Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_traspasar_usuarios.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="002";
$arbol["numero_hijos"][$li_i]=0;


$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Permisos por Pantalla";
$arbol["nombre_fisico"][$li_i]="sigespwindow_sss_derecho_grupo.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="003";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Actualizar Ventanas";
$arbol["nombre_fisico"][$li_i]="sigesp_c_actualizar_ventanas.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Administrador - Cambio de Password";
$arbol["nombre_fisico"][$li_i]="sigesp_c_repassword_admin.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Permisos por Sistemas";
$arbol["nombre_fisico"][$li_i]="sigesp_c_permisos_globales.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Eliminar Perfil de Seguridad";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_p_eliminar_permisos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="004";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Auditoría";
$arbol["nombre_fisico"][$li_i]="sigespwindow_sss_auditoria.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte de Permisos a Usuarios";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_r_permisos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$li_i++; 
$arbol["sistema"][$li_i]="SSS";
$arbol["nivel"][$li_i]=1;
$arbol["nombre_logico"][$li_i]="Reporte de Traspasos";
$arbol["nombre_fisico"][$li_i]="sigesp_sss_r_traspasos.php";
$arbol["id"][$li_i]=$li_i;
$arbol["padre"][$li_i]="005";
$arbol["numero_hijos"][$li_i]=0;

$gi_total=$li_i;

?>
