--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
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

--
-- Name: acceso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE acceso (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    tipo_acceso integer NOT NULL,
    ip character varying(45),
    registrado_at date
);


ALTER TABLE public.acceso OWNER TO jelitox;

--
-- Name: TABLE acceso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE acceso IS 'Modelo para manipular las empresas';


--
-- Name: COLUMN acceso.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.usuario_id IS 'Identificador del usuario que accede';


--
-- Name: COLUMN acceso.tipo_acceso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.tipo_acceso IS 'Tipo de acceso (entrada o salida)';


--
-- Name: COLUMN acceso.ip; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.ip IS 'Dirección IP del usuario que ingresa';


--
-- Name: COLUMN acceso.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.registrado_at IS 'Fecha de registro del acceso';


--
-- Name: acceso_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE acceso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.acceso_id_seq OWNER TO jelitox;

--
-- Name: acceso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE acceso_id_seq OWNED BY acceso.id;


--
-- Name: backup; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE backup (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    denominacion character varying(200) NOT NULL,
    tamano character varying(45),
    archivo character varying(45) NOT NULL,
    registrado_at date
);


ALTER TABLE public.backup OWNER TO jelitox;

--
-- Name: COLUMN backup.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN backup.usuario_id IS 'ID del Usuario';


--
-- Name: COLUMN backup.denominacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN backup.denominacion IS 'Denominacion del Backup';


--
-- Name: COLUMN backup.tamano; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN backup.tamano IS 'Tamaño del Backup';


--
-- Name: COLUMN backup.archivo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN backup.archivo IS 'Nombre del Archivo';


--
-- Name: backup_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE backup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.backup_id_seq OWNER TO jelitox;

--
-- Name: backup_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE backup_id_seq OWNED BY backup.id;


--
-- Name: ciudad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE ciudad (
    id integer NOT NULL,
    ciudad character varying(45),
    registrado_at character varying(45),
    modificado_in character varying(45)
);


ALTER TABLE public.ciudad OWNER TO jelitox;

--
-- Name: TABLE ciudad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE ciudad IS 'Tabla que registra las ciudades del sistema';


--
-- Name: COLUMN ciudad.ciudad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN ciudad.ciudad IS 'Nombre de la ciudad';


--
-- Name: ciudad_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE ciudad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ciudad_id_seq OWNER TO jelitox;

--
-- Name: ciudad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE ciudad_id_seq OWNED BY ciudad.id;


--
-- Name: empresa; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE empresa (
    id integer NOT NULL,
    razon_social character varying(100) NOT NULL,
    siglas character varying(45),
    nit character varying(15) NOT NULL,
    dv integer,
    representante_legal character varying(100) NOT NULL,
    nuip integer NOT NULL,
    tipo_nuip_id integer NOT NULL,
    pagina_web character varying(45),
    logo character varying(45),
    registrado_at character varying(45),
    modificado_in character varying(45)
);


ALTER TABLE public.empresa OWNER TO jelitox;

--
-- Name: COLUMN empresa.razon_social; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.razon_social IS 'Razon Social de la Empresa ';


--
-- Name: COLUMN empresa.siglas; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.siglas IS 'Siglas de la Empresa ';


--
-- Name: COLUMN empresa.nit; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.nit IS 'NIT de la Empresa ';


--
-- Name: COLUMN empresa.dv; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.dv IS 'DV de la Empresa ';


--
-- Name: COLUMN empresa.representante_legal; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.representante_legal IS 'Representante Legal de la Empresa ';


--
-- Name: COLUMN empresa.nuip; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.nuip IS '------ ';


--
-- Name: COLUMN empresa.tipo_nuip_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.tipo_nuip_id IS '--------- ';


--
-- Name: COLUMN empresa.pagina_web; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.pagina_web IS 'Pagina Web de la Empresa ';


--
-- Name: COLUMN empresa.logo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.logo IS 'Logo de la Empresa ';


--
-- Name: COLUMN empresa.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.registrado_at IS 'Registrado ';


--
-- Name: COLUMN empresa.modificado_in; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.modificado_in IS 'modificado ';


