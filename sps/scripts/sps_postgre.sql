/*==============================================================*/
/* DBMS name:      PostgreSQL 8                                 */
/* Created on:     02/07/2008 10:48:39 a.m.                     */
/*==============================================================*/


drop index idx_anticipos;

drop table sps_anticipos;

drop index idx_antiguedad;

drop table sps_antiguedad;

drop table sps_articulos;

drop table sps_causaretiro;

drop table sps_configuracion;

drop index idx_deuda_anterior;

drop table sps_deuda_anterior;

drop table sps_dt_liquidacion;

drop index idx_liquidacion;

drop table sps_liquidacion;

drop index idx_sueldos;

drop table sps_sueldos;

drop table sps_tasa_interes;

/*==============================================================*/
/* Table: sps_anticipos                                         */
/*==============================================================*/
create table sps_anticipos (
   codemp               CHAR(4)              not null,
   codnom               CHAR(4)              not null,
   codper               CHAR(10)             not null,
   fecantper            DATE                 not null,
   anoserper            INT4                 not null,
   messerper            INT4                 not null,
   diaserper            INT4                 not null,
   motant               VARCHAR(254)         not null,
   mondeulab            FLOAT8               not null,
   monporant            FLOAT8               not null,
   monant               FLOAT8               not null,
   estant               VARCHAR(1)           not null default '0' 
      constraint CKC_ESTANT_SPS_ANTI check (estant in ('0','1','2','3')),
   obsant               VARCHAR(254)         null,
   constraint PK_SPS_ANTICIPOS primary key (codemp, codper, codnom, fecantper)
);

comment on table sps_anticipos is
'Tabla que contiene los anticipos de prestaciones sociales solicitados y aprobados al personal';

/*==============================================================*/
/* Index: idx_anticipos                                         */
/*==============================================================*/
create unique index idx_anticipos on sps_anticipos (
codemp,
codnom,
codper,
fecantper
);

/*==============================================================*/
/* Table: sps_antiguedad                                        */
/*==============================================================*/
create table sps_antiguedad (
   codemp               CHAR(4)              not null,
   codnom               CHAR(4)              not null,
   codper               CHAR(10)             not null,
   fecant               DATE                 not null,
   anoserant            INT4                 not null,
   messerant            INT4                 not null,
   diaserant            INT4                 not null,
   salbas               FLOAT8               not null default '0',
   incbonvac            FLOAT8               not null,
   incbonnav            FLOAT8               not null,
   salint               FLOAT8               not null default '0',
   salintdia            FLOAT8               not null,
   diabas               INT4                 not null,
   diacom               INT4                 not null,
   diaacu               INT4                 not null,
   monant               FLOAT8               not null default '0',
   monacuant            FLOAT8               not null default '0',
   monantant            FLOAT8               not null default '0',
   salparant            FLOAT8               not null default '0',
   porint               FLOAT8               not null,
   diaint               INT4                 not null,
   monint               FLOAT8               not null default '0',
   monacuint            FLOAT8               not null default '0',
   saltotant            FLOAT8               not null,
   estcapint            CHAR(1)              not null,
   estant               CHAR(1)              not null 
      constraint CKC_ESTANT_SPS_ANTI check (estant in ('R','P','L')),
   liquidacion          CHAR(10)             null,
   constraint PK_SPS_ANTIGUEDAD primary key (codemp, codper, codnom, fecant)
);

comment on table sps_antiguedad is
'tabla donde se refleja todos los items de calculo de antiguedad de cada empleado.
Art. 108 de la ley organica del trabajdor.';

/*==============================================================*/
/* Index: idx_antiguedad                                        */
/*==============================================================*/
create unique index idx_antiguedad on sps_antiguedad (
codemp,
codnom,
codper,
fecant
);

/*==============================================================*/
/* Table: sps_articulos                                         */
/*==============================================================*/
create table sps_articulos (
   id_art               CHAR(4)              not null,
   numart               CHAR(4)              not null,
   fecvig               DATE                 not null,
   numlitart            CHAR(2)              not null,
   numcon               CHAR(1)              not null,
   conart               VARCHAR(60)          not null,
   operador             CHAR(1)              not null,
   canmes               INT4                 not null,
   tiempo               CHAR(1)              not null,
   diasal               FLOAT8               not null,
   condicion            CHAR(4)              not null,
   estacu               CHAR(1)              not null,
   diaacu               FLOAT8               not null,
   constraint PK_SPS_ARTICULOS primary key (id_art, numart, fecvig, numlitart, numcon)
);

