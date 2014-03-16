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
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    tipo_acceso integer NOT NULL,
    navegador character varying(45),
    version_navegador character varying(45),
    sistema_operativo character varying(45),
    nombre_equipo character varying(45),
    ip character varying(45)
);


ALTER TABLE public.acceso OWNER TO jelitox;

--
-- Name: TABLE acceso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE acceso IS 'Modelo para manipular los  accesos de usuarios';


--
-- Name: COLUMN acceso.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.usuario_id IS 'Identificador del usuario que accede';


--
-- Name: COLUMN acceso.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.fecha_registro IS 'Fecha de registro del acceso';


--
-- Name: COLUMN acceso.tipo_acceso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.tipo_acceso IS 'Tipo de acceso (entrada o salida)';


--
-- Name: COLUMN acceso.navegador; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.navegador IS 'Navegador del Cliente';


--
-- Name: COLUMN acceso.version_navegador; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.version_navegador IS 'Version del Navegador del Cliente';


--
-- Name: COLUMN acceso.sistema_operativo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.sistema_operativo IS 'Sistema Operativo del Cliente';


--
-- Name: COLUMN acceso.nombre_equipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.nombre_equipo IS 'Nombre del Equipo';


--
-- Name: COLUMN acceso.ip; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN acceso.ip IS 'Dirección IP del usuario que ingresa';


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
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    denominacion character varying(200) NOT NULL,
    tamano character varying(45),
    archivo character varying(45) NOT NULL
);


ALTER TABLE public.backup OWNER TO jelitox;