--
-- Name: empresa_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE empresa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.empresa_id_seq OWNER TO jelitox;

--
-- Name: empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE empresa_id_seq OWNED BY empresa.id;


--
-- Name: estado_usuario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE estado_usuario (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    estado_usuario integer NOT NULL,
    descripcion character varying(100) NOT NULL,
    fecha_estado_at date
);


ALTER TABLE public.estado_usuario OWNER TO jelitox;

--
-- Name: TABLE estado_usuario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE estado_usuario IS 'Modelo para manipular el estado de los usuarios';


--
-- Name: COLUMN estado_usuario.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado_usuario.usuario_id IS 'ID usuario ';


--
-- Name: COLUMN estado_usuario.estado_usuario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado_usuario.estado_usuario IS 'ID Estado del usuario ';


--
-- Name: COLUMN estado_usuario.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado_usuario.descripcion IS 'Descripcion del estado del usuario ';


--
-- Name: estado_usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE estado_usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estado_usuario_id_seq OWNER TO jelitox;

--
-- Name: estado_usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE estado_usuario_id_seq OWNED BY estado_usuario.id;


--
-- Name: menu; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE menu (
    id integer NOT NULL,
    menu_id integer,
    recurso_id integer,
    menu character varying(45) NOT NULL,
    url character varying(45) NOT NULL,
    posicion integer DEFAULT 0,
    icono character varying(45),
    activo integer DEFAULT 1 NOT NULL,
    visibilidad integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.menu OWNER TO jelitox;

--
-- Name: TABLE menu; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE menu IS 'Modelo para manipular menus del sistema';


--
-- Name: COLUMN menu.menu_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.menu_id IS 'ID menu padre';


--
-- Name: COLUMN menu.recurso_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.recurso_id IS 'ID del recurso ';


--
-- Name: COLUMN menu.menu; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.menu IS 'Texto a mostrar del menu';


--
-- Name: COLUMN menu.url; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.url IS 'Url del menu';


--
-- Name: COLUMN menu.posicion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.posicion IS 'Posicion del menu dentro de otros items';


--
-- Name: COLUMN menu.icono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.icono IS 'Icono a mostrar';


--
-- Name: COLUMN menu.activo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.activo IS 'Estado del menu (Activo o Inactivo)';


--
-- Name: COLUMN menu.visibilidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.visibilidad IS 'Indica si el menú se muestra en el backend o en el frontend';


--
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.menu_id_seq OWNER TO jelitox;

--
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE menu_id_seq OWNED BY menu.id;


--
-- Name: perfil; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE perfil (
    id integer NOT NULL,
    perfil character varying(45) NOT NULL,
    estado integer DEFAULT 1 NOT NULL,
    plantilla character varying(45) DEFAULT 'default'::character varying,
    registrado_at character varying(45)
);


ALTER TABLE public.perfil OWNER TO jelitox;

--
-- Name: TABLE perfil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE perfil IS 'Modelo para manipular perfiles del sistema';


--
-- Name: COLUMN perfil.perfil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.perfil IS 'Nombre del Perfil';


--
-- Name: COLUMN perfil.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.estado IS 'Indica si el perfil esta activo o inactivo';


--
-- Name: COLUMN perfil.plantilla; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.plantilla IS 'Plantilla para usar en el sistema';


--
-- Name: COLUMN perfil.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.registrado_at IS 'Fecha de registro del perfil';


--
-- Name: perfil_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE perfil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.perfil_id_seq OWNER TO jelitox;

--
-- Name: perfil_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE perfil_id_seq OWNED BY perfil.id;


--
-- Name: persona; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE persona (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    apellido character varying(100) NOT NULL,
    nuip integer NOT NULL,
    tipo_nuip_id integer NOT NULL,
    telefono character varying(45),
    fotografia character varying(45) DEFAULT 'default.png'::character varying,
    registrado_at character varying(45),
    modificado_in character varying(45)
);


ALTER TABLE public.persona OWNER TO jelitox;

--
-- Name: TABLE persona; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE persona IS 'Modelo para manipular persona';


--
-- Name: COLUMN persona.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.nombre IS 'Nombre';


--
-- Name: COLUMN persona.apellido; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.apellido IS 'apellido';


--
-- Name: COLUMN persona.nuip; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.nuip IS 'numero de identificacion personal';


--
-- Name: COLUMN persona.tipo_nuip_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.tipo_nuip_id IS 'tipo de identificacion';


--
-- Name: COLUMN persona.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.telefono IS 'telefono';


--
-- Name: COLUMN persona.fotografia; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.fotografia IS 'fotografia';


--
-- Name: COLUMN persona.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.registrado_at IS 'Fecha de Registro';


--
-- Name: COLUMN persona.modificado_in; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.modificado_in IS 'Fecha de Modificacion del Registro';


--
-- Name: persona_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE persona_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.persona_id_seq OWNER TO jelitox;

--
-- Name: persona_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE persona_id_seq OWNED BY persona.id;


--
-- Name: recurso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recurso (
    id integer NOT NULL,
    modulo character varying(45),
    controlador character varying(45),
    accion character varying(45),
    recurso character varying(100),
    descripcion character varying(150) NOT NULL,
    activo integer DEFAULT 1 NOT NULL,
    registrado_at character varying(45)
);


ALTER TABLE public.recurso OWNER TO jelitox;

--
-- Name: TABLE recurso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recurso IS 'Modelo para manipular recursos (controladores)';


--
-- Name: COLUMN recurso.modulo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.modulo IS 'Nombre del Modulo';


--
-- Name: COLUMN recurso.controlador; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.controlador IS 'Nombre del Controlador';


--
-- Name: COLUMN recurso.accion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.accion IS 'Nombre de la Accion';


--
-- Name: COLUMN recurso.recurso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.recurso IS 'Nombre del recurso';


--
-- Name: COLUMN recurso.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.descripcion IS 'Descripcion del Recurso';


--
-- Name: COLUMN recurso.activo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.activo IS 'Estado del Recurso';


--
-- Name: COLUMN recurso.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.registrado_at IS 'Estado del Recurso';


--
-- Name: recurso_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recurso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recurso_id_seq OWNER TO jelitox;

--
-- Name: recurso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recurso_id_seq OWNED BY recurso.id;


--
-- Name: recurso_perfil; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recurso_perfil (
    id integer NOT NULL,
    recurso_id integer NOT NULL,
    perfil_id integer NOT NULL,
    registrado_at date,
    modificado_in date
);


ALTER TABLE public.recurso_perfil OWNER TO jelitox;

--
-- Name: TABLE recurso_perfil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recurso_perfil IS 'Modelo para manipular relacion Recurso - Perfil';


--
-- Name: COLUMN recurso_perfil.recurso_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.recurso_id IS 'ID del Recurso';


--
-- Name: COLUMN recurso_perfil.perfil_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.perfil_id IS 'ID del Perfil';


--
-- Name: COLUMN recurso_perfil.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.registrado_at IS 'Fecha Registro del Perfil';


--
-- Name: COLUMN recurso_perfil.modificado_in; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.modificado_in IS 'Fecha de Modificacion del Perfil';


--
-- Name: recurso_perfil_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recurso_perfil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recurso_perfil_id_seq OWNER TO jelitox;

--
-- Name: recurso_perfil_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recurso_perfil_id_seq OWNED BY recurso_perfil.id;


--
-- Name: sucursal; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sucursal (
    id integer NOT NULL,
    empresa_id integer NOT NULL,
    sucursal character varying(45) NOT NULL,
    sucursal_slug character varying(45),
    direccion character varying(45),
    telefono character varying(45),
    fax character varying(45),
    celular character varying(45),
    ciudad_id integer NOT NULL,
    registrado_at character varying(45),
    modificado_in character varying(45)
);


ALTER TABLE public.sucursal OWNER TO jelitox;

--
-- Name: TABLE sucursal; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sucursal IS 'Modelo para manipular las sucursales';


--
-- Name: COLUMN sucursal.empresa_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.empresa_id IS 'ID de la Empresa';


--
-- Name: COLUMN sucursal.sucursal; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.sucursal IS 'Nombre de la Sucursal';


--
-- Name: COLUMN sucursal.sucursal_slug; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.sucursal_slug IS 'Slug de la sucursal';


--
-- Name: COLUMN sucursal.direccion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.direccion IS 'Direccion de la Sucursal';


--
-- Name: COLUMN sucursal.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.telefono IS 'Telefono de la Sucursal';


--
-- Name: COLUMN sucursal.fax; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.fax IS 'fax de la Sucursal';


--
-- Name: COLUMN sucursal.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.celular IS 'fax de la Sucursal';


--
-- Name: COLUMN sucursal.ciudad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.ciudad_id IS 'Id de la Ciudad';


--
-- Name: COLUMN sucursal.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.registrado_at IS 'Fecha Registro del Perfil';


--
-- Name: COLUMN sucursal.modificado_in; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.modificado_in IS 'Fecha de Modificacion del Perfil';


--
-- Name: sucursal_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sucursal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sucursal_id_seq OWNER TO jelitox;

--
-- Name: sucursal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sucursal_id_seq OWNED BY sucursal.id;


--
-- Name: tipo_nuip; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE tipo_nuip (
    id integer NOT NULL,
    tipo_nuip character varying(45) NOT NULL
);


ALTER TABLE public.tipo_nuip OWNER TO jelitox;

--
-- Name: TABLE tipo_nuip; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE tipo_nuip IS 'Modelo para manipular Tipos de NUIP';


--
-- Name: COLUMN tipo_nuip.tipo_nuip; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tipo_nuip.tipo_nuip IS 'Tipo de NUIP';


--
-- Name: tipo_nuip_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE tipo_nuip_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_nuip_id_seq OWNER TO jelitox;

--
-- Name: tipo_nuip_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE tipo_nuip_id_seq OWNED BY tipo_nuip.id;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE usuario (
    id integer NOT NULL,
    sucursal_id integer,
    persona_id integer,
    login character varying(45) NOT NULL,
    password character varying(45) NOT NULL,
    perfil_id integer NOT NULL,
    email character varying(45),
    tema character varying(45),
    app_ajax integer DEFAULT 1,
    datagrid integer DEFAULT 30,
    registrado_at date,
    modificado_in date
);


ALTER TABLE public.usuario OWNER TO jelitox;

--
-- Name: TABLE usuario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE usuario IS 'Modelo para manipular los usuarios';


--
-- Name: COLUMN usuario.sucursal_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.sucursal_id IS 'ID de la Sucursal';


--
-- Name: COLUMN usuario.persona_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.persona_id IS 'ID de la Persona';


--
-- Name: COLUMN usuario.login; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.login IS 'Login del usuario';


--
-- Name: COLUMN usuario.password; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.password IS 'Password del usuario';


--
-- Name: COLUMN usuario.perfil_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.perfil_id IS 'ID Perfil de Usuario';


--
-- Name: COLUMN usuario.email; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.email IS 'Email del usuario';


--
-- Name: COLUMN usuario.tema; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.tema IS 'Tema de la interfaz aplicable al usuario';


--
-- Name: COLUMN usuario.app_ajax; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.app_ajax IS 'Indica si la app se trabaja con ajax o peticiones normales';


--
-- Name: COLUMN usuario.datagrid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.datagrid IS 'Datos por página en los datagrid';


--
-- Name: COLUMN usuario.registrado_at; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.registrado_at IS 'Fecha de registro del usuario';


--
-- Name: COLUMN usuario.modificado_in; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.modificado_in IS 'Fecha de modificacion';


--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_id_seq OWNER TO jelitox;

--
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE usuario_id_seq OWNED BY usuario.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY acceso ALTER COLUMN id SET DEFAULT nextval('acceso_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY backup ALTER COLUMN id SET DEFAULT nextval('backup_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY ciudad ALTER COLUMN id SET DEFAULT nextval('ciudad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa ALTER COLUMN id SET DEFAULT nextval('empresa_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY estado_usuario ALTER COLUMN id SET DEFAULT nextval('estado_usuario_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY menu ALTER COLUMN id SET DEFAULT nextval('menu_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY perfil ALTER COLUMN id SET DEFAULT nextval('perfil_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona ALTER COLUMN id SET DEFAULT nextval('persona_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recurso ALTER COLUMN id SET DEFAULT nextval('recurso_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recurso_perfil ALTER COLUMN id SET DEFAULT nextval('recurso_perfil_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal ALTER COLUMN id SET DEFAULT nextval('sucursal_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY tipo_nuip ALTER COLUMN id SET DEFAULT nextval('tipo_nuip_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY usuario ALTER COLUMN id SET DEFAULT nextval('usuario_id_seq'::regclass);


--
-- Data for Name: acceso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY acceso (id, usuario_id, tipo_acceso, ip, registrado_at) FROM stdin;
1	2	2	127.0.0.1	2014-03-03
\.


--
-- Name: acceso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('acceso_id_seq', 1, true);


--
-- Data for Name: backup; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY backup (id, usuario_id, denominacion, tamano, archivo, registrado_at) FROM stdin;
1	2	Sistema inicial	4,09 KB	backup-1.sql.gz	2013-01-01
\.


--
-- Name: backup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('backup_id_seq', 1, false);


--
-- Data for Name: ciudad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY ciudad (id, ciudad, registrado_at, modificado_in) FROM stdin;
1	Acarigua	2013-01-01 00:00:01	\N
\.


--
-- Name: ciudad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('ciudad_id_seq', 1, true);


--
-- Data for Name: empresa; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY empresa (id, razon_social, siglas, nit, dv, representante_legal, nuip, tipo_nuip_id, pagina_web, logo, registrado_at, modificado_in) FROM stdin;
1	Empresa Mixta Socialista Arroz del Alba	S.A.	1091652165	6	Francisco Ortiz	1091652165	1	www.arrozdelalba.gob.ve	default.png	2013-01-01 00:00:01	\N
\.


--
-- Name: empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('empresa_id_seq', 1, false);


--
-- Data for Name: estado_usuario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY estado_usuario (id, usuario_id, estado_usuario, descripcion, fecha_estado_at) FROM stdin;
1	1	2	Bloqueado por ser un usuario sin privilegios	2013-01-01
2	2	1	Activo por ser el Super Usuario del sistema	2013-01-01
\.


--
-- Name: estado_usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('estado_usuario_id_seq', 1, false);


--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY menu (id, menu_id, recurso_id, menu, url, posicion, icono, activo, visibilidad) FROM stdin;
1	\N	\N	Dashboard	#	10	icon-home	1	1
2	1	2	Dashboard	dashboard/	11	icon-home	1	1
3	\N	\N	Sistema	#	900	icon-cogs	1	1
4	3	4	Accesos	sistema/acceso/listar/	901	icon-exchange	1	1
5	3	5	Auditorías	sistema/auditoria/	902	icon-eye-open	1	1
6	3	6	Backups	sistema/backup/listar/	903	icon-hdd	1	1
7	3	7	Mantenimiento	sistema/mantenimiento/	904	icon-bolt	1	1
8	3	8	Menús	sistema/menu/listar/	905	icon-list	1	1
9	3	9	Perfiles	sistema/perfil/listar/	906	icon-group	1	1
10	3	10	Permisos	sistema/privilegio/listar/	907	icon-magic	1	1
11	3	11	Recursos	sistema/recurso/listar/	908	icon-lock	1	1
12	3	12	Usuarios	sistema/usuario/listar/	909	icon-user	1	1
13	3	13	Visor de sucesos	sistema/sucesos/	910	icon-filter	1	1
14	3	14	Sistema	sistema/configuracion/	911	icon-wrench	1	1
15	\N	\N	Configuraciones	#	800	icon-wrench	1	1
16	15	15	Empresa	config/empresa/	801	icon-briefcase	1	1
17	15	16	Sucursales	config/sucursal/listar/	802	icon-sitemap	1	1
\.


--
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('menu_id_seq', 1, false);


--
-- Data for Name: perfil; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY perfil (id, perfil, estado, plantilla, registrado_at) FROM stdin;
1	Super Usuario	1	default	2013-01-01 00:00:01
\.


--
-- Name: perfil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('perfil_id_seq', 1, false);


--
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY persona (id, nombre, apellido, nuip, tipo_nuip_id, telefono, fotografia, registrado_at, modificado_in) FROM stdin;
1	Error	Error	1010101010	1	\N	default.png	2013-01-01 00:00:01	\N
2	Javier Enrique	León	1091652165	1	\N	default.png	2013-01-01 00:00:01	\N
\.


--
-- Name: persona_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('persona_id_seq', 1, false);


--
-- Data for Name: recurso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recurso (id, modulo, controlador, accion, recurso, descripcion, activo, registrado_at) FROM stdin;
1	*	\N	\N	*	Comodín para la administración total (usar con cuidado)	1	2013-01-01 00:00:01
2	dashboard	*	*	dashboard/*/*	Página principal del sistema	1	2013-01-01 00:00:01
3	sistema	mi_cuenta	*	sistema/mi_cuenta/*	Gestión de la cuenta del usuario logueado	1	2013-01-01 00:00:01
4	sistema	acceso	*	sistema/acceso/*	Submódulo para la gestión de ingresos al sistema	1	2013-01-01 00:00:01
5	sistema	auditoria	*	sistema/auditoria/*	Submódulo para el control de las acciones de los usuarios	1	2013-01-01 00:00:01
6	sistema	backup	*	sistema/backup/*	Submódulo para la gestión de las copias de seguridad	1	2013-01-01 00:00:01
7	sistema	mantenimiento	*	sistema/mantenimiento/*	Submódulo para el mantenimiento de las tablas	1	2013-01-01 00:00:01
8	sistema	menu	*	sistema/menu/*	Submódulo del sistema para la creación de menús	1	2013-01-01 00:00:01
9	sistema	perfil	*	sistema/perfil/*	Submódulo del sistema para los perfiles de usuarios	1	2013-01-01 00:00:01
10	sistema	privilegio	*	sistema/privilegio/*	Submódulo del sistema para asignar recursos a los perfiles	1	2013-01-01 00:00:01
11	sistema	recurso	*	sistema/recurso/*	Submódulo del sistema para la gestión de los recursos	1	2013-01-01 00:00:01
12	sistema	usuario	*	sistema/usuario/*	Submódulo para la administración de los usuarios del sistema	1	2013-01-01 00:00:01
13	sistema	sucesos	*	sistema/suceso/*	Submódulo para el listado de los logs del sistema	1	2013-01-01 00:00:01
14	sistema	configuracion	*	sistema/configuracion/*	Submódulo para la configuración de la aplicación (.ini)	1	2013-01-01 00:00:01
15	config	empresa	*	config/empresa/*	Submódulo para la configuración de la información de la empresa	1	2013-01-01 00:00:01
16	config	sucursal	*	config/sucursal/*	Submódulo para la administración de las sucursales	1	2013-01-01 00:00:01
\.


--
-- Name: recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recurso_id_seq', 1, false);


--
-- Data for Name: recurso_perfil; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recurso_perfil (id, recurso_id, perfil_id, registrado_at, modificado_in) FROM stdin;
1	1	1	2013-01-01	\N
\.


--
-- Name: recurso_perfil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recurso_perfil_id_seq', 1, false);


--
-- Data for Name: sucursal; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sucursal (id, empresa_id, sucursal, sucursal_slug, direccion, telefono, fax, celular, ciudad_id, registrado_at, modificado_in) FROM stdin;
1	1	Oficina Principal	oficina-principal	Dirección	3162404183	3162404183	3162404183	1	2013-01-01 00:00:01	\N
\.


--
-- Name: sucursal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sucursal_id_seq', 1, true);


--
-- Data for Name: tipo_nuip; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY tipo_nuip (id, tipo_nuip) FROM stdin;
1	C.C.
2	C.E.
3	PAS.
4	T.I.
5	N.D.
\.


--
-- Name: tipo_nuip_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('tipo_nuip_id_seq', 1, false);


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY usuario (id, sucursal_id, persona_id, login, password, perfil_id, email, tema, app_ajax, datagrid, registrado_at, modificado_in) FROM stdin;
1	\N	1	error	963db57a0088931e0e3627b1e73e6eb5	1	\N	default	1	30	2013-01-01	\N
2	\N	2	admin	d93a5def7511da3d0f2d171d9c344e91	1	\N	default	1	30	2013-01-01	\N
\.


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('usuario_id_seq', 1, false);


--
-- Name: acceso_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY acceso
    ADD CONSTRAINT acceso_pkey PRIMARY KEY (id);


--
-- Name: backup_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY backup
    ADD CONSTRAINT backup_pkey PRIMARY KEY (id);


--
-- Name: ciudad_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY ciudad
    ADD CONSTRAINT ciudad_pkey PRIMARY KEY (id);


--
-- Name: empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_pkey PRIMARY KEY (id);


--
-- Name: estado_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY estado_usuario
    ADD CONSTRAINT estado_usuario_pkey PRIMARY KEY (id);


--
-- Name: menu_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT menu_pkey PRIMARY KEY (id);


--
-- Name: perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY perfil
    ADD CONSTRAINT perfil_pkey PRIMARY KEY (id);


--
-- Name: persona_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_pkey PRIMARY KEY (id);


--
-- Name: recurso_perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recurso_perfil
    ADD CONSTRAINT recurso_perfil_pkey PRIMARY KEY (id);


--
-- Name: recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recurso
    ADD CONSTRAINT recurso_pkey PRIMARY KEY (id);


--
-- Name: sucursal_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_pkey PRIMARY KEY (id);


--
-- Name: tipo_nuip_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY tipo_nuip
    ADD CONSTRAINT tipo_nuip_pkey PRIMARY KEY (id);


--
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id);


--
-- Name: usuario_perfil_idx; Type: INDEX; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE INDEX usuario_perfil_idx ON usuario USING btree (perfil_id);


--
-- Name: usuario_persona_idx; Type: INDEX; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE INDEX usuario_persona_idx ON usuario USING btree (persona_id);


--
-- Name: usuario_sucursal_idx; Type: INDEX; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE INDEX usuario_sucursal_idx ON usuario USING btree (sucursal_id);


--
-- Name: acceso_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY acceso
    ADD CONSTRAINT acceso_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: backup_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY backup
    ADD CONSTRAINT backup_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: empresa_tipo_nuip_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_tipo_nuip_fkey FOREIGN KEY (tipo_nuip_id) REFERENCES tipo_nuip(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: estado_usuario_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY estado_usuario
    ADD CONSTRAINT estado_usuario_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: menu_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT menu_menu_id_fkey FOREIGN KEY (menu_id) REFERENCES menu(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: menu_recurso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT menu_recurso_id_fkey FOREIGN KEY (recurso_id) REFERENCES recurso(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: persona_tipo_nuip_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_tipo_nuip_fkey FOREIGN KEY (tipo_nuip_id) REFERENCES tipo_nuip(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recurso_perfil_perfil_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recurso_perfil
    ADD CONSTRAINT recurso_perfil_perfil_fkey FOREIGN KEY (perfil_id) REFERENCES perfil(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recurso_perfil_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recurso_perfil
    ADD CONSTRAINT recurso_perfil_recurso_fkey FOREIGN KEY (recurso_id) REFERENCES recurso(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_ciudad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_ciudad_fkey FOREIGN KEY (ciudad_id) REFERENCES ciudad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_empresa_fkey FOREIGN KEY (empresa_id) REFERENCES empresa(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: usuario_perfil_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_perfil_fkey FOREIGN KEY (perfil_id) REFERENCES perfil(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: usuario_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_persona_fkey FOREIGN KEY (persona_id) REFERENCES persona(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: usuario_sucursal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_sucursal_fkey FOREIGN KEY (sucursal_id) REFERENCES sucursal(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