comment on table sps_articulos is
'tabla que contiene los detalles de cada articulo, asi como tambien los paragrafos en caso de tenerlos.';

/*==============================================================*/
/* Table: sps_causaretiro                                       */
/*==============================================================*/
create table sps_causaretiro (
   codcauret            CHAR(2)              not null,
   dencauret            VARCHAR(50)          not null,
   constraint PK_SPS_CAUSARETIRO primary key (codcauret)
);

comment on table sps_causaretiro is
'Dicha tabla define las causas por la cual se puede retirar un trabajador de la empresa';

/*==============================================================*/
/* Table: sps_configuracion                                     */
/*==============================================================*/
create table sps_configuracion (
   id                   CHAR(1)              not null,
   porant               FLOAT8               not null,
   estsue               CHAR(1)              not null,
   estincbon            CHAR(1)              not null,
   sc_cuenta_ps         CHAR(25)             null default ' ',
   sig_cuenta_emp_fijo_ps CHAR(25)             null default ' ',
   sig_cuenta_emp_fijo_vac CHAR(25)             null default ' ',
   sig_cuenta_emp_fijo_agu CHAR(25)             null default ' ',
   sig_cuenta_obr_fijo_ps CHAR(25)             null default ' ',
   sig_cuenta_obr_fijo_vac CHAR(25)             null default ' ',
   sig_cuenta_obr_fijo_agu CHAR(25)             null default ' ',
   sig_cuenta_emp_cont_ps CHAR(25)             null default ' ',
   sig_cuenta_emp_cont_vac CHAR(25)             null default ' ',
   sig_cuenta_emp_cont_agu CHAR(25)             null default ' ',
   sig_cuenta_emp_esp_ps CHAR(25)             null default ' ',
   sig_cuenta_emp_esp_vac CHAR(25)             null default ' ',
   sig_cuenta_emp_esp_agu CHAR(25)             null default ' ',
   constraint PK_SPS_CONFIGURACION primary key (id)
);

comment on table sps_configuracion is
'Tabla que contiene los campos de configuracion del sistema';

/*==============================================================*/
/* Table: sps_deuda_anterior                                    */
/*==============================================================*/
create table sps_deuda_anterior (
   codemp               CHAR(4)              not null,
   codnom               CHAR(4)              not null,
   codper               CHAR(10)             not null,
   feccordeuant         DATE                 not null,
   deuantant            FLOAT8               not null default '0',
   deuantint            FLOAT8               not null default '0',
   antpag               FLOAT8               not null default '0',
   estdeuant            CHAR(1)              not null 
      constraint CKC_ESTDEUANT_SPS_DEUD check (estdeuant in ('E','P')),
   constraint PK_SPS_DEUDA_ANTERIOR primary key (codemp, codper, codnom, feccordeuant)
);

comment on table sps_deuda_anterior is
'tabla que registra los montos de deuda anteriores del empleado (q tenga de otra institucion o la deuda de transición a la fecha de reforma de la ley)';

/*==============================================================*/
/* Index: idx_deuda_anterior                                    */
/*==============================================================*/
create unique index idx_deuda_anterior on sps_deuda_anterior (
codemp,
codnom,
codper,
feccordeuant
);

/*==============================================================*/
/* Table: sps_dt_liquidacion                                    */
/*==============================================================*/
create table sps_dt_liquidacion (
   codemp               CHAR(4)              not null,
   codper               CHAR(10)             not null,
   codnom               CHAR(4)              not null,
   numliq               CHAR(10)             not null,
   numespliq            CHAR(2)              not null,
   desespliq            CHAR(150)            not null,
   salpro               FLOAT8               not null,
   diapag               FLOAT8               not null,
   subtotal             FLOAT8               not null,
   constraint PK_SPS_DT_LIQUIDACION primary key (codemp, codper, codnom, numliq, numespliq)
);

comment on table sps_dt_liquidacion is
'tabla que contiene los detalles de la liquidación';

