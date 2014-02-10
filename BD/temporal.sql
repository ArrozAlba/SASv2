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
-- Name: sas_solicitud_servicio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_solicitud_servicio (
    id integer DEFAULT nextval('sas_solicitud_servicio_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
	fecha date DEFAULT '1900-01-01'::date,	
    codigo_solicitud character varying(8) NOT NULL,
	titular_id integer NOT NULL,
	beneficiario_id integer NOT NULL,
	beneficiario_tipo_id integer NOT NULL,
	id_patologia integer NOT NULL,
	servicio_id integer NOT NULL,
	proveedor_id integer NOT NULL,
	medico_id integer NOT NULL,
	persona_autorizada character varying(30) NOT NULL,
	persona_cedula character varying(8) NOT NULL,
	tiempo_tratamiento character varying(1) NOT NULL DEFAULT 'T',
	fecha_inicio date DEFAULT '1900-01-01'::date,
	fecha_fin date DEFAULT '1900-01-01'::date,
    diagnostico character varying(250) NOT NULL,
    observacion character varying(250) NOT NULL
);

ALTER TABLE public.sas_solicitud_servicio OWNER TO jelitox;

--
-- Name: TABLE sas_solicitud_servicio; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_solicitud_servicio IS 'Modelo para manipular los beneficiarios';

--
-- Name: COLUMN sas_solicitud_servicio.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_solicitud_servicio.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_solicitud_servicio.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_solicitud_servicio.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


--
-- Name: COLUMN sas_solicitud_servicio.tipoempleado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.titular_id IS 'Empleado Titular';

--
-- Name: COLUMN sas_solicitud_servicio.parentesco; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.parentesco IS 'Parentesco del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.nacionalidad; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.nacionalidad IS 'Nacionalidad del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.cedula; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.cedula IS 'N° Cedula beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.nombre1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.nombre1 IS 'N° Primer Nombre del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.nombre2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.nombre2 IS 'N° Segundo Nombre del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.apellido1; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.apellido1 IS 'N° Primer Apellido del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.apellido2; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.apellido2 IS 'N° Segundo Apellido del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.sexo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.sexo IS 'N° Sexo del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.fecha_nacimiento; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.fecha_nacimiento IS 'Fecha de Nacimiento del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.pais_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.pais_id IS 'Pais Origen del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.pais_estado_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.pais_estado_id IS 'Estado de Origen del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.ciudad_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.ciudad_id IS 'Ciudad de Origen del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.municipio_id IS 'Municipio de Origen del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.parroquia_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.parroquia_id IS 'Parroquia de Origen del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.direccion_habitacion; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.direccion_habitacion IS 'Direccion de Habitacion del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.estado_civil; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.estado_civil IS 'Estado Civil del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.celular; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.celular IS 'N° de Celular del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.telefono; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.telefono IS 'N° de Telefono del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.correo_electronico; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.correo_electronico IS 'Direccion de Correo Electronico del beneficiario';

--
-- Name: COLUMN sas_solicitud_servicio.grupo_sanguineo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.grupo_sanguineo IS 'Grupo Sanguineo del beneficiario';


--
-- Name: COLUMN sas_solicitud_servicio.beneficiario_tipo_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_solicitud_servicio.beneficiario_tipo_id IS 'Tipo de Beneficiario';


--
-- Name: COLUMN sas_solicitud_servicio.observacion; Type: COMMENT; Schema: public; Owner: jelitox
--
COMMENT ON COLUMN sas_solicitud_servicio.observacion IS 'Observacion';

--
-- Name: sas_solicitud_servicio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_solicitud_servicio_id_seq OWNED BY sas_solicitud_servicio.id;
-------------------------------------------------------------------------------------------
--
-- Name: sas_solicitud_servicio_pkey; Type: CONSTRAINT; Schema: public; Owner: jelitox; Tablespace: 
--

ALTER TABLE ONLY sas_solicitud_servicio
    ADD CONSTRAINT sas_solicitud_servicio_pkey PRIMARY KEY (id);

-- ----------------------------
-- Foreign Key structure for table "sas_solicitud_servicio"
-- ----------------------------
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("titular_id") REFERENCES "sas_titular" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("pais_id") REFERENCES "sas_pais" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("pais_estado_id") REFERENCES "sas_pais_estado" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("ciudad_id") REFERENCES "sas_ciudad" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("municipio_id") REFERENCES "sas_municipio" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("parroquia_id") REFERENCES "sas_parroquia" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sas_solicitud_servicio" ADD FOREIGN KEY ("beneficiario_tipo_id") REFERENCES "sas_solicitud_servicio_tipo" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

