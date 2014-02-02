--
-- Name: sas_titular; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_titular (
    id integer NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
    tipoempleado_id integer NOT NULL,
	nacionalidad enum('Venezolana','Extranjero') NOT NULL DEFAULT 'Venezolana',
	cedula character varying(8) NOT NULL,
    nombre1 character varying(30) NOT NULL,
    nombre2 character varying(30) NOT NULL,
    apellido1 character varying(30) NOT NULL,
    apellido2 character varying(30) NOT NULL,
	nacionalidad enum('Masculino','Femenino') NOT NULL DEFAULT 'Masculino',
    fecha_nacimiento date DEFAULT '1900-01-01'::date,
    pais_id integer NOT NULL,
    pais_estado_id integer NOT NULL,
    ciudad_id integer NOT NULL,
	municipio_id integer NOT NULL,
	parroquia_id integer NOT NULL,
    direccion_habitacion character varying(250) NOT NULL,  
    estado_civil enum('Soltero','Concubinato','Casado','Divorciado','Viudo') NOT NULL DEFAULT 'Soltero',
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

COMMENT ON TABLE sas_titular IS 'Modelo para manipular las diferentes Profesiones';

--
-- Name: COLUMN sas_titular.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_titular.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_titular.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_titular.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


--
-- Name: COLUMN sas_titular.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_titular.nombre IS 'Nombre de la Profesion';


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