/*==============================================================*/
/* Table: sps_liquidacion                                       */
/*==============================================================*/
create table sps_liquidacion (
   codemp               CHAR(4)              not null,
   codnom               CHAR(4)              not null,
   codper               CHAR(10)             not null,
   numliq               CHAR(10)             not null,
   codcauret            CHAR(2)              not null,
   fecliq               DATE                 not null,
   fecing               DATE                 not null,
   fecegr               DATE                 not null,
   salint               FLOAT8               not null,
   descargo             CHAR(40)             not null,
   anoser               INT4                 not null,
   messer               INT4                 not null,
   diaser               INT4                 not null,
   totasiliq            FLOAT8               not null,
   totdedliq            FLOAT8               not null,
   totpagliq            FLOAT8               not null,
   estliq               CHAR(1)              not null 
      constraint CKC_ESTLIQ_SPS_LIQU check (estliq in ('R','A','P')),
   obsliq               VARCHAR(200)         null,
   dedicacion           VARCHAR(100)         not null,
   tipopersonal         VARCHAR(100)         not null,
   constraint PK_SPS_LIQUIDACION primary key (codemp, codper, codnom, numliq)
);

comment on table sps_liquidacion is
'tabla que contiene las liquidaciones de los empleados';

/*==============================================================*/
/* Index: idx_liquidacion                                       */
/*==============================================================*/
create unique index idx_liquidacion on sps_liquidacion (
codemp,
codnom,
codper,
numliq
);

/*==============================================================*/
/* Table: sps_sueldos                                           */
/*==============================================================*/
create table sps_sueldos (
   codemp               CHAR(4)              not null,
   codnom               CHAR(4)              not null,
   codper               CHAR(10)             not null,
   fecincsue            DATE                 not null,
   monsuebas            FLOAT8               not null,
   monsueint            FLOAT8               not null,
   monsuenordia         FLOAT8               not null,
   constraint PK_SPS_SUELDOS primary key (codemp, codper, codnom, fecincsue)
);

comment on table sps_sueldos is
'Tabla que contiene los distintos sueldos e incrementos que afectan al trabajador';

/*==============================================================*/
/* Index: idx_sueldos                                           */
/*==============================================================*/
create unique index idx_sueldos on sps_sueldos (
codemp,
codnom,
codper,
fecincsue
);

/*==============================================================*/
/* Table: sps_tasa_interes                                      */
/*==============================================================*/
create table sps_tasa_interes (
   anotasint            INT4                 not null,
   mestasint            INT4                 not null,
   valtas               FLOAT8               not null,
   numgac               CHAR(6)              not null,
   constraint PK_SPS_TASA_INTERES primary key (anotasint, mestasint)
);

comment on table sps_tasa_interes is
'Tabla que contiene las tasas porcentual mensual para el calculo de intereses, dichas tasas deben ser suministradas por el banco central de venezuela.';

alter table sps_anticipos
   add constraint FK_SPS_ANTI_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
      references sno_personalnomina (codemp, codnom, codper)
      on delete restrict on update restrict;

alter table sps_antiguedad
   add constraint FK_SPS_ANTI_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
      references sno_personalnomina (codemp, codnom, codper)
      on delete restrict on update restrict;

alter table sps_deuda_anterior
   add constraint FK_SPS_DEUD_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
      references sno_personalnomina (codemp, codnom, codper)
      on delete restrict on update restrict;

alter table sps_dt_liquidacion
   add constraint FK_SPS_DT_L_REFERENCE_SPS_LIQU foreign key (codemp, codper, codnom, numliq)
      references sps_liquidacion (codemp, codper, codnom, numliq)
      on delete restrict on update restrict;

alter table sps_liquidacion
   add constraint FK_SPS_LIQU_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
      references sno_personalnomina (codemp, codnom, codper)
      on delete restrict on update restrict;

alter table sps_liquidacion
   add constraint FK_SPS_LIQU_REFERENCE_SPS_CAUS foreign key (codcauret)
      references sps_causaretiro (codcauret)
      on delete restrict on update restrict;

alter table sps_sueldos
   add constraint FK_SPS_SUEL_REFERENCE_SNO_PERS foreign key (codemp, codnom, codper)
      references sno_personalnomina (codemp, codnom, codper)
      on delete restrict on update restrict;

