-- Testing Branch DEV
-- PostgreSQL database dump
--
SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--
CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;

--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';

SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE SEQUENCE sas_acceso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
--
-- Name: sas_acceso_id_seq; Type: SEQUENCE; Schema: public; Owner: tua
--
CREATE TABLE sas_acceso (
    id integer DEFAULT nextval('sas_acceso_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
    usuario_id integer NOT NULL,
    tipo_acceso integer NOT NULL,
    ip character varying(45) NULL,
);

--
-- Name: TABLE sas_acceso; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON TABLE sas_acceso IS 'Tabla que registra los accesos de los usuarios al sistema';
--
-- Name: COLUMN sas_acceso.creacion_uid; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.creacion_uid IS 'Codigo del Usuario Creador del Registro';
--
-- Name: COLUMN sas_acceso.creacion_fecha; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.creacion_fecha IS 'Fecha de Creación del Registro';
--
-- Name: COLUMN sas_acceso.edicion_fecha; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.edicion_fecha IS 'Fecha de Ultima Edición del Registro';
--
-- Name: COLUMN sas_acceso.edicion_uid; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';
--
-- Name: COLUMN sas_acceso.usuario_id; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.usuario_id IS 'Identificador del usuario que accede';
--
-- Name: COLUMN sas_acceso.tipo_acceso; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.tipo_acceso IS 'Tipo de acceso (entrata o salida)';
--
-- Name: COLUMN sas_acceso.ip; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.ip IS 'Dirección IP del usuario que ingresa';
--
-- Name: COLUMN sas_acceso.registrado_at; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_acceso.registrado_at IS 'Fecha de registro del acceso';
--
-- Name: sas_acceso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: tua
--

ALTER SEQUENCE sas_acceso_id_seq OWNED BY sas_acceso.id;

--
-- Name: sas_backup; Type: TABLE; Schema: public; Owner: tua; Tablespace: 
--
CREATE SEQUENCE sas_backup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_backup (
    id integer DEFAULT nextval('sas_backup_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
    usuario_id integer NOT NULL,
    denominacion character varying(200) NOT NULL,
    tamano character varying(45) NULL,
    archivo character varying(45) NOT NULL,
);

-- Name: TABLE sas_acceso; Type: COMMENT; Schema: public; Owner: tua
--
COMMENT ON TABLE sas_acceso IS 'Tabla que contiene las copias de seguridad del sistema';

ALTER SEQUENCE sas_backup_id_seq OWNED BY sas_backup.id;

--
-- Name: sas_ciudad; Type: TABLE; Schema: public; Owner: tua; Tablespace: 
--

CREATE SEQUENCE sas_ciudad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_ciudad (
  id integer DEFAULT nextval('sas_ciudad_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  ciudad character varying(45) NULL
);


COMMENT ON TABLE sas_ciudad IS 'Tabla que registra las ciudades del sistema';

--
-- Name: COLUMN sas_ciudad.creacion_uid; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_ciudad.creacion_uid IS 'Codigo del Usuario Creador del Registro';
--
-- Name: COLUMN sas_ciudad.creacion_fecha; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_ciudad.creacion_fecha IS 'Fecha de Creación del Registro';
--
-- Name: COLUMN sas_ciudad.edicion_fecha; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_ciudad.edicion_fecha IS 'Fecha de Ultima Edición del Registro';
--
-- Name: COLUMN sas_ciudad.edicion_uid; Type: COMMENT; Schema: public; Owner: tua
--

COMMENT ON COLUMN sas_ciudad.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';

--
-- Name: COLUMN sas_ciudad.ciudad; Type: COMMENT; Schema: public; Owner: tua
--
COMMENT ON COLUMN sas_ciudad.ciudad IS 'Nombre de la ciudad';

--
-- Name: sas_acceso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: tua
--

ALTER SEQUENCE sas_ciudad_id_seq OWNED BY sas_ciudad.id;

--
-- Name: TABLE sas_empresa; Type: COMMENT; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_empresa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_empresa (
  id integer DEFAULT nextval('sas_empresa_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  razon_social character varying(100) NOT NULL,
  siglas character varying(45) NULL,
  nit character varying(15) NOT NULL,
  dv integer NULL,
  representante_legal character varying(100) NOT NULL,
  nuip integer NOT NULL,
  tipo_nuip_id integer NOT NULL,
  pagina_web  character varying(45) NULL,
  logo character varying(45) NULL,
);

--
-- Name: TABLE sas_estado_usuario; Type: COMMENT; Schema: public; Owner: jelitox
--
CREATE SEQUENCE sas_estado_usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


CREATE TABLE sas_estado_usuario (
  id integer DEFAULT nextval('sas_estado_usuario_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  usuario_id integer NOT NULL, 
  estado_usuario integer NOT NULL,
  descripcion character varying(100) NOT NULL
);

CREATE SEQUENCE sas_menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_menu (
  id integer DEFAULT nextval('sas_menu_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  menu_id integer NULL,
  recurso_id integer NULL,
  menu character varying(45) NOT NULL,
  url character varying(45) NOT NULL,
  posicion integer default '0',
  icono character varying(45) NULL,
  activo integer NOT NULL default '1',
  visibilidad integer NOT NULL default '1'
);

CREATE SEQUENCE sas_perfil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_perfil (
  id integer DEFAULT nextval('sas_perfil_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  perfil character varying(45) NOT NULL, 
  estado integer default '1' NOT NULL, 
  plantilla character varying(45)  default 'default'
);

CREATE SEQUENCE sas_persona_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_persona (
  id integer DEFAULT nextval('sas_persona_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  nombre character varying(50) NOT NULL,
  apellido character varying(100) NOT NULL,
  nuip integer NOT NULL,
  tipo_nuip_id integer NOT NULL,
  telefono character varying(45) NULL,
  fotografia character varying(45) default 'default.png'
);

CREATE SEQUENCE sas_recurso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_recurso (
  id integer DEFAULT nextval('sas_recurso_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  modulo character varying(45) NULL,
  controlador character varying(45) NULL,
  accion character varying(45) NULL,
  recurso character varying(100) NULL,
  descripcion character varying(150) NOT NULL,
  activo integer NOT NULL default '1'
);


CREATE SEQUENCE sas_recurso_perfil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_recurso_perfil (
  id integer DEFAULT nextval('sas_recurso_perfil_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  recurso_id integer NOT NULL,
  perfil_id integer NOT NULL
);

--------------------------------------------------------------
CREATE SEQUENCE sas_sucursal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_sucursal (
  id integer DEFAULT nextval('sas_sucursal_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  empresa_id integer NOT NULL, 
  sucursal character varying(45) NOT NULL,
  sucursal_slug character varying(45) NULL,
  direccion character varying(45) NULL,
  telefono character varying(45) NULL,
  fax character varying(45) NULL,
  celular character varying(45) NULL,
  ciudad_id integer NOT NULL
);



CREATE SEQUENCE sas_tipo_nuip_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_tipo_nuip (
  id integer DEFAULT nextval('sas_tipo_nuip_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  tipo_nuip character varying(45) NOT NULL
  );


CREATE SEQUENCE sas_usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE sas_usuario (
  id integer DEFAULT nextval('sas_usuario_id_seq'::regclass) NOT NULL,
  creacion_uid integer,
  creacion_fecha timestamp without time zone,
  edicion_fecha timestamp without time zone,
  edicion_uid integer,
  sucursal_id integer NULL,
  persona_id integer NULL,
  login character varying(45) NOT NULL,
  password character varying(45) NOT NULL,
  perfil_id integer NOT NULL,
  email character varying(45) NULL,
  tema character varying(45) NULL,
  app_ajax integer default '1',
  datagrid integer default '30'
);


-- ----------------------------
-- Foreign Key structure for table "sas_acceso"
-- ----------------------------
ALTER TABLE ONLY sas_acceso
    ADD CONSTRAINT sas_acceso_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_backup"
-- ----------------------------
ALTER TABLE ONLY sas_backup
    ADD CONSTRAINT sas_backup_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_ciudad"
-- ----------------------------
ALTER TABLE ONLY sas_ciudad
    ADD CONSTRAINT sas_ciudad_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_empresa"
-- ----------------------------
ALTER TABLE ONLY sas_empresa
    ADD CONSTRAINT sas_empresa_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_estado_usuario"
-- ----------------------------
ALTER TABLE ONLY sas_estado_usuario
    ADD CONSTRAINT sas_estado_usuario_pkey PRIMARY KEY (id);


-- ----------------------------
-- Foreign Key structure for table "sas_menu"
-- ----------------------------
ALTER TABLE ONLY sas_menu
    ADD CONSTRAINT sas_menu_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_perfil"
-- ----------------------------
ALTER TABLE ONLY sas_perfil
    ADD CONSTRAINT sas_perfil_pkey PRIMARY KEY (id);


-- ----------------------------
-- Foreign Key structure for table "sas_persona"
-- ----------------------------
ALTER TABLE ONLY sas_persona
    ADD CONSTRAINT sas_persona_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_recurso"
-- ----------------------------
ALTER TABLE ONLY sas_recurso
    ADD CONSTRAINT sas_recurso_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_recurso_perfil"
-- ----------------------------
ALTER TABLE ONLY sas_recurso_perfil
    ADD CONSTRAINT sas_recurso_perfil_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_sucursal"
-- ----------------------------
ALTER TABLE ONLY sas_sucursal
    ADD CONSTRAINT sas_sucursal_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_tipo_nuip"
-- ----------------------------
ALTER TABLE ONLY sas_tipo_nuip
    ADD CONSTRAINT sas_tipo_nuip_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_usuario"
-- ----------------------------

ALTER TABLE ONLY sas_usuario
    ADD CONSTRAINT sas_usuario_pkey PRIMARY KEY (id);

