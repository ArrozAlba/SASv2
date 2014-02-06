create table sps_anticipos
(
   codemp                         char(4)                        not null,
   codper                         char(10)                       not null,
   codnom                         char(4)                        not null,
   fecantper                      date                           not null,
   anoserper                      integer                        not null,
   messerper                      integer                        not null,
   diaserper                      integer                        not null,
   motant                         varchar(254)                   not null,
   mondeulab                      double(19,4)                   not null,
   monporant                      double(19,4)                   not null,
   estant                         varchar(1)                     not null default '0',
   estantant                      varchar(1)                     not null default '0',
   obsant                         varchar(254),
   primary key (codemp, codper, codnom, fecantper)
)
comment = "tabla que contiene los anticipos de prestaciones sociales pagados al personal"
type = innodb;

/*==============================================================*/
/* index: "reference_437_fk"                                            */
/*==============================================================*/
create index reference_437_fk
(
   codemp,
   codper,
   codnom
);

create table sps_antiguedad
(
   codemp                         char(4)                        not null,
   codper                         char(10)                       not null,
   codnom                         char(4)                        not null,
   fecant                         date                           not null,
   anoserant                      integer                        not null,
   messerant                      integer                        not null,
   salint                         double(19,4)                   not null default 0,
   salbasdia                      double(19,4)                   not null default 0,
   diabas                         integer                        not null,
   diacom                         integer                        not null,
   diaacu                         integer                        not null,
   monant                         double(19,4)                   not null default 0,
   monacuant                      double(19,4)                   not null default 0,
   monantant                      double(19,4)                   not null default 0,
   salparant                      double(19,4)                   not null default 0,
   fectas                         date                           not null,
   porint                         double(5,2)                    not null,
   diaint                         integer                        not null,
   monint                         double(19,4)                   not null default 0,
   monacuint                      double(19,4)                   not null default 0,
   saltotant                      double(19,4)                   not null,
   primary key (codemp, codper, codnom, fecant)
)
comment = "tabla donde se refleja todos los items de calculo de antiguedad de cada empleado.
art. 108 de la ley organica del trabajdor."
type = innodb;

/*==============================================================*/
/* index: "reference_435_fk"                                            */
/*==============================================================*/
create index reference_435_fk
(
   codemp,
   codper,
   codnom
);
/*==============================================================*/
/* index: "reference_5_fk"                                            */
/*==============================================================*/
create index reference_5_fk
(
   fectas
);

create table sps_articulos
(
   numart                         char(4)                        not null,
   fecvig                         date                           not null,
   numlitart                      char(2)                        not null,
   conart                         varchar(60)                    not null,
   operador                       char(1)                        not null,
   canmes                         integer                        not null,
   tiempo                         char(1)                        not null,
   diasal                         double                         not null,
   condicion                      char(4)                        not null,
   estacu                         char(1)                        not null,
   diaacu                         double                         not null,
   primary key (numart, fecvig, numlitart)
)
comment = "tabla que contiene los detalles de cada articulo, asi como tambien los paragrafos en caso de tenerlos."
type = innodb;

create table sps_causaretiro
(
   codcauret                      char(2)                        not null,
   dencauret                      varchar(50)                    not null,
   primary key (codcauret)
)
comment = "dicha tabla define las causas por la cual se puede retirar un trabajador de la empresa"
type = innodb;

create table sps_configuracion
(
   id                             char(1)                        not null,
   porant                         double(5,2)                    not null,
   estsue                         char(1)                        not null,
   primary key (id)
)
comment = "tabla que contiene los campos de configuracion del sistema"
type = innodb;

create table sps_deuda_anterior
(
   codemp                         char(4)                        not null,
   codper                         char(10)                       not null,
   codnom                         char(4)                        not null,
   feccordeuant                   date                           not null,
   deuantant                      double(19,4)                   not null default 0,
   deuantint                      double(19,4)                   not null default 0,
   antpag                         double(19,4)                   not null default 0,
   primary key (codemp, codper, codnom, feccordeuant)
)
comment = "tabla que registra los montos de deuda anteriores del empleado (q tenga de otra institucion o la deuda de transición a la fecha de reforma de la ley)"
type = innodb;

/*==============================================================*/
/* index: "reference_439_fk"                                            */
/*==============================================================*/
create index reference_439_fk
(
   codemp,
   codper,
   codnom
);

