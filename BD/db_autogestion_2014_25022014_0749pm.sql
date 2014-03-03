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
-- Name: sas_beneficiario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_beneficiario (
    id integer NOT NULL,
    titular_id integer NOT NULL,
    parentesco character varying(1) DEFAULT 'M'::character varying NOT NULL,
    nacionalidad character varying(1) DEFAULT 'V'::character varying NOT NULL,
    cedula character varying(8) NOT NULL,
    nombre1 character varying(30) NOT NULL,
    nombre2 character varying(30) NOT NULL,
    apellido1 character varying(30) NOT NULL,
    apellido2 character varying(30) NOT NULL,
    sexo character varying(1) DEFAULT 'M'::character varying NOT NULL,
    fecha_nacimiento date DEFAULT '1900-01-01'::date,
    pais_id integer NOT NULL,
    pais_estado_id integer NOT NULL,
    ciudad_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    direccion_habitacion character varying(250) NOT NULL,
    estado_civil character varying(1) DEFAULT 'S'::character varying NOT NULL,
    celular character varying(12),
    telefono character varying(12),
    correo_electronico character varying(64),
    grupo_sanguineo character varying(4),
    beneficiario_tipo_id integer NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_beneficiario OWNER TO jelitox;

--
-- Name: TABLE sas_beneficiario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_beneficiario IS 'Modelo para manipular los beneficiarios';


--
-- Name: COLUMN sas_beneficiario.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.titular_id IS 'Empleado Titular';


--
-- Name: COLUMN sas_beneficiario.parentesco; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.parentesco IS 'Parentesco del beneficiario';


--
-- Name: COLUMN sas_beneficiario.nacionalidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.nacionalidad IS 'Nacionalidad del beneficiario';


--
-- Name: COLUMN sas_beneficiario.cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.cedula IS 'N° Cedula beneficiario';


--
-- Name: COLUMN sas_beneficiario.nombre1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.nombre1 IS 'N° Primer Nombre del beneficiario';


--
-- Name: COLUMN sas_beneficiario.nombre2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.nombre2 IS 'N° Segundo Nombre del beneficiario';


--
-- Name: COLUMN sas_beneficiario.apellido1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.apellido1 IS 'N° Primer Apellido del beneficiario';


--
-- Name: COLUMN sas_beneficiario.apellido2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.apellido2 IS 'N° Segundo Apellido del beneficiario';


--
-- Name: COLUMN sas_beneficiario.sexo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.sexo IS 'N° Sexo del beneficiario';


--
-- Name: COLUMN sas_beneficiario.fecha_nacimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.fecha_nacimiento IS 'Fecha de Nacimiento del beneficiario';


--
-- Name: COLUMN sas_beneficiario.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.pais_id IS 'Pais Origen del beneficiario';


--
-- Name: COLUMN sas_beneficiario.pais_estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.pais_estado_id IS 'Estado de Origen del beneficiario';


--
-- Name: COLUMN sas_beneficiario.ciudad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.ciudad_id IS 'Ciudad de Origen del beneficiario';


--
-- Name: COLUMN sas_beneficiario.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.municipio_id IS 'Municipio de Origen del beneficiario';


--
-- Name: COLUMN sas_beneficiario.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.parroquia_id IS 'Parroquia de Origen del beneficiario';


--
-- Name: COLUMN sas_beneficiario.direccion_habitacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.direccion_habitacion IS 'Direccion de Habitacion del beneficiario';


--
-- Name: COLUMN sas_beneficiario.estado_civil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.estado_civil IS 'Estado Civil del beneficiario';


--
-- Name: COLUMN sas_beneficiario.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.celular IS 'N° de Celular del beneficiario';


--
-- Name: COLUMN sas_beneficiario.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.telefono IS 'N° de Telefono del beneficiario';


--
-- Name: COLUMN sas_beneficiario.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.correo_electronico IS 'Direccion de Correo Electronico del beneficiario';


--
-- Name: COLUMN sas_beneficiario.grupo_sanguineo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.grupo_sanguineo IS 'Grupo Sanguineo del beneficiario';


--
-- Name: COLUMN sas_beneficiario.beneficiario_tipo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.beneficiario_tipo_id IS 'Tipo de Beneficiario';


--
-- Name: COLUMN sas_beneficiario.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario.observacion IS 'Observacion';


--
-- Name: sas_beneficiario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_beneficiario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_beneficiario_id_seq OWNER TO jelitox;

--
-- Name: sas_beneficiario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_beneficiario_id_seq OWNED BY sas_beneficiario.id;


--
-- Name: sas_beneficiario_tipo; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_beneficiario_tipo (
    id integer NOT NULL,
    descripcion character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_beneficiario_tipo OWNER TO jelitox;

--
-- Name: TABLE sas_beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_beneficiario_tipo IS 'Modelo para manipular los Tipos de Beneficiarios';


--
-- Name: COLUMN sas_beneficiario_tipo.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario_tipo.descripcion IS 'Descripcion del Tipo de Beneficiario';


--
-- Name: COLUMN sas_beneficiario_tipo.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_beneficiario_tipo.observacion IS 'Observacion';


--
-- Name: sas_beneficiario_tipo_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_beneficiario_tipo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_beneficiario_tipo_id_seq OWNER TO jelitox;

--
-- Name: sas_beneficiario_tipo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_beneficiario_tipo_id_seq OWNED BY sas_beneficiario_tipo.id;


--
-- Name: sas_cargo; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_cargo (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_cargo OWNER TO jelitox;

--
-- Name: TABLE sas_cargo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_cargo IS 'Modelo para manipular las diferentes Profesiones';


--
-- Name: COLUMN sas_cargo.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cargo.nombre IS 'Nombre de la Profesion';


--
-- Name: COLUMN sas_cargo.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cargo.observacion IS 'Observacion';


--
-- Name: sas_cargo_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_cargo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_cargo_id_seq OWNER TO jelitox;

--
-- Name: sas_cargo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_cargo_id_seq OWNED BY sas_cargo.id;


--
-- Name: sas_ciudad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_ciudad (
    id integer NOT NULL,
    estado_id integer NOT NULL,
    codigo character varying(3) NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.sas_ciudad OWNER TO jelitox;

--
-- Name: TABLE sas_ciudad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_ciudad IS 'Modelo para manipular las Ciudades';


--
-- Name: COLUMN sas_ciudad.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.estado_id IS 'Estado';


--
-- Name: COLUMN sas_ciudad.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.codigo IS 'Codigo de Ciudad';


--
-- Name: COLUMN sas_ciudad.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.nombre IS 'Nombre de Ciudad';


--
-- Name: sas_ciudad_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_ciudad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_ciudad_id_seq OWNER TO jelitox;

--
-- Name: sas_ciudad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_ciudad_id_seq OWNED BY sas_ciudad.id;


--
-- Name: sas_cobertura; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_cobertura (
    id integer NOT NULL,
    descripcion character varying(30) NOT NULL,
    tipo_cobertura character varying(1) NOT NULL,
    monto_cobertura numeric(11,2) DEFAULT 0.0 NOT NULL,
    fecha_inicio date DEFAULT '1900-01-01'::date,
    fecha_fin date DEFAULT '1900-01-01'::date,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_cobertura OWNER TO jelitox;

--
-- Name: TABLE sas_cobertura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_cobertura IS 'Modelo para manipular las Coberturas';


--
-- Name: COLUMN sas_cobertura.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cobertura.descripcion IS 'Descripcion de la cobertura';


--
-- Name: COLUMN sas_cobertura.tipo_cobertura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cobertura.tipo_cobertura IS 'Tipo de Cobertura (G-Grupal,I-Individual)';


--
-- Name: COLUMN sas_cobertura.monto_cobertura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cobertura.monto_cobertura IS 'Monto de la Cobertura';


--
-- Name: COLUMN sas_cobertura.fecha_inicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cobertura.fecha_inicio IS 'Fecha de Inicio de la cobertura';


--
-- Name: COLUMN sas_cobertura.fecha_fin; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cobertura.fecha_fin IS 'Fecha de Fin de la cobertura';


--
-- Name: COLUMN sas_cobertura.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_cobertura.observacion IS 'Observacion de la Cobertura';


--
-- Name: sas_cobertura_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_cobertura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_cobertura_id_seq OWNER TO jelitox;

--
-- Name: sas_cobertura_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_cobertura_id_seq OWNED BY sas_cobertura.id;


--
-- Name: sas_departamento; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_departamento (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_departamento OWNER TO jelitox;

--
-- Name: TABLE sas_departamento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_departamento IS 'Modelo para manipular los diferentes Departamentos de las UPSAS';


--
-- Name: COLUMN sas_departamento.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_departamento.nombre IS 'Nombre del Departamento';


--
-- Name: COLUMN sas_departamento.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_departamento.observacion IS 'Observacion';


--
-- Name: sas_departamento_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_departamento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_departamento_id_seq OWNER TO jelitox;

--
-- Name: sas_departamento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_departamento_id_seq OWNED BY sas_departamento.id;


--
-- Name: sas_discapacidad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_discapacidad (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_discapacidad OWNER TO jelitox;

--
-- Name: TABLE sas_discapacidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_discapacidad IS 'Modelo para manipular los diferentes Tipos de Discapacidades';


--
-- Name: COLUMN sas_discapacidad.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_discapacidad.nombre IS 'Nombre de la Discapacidad';


--
-- Name: COLUMN sas_discapacidad.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_discapacidad.observacion IS 'Observacion';


--
-- Name: sas_discapacidad_beneficiario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_discapacidad_beneficiario (
    id integer NOT NULL,
    beneficiario_id integer NOT NULL,
    discapacidad_id integer NOT NULL
);


ALTER TABLE public.sas_discapacidad_beneficiario OWNER TO jelitox;

--
-- Name: TABLE sas_discapacidad_beneficiario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_discapacidad_beneficiario IS 'Modelo para manipular la relacion Discapacidad-Beneficiarios';


--
-- Name: COLUMN sas_discapacidad_beneficiario.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_discapacidad_beneficiario.beneficiario_id IS 'ID del Beneficiario';


--
-- Name: COLUMN sas_discapacidad_beneficiario.discapacidad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_discapacidad_beneficiario.discapacidad_id IS 'ID de la Discapacidad';


--
-- Name: sas_discapacidad_beneficiario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_discapacidad_beneficiario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_discapacidad_beneficiario_id_seq OWNER TO jelitox;

--
-- Name: sas_discapacidad_beneficiario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_discapacidad_beneficiario_id_seq OWNED BY sas_discapacidad_beneficiario.id;


--
-- Name: sas_discapacidad_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_discapacidad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_discapacidad_id_seq OWNER TO jelitox;

--
-- Name: sas_discapacidad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_discapacidad_id_seq OWNED BY sas_discapacidad.id;


--
-- Name: sas_discapacidad_titular; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_discapacidad_titular (
    id integer NOT NULL,
    titular_id integer NOT NULL,
    discapacidad_id integer NOT NULL
);


ALTER TABLE public.sas_discapacidad_titular OWNER TO jelitox;

--
-- Name: TABLE sas_discapacidad_titular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_discapacidad_titular IS 'Modelo para manipular la relacion Discapacidad-Beneficiarios';


--
-- Name: COLUMN sas_discapacidad_titular.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_discapacidad_titular.titular_id IS 'ID del Titular';


--
-- Name: COLUMN sas_discapacidad_titular.discapacidad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_discapacidad_titular.discapacidad_id IS 'ID de la Discapacidad';


--
-- Name: sas_discapacidad_titular_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_discapacidad_titular_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_discapacidad_titular_id_seq OWNER TO jelitox;

--
-- Name: sas_discapacidad_titular_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_discapacidad_titular_id_seq OWNED BY sas_discapacidad_titular.id;


--
-- Name: sas_especialidad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_especialidad (
    id integer NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_especialidad OWNER TO jelitox;

--
-- Name: TABLE sas_especialidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_especialidad IS 'Modelo para manipular las Especialidades';


--
-- Name: COLUMN sas_especialidad.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_especialidad.descripcion IS 'Descripcion de la Especialidad';


--
-- Name: COLUMN sas_especialidad.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_especialidad.observacion IS 'Observacion de la Especialidad';


--
-- Name: sas_especialidad_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_especialidad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_especialidad_id_seq OWNER TO jelitox;

--
-- Name: sas_especialidad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_especialidad_id_seq OWNED BY sas_especialidad.id;


--
-- Name: sas_proveedor; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_proveedor (
    id integer NOT NULL,
    rif character varying(10) NOT NULL,
    razon_social character varying(30) NOT NULL,
    nombre_corto character varying(30) NOT NULL,
    pais_id integer NOT NULL,
    pais_estado_id integer NOT NULL,
    ciudad_id integer NOT NULL,
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


ALTER TABLE public.sas_proveedor OWNER TO jelitox;

--
-- Name: TABLE sas_proveedor; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_proveedor IS 'Modelo para manipular los Proveedores';


--
-- Name: COLUMN sas_proveedor.rif; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.rif IS 'Rif del Proveedor';


--
-- Name: COLUMN sas_proveedor.razon_social; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.razon_social IS 'Razon Social del Proveedor';


--
-- Name: COLUMN sas_proveedor.nombre_corto; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.nombre_corto IS 'Nombre Corto Proveedor';


--
-- Name: COLUMN sas_proveedor.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.pais_id IS 'Pais Origen del Proveedor';


--
-- Name: COLUMN sas_proveedor.pais_estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.pais_estado_id IS 'Estado de Origen del Proveedor';


--
-- Name: COLUMN sas_proveedor.ciudad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.ciudad_id IS 'Ciudad de Origen del Proveedor';


--
-- Name: COLUMN sas_proveedor.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.municipio_id IS 'Municipio de Origen del Proveedor';


--
-- Name: COLUMN sas_proveedor.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.parroquia_id IS 'Parroquia de Origen del Proveedor';


--
-- Name: COLUMN sas_proveedor.direccion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.direccion IS 'Direccion del Proveedor';


--
-- Name: COLUMN sas_proveedor.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.celular IS 'N° de Celular del Proveedor';


--
-- Name: COLUMN sas_proveedor.telefono1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.telefono1 IS 'N° de Telefono del Proveedor';


--
-- Name: COLUMN sas_proveedor.telefono2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.telefono2 IS 'N° de Telefono del Proveedor';


--
-- Name: COLUMN sas_proveedor.fax; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.fax IS 'N° de Fax del Proveedor';


--
-- Name: COLUMN sas_proveedor.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.correo_electronico IS 'Direccion de Correo Electronico del Proveedor';


--
-- Name: COLUMN sas_proveedor.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor.observacion IS 'Observacion';


--
-- Name: sas_proveedor_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_proveedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_proveedor_id_seq OWNER TO jelitox;

--
-- Name: sas_proveedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_proveedor_id_seq OWNED BY sas_proveedor.id;


--
-- Name: sas_especialidad_medico; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_especialidad_medico (
    id integer DEFAULT nextval('sas_proveedor_id_seq'::regclass) NOT NULL,
    medico_id integer NOT NULL,
    especialidad_id integer NOT NULL
);


ALTER TABLE public.sas_especialidad_medico OWNER TO jelitox;

--
-- Name: TABLE sas_especialidad_medico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_especialidad_medico IS 'Modelo para manipular la relacion especialidad-proveedors';


--
-- Name: COLUMN sas_especialidad_medico.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_especialidad_medico.medico_id IS 'ID del medico';


--
-- Name: COLUMN sas_especialidad_medico.especialidad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_especialidad_medico.especialidad_id IS 'ID de la especialidad';


--
-- Name: sas_especialidad_medico_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_especialidad_medico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_especialidad_medico_id_seq OWNER TO jelitox;

--
-- Name: sas_especialidad_medico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_especialidad_medico_id_seq OWNED BY sas_especialidad_medico.id;


--
-- Name: sas_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_medicina (
    id integer NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_medicina OWNER TO jelitox;

--
-- Name: TABLE sas_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_medicina IS 'Modelo para manipular las Medicina';


--
-- Name: COLUMN sas_medicina.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medicina.descripcion IS 'Descripcion de la Medicina';


--
-- Name: COLUMN sas_medicina.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medicina.observacion IS 'Observacion de la Medicina';


--
-- Name: sas_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_medicina_id_seq OWNER TO jelitox;

--
-- Name: sas_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_medicina_id_seq OWNED BY sas_medicina.id;


--
-- Name: sas_medico; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_medico (
    id integer NOT NULL,
    nacionalidad character varying(1) DEFAULT 'V'::character varying NOT NULL,
    cedula character varying(8) NOT NULL,
    rmpps character varying(8) NOT NULL,
    rif character varying(10) NOT NULL,
    nombre1 character varying(30) NOT NULL,
    nombre2 character varying(30),
    apellido1 character varying(30) NOT NULL,
    apellido2 character varying(30),
    sexo character varying(1) DEFAULT 'M'::character varying NOT NULL,
    especialidad_id integer NOT NULL,
    celular character varying(12),
    telefono character varying(12),
    correo_electronico character varying(30),
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_medico OWNER TO jelitox;

--
-- Name: TABLE sas_medico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_medico IS 'Modelo para manipular los Medicos';


--
-- Name: COLUMN sas_medico.nacionalidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.nacionalidad IS 'Nacionalidad del Medico';


--
-- Name: COLUMN sas_medico.cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.cedula IS 'Numero de Cedula del Medico';


--
-- Name: COLUMN sas_medico.rmpps; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.rmpps IS 'Numero de Registro del MPPS del Medico';


--
-- Name: COLUMN sas_medico.rif; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.rif IS 'Numero de Rif del Medico';


--
-- Name: COLUMN sas_medico.nombre1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.nombre1 IS 'Primer Nombre del Medico';


--
-- Name: COLUMN sas_medico.nombre2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.nombre2 IS 'Segundo Nombre del Medico';


--
-- Name: COLUMN sas_medico.apellido1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.apellido1 IS 'Primer apellido del Medico';


--
-- Name: COLUMN sas_medico.apellido2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.apellido2 IS 'Segundo apellido del Medico';


--
-- Name: COLUMN sas_medico.sexo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.sexo IS 'Sexo del Medico';


--
-- Name: COLUMN sas_medico.especialidad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.especialidad_id IS 'Especialidad del Medico';


--
-- Name: COLUMN sas_medico.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.celular IS 'Numero Celular del Medico';


--
-- Name: COLUMN sas_medico.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.telefono IS 'Numero Telefono del Medico';


--
-- Name: COLUMN sas_medico.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.correo_electronico IS 'Correo Electronico del medico';


--
-- Name: COLUMN sas_medico.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_medico.observacion IS 'Observacion del Medico';


--
-- Name: sas_medico_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_medico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_medico_id_seq OWNER TO jelitox;

--
-- Name: sas_medico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_medico_id_seq OWNED BY sas_medico.id;


--
-- Name: sas_municipio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_municipio (
    id integer NOT NULL,
    estado_id integer NOT NULL,
    codigo character varying(3) NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.sas_municipio OWNER TO jelitox;

--
-- Name: TABLE sas_municipio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_municipio IS 'Modelo para manipular Municipios';


--
-- Name: COLUMN sas_municipio.estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.estado_id IS 'Estado';


--
-- Name: COLUMN sas_municipio.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.codigo IS 'Codigo Municipio';


--
-- Name: COLUMN sas_municipio.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.nombre IS 'Nombre Municipio';


--
-- Name: sas_municipio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_municipio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_municipio_id_seq OWNER TO jelitox;

--
-- Name: sas_municipio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_municipio_id_seq OWNED BY sas_municipio.id;


--
-- Name: sas_pais; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_pais (
    id integer NOT NULL,
    codigo character varying(2) NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.sas_pais OWNER TO jelitox;

--
-- Name: TABLE sas_pais; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_pais IS 'Modelo para manipular los Paises';


--
-- Name: COLUMN sas_pais.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.codigo IS 'Codigo del Pais';


--
-- Name: COLUMN sas_pais.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.nombre IS 'Nombre Pais';


--
-- Name: sas_pais_estado; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_pais_estado (
    id integer NOT NULL,
    codigo character varying(3) NOT NULL,
    pais_id integer NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.sas_pais_estado OWNER TO jelitox;

--
-- Name: TABLE sas_pais_estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_pais_estado IS 'Modelo para manipular la relación Pais Estado';


--
-- Name: COLUMN sas_pais_estado.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.codigo IS 'Codigo Estado';


--
-- Name: COLUMN sas_pais_estado.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.pais_id IS 'Pais';


--
-- Name: COLUMN sas_pais_estado.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.nombre IS 'Nombre Estado';


--
-- Name: sas_pais_estado_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_pais_estado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_pais_estado_id_seq OWNER TO jelitox;

--
-- Name: sas_pais_estado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_pais_estado_id_seq OWNED BY sas_pais_estado.id;


--
-- Name: sas_pais_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_pais_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_pais_id_seq OWNER TO jelitox;

--
-- Name: sas_pais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_pais_id_seq OWNED BY sas_pais.id;


--
-- Name: sas_parroquia; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_parroquia (
    id integer NOT NULL,
    nombre character varying(128) NOT NULL,
    municipio_id integer NOT NULL
);


ALTER TABLE public.sas_parroquia OWNER TO jelitox;

--
-- Name: TABLE sas_parroquia; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_parroquia IS 'Modelo para  manipular Parroquia';


--
-- Name: COLUMN sas_parroquia.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.nombre IS 'Parroquia';


--
-- Name: COLUMN sas_parroquia.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.municipio_id IS 'Municipio';


--
-- Name: sas_parroquia_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_parroquia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_parroquia_id_seq OWNER TO jelitox;

--
-- Name: sas_parroquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_parroquia_id_seq OWNED BY sas_parroquia.id;


--
-- Name: sas_patologia; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_patologia (
    id integer NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_patologia OWNER TO jelitox;

--
-- Name: TABLE sas_patologia; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_patologia IS 'Modelo para manipular las Patologias';


--
-- Name: COLUMN sas_patologia.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_patologia.descripcion IS 'Descripcion de la Patologia';


--
-- Name: COLUMN sas_patologia.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_patologia.observacion IS 'Observacion de la Patologia';


--
-- Name: sas_patologia_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_patologia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_patologia_id_seq OWNER TO jelitox;

--
-- Name: sas_patologia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_patologia_id_seq OWNED BY sas_patologia.id;


--
-- Name: sas_profesion; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_profesion (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_profesion OWNER TO jelitox;

--
-- Name: TABLE sas_profesion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_profesion IS 'Modelo para manipular las diferentes Profesiones';


--
-- Name: COLUMN sas_profesion.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_profesion.nombre IS 'Nombre de la Profesion';


--
-- Name: COLUMN sas_profesion.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_profesion.observacion IS 'Observacion';


--
-- Name: sas_profesion_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_profesion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_profesion_id_seq OWNER TO jelitox;

--
-- Name: sas_profesion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_profesion_id_seq OWNED BY sas_profesion.id;


--
-- Name: sas_proveedor_medico; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_proveedor_medico (
    id integer DEFAULT nextval('sas_proveedor_id_seq'::regclass) NOT NULL,
    medico_id integer NOT NULL,
    proveedor_id integer NOT NULL
);


ALTER TABLE public.sas_proveedor_medico OWNER TO jelitox;

--
-- Name: TABLE sas_proveedor_medico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_proveedor_medico IS 'Modelo para manipular la relacion proveedor-medico';


--
-- Name: COLUMN sas_proveedor_medico.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor_medico.medico_id IS 'ID del medico';


--
-- Name: COLUMN sas_proveedor_medico.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_proveedor_medico.proveedor_id IS 'ID del proveedor';


--
-- Name: sas_proveedor_medico_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_proveedor_medico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_proveedor_medico_id_seq OWNER TO jelitox;

--
-- Name: sas_proveedor_medico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_proveedor_medico_id_seq OWNED BY sas_proveedor_medico.id;


--
-- Name: sas_recaudo; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_recaudo (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    tipo character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_recaudo OWNER TO jelitox;

--
-- Name: TABLE sas_recaudo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_recaudo IS 'Modelo para manipular los diferentes Recaudos';


--
-- Name: COLUMN sas_recaudo.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo.nombre IS 'Nombre del Recaudo';


--
-- Name: COLUMN sas_recaudo.tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo.tipo IS 'Tipo de Recaudo';


--
-- Name: COLUMN sas_recaudo.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo.observacion IS 'Observacion';


--
-- Name: sas_recaudo_beneficiario; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_recaudo_beneficiario (
    id integer DEFAULT nextval('sas_beneficiario_id_seq'::regclass) NOT NULL,
    beneficiario_id integer NOT NULL,
    recaudo_id integer NOT NULL,
    estado boolean
);


ALTER TABLE public.sas_recaudo_beneficiario OWNER TO jelitox;

--
-- Name: TABLE sas_recaudo_beneficiario; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_recaudo_beneficiario IS 'Modelo para manipular la relacion Recaudo-Beneficiarios';


--
-- Name: COLUMN sas_recaudo_beneficiario.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_beneficiario.beneficiario_id IS 'ID del Beneficiario';


--
-- Name: COLUMN sas_recaudo_beneficiario.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_beneficiario.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN sas_recaudo_beneficiario.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_beneficiario.estado IS 'Estado del Recaudo';


--
-- Name: sas_recaudo_beneficiario_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_recaudo_beneficiario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_recaudo_beneficiario_id_seq OWNER TO jelitox;

--
-- Name: sas_recaudo_beneficiario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_recaudo_beneficiario_id_seq OWNED BY sas_recaudo_beneficiario.id;


--
-- Name: sas_recaudo_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_recaudo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_recaudo_id_seq OWNER TO jelitox;

--
-- Name: sas_recaudo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_recaudo_id_seq OWNED BY sas_recaudo.id;


--
-- Name: sas_recaudo_reembolso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_recaudo_reembolso (
    id integer NOT NULL,
    recaudo_id integer NOT NULL,
    codigo_solicitud character varying(8) NOT NULL,
    estado boolean
);


ALTER TABLE public.sas_recaudo_reembolso OWNER TO jelitox;

--
-- Name: TABLE sas_recaudo_reembolso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_recaudo_reembolso IS 'Modelo para manipular la relacion Recaudo - Reembolsos';


--
-- Name: COLUMN sas_recaudo_reembolso.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_reembolso.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN sas_recaudo_reembolso.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_reembolso.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_recaudo_reembolso.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_reembolso.estado IS 'Estado del Recaudo';


--
-- Name: sas_recaudo_reembolso_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_recaudo_reembolso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_recaudo_reembolso_id_seq OWNER TO jelitox;

--
-- Name: sas_recaudo_reembolso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_recaudo_reembolso_id_seq OWNED BY sas_recaudo_reembolso.id;


--
-- Name: sas_recaudo_solicitud_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_recaudo_solicitud_medicina (
    id integer NOT NULL,
    recaudo_id integer NOT NULL,
    codigo_solicitud character varying(8) NOT NULL,
    estado boolean
);


ALTER TABLE public.sas_recaudo_solicitud_medicina OWNER TO jelitox;

--
-- Name: TABLE sas_recaudo_solicitud_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_recaudo_solicitud_medicina IS 'Modelo para manipular la relacion Recaudo - Solicitud Medicina';


--
-- Name: COLUMN sas_recaudo_solicitud_medicina.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_solicitud_medicina.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN sas_recaudo_solicitud_medicina.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_solicitud_medicina.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_recaudo_solicitud_medicina.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_solicitud_medicina.estado IS 'Estado del Recaudo';


--
-- Name: sas_recaudo_solicitud_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_recaudo_solicitud_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_recaudo_solicitud_medicina_id_seq OWNER TO jelitox;

--
-- Name: sas_recaudo_solicitud_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_recaudo_solicitud_medicina_id_seq OWNED BY sas_recaudo_solicitud_medicina.id;


--
-- Name: sas_recaudo_solicitud_servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_recaudo_solicitud_servicio (
    id integer NOT NULL,
    recaudo_id integer NOT NULL,
    codigo_solicitud character varying(8) NOT NULL,
    estado boolean
);


ALTER TABLE public.sas_recaudo_solicitud_servicio OWNER TO jelitox;

--
-- Name: TABLE sas_recaudo_solicitud_servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_recaudo_solicitud_servicio IS 'Modelo para manipular la relacion Recaudo - Solicitud Servicio';


--
-- Name: COLUMN sas_recaudo_solicitud_servicio.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_solicitud_servicio.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN sas_recaudo_solicitud_servicio.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_solicitud_servicio.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_recaudo_solicitud_servicio.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_solicitud_servicio.estado IS 'Estado del Recaudo';


--
-- Name: sas_recaudo_solicitud_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_recaudo_solicitud_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_recaudo_solicitud_servicio_id_seq OWNER TO jelitox;

--
-- Name: sas_recaudo_solicitud_servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_recaudo_solicitud_servicio_id_seq OWNED BY sas_recaudo_solicitud_servicio.id;


--
-- Name: sas_recaudo_titular; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_recaudo_titular (
    id integer NOT NULL,
    titular_id integer NOT NULL,
    recaudo_id integer NOT NULL,
    estado boolean
);


ALTER TABLE public.sas_recaudo_titular OWNER TO jelitox;

--
-- Name: TABLE sas_recaudo_titular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_recaudo_titular IS 'Modelo para manipular la relacion Recaudo-Titular';


--
-- Name: COLUMN sas_recaudo_titular.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_titular.titular_id IS 'ID del Titular';


--
-- Name: COLUMN sas_recaudo_titular.recaudo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_titular.recaudo_id IS 'ID del Recaudo';


--
-- Name: COLUMN sas_recaudo_titular.estado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_recaudo_titular.estado IS 'Estado del Recaudo';


--
-- Name: sas_recaudo_titular_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_recaudo_titular_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_recaudo_titular_id_seq OWNER TO jelitox;

--
-- Name: sas_recaudo_titular_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_recaudo_titular_id_seq OWNED BY sas_recaudo_titular.id;


--
-- Name: sas_reembolso; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_reembolso (
    id integer NOT NULL,
    estado_solicitud character(1) NOT NULL,
    fecha_solicitud date DEFAULT '1900-01-01'::date,
    codigo_solicitud character varying(8) NOT NULL,
    titular_id integer NOT NULL,
    beneficiario_id integer NOT NULL,
    beneficiario_tipo character(1) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_reembolso OWNER TO jelitox;

--
-- Name: TABLE sas_reembolso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_reembolso IS 'Modelo para manipular las Solicitudes de Reembolso';


--
-- Name: COLUMN sas_reembolso.estado_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.estado_solicitud IS 'Estado de la Solicitud';


--
-- Name: COLUMN sas_reembolso.fecha_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.fecha_solicitud IS 'Fecha de la Solicitud';


--
-- Name: COLUMN sas_reembolso.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_reembolso.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.titular_id IS 'Codigo del Titular';


--
-- Name: COLUMN sas_reembolso.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.beneficiario_id IS 'Codigo del Beneficiario';


--
-- Name: COLUMN sas_reembolso.beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.beneficiario_tipo IS 'beneficiario de la Solicitud';


--
-- Name: COLUMN sas_reembolso.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_reembolso.observacion IS 'Observacion';


--
-- Name: sas_reembolso_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_reembolso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_reembolso_id_seq OWNER TO jelitox;

--
-- Name: sas_reembolso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_reembolso_id_seq OWNED BY sas_reembolso.id;


--
-- Name: sas_servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_servicio (
    id integer NOT NULL,
    descripcion character varying(150) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_servicio OWNER TO jelitox;

--
-- Name: TABLE sas_servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_servicio IS 'Modelo para manipular los Servicios';


--
-- Name: COLUMN sas_servicio.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_servicio.descripcion IS 'Descripcion del Servicio';


--
-- Name: COLUMN sas_servicio.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_servicio.observacion IS 'Observacion del Servicio';


--
-- Name: sas_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_servicio_id_seq OWNER TO jelitox;

--
-- Name: sas_servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_servicio_id_seq OWNED BY sas_servicio.id;


--
-- Name: sas_servicio_proveedor; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_servicio_proveedor (
    id integer DEFAULT nextval('sas_proveedor_id_seq'::regclass) NOT NULL,
    proveedor_id integer NOT NULL,
    servicio_id integer NOT NULL
);


ALTER TABLE public.sas_servicio_proveedor OWNER TO jelitox;

--
-- Name: TABLE sas_servicio_proveedor; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_servicio_proveedor IS 'Modelo para manipular la relacion servicio-proveedors';


--
-- Name: COLUMN sas_servicio_proveedor.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_servicio_proveedor.proveedor_id IS 'ID del proveedor';


--
-- Name: COLUMN sas_servicio_proveedor.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_servicio_proveedor.servicio_id IS 'ID del servicio';


--
-- Name: sas_servicio_proveedor_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_servicio_proveedor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_servicio_proveedor_id_seq OWNER TO jelitox;

--
-- Name: sas_servicio_proveedor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_servicio_proveedor_id_seq OWNED BY sas_servicio_proveedor.id;


--
-- Name: sas_servicio_tiposolicitud; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_servicio_tiposolicitud (
    id integer DEFAULT nextval('sas_proveedor_id_seq'::regclass) NOT NULL,
    tiposolicitud_id integer NOT NULL,
    servicio_id integer NOT NULL
);


ALTER TABLE public.sas_servicio_tiposolicitud OWNER TO jelitox;

--
-- Name: TABLE sas_servicio_tiposolicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_servicio_tiposolicitud IS 'Modelo para manipular la relacion Servicio - Tiposolicitud';


--
-- Name: COLUMN sas_servicio_tiposolicitud.tiposolicitud_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_servicio_tiposolicitud.tiposolicitud_id IS 'ID del tipo de solicitud';


--
-- Name: COLUMN sas_servicio_tiposolicitud.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_servicio_tiposolicitud.servicio_id IS 'ID del Servicio';


--
-- Name: sas_servicio_tiposolicitud_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_servicio_tiposolicitud_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_servicio_tiposolicitud_id_seq OWNER TO jelitox;

--
-- Name: sas_servicio_tiposolicitud_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_servicio_tiposolicitud_id_seq OWNED BY sas_servicio_tiposolicitud.id;


--
-- Name: sas_solicitud_dt_factura; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_solicitud_dt_factura (
    id integer NOT NULL,
    solicitud_factura_id integer,
    descripcion character varying(150) NOT NULL,
    cantidad integer,
    monto numeric(11,2) NOT NULL,
    exento boolean
);


ALTER TABLE public.sas_solicitud_dt_factura OWNER TO jelitox;

--
-- Name: TABLE sas_solicitud_dt_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_solicitud_dt_factura IS 'Modelo para manipular el Detalle de la Facturacion de las Solicitudes de Servicios';


--
-- Name: COLUMN sas_solicitud_dt_factura.solicitud_factura_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_factura.solicitud_factura_id IS 'Id de la Factura';


--
-- Name: COLUMN sas_solicitud_dt_factura.descripcion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_factura.descripcion IS 'Descripcion del Item';


--
-- Name: COLUMN sas_solicitud_dt_factura.cantidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_factura.cantidad IS 'Cantidad del Item';


--
-- Name: COLUMN sas_solicitud_dt_factura.monto; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_factura.monto IS 'Monto del Item';


--
-- Name: COLUMN sas_solicitud_dt_factura.exento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_factura.exento IS 'Item Exento del Iva';


--
-- Name: sas_solicitud_dt_factura_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_solicitud_dt_factura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_solicitud_dt_factura_id_seq OWNER TO jelitox;

--
-- Name: sas_solicitud_dt_factura_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_solicitud_dt_factura_id_seq OWNED BY sas_solicitud_dt_factura.id;


--
-- Name: sas_solicitud_dt_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_solicitud_dt_medicina (
    id integer NOT NULL,
    solicitud_id integer NOT NULL,
    medicina_id integer NOT NULL,
    fecha_inicio date DEFAULT '1900-01-01'::date,
    fecha_fin date DEFAULT '1900-01-01'::date,
    dosis integer,
    horas time without time zone
);


ALTER TABLE public.sas_solicitud_dt_medicina OWNER TO jelitox;

--
-- Name: TABLE sas_solicitud_dt_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_solicitud_dt_medicina IS 'Modelo para manipular los Detalles de las Solicitudes de Medicinas';


--
-- Name: COLUMN sas_solicitud_dt_medicina.id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.id IS 'Id del Registro';


--
-- Name: COLUMN sas_solicitud_dt_medicina.solicitud_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.solicitud_id IS 'Id la Solicitud';


--
-- Name: COLUMN sas_solicitud_dt_medicina.medicina_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.medicina_id IS 'Codigo de la Medicina';


--
-- Name: COLUMN sas_solicitud_dt_medicina.fecha_inicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.fecha_inicio IS 'Fecha Inicio del Tratamiento';


--
-- Name: COLUMN sas_solicitud_dt_medicina.fecha_fin; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.fecha_fin IS 'Fecha Fin del Tratamiento';


--
-- Name: COLUMN sas_solicitud_dt_medicina.dosis; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.dosis IS 'Dosis de la Medicina';


--
-- Name: COLUMN sas_solicitud_dt_medicina.horas; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_dt_medicina.horas IS 'Dosis de la Medicina';


--
-- Name: sas_solicitud_dt_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_solicitud_dt_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_solicitud_dt_medicina_id_seq OWNER TO jelitox;

--
-- Name: sas_solicitud_dt_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_solicitud_dt_medicina_id_seq OWNED BY sas_solicitud_dt_medicina.id;


--
-- Name: sas_solicitud_factura; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_solicitud_factura (
    id integer NOT NULL,
    solicitud_servicio_id integer,
    codigo_solicitud character varying(8) NOT NULL,
    fecha_factura date DEFAULT '1900-01-01'::date,
    nro_control integer,
    nro_factura integer,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_solicitud_factura OWNER TO jelitox;

--
-- Name: TABLE sas_solicitud_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_solicitud_factura IS 'Modelo para manipular la Facturacion de las Solicitudes de Servicios';


--
-- Name: COLUMN sas_solicitud_factura.solicitud_servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_factura.solicitud_servicio_id IS 'Id de la Solicitud';


--
-- Name: COLUMN sas_solicitud_factura.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_factura.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_solicitud_factura.fecha_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_factura.fecha_factura IS 'Fecha de Factura';


--
-- Name: COLUMN sas_solicitud_factura.nro_control; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_factura.nro_control IS 'Numero de Control';


--
-- Name: COLUMN sas_solicitud_factura.nro_factura; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_factura.nro_factura IS 'Numero de Factura';


--
-- Name: COLUMN sas_solicitud_factura.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_factura.observacion IS 'Observacion';


--
-- Name: sas_solicitud_factura_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_solicitud_factura_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_solicitud_factura_id_seq OWNER TO jelitox;

--
-- Name: sas_solicitud_factura_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_solicitud_factura_id_seq OWNED BY sas_solicitud_factura.id;


--
-- Name: sas_solicitud_medicina; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_solicitud_medicina (
    id integer NOT NULL,
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


ALTER TABLE public.sas_solicitud_medicina OWNER TO jelitox;

--
-- Name: TABLE sas_solicitud_medicina; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_solicitud_medicina IS 'Modelo para manipular las Solicitudes de Medicinas';


--
-- Name: COLUMN sas_solicitud_medicina.estado_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.estado_solicitud IS 'Estado de la Solicitud';


--
-- Name: COLUMN sas_solicitud_medicina.fecha_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.fecha_solicitud IS 'Fecha de la Solicitud';


--
-- Name: COLUMN sas_solicitud_medicina.fecha_vencimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.fecha_vencimiento IS 'Fecha de Vencimiento de la Solicitud';


--
-- Name: COLUMN sas_solicitud_medicina.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_solicitud_medicina.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.titular_id IS 'Codigo del Titular';


--
-- Name: COLUMN sas_solicitud_medicina.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.beneficiario_id IS 'Codigo del Beneficiario';


--
-- Name: COLUMN sas_solicitud_medicina.beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.beneficiario_tipo IS 'beneficiario de la Solicitud';


--
-- Name: COLUMN sas_solicitud_medicina.patologia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.patologia_id IS 'Codigo de la Patologia';


--
-- Name: COLUMN sas_solicitud_medicina.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.proveedor_id IS 'Codigo del Proveedor';


--
-- Name: COLUMN sas_solicitud_medicina.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.medico_id IS 'Codigo del Medico';


--
-- Name: COLUMN sas_solicitud_medicina.persona_autorizada; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.persona_autorizada IS 'Persona Autorizada';


--
-- Name: COLUMN sas_solicitud_medicina.persona_cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.persona_cedula IS 'Cedula Persona Autorizada';


--
-- Name: COLUMN sas_solicitud_medicina.tipo_tratamiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.tipo_tratamiento IS 'Tipo de Tratamiento';


--
-- Name: COLUMN sas_solicitud_medicina.diagnostico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.diagnostico IS 'Diagnostico';


--
-- Name: COLUMN sas_solicitud_medicina.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.servicio_id IS 'Codigo del Servicio';


--
-- Name: COLUMN sas_solicitud_medicina.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_medicina.observacion IS 'Observacion';


--
-- Name: sas_solicitud_medicina_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_solicitud_medicina_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_solicitud_medicina_id_seq OWNER TO jelitox;

--
-- Name: sas_solicitud_medicina_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_solicitud_medicina_id_seq OWNED BY sas_solicitud_medicina.id;


--
-- Name: sas_solicitud_servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_solicitud_servicio (
    id integer NOT NULL,
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


ALTER TABLE public.sas_solicitud_servicio OWNER TO jelitox;

--
-- Name: TABLE sas_solicitud_servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_solicitud_servicio IS 'Modelo para manipular las Solicitudes de Servicios';


--
-- Name: COLUMN sas_solicitud_servicio.estado_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.estado_solicitud IS 'Estado de la Solicitud';


--
-- Name: COLUMN sas_solicitud_servicio.tiposolicitud_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.tiposolicitud_id IS 'Tipo de Solicitud';


--
-- Name: COLUMN sas_solicitud_servicio.fecha_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.fecha_solicitud IS 'Fecha de la Solicitud';


--
-- Name: COLUMN sas_solicitud_servicio.codigo_solicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.codigo_solicitud IS 'Codigo de la Solicitud';


--
-- Name: COLUMN sas_solicitud_servicio.titular_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.titular_id IS 'Codigo del Titular';


--
-- Name: COLUMN sas_solicitud_servicio.beneficiario_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.beneficiario_id IS 'Codigo del Beneficiario';


--
-- Name: COLUMN sas_solicitud_servicio.beneficiario_tipo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.beneficiario_tipo IS 'beneficiario de la Solicitud';


--
-- Name: COLUMN sas_solicitud_servicio.patologia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.patologia_id IS 'Codigo de la Patologia';


--
-- Name: COLUMN sas_solicitud_servicio.proveedor_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.proveedor_id IS 'Codigo del Proveedor';


--
-- Name: COLUMN sas_solicitud_servicio.medico_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.medico_id IS 'Codigo del Medico';


--
-- Name: COLUMN sas_solicitud_servicio.fecha_vencimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.fecha_vencimiento IS 'Fecha Vencimiento de la Solicitud';


--
-- Name: COLUMN sas_solicitud_servicio.servicio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.servicio_id IS 'Codigo del Servicio';


--
-- Name: COLUMN sas_solicitud_servicio.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.observacion IS 'Observacion';


--
-- Name: sas_solicitud_servicio_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_solicitud_servicio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_solicitud_servicio_id_seq OWNER TO jelitox;

--
-- Name: sas_solicitud_servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_solicitud_servicio_id_seq OWNED BY sas_solicitud_servicio.id;


--
-- Name: sas_tipoempleado; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_tipoempleado (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_tipoempleado OWNER TO jelitox;

--
-- Name: TABLE sas_tipoempleado; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_tipoempleado IS 'Modelo para manipular las diferentes Profesiones';


--
-- Name: COLUMN sas_tipoempleado.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_tipoempleado.nombre IS 'Nombre de la Profesion';


--
-- Name: COLUMN sas_tipoempleado.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_tipoempleado.observacion IS 'Observacion';


--
-- Name: sas_tipoempleado_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_tipoempleado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_tipoempleado_id_seq OWNER TO jelitox;

--
-- Name: sas_tipoempleado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_tipoempleado_id_seq OWNED BY sas_tipoempleado.id;


--
-- Name: sas_tiposolicitud; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_tiposolicitud (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_tiposolicitud OWNER TO jelitox;

--
-- Name: TABLE sas_tiposolicitud; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_tiposolicitud IS 'Modelo para manipular las diferentes Tipos de Solicitudes';


--
-- Name: COLUMN sas_tiposolicitud.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_tiposolicitud.nombre IS 'Nombre del Tipo Solicitud';


--
-- Name: COLUMN sas_tiposolicitud.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_tiposolicitud.observacion IS 'Observacion';


--
-- Name: sas_tiposolicitud_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_tiposolicitud_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_tiposolicitud_id_seq OWNER TO jelitox;

--
-- Name: sas_tiposolicitud_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_tiposolicitud_id_seq OWNED BY sas_tiposolicitud.id;


--
-- Name: sas_titular; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_titular (
    id integer NOT NULL,
    tipoempleado_id integer NOT NULL,
    nacionalidad character varying(1) DEFAULT 'V'::character varying NOT NULL,
    cedula character varying(8) NOT NULL,
    nombre1 character varying(30) NOT NULL,
    nombre2 character varying(30) NOT NULL,
    apellido1 character varying(30) NOT NULL,
    apellido2 character varying(30) NOT NULL,
    sexo character varying(1) DEFAULT 'M'::character varying NOT NULL,
    fecha_nacimiento date DEFAULT '1900-01-01'::date,
    pais_id integer NOT NULL,
    pais_estado_id integer NOT NULL,
    ciudad_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    direccion_habitacion character varying(250) NOT NULL,
    estado_civil character varying(1) DEFAULT 'S'::character varying NOT NULL,
    celular character varying(12),
    telefono character varying(12),
    correo_electronico character varying(64),
    fecha_ingreso date DEFAULT '1900-01-01'::date,
    profesion_id integer NOT NULL,
    departamento_id integer NOT NULL,
    cargo_id integer NOT NULL,
    grupo_sanguineo character varying(4),
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_titular OWNER TO jelitox;

--
-- Name: TABLE sas_titular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_titular IS 'Modelo para manipular los Titulares';


--
-- Name: COLUMN sas_titular.tipoempleado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.tipoempleado_id IS 'Tipo de Empleado';


--
-- Name: COLUMN sas_titular.nacionalidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.nacionalidad IS 'Nacionalidad del Titular';


--
-- Name: COLUMN sas_titular.cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.cedula IS 'N° Cedula Titular';


--
-- Name: COLUMN sas_titular.nombre1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.nombre1 IS 'N° Primer Nombre del Titular';


--
-- Name: COLUMN sas_titular.nombre2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.nombre2 IS 'N° Segundo Nombre del Titular';


--
-- Name: COLUMN sas_titular.apellido1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.apellido1 IS 'N° Primer Apellido del Titular';


--
-- Name: COLUMN sas_titular.apellido2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.apellido2 IS 'N° Segundo Apellido del Titular';


--
-- Name: COLUMN sas_titular.sexo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.sexo IS 'N° Sexo del Titular';


--
-- Name: COLUMN sas_titular.fecha_nacimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.fecha_nacimiento IS 'Fecha de Nacimiento del Titular';


--
-- Name: COLUMN sas_titular.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.pais_id IS 'Pais Origen del Titular';


--
-- Name: COLUMN sas_titular.pais_estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.pais_estado_id IS 'Estado de Origen del Titular';


--
-- Name: COLUMN sas_titular.ciudad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.ciudad_id IS 'Ciudad de Origen del Titular';


--
-- Name: COLUMN sas_titular.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.municipio_id IS 'Municipio de Origen del Titular';


--
-- Name: COLUMN sas_titular.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.parroquia_id IS 'Parroquia de Origen del Titular';


--
-- Name: COLUMN sas_titular.direccion_habitacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.direccion_habitacion IS 'Direccion de Habitacion del Titular';


--
-- Name: COLUMN sas_titular.estado_civil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.estado_civil IS 'Estado Civil del Titular';


--
-- Name: COLUMN sas_titular.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.celular IS 'N° de Celular del Titular';


--
-- Name: COLUMN sas_titular.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.telefono IS 'N° de Telefono del Titular';


--
-- Name: COLUMN sas_titular.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.correo_electronico IS 'Direccion de Correo Electronico del Titular';


--
-- Name: COLUMN sas_titular.fecha_ingreso; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.fecha_ingreso IS 'Fecha de Ingreso del Titular';


--
-- Name: COLUMN sas_titular.profesion_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.profesion_id IS 'Profesion del Titular';


--
-- Name: COLUMN sas_titular.departamento_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.departamento_id IS 'Departamento al que pertenece el Titular';


--
-- Name: COLUMN sas_titular.cargo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.cargo_id IS 'Departamento al que pertenece el Titular';


--
-- Name: COLUMN sas_titular.grupo_sanguineo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.grupo_sanguineo IS 'Grupo Sanguineo del Titular';


--
-- Name: COLUMN sas_titular.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.observacion IS 'Observacion';


--
-- Name: sas_titular_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_titular_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_titular_id_seq OWNER TO jelitox;

--
-- Name: sas_titular_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_titular_id_seq OWNED BY sas_titular.id;


--
-- Name: sas_upsa; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_upsa (
    id integer NOT NULL,
    nombre character varying(64) NOT NULL,
    ciudad_id integer NOT NULL,
    municipio_id integer NOT NULL,
    parroquia_id integer NOT NULL,
    direccion character varying(100) NOT NULL,
    telefono character varying(64) NOT NULL,
    correo character varying(100) NOT NULL,
    observacion character varying(250) NOT NULL
);


ALTER TABLE public.sas_upsa OWNER TO jelitox;

--
-- Name: TABLE sas_upsa; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_upsa IS 'Modelo para manipular las diferentes UPSAS';


--
-- Name: COLUMN sas_upsa.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.nombre IS 'Nombre de la UPSA';


--
-- Name: COLUMN sas_upsa.ciudad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.ciudad_id IS 'Codigo de Ciudad';


--
-- Name: COLUMN sas_upsa.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.municipio_id IS 'Codigo de Municipio';


--
-- Name: COLUMN sas_upsa.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.parroquia_id IS 'Codigo de Parroquia';


--
-- Name: COLUMN sas_upsa.direccion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.direccion IS 'Direccion de la UPSA';


--
-- Name: COLUMN sas_upsa.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.telefono IS 'Telefono de la UPSA';


--
-- Name: COLUMN sas_upsa.correo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.correo IS 'Correo de la UPSA';


--
-- Name: COLUMN sas_upsa.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_upsa.observacion IS 'Observacion';


--
-- Name: sas_upsa_id_seq; Type: SEQUENCE; Schema: public; Owner: jelitox
--

CREATE SEQUENCE sas_upsa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sas_upsa_id_seq OWNER TO jelitox;

--
-- Name: sas_upsa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_upsa_id_seq OWNED BY sas_upsa.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario ALTER COLUMN id SET DEFAULT nextval('sas_beneficiario_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario_tipo ALTER COLUMN id SET DEFAULT nextval('sas_beneficiario_tipo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_cargo ALTER COLUMN id SET DEFAULT nextval('sas_cargo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_ciudad ALTER COLUMN id SET DEFAULT nextval('sas_ciudad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_cobertura ALTER COLUMN id SET DEFAULT nextval('sas_cobertura_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_departamento ALTER COLUMN id SET DEFAULT nextval('sas_departamento_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad ALTER COLUMN id SET DEFAULT nextval('sas_discapacidad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad_beneficiario ALTER COLUMN id SET DEFAULT nextval('sas_discapacidad_beneficiario_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad_titular ALTER COLUMN id SET DEFAULT nextval('sas_discapacidad_titular_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_especialidad ALTER COLUMN id SET DEFAULT nextval('sas_especialidad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_medicina ALTER COLUMN id SET DEFAULT nextval('sas_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_medico ALTER COLUMN id SET DEFAULT nextval('sas_medico_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_municipio ALTER COLUMN id SET DEFAULT nextval('sas_municipio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_pais ALTER COLUMN id SET DEFAULT nextval('sas_pais_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_pais_estado ALTER COLUMN id SET DEFAULT nextval('sas_pais_estado_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_parroquia ALTER COLUMN id SET DEFAULT nextval('sas_parroquia_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_patologia ALTER COLUMN id SET DEFAULT nextval('sas_patologia_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_profesion ALTER COLUMN id SET DEFAULT nextval('sas_profesion_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor ALTER COLUMN id SET DEFAULT nextval('sas_proveedor_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo ALTER COLUMN id SET DEFAULT nextval('sas_recaudo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_reembolso ALTER COLUMN id SET DEFAULT nextval('sas_recaudo_reembolso_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_solicitud_medicina ALTER COLUMN id SET DEFAULT nextval('sas_recaudo_solicitud_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_solicitud_servicio ALTER COLUMN id SET DEFAULT nextval('sas_recaudo_solicitud_servicio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_titular ALTER COLUMN id SET DEFAULT nextval('sas_recaudo_titular_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_reembolso ALTER COLUMN id SET DEFAULT nextval('sas_reembolso_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_servicio ALTER COLUMN id SET DEFAULT nextval('sas_servicio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_dt_factura ALTER COLUMN id SET DEFAULT nextval('sas_solicitud_dt_factura_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_dt_medicina ALTER COLUMN id SET DEFAULT nextval('sas_solicitud_dt_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_factura ALTER COLUMN id SET DEFAULT nextval('sas_solicitud_factura_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina ALTER COLUMN id SET DEFAULT nextval('sas_solicitud_medicina_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio ALTER COLUMN id SET DEFAULT nextval('sas_solicitud_servicio_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_tipoempleado ALTER COLUMN id SET DEFAULT nextval('sas_tipoempleado_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_tiposolicitud ALTER COLUMN id SET DEFAULT nextval('sas_tiposolicitud_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular ALTER COLUMN id SET DEFAULT nextval('sas_titular_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_upsa ALTER COLUMN id SET DEFAULT nextval('sas_upsa_id_seq'::regclass);


--
-- Data for Name: sas_beneficiario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_beneficiario (id, titular_id, parentesco, nacionalidad, cedula, nombre1, nombre2, apellido1, apellido2, sexo, fecha_nacimiento, pais_id, pais_estado_id, ciudad_id, municipio_id, parroquia_id, direccion_habitacion, estado_civil, celular, telefono, correo_electronico, grupo_sanguineo, beneficiario_tipo_id, observacion) FROM stdin;
\.


--
-- Name: sas_beneficiario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_beneficiario_id_seq', 1, false);


--
-- Data for Name: sas_beneficiario_tipo; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_beneficiario_tipo (id, descripcion, observacion) FROM stdin;
\.


--
-- Name: sas_beneficiario_tipo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_beneficiario_tipo_id_seq', 1, false);


--
-- Data for Name: sas_cargo; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_cargo (id, nombre, observacion) FROM stdin;
\.


--
-- Name: sas_cargo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_cargo_id_seq', 1, false);


--
-- Data for Name: sas_ciudad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_ciudad (id, estado_id, codigo, nombre) FROM stdin;
\.


--
-- Name: sas_ciudad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_ciudad_id_seq', 1, false);


--
-- Data for Name: sas_cobertura; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_cobertura (id, descripcion, tipo_cobertura, monto_cobertura, fecha_inicio, fecha_fin, observacion) FROM stdin;
\.


--
-- Name: sas_cobertura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_cobertura_id_seq', 1, false);


--
-- Data for Name: sas_departamento; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_departamento (id, nombre, observacion) FROM stdin;
\.


--
-- Name: sas_departamento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_departamento_id_seq', 1, false);


--
-- Data for Name: sas_discapacidad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_discapacidad (id, nombre, observacion) FROM stdin;
\.


--
-- Data for Name: sas_discapacidad_beneficiario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_discapacidad_beneficiario (id, beneficiario_id, discapacidad_id) FROM stdin;
\.


--
-- Name: sas_discapacidad_beneficiario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_discapacidad_beneficiario_id_seq', 1, false);


--
-- Name: sas_discapacidad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_discapacidad_id_seq', 1, false);


--
-- Data for Name: sas_discapacidad_titular; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_discapacidad_titular (id, titular_id, discapacidad_id) FROM stdin;
\.


--
-- Name: sas_discapacidad_titular_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_discapacidad_titular_id_seq', 1, false);


--
-- Data for Name: sas_especialidad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_especialidad (id, descripcion, observacion) FROM stdin;
\.


--
-- Name: sas_especialidad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_especialidad_id_seq', 1, false);


--
-- Data for Name: sas_especialidad_medico; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_especialidad_medico (id, medico_id, especialidad_id) FROM stdin;
\.


--
-- Name: sas_especialidad_medico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_especialidad_medico_id_seq', 1, false);


--
-- Data for Name: sas_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_medicina (id, descripcion, observacion) FROM stdin;
\.


--
-- Name: sas_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_medicina_id_seq', 1, false);


--
-- Data for Name: sas_medico; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_medico (id, nacionalidad, cedula, rmpps, rif, nombre1, nombre2, apellido1, apellido2, sexo, especialidad_id, celular, telefono, correo_electronico, observacion) FROM stdin;
\.


--
-- Name: sas_medico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_medico_id_seq', 1, false);


--
-- Data for Name: sas_municipio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_municipio (id, estado_id, codigo, nombre) FROM stdin;
\.


--
-- Name: sas_municipio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_municipio_id_seq', 1, false);


--
-- Data for Name: sas_pais; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_pais (id, codigo, nombre) FROM stdin;
\.


--
-- Data for Name: sas_pais_estado; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_pais_estado (id, codigo, pais_id, nombre) FROM stdin;
\.


--
-- Name: sas_pais_estado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_pais_estado_id_seq', 1, false);


--
-- Name: sas_pais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_pais_id_seq', 253, true);


--
-- Data for Name: sas_parroquia; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_parroquia (id, nombre, municipio_id) FROM stdin;
\.


--
-- Name: sas_parroquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_parroquia_id_seq', 1, false);


--
-- Data for Name: sas_patologia; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_patologia (id, descripcion, observacion) FROM stdin;
\.


--
-- Name: sas_patologia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_patologia_id_seq', 1, false);


--
-- Data for Name: sas_profesion; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_profesion (id, nombre, observacion) FROM stdin;
\.


--
-- Name: sas_profesion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_profesion_id_seq', 1, false);


--
-- Data for Name: sas_proveedor; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_proveedor (id, rif, razon_social, nombre_corto, pais_id, pais_estado_id, ciudad_id, municipio_id, parroquia_id, direccion, celular, telefono1, telefono2, fax, correo_electronico, observacion) FROM stdin;
\.


--
-- Name: sas_proveedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_proveedor_id_seq', 1, false);


--
-- Data for Name: sas_proveedor_medico; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_proveedor_medico (id, medico_id, proveedor_id) FROM stdin;
\.


--
-- Name: sas_proveedor_medico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_proveedor_medico_id_seq', 1, false);


--
-- Data for Name: sas_recaudo; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_recaudo (id, nombre, tipo, observacion) FROM stdin;
\.


--
-- Data for Name: sas_recaudo_beneficiario; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_recaudo_beneficiario (id, beneficiario_id, recaudo_id, estado) FROM stdin;
\.


--
-- Name: sas_recaudo_beneficiario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_recaudo_beneficiario_id_seq', 1, false);


--
-- Name: sas_recaudo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_recaudo_id_seq', 1, false);


--
-- Data for Name: sas_recaudo_reembolso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_recaudo_reembolso (id, recaudo_id, codigo_solicitud, estado) FROM stdin;
\.


--
-- Name: sas_recaudo_reembolso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_recaudo_reembolso_id_seq', 1, false);


--
-- Data for Name: sas_recaudo_solicitud_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_recaudo_solicitud_medicina (id, recaudo_id, codigo_solicitud, estado) FROM stdin;
\.


--
-- Name: sas_recaudo_solicitud_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_recaudo_solicitud_medicina_id_seq', 1, false);


--
-- Data for Name: sas_recaudo_solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_recaudo_solicitud_servicio (id, recaudo_id, codigo_solicitud, estado) FROM stdin;
\.


--
-- Name: sas_recaudo_solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_recaudo_solicitud_servicio_id_seq', 1, false);


--
-- Data for Name: sas_recaudo_titular; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_recaudo_titular (id, titular_id, recaudo_id, estado) FROM stdin;
\.


--
-- Name: sas_recaudo_titular_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_recaudo_titular_id_seq', 1, false);


--
-- Data for Name: sas_reembolso; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_reembolso (id, estado_solicitud, fecha_solicitud, codigo_solicitud, titular_id, beneficiario_id, beneficiario_tipo, observacion) FROM stdin;
\.


--
-- Name: sas_reembolso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_reembolso_id_seq', 1, false);


--
-- Data for Name: sas_servicio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_servicio (id, descripcion, observacion) FROM stdin;
\.


--
-- Name: sas_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_servicio_id_seq', 1, false);


--
-- Data for Name: sas_servicio_proveedor; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_servicio_proveedor (id, proveedor_id, servicio_id) FROM stdin;
\.


--
-- Name: sas_servicio_proveedor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_servicio_proveedor_id_seq', 1, false);


--
-- Data for Name: sas_servicio_tiposolicitud; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_servicio_tiposolicitud (id, tiposolicitud_id, servicio_id) FROM stdin;
\.


--
-- Name: sas_servicio_tiposolicitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_servicio_tiposolicitud_id_seq', 1, false);


--
-- Data for Name: sas_solicitud_dt_factura; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_solicitud_dt_factura (id, solicitud_factura_id, descripcion, cantidad, monto, exento) FROM stdin;
\.


--
-- Name: sas_solicitud_dt_factura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_solicitud_dt_factura_id_seq', 1, false);


--
-- Data for Name: sas_solicitud_dt_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_solicitud_dt_medicina (id, solicitud_id, medicina_id, fecha_inicio, fecha_fin, dosis, horas) FROM stdin;
\.


--
-- Name: sas_solicitud_dt_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_solicitud_dt_medicina_id_seq', 1, false);


--
-- Data for Name: sas_solicitud_factura; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_solicitud_factura (id, solicitud_servicio_id, codigo_solicitud, fecha_factura, nro_control, nro_factura, observacion) FROM stdin;
\.


--
-- Name: sas_solicitud_factura_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_solicitud_factura_id_seq', 1, false);


--
-- Data for Name: sas_solicitud_medicina; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_solicitud_medicina (id, estado_solicitud, fecha_solicitud, fecha_vencimiento, codigo_solicitud, titular_id, beneficiario_id, beneficiario_tipo, patologia_id, proveedor_id, medico_id, persona_autorizada, persona_cedula, tipo_tratamiento, diagnostico, servicio_id, observacion) FROM stdin;
\.


--
-- Name: sas_solicitud_medicina_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_solicitud_medicina_id_seq', 1, false);


--
-- Data for Name: sas_solicitud_servicio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_solicitud_servicio (id, estado_solicitud, tiposolicitud_id, fecha_solicitud, codigo_solicitud, titular_id, beneficiario_id, beneficiario_tipo, patologia_id, proveedor_id, medico_id, fecha_vencimiento, servicio_id, observacion) FROM stdin;
\.


--
-- Name: sas_solicitud_servicio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_solicitud_servicio_id_seq', 1, false);


--
-- Data for Name: sas_tipoempleado; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_tipoempleado (id, nombre, observacion) FROM stdin;
\.


--
-- Name: sas_tipoempleado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_tipoempleado_id_seq', 1, false);


--
-- Data for Name: sas_tiposolicitud; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_tiposolicitud (id, nombre, observacion) FROM stdin;
\.


--
-- Name: sas_tiposolicitud_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_tiposolicitud_id_seq', 1, false);


--
-- Data for Name: sas_titular; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_titular (id, tipoempleado_id, nacionalidad, cedula, nombre1, nombre2, apellido1, apellido2, sexo, fecha_nacimiento, pais_id, pais_estado_id, ciudad_id, municipio_id, parroquia_id, direccion_habitacion, estado_civil, celular, telefono, correo_electronico, fecha_ingreso, profesion_id, departamento_id, cargo_id, grupo_sanguineo, observacion) FROM stdin;
\.


--
-- Name: sas_titular_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_titular_id_seq', 1, false);


--
-- Data for Name: sas_upsa; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_upsa (id, nombre, ciudad_id, municipio_id, parroquia_id, direccion, telefono, correo, observacion) FROM stdin;
\.


--
-- Name: sas_upsa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_upsa_id_seq', 1, false);


--
-- Name: sas_beneficiario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_pkey PRIMARY KEY (id);


--
-- Name: sas_beneficiario_tipo_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_beneficiario_tipo
    ADD CONSTRAINT sas_beneficiario_tipo_descripcion_key UNIQUE (descripcion);


--
-- Name: sas_beneficiario_tipo_descripcion_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_beneficiario_tipo
    ADD CONSTRAINT sas_beneficiario_tipo_descripcion_unico UNIQUE (descripcion);


--
-- Name: sas_beneficiario_tipo_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_beneficiario_tipo
    ADD CONSTRAINT sas_beneficiario_tipo_pkey PRIMARY KEY (id);


--
-- Name: sas_cargo_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_cargo
    ADD CONSTRAINT sas_cargo_nombre_key UNIQUE (nombre);


--
-- Name: sas_cargo_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_cargo
    ADD CONSTRAINT sas_cargo_pkey PRIMARY KEY (id);


--
-- Name: sas_ciudad_codigo_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_ciudad
    ADD CONSTRAINT sas_ciudad_codigo_unico UNIQUE (codigo);


--
-- Name: sas_ciudad_nombre_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_ciudad
    ADD CONSTRAINT sas_ciudad_nombre_unico UNIQUE (nombre);


--
-- Name: sas_ciudad_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_ciudad
    ADD CONSTRAINT sas_ciudad_pkey PRIMARY KEY (id);


--
-- Name: sas_cobertura_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_cobertura
    ADD CONSTRAINT sas_cobertura_pkey PRIMARY KEY (id);


--
-- Name: sas_departamento_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_departamento
    ADD CONSTRAINT sas_departamento_nombre_key UNIQUE (nombre);


--
-- Name: sas_departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_departamento
    ADD CONSTRAINT sas_departamento_pkey PRIMARY KEY (id);


--
-- Name: sas_discapacidad_beneficiario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_discapacidad_beneficiario
    ADD CONSTRAINT sas_discapacidad_beneficiario_pkey PRIMARY KEY (id);


--
-- Name: sas_discapacidad_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_discapacidad
    ADD CONSTRAINT sas_discapacidad_nombre_key UNIQUE (nombre);


--
-- Name: sas_discapacidad_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_discapacidad
    ADD CONSTRAINT sas_discapacidad_pkey PRIMARY KEY (id);


--
-- Name: sas_discapacidad_titular_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_discapacidad_titular
    ADD CONSTRAINT sas_discapacidad_titular_pkey PRIMARY KEY (id);


--
-- Name: sas_especialidad_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_especialidad
    ADD CONSTRAINT sas_especialidad_descripcion_key UNIQUE (descripcion);


--
-- Name: sas_especialidad_medico_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_especialidad_medico
    ADD CONSTRAINT sas_especialidad_medico_pkey PRIMARY KEY (id);


--
-- Name: sas_especialidad_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_especialidad
    ADD CONSTRAINT sas_especialidad_pkey PRIMARY KEY (id);


--
-- Name: sas_medicina_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_medicina
    ADD CONSTRAINT sas_medicina_descripcion_key UNIQUE (descripcion);


--
-- Name: sas_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_medicina
    ADD CONSTRAINT sas_medicina_pkey PRIMARY KEY (id);


--
-- Name: sas_medico_cedula_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_medico
    ADD CONSTRAINT sas_medico_cedula_key UNIQUE (cedula);


--
-- Name: sas_medico_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_medico
    ADD CONSTRAINT sas_medico_pkey PRIMARY KEY (id);


--
-- Name: sas_medico_rif_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_medico
    ADD CONSTRAINT sas_medico_rif_key UNIQUE (rif);


--
-- Name: sas_medico_rmpps_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_medico
    ADD CONSTRAINT sas_medico_rmpps_key UNIQUE (rmpps);


--
-- Name: sas_municipio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_municipio
    ADD CONSTRAINT sas_municipio_pkey PRIMARY KEY (id);


--
-- Name: sas_pais_codigo_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_pais
    ADD CONSTRAINT sas_pais_codigo_unico UNIQUE (codigo);


--
-- Name: sas_pais_estado_codigo_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_pais_estado
    ADD CONSTRAINT sas_pais_estado_codigo_unico UNIQUE (codigo);


--
-- Name: sas_pais_estado_nombre_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_pais_estado
    ADD CONSTRAINT sas_pais_estado_nombre_unico UNIQUE (nombre);


--
-- Name: sas_pais_estado_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_pais_estado
    ADD CONSTRAINT sas_pais_estado_pkey PRIMARY KEY (id);


--
-- Name: sas_pais_nombre_unico; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_pais
    ADD CONSTRAINT sas_pais_nombre_unico UNIQUE (nombre);


--
-- Name: sas_pais_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_pais
    ADD CONSTRAINT sas_pais_pkey PRIMARY KEY (id);


--
-- Name: sas_parroquia_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_parroquia
    ADD CONSTRAINT sas_parroquia_pkey PRIMARY KEY (id);


--
-- Name: sas_patologia_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_patologia
    ADD CONSTRAINT sas_patologia_descripcion_key UNIQUE (descripcion);


--
-- Name: sas_patologia_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_patologia
    ADD CONSTRAINT sas_patologia_pkey PRIMARY KEY (id);


--
-- Name: sas_profesion_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_profesion
    ADD CONSTRAINT sas_profesion_nombre_key UNIQUE (nombre);


--
-- Name: sas_profesion_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_profesion
    ADD CONSTRAINT sas_profesion_pkey PRIMARY KEY (id);


--
-- Name: sas_proveedor_medico_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_proveedor_medico
    ADD CONSTRAINT sas_proveedor_medico_pkey PRIMARY KEY (id);


--
-- Name: sas_proveedor_nombre_corto_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_nombre_corto_key UNIQUE (nombre_corto);


--
-- Name: sas_proveedor_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_pkey PRIMARY KEY (id);


--
-- Name: sas_proveedor_razon_social_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_razon_social_key UNIQUE (razon_social);


--
-- Name: sas_proveedor_rif_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_rif_key UNIQUE (rif);


--
-- Name: sas_recaudo_beneficiario_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_beneficiario
    ADD CONSTRAINT sas_recaudo_beneficiario_pkey PRIMARY KEY (id);


--
-- Name: sas_recaudo_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo
    ADD CONSTRAINT sas_recaudo_nombre_key UNIQUE (nombre);


--
-- Name: sas_recaudo_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo
    ADD CONSTRAINT sas_recaudo_pkey PRIMARY KEY (id);


--
-- Name: sas_recaudo_reembolso_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_reembolso
    ADD CONSTRAINT sas_recaudo_reembolso_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_recaudo_reembolso_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_reembolso
    ADD CONSTRAINT sas_recaudo_reembolso_pkey PRIMARY KEY (id);


--
-- Name: sas_recaudo_solicitud_medicina_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_solicitud_medicina
    ADD CONSTRAINT sas_recaudo_solicitud_medicina_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_recaudo_solicitud_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_solicitud_medicina
    ADD CONSTRAINT sas_recaudo_solicitud_medicina_pkey PRIMARY KEY (id);


--
-- Name: sas_recaudo_solicitud_servicio_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_solicitud_servicio
    ADD CONSTRAINT sas_recaudo_solicitud_servicio_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_recaudo_solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_solicitud_servicio
    ADD CONSTRAINT sas_recaudo_solicitud_servicio_pkey PRIMARY KEY (id);


--
-- Name: sas_recaudo_titular_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_recaudo_titular
    ADD CONSTRAINT sas_recaudo_titular_pkey PRIMARY KEY (id);


--
-- Name: sas_reembolso_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_reembolso
    ADD CONSTRAINT sas_reembolso_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_reembolso_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_reembolso
    ADD CONSTRAINT sas_reembolso_pkey PRIMARY KEY (id);


--
-- Name: sas_servicio_descripcion_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_servicio
    ADD CONSTRAINT sas_servicio_descripcion_key UNIQUE (descripcion);


--
-- Name: sas_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_servicio
    ADD CONSTRAINT sas_servicio_pkey PRIMARY KEY (id);


--
-- Name: sas_servicio_proveedor_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_servicio_proveedor
    ADD CONSTRAINT sas_servicio_proveedor_pkey PRIMARY KEY (id);


--
-- Name: sas_servicio_tiposolicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_servicio_tiposolicitud
    ADD CONSTRAINT sas_servicio_tiposolicitud_pkey PRIMARY KEY (id);


--
-- Name: sas_solicitud_dt_factura_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_dt_factura
    ADD CONSTRAINT sas_solicitud_dt_factura_pkey PRIMARY KEY (id);


--
-- Name: sas_solicitud_dt_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_dt_medicina
    ADD CONSTRAINT sas_solicitud_dt_medicina_pkey PRIMARY KEY (id);


--
-- Name: sas_solicitud_factura_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_factura
    ADD CONSTRAINT sas_solicitud_factura_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_solicitud_factura_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_factura
    ADD CONSTRAINT sas_solicitud_factura_pkey PRIMARY KEY (id);


--
-- Name: sas_solicitud_medicina_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_solicitud_medicina_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_pkey PRIMARY KEY (id);


--
-- Name: sas_solicitud_servicio_codigo_solicitud_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_codigo_solicitud_key UNIQUE (codigo_solicitud);


--
-- Name: sas_solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_pkey PRIMARY KEY (id);


--
-- Name: sas_tipoempleado_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_tipoempleado
    ADD CONSTRAINT sas_tipoempleado_nombre_key UNIQUE (nombre);


--
-- Name: sas_tipoempleado_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_tipoempleado
    ADD CONSTRAINT sas_tipoempleado_pkey PRIMARY KEY (id);


--
-- Name: sas_tiposolicitud_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_tiposolicitud
    ADD CONSTRAINT sas_tiposolicitud_nombre_key UNIQUE (nombre);


--
-- Name: sas_tiposolicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_tiposolicitud
    ADD CONSTRAINT sas_tiposolicitud_pkey PRIMARY KEY (id);


--
-- Name: sas_titular_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_pkey PRIMARY KEY (id);


--
-- Name: sas_upsa_nombre_key; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_upsa
    ADD CONSTRAINT sas_upsa_nombre_key UNIQUE (nombre);


--
-- Name: sas_upsa_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_upsa
    ADD CONSTRAINT sas_upsa_pkey PRIMARY KEY (id);


--
-- Name: sas_beneficiario_beneficiario_tipo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_beneficiario_tipo_id_fkey FOREIGN KEY (beneficiario_tipo_id) REFERENCES sas_beneficiario_tipo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_beneficiario_ciudad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_ciudad_id_fkey FOREIGN KEY (ciudad_id) REFERENCES sas_ciudad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_beneficiario_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES sas_municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_beneficiario_pais_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_pais_estado_id_fkey FOREIGN KEY (pais_estado_id) REFERENCES sas_pais_estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_beneficiario_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES sas_pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_beneficiario_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES sas_parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_beneficiario_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_beneficiario
    ADD CONSTRAINT sas_beneficiario_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES sas_titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_ciudad_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_ciudad
    ADD CONSTRAINT sas_ciudad_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES sas_pais_estado(id) ON DELETE SET NULL;


--
-- Name: sas_discapacidad_beneficiario_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad_beneficiario
    ADD CONSTRAINT sas_discapacidad_beneficiario_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES sas_beneficiario(id) ON DELETE SET NULL;


--
-- Name: sas_discapacidad_beneficiario_discapacidad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad_beneficiario
    ADD CONSTRAINT sas_discapacidad_beneficiario_discapacidad_id_fkey FOREIGN KEY (discapacidad_id) REFERENCES sas_discapacidad(id) ON DELETE SET NULL;


--
-- Name: sas_discapacidad_titular_discapacidad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad_titular
    ADD CONSTRAINT sas_discapacidad_titular_discapacidad_id_fkey FOREIGN KEY (discapacidad_id) REFERENCES sas_discapacidad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_discapacidad_titular_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_discapacidad_titular
    ADD CONSTRAINT sas_discapacidad_titular_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES sas_titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_especialidad_medico_especialidad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_especialidad_medico
    ADD CONSTRAINT sas_especialidad_medico_especialidad_id_fkey FOREIGN KEY (especialidad_id) REFERENCES sas_especialidad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_especialidad_medico_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_especialidad_medico
    ADD CONSTRAINT sas_especialidad_medico_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES sas_medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_medico_especialidad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_medico
    ADD CONSTRAINT sas_medico_especialidad_id_fkey FOREIGN KEY (especialidad_id) REFERENCES sas_especialidad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_municipio_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_municipio
    ADD CONSTRAINT sas_municipio_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES sas_pais_estado(id) ON DELETE SET NULL;


--
-- Name: sas_pais_estado_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_pais_estado
    ADD CONSTRAINT sas_pais_estado_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES sas_pais(id) ON DELETE SET NULL;


--
-- Name: sas_parroquia_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_parroquia
    ADD CONSTRAINT sas_parroquia_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES sas_municipio(id) ON DELETE SET NULL;


--
-- Name: sas_proveedor_ciudad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_ciudad_id_fkey FOREIGN KEY (ciudad_id) REFERENCES sas_ciudad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_proveedor_medico_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor_medico
    ADD CONSTRAINT sas_proveedor_medico_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES sas_medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_proveedor_medico_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor_medico
    ADD CONSTRAINT sas_proveedor_medico_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES sas_proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_proveedor_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES sas_municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_proveedor_pais_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_pais_estado_id_fkey FOREIGN KEY (pais_estado_id) REFERENCES sas_pais_estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_proveedor_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES sas_pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_proveedor_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_proveedor
    ADD CONSTRAINT sas_proveedor_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES sas_parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_beneficiario_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_beneficiario
    ADD CONSTRAINT sas_recaudo_beneficiario_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES sas_beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_beneficiario_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_beneficiario
    ADD CONSTRAINT sas_recaudo_beneficiario_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES sas_recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_reembolso_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_reembolso
    ADD CONSTRAINT sas_recaudo_reembolso_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES sas_reembolso(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_reembolso_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_reembolso
    ADD CONSTRAINT sas_recaudo_reembolso_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES sas_recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_solicitud_medicina_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_solicitud_medicina
    ADD CONSTRAINT sas_recaudo_solicitud_medicina_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES sas_solicitud_medicina(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_solicitud_medicina_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_solicitud_medicina
    ADD CONSTRAINT sas_recaudo_solicitud_medicina_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES sas_recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_solicitud_servicio_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_solicitud_servicio
    ADD CONSTRAINT sas_recaudo_solicitud_servicio_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES sas_solicitud_servicio(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_solicitud_servicio_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_solicitud_servicio
    ADD CONSTRAINT sas_recaudo_solicitud_servicio_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES sas_recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_titular_recaudo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_titular
    ADD CONSTRAINT sas_recaudo_titular_recaudo_id_fkey FOREIGN KEY (recaudo_id) REFERENCES sas_recaudo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_recaudo_titular_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_recaudo_titular
    ADD CONSTRAINT sas_recaudo_titular_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES sas_titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_reembolso_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_reembolso
    ADD CONSTRAINT sas_reembolso_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES sas_beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_reembolso_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_reembolso
    ADD CONSTRAINT sas_reembolso_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES sas_titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_servicio_proveedor_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_servicio_proveedor
    ADD CONSTRAINT sas_servicio_proveedor_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES sas_proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_servicio_proveedor_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_servicio_proveedor
    ADD CONSTRAINT sas_servicio_proveedor_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES sas_servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_servicio_tiposolicitud_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_servicio_tiposolicitud
    ADD CONSTRAINT sas_servicio_tiposolicitud_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES sas_servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_servicio_tiposolicitud_tiposolicitud_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_servicio_tiposolicitud
    ADD CONSTRAINT sas_servicio_tiposolicitud_tiposolicitud_id_fkey FOREIGN KEY (tiposolicitud_id) REFERENCES sas_tiposolicitud(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_dt_factura_solicitud_factura_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_dt_factura
    ADD CONSTRAINT sas_solicitud_dt_factura_solicitud_factura_id_fkey FOREIGN KEY (solicitud_factura_id) REFERENCES sas_solicitud_factura(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_dt_medicina_medicina_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_dt_medicina
    ADD CONSTRAINT sas_solicitud_dt_medicina_medicina_id_fkey FOREIGN KEY (medicina_id) REFERENCES sas_medicina(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_dt_medicina_solicitud_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_dt_medicina
    ADD CONSTRAINT sas_solicitud_dt_medicina_solicitud_id_fkey FOREIGN KEY (solicitud_id) REFERENCES sas_solicitud_medicina(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_factura_codigo_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_factura
    ADD CONSTRAINT sas_solicitud_factura_codigo_solicitud_fkey FOREIGN KEY (codigo_solicitud) REFERENCES sas_solicitud_servicio(codigo_solicitud) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_factura_solicitud_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_factura
    ADD CONSTRAINT sas_solicitud_factura_solicitud_servicio_id_fkey FOREIGN KEY (solicitud_servicio_id) REFERENCES sas_solicitud_servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_medicina_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES sas_beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_medicina_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES sas_medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_medicina_patologia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_patologia_id_fkey FOREIGN KEY (patologia_id) REFERENCES sas_patologia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_medicina_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES sas_proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_medicina_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES sas_servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_medicina_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_medicina
    ADD CONSTRAINT sas_solicitud_medicina_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES sas_titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_beneficiario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_beneficiario_id_fkey FOREIGN KEY (beneficiario_id) REFERENCES sas_beneficiario(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_medico_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_medico_id_fkey FOREIGN KEY (medico_id) REFERENCES sas_medico(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_patologia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_patologia_id_fkey FOREIGN KEY (patologia_id) REFERENCES sas_patologia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_proveedor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_proveedor_id_fkey FOREIGN KEY (proveedor_id) REFERENCES sas_proveedor(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_servicio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_servicio_id_fkey FOREIGN KEY (servicio_id) REFERENCES sas_servicio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_tiposolicitud_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_tiposolicitud_id_fkey FOREIGN KEY (tiposolicitud_id) REFERENCES sas_tiposolicitud(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_solicitud_servicio_titular_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_titular_id_fkey FOREIGN KEY (titular_id) REFERENCES sas_titular(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_cargo_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_cargo_id_fkey FOREIGN KEY (cargo_id) REFERENCES sas_cargo(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_ciudad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_ciudad_id_fkey FOREIGN KEY (ciudad_id) REFERENCES sas_ciudad(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_departamento_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_departamento_id_fkey FOREIGN KEY (departamento_id) REFERENCES sas_departamento(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_municipio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_municipio_id_fkey FOREIGN KEY (municipio_id) REFERENCES sas_municipio(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_pais_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_pais_estado_id_fkey FOREIGN KEY (pais_estado_id) REFERENCES sas_pais_estado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_pais_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_pais_id_fkey FOREIGN KEY (pais_id) REFERENCES sas_pais(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_parroquia_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_parroquia_id_fkey FOREIGN KEY (parroquia_id) REFERENCES sas_parroquia(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_profesion_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_profesion_id_fkey FOREIGN KEY (profesion_id) REFERENCES sas_profesion(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: sas_titular_tipoempleado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_titular
    ADD CONSTRAINT sas_titular_tipoempleado_id_fkey FOREIGN KEY (tipoempleado_id) REFERENCES sas_tipoempleado(id) ON UPDATE CASCADE ON DELETE RESTRICT;


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