--
-- Name: TABLE backup; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE backup IS 'Modelo para manipular los Backups generados por el sistema';


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
-- Name: beneficiario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE beneficiario (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    titular_id integer NOT NULL,
    persona_id integer NOT NULL,
    parentesco character varying(1) DEFAULT 'M'::character varying NOT NULL,
    beneficiario_tipo_id integer NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.beneficiario OWNER TO jelitox;

--
-- Name: TABLE beneficiario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE beneficiario IS 'Modelo para manipular los beneficiarios';


--
-- Name: COLUMN beneficiario.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN beneficiario.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN beneficiario.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN beneficiario.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario.titular_id IS 'Empleado Titular';


--
-- Name: COLUMN beneficiario.parentesco; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario.parentesco IS 'Parentesco del beneficiario';


--
-- Name: COLUMN beneficiario.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario.observacion IS 'Observacion';


--
-- Name: beneficiario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE beneficiario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.beneficiario_id_seq OWNER TO jelitox;

--
-- Name: beneficiario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE beneficiario_id_seq OWNED BY beneficiario.id;


--
-- Name: beneficiario_tipo; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE beneficiario_tipo (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    descripcion character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.beneficiario_tipo OWNER TO jelitox;

--
-- Name: TABLE beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE beneficiario_tipo IS 'Modelo para manipular los Tipos de Beneficiarios';


--
-- Name: COLUMN beneficiario_tipo.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario_tipo.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN beneficiario_tipo.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario_tipo.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN beneficiario_tipo.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario_tipo.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN beneficiario_tipo.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario_tipo.descripcion IS 'Descripcion del Tipo de Beneficiario';


--
-- Name: COLUMN beneficiario_tipo.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN beneficiario_tipo.observacion IS 'Observacion';


--
-- Name: beneficiario_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE beneficiario_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.beneficiario_tipo_id_seq OWNER TO jelitox;

--
-- Name: beneficiario_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE beneficiario_tipo_id_seq OWNED BY beneficiario_tipo.id;


--
-- Name: cargo; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE cargo (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.cargo OWNER TO jelitox;

--
-- Name: TABLE cargo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE cargo IS 'Modelo para manipular las diferentes Profesiones';


--
-- Name: COLUMN cargo.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cargo.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN cargo.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cargo.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN cargo.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cargo.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN cargo.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cargo.nombre IS 'Nombre de la Profesion';


--
-- Name: COLUMN cargo.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cargo.observacion IS 'Observacion';


--
-- Name: cargo_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE cargo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cargo_id_seq OWNER TO jelitox;

--
-- Name: cargo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE cargo_id_seq OWNED BY cargo.id;


--
-- Name: cobertura; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE cobertura (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    descripcion character varying(30) NOT NULL,
    tipo_cobertura character varying(1) NOT NULL,
    monto_cobertura numeric(11,2) DEFAULT 0.0 NOT NULL,
    fecha_inicio date DEFAULT '1900-01-01'::date,
    fecha_fin date DEFAULT '1900-01-01'::date,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.cobertura OWNER TO jelitox;

--
-- Name: TABLE cobertura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE cobertura IS 'Modelo para manipular las Coberturas';


--
-- Name: COLUMN cobertura.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN cobertura.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN cobertura.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN cobertura.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.descripcion IS 'Descripcion de la cobertura';


--
-- Name: COLUMN cobertura.tipo_cobertura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.tipo_cobertura IS 'Tipo de Cobertura (G-Grupal,I-Individual)';


--
-- Name: COLUMN cobertura.monto_cobertura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.monto_cobertura IS 'Monto de la Cobertura';


--
-- Name: COLUMN cobertura.fecha_inicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.fecha_inicio IS 'Fecha de Inicio de la cobertura';


--
-- Name: COLUMN cobertura.fecha_fin; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.fecha_fin IS 'Fecha de Fin de la cobertura';


--
-- Name: COLUMN cobertura.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN cobertura.observacion IS 'Observacion de la Cobertura';


--
-- Name: cobertura_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE cobertura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cobertura_id_seq OWNER TO jelitox;

--
-- Name: cobertura_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE cobertura_id_seq OWNED BY cobertura.id;


--
-- Name: departamento; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE departamento (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL,
    sucursal_id integer NOT NULL
);


ALTER TABLE public.departamento OWNER TO jelitox;

--
-- Name: TABLE departamento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE departamento IS 'Modelo para manipular los diferentes Departamentos de las UPSAS';


--
-- Name: COLUMN departamento.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN departamento.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN departamento.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN departamento.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN departamento.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN departamento.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN departamento.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN departamento.nombre IS 'Nombre del Departamento';


--
-- Name: COLUMN departamento.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN departamento.observacion IS 'Observacion';


--
-- Name: departamento_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE departamento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.departamento_id_seq OWNER TO jelitox;

--
-- Name: departamento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE departamento_id_seq OWNED BY departamento.id;


--
-- Name: discapacidad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE discapacidad (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.discapacidad OWNER TO jelitox;

--
-- Name: TABLE discapacidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE discapacidad IS 'Modelo para manipular los diferentes Tipos de Discapacidades';


--
-- Name: COLUMN discapacidad.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN discapacidad.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN discapacidad.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN discapacidad.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad.nombre IS 'Nombre de la Discapacidad';


--
-- Name: COLUMN discapacidad.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad.observacion IS 'Observacion';


--
-- Name: discapacidad_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE discapacidad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.discapacidad_id_seq OWNER TO jelitox;

--
-- Name: discapacidad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE discapacidad_id_seq OWNED BY discapacidad.id;


--
-- Name: discapacidad_persona; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE discapacidad_persona (
    id integer NOT NULL,
    persona_id integer NOT NULL,
    discapacidad_id integer NOT NULL
);


ALTER TABLE public.discapacidad_persona OWNER TO jelitox;

--
-- Name: TABLE discapacidad_persona; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE discapacidad_persona IS 'Modelo para manipular la relacion Discapacidad-Persona';


--
-- Name: COLUMN discapacidad_persona.persona_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad_persona.persona_id IS 'ID de la Persona';


--
-- Name: COLUMN discapacidad_persona.discapacidad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN discapacidad_persona.discapacidad_id IS 'ID de la Discapacidad';


--
-- Name: discapacidad_persona_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE discapacidad_persona_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.discapacidad_persona_id_seq OWNER TO jelitox;

--
-- Name: discapacidad_persona_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE discapacidad_persona_id_seq OWNED BY discapacidad_persona.id;


--
-- Name: empresa; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE empresa (
    id integer NOT NULL,
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    razon_social character varying(100) NOT NULL,
    rif character varying(15) NOT NULL,
    pais_id integer NOT NULL,
    estado_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    representante_legal character varying(100) NOT NULL,
    pagina_web character varying(45),
    telefono character varying(15) NOT NULL,
    fax character varying(15),
    celular character varying(15),
    logo character varying(45),
    email character varying(100)
);


ALTER TABLE public.empresa OWNER TO jelitox;

--
-- Name: TABLE empresa; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE empresa IS 'Modelo para manipular la empresa';


--
-- Name: COLUMN empresa.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN empresa.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN empresa.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN empresa.razon_social; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.razon_social IS 'Razon Social de la Empresa ';


--
-- Name: COLUMN empresa.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.pais_id IS 'ID Pais';


--
-- Name: COLUMN empresa.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.estado_id IS 'ID Estado';


--
-- Name: COLUMN empresa.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.municipio_id IS 'ID Municipio ';


--
-- Name: COLUMN empresa.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.parroquia_id IS 'ID Parroquia ';


--
-- Name: COLUMN empresa.representante_legal; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.representante_legal IS 'Representante Legal de la Empresa ';


--
-- Name: COLUMN empresa.pagina_web; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.pagina_web IS 'Pagina Web de la Empresa ';


--
-- Name: COLUMN empresa.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.telefono IS 'Telefono de la Empresa ';


--
-- Name: COLUMN empresa.fax; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.fax IS 'Telefax de la Empresa ';


--
-- Name: COLUMN empresa.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.celular IS 'Telefono Celular del Representante Legal ';


--
-- Name: COLUMN empresa.logo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.logo IS 'Logo de la Empresa ';


--
-- Name: COLUMN empresa.email; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN empresa.email IS 'Correo Electronico de la Empresa
';


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
-- Name: especialidad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE especialidad (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.especialidad OWNER TO jelitox;

--
-- Name: TABLE especialidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE especialidad IS 'Modelo para manipular las Especialidades';


--
-- Name: COLUMN especialidad.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN especialidad.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN especialidad.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN especialidad.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad.descripcion IS 'Descripcion de la Especialidad';


--
-- Name: COLUMN especialidad.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad.observacion IS 'Observacion de la Especialidad';


--
-- Name: especialidad_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE especialidad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.especialidad_id_seq OWNER TO jelitox;

--
-- Name: especialidad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE especialidad_id_seq OWNED BY especialidad.id;


--
-- Name: proveedor; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE proveedor (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    rif character varying(10) NOT NULL,
    razon_social character varying(30) NOT NULL,
    nombre_corto character varying(30) NOT NULL,
    pais_id integer NOT NULL,
    estado_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    direccion character varying(250) NOT NULL,
    celular character varying(12),
    telefono1 character varying(12),
    telefono2 character varying(12),
    fax character varying(12),
    correo_electronico character varying(64),
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.proveedor OWNER TO jelitox;

--
-- Name: TABLE proveedor; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE proveedor IS 'Modelo para manipular los Proveedores';


--
-- Name: COLUMN proveedor.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN proveedor.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN proveedor.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN proveedor.rif; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.rif IS 'Rif del Proveedor';


--
-- Name: COLUMN proveedor.razon_social; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.razon_social IS 'Razon Social del Proveedor';


--
-- Name: COLUMN proveedor.nombre_corto; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.nombre_corto IS 'Nombre Corto Proveedor';


--
-- Name: COLUMN proveedor.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.pais_id IS 'Pais Origen del Proveedor';


--
-- Name: COLUMN proveedor.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.estado_id IS 'Estado de Origen del Proveedor';


--
-- Name: COLUMN proveedor.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.municipio_id IS 'Municipio de Origen del Proveedor';


--
-- Name: COLUMN proveedor.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.parroquia_id IS 'Parroquia de Origen del Proveedor';


--
-- Name: COLUMN proveedor.direccion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.direccion IS 'Direccion del Proveedor';


--
-- Name: COLUMN proveedor.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.celular IS 'N° de Celular del Proveedor';


--
-- Name: COLUMN proveedor.telefono1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.telefono1 IS 'N° de Telefono del Proveedor';


--
-- Name: COLUMN proveedor.telefono2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.telefono2 IS 'N° de Telefono del Proveedor';


--
-- Name: COLUMN proveedor.fax; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.fax IS 'N° de Fax del Proveedor';


--
-- Name: COLUMN proveedor.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.correo_electronico IS 'Direccion de Correo Electronico del Proveedor';


--
-- Name: COLUMN proveedor.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor.observacion IS 'Observacion';


--
-- Name: proveedor_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE proveedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.proveedor_id_seq OWNER TO jelitox;

--
-- Name: proveedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE proveedor_id_seq OWNED BY proveedor.id;


--
-- Name: especialidad_medico; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE especialidad_medico (
    id integer DEFAULT nextval('proveedor_id_seq'::regclass) NOT NULL,
    medico_id integer NOT NULL,
    especialidad_id integer NOT NULL
);


ALTER TABLE public.especialidad_medico OWNER TO jelitox;

--
-- Name: TABLE especialidad_medico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE especialidad_medico IS 'Modelo para manipular la relacion especialidad-proveedors';


--
-- Name: COLUMN especialidad_medico.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad_medico.medico_id IS 'ID del medico';


--
-- Name: COLUMN especialidad_medico.especialidad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN especialidad_medico.especialidad_id IS 'ID de la especialidad';


--
-- Name: especialidad_medico_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE especialidad_medico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.especialidad_medico_id_seq OWNER TO jelitox;

--
-- Name: especialidad_medico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE especialidad_medico_id_seq OWNED BY especialidad_medico.id;


--
-- Name: estado; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE estado (
    id integer NOT NULL,
    codigo character varying(3) NOT NULL,
    pais_id integer NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.estado OWNER TO jelitox;

--
-- Name: TABLE estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE estado IS 'Modelo para manipular la relación Pais Estado';


--
-- Name: COLUMN estado.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado.codigo IS 'Codigo Estado';


--
-- Name: COLUMN estado.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado.pais_id IS 'Pais';


--
-- Name: COLUMN estado.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado.nombre IS 'Nombre Estado';


--
-- Name: estado_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE estado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estado_id_seq OWNER TO jelitox;

--
-- Name: estado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE estado_id_seq OWNED BY estado.id;


--
-- Name: estado_usuario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE estado_usuario (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    estado_usuario integer NOT NULL,
    descripcion character varying(100) NOT NULL
);


ALTER TABLE public.estado_usuario OWNER TO jelitox;

--
-- Name: TABLE estado_usuario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE estado_usuario IS 'Modelo para manipular el estado de los usuarios';


--
-- Name: COLUMN estado_usuario.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado_usuario.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN estado_usuario.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado_usuario.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN estado_usuario.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN estado_usuario.fecha_modificado IS 'Fecha Modificacion del Registro';


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
-- Name: medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE medicina (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.medicina OWNER TO jelitox;

--
-- Name: TABLE medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE medicina IS 'Modelo para manipular las Medicina';


--
-- Name: COLUMN medicina.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medicina.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN medicina.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medicina.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN medicina.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medicina.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN medicina.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medicina.descripcion IS 'Descripcion de la Medicina';


--
-- Name: COLUMN medicina.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medicina.observacion IS 'Observacion de la Medicina';


--
-- Name: medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.medicina_id_seq OWNER TO jelitox;

--
-- Name: medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE medicina_id_seq OWNED BY medicina.id;


--
-- Name: medico; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE medico (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nacionalidad character varying(1) DEFAULT 'V'::character varying NOT NULL,
    cedula character varying(8) NOT NULL,
    rmpps character varying(8) NOT NULL,
    rif character varying(10) NOT NULL,
    nombre1 character varying(30) NOT NULL,
    nombre2 character varying(30),
    apellido1 character varying(30) NOT NULL,
    apellido2 character varying(30),
    sexo character varying(1) DEFAULT 'M'::character varying NOT NULL,
    celular character varying(12),
    telefono character varying(12),
    correo_electronico character varying(30),
    observacion character varying(250)
);


ALTER TABLE public.medico OWNER TO jelitox;

--
-- Name: TABLE medico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE medico IS 'Modelo para manipular los Medicos';


--
-- Name: COLUMN medico.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN medico.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN medico.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN medico.nacionalidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.nacionalidad IS 'Nacionalidad del Medico';


--
-- Name: COLUMN medico.cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.cedula IS 'Numero de Cedula del Medico';


--
-- Name: COLUMN medico.rmpps; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.rmpps IS 'Numero de Registro del MPPS del Medico';


--
-- Name: COLUMN medico.rif; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.rif IS 'Numero de Rif del Medico';


--
-- Name: COLUMN medico.nombre1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.nombre1 IS 'Primer Nombre del Medico';


--
-- Name: COLUMN medico.nombre2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.nombre2 IS 'Segundo Nombre del Medico';


--
-- Name: COLUMN medico.apellido1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.apellido1 IS 'Primer apellido del Medico';


--
-- Name: COLUMN medico.apellido2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.apellido2 IS 'Segundo apellido del Medico';


--
-- Name: COLUMN medico.sexo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.sexo IS 'Sexo del Medico';


--
-- Name: COLUMN medico.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.celular IS 'Numero Celular del Medico';


--
-- Name: COLUMN medico.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.telefono IS 'Numero Telefono del Medico';


--
-- Name: COLUMN medico.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.correo_electronico IS 'Correo Electronico del medico';


--
-- Name: COLUMN medico.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN medico.observacion IS 'Observacion del Medico';


--
-- Name: medico_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE medico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.medico_id_seq OWNER TO jelitox;

--
-- Name: medico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE medico_id_seq OWNED BY medico.id;


--
-- Name: menu; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE menu (
    id integer NOT NULL,
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
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
-- Name: COLUMN menu.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN menu.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN menu.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN menu.fecha_modificado IS 'Fecha Modificacion del Registro';


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
-- Name: municipio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE municipio (
    id integer NOT NULL,
    estado_id integer NOT NULL,
    codigo character varying(3) NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.municipio OWNER TO jelitox;

--
-- Name: TABLE municipio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE municipio IS 'Modelo para manipular Municipios';


--
-- Name: COLUMN municipio.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN municipio.estado_id IS 'Estado';


--
-- Name: COLUMN municipio.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN municipio.codigo IS 'Codigo Municipio';


--
-- Name: COLUMN municipio.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN municipio.nombre IS 'Nombre Municipio';


--
-- Name: municipio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE municipio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.municipio_id_seq OWNER TO jelitox;

--
-- Name: municipio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE municipio_id_seq OWNED BY municipio.id;


--
-- Name: pais; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE pais (
    id integer NOT NULL,
    codigo character varying(3) NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.pais OWNER TO jelitox;

--
-- Name: TABLE pais; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE pais IS 'Modelo para manipular los Paises';


--
-- Name: COLUMN pais.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN pais.codigo IS 'Codigo del Pais';


--
-- Name: COLUMN pais.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN pais.nombre IS 'Nombre Pais';


--
-- Name: pais_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE pais_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pais_id_seq OWNER TO jelitox;

--
-- Name: pais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE pais_id_seq OWNED BY pais.id;


--
-- Name: parroquia; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE parroquia (
    id integer NOT NULL,
    nombre character varying(128) NOT NULL,
    municipio_id integer NOT NULL
);


ALTER TABLE public.parroquia OWNER TO jelitox;

--
-- Name: TABLE parroquia; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE parroquia IS 'Modelo para  manipular Parroquia';


--
-- Name: COLUMN parroquia.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN parroquia.nombre IS 'Parroquia';


--
-- Name: COLUMN parroquia.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN parroquia.municipio_id IS 'Municipio';


--
-- Name: parroquia_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE parroquia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parroquia_id_seq OWNER TO jelitox;

--
-- Name: parroquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE parroquia_id_seq OWNED BY parroquia.id;


--
-- Name: patologia; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE patologia (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250)
);


ALTER TABLE public.patologia OWNER TO jelitox;

--
-- Name: TABLE patologia; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE patologia IS 'Modelo para manipular las Patologias';


--
-- Name: COLUMN patologia.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN patologia.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN patologia.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN patologia.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN patologia.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN patologia.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN patologia.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN patologia.descripcion IS 'Descripcion de la Patologia';


--
-- Name: COLUMN patologia.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN patologia.observacion IS 'Observacion de la Patologia';


--
-- Name: patologia_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE patologia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.patologia_id_seq OWNER TO jelitox;

--
-- Name: patologia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE patologia_id_seq OWNED BY patologia.id;


--
-- Name: perfil; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE perfil (
    id integer NOT NULL,
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    perfil character varying(45) NOT NULL,
    estado integer DEFAULT 1 NOT NULL,
    plantilla character varying(45) DEFAULT 'default'::character varying
);


ALTER TABLE public.perfil OWNER TO jelitox;

--
-- Name: TABLE perfil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE perfil IS 'Modelo para manipular perfiles del sistema';


--
-- Name: COLUMN perfil.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN perfil.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN perfil.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN perfil.fecha_modificado IS 'Fecha Modificacion del Registro';


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
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    cedula character varying(8) NOT NULL,
    nombre1 character varying(30) NOT NULL,
    nombre2 character varying(30),
    apellido1 character varying(30) NOT NULL,
    apellido2 character varying(30),
    nacionalidad character varying(1) DEFAULT 'V'::character varying NOT NULL,
    sexo character varying(1) DEFAULT 'M'::character varying NOT NULL,
    fecha_nacimiento date DEFAULT '1900-01-01'::date,
    pais_id integer NOT NULL,
    estado_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    direccion_habitacion character varying(250) NOT NULL,
    estado_civil character varying(1) DEFAULT 'S'::character varying NOT NULL,
    celular character varying(12),
    telefono character varying(12),
    correo_electronico character varying(64),
    grupo_sanguineo character varying(4) DEFAULT 'N/A'::character varying,
    fotografia character varying(45) DEFAULT 'default.png'::character varying
);


ALTER TABLE public.persona OWNER TO jelitox;

--
-- Name: TABLE persona; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE persona IS 'Modelo para manipular las diferentes Personas';


--
-- Name: COLUMN persona.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN persona.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN persona.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN persona.cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.cedula IS 'N° Cedula persona';


--
-- Name: COLUMN persona.nombre1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.nombre1 IS 'N° Primer Nombre de la persona';


--
-- Name: COLUMN persona.nombre2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.nombre2 IS 'N° Segundo Nombre de la persona';


--
-- Name: COLUMN persona.apellido1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.apellido1 IS 'N° Primer Apellido del persona';


--
-- Name: COLUMN persona.apellido2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.apellido2 IS 'N° Segundo Apellido del persona';


--
-- Name: COLUMN persona.nacionalidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.nacionalidad IS 'Nacionalidad de la persona';


--
-- Name: COLUMN persona.sexo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.sexo IS 'N° Sexo del persona';


--
-- Name: COLUMN persona.fecha_nacimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.fecha_nacimiento IS 'Fecha de Nacimiento del persona';


--
-- Name: COLUMN persona.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.pais_id IS 'Pais Origen del persona';


--
-- Name: COLUMN persona.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.estado_id IS 'Estado de Origen del persona';


--
-- Name: COLUMN persona.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.municipio_id IS 'Municipio de Origen del persona';


--
-- Name: COLUMN persona.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.parroquia_id IS 'Parroquia de Origen del persona';


--
-- Name: COLUMN persona.direccion_habitacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.direccion_habitacion IS 'Direccion de Habitacion del persona';


--
-- Name: COLUMN persona.estado_civil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.estado_civil IS 'Estado Civil del persona';


--
-- Name: COLUMN persona.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.celular IS 'N° de Celular del persona';


--
-- Name: COLUMN persona.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.telefono IS 'N° de Telefono del persona';


--
-- Name: COLUMN persona.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.correo_electronico IS 'Direccion de Correo Electronico del persona';


--
-- Name: COLUMN persona.grupo_sanguineo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN persona.grupo_sanguineo IS 'Grupo Sanguineo del persona';


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
-- Name: profesion; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE profesion (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250)
);


ALTER TABLE public.profesion OWNER TO jelitox;

--
-- Name: TABLE profesion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE profesion IS 'Modelo para manipular las diferentes Profesiones';


--
-- Name: COLUMN profesion.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN profesion.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN profesion.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN profesion.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN profesion.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN profesion.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN profesion.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN profesion.nombre IS 'Nombre de la Profesion';


--
-- Name: COLUMN profesion.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN profesion.observacion IS 'Observacion';


--
-- Name: profesion_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE profesion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profesion_id_seq OWNER TO jelitox;

--
-- Name: profesion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE profesion_id_seq OWNED BY profesion.id;


--
-- Name: proveedor_medico; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE proveedor_medico (
    id integer DEFAULT nextval('proveedor_id_seq'::regclass) NOT NULL,
    medico_id integer NOT NULL,
    proveedor_id integer NOT NULL
);


ALTER TABLE public.proveedor_medico OWNER TO jelitox;

--
-- Name: TABLE proveedor_medico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE proveedor_medico IS 'Modelo para manipular la relacion proveedor-medico';


--
-- Name: COLUMN proveedor_medico.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor_medico.medico_id IS 'ID del medico';


--
-- Name: COLUMN proveedor_medico.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN proveedor_medico.proveedor_id IS 'ID del proveedor';


--
-- Name: proveedor_medico_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE proveedor_medico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.proveedor_medico_id_seq OWNER TO jelitox;

--
-- Name: proveedor_medico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE proveedor_medico_id_seq OWNED BY proveedor_medico.id;


--
-- Name: recaudo; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recaudo (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    tipo character varying(64) NOT NULL,
    observacion character varying(250)
);


ALTER TABLE public.recaudo OWNER TO jelitox;

--
-- Name: TABLE recaudo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recaudo IS 'Modelo para manipular los diferentes Recaudos';


--
-- Name: COLUMN recaudo.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN recaudo.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN recaudo.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN recaudo.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo.nombre IS 'Nombre del Recaudo';


--
-- Name: COLUMN recaudo.tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo.tipo IS 'Tipo de Recaudo';


--
-- Name: COLUMN recaudo.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo.observacion IS 'Observacion';


--
-- Name: recaudo_beneficiario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recaudo_beneficiario (
    id integer DEFAULT nextval('beneficiario_id_seq'::regclass) NOT NULL,
    beneficiario_id integer NOT NULL,
    recaudo_id integer NOT NULL,
    estado boolean
);


ALTER TABLE public.recaudo_beneficiario OWNER TO jelitox;

--
-- Name: TABLE recaudo_beneficiario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recaudo_beneficiario IS 'Modelo para manipular la relacion Recaudo-Beneficiarios';


--
-- Name: COLUMN recaudo_beneficiario.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_beneficiario.beneficiario_id IS 'ID del Beneficiario';


--
-- Name: COLUMN recaudo_beneficiario.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_beneficiario.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN recaudo_beneficiario.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_beneficiario.estado IS 'Estado del Recaudo';


--
-- Name: recaudo_beneficiario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recaudo_beneficiario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recaudo_beneficiario_id_seq OWNER TO jelitox;

--
-- Name: recaudo_beneficiario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recaudo_beneficiario_id_seq OWNED BY recaudo_beneficiario.id;


--
-- Name: recaudo_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recaudo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recaudo_id_seq OWNER TO jelitox;

--
-- Name: recaudo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recaudo_id_seq OWNED BY recaudo.id;


--
-- Name: recaudo_reembolso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recaudo_reembolso (
    id integer NOT NULL,
    recaudo_id integer NOT NULL,
    codigo_solicitud character varying(8) NOT NULL,
    estado boolean
);


ALTER TABLE public.recaudo_reembolso OWNER TO jelitox;

--
-- Name: TABLE recaudo_reembolso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recaudo_reembolso IS 'Modelo para manipular la relacion Recaudo - Reembolsos';


--
-- Name: COLUMN recaudo_reembolso.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_reembolso.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN recaudo_reembolso.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_reembolso.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN recaudo_reembolso.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_reembolso.estado IS 'Estado del Recaudo';


--
-- Name: recaudo_reembolso_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recaudo_reembolso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recaudo_reembolso_id_seq OWNER TO jelitox;

--
-- Name: recaudo_reembolso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recaudo_reembolso_id_seq OWNED BY recaudo_reembolso.id;


--
-- Name: recaudo_solicitud_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recaudo_solicitud_medicina (
    id integer NOT NULL,
    recaudo_id integer NOT NULL,
    codigo_solicitud character varying(8) NOT NULL,
    estado boolean
);


ALTER TABLE public.recaudo_solicitud_medicina OWNER TO jelitox;

--
-- Name: TABLE recaudo_solicitud_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recaudo_solicitud_medicina IS 'Modelo para manipular la relacion Recaudo - Solicitud Medicina';


--
-- Name: COLUMN recaudo_solicitud_medicina.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_solicitud_medicina.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN recaudo_solicitud_medicina.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_solicitud_medicina.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN recaudo_solicitud_medicina.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_solicitud_medicina.estado IS 'Estado del Recaudo';


--
-- Name: recaudo_solicitud_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recaudo_solicitud_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recaudo_solicitud_medicina_id_seq OWNER TO jelitox;

--
-- Name: recaudo_solicitud_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recaudo_solicitud_medicina_id_seq OWNED BY recaudo_solicitud_medicina.id;


--
-- Name: recaudo_solicitud_servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recaudo_solicitud_servicio (
    id integer NOT NULL,
    recaudo_id integer NOT NULL,
    codigo_solicitud character varying(8) NOT NULL,
    estado boolean
);


ALTER TABLE public.recaudo_solicitud_servicio OWNER TO jelitox;

--
-- Name: TABLE recaudo_solicitud_servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recaudo_solicitud_servicio IS 'Modelo para manipular la relacion Recaudo - Solicitud Servicio';


--
-- Name: COLUMN recaudo_solicitud_servicio.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_solicitud_servicio.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN recaudo_solicitud_servicio.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_solicitud_servicio.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN recaudo_solicitud_servicio.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_solicitud_servicio.estado IS 'Estado del Recaudo';


--
-- Name: recaudo_solicitud_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recaudo_solicitud_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recaudo_solicitud_servicio_id_seq OWNER TO jelitox;

--
-- Name: recaudo_solicitud_servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recaudo_solicitud_servicio_id_seq OWNED BY recaudo_solicitud_servicio.id;


--
-- Name: recaudo_titular; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recaudo_titular (
    id integer NOT NULL,
    titular_id integer NOT NULL,
    recaudo_id integer NOT NULL,
    estado boolean
);


ALTER TABLE public.recaudo_titular OWNER TO jelitox;

--
-- Name: TABLE recaudo_titular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recaudo_titular IS 'Modelo para manipular la relacion Recaudo-Titular';


--
-- Name: COLUMN recaudo_titular.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_titular.titular_id IS 'ID del Titular';


--
-- Name: COLUMN recaudo_titular.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_titular.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN recaudo_titular.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recaudo_titular.estado IS 'Estado del Recaudo';


--
-- Name: recaudo_titular_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE recaudo_titular_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recaudo_titular_id_seq OWNER TO jelitox;

--
-- Name: recaudo_titular_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE recaudo_titular_id_seq OWNED BY recaudo_titular.id;


--
-- Name: recurso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE recurso (
    id integer NOT NULL,
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    modulo character varying(45),
    controlador character varying(45),
    accion character varying(45),
    recurso character varying(100),
    descripcion character varying(150) NOT NULL,
    activo integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.recurso OWNER TO jelitox;

--
-- Name: TABLE recurso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recurso IS 'Modelo para manipular recursos (controladores)';


--
-- Name: COLUMN recurso.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN recurso.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN recurso.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso.fecha_modificado IS 'Fecha Modificacion del Registro';


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
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    recurso_id integer NOT NULL,
    perfil_id integer NOT NULL
);


ALTER TABLE public.recurso_perfil OWNER TO jelitox;

--
-- Name: TABLE recurso_perfil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE recurso_perfil IS 'Modelo para manipular relacion Recurso - Perfil';


--
-- Name: COLUMN recurso_perfil.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN recurso_perfil.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN recurso_perfil.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN recurso_perfil.recurso_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.recurso_id IS 'ID del Recurso';


--
-- Name: COLUMN recurso_perfil.perfil_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN recurso_perfil.perfil_id IS 'ID del Perfil';


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
-- Name: reembolso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE reembolso (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    estado_solicitud character(1) NOT NULL,
    fecha_solicitud date DEFAULT '1900-01-01'::date,
    codigo_solicitud character varying(8) NOT NULL,
    titular_id integer NOT NULL,
    beneficiario_id integer NOT NULL,
    beneficiario_tipo character(1) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.reembolso OWNER TO jelitox;

--
-- Name: TABLE reembolso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE reembolso IS 'Modelo para manipular las Solicitudes de Reembolso';


--
-- Name: COLUMN reembolso.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN reembolso.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN reembolso.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN reembolso.estado_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.estado_solicitud IS 'Estado de la Solicitud';


--
-- Name: COLUMN reembolso.fecha_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.fecha_solicitud IS 'Fecha de la Solicitud';


--
-- Name: COLUMN reembolso.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN reembolso.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.titular_id IS 'Codigo del Titular';


--
-- Name: COLUMN reembolso.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.beneficiario_id IS 'Codigo del Beneficiario';


--
-- Name: COLUMN reembolso.beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.beneficiario_tipo IS 'beneficiario de la Solicitud';


--
-- Name: COLUMN reembolso.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN reembolso.observacion IS 'Observacion';


--
-- Name: reembolso_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE reembolso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reembolso_id_seq OWNER TO jelitox;

--
-- Name: reembolso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE reembolso_id_seq OWNED BY reembolso.id;


--
-- Name: servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE servicio (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250)
);


ALTER TABLE public.servicio OWNER TO jelitox;

--
-- Name: TABLE servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE servicio IS 'Modelo para manipular los Servicios';


--
-- Name: COLUMN servicio.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN servicio.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN servicio.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN servicio.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio.descripcion IS 'Descripcion del Servicio';


--
-- Name: COLUMN servicio.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio.observacion IS 'Observacion del Servicio';


--
-- Name: servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servicio_id_seq OWNER TO jelitox;

--
-- Name: servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE servicio_id_seq OWNED BY servicio.id;


--
-- Name: servicio_proveedor; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE servicio_proveedor (
    id integer DEFAULT nextval('proveedor_id_seq'::regclass) NOT NULL,
    proveedor_id integer NOT NULL,
    servicio_id integer NOT NULL
);


ALTER TABLE public.servicio_proveedor OWNER TO jelitox;

--
-- Name: TABLE servicio_proveedor; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE servicio_proveedor IS 'Modelo para manipular la relacion servicio-proveedors';


--
-- Name: COLUMN servicio_proveedor.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio_proveedor.proveedor_id IS 'ID del proveedor';


--
-- Name: COLUMN servicio_proveedor.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio_proveedor.servicio_id IS 'ID del servicio';


--
-- Name: servicio_proveedor_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE servicio_proveedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servicio_proveedor_id_seq OWNER TO jelitox;

--
-- Name: servicio_proveedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE servicio_proveedor_id_seq OWNED BY servicio_proveedor.id;


--
-- Name: servicio_tiposolicitud; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE servicio_tiposolicitud (
    id integer DEFAULT nextval('proveedor_id_seq'::regclass) NOT NULL,
    tiposolicitud_id integer NOT NULL,
    servicio_id integer NOT NULL
);


ALTER TABLE public.servicio_tiposolicitud OWNER TO jelitox;

--
-- Name: TABLE servicio_tiposolicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE servicio_tiposolicitud IS 'Modelo para manipular la relacion Servicio - Tiposolicitud';


--
-- Name: COLUMN servicio_tiposolicitud.tiposolicitud_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio_tiposolicitud.tiposolicitud_id IS 'ID del tipo de solicitud';


--
-- Name: COLUMN servicio_tiposolicitud.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN servicio_tiposolicitud.servicio_id IS 'ID del Servicio';


--
-- Name: servicio_tiposolicitud_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE servicio_tiposolicitud_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servicio_tiposolicitud_id_seq OWNER TO jelitox;

--
-- Name: servicio_tiposolicitud_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE servicio_tiposolicitud_id_seq OWNED BY servicio_tiposolicitud.id;


--
-- Name: solicitud_dt_factura; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE solicitud_dt_factura (
    id integer NOT NULL,
    solicitud_factura_id integer,
    descripcion character varying(150) NOT NULL,
    cantidad integer,
    monto numeric(11,2) NOT NULL,
    exento boolean
);


ALTER TABLE public.solicitud_dt_factura OWNER TO jelitox;

--
-- Name: TABLE solicitud_dt_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE solicitud_dt_factura IS 'Modelo para manipular el Detalle de la Facturacion de las Solicitudes de Servicios';


--
-- Name: COLUMN solicitud_dt_factura.solicitud_factura_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_factura.solicitud_factura_id IS 'Id de la Factura';


--
-- Name: COLUMN solicitud_dt_factura.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_factura.descripcion IS 'Descripcion del Item';


--
-- Name: COLUMN solicitud_dt_factura.cantidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_factura.cantidad IS 'Cantidad del Item';


--
-- Name: COLUMN solicitud_dt_factura.monto; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_factura.monto IS 'Monto del Item';


--
-- Name: COLUMN solicitud_dt_factura.exento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_factura.exento IS 'Item Exento del Iva';


--
-- Name: solicitud_dt_factura_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE solicitud_dt_factura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_dt_factura_id_seq OWNER TO jelitox;

--
-- Name: solicitud_dt_factura_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE solicitud_dt_factura_id_seq OWNED BY solicitud_dt_factura.id;


--
-- Name: solicitud_dt_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE solicitud_dt_medicina (
    id integer NOT NULL,
    solicitud_id integer NOT NULL,
    medicina_id integer NOT NULL,
    fecha_inicio date DEFAULT '1900-01-01'::date,
    fecha_fin date DEFAULT '1900-01-01'::date,
    dosis integer,
    horas time without time zone
);


ALTER TABLE public.solicitud_dt_medicina OWNER TO jelitox;

--
-- Name: TABLE solicitud_dt_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE solicitud_dt_medicina IS 'Modelo para manipular los Detalles de las Solicitudes de Medicinas';


--
-- Name: COLUMN solicitud_dt_medicina.id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.id IS 'Id del Registro';


--
-- Name: COLUMN solicitud_dt_medicina.solicitud_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.solicitud_id IS 'Id la Solicitud';


--
-- Name: COLUMN solicitud_dt_medicina.medicina_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.medicina_id IS 'Codigo de la Medicina';


--
-- Name: COLUMN solicitud_dt_medicina.fecha_inicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.fecha_inicio IS 'Fecha Inicio del Tratamiento';


--
-- Name: COLUMN solicitud_dt_medicina.fecha_fin; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.fecha_fin IS 'Fecha Fin del Tratamiento';


--
-- Name: COLUMN solicitud_dt_medicina.dosis; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.dosis IS 'Dosis de la Medicina';


--
-- Name: COLUMN solicitud_dt_medicina.horas; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_dt_medicina.horas IS 'Dosis de la Medicina';


--
-- Name: solicitud_dt_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE solicitud_dt_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_dt_medicina_id_seq OWNER TO jelitox;

--
-- Name: solicitud_dt_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE solicitud_dt_medicina_id_seq OWNED BY solicitud_dt_medicina.id;


--
-- Name: solicitud_factura; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE solicitud_factura (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    solicitud_servicio_id integer,
    codigo_solicitud character varying(8) NOT NULL,
    fecha_factura date DEFAULT '1900-01-01'::date,
    nro_control integer,
    nro_factura integer,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.solicitud_factura OWNER TO jelitox;

--
-- Name: TABLE solicitud_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE solicitud_factura IS 'Modelo para manipular la Facturacion de las Solicitudes de Servicios';


--
-- Name: COLUMN solicitud_factura.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN solicitud_factura.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN solicitud_factura.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN solicitud_factura.solicitud_servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.solicitud_servicio_id IS 'Id de la Solicitud';


--
-- Name: COLUMN solicitud_factura.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN solicitud_factura.fecha_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.fecha_factura IS 'Fecha de Factura';


--
-- Name: COLUMN solicitud_factura.nro_control; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.nro_control IS 'Numero de Control';


--
-- Name: COLUMN solicitud_factura.nro_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.nro_factura IS 'Numero de Factura';


--
-- Name: COLUMN solicitud_factura.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_factura.observacion IS 'Observacion';


--
-- Name: solicitud_factura_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE solicitud_factura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_factura_id_seq OWNER TO jelitox;

--
-- Name: solicitud_factura_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE solicitud_factura_id_seq OWNED BY solicitud_factura.id;


--
-- Name: solicitud_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE solicitud_medicina (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    estado_solicitud character(1) NOT NULL,
    fecha_solicitud date DEFAULT '1900-01-01'::date,
    fecha_vencimiento date DEFAULT '1900-01-01'::date,
    codigo_solicitud character varying(8) NOT NULL,
    titular_id integer NOT NULL,
    beneficiario_id integer NOT NULL,
    beneficiario_tipo character(1) NOT NULL,
    patologia_id integer NOT NULL,
    proveedor_id integer NOT NULL,
    medico_id integer NOT NULL,
    persona_autorizada character varying(30) NOT NULL,
    persona_cedula character varying(8) NOT NULL,
    tipo_tratamiento character varying(1) DEFAULT 'T'::character varying NOT NULL,
    diagnostico character varying(250) NOT NULL,
    servicio_id integer NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.solicitud_medicina OWNER TO jelitox;

--
-- Name: TABLE solicitud_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE solicitud_medicina IS 'Modelo para manipular las Solicitudes de Medicinas';


--
-- Name: COLUMN solicitud_medicina.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN solicitud_medicina.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN solicitud_medicina.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN solicitud_medicina.estado_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.estado_solicitud IS 'Estado de la Solicitud';


--
-- Name: COLUMN solicitud_medicina.fecha_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.fecha_solicitud IS 'Fecha de la Solicitud';


--
-- Name: COLUMN solicitud_medicina.fecha_vencimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.fecha_vencimiento IS 'Fecha de Vencimiento de la Solicitud';


--
-- Name: COLUMN solicitud_medicina.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN solicitud_medicina.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.titular_id IS 'Codigo del Titular';


--
-- Name: COLUMN solicitud_medicina.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.beneficiario_id IS 'Codigo del Beneficiario';


--
-- Name: COLUMN solicitud_medicina.beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.beneficiario_tipo IS 'beneficiario de la Solicitud';


--
-- Name: COLUMN solicitud_medicina.patologia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.patologia_id IS 'Codigo de la Patologia';


--
-- Name: COLUMN solicitud_medicina.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.proveedor_id IS 'Codigo del Proveedor';


--
-- Name: COLUMN solicitud_medicina.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.medico_id IS 'Codigo del Medico';


--
-- Name: COLUMN solicitud_medicina.persona_autorizada; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.persona_autorizada IS 'Persona Autorizada';


--
-- Name: COLUMN solicitud_medicina.persona_cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.persona_cedula IS 'Cedula Persona Autorizada';


--
-- Name: COLUMN solicitud_medicina.tipo_tratamiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.tipo_tratamiento IS 'Tipo de Tratamiento';


--
-- Name: COLUMN solicitud_medicina.diagnostico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.diagnostico IS 'Diagnostico';


--
-- Name: COLUMN solicitud_medicina.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.servicio_id IS 'Codigo del Servicio';


--
-- Name: COLUMN solicitud_medicina.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_medicina.observacion IS 'Observacion';


--
-- Name: solicitud_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE solicitud_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_medicina_id_seq OWNER TO jelitox;

--
-- Name: solicitud_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE solicitud_medicina_id_seq OWNED BY solicitud_medicina.id;


--
-- Name: solicitud_servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE solicitud_servicio (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    estado_solicitud character(1) NOT NULL,
    tiposolicitud_id integer NOT NULL,
    fecha_solicitud date DEFAULT '1900-01-01'::date,
    codigo_solicitud character varying(8) NOT NULL,
    titular_id integer NOT NULL,
    beneficiario_id integer NOT NULL,
    beneficiario_tipo character(1) NOT NULL,
    patologia_id integer NOT NULL,
    proveedor_id integer NOT NULL,
    medico_id integer NOT NULL,
    fecha_vencimiento date DEFAULT '1900-01-01'::date,
    servicio_id integer NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.solicitud_servicio OWNER TO jelitox;

--
-- Name: TABLE solicitud_servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE solicitud_servicio IS 'Modelo para manipular las Solicitudes de Servicios';


--
-- Name: COLUMN solicitud_servicio.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN solicitud_servicio.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN solicitud_servicio.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN solicitud_servicio.estado_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.estado_solicitud IS 'Estado de la Solicitud';


--
-- Name: COLUMN solicitud_servicio.tiposolicitud_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.tiposolicitud_id IS 'Tipo de Solicitud';


--
-- Name: COLUMN solicitud_servicio.fecha_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.fecha_solicitud IS 'Fecha de la Solicitud';


--
-- Name: COLUMN solicitud_servicio.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN solicitud_servicio.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.titular_id IS 'Codigo del Titular';


--
-- Name: COLUMN solicitud_servicio.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.beneficiario_id IS 'Codigo del Beneficiario';


--
-- Name: COLUMN solicitud_servicio.beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.beneficiario_tipo IS 'beneficiario de la Solicitud';


--
-- Name: COLUMN solicitud_servicio.patologia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.patologia_id IS 'Codigo de la Patologia';


--
-- Name: COLUMN solicitud_servicio.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.proveedor_id IS 'Codigo del Proveedor';


--
-- Name: COLUMN solicitud_servicio.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.medico_id IS 'Codigo del Medico';


--
-- Name: COLUMN solicitud_servicio.fecha_vencimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.fecha_vencimiento IS 'Fecha Vencimiento de la Solicitud';


--
-- Name: COLUMN solicitud_servicio.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.servicio_id IS 'Codigo del Servicio';


--
-- Name: COLUMN solicitud_servicio.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN solicitud_servicio.observacion IS 'Observacion';


--
-- Name: solicitud_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE solicitud_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitud_servicio_id_seq OWNER TO jelitox;

--
-- Name: solicitud_servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE solicitud_servicio_id_seq OWNED BY solicitud_servicio.id;


--
-- Name: sucursal; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sucursal (
    id integer NOT NULL,
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    empresa_id integer NOT NULL,
    sucursal character varying(45) NOT NULL,
    sucursal_slug character varying(45),
    pais_id integer NOT NULL,
    estado_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    direccion character varying(45),
    telefono character varying(45),
    fax character varying(45),
    celular character varying(45)
);


ALTER TABLE public.sucursal OWNER TO jelitox;

--
-- Name: TABLE sucursal; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sucursal IS 'Modelo para manipular las sucursales';


--
-- Name: COLUMN sucursal.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN sucursal.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN sucursal.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.fecha_modificado IS 'Fecha Modificacion del Registro';


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
-- Name: COLUMN sucursal.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.pais_id IS 'Id de la Pais';


--
-- Name: COLUMN sucursal.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.estado_id IS 'Id del Estado';


--
-- Name: COLUMN sucursal.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.municipio_id IS 'Id del Municipio';


--
-- Name: COLUMN sucursal.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sucursal.parroquia_id IS 'Id de la Parroquia';


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
-- Name: tipoempleado; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE tipoempleado (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.tipoempleado OWNER TO jelitox;

--
-- Name: TABLE tipoempleado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE tipoempleado IS 'Modelo para manipular las diferentes Profesiones';


--
-- Name: COLUMN tipoempleado.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tipoempleado.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN tipoempleado.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tipoempleado.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN tipoempleado.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tipoempleado.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN tipoempleado.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tipoempleado.nombre IS 'Nombre de la Profesion';


--
-- Name: COLUMN tipoempleado.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tipoempleado.observacion IS 'Observacion';


--
-- Name: tipoempleado_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE tipoempleado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipoempleado_id_seq OWNER TO jelitox;

--
-- Name: tipoempleado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE tipoempleado_id_seq OWNED BY tipoempleado.id;


--
-- Name: tiposolicitud; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE tiposolicitud (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.tiposolicitud OWNER TO jelitox;

--
-- Name: TABLE tiposolicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE tiposolicitud IS 'Modelo para manipular las diferentes Tipos de Solicitudes';


--
-- Name: COLUMN tiposolicitud.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tiposolicitud.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN tiposolicitud.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tiposolicitud.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN tiposolicitud.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tiposolicitud.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN tiposolicitud.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tiposolicitud.nombre IS 'Nombre del Tipo Solicitud';


--
-- Name: COLUMN tiposolicitud.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN tiposolicitud.observacion IS 'Observacion';


--
-- Name: tiposolicitud_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE tiposolicitud_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tiposolicitud_id_seq OWNER TO jelitox;

--
-- Name: tiposolicitud_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE tiposolicitud_id_seq OWNED BY tiposolicitud.id;


--
-- Name: titular; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE titular (
    id integer NOT NULL,
    usuario_id integer NOT NULL,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    tipoempleado_id integer NOT NULL,
    persona_id integer NOT NULL,
    fecha_ingreso date DEFAULT '1900-01-01'::date,
    profesion_id integer NOT NULL,
    departamento_id integer NOT NULL,
    cargo_id integer NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.titular OWNER TO jelitox;

--
-- Name: TABLE titular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE titular IS 'Modelo para manipular los Titulares';


--
-- Name: COLUMN titular.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN titular.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN titular.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN titular.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN titular.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN titular.fecha_modificado IS 'Fecha Modificacion del Registro';


--
-- Name: COLUMN titular.tipoempleado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN titular.tipoempleado_id IS 'Tipo de Empleado';


--
-- Name: COLUMN titular.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN titular.observacion IS 'Observacion';


--
-- Name: titular_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE titular_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.titular_id_seq OWNER TO jelitox;

--
-- Name: titular_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE titular_id_seq OWNED BY titular.id;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE usuario (
    id integer NOT NULL,
    usuario_id integer,
    fecha_registro timestamp with time zone DEFAULT now() NOT NULL,
    fecha_modificado timestamp with time zone DEFAULT now() NOT NULL,
    sucursal_id integer,
    persona_id integer,
    login character varying(45) NOT NULL,
    password character varying(45) NOT NULL,
    perfil_id integer NOT NULL,
    email character varying(45),
    tema character varying(45),
    app_ajax integer DEFAULT 1,
    datagrid integer DEFAULT 30
);


ALTER TABLE public.usuario OWNER TO jelitox;

--
-- Name: TABLE usuario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE usuario IS 'Modelo para manipular los usuarios';


--
-- Name: COLUMN usuario.usuario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.usuario_id IS 'Usuario Editor del Registro';


--
-- Name: COLUMN usuario.fecha_registro; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.fecha_registro IS 'Fecha del Registro';


--
-- Name: COLUMN usuario.fecha_modificado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN usuario.fecha_modificado IS 'Fecha Modificacion del Registro';


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

ALTER TABLE ONLY beneficiario ALTER COLUMN id SET DEFAULT nextval('beneficiario_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY beneficiario_tipo ALTER COLUMN id SET DEFAULT nextval('beneficiario_tipo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY cargo ALTER COLUMN id SET DEFAULT nextval('cargo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY cobertura ALTER COLUMN id SET DEFAULT nextval('cobertura_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY departamento ALTER COLUMN id SET DEFAULT nextval('departamento_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY discapacidad ALTER COLUMN id SET DEFAULT nextval('discapacidad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY discapacidad_persona ALTER COLUMN id SET DEFAULT nextval('discapacidad_persona_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa ALTER COLUMN id SET DEFAULT nextval('empresa_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY especialidad ALTER COLUMN id SET DEFAULT nextval('especialidad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY estado ALTER COLUMN id SET DEFAULT nextval('estado_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY estado_usuario ALTER COLUMN id SET DEFAULT nextval('estado_usuario_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY medicina ALTER COLUMN id SET DEFAULT nextval('medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY medico ALTER COLUMN id SET DEFAULT nextval('medico_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY menu ALTER COLUMN id SET DEFAULT nextval('menu_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY municipio ALTER COLUMN id SET DEFAULT nextval('municipio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY pais ALTER COLUMN id SET DEFAULT nextval('pais_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY parroquia ALTER COLUMN id SET DEFAULT nextval('parroquia_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY patologia ALTER COLUMN id SET DEFAULT nextval('patologia_id_seq'::regclass);


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

ALTER TABLE ONLY profesion ALTER COLUMN id SET DEFAULT nextval('profesion_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor ALTER COLUMN id SET DEFAULT nextval('proveedor_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo ALTER COLUMN id SET DEFAULT nextval('recaudo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_reembolso ALTER COLUMN id SET DEFAULT nextval('recaudo_reembolso_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_solicitud_medicina ALTER COLUMN id SET DEFAULT nextval('recaudo_solicitud_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_solicitud_servicio ALTER COLUMN id SET DEFAULT nextval('recaudo_solicitud_servicio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_titular ALTER COLUMN id SET DEFAULT nextval('recaudo_titular_id_seq'::regclass);


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

ALTER TABLE ONLY reembolso ALTER COLUMN id SET DEFAULT nextval('reembolso_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY servicio ALTER COLUMN id SET DEFAULT nextval('servicio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_dt_factura ALTER COLUMN id SET DEFAULT nextval('solicitud_dt_factura_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_dt_medicina ALTER COLUMN id SET DEFAULT nextval('solicitud_dt_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_factura ALTER COLUMN id SET DEFAULT nextval('solicitud_factura_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina ALTER COLUMN id SET DEFAULT nextval('solicitud_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio ALTER COLUMN id SET DEFAULT nextval('solicitud_servicio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal ALTER COLUMN id SET DEFAULT nextval('sucursal_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY tipoempleado ALTER COLUMN id SET DEFAULT nextval('tipoempleado_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY tiposolicitud ALTER COLUMN id SET DEFAULT nextval('tiposolicitud_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY titular ALTER COLUMN id SET DEFAULT nextval('titular_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY usuario ALTER COLUMN id SET DEFAULT nextval('usuario_id_seq'::regclass);


--
-- Data for Name: acceso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY acceso (id, usuario_id, fecha_registro, fecha_modificado, tipo_acceso, navegador, version_navegador, sistema_operativo, nombre_equipo, ip) FROM stdin;
2	1	2014-03-13 13:36:38.399487-04:30	2014-03-13 13:36:38.399487-04:30	2	\N	\N	\N	\N	127.0.0.1
3	1	2014-03-13 13:44:49.217453-04:30	2014-03-13 13:44:49.217453-04:30	2	\N	\N	\N	\N	127.0.0.1
4	1	2014-03-13 13:45:25.084298-04:30	2014-03-13 13:45:25.084298-04:30	2	\N	\N	\N	\N	127.0.0.1
5	1	2014-03-13 13:51:05.455943-04:30	2014-03-13 13:51:05.455943-04:30	2	\N	\N	\N	\N	127.0.0.1
6	1	2014-03-13 13:53:50.513476-04:30	2014-03-13 13:53:50.513476-04:30	2	\N	\N	\N	\N	127.0.0.1
7	1	2014-03-13 14:08:31.997715-04:30	2014-03-13 14:08:31.997715-04:30	2	\N	\N	\N	\N	127.0.0.1
8	1	2014-03-13 20:10:55.138285-04:30	2014-03-13 20:10:55.138285-04:30	2	\N	\N	\N	\N	127.0.0.1
9	1	2014-03-13 22:19:10.784492-04:30	2014-03-13 22:19:10.784492-04:30	2	\N	\N	\N	\N	127.0.0.1
10	1	2014-03-14 11:54:21.602879-04:30	2014-03-14 11:54:21.602879-04:30	2	\N	\N	\N	\N	127.0.0.1
11	1	2014-03-14 11:54:28.411002-04:30	2014-03-14 11:54:28.411002-04:30	1	\N	\N	\N	\N	127.0.0.1
12	1	2014-03-14 11:54:42.093035-04:30	2014-03-14 11:54:42.093035-04:30	2	\N	\N	\N	\N	127.0.0.1
13	1	2014-03-14 11:54:48.714039-04:30	2014-03-14 11:54:48.714039-04:30	1	\N	\N	\N	\N	127.0.0.1
14	1	2014-03-14 12:50:56.666416-04:30	2014-03-14 12:50:56.666416-04:30	1	\N	\N	\N	\N	127.0.0.1
15	1	2014-03-14 13:39:53.693347-04:30	2014-03-14 13:39:53.693347-04:30	1	\N	\N	\N	\N	127.0.0.1
16	1	2014-03-15 14:02:47.133496-04:30	2014-03-15 14:02:47.133496-04:30	1	\N	\N	\N	\N	127.0.0.1
17	1	2014-03-15 17:35:27.771259-04:30	2014-03-15 17:35:27.771259-04:30	1	\N	\N	\N	\N	127.0.0.1
18	1	2014-03-15 18:03:08.20981-04:30	2014-03-15 18:03:08.20981-04:30	2	\N	\N	\N	\N	127.0.0.1
19	1	2014-03-15 18:03:13.664569-04:30	2014-03-15 18:03:13.664569-04:30	1	\N	\N	\N	\N	127.0.0.1
20	1	2014-03-15 18:07:56.430811-04:30	2014-03-15 18:07:56.430811-04:30	2	\N	\N	\N	\N	127.0.0.1
21	1	2014-03-15 18:08:03.854353-04:30	2014-03-15 18:08:03.854353-04:30	1	\N	\N	\N	\N	127.0.0.1
22	1	2014-03-15 18:12:23.998826-04:30	2014-03-15 18:12:23.998826-04:30	2	\N	\N	\N	\N	127.0.0.1
23	1	2014-03-15 18:12:29.923889-04:30	2014-03-15 18:12:29.923889-04:30	1	\N	\N	\N	\N	127.0.0.1
24	1	2014-03-15 18:12:43.85387-04:30	2014-03-15 18:12:43.85387-04:30	2	\N	\N	\N	\N	127.0.0.1
25	1	2014-03-15 18:13:25.214322-04:30	2014-03-15 18:13:25.214322-04:30	1	\N	\N	\N	\N	127.0.0.1
26	1	2014-03-15 22:32:28.148879-04:30	2014-03-15 22:32:28.148879-04:30	2	\N	\N	\N	\N	127.0.0.1
27	1	2014-03-15 22:32:34.859826-04:30	2014-03-15 22:32:34.859826-04:30	1	\N	\N	\N	\N	127.0.0.1
28	1	2014-03-15 22:34:11.264848-04:30	2014-03-15 22:34:11.264848-04:30	2	\N	\N	\N	\N	127.0.0.1
29	1	2014-03-15 22:34:18.818627-04:30	2014-03-15 22:34:18.818627-04:30	1	\N	\N	\N	\N	127.0.0.1
30	1	2014-03-15 22:48:12.523915-04:30	2014-03-15 22:48:12.523915-04:30	2	\N	\N	\N	\N	127.0.0.1
31	1	2014-03-15 22:48:20.218691-04:30	2014-03-15 22:48:20.218691-04:30	1	\N	\N	\N	\N	127.0.0.1
32	1	2014-03-15 22:49:31.389011-04:30	2014-03-15 22:49:31.389011-04:30	2	\N	\N	\N	\N	127.0.0.1
33	1	2014-03-15 22:49:37.40785-04:30	2014-03-15 22:49:37.40785-04:30	1	\N	\N	\N	\N	127.0.0.1
34	1	2014-03-16 01:20:54.756982-04:30	2014-03-16 01:20:54.756982-04:30	2	\N	\N	\N	\N	127.0.0.1
35	4	2014-03-16 01:21:03.683843-04:30	2014-03-16 01:21:03.683843-04:30	1	\N	\N	\N	\N	127.0.0.1
36	4	2014-03-16 01:21:21.440617-04:30	2014-03-16 01:21:21.440617-04:30	2	\N	\N	\N	\N	127.0.0.1
37	1	2014-03-16 01:21:27.358997-04:30	2014-03-16 01:21:27.358997-04:30	1	\N	\N	\N	\N	127.0.0.1
38	1	2014-03-16 01:22:23.129786-04:30	2014-03-16 01:22:23.129786-04:30	2	\N	\N	\N	\N	127.0.0.1
39	4	2014-03-16 01:22:36.807659-04:30	2014-03-16 01:22:36.807659-04:30	1	\N	\N	\N	\N	127.0.0.1
40	4	2014-03-16 01:24:02.77809-04:30	2014-03-16 01:24:02.77809-04:30	2	\N	\N	\N	\N	127.0.0.1
41	1	2014-03-16 01:24:54.825045-04:30	2014-03-16 01:24:54.825045-04:30	1	\N	\N	\N	\N	127.0.0.1
42	1	2014-03-16 11:53:14.760225-04:30	2014-03-16 11:53:14.760225-04:30	2	\N	\N	\N	\N	127.0.0.1
43	1	2014-03-16 11:53:22.573651-04:30	2014-03-16 11:53:22.573651-04:30	1	\N	\N	\N	\N	127.0.0.1
44	1	2014-03-16 12:21:08.768412-04:30	2014-03-16 12:21:08.768412-04:30	2	\N	\N	\N	\N	127.0.0.1
45	4	2014-03-16 12:21:29.537315-04:30	2014-03-16 12:21:29.537315-04:30	1	\N	\N	\N	\N	127.0.0.1
46	4	2014-03-16 12:23:32.794662-04:30	2014-03-16 12:23:32.794662-04:30	2	\N	\N	\N	\N	127.0.0.1
47	1	2014-03-16 12:23:39.512138-04:30	2014-03-16 12:23:39.512138-04:30	1	\N	\N	\N	\N	127.0.0.1
48	1	2014-03-16 12:49:35.021334-04:30	2014-03-16 12:49:35.021334-04:30	2	\N	\N	\N	\N	127.0.0.1
49	4	2014-03-16 12:51:18.170273-04:30	2014-03-16 12:51:18.170273-04:30	1	\N	\N	\N	\N	127.0.0.1
50	4	2014-03-16 14:20:25.609382-04:30	2014-03-16 14:20:25.609382-04:30	2	\N	\N	\N	\N	127.0.0.1
51	4	2014-03-16 14:20:33.576394-04:30	2014-03-16 14:20:33.576394-04:30	1	\N	\N	\N	\N	127.0.0.1
52	1	2014-03-16 15:52:29.106416-04:30	2014-03-16 15:52:29.106416-04:30	1	\N	\N	\N	\N	127.0.0.1
53	1	2014-03-16 15:55:49.684289-04:30	2014-03-16 15:55:49.684289-04:30	2	\N	\N	\N	\N	127.0.0.1
54	4	2014-03-16 16:42:29.513223-04:30	2014-03-16 16:42:29.513223-04:30	1	\N	\N	\N	\N	127.0.0.1
\.


--
-- Name: acceso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('acceso_id_seq', 54, true);


--
-- Data for Name: backup; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY backup (id, usuario_id, fecha_registro, fecha_modificado, denominacion, tamano, archivo) FROM stdin;
\.


--
-- Name: backup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('backup_id_seq', 1, false);


--
-- Data for Name: beneficiario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY beneficiario (id, usuario_id, fecha_registro, fecha_modificado, titular_id, persona_id, parentesco, beneficiario_tipo_id, observacion) FROM stdin;
\.


--
-- Name: beneficiario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('beneficiario_id_seq', 1, false);


--
-- Data for Name: beneficiario_tipo; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY beneficiario_tipo (id, usuario_id, fecha_registro, fecha_modificado, descripcion, observacion) FROM stdin;
\.


--
-- Name: beneficiario_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('beneficiario_tipo_id_seq', 1, false);


--
-- Data for Name: cargo; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY cargo (id, usuario_id, fecha_registro, fecha_modificado, nombre, observacion) FROM stdin;
\.


--
-- Name: cargo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('cargo_id_seq', 1, false);


--
-- Data for Name: cobertura; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY cobertura (id, usuario_id, fecha_registro, fecha_modificado, descripcion, tipo_cobertura, monto_cobertura, fecha_inicio, fecha_fin, observacion) FROM stdin;
\.


--
-- Name: cobertura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('cobertura_id_seq', 1, false);


--
-- Data for Name: departamento; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY departamento (id, usuario_id, fecha_registro, fecha_modificado, nombre, observacion, sucursal_id) FROM stdin;
\.


--
-- Name: departamento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('departamento_id_seq', 1, false);


--
-- Data for Name: discapacidad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY discapacidad (id, usuario_id, fecha_registro, fecha_modificado, nombre, observacion) FROM stdin;
\.


--
-- Name: discapacidad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('discapacidad_id_seq', 1, false);


--
-- Data for Name: discapacidad_persona; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY discapacidad_persona (id, persona_id, discapacidad_id) FROM stdin;
\.


--
-- Name: discapacidad_persona_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('discapacidad_persona_id_seq', 1, false);


--
-- Data for Name: empresa; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY empresa (id, usuario_id, fecha_registro, fecha_modificado, razon_social, rif, pais_id, estado_id, municipio_id, parroquia_id, representante_legal, pagina_web, telefono, fax, celular, logo, email) FROM stdin;
1	\N	2014-03-13 12:11:18.427198-04:30	2014-03-13 12:11:18.427198-04:30	EMPRESA MIXTA SOCIALISTA ARROZ DEL ALBA S.A.	G-200054321	240	69	224	717	Francisco Ortiz	http://www.arrozdelalba.gob.ve	02563361333	02563361333	04162546908	default.png	arrozdelalba@arrozdelalba.gob.ve
\.


--
-- Name: empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('empresa_id_seq', 1, false);


--
-- Data for Name: especialidad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY especialidad (id, usuario_id, fecha_registro, fecha_modificado, descripcion, observacion) FROM stdin;
\.


--
-- Name: especialidad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('especialidad_id_seq', 1, false);


--
-- Data for Name: especialidad_medico; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY especialidad_medico (id, medico_id, especialidad_id) FROM stdin;
\.


--
-- Name: especialidad_medico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('especialidad_medico_id_seq', 1, false);


--
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY estado (id, codigo, pais_id, nombre) FROM stdin;
1	AL	235	Alabama
2	AK	235	Alaska
3	AZ	235	Arizona
4	AR	235	Arkansas
5	CA	235	California
6	CO	235	Colorado
7	CT	235	Connecticut
8	DE	235	Delaware
9	DC	235	District of Columbia
10	FL	235	Florida
11	GA	235	Georgia
12	HI	235	Hawaii
13	ID	235	Idaho
14	IL	235	Illinois
15	IN	235	Indiana
16	IA	235	Iowa
17	KS	235	Kansas
18	KY	235	Kentucky
19	LA	235	Louisiana
20	ME	235	Maine
21	MT	235	Montana
22	NE	235	Nebraska
23	NV	235	Nevada
24	NH	235	New Hampshire
25	NJ	235	New Jersey
26	NM	235	New Mexico
27	NY	235	New York
28	NC	235	North Carolina
29	ND	235	North Dakota
30	OH	235	Ohio
31	OK	235	Oklahoma
32	OR	235	Oregon
33	MD	235	Maryland
34	MA	235	Massachusetts
35	MI	235	Michigan
36	MN	235	Minnesota
37	MS	235	Mississippi
38	MO	235	Missouri
39	PA	235	Pennsylvania
40	RI	235	Rhode Island
41	SC	235	South Carolina
42	SD	235	South Dakota
43	TN	235	Tennessee
44	TX	235	Texas
45	UT	235	Utah
46	VT	235	Vermont
47	VA	235	Virginia
48	WA	235	Washington
49	WV	235	West Virginia
50	WI	235	Wisconsin
51	WY	235	Wyoming
52	dc	240	Distrito Capital
53	am	240	Amazonas
54	an	240	Anzoategui
55	ap	240	Apure
56	ar	240	Aragua
57	ba	240	Barinas
58	bo	240	Bolivar
59	ca	240	Carabobo
60	co	240	Cojedes
61	da	240	Delta Amacuro
62	fa	240	Falcon
63	gu	240	Guarico
64	la	240	Lara
65	me	240	Merida
66	mi	240	Miranda
67	mo	240	Monagas
68	ne	240	Nueva Esparta
69	po	240	Portuguesa
70	su	240	Sucre
71	ta	240	Tachira
72	tr	240	Trujillo
73	va	240	Vargas
74	ya	240	Yaracuy
75	zu	240	Zulia
\.


--
-- Name: estado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('estado_id_seq', 75, true);


--
-- Data for Name: estado_usuario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY estado_usuario (id, usuario_id, fecha_registro, fecha_modificado, estado_usuario, descripcion) FROM stdin;
1	1	2014-03-13 13:35:39.596605-04:30	2014-03-13 13:35:39.596605-04:30	1	Activo por ser el Super Usuario del Sistema
3	4	2014-03-16 01:14:45.552613-04:30	2014-03-16 01:14:45.552613-04:30	1	Activado por registro inicial
\.


--
-- Name: estado_usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('estado_usuario_id_seq', 3, true);


--
-- Data for Name: medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY medicina (id, usuario_id, fecha_registro, fecha_modificado, descripcion, observacion) FROM stdin;
\.


--
-- Name: medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('medicina_id_seq', 1, false);


--
-- Data for Name: medico; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY medico (id, usuario_id, fecha_registro, fecha_modificado, nacionalidad, cedula, rmpps, rif, nombre1, nombre2, apellido1, apellido2, sexo, celular, telefono, correo_electronico, observacion) FROM stdin;
\.


--
-- Name: medico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('medico_id_seq', 1, false);


--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY menu (id, usuario_id, fecha_registro, fecha_modificado, menu_id, recurso_id, menu, url, posicion, icono, activo, visibilidad) FROM stdin;
1	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	\N	\N	Dashboard	#	10	icon-home	1	1
2	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	1	2	Dashboard	dashboard/	11	icon-home	1	1
3	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	\N	\N	Sistema	#	900	icon-cogs	1	1
4	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	4	Accesos	sistema/acceso/listar/	901	icon-exchange	1	1
5	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	5	Auditorías	sistema/auditoria/	902	icon-eye-open	1	1
6	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	6	Backups	sistema/backup/listar/	903	icon-hdd	1	1
7	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	7	Mantenimiento	sistema/mantenimiento/	904	icon-bolt	1	1
8	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	8	Menús	sistema/menu/listar/	905	icon-list	1	1
9	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	9	Perfiles	sistema/perfil/listar/	906	icon-group	1	1
10	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	10	Permisos	sistema/privilegio/listar/	907	icon-magic	1	1
11	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	11	Recursos	sistema/recurso/listar/	908	icon-lock	1	1
12	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	12	Usuarios	sistema/usuario/listar/	909	icon-user	1	1
13	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	13	Visor de sucesos	sistema/sucesos/	910	icon-filter	1	1
14	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	3	14	Sistema	sistema/configuracion/	911	icon-wrench	1	1
15	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	\N	\N	Configuraciones	#	800	icon-wrench	1	1
16	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	15	Empresa	config/empresa/	876	icon-briefcase	1	1
19	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	18	Profesion	config/profesion/	803	\N	1	1
20	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	19	Cargo	config/cargo/	804	\N	1	1
21	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	20	Cobertura	config/cobertura/	805	\N	1	1
22	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	21	Departamento	config/departamento/	806	\N	1	1
23	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	22	Discapacidad	config/discapacidad/	807	\N	1	1
24	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	23	Patologia	config/patologia/	808	\N	1	1
25	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	24	Recaudos	config/recaudo/	809	\N	1	1
17	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	15	16	Sucursales	config/sucursal/listar/	802	icon-sitemap	1	1
33	\N	2014-03-16 13:27:52.745733-04:30	2014-03-16 13:27:52.745733-04:30	29	30	Examen de Imagenes 	solicitudes/examen_imagen/	204	icon-th	1	1
34	\N	2014-03-16 13:27:52.745733-04:30	2014-03-16 13:27:52.745733-04:30	29	31	Solicitud de Reembolso	solicitudes/reembolso/	205	icon-th	1	1
35	\N	2014-03-16 13:27:52.745733-04:30	2014-03-16 13:27:52.745733-04:30	29	32	Funeraria	solicitudes/funeraria/	206	icon-th	1	1
28	\N	2014-03-16 12:46:04.752491-04:30	2014-03-16 12:46:04.752491-04:30	\N	\N	Beneficiarios	#	100	icon-user	1	1
27	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	28	26	Beneficiarios	beneficiarios/beneficiario/	102	icon-user	1	1
26	\N	2014-03-13 13:30:24.848631-04:30	2014-03-13 13:30:24.848631-04:30	28	25	Titular	beneficiarios/titular/	101	icon-user	1	1
29	\N	2014-03-16 13:23:40.74219-04:30	2014-03-16 13:23:40.74219-04:30	\N	\N	Solicitudes	#	200	icon-th	1	1
30	\N	2014-03-16 13:24:43.632516-04:30	2014-03-16 13:24:43.632516-04:30	29	27	Orden Medicas	solicitudes/orden_medica/	201	icon-th	1	1
31	\N	2014-03-16 13:26:21.282386-04:30	2014-03-16 13:26:21.282386-04:30	29	28	Atención Primaria	solicitudes/atencion_primaria/	202	icon-th	1	1
32	\N	2014-03-16 13:27:52.745733-04:30	2014-03-16 13:27:52.745733-04:30	29	29	Examen de Laboratorio	solicitudes/examen_laboratorio/	203	icon-th	1	1
\.


--
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('menu_id_seq', 22, true);


--
-- Data for Name: municipio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY municipio (id, estado_id, codigo, nombre) FROM stdin;
1	52	001	Libertador
2	53	002	Alto Orinoco
3	53	003	Atabapo
4	53	004	Atures
5	53	005	Autana
6	53	006	Manapiare
7	53	007	Maroa
8	53	008	Rio Negro
9	54	009	Anaco
10	54	010	Aragua
11	54	011	Bolivar
12	54	012	Bruzual
13	54	013	Cajigal
14	54	014	Carvajal
15	54	015	Diego Bautista Urbaneja
16	54	016	Freites
17	54	017	Guanipa
18	54	018	Guanta
19	54	019	Independencia
20	54	020	Libertad
21	54	021	McGregor
22	54	022	Miranda
23	54	023	Monagas
24	54	024	Penalver
25	54	025	Piritu
26	54	026	San Juan de Capistrano
27	54	027	Santa Ana
28	54	028	Simon Rodriguez
29	54	029	Sotillo
30	55	031	Achaguas
31	55	032	Biruaca
32	55	033	Munoz
33	55	034	Paez
34	55	035	Pedro Camejo
35	55	036	Romulo Gallegos
36	55	037	San Fernando
37	56	038	Bolivar
38	56	039	Camatagua
39	56	040	Francisco Linares Alcantara
40	56	041	Girardot
41	56	042	Jose Angel Lamas
42	56	043	Jose Felix Ribas
43	56	044	José Rafael Revenga
44	56	046	Libertador
45	56	047	Mario Briceno Iragorry
46	56	048	Ocumare de la Costa de Oro
47	56	049	San Casimiro
48	56	050	San Sebastian
49	56	051	Santiago Marino
50	56	052	Santos Michelena
51	56	053	Sucre
52	56	054	Tovar
53	56	055	Urdaneta
54	56	056	Zamora
55	57	057	Alberto Arvelo Torrealba
56	57	058	Andres Eloy Blanco
57	57	059	Antonio Jose de Sucre
58	57	060	Arismendi
59	57	061	Barinas
60	57	062	Bolivar
61	57	063	Cruz Paredes
62	57	064	Ezequiel Zamora
63	57	065	Obispos
64	57	066	Pedraza
65	57	067	Rojas
66	57	068	Sosa
67	58	069	Caroni
68	58	070	Cedeno
69	58	071	El Callao
70	58	072	Gran Sabana
71	58	073	Heres
72	58	074	Piar
73	58	075	Raul Leoni
74	58	076	Roscio
75	58	077	Sifontes
76	58	078	Sucre
77	58	079	Padre Pedro Chien
78	59	080	Bejuma
79	59	081	Carlos Arvelo
80	59	082	Guacara
81	59	083	Diego Ibarra
82	59	084	Juan Jose Mora
83	59	085	Libertador
84	59	086	Los Guayos
85	59	087	Naguanagua
86	59	088	Miranda
87	59	089	Montalban
88	59	090	Puerto Cabello
89	59	091	San Diego
90	59	092	San Joaquín
91	59	093	Valencia
92	60	094	Anzoategui
93	60	095	Falcon
94	60	095	Girardot
95	60	096	Lima Blanco
96	60	097	Pao de San Juan Bautista
97	60	098	Ricaurte
98	60	099	Romulo Gallegos
99	60	100	San Carlos
100	60	101	Tinaco
101	61	102	Antonio Diaz
102	61	103	Casacoima
103	61	104	Pedernales
104	61	105	Tucupita
105	62	106	Acosta
106	62	107	Bolivar
107	62	108	Buchivacoa
108	62	109	Cacique Manaure
109	62	110	Carirubana
110	62	111	Colina
111	62	112	Dabajuro
112	62	113	Democracia
113	62	114	Falcon
114	62	115	Federacion
115	62	116	Jacura
116	62	117	Los Taques
117	62	118	Mauroa
118	62	119	Miranda
119	62	120	Monsenor Iturriza
120	62	121	Palmasola
121	62	122	Petit
122	62	123	Piritu
123	62	124	San Francisco
124	62	125	Silva
125	62	126	Sucre
126	62	127	Tocopero
127	62	128	Union
128	62	129	Urumaco
129	62	130	Zamora
130	63	131	Camaguan
131	63	132	Chaguaramas
132	63	133	El Socorro
133	63	134	Sebastian Francisco de Miranda
134	63	135	Jose Felix Ribas
135	63	136	Jose Tadeo Monagas
136	63	137	Juan German Roscio
137	63	138	Julian Mellado
138	63	139	Las Mercedes
139	63	140	Leonardo Infante
140	63	141	Pedro Zaraza
141	63	142	Ortiz
142	63	143	San Geronimo de Guayabal
143	63	144	San Jose de Guaribe
144	63	145	Santa Maria de Ipire
145	64	146	Andres Eloy Blanco
146	64	147	Crespo
147	64	148	Iribarren
148	64	149	Jimenez
149	64	150	Moran 
150	64	151	Palavecino
151	64	152	Simon Planas
152	64	153	Torres
153	64	154	Urdaneta
154	65	155	Alberto Adriani
155	65	156	Andres Bello
156	65	157	Antonio Pinto Salinas
157	65	158	Aricagua
158	65	159	Arzobispo Chacon
159	65	160	Campo Elias
160	65	161	Caracciolo Parra Olmedo
161	65	162	Cardenal Quintero
162	65	163	Guaraque
163	65	164	Julio Cesar Salas
164	65	165	Justo Briceno
165	65	166	Libertador
166	65	167	Miranda
167	65	168	Obispo Ramos de Lora
168	65	169	Padre Noguera
169	65	170	Pueblo Llano
170	65	171	Rangel
171	65	172	Rivas Davila
172	65	173	Santos Marquina
173	65	174	Sucre
174	65	175	Tovar
175	65	176	Tulio Febres Cordero
176	65	177	Zea
177	66	178	Acevedo
178	66	179	Andres Bello
179	66	180	Baruta
180	66	181	Brion
181	66	182	Buroz
182	66	183	Carrizal
183	66	184	Chacao
184	66	185	Cristobal Rojas
185	66	186	El Hatillo
186	66	187	Guaicaipuro
187	66	188	Independencia
188	66	189	Lander
189	66	190	Los Salias
190	66	191	Paez
191	66	192	Paz Castillo
192	66	193	Pedro Gual
193	66	194	Plaza
194	66	195	Simon Bolívar
195	66	196	Sucre
196	66	197	Urdaneta
197	66	198	Zamora
198	67	201	Acosta
199	67	202	Aguasay
200	67	203	Bolivar
201	67	204	Caripe
202	67	205	Cedeno
203	67	206	Ezequiel Zamora
204	67	207	Libertador
205	67	208	Maturin
206	67	209	Piar
207	67	210	Punceres
208	67	211	Santa Barbara
209	67	212	Sotillo
210	67	213	Uracoa
211	68	214	Antolin del Campo
212	68	215	Arismendi
213	68	216	Diaz
214	68	217	Garcia
215	68	218	Gomez
216	68	219	Maneiro
217	68	220	Marcano
218	68	221	Marino
219	68	222	Peninsula de Macanao
220	68	223	Tubores
221	68	224	Villalba
222	69	225	Agua Blanca
223	69	226	Araure
224	69	227	Esteller
225	69	228	Guanare
226	69	229	Guanarito
227	69	230	Monsenor Jose Vicente de Unda
228	69	231	Ospino
229	69	232	Paez
230	69	233	Papelon
231	69	234	San Genaro de Boconoito
232	69	235	San Rafael de Onoto
233	69	236	Santa Rosalia
234	69	237	Sucre
235	69	238	Turen
236	70	239	Andres Eloy Blanco
237	70	240	Andres Mata
238	70	241	Arismendi 
239	70	242	Benitez
240	70	243	Bermudez
241	70	244	Cajigal
242	70	245	Cruz Salmeron Acosta
243	70	246	Libertador
244	70	247	Marino
245	70	248	Mejia
246	70	249	Montes
247	70	250	Ribero
248	70	251	Sucre
249	70	252	Valdez
250	71	254	Andres Bello
251	71	255	Antonio Romulo Costa
252	71	256	Ayacucho
253	71	257	Bolivar
254	71	258	Cardenas
255	71	259	Cordoba
256	71	260	Fernandez Feo
257	71	261	Francisco de Miranda
258	71	262	Garcia de Hevia
259	71	263	Guasimos
260	71	264	Jose Maria Vargas
261	71	265	Independencia
262	71	266	Jauregui
263	71	267	Junin
264	71	268	Libertad
265	71	269	Libertador
266	71	270	Lobatera
267	71	271	Michelena
268	71	272	Pedro Maria Urena
269	71	273	Rafael Urdaneta
270	71	274	Samuel Dario Maldonado
271	71	275	San Cristobal 
272	71	276	Seboruco
273	71	277	Simon Rodriguez
274	71	278	Sucre
275	71	279	Torbes
276	71	280	Uribante
277	71	281	San Judas Tadeo
278	71	282	Panamericano
279	72	301	Andres Bello
280	72	302	Bocono
281	72	303	Bolivar
282	72	304	Candelaria
283	72	305	Carache
284	72	306	Escuque
285	72	307	Jose Felipe Marquez Canizalez
286	72	308	Juan Vicente Campos Elias
287	72	309	La Ceiba
288	72	310	Miranda
289	72	311	Monte Carmelo
290	72	312	Motatan
291	72	313	Pampan
292	72	314	Pampanito
293	72	315	Rafael Rangel
294	72	316	San Rafael de Carvajal
295	72	317	Sucre
296	72	318	Trujillo
297	72	319	Urdaneta
298	72	320	Valera
299	73	200	Vargas
300	74	401	Aristides Bastidas
301	74	402	Bolivar
302	74	403	Bruzual
303	74	404	Cocorote
304	74	405	Independencia
305	74	406	Jose Antonio Paez
306	74	407	La Trinidad
307	74	408	Manuel Monge
308	74	409	Nirgua
309	74	410	Pena
310	74	411	San Felipe
311	74	412	Sucre
312	74	413	Urachiche
313	74	414	Veroes
314	75	501	Almirante Padilla
315	75	502	Baralt
316	75	503	Cabimas
317	75	522	Catatumbo
318	75	504	Colon
319	75	505	Francisco Javier Pulgar
320	75	506	Jesús Enrique Losada
321	75	507	Jesus Maria Semprun
322	75	508	La Cañada de Urdaneta
323	75	509	Lagunillas
324	75	510	Machiques de Perija
325	75	511	Mara
326	75	512	Maracaibo
327	75	513	Miranda
328	75	514	Páez
329	75	515	Rosario de Perija
330	75	517	San Francisco
331	75	518	Santa Rita
332	75	519	Simon Bolivar
333	75	520	Sucre
334	75	521	Valmore Rodriguez
\.


--
-- Name: municipio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('municipio_id_seq', 334, true);


--
-- Data for Name: pais; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY pais (id, codigo, nombre) FROM stdin;
1	AD	Andorra, Principality of
2	AE	United Arab Emirates
3	AF	Afghanistan, Islamic State of
4	AG	Antigua and Barbuda
5	AI	Anguilla
6	AL	Albania
7	AM	Armenia
8	AN	Netherlands Antilles
9	AO	Angola
10	AQ	Antarctica
11	AR	Argentina
12	AS	American Samoa
13	AT	Austria
14	AU	Australia
15	AW	Aruba
16	AX	Åland Islands
17	AZ	Azerbaijan
18	BA	Bosnia-Herzegovina
19	BB	Barbados
20	BD	Bangladesh
21	BE	Belgium
22	BF	Burkina Faso
23	BG	Bulgaria
24	BH	Bahrain
25	BI	Burundi
26	BJ	Benin
27	BL	Saint Barthélémy
28	BM	Bermuda
29	BN	Brunei Darussalam
30	BO	Bolivia
31	BQ	Bonaire, Sint Eustatius and Saba
32	BR	Brazil
33	BS	Bahamas
34	BT	Bhutan
35	BV	Bouvet Island
36	BW	Botswana
37	BY	Belarus
38	BZ	Belize
39	CA	Canada
40	CC	Cocos (Keeling) Islands
41	CF	Central African Republic
42	CD	Congo, Democratic Republic of the
43	CG	Congo
44	CH	Switzerland
45	CI	Ivory Coast (Cote D''Ivoire)
46	CK	Cook Islands
47	CL	Chile
48	CM	Cameroon
49	CN	China
50	CO	Colombia
51	CR	Costa Rica
52	CU	Cuba
53	CV	Cape Verde
54	CW	Curaçao
55	CX	Christmas Island
56	CY	Cyprus
57	CZ	Czech Republic
58	DE	Germany
59	DJ	Djibouti
60	DK	Denmark
61	DM	Dominica
62	DO	Dominican Republic
63	DZ	Algeria
64	EC	Ecuador
65	EE	Estonia
66	EG	Egypt
67	EH	Western Sahara
68	ER	Eritrea
69	ES	Spain
70	ET	Ethiopia
71	FI	Finland
72	FJ	Fiji
73	FK	Falkland Islands
74	FM	Micronesia
75	FO	Faroe Islands
76	FR	France
77	GA	Gabon
78	GD	Grenada
79	GE	Georgia
80	GF	French Guyana
81	GH	Ghana
82	GI	Gibraltar
83	GG	Guernsey
84	GL	Greenland
85	GM	Gambia
86	GN	Guinea
87	GP	Guadeloupe (French)
88	GQ	Equatorial Guinea
89	GR	Greece
90	GS	South Georgia and the South Sandwich Islands
91	GT	Guatemala
92	GU	Guam (USA)
93	GW	Guinea Bissau
94	GY	Guyana
95	HK	Hong Kong
96	HM	Heard and McDonald Islands
97	HN	Honduras
98	HR	Croatia
99	HT	Haiti
100	HU	Hungary
101	ID	Indonesia
102	IE	Ireland
103	IL	Israel
104	IM	Isle of Man
105	IN	India
106	IO	British Indian Ocean Territory
107	IQ	Iraq
108	IR	Iran
109	IS	Iceland
110	IT	Italy
111	JE	Jersey
112	JM	Jamaica
113	JO	Jordan
114	JP	Japan
115	KE	Kenya
116	KG	Kyrgyz Republic (Kyrgyzstan)
117	KH	Cambodia, Kingdom of
118	KI	Kiribati
119	KM	Comoros
120	KN	Saint Kitts & Nevis Anguilla
121	KP	North Korea
122	KR	South Korea
123	KW	Kuwait
124	KY	Cayman Islands
125	KZ	Kazakhstan
126	LA	Laos
127	LB	Lebanon
128	LC	Saint Lucia
129	LI	Liechtenstein
130	LK	Sri Lanka
131	LR	Liberia
132	LS	Lesotho
133	LT	Lithuania
134	LU	Luxembourg
135	LV	Latvia
136	LY	Libya
137	MA	Morocco
138	MC	Monaco
139	MD	Moldavia
140	ME	Montenegro
141	MF	Saint Martin (French part)
142	MG	Madagascar
143	MH	Marshall Islands
144	MK	Macedonia, the former Yugoslav Republic of
145	ML	Mali
146	MM	Myanmar
147	MN	Mongolia
148	MO	Macau
149	MP	Northern Mariana Islands
150	MQ	Martinique (French)
151	MR	Mauritania
152	MS	Montserrat
153	MT	Malta
154	MU	Mauritius
155	MV	Maldives
156	MW	Malawi
157	MX	Mexico
158	MY	Malaysia
159	MZ	Mozambique
160	NA	Namibia
161	NC	New Caledonia (French)
162	NE	Niger
163	NF	Norfolk Island
164	NG	Nigeria
165	NI	Nicaragua
166	NL	Netherlands
167	NO	Norway
168	NP	Nepal
169	NR	Nauru
170	NT	Neutral Zone
171	NU	Niue
172	NZ	New Zealand
173	OM	Oman
174	PA	Panama
175	PE	Peru
176	PF	Polynesia (French)
177	PG	Papua New Guinea
178	PH	Philippines
179	PK	Pakistan
180	PL	Poland
181	PM	Saint Pierre and Miquelon
182	PN	Pitcairn Island
183	PR	Puerto Rico
184	PS	Palestinian Territory, Occupied
185	PT	Portugal
186	PW	Palau
187	PY	Paraguay
188	QA	Qatar
189	RE	Reunion (French)
190	RO	Romania
191	RS	Serbia
192	RU	Russian Federation
193	RW	Rwanda
194	SA	Saudi Arabia
195	SB	Solomon Islands
196	SC	Seychelles
197	SD	Sudan
198	SE	Sweden
199	SG	Singapore
200	SH	Saint Helena
201	SI	Slovenia
202	SJ	Svalbard and Jan Mayen Islands
203	SK	Slovakia
204	SL	Sierra Leone
205	SM	San Marino
206	SN	Senegal
207	SO	Somalia
208	SR	Suriname
209	SS	South Sudan
210	ST	Saint Tome (Sao Tome) and Principe
211	SV	El Salvador
212	SX	Sint Maarten (Dutch part)
213	SY	Syria
214	SZ	Swaziland
215	TC	Turks and Caicos Islands
216	TD	Chad
217	TF	French Southern Territories
218	TG	Togo
219	TH	Thailand
220	TJ	Tajikistan
221	TK	Tokelau
222	TM	Turkmenistan
223	TN	Tunisia
224	TO	Tonga
225	TP	East Timor
226	TR	Turkey
227	TT	Trinidad and Tobago
228	TV	Tuvalu
229	TW	Taiwan
230	TZ	Tanzania
231	UA	Ukraine
232	UG	Uganda
233	GB	United Kingdom
234	UM	USA Minor Outlying Islands
235	US	United States
236	UY	Uruguay
237	UZ	Uzbekistan
238	VA	Holy See (Vatican City State)
239	VC	Saint Vincent & Grenadines
240	VE	Venezuela
241	VG	Virgin Islands (British)
242	VI	Virgin Islands (USA)
243	VN	Vietnam
244	VU	Vanuatu
245	WF	Wallis and Futuna Islands
246	WS	Samoa
247	YE	Yemen
248	YT	Mayotte
249	YU	Yugoslavia
250	ZA	South Africa
251	ZM	Zambia
252	ZR	Zaire
253	ZW	Zimbabwe
\.


--
-- Name: pais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('pais_id_seq', 253, true);


--
-- Data for Name: parroquia; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY parroquia (id, nombre, municipio_id) FROM stdin;
1	Parroquia Huachamacare	2
2	Parroquia Marawaka	2
3	Parroquia Mavaca	2
4	Parroquia Sierra Parima	2
5	Parroquia Ucata	3
6	Parroquia Yapacana	3
7	Parroquia Caname	3
8	Parroquia Fernando Girón Tovar	4
9	Parroquia Luis Alberto Gómez	4
10	Parroquia Parhueña	4
11	Parroquia Platanillal	4
12	Parroquia Samariapo	5
13	Parroquia Sipapo	5
14	Parroquia Munduapo	5
15	Parroquia Guayapo	5
16	Parroquia Victorino	7
17	Parroquia Comunidad	7
18	Parroquia Alto Ventuari	6
19	Parroquia Medio Ventuari	6
20	Parroquia Bajo Ventuari	6
21	Parroquia Solano	8
22	Parroquia Casiquiare	8
23	Parroquia Cocuy	8
24	Parroquia Capital Anaco	9
25	Parroquia San Joaquín	9
26	Parroquia Capital Aragua	10
27	Parroquia Cachipo	10
28	Parroquia Capital Fernando de Peñalver	24
29	Parroquia San Miguel	24
30	Parroquia Sucre	24
31	Parroquia Capital Francisco del Carmen Carvajal	14
32	Parroquia Santa Bárbara	14
33	Parroquia Capital Francisco de Miranda	22
34	Parroquia Atapirire	22
35	Parroquia Boca del Pao	22
36	Parroquia El Pao	22
37	Parroquia Múcura	22
38	Parroquia Capital Guanta	18
39	Parroquia Chorrerón	18
40	Parroquia Capital Independencia	19
41	Parroquia Mamo	19
42	Parroquia Capital Puerto La Cruz	29
43	Parroquia Pozuelos	29
44	Parroquia Capital Juan Manuel Cajigal	13
45	Parroquia San Pablo	13
46	Parroquia Capital José Gregorio Monagas	23
47	Parroquia Piar	23
48	Parroquia San Diego de Cabrutica	23
49	Parroquia Santa Clara	23
50	Parroquia Uverito	23
51	Parroquia Zuata	23
52	Parroquia Capital Libertad	20
53	Parroquia El Carito	20
54	Parroquia Santa Inés	20
55	Parroquia Capital Manuel Ezequiel Bruzual	12
56	Parroquia Guanape	12
57	Parroquia Sabana de Uchire	12
58	Parroquia Capital Pedro María Freites	16
59	Parroquia Libertador	16
60	Parroquia Santa Rosa	16
61	Parroquia Urica	16
62	Parroquia Capital Píritu	25
63	Parroquia San Francisco	25
64	Parroquia Capital San Juan de Capistrano	26
65	Parroquia Boca de Chávez	26
66	Parroquia Capital Santa Ana	27
67	Parroquia Pueblo Nuevo	27
68	Parroquia El Carmen	11
69	Parroquia San Cristóbal	11
70	Parroquia Bergantín	11
71	Parroquia Caigua	11
72	Parroquia El Pilar	11
73	Parroquia Naricual	11
74	Parroquia Edmundo Barrios	28
75	Parroquia Miguel Otero Silva	28
76	Parroquia Capital Sir Arthur Mc Gregor	21
77	Parroquia Tomás Alfaro Calatrava	21
78	Parroquia Capital Diego Bautista Urbaneja	15
79	Parroquia El Morro	15
80	Parroquia Urbana Achaguas	30
81	Parroquia Apurito	30
82	Parroquia El Yagual	30
83	Parroquia Guachara	30
84	Parroquia Mucuritas	30
85	Parroquia Queseras del Medio	30
86	Parroquia Urbana Biruaca	31
87	Parroquia Urbana Bruzual	32
88	Parroquia Mantecal	32
89	Parroquia Quintero	32
90	Parroquia Rincón Hondo	32
91	Parroquia San Vicente	32
92	Parroquia Urbana Guasdualito	33
93	Parroquia Aramendi	33
94	Parroquia El Amparo	33
95	Parroquia San Camilo	33
96	Parroquia Urdaneta	33
97	Parroquia Urbana San Juan de Payara	34
98	Parroquia Codazzi	34
99	Parroquia Cunaviche	34
100	Parroquia Urbana Elorza	35
101	Parroquia La Trinidad	35
102	Parroquia Urbana San Fernando	36
103	Parroquia El Recreo	36
104	Parroquia Peñalver	36
105	Parroquia San Rafael de Atamaica	36
106	Parroquia Camatagua	38
107	Parroquia No Urbana Carmen de Cura	38
108	Parroquia No Urbana Choroní	40
109	Parroquia Urbana Las Delicias	40
110	Parroquia Urbana Madre María de San José	40
111	Parroquia Urbana Joaquín Crespo	40
112	Parroquia Urbana Pedro José Ovalles	40
113	Parroquia Urbana José Casanova Godoy	40
114	Parroquia Urbana Andrés Eloy Blanco	40
115	Parroquia Urbana Los Tacariguas	40
116	Parroquia José Félix Ribas	42
117	Parroquia Castor Nieves Ríos	42
118	Parroquia No Urbana Las Guacamayas	42
119	Parroquia No Urbana Pao de Zárate	42
120	Parroquia No Urbana Zuata	42
121	Parroquia Libertador	44
122	Parroquia No Urbana San Martín de Porres	44
123	Parroquia Mario Briceño Iragorry	45
124	Parroquia Caña de Azúcar	45
125	Parroquia San Casimiro	47
126	Parroquia No Urbana Güiripa	47
127	Parroquia No Urbana Ollas de Caramacate	47
128	Parroquia No Urbana Valle Morín	47
129	Parroquia Santiago Mariño	49
130	Parroquia No Urbana Arévalo Aponte	49
131	Parroquia No Urbana Chuao	49
132	Parroquia No Urbana Samán de Güere	49
133	Parroquia No Urbana Alfredo Pacheco Miranda	49
134	Parroquia Santos Michelena	50
135	Parroquia No Urbana Tiara	50
136	Parroquia Sucre	51
137	Parroquia No Urbana Bella Vista	51
138	Parroquia Urdaneta	53
139	Parroquia No Urbana Las Peñitas	53
140	Parroquia No Urbana San Francisco de Cara	53
141	Parroquia No Urbana Taguay	53
142	Parroquia Zamora	54
143	Parroquia No Urbana Magdaleno	54
144	Parroquia No Urbana San Francisco de Asís	54
145	Parroquia No Urbana Valles de Tucutunemo	54
146	Parroquia No Urbana Augusto Mijares	54
147	Parroquia Francisco Linares Alcántara	39
148	Parroquia No Urbana Francisco de Miranda	39
149	Parroquia No Urbana Monseñor Feliciano González	39
150	Parroquia Sabaneta	55
151	Parroquia Rodríguez Domínguez	55
152	Parroquia Ticoporo	57
153	Parroquia Andrés Bello	57
154	Parroquia Nicolás Pulido	57
155	Parroquia Arismendi	58
156	Parroquia Guadarrama	58
157	Parroquia La Unión	58
158	Parroquia San Antonio	58
159	Parroquia Barinas	59
160	Parroquia Alfredo Arvelo Larriva	59
161	Parroquia San Silvestre	59
162	Parroquia Santa Inés	59
163	Parroquia Santa Lucía	59
164	Parroquia Torunos	59
165	Parroquia El Carmen	59
166	Parroquia Rómulo Betancourt	59
167	Parroquia Corazón de Jesús	59
168	Parroquia Ramón Ignacio Méndez	59
169	Parroquia Alto Barinas	59
170	Parroquia Manuel Palacio Fajardo	59
171	Parroquia Juan Antonio Rodríguez Domínguez	59
172	Parroquia Dominga Ortiz de Páez	59
173	Parroquia Barinitas	60
174	Parroquia Altamira	60
175	Parroquia Calderas	60
176	Parroquia Barrancas	61
177	Parroquia El Socorro	61
178	Parroquia Masparrito	61
179	Parroquia Santa Bárbara	62
180	Parroquia José Ignacio Del Pumar	62
181	Parroquia Pedro Briceño Méndez	62
182	Parroquia Ramón Ignacio Méndez	62
183	Parroquia Obispos	63
184	Parroquia El Real	63
185	Parroquia La Luz	63
186	Parroquia Los Guasimitos	63
187	Parroquia Ciudad Bolivia	64
188	Parroquia Ignacio Briceño	64
189	Parroquia José Félix Ribas	64
190	Parroquia Paez	64
191	Parroquia Libertad	65
192	Parroquia Dolores	65
193	Parroquia Palacios Fajardo	65
194	Parroquia Santa Rosa	65
195	Parroquia Simón Rodríguez	65
196	Parroquia Ciudad de Nutrias	66
197	Parroquia El Regalo	66
198	Parroquia Puerto de Nutrias	66
199	Parroquia Santa Catalina	66
200	Parroquia Simón Bolívar	66
201	Parroquia El Cantón	56
202	Parroquia Santa Cruz de Guacas	56
203	Parroquia Puerto Vivas	56
204	Parroquia Cachamay	67
205	Parroquia Chirica	67
206	Parroquia Dalla Costa	67
207	Parroquia Once de Abril	67
208	Parroquia Simón Bolívar	67
209	Parroquia Unare	67
210	Parroquia Universidad	67
211	Parroquia Vista al Sol	67
212	Parroquia Pozo Verde	67
213	Parroquia Yocoima	67
214	Sección Capital Cedeño	68
215	Parroquia Altagracia	68
216	Parroquia Ascensión Farreras	68
217	Parroquia Guaniamo	68
218	Parroquia La Urbana	68
219	Parroquia Pijiguaos	68
220	Sección Capital Gran Sabana	70
221	Parroquia Ikabarú	70
222	Parroquia Agua Salada	71
223	Parroquia Catedral	71
224	Parroquia José Antonio Páez	71
225	Parroquia La Sabanita	71
226	Parroquia Marhuanta	71
227	Parroquia Vista Hermosa	71
228	Parroquia Orinoco	71
229	Parroquia Panapana	71
230	Parroquia Zea	71
231	Sección Capital Piar	72
232	Parroquia Andrés Eloy Blanco	72
233	Parroquia Pedro Cova	72
234	Sección Capital Raúl Leoni	73
235	Parroquia Barceloneta	73
236	Parroquia San Francisco	73
237	Parroquia Santa Bárbara	73
238	Sección Capital Roscio	74
239	Parroquia Salom	74
240	Sección Capital Sifontes	75
241	Parroquia Dalla Costa	75
242	Parroquia San Isidro	75
243	Sección Capital Sucre	76
244	Parroquia Aripao	76
245	Parroquia Guarataro	76
246	Parroquia Las Majadas	76
247	Parroquia Moitaco	76
248	Parroquia Urbana Bejuma	78
249	Parroquia No Urbana Canoabo	78
250	Parroquia No Urbana Simón Bolívar	78
251	Parroquia Urbana Güigüe	79
252	Parroquia No Urbana Belén	79
253	Parroquia No Urbana Tacarigua	79
254	Parroquia Urbana Aguas Calientes	81
255	Parroquia Urbana Mariara	81
256	Parroquia Urbana Ciudad Alianza	80
257	Parroquia Urbana Guacara	80
258	Parroquia No Urbana Yagua	80
259	Parroquia Urbana Morón	82
260	Parroquia No Urbana Urama	82
261	Parroquia Urbana Tocuyito	83
262	Parroquia Urbana Independencia	83
263	Parroquia Urbana Los Guayos	84
264	Parroquia Urbana Miranda	86
265	Parroquia Urbana Montalbán	87
266	Parroquia Urbana Naguanagua	85
267	Parroquia Urbana Bartolomé Salom	88
268	Parroquia Urbana Democracia	88
269	Parroquia Urbana Fraternidad	88
270	Parroquia Urbana Goaigoaza	88
271	Parroquia Urbana Juan José Flores	88
272	Parroquia Urbana Unión	88
273	Parroquia No Urbana Borburata	88
274	Parroquia No Urbana Patanemo	88
275	Parroquia Urbana San Diego	89
276	Parroquia Urbana San Joaquín	90
277	Parroquia Urbana Candelaria	91
278	Parroquia Urbana Catedral	91
279	Parroquia Urbana El Socorro	91
280	Parroquia Urbana Miguel Peña	91
281	Parroquia Urbana Rafael Urdaneta	91
282	Parroquia Urbana San Blas	91
283	Parroquia Urbana San José	91
284	Parroquia Urbana Santa Rosa	91
285	Parroquia No Urbana Negro Primero	91
286	Parroquia Cojedes	92
287	Parroquia Juan de Mata Suárez	92
288	Parroquia Tinaquillo	93
289	Parroquia El Baúl	94
290	Parroquia Sucre	94
291	Parroquia Macapo	95
292	Parroquia La Aguadita	95
293	Parroquia El Pao	96
294	Parroquia Libertad de Cojedes	97
295	Parroquia El Amparo	97
296	Parroquia Rómulo Gallegos	98
297	Parroquia San Carlos de Austria	99
298	Parroquia Juan Angel Bravo	99
299	Parroquia Manuel Manrique	99
300	Parroquia General en Jefe José Laurencio Silva	100
301	Parroquia Curiapo	101
302	Parroquia Almirante Luis Brión	101
303	Parroquia Francisco Aniceto Lugo	101
304	Parroquia Manuel Renaud	101
305	Parroquia Padre Barral	101
306	Parroquia Santos de Abelgas	101
307	Parroquia Imataca	102
308	Parroquia Cinco de Julio	102
309	Parroquia Juan Bautista Arismendi	102
310	Parroquia Manuel Piar	102
311	Parroquia Rómulo Gallegos	102
312	Parroquia Pedernales	103
313	Parroquia Luis Beltrán Prieto Figueroa	103
314	Parroquia San José	104
315	Parroquia José Vidal Marcano	104
316	Parroquia Juan Millán	104
317	Parroquia Leonardo Ruíz Pineda	104
318	Parroquia Mariscal Antonio José de Sucre	104
319	Parroquia Monseñor Argimiro García	104
320	Parroquia San Rafael	104
321	Parroquia Virgen del Valle	104
322	Parroquia Altagracia	1
323	Parroquia Antímano	1
324	Parroquia Candelaria	1
325	Parroquia Caricuao	1
326	Parroquia Catedral	1
327	Parroquia Coche	1
328	Parroquia El Junquito	1
329	Parroquia EL Paraíso	1
330	Parroquia El Recreo	1
331	Parroquia El Valle	1
332	Parroquia La Pastora	1
333	Parroquia La Vega	1
334	Parroquia Macarao	1
335	Parroquia San Agustín	1
336	Parroquia San Bernardino	1
337	Parroquia San José	1
338	Parroquia San Juan	1
339	Parroquia San Pedro	1
340	Parroquia Santa Rosalía	1
341	Parroquia Santa Teresa	1
342	Parroquia Sucre	1
343	Parroquia 23 de Enero	1
344	Parroquia San Juan de los Cayos	105
345	Parroquia Capadare	105
346	Parroquia La Pastora	105
347	Parroquia Libertador	105
348	Parroquia San Luis	106
349	Parroquia Aracua	106
350	Parroquia La Peña	106
351	Parroquia Capatárida	107
352	Parroquia Bariro	107
353	Parroquia Borojó	107
354	Parroquia Guajiro	107
355	Parroquia Seque	107
356	Parroquia Zazárida	107
357	Parroquia Carirubana	109
358	Parroquia Norte	109
359	Parroquia Punta Cardón	109
360	Parroquia Santa Ana	109
361	Parroquia La Vela de Coro	110
362	Parroquia Acurigua	110
363	Parroquia Guaibacoa	110
364	Parroquia Las Calderas	110
365	Parroquia Macoruca	110
366	Parroquia Pedregal	112
367	Parroquia Agua Clara	112
368	Parroquia Avaria	112
369	Parroquia Piedra Grande	112
370	Parroquia Purureche	112
371	Parroquia Pueblo Nuevo	113
372	Parroquia Adícora	113
373	Parroquia Baraived	113
374	Parroquia Buena Vista	113
375	Parroquia Jadacaquiva	113
376	Parroquia Moruy	113
377	Parroquia Adaure	113
378	Parroquia El Hato	113
379	Parroquia El Vínculo	113
380	Parroquia Churuguara	114
381	Parroquia Agua Larga	114
382	Parroquia El Paují	114
383	Parroquia Independencia	114
384	Parroquia Mapararí	114
385	Parroquia Jacura	115
386	Parroquia Agua Linda	115
387	Parroquia Araurima	115
388	Parroquia Los Taques	116
389	Parroquia Judibana	116
390	Parroquia Mene de Mauroa	117
391	Parroquia Casigua	117
392	Parroquia San Félix	117
393	Parroquia San Antonio	118
394	Parroquia San Gabriel	118
395	Parroquia Santa Ana	118
396	Parroquia Guzmán Guillermo	118
397	Parroquia Mitare	118
398	Parroquia Río Seco	118
399	Parroquia Sabaneta	118
400	Parroquia Chichiriviche	119
401	Parroquia Boca de Tocuyo	119
402	Parroquia Tocuyo de la Costa	119
403	Parroquia Cabure	121
404	Parroquia Colina	121
405	Parroquia Curimagua	121
406	Parroquia Píritu	122
407	Parroquia San José de la Costa	122
408	Parroquia Tucacas	124
409	Parroquia Boca de Aroa	124
410	Parroquia Sucre	125
411	Parroquia Pecaya	125
412	Parroquia Santa Cruz de Bucaral	127
413	Parroquia El Charal	127
414	Parroquia Las Vegas del Tuy	127
415	Parroquia Urumaco	128
416	Parroquia Bruzual	128
417	Parroquia Puerto Cumarebo	129
418	Parroquia La Ciénaga	129
419	Parroquia La Soledad	129
420	Parroquia Pueblo Cumarebo	129
421	Parroquia Zazárida	129
422	Parroquia Capital Camaguán	130
423	Parroquia Puerto Miranda	130
424	Parroquia Uverito	130
425	Parroquia Chaguaramas	131
426	Parroquia El Socorro	132
427	Parroquia Capital San Gerónimo de Guayabal	142
428	Parroquia Cazorla	142
429	Parroquia Capital Valle de La Pascua	139
430	Parroquia Espino	139
431	Parroquia Capital Las Mercedes	138
432	Parroquia Cabruta	138
433	Parroquia Santa Rita de Manapire	138
434	Parroquia Capital El Sombrero	137
435	Parroquia Sosa	137
436	Parroquia Capital Calabozo	133
437	Parroquia El Calvario	133
438	Parroquia El Rastro	133
439	Parroquia Guardatinajas	133
440	Parroquia Capital Altagracia de Orituco	135
441	Parroquia Lezama	135
442	Parroquia Libertad de Orituco	135
443	Parroquia Paso Real de Macaira	135
444	Parroquia San Francisco de Macaira	135
445	Parroquia San Rafael de Orituco	135
446	Parroquia Soublette	135
447	Parroquia Capital Ortiz	141
448	Parroquia San Francisco de Tiznado	141
449	Parroquia San José de Tiznado	141
450	Parroquia San Lorenzo de Tiznado	141
451	Parroquia Capital Tucupido	134
452	Parroquia San Rafael de Laya	134
453	Parroquia Capital San Juan de Los Morros	136
454	Parroquia Cantagallo	136
455	Parroquia Parapara	136
456	Parroquia San José de Guaribe	143
457	Parroquia Capital Santa María de Ipire	144
458	Parroquia Altamira	144
459	Parroquia Capital Zaraza	140
460	Parroquia San José de Unare	140
461	Parroquia Pío Tamayo	145
462	Parroquia Quebrada Honda de Guache	145
463	Parroquia Yacambú	145
464	Parroquia Fréitez	146
465	Parroquia José María Blanco	146
466	Parroquia Catedral	147
467	Parroquia Concepción	147
468	Parroquia El Cují	147
469	Parroquia Juan de Villegas	147
470	Parroquia Santa Rosa	147
471	Parroquia Tamaca	147
472	Parroquia Unión	147
473	Parroquia Aguedo Felipe Alvarado	147
474	Parroquia Buena Vista	147
475	Parroquia Juárez	147
476	Parroquia Juan Bautista Rodríguez	148
477	Parroquia Cuara	148
478	Parroquia Diego de Lozada	148
479	Parroquia Paraíso de San José	148
480	Parroquia San Miguel	148
481	Parroquia Tintorero	148
482	Parroquia José Bernardo Dorante	148
483	Parroquia Coronel Mariano Peraza	148
484	Parroquia Bolívar	149
485	Parroquia Anzoátegui	149
486	Parroquia Guarico	149
487	Parroquia Hilario Luna y Luna	149
488	Parroquia Humocaro Alto	149
489	Parroquia Humocaro Bajo	149
490	Parroquia La Candelaria	149
491	Parroquia Morán	149
492	Parroquia Cabudare	150
493	Parroquia José Gregorio Bastidas	150
494	Parroquia Agua Viva	150
495	Parroquia Sarare	151
496	Parroquia Buría	151
497	Parroquia Gustavo Vegas León	151
498	Parroquia Trinidad Samuel	152
499	Parroquia Antonio Díaz	152
500	Parroquia Camacaro	152
501	Parroquia Castañeda	152
502	Parroquia Cecilio Zubillaga	152
503	Parroquia Chiquinquirá	152
504	Parroquia El Blanco	152
505	Parroquia Espinoza de los Monteros	152
506	Parroquia Lara	152
507	Parroquia Las Mercedes	152
508	Parroquia Manuel Morillo	152
509	Parroquia Montaña Verde	152
510	Parroquia Montes de Oca	152
511	Parroquia Torres	152
512	Parroquia Heriberto Arroyo	152
513	Parroquia Reyes Vargas	152
514	Parroquia Altagracia	152
515	Parroquia Siquisique	153
516	Parroquia Moroturo	153
517	Parroquia San Miguel	153
518	Parroquia Xaguas	153
519	Parroquia Presidente Betancourt	154
520	Parroquia Presidente Páez 	154
521	Parroquia Presidente Rómulo Gallegos	154
522	Parroquia Gabriel Picón González	154
523	Parroquia Héctor Amable Mora	154
524	Parroquia José Nucete Sardi	154
525	Parroquia Pulido Méndez	154
526	Parroquia Capital Antonio Pinto Salinas 	156
527	Parroquia Mesa Bolívar	156
528	Parroquia Mesa de Las Palmas	156
529	Parroquia Capital Aricagua	157
530	Parroquia San Antonio	157
531	Parroquia Capital Arzobispo Chacón	158
532	Parroquia Capurí	158
533	Parroquia Chacantá	158
534	Parroquia El Molino	158
535	Parroquia Guaimaral	158
536	Parroquia Mucutuy	158
537	Parroquia Mucuchachí	158
538	Parroquia Fernández Peña	159
539	Parroquia Matriz	159
540	Parroquia Montalbán	159
541	Parroquia Acequias	159
542	Parroquia Jají	159
543	Parroquia La Mesa	159
544	Parroquia San José del Sur	159
545	Parroquia Capital Caracciolo Parra Olmedo	160
546	Parroquia Florencio Ramírez	160
547	Parroquia Capital Cardenal Quintero	161
548	Parroquia Las Piedras	161
549	Parroquia Capital Guaraque	162
550	Parroquia Mesa de Quintero	162
551	Parroquia Río Negro	162
552	Parroquia Capital Julio César Salas	163
553	Parroquia Palmira	163
554	Parroquia Capital Justo Briceño	164
555	Parroquia San Cristóbal de Torondoy	164
556	Parroquia Antonio Spinetti Dini	165
557	Parroquia Arias	165
558	Parroquia Caracciolo Parra Pérez	165
559	Parroquia Domingo Peña	165
560	Parroquia El Llano	165
561	Parroquia Gonzalo Picón Febres	165
562	Parroquia Jacinto Plaza	165
563	Parroquia Juan Rodríguez Suárez	165
564	Parroquia Lasso de la Vega	165
565	Parroquia Mariano Picón Salas	165
566	Parroquia Milla	165
567	Parroquia Osuna Rodríguez	165
568	Parroquia Sagrario	165
569	Parroquia El Morro	165
570	Parroquia Los Nevados	165
571	Parroquia Capital Miranda	166
572	Parroquia Andrés Eloy Blanco	166
573	Parroquia La Venta	166
574	Parroquia Piñango	166
575	Parroquia Capital Obispo Ramos de Lora	167
576	Parroquia Eloy Paredes	167
577	Parroquia San Rafael de Alcázar	167
578	Parroquia Capital Rangel	170
579	Parroquia Cacute	170
580	Parroquia La Toma	170
581	Parroquia Mucurubá	170
582	Parroquia San Rafael	170
583	Parroquia Capital Rivas Dávila	171
584	Parroquia Gerónimo Maldonado	171
585	Parroquia Capital Sucre	173
586	Parroquia Chiguará	173
587	Parroquia Estánquez	173
588	Parroquia La Trampa	173
589	Parroquia Pueblo Nuevo del Sur	173
590	Parroquia San Juan	173
591	Parroquia El Amparo	174
592	Parroquia El Llano	174
593	Parroquia San Francisco	174
594	Parroquia Tovar	174
595	Parroquia Capital Tulio Febres Cordero	175
596	Parroquia Independencia	175
597	Parroquia María de la Concepción Palacios Blanco	175
598	Parroquia Santa Apolonia	175
599	Parroquia Capital Zea	176
600	Parroquia Caño El Tigre	176
601	Parroquia Caucagua	177
602	Parroquia Aragüita	177
603	Parroquia Arévalo González	177
604	Parroquia Capaya	177
605	Parroquia El Café	177
606	Parroquia Marizapa	177
607	Parroquia Panaquire	177
608	Parroquia Ribas	177
609	Parroquia San José de Barlovento	178
610	Parroquia Cumbo	178
611	Parroquia Baruta	179
612	Parroquia El Cafetal	179
613	Parroquia Las Minas de Baruta	179
614	Parroquia Higuerote	180
615	Parroquia Curiepe	180
616	Parroquia Tacarigua	180
617	Parroquia Mamporal	181
618	Parroquia Carrizal	182
619	Parroquia Chacao	183
620	Parroquia Charallave	184
621	Parroquia Las Brisas	184
622	Parroquia El Hatillo	185
623	Parroquia Los Teques	186
624	Parroquia Altagracia de La Montaña	186
625	Parroquia Cecilio Acosta	186
626	Parroquia El Jarillo	186
627	Parroquia Paracotos	186
628	Parroquia San Pedro	186
629	Parroquia Tácata	186
630	Parroquia Santa Teresa del Tuy	187
631	Parroquia El Cartanal/	187
632	Parroquia Ocumare del Tuy	188
633	Parroquia La Democracia	188
634	Parroquia Santa Bárbara	188
635	Parroquia San Antonio de Los Altos	189
636	Parroquia Río Chico	190
637	Parroquia El Guapo	190
638	Parroquia Tacarigua de La Laguna	190
639	Parroquia Paparo	190
640	Parroquia San Fernando del Guapo	190
641	Parroquia Santa Lucía	191
642	Parroquia Cúpira	192
643	Parroquia Machurucuto	192
644	Parroquia Guarenas	193
645	Parroquia San Francisco de Yare	194
646	Parroquia San Antonio de Yare	194
647	Parroquia Petare	195
648	Parroquia Caucagüita	195
649	Parroquia Fila de Mariches	195
650	Parroquia La Dolorita	195
651	Parroquia Leoncio Martínez	195
652	Parroquia Cúa	196
653	Parroquia Nueva Cúa	196
654	Parroquia Guatire	197
655	Parroquia Bolívar	197
656	Parroquia Capital Acosta 	198
657	Parroquia San Francisco	198
658	Parroquia Capital Caripe	201
659	Parroquia El Guácharo	201
660	Parroquia La Guanota	201
661	Parroquia Sabana de Piedra	201
662	Parroquia San Agustín	201
663	Parroquia Teresén	201
664	Parroquia Capital Cedeño	202
665	Parroquia Areo	202
666	Parroquia San Félix	202
667	Parroquia Viento Fresco	202
668	Parroquia Capital Ezequiel Zamora	203
669	Parroquia El Tejero	203
670	Parroquia Capital Libertador	204
671	Parroquia Chaguaramas	204
672	Parroquia Las Alhuacas	204
673	Parroquia Tabasca	204
674	Parroquia Capital Maturín	205
675	Parroquia Alto de los Godos	205
676	Parroquia Boquerón	205
677	Parroquia Las Cocuizas	205
678	Parroquia San Simón	205
679	Parroquia Santa Cruz	205
680	Parroquia El Corozo	205
681	Parroquia El Furrial	205
682	Parroquia Jusepín	205
683	Parroquia La Pica	205
684	Parroquia San Vicente	205
685	Parroquia Capital Piar	206
686	Parroquia Aparicio	206
687	Parroquia Chaguaramal	206
688	Parroquia El Pinto	206
689	Parroquia Guanaguana	206
690	Parroquia La Toscana	206
691	Parroquia Taguaya	206
692	Parroquia Capital Punceres	207
693	Parroquia Cachipo	207
694	Parroquia Capital Sotillo	209
695	Parroquia Los Barrancos de Fajardo	209
696	Parroquia Capital Díaz	213
697	Parroquia Zabala	213
698	Parroquia Capital García	214
699	Parroquia Francisco Fajardo	214
700	Parroquia Capital Gómez	215
701	Parroquia Bolívar	215
702	Parroquia Guevara	215
703	Parroquia Matasiete	215
704	Parroquia Sucre	215
705	Parroquia Capital Maneiro	216
706	Parroquia Aguirre	216
707	Parroquia Capital Marcano	217
708	Parroquia Adrián	217
709	Parroquia Capital Península de Macanao	219
710	Parroquia San Francisco	219
711	Parroquia Capital Tubores	220
712	Parroquia Los Barales	220
713	Parroquia Capital Villalba	221
714	Parroquia Vicente Fuentes	221
715	Parroquia Capital Araure	223
716	Parroquia Río Acarigua	223
717	Parroquia Capital Esteller	224
718	Parroquia Uveral	224
719	Parroquia Capital Guanare	225
720	Parroquia Córdoba	225
721	Parroquia San José de la Montaña	225
722	Parroquia San Juan de Guanaguanare	225
723	Parroquia Virgen de la Coromoto	225
724	Parroquia Capital Guanarito	226
725	Parroquia Trinidad de la Capilla	226
726	Parroquia Divina Pastora	226
727	Parroquia Capital Mons.José Vicente de Unda	227
728	Parroquia Peña Blanca	227
729	Parroquia Capital Ospino	228
730	Parroquia Aparición	228
731	Parroquia La Estación	228
732	Parroquia Capital Páez	229
733	Parroquia Payara	229
734	Parroquia Pimpinela	229
735	Parroquia Ramón Peraza	229
736	Parroquia Capital Papelón	230
737	Parroquia Caño Delgadito	230
738	Parroquia Capital San Genaro de Boconoito	231
739	Parroquia Antolín Tovar	231
740	Parroquia Capital San Rafael de Onoto	232
741	Parroquia Santa Fe	232
742	Parroquia Thermo Morles	232
743	Parroquia Capital Santa Rosalía	233
744	Parroquia Florida	233
745	Parroquia Capital Sucre	234
746	Parroquia Concepción	234
747	Parroquia San Rafael de Palo Alzado	234
748	Parroquia Uvencio Antonio Velásquez	234
749	Parroquia San José de Saguaz	234
750	Parroquia Villa Rosa	234
751	Parroquia Capital Turén	235
752	Parroquia Canelones	235
753	Parroquia Santa Cruz	235
754	Parroquia San Isidro Labrador	235
755	Parroquia Mariño	236
756	Parroquia Rómulo Gallegos	236
757	Parroquia San José de Aerocuar	237
758	Parroquia Tavera Acosta	237
759	Parroquia Río Caribe	238
760	Parroquia Antonio José de Sucre	238
761	Parroquia El Morro de Puerto Santo	238
762	Parroquia Puerto Santo	238
763	Parroquia San Juan de Las Galdonas	238
764	Parroquia El Pilar	239
765	Parroquia El Rincón	239
766	Parroquia General Francisco Antonio Vásquez	239
767	Parroquia Guaraúnos	239
768	Parroquia Tunapuicito	239
769	Parroquia Unión	239
770	Parroquia Bolívar	240
771	Parroquia Macarapana	240
772	Parroquia Santa Catalina	240
773	Parroquia Santa Rosa	240
774	Parroquia Santa Teresa	240
775	Parroquia Yaguaraparo	241
776	Parroquia El Paujil	241
777	Parroquia Libertad	241
778	Parroquia Araya	242
779	Parroquia Chacopata	242
780	Parroquia Manicuare	242
781	Parroquia Tunapuy	243
782	Parroquia Campo Elías	243
783	Parroquia Irapa	244
784	Parroquia Campo Claro	244
785	Parroquia Marabal	244
786	Parroquia San Antonio de Irapa	244
787	Parroquia Soro	244
788	Parroquia Cumanacoa	246
789	Parroquia Arenas	246
790	Parroquia Aricagua	246
791	Parroquia Cocollar	246
792	Parroquia San Fernando	246
793	Parroquia San Lorenzo	246
794	Parroquia Villa Frontado (Muelle de Cariaco)	247
795	Parroquia Catuaro	247
796	Parroquia Rendón	247
797	Parroquia Santa Cruz	247
798	Parroquia Santa María	247
799	Parroquia Altagracia	248
800	Parroquia Ayacucho	248
801	Parroquia Santa Inés	248
802	Parroquia Valentín Valiente	248
803	Parroquia San Juan	248
804	Parroquia Raúl Leoni	248
805	Parroquia Gran Mariscal 	248
806	Parroquia Güiria	249
807	Parroquia Bideau	249
808	Parroquia Cristóbal Colón	249
809	Parroquia Punta de Piedras	249
810	Parroquia Ayacucho	252
811	Parroquia Rivas Berti	252
812	Parroquia San Pedro del Río	252
813	Parroquia Bolívar	253
814	Parroquia Palotal	253
815	Parroquia Juan Vicente Gómez	253
816	Parroquia Isaías Medina Angarita	253
817	Parroquia Cárdenas	254
818	Parroquia Amenodoro Rangel Lamús	254
819	Parroquia La Florida	254
820	Parroquia Fernández Feo	256
821	Parroquia Alberto Adriani	256
822	Parroquia Santo Domingo	256
823	Parroquia García de Hevia	258
824	Parroquia Boca de Grita	258
825	Parroquia José Antonio Páez	258
826	Parroquia Independencia	261
827	Parroquia Juan Germán Roscio	261
828	Parroquia Román Cárdenas	261
829	Parroquia Jáuregui	262
830	Parroquia Emilio Constantino Guerrero	262
831	Parroquia Monseñor Miguel Antonio Salas	262
832	Parroquia Junín	263
833	Parroquia La Petrólea	263
834	Parroquia Quinimarí	263
835	Parroquia Bramón	263
836	Parroquia Libertad	264
837	Parroquia Cipriano Castro	264
838	Parroquia Manuel Felipe Rugeles	264
839	Parroquia Libertador	265
840	Parroquia Don Emeterio Ochoa	265
841	Parroquia Doradas	265
842	Parroquia San Joaquín de Navay	265
843	Parroquia Lobatera	266
844	Parroquia Constitución	266
845	Parroquia Panamericano	278
846	Parroquia La Palmita	278
847	Parroquia Pedro María Ureña	268
848	Parroquia Nueva Arcadia	268
849	Parroquia Samuel Darío Maldonado	270
850	Parroquia Boconó	270
851	Parroquia Hernández	270
852	Parroquia La Concordia	271
853	Parroquia Pedro María Morantes	271
854	Parroquia San Juan Bautista	271
855	Parroquia San Sebastián	271
856	Parroquia Dr. Francisco Romero Lobo	271
857	Parroquia Sucre	274
858	Parroquia Eleazar López Contreras	274
859	Parroquia San Pablo	274
860	Parroquia  Uribante	276
861	Parroquia Cárdenas	276
862	Parroquia Juan Pablo Peñaloza	276
863	Parroquia Potosí	276
864	Parroquia Santa Isabel	279
865	Parroquia Araguaney	279
866	Parroquia El Jagüito	279
867	Parroquia La Esperanza	279
868	Parroquia Boconó	280
869	Parroquia El Carmen	280
870	Parroquia Mosquey	280
871	Parroquia Ayacucho	280
872	Parroquia Burbusay	280
873	Parroquia General Rivas	280
874	Parroquia Guaramacal	280
875	Parroquia Vega de Guaramacal	280
876	Parroquia Monseñor Jáuregui	280
877	Parroquia Rafael Rangel	280
878	Parroquia San Miguel	280
879	Parroquia San José	280
880	Parroquia Sabana Grande	281
881	Parroquia Cheregüé	281
882	Parroquia Granados	281
883	Parroquia Chejendé	282
884	Parroquia Arnoldo Gabaldón	282
885	Parroquia Bolivia	282
886	Parroquia Carrillo	282
887	Parroquia Cegarra	282
888	Parroquia Manuel Salvador Ulloa	282
889	Parroquia San José	282
890	Parroquia Carache	283
891	Parroquia Cuicas	283
892	Parroquia La Concepción	283
893	Parroquia Panamericana	283
894	Parroquia Santa Cruz	283
895	Parroquia Escuque	284
896	Parroquia La Unión	284
897	Parroquia Sabana Libre	284
898	Parroquia Santa Rita	284
899	Parroquia El Socorro	285
900	Parroquia Antonio José de Sucre	285
901	Parroquia Los Caprichos	285
902	Parroquia Campo Elías	286
903	Parroquia Arnoldo Gabaldón	286
904	Parroquia Santa Apolonia	287
905	Parroquia El Progreso 	287
906	Parroquia La Ceiba 	287
907	Parroquia Tres de Febrero 	287
908	Parroquia El Dividive	288
909	Parroquia Agua Santa	288
910	Parroquia Agua Caliente	288
911	Parroquia El Cenizo	288
912	Parroquia Valerita	288
913	Parroquia Monte Carmelo	289
914	Parroquia Buena Vista	289
915	Parroquia Santa María del Horcón	289
916	Parroquia Motatán	290
917	Parroquia El Baño	290
918	Parroquia Jalisco	290
919	Parroquia Pampán	291
920	Parroquia Flor de Patria	291
921	Parroquia La Paz	291
922	Parroquia Santa Ana	291
923	Parroquia Pampanito	292
924	Parroquia La Concepción	292
925	Parroquia Pampanito II	292
926	Parroquia Betijoque	293
927	Parroquia La Pueblita	293
928	Parroquia Los Cedros	293
929	Parroquia José Gregorio Hernández	293
930	Parroquia Carvajal	294
931	Parroquia Antonio Nicolás Briceño	294
932	Parroquia Campo Alegre	294
933	Parroquia José Leonardo Suárez	294
934	Parroquia Sabana de Mendoza	295
935	Parroquia El Paraíso	295
936	Parroquia Junín	295
937	Parroquia Valmore Rodríguez	295
938	Parroquia Andrés Linares	296
939	Parroquia Chiquinquirá	296
940	Parroquia Cristóbal Mendoza	296
941	Parroquia Cruz Carrillo	296
942	Parroquia Matriz	296
943	Parroquia Monseñor Carrillo	296
944	Parroquia Tres Esquinas	296
945	Parroquia La Quebrada	297
946	Parroquia Cabimbú	297
947	Parroquia Jajó	297
948	Parroquia La Mesa	297
949	Parroquia Santiago	297
950	Parroquia Tuñame	297
951	Parroquia Juan Ignacio Montilla	298
952	Parroquia La Beatriz	298
953	Parroquia Mercedes Díaz	298
954	Parroquia San Luis	298
955	Parroquia La Puerta	298
956	Parroquia Mendoza	298
957	Parroquia Caraballeda	299
958	Parroquia Carayaca	299
959	Parroquia Caruao	299
960	Parroquia Catia La Mar	299
961	Parroquia El Junko	299
962	Parroquia La Guaira	299
963	Parroquia Macuto	299
964	Parroquia Maiquetía	299
965	Parroquia Naiguatá	299
966	Parroquia Urimare	299
967	Parroquia Carlos Soublette	299
968	Parroquia Capital Bruzual	302
969	Parroquia Campo Elías	302
970	Parroquia Capital Nirgua	308
971	Parroquia Salom	308
972	Parroquia Temerla	308
973	Parroquia Capital Peña	309
974	Parroquia San Andrés	309
975	Parroquia Capital San Felipe	310
976	Parroquia Albarico	310
977	Parroquia San Javier	310
978	Parroquia Capital Veroes	313
979	Parroquia El Guayabo	313
980	Parroquia Isla de Toas	314
981	Parroquia Monagas	314
982	Parroquia San Timoteo	315
983	Parroquia General Urdaneta	315
984	Parroquia Libertador	315
985	Parroquia Manuel Guanipa Matos	315
986	Parroquia Marcelino Briceño	315
987	Parroquia Pueblo Nuevo	315
988	Parroquia Ambrosio	316
989	Parroquia Carmen Herrera	316
990	Parroquia Germán Ríos Linares	316
991	Parroquia La Rosa	316
992	Parroquia Jorge Hernández	316
993	Parroquia Rómulo Betancourt	316
994	Parroquia San Benito	316
995	Parroquia Arístides Calvani	316
996	Parroquia Punta Gorda	316
997	Parroquia Encontrados	317
998	Parroquia Udón Pérez	317
999	Parroquia San Carlos del Zulia	318
1000	Parroquia Moralito	318
1001	Parroquia Santa Bárbara	318
1002	Parroquia Santa Cruz del Zulia	318
1003	Parroquia Urribarri	318
1004	Parroquia Simón Rodríguez	319
1005	Parroquia Carlos Quevedo	319
1006	Parroquia Francisco Javier Pulgar	319
1007	Parroquia La Concepción	320
1008	Parroquia José Ramón Yepes	320
1009	Parroquia Mariano Parra León	320
1010	Parroquia San José	320
1011	Parroquia Jesús María Semprún	321
1012	Parroquia Barí	321
1013	Parroquia Concepción	322
1014	Parroquia Andrés Bello	322
1015	Parroquia Chiquinquirá	322
1016	Parroquia El Carmelo	322
1017	Parroquia Potreritos	322
1018	Parroquia Alonso de Ojeda	323
1019	Parroquia Libertad	323
1020	Parroquia Campo Lara	323
1021	Parroquia Eleazar López Contreras	323
1022	Parroquia Venezuela	323
1023	Parroquia Libertad	324
1024	Parroquia Bartolomé de las Casas	324
1025	Parroquia Río Negro	324
1026	Parroquia San José de Perijá	324
1027	Parroquia San Rafael	325
1028	Parroquia La Sierrita	325
1029	Parroquia Las Parcelas	325
1030	Parroquia Luis de Vicente	325
1031	Parroquia Monseñor Marcos Sergio Godoy	325
1032	Parroquia Ricaurte	325
1033	Parroquia Tamare	325
1034	Parroquia Antonio Borjas Romero	326
1035	Parroquia Bolívar	326
1036	Parroquia Cacique Mara	326
1037	Parroquia Caracciolo Parra Pérez	326
1038	Parroquia Cecilio Acosta	326
1039	Parroquia Cristo de Aranza	326
1040	Parroquia Coquivacoa	326
1041	Parroquia Chiquinquirá	326
1042	Parroquia Francisco Eugenio Bustamante	326
1043	Parroquia Idelfonso Vásquez	326
1044	Parroquia Juana de Avila	326
1045	Parroquia Luis Hurtado Higuera	326
1046	Parroquia Manuel Dagnino	326
1047	Parroquia Olegario Villalobos	326
1048	Parroquia Raúl Leoni	326
1049	Parroquia Santa Lucía	326
1050	Parroquia Venancio Pulgar	326
1051	Parroquia San Isidro	326
1052	Parroquia Altagracia	327
1053	Parroquia Ana María Campos	327
1054	Parroquia Faría	327
1055	Parroquia San Antonio	327
1056	Parroquia San José	327
1057	Parroquia Sinamaica	328
1058	Parroquia Alta Guajira	328
1059	Parroquia Elías Sánchez Rubio	328
1060	Parroquia Guajira	328
1061	Parroquia El Rosario	329
1062	Parroquia Donaldo García	329
1063	Parroquia Sixto Zambrano	329
1064	Parroquia San Francisco	330
1065	Parroquia El Bajo/	330
1066	Parroquia Domitila Flores	330
1067	Parroquia Francisco Ochoa	330
1068	Parroquia Los Cortijos	330
1069	Parroquia Marcial Hernández	330
1070	Parroquia Jose Domingo Rus	330
1071	Parroquia Santa Rita	331
1072	Parroquia El Mene	331
1073	Parroquia José Cenovio Urribarri	331
1074	Parroquia Pedro Lucas Urribarri	331
1075	Parroquia Manuel Manrique	332
1076	Parroquia Rafael María Baralt	332
1077	Parroquia Rafael Urdaneta	332
1078	Parroquia Bobures	333
1079	Parroquia El Batey	333
1080	Parroquia Gibraltar	333
1081	Parroquia Heras	333
1082	Parroquia Monseñor Arturo Celestino Alvarez	333
1083	Parroquia Rómulo Gallegos	333
1084	Parroquia La Victoria	334
1085	Parroquia Rafael Urdaneta	334
1086	Parroquia Raúl Cuenca	334
\.


--
-- Name: parroquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('parroquia_id_seq', 1086, true);


--
-- Data for Name: patologia; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY patologia (id, usuario_id, fecha_registro, fecha_modificado, descripcion, observacion) FROM stdin;
\.


--
-- Name: patologia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('patologia_id_seq', 1, false);


--
-- Data for Name: perfil; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY perfil (id, usuario_id, fecha_registro, fecha_modificado, perfil, estado, plantilla) FROM stdin;
1	\N	2014-03-13 12:19:42.852111-04:30	2014-03-13 12:19:42.852111-04:30	Super User	1	default
2	\N	2014-03-13 12:20:07.544255-04:30	2014-03-13 12:20:07.544255-04:30	Usuario Full	1	default
3	\N	2014-03-13 12:20:07.544255-04:30	2014-03-13 12:20:07.544255-04:30	Usuario	1	default
\.


--
-- Name: perfil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('perfil_id_seq', 1, true);


--
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY persona (id, usuario_id, fecha_registro, fecha_modificado, cedula, nombre1, nombre2, apellido1, apellido2, nacionalidad, sexo, fecha_nacimiento, pais_id, estado_id, municipio_id, parroquia_id, direccion_habitacion, estado_civil, celular, telefono, correo_electronico, grupo_sanguineo, fotografia) FROM stdin;
1	\N	2014-03-13 12:03:49.841971-04:30	2014-03-13 12:03:49.841971-04:30	20643647	Super	\N	Administrador	\N	V	M	1990-11-12	240	69	223	715	URB 12 DE OCTUBRE	S	04167012111	\N	tuaalexis@gmail.com	A	default.png
4	\N	2014-03-16 01:14:45.552613-04:30	2014-03-16 01:14:45.552613-04:30	16753367	Javier	Enrique	León	\N	V	M	1984-12-09	240	69	229	732	Av principal	c	04162546908	02556217013	\N	AB-	default.png
\.


--
-- Name: persona_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('persona_id_seq', 4, true);


--
-- Data for Name: profesion; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY profesion (id, usuario_id, fecha_registro, fecha_modificado, nombre, observacion) FROM stdin;
\.


--
-- Name: profesion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('profesion_id_seq', 1, false);


--
-- Data for Name: proveedor; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY proveedor (id, usuario_id, fecha_registro, fecha_modificado, rif, razon_social, nombre_corto, pais_id, estado_id, municipio_id, parroquia_id, direccion, celular, telefono1, telefono2, fax, correo_electronico, observacion) FROM stdin;
\.


--
-- Name: proveedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('proveedor_id_seq', 1, false);


--
-- Data for Name: proveedor_medico; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY proveedor_medico (id, medico_id, proveedor_id) FROM stdin;
\.


--
-- Name: proveedor_medico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('proveedor_medico_id_seq', 1, false);


--
-- Data for Name: recaudo; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recaudo (id, usuario_id, fecha_registro, fecha_modificado, nombre, tipo, observacion) FROM stdin;
\.


--
-- Data for Name: recaudo_beneficiario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recaudo_beneficiario (id, beneficiario_id, recaudo_id, estado) FROM stdin;
\.


--
-- Name: recaudo_beneficiario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recaudo_beneficiario_id_seq', 1, false);


--
-- Name: recaudo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recaudo_id_seq', 1, false);


--
-- Data for Name: recaudo_reembolso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recaudo_reembolso (id, recaudo_id, codigo_solicitud, estado) FROM stdin;
\.


--
-- Name: recaudo_reembolso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recaudo_reembolso_id_seq', 1, false);


--
-- Data for Name: recaudo_solicitud_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recaudo_solicitud_medicina (id, recaudo_id, codigo_solicitud, estado) FROM stdin;
\.


--
-- Name: recaudo_solicitud_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recaudo_solicitud_medicina_id_seq', 1, false);


--
-- Data for Name: recaudo_solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recaudo_solicitud_servicio (id, recaudo_id, codigo_solicitud, estado) FROM stdin;
\.


--
-- Name: recaudo_solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recaudo_solicitud_servicio_id_seq', 1, false);


--
-- Data for Name: recaudo_titular; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recaudo_titular (id, titular_id, recaudo_id, estado) FROM stdin;
\.


--
-- Name: recaudo_titular_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recaudo_titular_id_seq', 1, false);


--
-- Data for Name: recurso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recurso (id, usuario_id, fecha_registro, fecha_modificado, modulo, controlador, accion, recurso, descripcion, activo) FROM stdin;
1	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	*	\N	\N	*	Comodín para la administración total (usar con cuidado)	1
2	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	dashboard	*	*	dashboard/*/*	Página principal del sistema	1
3	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	mi_cuenta	*	sistema/mi_cuenta/*	Gestión de la cuenta del usuario logueado	1
4	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	acceso	*	sistema/acceso/*	Submódulo para la gestión de ingresos al sistema	1
5	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	auditoria	*	sistema/auditoria/*	Submódulo para el control de las acciones de los usuarios	1
6	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	backup	*	sistema/backup/*	Submódulo para la gestión de las copias de seguridad	1
7	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	mantenimiento	*	sistema/mantenimiento/*	Submódulo para el mantenimiento de las tablas	1
8	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	menu	*	sistema/menu/*	Submódulo del sistema para la creación de menús	1
9	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	perfil	*	sistema/perfil/*	Submódulo del sistema para los perfiles de usuarios	1
10	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	privilegio	*	sistema/privilegio/*	Submódulo del sistema para asignar recursos a los perfiles	1
11	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	recurso	*	sistema/recurso/*	Submódulo del sistema para la gestión de los recursos	1
12	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	usuario	*	sistema/usuario/*	Submódulo para la administración de los usuarios del sistema	1
13	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	sucesos	*	sistema/suceso/*	Submódulo para el listado de los logs del sistema	1
14	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	sistema	configuracion	*	sistema/configuracion/*	Submódulo para la configuración de la aplicación (.ini)	1
15	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	empresa	*	config/empresa/*	Submódulo para la configuración de la información de la empresa	1
16	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	sucursal	*	config/sucursal/*	Submódulo para la administración de las sucursales	1
18	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	profesion	*	config/profesion/*	Configura las profesiones	1
19	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	cargo	*	config/cargo/*	Gestión de Cargos de empleados	1
20	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	cobertura	*	config/cobertura/*	Gestión de las coberturas de las polizas	1
21	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	departamento	*	config/departamento/*	Gestión de departamento	1
22	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	discapacidad	*	config/discapacidad/*	Gestión de la discapacidad	1
23	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	patologia	*	config/patologia/*	Gestión de las Patologias	1
24	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	config	recaudo	*	config/recaudo/*	Gestión de los Recaudos	1
25	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	beneficiarios	titular	*	beneficiarios/titular/*	Gestión del personal de la empresa	1
26	\N	2014-03-13 13:24:45.006859-04:30	2014-03-13 13:24:45.006859-04:30	beneficiarios	beneficiario	*	beneficiarios/beneficiario/*	Página para la gestión de beneficiarios del sistema	1
27	\N	2014-03-16 13:19:39.864679-04:30	2014-03-16 13:19:39.864679-04:30	solicitudes	orden_medica	*	solicitudes/orden_medica/*	Página para la gestión de Ordenes Medicas	1
28	\N	2014-03-16 13:19:39.864679-04:30	2014-03-16 13:19:39.864679-04:30	solicitudes	atencion_primaria	*	solicitudes/atencion_primaria/*	Página para la gestión de Ordenes Medicas	1
29	\N	2014-03-16 13:19:39.864679-04:30	2014-03-16 13:19:39.864679-04:30	solicitudes	examen_laboratorio	*	solicitudes/examen_laboratorio/*	Página para la gestión de Ordenes Medicas	1
30	\N	2014-03-16 13:19:39.864679-04:30	2014-03-16 13:19:39.864679-04:30	solicitudes	examen_imagen	*	solicitudes/examen_imagen/*	Página para la gestión de Ordenes Medicas	1
31	\N	2014-03-16 13:19:39.864679-04:30	2014-03-16 13:19:39.864679-04:30	solicitudes	reembolso	*	solicitudes/reembolso/*	Página para la gestión de Ordenes Medicas	1
32	\N	2014-03-16 13:19:39.864679-04:30	2014-03-16 13:19:39.864679-04:30	solicitudes	funeraria	*	solicitudes/funeraria/*	Página para la gestión de Ordenes Medicas	1
\.


--
-- Name: recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recurso_id_seq', 6, true);


--
-- Data for Name: recurso_perfil; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY recurso_perfil (id, usuario_id, fecha_registro, fecha_modificado, recurso_id, perfil_id) FROM stdin;
1	1	2014-03-13 14:07:07.669586-04:30	2014-03-13 14:07:07.669586-04:30	1	1
12	\N	2014-03-16 01:22:15.711586-04:30	2014-03-16 01:22:15.711586-04:30	2	2
171	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	1	2
172	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	26	3
173	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	26	2
174	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	25	3
175	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	25	2
176	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	16	2
177	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	15	2
178	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	18	2
179	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	19	2
180	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	20	2
181	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	21	2
182	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	22	2
183	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	23	2
184	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	24	2
185	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	2	3
186	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	14	2
187	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	3	2
188	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	4	2
189	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	13	2
190	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	7	2
191	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	8	2
192	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	9	2
193	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	10	2
194	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	11	2
195	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	12	2
196	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	5	2
197	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	6	2
198	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	27	3
199	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	27	2
200	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	28	3
201	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	28	2
202	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	29	3
203	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	29	2
204	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	30	3
205	\N	2014-03-16 15:55:00.358245-04:30	2014-03-16 15:55:00.358245-04:30	31	3
\.


--
-- Name: recurso_perfil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('recurso_perfil_id_seq', 205, true);


--
-- Data for Name: reembolso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY reembolso (id, usuario_id, fecha_registro, fecha_modificado, estado_solicitud, fecha_solicitud, codigo_solicitud, titular_id, beneficiario_id, beneficiario_tipo, observacion) FROM stdin;
\.


--
-- Name: reembolso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('reembolso_id_seq', 1, false);


--
-- Data for Name: servicio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY servicio (id, usuario_id, fecha_registro, fecha_modificado, descripcion, observacion) FROM stdin;
\.


--
-- Name: servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('servicio_id_seq', 1, false);


--
-- Data for Name: servicio_proveedor; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY servicio_proveedor (id, proveedor_id, servicio_id) FROM stdin;
\.


--
-- Name: servicio_proveedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('servicio_proveedor_id_seq', 1, false);


--
-- Data for Name: servicio_tiposolicitud; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY servicio_tiposolicitud (id, tiposolicitud_id, servicio_id) FROM stdin;
\.


--
-- Name: servicio_tiposolicitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('servicio_tiposolicitud_id_seq', 1, false);


--
-- Data for Name: solicitud_dt_factura; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY solicitud_dt_factura (id, solicitud_factura_id, descripcion, cantidad, monto, exento) FROM stdin;
\.


--
-- Name: solicitud_dt_factura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('solicitud_dt_factura_id_seq', 1, false);


--
-- Data for Name: solicitud_dt_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY solicitud_dt_medicina (id, solicitud_id, medicina_id, fecha_inicio, fecha_fin, dosis, horas) FROM stdin;
\.


--
-- Name: solicitud_dt_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('solicitud_dt_medicina_id_seq', 1, false);


--
-- Data for Name: solicitud_factura; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY solicitud_factura (id, usuario_id, fecha_registro, fecha_modificado, solicitud_servicio_id, codigo_solicitud, fecha_factura, nro_control, nro_factura, observacion) FROM stdin;
\.


--
-- Name: solicitud_factura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('solicitud_factura_id_seq', 1, false);


--
-- Data for Name: solicitud_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY solicitud_medicina (id, usuario_id, fecha_registro, fecha_modificado, estado_solicitud, fecha_solicitud, fecha_vencimiento, codigo_solicitud, titular_id, beneficiario_id, beneficiario_tipo, patologia_id, proveedor_id, medico_id, persona_autorizada, persona_cedula, tipo_tratamiento, diagnostico, servicio_id, observacion) FROM stdin;
\.


--
-- Name: solicitud_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('solicitud_medicina_id_seq', 1, false);


--
-- Data for Name: solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY solicitud_servicio (id, usuario_id, fecha_registro, fecha_modificado, estado_solicitud, tiposolicitud_id, fecha_solicitud, codigo_solicitud, titular_id, beneficiario_id, beneficiario_tipo, patologia_id, proveedor_id, medico_id, fecha_vencimiento, servicio_id, observacion) FROM stdin;
\.


--
-- Name: solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('solicitud_servicio_id_seq', 1, false);


--
-- Data for Name: sucursal; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sucursal (id, usuario_id, fecha_registro, fecha_modificado, empresa_id, sucursal, sucursal_slug, pais_id, estado_id, municipio_id, parroquia_id, direccion, telefono, fax, celular) FROM stdin;
1	\N	2014-03-13 12:13:18.140817-04:30	2014-03-13 12:13:18.140817-04:30	1	ACCION CENTRAL	WTF	240	69	224	717	CARRETERA PRINCIPAL VIA TUREN	02563361333	\N	\N
\.


--
-- Name: sucursal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sucursal_id_seq', 1, true);


--
-- Data for Name: tipoempleado; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY tipoempleado (id, usuario_id, fecha_registro, fecha_modificado, nombre, observacion) FROM stdin;
\.


--
-- Name: tipoempleado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('tipoempleado_id_seq', 1, false);


--
-- Data for Name: tiposolicitud; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY tiposolicitud (id, usuario_id, fecha_registro, fecha_modificado, nombre, observacion) FROM stdin;
\.


--
-- Name: tiposolicitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('tiposolicitud_id_seq', 1, false);


--
-- Data for Name: titular; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY titular (id, usuario_id, fecha_registro, fecha_modificado, tipoempleado_id, persona_id, fecha_ingreso, profesion_id, departamento_id, cargo_id, observacion) FROM stdin;
\.


--
-- Name: titular_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('titular_id_seq', 1, false);


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY usuario (id, usuario_id, fecha_registro, fecha_modificado, sucursal_id, persona_id, login, password, perfil_id, email, tema, app_ajax, datagrid) FROM stdin;
1	1	2014-03-13 12:20:43.690531-04:30	2014-03-13 12:20:43.690531-04:30	1	1	admin	d93a5def7511da3d0f2d171d9c344e91	1	\N	default	1	30
4	\N	2014-03-16 01:14:45.552613-04:30	2014-03-16 01:14:45.552613-04:30	\N	4	jelitox	d93a5def7511da3d0f2d171d9c344e91	3	jel1284@gmail.com	default	1	30
\.


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('usuario_id_seq', 4, true);


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
-- Name: beneficiario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY beneficiario
    ADD CONSTRAINT beneficiario_pkey PRIMARY KEY (id);


--
-- Name: beneficiario_tipo_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY beneficiario_tipo
    ADD CONSTRAINT beneficiario_tipo_descripcion_key UNIQUE (descripcion);


--
-- Name: beneficiario_tipo_descripcion_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY beneficiario_tipo
    ADD CONSTRAINT beneficiario_tipo_descripcion_unico UNIQUE (descripcion);


--
-- Name: beneficiario_tipo_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY beneficiario_tipo
    ADD CONSTRAINT beneficiario_tipo_pkey PRIMARY KEY (id);


--
-- Name: cargo_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY cargo
    ADD CONSTRAINT cargo_nombre_key UNIQUE (nombre);


--
-- Name: cargo_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY cargo
    ADD CONSTRAINT cargo_pkey PRIMARY KEY (id);


--
-- Name: cobertura_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY cobertura
    ADD CONSTRAINT cobertura_pkey PRIMARY KEY (id);


--
-- Name: departamento_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_nombre_key UNIQUE (nombre);


--
-- Name: departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_pkey PRIMARY KEY (id);


--
-- Name: discapacidad_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY discapacidad
    ADD CONSTRAINT discapacidad_nombre_key UNIQUE (nombre);


--
-- Name: discapacidad_persona_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY discapacidad_persona
    ADD CONSTRAINT discapacidad_persona_pkey PRIMARY KEY (id);


--
-- Name: discapacidad_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY discapacidad
    ADD CONSTRAINT discapacidad_pkey PRIMARY KEY (id);


--
-- Name: empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_pkey PRIMARY KEY (id);


--
-- Name: especialidad_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY especialidad
    ADD CONSTRAINT especialidad_descripcion_key UNIQUE (descripcion);


--
-- Name: especialidad_medico_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY especialidad_medico
    ADD CONSTRAINT especialidad_medico_pkey PRIMARY KEY (id);


--
-- Name: especialidad_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY especialidad
    ADD CONSTRAINT especialidad_pkey PRIMARY KEY (id);


--
-- Name: estado_codigo_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY estado
    ADD CONSTRAINT estado_codigo_unico UNIQUE (codigo);


--
-- Name: estado_nombre_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY estado
    ADD CONSTRAINT estado_nombre_unico UNIQUE (nombre);


--
-- Name: estado_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY estado
    ADD CONSTRAINT estado_pkey PRIMARY KEY (id);


--
-- Name: estado_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY estado_usuario
    ADD CONSTRAINT estado_usuario_pkey PRIMARY KEY (id);


--
-- Name: medicina_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY medicina
    ADD CONSTRAINT medicina_descripcion_key UNIQUE (descripcion);


--
-- Name: medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY medicina
    ADD CONSTRAINT medicina_pkey PRIMARY KEY (id);


--
-- Name: medico_cedula_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY medico
    ADD CONSTRAINT medico_cedula_key UNIQUE (cedula);


--
-- Name: medico_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY medico
    ADD CONSTRAINT medico_pkey PRIMARY KEY (id);


--
-- Name: medico_rif_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY medico
    ADD CONSTRAINT medico_rif_key UNIQUE (rif);


--
-- Name: medico_rmpps_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY medico
    ADD CONSTRAINT medico_rmpps_key UNIQUE (rmpps);


--
-- Name: menu_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT menu_pkey PRIMARY KEY (id);


--
-- Name: municipio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT municipio_pkey PRIMARY KEY (id);


--
-- Name: pais_codigo_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY pais
    ADD CONSTRAINT pais_codigo_unico UNIQUE (codigo);


--
-- Name: pais_nombre_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY pais
    ADD CONSTRAINT pais_nombre_unico UNIQUE (nombre);


--
-- Name: pais_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY pais
    ADD CONSTRAINT pais_pkey PRIMARY KEY (id);


--
-- Name: parroquia_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY parroquia
    ADD CONSTRAINT parroquia_pkey PRIMARY KEY (id);


--
-- Name: patologia_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY patologia
    ADD CONSTRAINT patologia_descripcion_key UNIQUE (descripcion);


--
-- Name: patologia_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY patologia
    ADD CONSTRAINT patologia_pkey PRIMARY KEY (id);


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
-- Name: profesion_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY profesion
    ADD CONSTRAINT profesion_nombre_key UNIQUE (nombre);


--
-- Name: profesion_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY profesion
    ADD CONSTRAINT profesion_pkey PRIMARY KEY (id);


--
-- Name: proveedor_medico_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY proveedor_medico
    ADD CONSTRAINT proveedor_medico_pkey PRIMARY KEY (id);


--
-- Name: proveedor_nombre_corto_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_nombre_corto_key UNIQUE (nombre_corto);


--
-- Name: proveedor_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_pkey PRIMARY KEY (id);


--
-- Name: proveedor_razon_social_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_razon_social_key UNIQUE (razon_social);


--
-- Name: proveedor_rif_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_rif_key UNIQUE (rif);


--
-- Name: recaudo_beneficiario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_beneficiario
    ADD CONSTRAINT recaudo_beneficiario_pkey PRIMARY KEY (id);


--
-- Name: recaudo_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo
    ADD CONSTRAINT recaudo_nombre_key UNIQUE (nombre);


--
-- Name: recaudo_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo
    ADD CONSTRAINT recaudo_pkey PRIMARY KEY (id);


--
-- Name: recaudo_reembolso_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_reembolso
    ADD CONSTRAINT recaudo_reembolso_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: recaudo_reembolso_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_reembolso
    ADD CONSTRAINT recaudo_reembolso_pkey PRIMARY KEY (id);


--
-- Name: recaudo_solicitud_medicina_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_solicitud_medicina
    ADD CONSTRAINT recaudo_solicitud_medicina_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: recaudo_solicitud_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_solicitud_medicina
    ADD CONSTRAINT recaudo_solicitud_medicina_pkey PRIMARY KEY (id);


--
-- Name: recaudo_solicitud_servicio_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_solicitud_servicio
    ADD CONSTRAINT recaudo_solicitud_servicio_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: recaudo_solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_solicitud_servicio
    ADD CONSTRAINT recaudo_solicitud_servicio_pkey PRIMARY KEY (id);


--
-- Name: recaudo_titular_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY recaudo_titular
    ADD CONSTRAINT recaudo_titular_pkey PRIMARY KEY (id);


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
-- Name: reembolso_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY reembolso
    ADD CONSTRAINT reembolso_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: reembolso_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY reembolso
    ADD CONSTRAINT reembolso_pkey PRIMARY KEY (id);


--
-- Name: servicio_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY servicio
    ADD CONSTRAINT servicio_descripcion_key UNIQUE (descripcion);


--
-- Name: servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY servicio
    ADD CONSTRAINT servicio_pkey PRIMARY KEY (id);


--
-- Name: servicio_proveedor_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY servicio_proveedor
    ADD CONSTRAINT servicio_proveedor_pkey PRIMARY KEY (id);


--
-- Name: servicio_tiposolicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY servicio_tiposolicitud
    ADD CONSTRAINT servicio_tiposolicitud_pkey PRIMARY KEY (id);


--
-- Name: solicitud_dt_factura_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_dt_factura
    ADD CONSTRAINT solicitud_dt_factura_pkey PRIMARY KEY (id);


--
-- Name: solicitud_dt_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_dt_medicina
    ADD CONSTRAINT solicitud_dt_medicina_pkey PRIMARY KEY (id);


--
-- Name: solicitud_factura_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_factura
    ADD CONSTRAINT solicitud_factura_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: solicitud_factura_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_factura
    ADD CONSTRAINT solicitud_factura_pkey PRIMARY KEY (id);


--
-- Name: solicitud_medicina_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: solicitud_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_pkey PRIMARY KEY (id);


--
-- Name: solicitud_servicio_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_pkey PRIMARY KEY (id);


--
-- Name: sucursal_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_pkey PRIMARY KEY (id);


--
-- Name: tipoempleado_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY tipoempleado
    ADD CONSTRAINT tipoempleado_nombre_key UNIQUE (nombre);


--
-- Name: tipoempleado_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY tipoempleado
    ADD CONSTRAINT tipoempleado_pkey PRIMARY KEY (id);


--
-- Name: tiposolicitud_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY tiposolicitud
    ADD CONSTRAINT tiposolicitud_nombre_key UNIQUE (nombre);


--
-- Name: tiposolicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY tiposolicitud
    ADD CONSTRAINT tiposolicitud_pkey PRIMARY KEY (id);


--
-- Name: titular_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY titular
    ADD CONSTRAINT titular_pkey PRIMARY KEY (id);


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
-- Name: beneficiario_beneficiario_tipo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY beneficiario
    ADD CONSTRAINT beneficiario_beneficiario_tipo_id_fkey FOREIGN KEY (beneficiario_tipo_id) REFERENCES beneficiario_tipo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: beneficiario_persona_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY beneficiario
    ADD CONSTRAINT beneficiario_persona_id_fkey FOREIGN KEY (persona_id) REFERENCES persona(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: beneficiario_tipo_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY beneficiario_tipo
    ADD CONSTRAINT beneficiario_tipo_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: beneficiario_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY beneficiario
    ADD CONSTRAINT beneficiario_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: beneficiario_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY beneficiario
    ADD CONSTRAINT beneficiario_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: cargo_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY cargo
    ADD CONSTRAINT cargo_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: cobertura_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY cobertura
    ADD CONSTRAINT cobertura_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: departamento_sucursal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_sucursal_fkey FOREIGN KEY (sucursal_id) REFERENCES sucursal(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: departamento_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: discapacidad_persona_discapacidad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY discapacidad_persona
    ADD CONSTRAINT discapacidad_persona_discapacidad_id_fkey FOREIGN KEY (discapacidad_id) REFERENCES discapacidad(id) ON DELETE SET NULL;


--
-- Name: discapacidad_persona_persona_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY discapacidad_persona
    ADD CONSTRAINT discapacidad_persona_persona_id_fkey FOREIGN KEY (persona_id) REFERENCES persona(id) ON DELETE SET NULL;


--
-- Name: discapacidad_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY discapacidad
    ADD CONSTRAINT discapacidad_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: empresa_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: empresa_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: empresa_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: empresa_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY empresa
    ADD CONSTRAINT empresa_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: especialidad_medico_especialidad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY especialidad_medico
    ADD CONSTRAINT especialidad_medico_especialidad_id_fkey FOREIGN KEY (especialidad_id) REFERENCES especialidad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: especialidad_medico_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY especialidad_medico
    ADD CONSTRAINT especialidad_medico_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: especialidad_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY especialidad
    ADD CONSTRAINT especialidad_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: estado_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY estado
    ADD CONSTRAINT estado_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES pais(id) ON DELETE SET NULL;


--
-- Name: estado_usuario_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY estado_usuario
    ADD CONSTRAINT estado_usuario_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: medicina_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY medicina
    ADD CONSTRAINT medicina_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: medico_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY medico
    ADD CONSTRAINT medico_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


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
-- Name: municipio_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT municipio_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES estado(id) ON DELETE SET NULL;


--
-- Name: parroquia_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY parroquia
    ADD CONSTRAINT parroquia_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES municipio(id) ON DELETE SET NULL;


--
-- Name: patologia_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY patologia
    ADD CONSTRAINT patologia_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: persona_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: persona_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: persona_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: persona_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: persona_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: profesion_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY profesion
    ADD CONSTRAINT profesion_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_medico_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor_medico
    ADD CONSTRAINT proveedor_medico_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_medico_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor_medico
    ADD CONSTRAINT proveedor_medico_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: proveedor_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_beneficiario_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_beneficiario
    ADD CONSTRAINT recaudo_beneficiario_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_beneficiario_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_beneficiario
    ADD CONSTRAINT recaudo_beneficiario_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_reembolso_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_reembolso
    ADD CONSTRAINT recaudo_reembolso_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES reembolso(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_reembolso_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_reembolso
    ADD CONSTRAINT recaudo_reembolso_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_solicitud_medicina_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_solicitud_medicina
    ADD CONSTRAINT recaudo_solicitud_medicina_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES solicitud_medicina(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_solicitud_medicina_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_solicitud_medicina
    ADD CONSTRAINT recaudo_solicitud_medicina_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_solicitud_servicio_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_solicitud_servicio
    ADD CONSTRAINT recaudo_solicitud_servicio_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES solicitud_servicio(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_solicitud_servicio_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_solicitud_servicio
    ADD CONSTRAINT recaudo_solicitud_servicio_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_titular_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_titular
    ADD CONSTRAINT recaudo_titular_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_titular_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo_titular
    ADD CONSTRAINT recaudo_titular_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: recaudo_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY recaudo
    ADD CONSTRAINT recaudo_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


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
-- Name: reembolso_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY reembolso
    ADD CONSTRAINT reembolso_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: reembolso_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY reembolso
    ADD CONSTRAINT reembolso_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: reembolso_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY reembolso
    ADD CONSTRAINT reembolso_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: servicio_proveedor_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY servicio_proveedor
    ADD CONSTRAINT servicio_proveedor_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: servicio_proveedor_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY servicio_proveedor
    ADD CONSTRAINT servicio_proveedor_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: servicio_tiposolicitud_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY servicio_tiposolicitud
    ADD CONSTRAINT servicio_tiposolicitud_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: servicio_tiposolicitud_tiposolicitud_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY servicio_tiposolicitud
    ADD CONSTRAINT servicio_tiposolicitud_tiposolicitud_id_fkey FOREIGN KEY (tiposolicitud_id) REFERENCES tiposolicitud(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: servicio_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY servicio
    ADD CONSTRAINT servicio_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_dt_factura_solicitud_factura_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_dt_factura
    ADD CONSTRAINT solicitud_dt_factura_solicitud_factura_id_fkey FOREIGN KEY (solicitud_factura_id) REFERENCES solicitud_factura(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_dt_medicina_medicina_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_dt_medicina
    ADD CONSTRAINT solicitud_dt_medicina_medicina_id_fkey FOREIGN KEY (medicina_id) REFERENCES medicina(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_dt_medicina_solicitud_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_dt_medicina
    ADD CONSTRAINT solicitud_dt_medicina_solicitud_id_fkey FOREIGN KEY (solicitud_id) REFERENCES solicitud_medicina(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_factura_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_factura
    ADD CONSTRAINT solicitud_factura_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES solicitud_servicio(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_factura_solicitud_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_factura
    ADD CONSTRAINT solicitud_factura_solicitud_servicio_id_fkey FOREIGN KEY (solicitud_servicio_id) REFERENCES solicitud_servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_medicina_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_medicina_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_medicina_patologia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_patologia_id_fkey FOREIGN KEY (patologia_id) REFERENCES patologia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_medicina_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_medicina_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_medicina_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_medicina
    ADD CONSTRAINT solicitud_medicina_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_patologia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_patologia_id_fkey FOREIGN KEY (patologia_id) REFERENCES patologia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_tiposolicitud_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_tiposolicitud_id_fkey FOREIGN KEY (tiposolicitud_id) REFERENCES tiposolicitud(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: solicitud_servicio_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY solicitud_servicio
    ADD CONSTRAINT solicitud_servicio_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_empresa_fkey FOREIGN KEY (empresa_id) REFERENCES empresa(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sucursal_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sucursal
    ADD CONSTRAINT sucursal_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: titular_cargo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY titular
    ADD CONSTRAINT titular_cargo_id_fkey FOREIGN KEY (cargo_id) REFERENCES cargo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: titular_departamento_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY titular
    ADD CONSTRAINT titular_departamento_id_fkey FOREIGN KEY (departamento_id) REFERENCES departamento(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: titular_profesion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY titular
    ADD CONSTRAINT titular_profesion_id_fkey FOREIGN KEY (profesion_id) REFERENCES profesion(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: titular_tipoempleado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY titular
    ADD CONSTRAINT titular_tipoempleado_id_fkey FOREIGN KEY (tipoempleado_id) REFERENCES tipoempleado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


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