create table sps_dt_liquidacion
(
   codemp                         char(4)                        not null,
   codper                         char(10)                       not null,
   codnom                         char(4)                        not null,
   numliq                         char(10)                       not null,
   numart                         char(4)                        not null,
   fecvig                         date                           not null,
   numlitart                      char(2)                        not null,
   anoser                         integer                        not null,
   messer                         integer                        not null,
   diaser                         integer                        not null,
   salpro                         double(19,4)                   not null,
   diapag                         double(5,4),
   monasi                         double(19,4),
   monded                         double(19,4),
   subtotal                       double(19,4),
   primary key (codemp, codper, codnom, numliq, numart, fecvig, numlitart)
)
comment = "tabla que contiene los detalles de la liquidación"
type = innodb;

/*==============================================================*/
/* index: "reference_8_fk"                                            */
/*==============================================================*/
create index reference_8_fk
(
   codemp,
   codper,
   codnom,
   numliq
);
/*==============================================================*/
/* index: "reference_8_fk"                                            */
/*==============================================================*/
create index reference_8_fk
(
   numart,
   fecvig,
   numlitart
);

create table sps_liquidacion
(
   codemp                         char(4)                        not null,
   codper                         char(10)                       not null,
   codnom                         char(4)                        not null,
   numliq                         char(10)                       not null,
   codcauret                      char(2)                        not null,
   fecliq                         date                           not null,
   totasiliq                      double(19,4)                   not null,
   totdedliq                      double(19,4)                   not null,
   totpagliq                      double(19,4)                   not null,
   primary key (codemp, codper, codnom, numliq)
)
comment = "tabla que contiene las liquidaciones de los empleados"
type = innodb;

/*==============================================================*/
/* index: "reference_7_fk"                                            */
/*==============================================================*/
create index reference_7_fk
(
   codcauret
);
/*==============================================================*/
/* index: "reference_436_fk"                                            */
/*==============================================================*/
create index reference_436_fk
(
   codemp,
   codper,
   codnom
);

create table sps_sueldos
(
   codemp                         char(4)                        not null,
   codper                         char(10)                       not null,
   codnom                         char(4)                        not null,
   fecincsue                      date                           not null,
   monsuebas                      double(19,4),
   monsueint                      double(19,4),
   monsuenordia                   double(19,4),
   monpri                         double(19,4),
   primary key (codemp, codper, codnom, fecincsue)
)
type = innodb;

/*==============================================================*/
/* index: "reference_438_fk"                                            */
/*==============================================================*/
create index reference_438_fk
(
   codemp,
   codper,
   codnom
);

create table sps_tasa_interes
(
   fectas                         date                           not null,
   valtas                         double                         not null,
   primary key (fectas)
)
comment = "tabla que contiene las tasas porcentual mensual para el calculo de intereses, dichas tasas deben ser suministradas por el banco central de venezuela."
type = innodb;

alter table sps_anticipos add constraint fk_reference_437 foreign key (codemp, codper, codnom)
      references sno_personalnomina (codemp, codper, codnom) on delete restrict on update restrict;

alter table sps_antiguedad add constraint fk_reference_435 foreign key (codemp, codper, codnom)
      references sno_personalnomina (codemp, codper, codnom) on delete restrict on update restrict;

alter table sps_antiguedad add constraint fk_reference_5 foreign key (fectas)
      references sps_tasa_interes (fectas) on delete restrict on update restrict;

alter table sps_deuda_anterior add constraint fk_reference_439 foreign key (codemp, codper, codnom)
      references sno_personalnomina (codemp, codper, codnom) on delete restrict on update restrict;

alter table sps_dt_liquidacion add constraint fk_reference_8 foreign key (numart, fecvig, numlitart)
      references sps_articulos (numart, fecvig, numlitart) on delete restrict on update restrict;

alter table sps_dt_liquidacion add constraint fk_reference_8 foreign key (codemp, codper, codnom, numliq)
      references sps_liquidacion (codemp, codper, codnom, numliq) on delete restrict on update restrict;

alter table sps_liquidacion add constraint fk_reference_436 foreign key (codemp, codper, codnom)
      references sno_personalnomina (codemp, codper, codnom) on delete restrict on update restrict;

alter table sps_liquidacion add constraint fk_reference_7 foreign key (codcauret)
      references sps_causaretiro (codcauret) on delete restrict on update restrict;

alter table sps_sueldos add constraint fk_reference_438 foreign key (codemp, codper, codnom)
      references sno_personalnomina (codemp, codper, codnom) on delete restrict on update restrict;

