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
-- Name: sas_ciudad; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_ciudad (
    id integer DEFAULT nextval('sas_ciudad_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
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
-- Name: COLUMN sas_ciudad.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_ciudad.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_ciudad.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_ciudad.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_ciudad.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


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
-- Name: sas_ciudad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_ciudad_id_seq OWNED BY sas_ciudad.id;

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
-- Name: sas_municipio; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_municipio (
    id integer DEFAULT nextval('sas_municipio_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
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
-- Name: COLUMN sas_municipio.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_municipio.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_municipio.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_municipio.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_municipio.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


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
-- Name: sas_municipio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_municipio_id_seq OWNED BY sas_municipio.id;


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
-- Name: sas_pais; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_pais (
    id integer DEFAULT nextval('sas_pais_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
    codigo character varying(2) NOT NULL,
    nombre character varying(64) NOT NULL
);


ALTER TABLE public.sas_pais OWNER TO jelitox;
--
-- Name: sas_pais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_pais_id_seq OWNED BY sas_pais.id;

--
-- Name: TABLE sas_pais; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_pais IS 'Modelo para manipular los Paises';


--
-- Name: COLUMN sas_pais.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_pais.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_pais.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_pais.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


--
-- Name: COLUMN sas_pais.codigo; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.codigo IS 'Codigo del Pais';


--
-- Name: COLUMN sas_pais.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais.nombre IS 'Nombre Pais';

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
-- Name: sas_pais_estado; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_pais_estado (
    id integer DEFAULT nextval('sas_pais_estado_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
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
-- Name: COLUMN sas_pais_estado.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_pais_estado.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_pais_estado.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_pais_estado.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_pais_estado.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


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
-- Name: sas_pais_estado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_pais_estado_id_seq OWNED BY sas_pais_estado.id;

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
-- Name: sas_parroquia; Type: TABLE; Schema: public; Owner: jelitox; Tablespace: 
--

CREATE TABLE sas_parroquia (
    id integer DEFAULT nextval('sas_parroquia_id_seq'::regclass) NOT NULL,
    creacion_uid integer,
    creacion_fecha timestamp without time zone,
    edicion_fecha timestamp without time zone,
    edicion_uid integer,
    nombre character varying(128) NOT NULL,
    municipio_id integer NOT NULL
);


ALTER TABLE public.sas_parroquia OWNER TO jelitox;

--
-- Name: TABLE sas_parroquia; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON TABLE sas_parroquia IS 'Modelo para  manipular Parroquia';


--
-- Name: COLUMN sas_parroquia.creacion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.creacion_uid IS 'Codigo del Usuario Creador del Registro';


--
-- Name: COLUMN sas_parroquia.creacion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.creacion_fecha IS 'Fecha de Creación del Registro';


--
-- Name: COLUMN sas_parroquia.edicion_fecha; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.edicion_fecha IS 'Fecha de Ultima Edición del Registro';


--
-- Name: COLUMN sas_parroquia.edicion_uid; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.edicion_uid IS 'Codigo del Ultimo Usuario Editor del Registro';


--
-- Name: COLUMN sas_parroquia.nombre; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.nombre IS 'Parroquia';


--
-- Name: COLUMN sas_parroquia.municipio_id; Type: COMMENT; Schema: public; Owner: jelitox
--

COMMENT ON COLUMN sas_parroquia.municipio_id IS 'Municipio';



--
-- Name: sas_parroquia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: jelitox
--

ALTER SEQUENCE sas_parroquia_id_seq OWNED BY sas_parroquia.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_ciudad ALTER COLUMN id SET DEFAULT nextval('sas_ciudad_id_seq'::regclass);


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
-- Data for Name: sas_ciudad; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_ciudad (id, creacion_uid, creacion_fecha, edicion_fecha, edicion_uid, estado_id, codigo, nombre) FROM stdin;
1	1	2014-01-26 04:37:26.924415	\N	\N	53	pay	Puerto Ayacucho
2	1	2014-01-26 04:37:26.924415	\N	\N	54	anc	Anaco
3	1	2014-01-26 04:37:26.924415	\N	\N	54	bar	Barcelona
4	1	2014-01-26 04:37:26.924415	\N	\N	54	can	Cantaura
5	1	2014-01-26 04:37:26.924415	\N	\N	54	tig	El Tigre
6	1	2014-01-26 04:37:26.924415	\N	\N	54	plc	Puerto La Cruz
7	1	2014-01-26 04:37:26.924415	\N	\N	54	sjg	San José de Guanipa
8	1	2014-01-26 04:37:26.924415	\N	\N	55	bir	Biruaca
9	1	2014-01-26 04:37:26.924415	\N	\N	55	gto	Guasdualito
10	1	2014-01-26 04:37:26.924415	\N	\N	55	sfa	San Fernando de Apure
11	1	2014-01-26 04:37:26.924415	\N	\N	56	cag	Cagua
12	1	2014-01-26 04:37:26.924415	\N	\N	56	con	El Consejo
13	1	2014-01-26 04:37:26.924415	\N	\N	56	lim	El Limón
14	1	2014-01-26 04:37:26.924415	\N	\N	56	vic	La Victoria
15	1	2014-01-26 04:37:26.924415	\N	\N	56	tej	Las Tejerías
16	1	2014-01-26 04:37:26.924415	\N	\N	56	mry	Maracay
17	1	2014-01-26 04:37:26.924415	\N	\N	56	png	Palo Negro
18	1	2014-01-26 04:37:26.924415	\N	\N	56	smt	San Mateo
19	1	2014-01-26 04:37:26.924415	\N	\N	56	tmr	Turmero
20	1	2014-01-26 04:37:26.924415	\N	\N	56	vdc	Villa de Cura
21	1	2014-01-26 04:37:26.924415	\N	\N	57	brn	Barinas
22	1	2014-01-26 04:37:26.924415	\N	\N	58	cbv	Ciudad Bolívar
23	1	2014-01-26 04:37:26.924415	\N	\N	58	cgy	Ciudad Guayana
24	1	2014-01-26 04:37:26.924415	\N	\N	58	upt	Upata
25	1	2014-01-26 04:37:26.924415	\N	\N	59	gcr	Guacara
26	1	2014-01-26 04:37:26.924415	\N	\N	59	gui	Güigüe
27	1	2014-01-26 04:37:26.924415	\N	\N	59	mra	Mariara
28	1	2014-01-26 04:37:26.924415	\N	\N	59	mrn	Morón
29	1	2014-01-26 04:37:26.924415	\N	\N	59	pca	Puerto Cabello
30	1	2014-01-26 04:37:26.924415	\N	\N	59	snj	San Joaquín
31	1	2014-01-26 04:37:26.924415	\N	\N	59	tcr	Tacarigua
32	1	2014-01-26 04:37:26.924415	\N	\N	59	val	Valencia
33	1	2014-01-26 04:37:26.924415	\N	\N	60	sca	San Carlos
34	1	2014-01-26 04:37:26.924415	\N	\N	60	tnq	Tinaquillo
35	1	2014-01-26 04:37:26.924415	\N	\N	61	tcp	Tucupita
36	1	2014-01-26 04:37:26.924415	\N	\N	52	ccs	Caracas
37	1	2014-01-26 04:37:26.924415	\N	\N	62	cor	Coro
38	1	2014-01-26 04:37:26.924415	\N	\N	62	ptc	Punta Cardón
39	1	2014-01-26 04:37:26.924415	\N	\N	62	ptf	Punto Fijo
40	1	2014-01-26 04:37:26.924415	\N	\N	63	ado	Altagracia de Orituco
41	1	2014-01-26 04:37:26.924415	\N	\N	63	clb	Calabozo
42	1	2014-01-26 04:37:26.924415	\N	\N	63	sjm	San Juan de los Morros
43	1	2014-01-26 04:37:26.924415	\N	\N	63	vdp	Valle de la Pascua
44	1	2014-01-26 04:37:26.924415	\N	\N	63	zrz	Zaraza
45	1	2014-01-26 04:37:26.924415	\N	\N	64	bto	Barquisimeto
46	1	2014-01-26 04:37:26.924415	\N	\N	64	cbd	Cabudare
47	1	2014-01-26 04:37:26.924415	\N	\N	64	crr	Carora
48	1	2014-01-26 04:37:26.924415	\N	\N	64	tcy	El Tocuyo
49	1	2014-01-26 04:37:26.924415	\N	\N	64	qbo	Quibor
50	1	2014-01-26 04:37:26.924415	\N	\N	65	bta	Baruta
51	1	2014-01-26 04:37:26.924415	\N	\N	65	ejd	Ejido
52	1	2014-01-26 04:37:26.924415	\N	\N	65	vgi	El Vigía
53	1	2014-01-26 04:37:26.924415	\N	\N	65	mer	Mérida
54	1	2014-01-26 04:37:26.924415	\N	\N	66	crz	Carrizal
55	1	2014-01-26 04:37:26.924415	\N	\N	66	ccg	Caucagüita
56	1	2014-01-26 04:37:26.924415	\N	\N	66	cco	Chacao
57	1	2014-01-26 04:37:26.924415	\N	\N	66	cha	Charallave
58	1	2014-01-26 04:37:26.924415	\N	\N	66	cua	Cúa
59	1	2014-01-26 04:37:26.924415	\N	\N	66	cft	El Cafetal 
60	1	2014-01-26 04:37:26.924415	\N	\N	66	htl	El Hatillo
61	1	2014-01-26 04:37:26.924415	\N	\N	66	grn	Guarenas
62	1	2014-01-26 04:37:26.924415	\N	\N	66	gtr	Guatire
63	1	2014-01-26 04:37:26.924415	\N	\N	66	dlr	La Dolorita
64	1	2014-01-26 04:37:26.924415	\N	\N	66	dcm	Los Dos Caminos
65	1	2014-01-26 04:37:26.924415	\N	\N	66	tqs	Los Teques
66	1	2014-01-26 04:37:26.924415	\N	\N	66	odt	Ocumare del Tuy
67	1	2014-01-26 04:37:26.924415	\N	\N	66	ptr	Petare
68	1	2014-01-26 04:37:26.924415	\N	\N	66	saa	San Antonio de los Altos
69	1	2014-01-26 04:37:26.924415	\N	\N	66	stl	Santa Lucía
70	1	2014-01-26 04:37:26.924415	\N	\N	66	stt	Santa Teresa del Tuy
71	1	2014-01-26 04:37:26.924415	\N	\N	67	crp	Caripito
72	1	2014-01-26 04:37:26.924415	\N	\N	67	mtr	Maturín
73	1	2014-01-26 04:37:26.924415	\N	\N	69	acr	Acarigua
74	1	2014-01-26 04:37:26.924415	\N	\N	69	aru	Araure
75	1	2014-01-26 04:37:26.924415	\N	\N	69	gnr	Guanare
76	1	2014-01-26 04:37:26.924415	\N	\N	69	vbz	Villa Bruzual
77	1	2014-01-26 04:37:26.924415	\N	\N	70	cru	Carúpano
78	1	2014-01-26 04:37:26.924415	\N	\N	70	cum	Cumaná
79	1	2014-01-26 04:37:26.924415	\N	\N	71	pal	Palmira
80	1	2014-01-26 04:37:26.924415	\N	\N	71	rub	Rubio
81	1	2014-01-26 04:37:26.924415	\N	\N	71	sat	San Antonio del Táchira
82	1	2014-01-26 04:37:26.924415	\N	\N	71	sct	San Cristóbal
83	1	2014-01-26 04:37:26.924415	\N	\N	71	sjo	San Josecito
84	1	2014-01-26 04:37:26.924415	\N	\N	71	sjc	San Juan de Colón
85	1	2014-01-26 04:37:26.924415	\N	\N	71	trb	Táriba
86	1	2014-01-26 04:37:26.924415	\N	\N	72	tru	Trujillo
87	1	2014-01-26 04:37:26.924415	\N	\N	72	vlr	Valera
88	1	2014-01-26 04:37:26.924415	\N	\N	73	crb	Caraballeda
89	1	2014-01-26 04:37:26.924415	\N	\N	73	clm	Catia La Mar
90	1	2014-01-26 04:37:26.924415	\N	\N	73	asn	La Asunción
91	1	2014-01-26 04:37:26.924415	\N	\N	73	gua	La Guaira
92	1	2014-01-26 04:37:26.924415	\N	\N	73	mai	Maiquetiá
93	1	2014-01-26 04:37:26.924415	\N	\N	73	por	Porlamar
94	1	2014-01-26 04:37:26.924415	\N	\N	73	vro	Villa Rosa
95	1	2014-01-26 04:37:26.924415	\N	\N	74	chi	Chivacoa
96	1	2014-01-26 04:37:26.924415	\N	\N	74	crt	Cocorote
97	1	2014-01-26 04:37:26.924415	\N	\N	74	idp	Independencia
98	1	2014-01-26 04:37:26.924415	\N	\N	74	sfe	San Felipe
99	1	2014-01-26 04:37:26.924415	\N	\N	74	yrt	Yaritagua
100	1	2014-01-26 04:37:26.924415	\N	\N	75	bcq	Bachaquero
101	1	2014-01-26 04:37:26.924415	\N	\N	75	cbm	Cabimas
102	1	2014-01-26 04:37:26.924415	\N	\N	75	coj	Ciudad Ojeda
103	1	2014-01-26 04:37:26.924415	\N	\N	75	lag	Lagunillas
104	1	2014-01-26 04:37:26.924415	\N	\N	75	mcq	Machiques
105	1	2014-01-26 04:37:26.924415	\N	\N	75	mbo	Maracaibo
106	1	2014-01-26 04:37:26.924415	\N	\N	75	scz	San Carlos del Zulia
107	1	2014-01-26 04:37:26.924415	\N	\N	75	srt	Santa Rita
108	1	2014-01-26 04:37:26.924415	\N	\N	75	vrs	Villa del Rosario
\.


--
-- Name: sas_ciudad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_ciudad_id_seq', 108, true);


--
-- Data for Name: sas_municipio; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_municipio (id, creacion_uid, creacion_fecha, edicion_fecha, edicion_uid, estado_id, codigo, nombre) FROM stdin;
1	1	2014-01-26 04:37:26.924415	\N	\N	52	001	Libertador
2	1	2014-01-26 04:37:26.924415	\N	\N	53	002	Alto Orinoco
3	1	2014-01-26 04:37:26.924415	\N	\N	53	003	Atabapo
4	1	2014-01-26 04:37:26.924415	\N	\N	53	004	Atures
5	1	2014-01-26 04:37:26.924415	\N	\N	53	005	Autana
6	1	2014-01-26 04:37:26.924415	\N	\N	53	006	Manapiare
7	1	2014-01-26 04:37:26.924415	\N	\N	53	007	Maroa
8	1	2014-01-26 04:37:26.924415	\N	\N	53	008	Rio Negro
9	1	2014-01-26 04:37:26.924415	\N	\N	54	009	Anaco
10	1	2014-01-26 04:37:26.924415	\N	\N	54	010	Aragua
11	1	2014-01-26 04:37:26.924415	\N	\N	54	011	Bolivar
12	1	2014-01-26 04:37:26.924415	\N	\N	54	012	Bruzual
13	1	2014-01-26 04:37:26.924415	\N	\N	54	013	Cajigal
14	1	2014-01-26 04:37:26.924415	\N	\N	54	014	Carvajal
15	1	2014-01-26 04:37:26.924415	\N	\N	54	015	Diego Bautista Urbaneja
16	1	2014-01-26 04:37:26.924415	\N	\N	54	016	Freites
17	1	2014-01-26 04:37:26.924415	\N	\N	54	017	Guanipa
18	1	2014-01-26 04:37:26.924415	\N	\N	54	018	Guanta
19	1	2014-01-26 04:37:26.924415	\N	\N	54	019	Independencia
20	1	2014-01-26 04:37:26.924415	\N	\N	54	020	Libertad
21	1	2014-01-26 04:37:26.924415	\N	\N	54	021	McGregor
22	1	2014-01-26 04:37:26.924415	\N	\N	54	022	Miranda
23	1	2014-01-26 04:37:26.924415	\N	\N	54	023	Monagas
24	1	2014-01-26 04:37:26.924415	\N	\N	54	024	Penalver
25	1	2014-01-26 04:37:26.924415	\N	\N	54	025	Piritu
26	1	2014-01-26 04:37:26.924415	\N	\N	54	026	San Juan de Capistrano
27	1	2014-01-26 04:37:26.924415	\N	\N	54	027	Santa Ana
28	1	2014-01-26 04:37:26.924415	\N	\N	54	028	Simon Rodriguez
29	1	2014-01-26 04:37:26.924415	\N	\N	54	029	Sotillo
30	1	2014-01-26 04:37:26.924415	\N	\N	55	031	Achaguas
31	1	2014-01-26 04:37:26.924415	\N	\N	55	032	Biruaca
32	1	2014-01-26 04:37:26.924415	\N	\N	55	033	Munoz
33	1	2014-01-26 04:37:26.924415	\N	\N	55	034	Paez
34	1	2014-01-26 04:37:26.924415	\N	\N	55	035	Pedro Camejo
35	1	2014-01-26 04:37:26.924415	\N	\N	55	036	Romulo Gallegos
36	1	2014-01-26 04:37:26.924415	\N	\N	55	037	San Fernando
37	1	2014-01-26 04:37:26.924415	\N	\N	56	038	Bolivar
38	1	2014-01-26 04:37:26.924415	\N	\N	56	039	Camatagua
39	1	2014-01-26 04:37:26.924415	\N	\N	56	040	Francisco Linares Alcantara
40	1	2014-01-26 04:37:26.924415	\N	\N	56	041	Girardot
41	1	2014-01-26 04:37:26.924415	\N	\N	56	042	Jose Angel Lamas
42	1	2014-01-26 04:37:26.924415	\N	\N	56	043	Jose Felix Ribas
43	1	2014-01-26 04:37:26.924415	\N	\N	56	044	José Rafael Revenga
44	1	2014-01-26 04:37:26.924415	\N	\N	56	046	Libertador
45	1	2014-01-26 04:37:26.924415	\N	\N	56	047	Mario Briceno Iragorry
46	1	2014-01-26 04:37:26.924415	\N	\N	56	048	Ocumare de la Costa de Oro
47	1	2014-01-26 04:37:26.924415	\N	\N	56	049	San Casimiro
48	1	2014-01-26 04:37:26.924415	\N	\N	56	050	San Sebastian
49	1	2014-01-26 04:37:26.924415	\N	\N	56	051	Santiago Marino
50	1	2014-01-26 04:37:26.924415	\N	\N	56	052	Santos Michelena
51	1	2014-01-26 04:37:26.924415	\N	\N	56	053	Sucre
52	1	2014-01-26 04:37:26.924415	\N	\N	56	054	Tovar
53	1	2014-01-26 04:37:26.924415	\N	\N	56	055	Urdaneta
54	1	2014-01-26 04:37:26.924415	\N	\N	56	056	Zamora
55	1	2014-01-26 04:37:26.924415	\N	\N	57	057	Alberto Arvelo Torrealba
56	1	2014-01-26 04:37:26.924415	\N	\N	57	058	Andres Eloy Blanco
57	1	2014-01-26 04:37:26.924415	\N	\N	57	059	Antonio Jose de Sucre
58	1	2014-01-26 04:37:26.924415	\N	\N	57	060	Arismendi
59	1	2014-01-26 04:37:26.924415	\N	\N	57	061	Barinas
60	1	2014-01-26 04:37:26.924415	\N	\N	57	062	Bolivar
61	1	2014-01-26 04:37:26.924415	\N	\N	57	063	Cruz Paredes
62	1	2014-01-26 04:37:26.924415	\N	\N	57	064	Ezequiel Zamora
63	1	2014-01-26 04:37:26.924415	\N	\N	57	065	Obispos
64	1	2014-01-26 04:37:26.924415	\N	\N	57	066	Pedraza
65	1	2014-01-26 04:37:26.924415	\N	\N	57	067	Rojas
66	1	2014-01-26 04:37:26.924415	\N	\N	57	068	Sosa
67	1	2014-01-26 04:37:26.924415	\N	\N	58	069	Caroni
68	1	2014-01-26 04:37:26.924415	\N	\N	58	070	Cedeno
69	1	2014-01-26 04:37:26.924415	\N	\N	58	071	El Callao
70	1	2014-01-26 04:37:26.924415	\N	\N	58	072	Gran Sabana
71	1	2014-01-26 04:37:26.924415	\N	\N	58	073	Heres
72	1	2014-01-26 04:37:26.924415	\N	\N	58	074	Piar
73	1	2014-01-26 04:37:26.924415	\N	\N	58	075	Raul Leoni
74	1	2014-01-26 04:37:26.924415	\N	\N	58	076	Roscio
75	1	2014-01-26 04:37:26.924415	\N	\N	58	077	Sifontes
76	1	2014-01-26 04:37:26.924415	\N	\N	58	078	Sucre
77	1	2014-01-26 04:37:26.924415	\N	\N	58	079	Padre Pedro Chien
78	1	2014-01-26 04:37:26.924415	\N	\N	59	080	Bejuma
79	1	2014-01-26 04:37:26.924415	\N	\N	59	081	Carlos Arvelo
80	1	2014-01-26 04:37:26.924415	\N	\N	59	082	Guacara
81	1	2014-01-26 04:37:26.924415	\N	\N	59	083	Diego Ibarra
82	1	2014-01-26 04:37:26.924415	\N	\N	59	084	Juan Jose Mora
83	1	2014-01-26 04:37:26.924415	\N	\N	59	085	Libertador
84	1	2014-01-26 04:37:26.924415	\N	\N	59	086	Los Guayos
85	1	2014-01-26 04:37:26.924415	\N	\N	59	087	Naguanagua
86	1	2014-01-26 04:37:26.924415	\N	\N	59	088	Miranda
87	1	2014-01-26 04:37:26.924415	\N	\N	59	089	Montalban
88	1	2014-01-26 04:37:26.924415	\N	\N	59	090	Puerto Cabello
89	1	2014-01-26 04:37:26.924415	\N	\N	59	091	San Diego
90	1	2014-01-26 04:37:26.924415	\N	\N	59	092	San Joaquín
91	1	2014-01-26 04:37:26.924415	\N	\N	59	093	Valencia
92	1	2014-01-26 04:37:26.924415	\N	\N	60	094	Anzoategui
93	1	2014-01-26 04:37:26.924415	\N	\N	60	095	Falcon
94	1	2014-01-26 04:37:26.924415	\N	\N	60	095	Girardot
95	1	2014-01-26 04:37:26.924415	\N	\N	60	096	Lima Blanco
96	1	2014-01-26 04:37:26.924415	\N	\N	60	097	Pao de San Juan Bautista
97	1	2014-01-26 04:37:26.924415	\N	\N	60	098	Ricaurte
98	1	2014-01-26 04:37:26.924415	\N	\N	60	099	Romulo Gallegos
99	1	2014-01-26 04:37:26.924415	\N	\N	60	100	San Carlos
100	1	2014-01-26 04:37:26.924415	\N	\N	60	101	Tinaco
101	1	2014-01-26 04:37:26.924415	\N	\N	61	102	Antonio Diaz
102	1	2014-01-26 04:37:26.924415	\N	\N	61	103	Casacoima
103	1	2014-01-26 04:37:26.924415	\N	\N	61	104	Pedernales
104	1	2014-01-26 04:37:26.924415	\N	\N	61	105	Tucupita
105	1	2014-01-26 04:37:26.924415	\N	\N	62	106	Acosta
106	1	2014-01-26 04:37:26.924415	\N	\N	62	107	Bolivar
107	1	2014-01-26 04:37:26.924415	\N	\N	62	108	Buchivacoa
108	1	2014-01-26 04:37:26.924415	\N	\N	62	109	Cacique Manaure
109	1	2014-01-26 04:37:26.924415	\N	\N	62	110	Carirubana
110	1	2014-01-26 04:37:26.924415	\N	\N	62	111	Colina
111	1	2014-01-26 04:37:26.924415	\N	\N	62	112	Dabajuro
112	1	2014-01-26 04:37:26.924415	\N	\N	62	113	Democracia
113	1	2014-01-26 04:37:26.924415	\N	\N	62	114	Falcon
114	1	2014-01-26 04:37:26.924415	\N	\N	62	115	Federacion
115	1	2014-01-26 04:37:26.924415	\N	\N	62	116	Jacura
116	1	2014-01-26 04:37:26.924415	\N	\N	62	117	Los Taques
117	1	2014-01-26 04:37:26.924415	\N	\N	62	118	Mauroa
118	1	2014-01-26 04:37:26.924415	\N	\N	62	119	Miranda
119	1	2014-01-26 04:37:26.924415	\N	\N	62	120	Monsenor Iturriza
120	1	2014-01-26 04:37:26.924415	\N	\N	62	121	Palmasola
121	1	2014-01-26 04:37:26.924415	\N	\N	62	122	Petit
122	1	2014-01-26 04:37:26.924415	\N	\N	62	123	Piritu
123	1	2014-01-26 04:37:26.924415	\N	\N	62	124	San Francisco
124	1	2014-01-26 04:37:26.924415	\N	\N	62	125	Silva
125	1	2014-01-26 04:37:26.924415	\N	\N	62	126	Sucre
126	1	2014-01-26 04:37:26.924415	\N	\N	62	127	Tocopero
127	1	2014-01-26 04:37:26.924415	\N	\N	62	128	Union
128	1	2014-01-26 04:37:26.924415	\N	\N	62	129	Urumaco
129	1	2014-01-26 04:37:26.924415	\N	\N	62	130	Zamora
130	1	2014-01-26 04:37:26.924415	\N	\N	63	131	Camaguan
131	1	2014-01-26 04:37:26.924415	\N	\N	63	132	Chaguaramas
132	1	2014-01-26 04:37:26.924415	\N	\N	63	133	El Socorro
133	1	2014-01-26 04:37:26.924415	\N	\N	63	134	Sebastian Francisco de Miranda
134	1	2014-01-26 04:37:26.924415	\N	\N	63	135	Jose Felix Ribas
135	1	2014-01-26 04:37:26.924415	\N	\N	63	136	Jose Tadeo Monagas
136	1	2014-01-26 04:37:26.924415	\N	\N	63	137	Juan German Roscio
137	1	2014-01-26 04:37:26.924415	\N	\N	63	138	Julian Mellado
138	1	2014-01-26 04:37:26.924415	\N	\N	63	139	Las Mercedes
139	1	2014-01-26 04:37:26.924415	\N	\N	63	140	Leonardo Infante
140	1	2014-01-26 04:37:26.924415	\N	\N	63	141	Pedro Zaraza
141	1	2014-01-26 04:37:26.924415	\N	\N	63	142	Ortiz
142	1	2014-01-26 04:37:26.924415	\N	\N	63	143	San Geronimo de Guayabal
143	1	2014-01-26 04:37:26.924415	\N	\N	63	144	San Jose de Guaribe
144	1	2014-01-26 04:37:26.924415	\N	\N	63	145	Santa Maria de Ipire
145	1	2014-01-26 04:37:26.924415	\N	\N	64	146	Andres Eloy Blanco
146	1	2014-01-26 04:37:26.924415	\N	\N	64	147	Crespo
147	1	2014-01-26 04:37:26.924415	\N	\N	64	148	Iribarren
148	1	2014-01-26 04:37:26.924415	\N	\N	64	149	Jimenez
149	1	2014-01-26 04:37:26.924415	\N	\N	64	150	Moran 
150	1	2014-01-26 04:37:26.924415	\N	\N	64	151	Palavecino
151	1	2014-01-26 04:37:26.924415	\N	\N	64	152	Simon Planas
152	1	2014-01-26 04:37:26.924415	\N	\N	64	153	Torres
153	1	2014-01-26 04:37:26.924415	\N	\N	64	154	Urdaneta
154	1	2014-01-26 04:37:26.924415	\N	\N	65	155	Alberto Adriani
155	1	2014-01-26 04:37:26.924415	\N	\N	65	156	Andres Bello
156	1	2014-01-26 04:37:26.924415	\N	\N	65	157	Antonio Pinto Salinas
157	1	2014-01-26 04:37:26.924415	\N	\N	65	158	Aricagua
158	1	2014-01-26 04:37:26.924415	\N	\N	65	159	Arzobispo Chacon
159	1	2014-01-26 04:37:26.924415	\N	\N	65	160	Campo Elias
160	1	2014-01-26 04:37:26.924415	\N	\N	65	161	Caracciolo Parra Olmedo
161	1	2014-01-26 04:37:26.924415	\N	\N	65	162	Cardenal Quintero
162	1	2014-01-26 04:37:26.924415	\N	\N	65	163	Guaraque
163	1	2014-01-26 04:37:26.924415	\N	\N	65	164	Julio Cesar Salas
164	1	2014-01-26 04:37:26.924415	\N	\N	65	165	Justo Briceno
165	1	2014-01-26 04:37:26.924415	\N	\N	65	166	Libertador
166	1	2014-01-26 04:37:26.924415	\N	\N	65	167	Miranda
167	1	2014-01-26 04:37:26.924415	\N	\N	65	168	Obispo Ramos de Lora
168	1	2014-01-26 04:37:26.924415	\N	\N	65	169	Padre Noguera
169	1	2014-01-26 04:37:26.924415	\N	\N	65	170	Pueblo Llano
170	1	2014-01-26 04:37:26.924415	\N	\N	65	171	Rangel
171	1	2014-01-26 04:37:26.924415	\N	\N	65	172	Rivas Davila
172	1	2014-01-26 04:37:26.924415	\N	\N	65	173	Santos Marquina
173	1	2014-01-26 04:37:26.924415	\N	\N	65	174	Sucre
174	1	2014-01-26 04:37:26.924415	\N	\N	65	175	Tovar
175	1	2014-01-26 04:37:26.924415	\N	\N	65	176	Tulio Febres Cordero
176	1	2014-01-26 04:37:26.924415	\N	\N	65	177	Zea
177	1	2014-01-26 04:37:26.924415	\N	\N	66	178	Acevedo
178	1	2014-01-26 04:37:26.924415	\N	\N	66	179	Andres Bello
179	1	2014-01-26 04:37:26.924415	\N	\N	66	180	Baruta
180	1	2014-01-26 04:37:26.924415	\N	\N	66	181	Brion
181	1	2014-01-26 04:37:26.924415	\N	\N	66	182	Buroz
182	1	2014-01-26 04:37:26.924415	\N	\N	66	183	Carrizal
183	1	2014-01-26 04:37:26.924415	\N	\N	66	184	Chacao
184	1	2014-01-26 04:37:26.924415	\N	\N	66	185	Cristobal Rojas
185	1	2014-01-26 04:37:26.924415	\N	\N	66	186	El Hatillo
186	1	2014-01-26 04:37:26.924415	\N	\N	66	187	Guaicaipuro
187	1	2014-01-26 04:37:26.924415	\N	\N	66	188	Independencia
188	1	2014-01-26 04:37:26.924415	\N	\N	66	189	Lander
189	1	2014-01-26 04:37:26.924415	\N	\N	66	190	Los Salias
190	1	2014-01-26 04:37:26.924415	\N	\N	66	191	Paez
191	1	2014-01-26 04:37:26.924415	\N	\N	66	192	Paz Castillo
192	1	2014-01-26 04:37:26.924415	\N	\N	66	193	Pedro Gual
193	1	2014-01-26 04:37:26.924415	\N	\N	66	194	Plaza
194	1	2014-01-26 04:37:26.924415	\N	\N	66	195	Simon Bolívar
195	1	2014-01-26 04:37:26.924415	\N	\N	66	196	Sucre
196	1	2014-01-26 04:37:26.924415	\N	\N	66	197	Urdaneta
197	1	2014-01-26 04:37:26.924415	\N	\N	66	198	Zamora
198	1	2014-01-26 04:37:26.924415	\N	\N	67	201	Acosta
199	1	2014-01-26 04:37:26.924415	\N	\N	67	202	Aguasay
200	1	2014-01-26 04:37:26.924415	\N	\N	67	203	Bolivar
201	1	2014-01-26 04:37:26.924415	\N	\N	67	204	Caripe
202	1	2014-01-26 04:37:26.924415	\N	\N	67	205	Cedeno
203	1	2014-01-26 04:37:26.924415	\N	\N	67	206	Ezequiel Zamora
204	1	2014-01-26 04:37:26.924415	\N	\N	67	207	Libertador
205	1	2014-01-26 04:37:26.924415	\N	\N	67	208	Maturin
206	1	2014-01-26 04:37:26.924415	\N	\N	67	209	Piar
207	1	2014-01-26 04:37:26.924415	\N	\N	67	210	Punceres
208	1	2014-01-26 04:37:26.924415	\N	\N	67	211	Santa Barbara
209	1	2014-01-26 04:37:26.924415	\N	\N	67	212	Sotillo
210	1	2014-01-26 04:37:26.924415	\N	\N	67	213	Uracoa
211	1	2014-01-26 04:37:26.924415	\N	\N	68	214	Antolin del Campo
212	1	2014-01-26 04:37:26.924415	\N	\N	68	215	Arismendi
213	1	2014-01-26 04:37:26.924415	\N	\N	68	216	Diaz
214	1	2014-01-26 04:37:26.924415	\N	\N	68	217	Garcia
215	1	2014-01-26 04:37:26.924415	\N	\N	68	218	Gomez
216	1	2014-01-26 04:37:26.924415	\N	\N	68	219	Maneiro
217	1	2014-01-26 04:37:26.924415	\N	\N	68	220	Marcano
218	1	2014-01-26 04:37:26.924415	\N	\N	68	221	Marino
219	1	2014-01-26 04:37:26.924415	\N	\N	68	222	Peninsula de Macanao
220	1	2014-01-26 04:37:26.924415	\N	\N	68	223	Tubores
221	1	2014-01-26 04:37:26.924415	\N	\N	68	224	Villalba
222	1	2014-01-26 04:37:26.924415	\N	\N	69	225	Agua Blanca
223	1	2014-01-26 04:37:26.924415	\N	\N	69	226	Araure
224	1	2014-01-26 04:37:26.924415	\N	\N	69	227	Esteller
225	1	2014-01-26 04:37:26.924415	\N	\N	69	228	Guanare
226	1	2014-01-26 04:37:26.924415	\N	\N	69	229	Guanarito
227	1	2014-01-26 04:37:26.924415	\N	\N	69	230	Monsenor Jose Vicente de Unda
228	1	2014-01-26 04:37:26.924415	\N	\N	69	231	Ospino
229	1	2014-01-26 04:37:26.924415	\N	\N	69	232	Paez
230	1	2014-01-26 04:37:26.924415	\N	\N	69	233	Papelon
231	1	2014-01-26 04:37:26.924415	\N	\N	69	234	San Genaro de Boconoito
232	1	2014-01-26 04:37:26.924415	\N	\N	69	235	San Rafael de Onoto
233	1	2014-01-26 04:37:26.924415	\N	\N	69	236	Santa Rosalia
234	1	2014-01-26 04:37:26.924415	\N	\N	69	237	Sucre
235	1	2014-01-26 04:37:26.924415	\N	\N	69	238	Turen
236	1	2014-01-26 04:37:26.924415	\N	\N	70	239	Andres Eloy Blanco
237	1	2014-01-26 04:37:26.924415	\N	\N	70	240	Andres Mata
238	1	2014-01-26 04:37:26.924415	\N	\N	70	241	Arismendi 
239	1	2014-01-26 04:37:26.924415	\N	\N	70	242	Benitez
240	1	2014-01-26 04:37:26.924415	\N	\N	70	243	Bermudez
241	1	2014-01-26 04:37:26.924415	2014-01-26 04:37:26.924415	1	70	244	Cajigal
242	1	2014-01-26 04:37:26.924415	\N	\N	70	245	Cruz Salmeron Acosta
243	1	2014-01-26 04:37:26.924415	\N	\N	70	246	Libertador
244	1	2014-01-26 04:37:26.924415	\N	\N	70	247	Marino
245	1	2014-01-26 04:37:26.924415	\N	\N	70	248	Mejia
246	1	2014-01-26 04:37:26.924415	\N	\N	70	249	Montes
247	1	2014-01-26 04:37:26.924415	\N	\N	70	250	Ribero
248	1	2014-01-26 04:37:26.924415	\N	\N	70	251	Sucre
249	1	2014-01-26 04:37:26.924415	\N	\N	70	252	Valdez
250	1	2014-01-26 04:37:26.924415	\N	\N	71	254	Andres Bello
251	1	2014-01-26 04:37:26.924415	\N	\N	71	255	Antonio Romulo Costa
252	1	2014-01-26 04:37:26.924415	\N	\N	71	256	Ayacucho
253	1	2014-01-26 04:37:26.924415	\N	\N	71	257	Bolivar
254	1	2014-01-26 04:37:26.924415	\N	\N	71	258	Cardenas
255	1	2014-01-26 04:37:26.924415	\N	\N	71	259	Cordoba
256	1	2014-01-26 04:37:26.924415	\N	\N	71	260	Fernandez Feo
257	1	2014-01-26 04:37:26.924415	\N	\N	71	261	Francisco de Miranda
258	1	2014-01-26 04:37:26.924415	\N	\N	71	262	Garcia de Hevia
259	1	2014-01-26 04:37:26.924415	\N	\N	71	263	Guasimos
260	1	2014-01-26 04:37:26.924415	\N	\N	71	264	Jose Maria Vargas
261	1	2014-01-26 04:37:26.924415	\N	\N	71	265	Independencia
262	1	2014-01-26 04:37:26.924415	\N	\N	71	266	Jauregui
263	1	2014-01-26 04:37:26.924415	\N	\N	71	267	Junin
264	1	2014-01-26 04:37:26.924415	\N	\N	71	268	Libertad
265	1	2014-01-26 04:37:26.924415	\N	\N	71	269	Libertador
266	1	2014-01-26 04:37:26.924415	\N	\N	71	270	Lobatera
267	1	2014-01-26 04:37:26.924415	\N	\N	71	271	Michelena
268	1	2014-01-26 04:37:26.924415	\N	\N	71	272	Pedro Maria Urena
269	1	2014-01-26 04:37:26.924415	\N	\N	71	273	Rafael Urdaneta
270	1	2014-01-26 04:37:26.924415	\N	\N	71	274	Samuel Dario Maldonado
271	1	2014-01-26 04:37:26.924415	\N	\N	71	275	San Cristobal 
272	1	2014-01-26 04:37:26.924415	\N	\N	71	276	Seboruco
273	1	2014-01-26 04:37:26.924415	\N	\N	71	277	Simon Rodriguez
274	1	2014-01-26 04:37:26.924415	\N	\N	71	278	Sucre
275	1	2014-01-26 04:37:26.924415	\N	\N	71	279	Torbes
276	1	2014-01-26 04:37:26.924415	\N	\N	71	280	Uribante
277	1	2014-01-26 04:37:26.924415	\N	\N	71	281	San Judas Tadeo
278	1	2014-01-26 04:37:26.924415	\N	\N	71	282	Panamericano
279	1	2014-01-26 04:37:26.924415	\N	\N	72	301	Andres Bello
280	1	2014-01-26 04:37:26.924415	\N	\N	72	302	Bocono
281	1	2014-01-26 04:37:26.924415	\N	\N	72	303	Bolivar
282	1	2014-01-26 04:37:26.924415	\N	\N	72	304	Candelaria
283	1	2014-01-26 04:37:26.924415	\N	\N	72	305	Carache
284	1	2014-01-26 04:37:26.924415	\N	\N	72	306	Escuque
285	1	2014-01-26 04:37:26.924415	\N	\N	72	307	Jose Felipe Marquez Canizalez
286	1	2014-01-26 04:37:26.924415	\N	\N	72	308	Juan Vicente Campos Elias
287	1	2014-01-26 04:37:26.924415	\N	\N	72	309	La Ceiba
288	1	2014-01-26 04:37:26.924415	\N	\N	72	310	Miranda
289	1	2014-01-26 04:37:26.924415	\N	\N	72	311	Monte Carmelo
290	1	2014-01-26 04:37:26.924415	\N	\N	72	312	Motatan
291	1	2014-01-26 04:37:26.924415	\N	\N	72	313	Pampan
292	1	2014-01-26 04:37:26.924415	\N	\N	72	314	Pampanito
293	1	2014-01-26 04:37:26.924415	\N	\N	72	315	Rafael Rangel
294	1	2014-01-26 04:37:26.924415	\N	\N	72	316	San Rafael de Carvajal
295	1	2014-01-26 04:37:26.924415	\N	\N	72	317	Sucre
296	1	2014-01-26 04:37:26.924415	\N	\N	72	318	Trujillo
297	1	2014-01-26 04:37:26.924415	\N	\N	72	319	Urdaneta
298	1	2014-01-26 04:37:26.924415	\N	\N	72	320	Valera
299	1	2014-01-26 04:37:26.924415	\N	\N	73	200	Vargas
300	1	2014-01-26 04:37:26.924415	\N	\N	74	401	Aristides Bastidas
301	1	2014-01-26 04:37:26.924415	\N	\N	74	402	Bolivar
302	1	2014-01-26 04:37:26.924415	\N	\N	74	403	Bruzual
303	1	2014-01-26 04:37:26.924415	\N	\N	74	404	Cocorote
304	1	2014-01-26 04:37:26.924415	\N	\N	74	405	Independencia
305	1	2014-01-26 04:37:26.924415	\N	\N	74	406	Jose Antonio Paez
306	1	2014-01-26 04:37:26.924415	\N	\N	74	407	La Trinidad
307	1	2014-01-26 04:37:26.924415	\N	\N	74	408	Manuel Monge
308	1	2014-01-26 04:37:26.924415	\N	\N	74	409	Nirgua
309	1	2014-01-26 04:37:26.924415	\N	\N	74	410	Pena
310	1	2014-01-26 04:37:26.924415	\N	\N	74	411	San Felipe
311	1	2014-01-26 04:37:26.924415	\N	\N	74	412	Sucre
312	1	2014-01-26 04:37:26.924415	\N	\N	74	413	Urachiche
313	1	2014-01-26 04:37:26.924415	\N	\N	74	414	Veroes
314	1	2014-01-26 04:37:26.924415	\N	\N	75	501	Almirante Padilla
315	1	2014-01-26 04:37:26.924415	\N	\N	75	502	Baralt
316	1	2014-01-26 04:37:26.924415	\N	\N	75	503	Cabimas
317	1	2014-01-26 04:37:26.924415	\N	\N	75	522	Catatumbo
318	1	2014-01-26 04:37:26.924415	\N	\N	75	504	Colon
319	1	2014-01-26 04:37:26.924415	\N	\N	75	505	Francisco Javier Pulgar
320	1	2014-01-26 04:37:26.924415	\N	\N	75	506	Jesús Enrique Losada
321	1	2014-01-26 04:37:26.924415	\N	\N	75	507	Jesus Maria Semprun
322	1	2014-01-26 04:37:26.924415	\N	\N	75	508	La Cañada de Urdaneta
323	1	2014-01-26 04:37:26.924415	\N	\N	75	509	Lagunillas
324	1	2014-01-26 04:37:26.924415	\N	\N	75	510	Machiques de Perija
325	1	2014-01-26 04:37:26.924415	\N	\N	75	511	Mara
326	1	2014-01-26 04:37:26.924415	\N	\N	75	512	Maracaibo
327	1	2014-01-26 04:37:26.924415	\N	\N	75	513	Miranda
328	1	2014-01-26 04:37:26.924415	\N	\N	75	514	Páez
329	1	2014-01-26 04:37:26.924415	\N	\N	75	515	Rosario de Perija
330	1	2014-01-26 04:37:26.924415	\N	\N	75	517	San Francisco
331	1	2014-01-26 04:37:26.924415	\N	\N	75	518	Santa Rita
332	1	2014-01-26 04:37:26.924415	\N	\N	75	519	Simon Bolivar
333	1	2014-01-26 04:37:26.924415	\N	\N	75	520	Sucre
334	1	2014-01-26 04:37:26.924415	\N	\N	75	521	Valmore Rodriguez
\.


--
-- Name: sas_municipio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_municipio_id_seq', 334, true);


--
-- Data for Name: sas_pais; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_pais (id, creacion_uid, creacion_fecha, edicion_fecha, edicion_uid, codigo, nombre) FROM stdin;
1	1	2014-01-26 04:36:17.418381	\N	\N	AD	Andorra, Principality of
2	1	2014-01-26 04:36:17.418381	\N	\N	AE	United Arab Emirates
3	1	2014-01-26 04:36:17.418381	\N	\N	AF	Afghanistan, Islamic State of
4	1	2014-01-26 04:36:17.418381	\N	\N	AG	Antigua and Barbuda
5	1	2014-01-26 04:36:17.418381	\N	\N	AI	Anguilla
6	1	2014-01-26 04:36:17.418381	\N	\N	AL	Albania
7	1	2014-01-26 04:36:17.418381	\N	\N	AM	Armenia
8	1	2014-01-26 04:36:17.418381	\N	\N	AN	Netherlands Antilles
9	1	2014-01-26 04:36:17.418381	\N	\N	AO	Angola
10	1	2014-01-26 04:36:17.418381	\N	\N	AQ	Antarctica
11	1	2014-01-26 04:36:17.418381	\N	\N	AR	Argentina
12	1	2014-01-26 04:36:17.418381	\N	\N	AS	American Samoa
13	1	2014-01-26 04:36:17.418381	\N	\N	AT	Austria
14	1	2014-01-26 04:36:17.418381	\N	\N	AU	Australia
15	1	2014-01-26 04:36:17.418381	\N	\N	AW	Aruba
16	1	2014-01-26 04:36:17.418381	\N	\N	AX	Åland Islands
17	1	2014-01-26 04:36:17.418381	\N	\N	AZ	Azerbaijan
18	1	2014-01-26 04:36:17.418381	\N	\N	BA	Bosnia-Herzegovina
19	1	2014-01-26 04:36:17.418381	\N	\N	BB	Barbados
20	1	2014-01-26 04:36:17.418381	\N	\N	BD	Bangladesh
21	1	2014-01-26 04:36:17.418381	\N	\N	BE	Belgium
22	1	2014-01-26 04:36:17.418381	\N	\N	BF	Burkina Faso
23	1	2014-01-26 04:36:17.418381	\N	\N	BG	Bulgaria
24	1	2014-01-26 04:36:17.418381	\N	\N	BH	Bahrain
25	1	2014-01-26 04:36:17.418381	\N	\N	BI	Burundi
26	1	2014-01-26 04:36:17.418381	\N	\N	BJ	Benin
27	1	2014-01-26 04:36:17.418381	\N	\N	BL	Saint Barthélémy
28	1	2014-01-26 04:36:17.418381	\N	\N	BM	Bermuda
29	1	2014-01-26 04:36:17.418381	\N	\N	BN	Brunei Darussalam
30	1	2014-01-26 04:36:17.418381	\N	\N	BO	Bolivia
31	1	2014-01-26 04:36:17.418381	\N	\N	BQ	Bonaire, Sint Eustatius and Saba
32	1	2014-01-26 04:36:17.418381	\N	\N	BR	Brazil
33	1	2014-01-26 04:36:17.418381	\N	\N	BS	Bahamas
34	1	2014-01-26 04:36:17.418381	\N	\N	BT	Bhutan
35	1	2014-01-26 04:36:17.418381	\N	\N	BV	Bouvet Island
36	1	2014-01-26 04:36:17.418381	\N	\N	BW	Botswana
37	1	2014-01-26 04:36:17.418381	\N	\N	BY	Belarus
38	1	2014-01-26 04:36:17.418381	\N	\N	BZ	Belize
39	1	2014-01-26 04:36:17.418381	\N	\N	CA	Canada
40	1	2014-01-26 04:36:17.418381	\N	\N	CC	Cocos (Keeling) Islands
41	1	2014-01-26 04:36:17.418381	\N	\N	CF	Central African Republic
42	1	2014-01-26 04:36:17.418381	\N	\N	CD	Congo, Democratic Republic of the
43	1	2014-01-26 04:36:17.418381	\N	\N	CG	Congo
44	1	2014-01-26 04:36:17.418381	\N	\N	CH	Switzerland
45	1	2014-01-26 04:36:17.418381	\N	\N	CI	Ivory Coast (Cote D'Ivoire)
46	1	2014-01-26 04:36:17.418381	\N	\N	CK	Cook Islands
47	1	2014-01-26 04:36:17.418381	\N	\N	CL	Chile
48	1	2014-01-26 04:36:17.418381	\N	\N	CM	Cameroon
49	1	2014-01-26 04:36:17.418381	\N	\N	CN	China
50	1	2014-01-26 04:36:17.418381	\N	\N	CO	Colombia
51	1	2014-01-26 04:36:17.418381	\N	\N	CR	Costa Rica
52	1	2014-01-26 04:36:17.418381	\N	\N	CU	Cuba
53	1	2014-01-26 04:36:17.418381	\N	\N	CV	Cape Verde
54	1	2014-01-26 04:36:17.418381	\N	\N	CW	Curaçao
55	1	2014-01-26 04:36:17.418381	\N	\N	CX	Christmas Island
56	1	2014-01-26 04:36:17.418381	\N	\N	CY	Cyprus
57	1	2014-01-26 04:36:17.418381	\N	\N	CZ	Czech Republic
58	1	2014-01-26 04:36:17.418381	\N	\N	DE	Germany
59	1	2014-01-26 04:36:17.418381	\N	\N	DJ	Djibouti
60	1	2014-01-26 04:36:17.418381	\N	\N	DK	Denmark
61	1	2014-01-26 04:36:17.418381	\N	\N	DM	Dominica
62	1	2014-01-26 04:36:17.418381	\N	\N	DO	Dominican Republic
63	1	2014-01-26 04:36:17.418381	\N	\N	DZ	Algeria
64	1	2014-01-26 04:36:17.418381	\N	\N	EC	Ecuador
65	1	2014-01-26 04:36:17.418381	\N	\N	EE	Estonia
66	1	2014-01-26 04:36:17.418381	\N	\N	EG	Egypt
67	1	2014-01-26 04:36:17.418381	\N	\N	EH	Western Sahara
68	1	2014-01-26 04:36:17.418381	\N	\N	ER	Eritrea
69	1	2014-01-26 04:36:17.418381	\N	\N	ES	Spain
70	1	2014-01-26 04:36:17.418381	\N	\N	ET	Ethiopia
71	1	2014-01-26 04:36:17.418381	\N	\N	FI	Finland
72	1	2014-01-26 04:36:17.418381	\N	\N	FJ	Fiji
73	1	2014-01-26 04:36:17.418381	\N	\N	FK	Falkland Islands
74	1	2014-01-26 04:36:17.418381	\N	\N	FM	Micronesia
75	1	2014-01-26 04:36:17.418381	\N	\N	FO	Faroe Islands
76	1	2014-01-26 04:36:17.418381	\N	\N	FR	France
77	1	2014-01-26 04:36:17.418381	\N	\N	GA	Gabon
78	1	2014-01-26 04:36:17.418381	\N	\N	GD	Grenada
79	1	2014-01-26 04:36:17.418381	\N	\N	GE	Georgia
80	1	2014-01-26 04:36:17.418381	\N	\N	GF	French Guyana
81	1	2014-01-26 04:36:17.418381	\N	\N	GH	Ghana
82	1	2014-01-26 04:36:17.418381	\N	\N	GI	Gibraltar
83	1	2014-01-26 04:36:17.418381	\N	\N	GG	Guernsey
84	1	2014-01-26 04:36:17.418381	\N	\N	GL	Greenland
85	1	2014-01-26 04:36:17.418381	\N	\N	GM	Gambia
86	1	2014-01-26 04:36:17.418381	\N	\N	GN	Guinea
87	1	2014-01-26 04:36:17.418381	\N	\N	GP	Guadeloupe (French)
88	1	2014-01-26 04:36:17.418381	\N	\N	GQ	Equatorial Guinea
89	1	2014-01-26 04:36:17.418381	\N	\N	GR	Greece
90	1	2014-01-26 04:36:17.418381	\N	\N	GS	South Georgia and the South Sandwich Islands
91	1	2014-01-26 04:36:17.418381	\N	\N	GT	Guatemala
92	1	2014-01-26 04:36:17.418381	\N	\N	GU	Guam (USA)
93	1	2014-01-26 04:36:17.418381	\N	\N	GW	Guinea Bissau
94	1	2014-01-26 04:36:17.418381	\N	\N	GY	Guyana
95	1	2014-01-26 04:36:17.418381	\N	\N	HK	Hong Kong
96	1	2014-01-26 04:36:17.418381	\N	\N	HM	Heard and McDonald Islands
97	1	2014-01-26 04:36:17.418381	\N	\N	HN	Honduras
98	1	2014-01-26 04:36:17.418381	\N	\N	HR	Croatia
99	1	2014-01-26 04:36:17.418381	\N	\N	HT	Haiti
100	1	2014-01-26 04:36:17.418381	\N	\N	HU	Hungary
101	1	2014-01-26 04:36:17.418381	\N	\N	ID	Indonesia
102	1	2014-01-26 04:36:17.418381	\N	\N	IE	Ireland
103	1	2014-01-26 04:36:17.418381	\N	\N	IL	Israel
104	1	2014-01-26 04:36:17.418381	\N	\N	IM	Isle of Man
105	1	2014-01-26 04:36:17.418381	\N	\N	IN	India
106	1	2014-01-26 04:36:17.418381	\N	\N	IO	British Indian Ocean Territory
107	1	2014-01-26 04:36:17.418381	\N	\N	IQ	Iraq
108	1	2014-01-26 04:36:17.418381	\N	\N	IR	Iran
109	1	2014-01-26 04:36:17.418381	\N	\N	IS	Iceland
110	1	2014-01-26 04:36:17.418381	\N	\N	IT	Italy
111	1	2014-01-26 04:36:17.418381	\N	\N	JE	Jersey
112	1	2014-01-26 04:36:17.418381	\N	\N	JM	Jamaica
113	1	2014-01-26 04:36:17.418381	\N	\N	JO	Jordan
114	1	2014-01-26 04:36:17.418381	\N	\N	JP	Japan
115	1	2014-01-26 04:36:17.418381	\N	\N	KE	Kenya
116	1	2014-01-26 04:36:17.418381	\N	\N	KG	Kyrgyz Republic (Kyrgyzstan)
117	1	2014-01-26 04:36:17.418381	\N	\N	KH	Cambodia, Kingdom of
118	1	2014-01-26 04:36:17.418381	\N	\N	KI	Kiribati
119	1	2014-01-26 04:36:17.418381	\N	\N	KM	Comoros
120	1	2014-01-26 04:36:17.418381	\N	\N	KN	Saint Kitts & Nevis Anguilla
121	1	2014-01-26 04:36:17.418381	\N	\N	KP	North Korea
122	1	2014-01-26 04:36:17.418381	\N	\N	KR	South Korea
123	1	2014-01-26 04:36:17.418381	\N	\N	KW	Kuwait
124	1	2014-01-26 04:36:17.418381	\N	\N	KY	Cayman Islands
125	1	2014-01-26 04:36:17.418381	\N	\N	KZ	Kazakhstan
126	1	2014-01-26 04:36:17.418381	\N	\N	LA	Laos
127	1	2014-01-26 04:36:17.418381	\N	\N	LB	Lebanon
128	1	2014-01-26 04:36:17.418381	\N	\N	LC	Saint Lucia
129	1	2014-01-26 04:36:17.418381	\N	\N	LI	Liechtenstein
130	1	2014-01-26 04:36:17.418381	\N	\N	LK	Sri Lanka
131	1	2014-01-26 04:36:17.418381	\N	\N	LR	Liberia
132	1	2014-01-26 04:36:17.418381	\N	\N	LS	Lesotho
133	1	2014-01-26 04:36:17.418381	\N	\N	LT	Lithuania
134	1	2014-01-26 04:36:17.418381	\N	\N	LU	Luxembourg
135	1	2014-01-26 04:36:17.418381	\N	\N	LV	Latvia
136	1	2014-01-26 04:36:17.418381	\N	\N	LY	Libya
137	1	2014-01-26 04:36:17.418381	\N	\N	MA	Morocco
138	1	2014-01-26 04:36:17.418381	\N	\N	MC	Monaco
139	1	2014-01-26 04:36:17.418381	\N	\N	MD	Moldavia
140	1	2014-01-26 04:36:17.418381	\N	\N	ME	Montenegro
141	1	2014-01-26 04:36:17.418381	\N	\N	MF	Saint Martin (French part)
142	1	2014-01-26 04:36:17.418381	\N	\N	MG	Madagascar
143	1	2014-01-26 04:36:17.418381	\N	\N	MH	Marshall Islands
144	1	2014-01-26 04:36:17.418381	\N	\N	MK	Macedonia, the former Yugoslav Republic of
145	1	2014-01-26 04:36:17.418381	\N	\N	ML	Mali
146	1	2014-01-26 04:36:17.418381	\N	\N	MM	Myanmar
147	1	2014-01-26 04:36:17.418381	\N	\N	MN	Mongolia
148	1	2014-01-26 04:36:17.418381	\N	\N	MO	Macau
149	1	2014-01-26 04:36:17.418381	\N	\N	MP	Northern Mariana Islands
150	1	2014-01-26 04:36:17.418381	\N	\N	MQ	Martinique (French)
151	1	2014-01-26 04:36:17.418381	\N	\N	MR	Mauritania
152	1	2014-01-26 04:36:17.418381	\N	\N	MS	Montserrat
153	1	2014-01-26 04:36:17.418381	\N	\N	MT	Malta
154	1	2014-01-26 04:36:17.418381	\N	\N	MU	Mauritius
155	1	2014-01-26 04:36:17.418381	\N	\N	MV	Maldives
156	1	2014-01-26 04:36:17.418381	\N	\N	MW	Malawi
157	1	2014-01-26 04:36:17.418381	\N	\N	MX	Mexico
158	1	2014-01-26 04:36:17.418381	\N	\N	MY	Malaysia
159	1	2014-01-26 04:36:17.418381	\N	\N	MZ	Mozambique
160	1	2014-01-26 04:36:17.418381	\N	\N	NA	Namibia
161	1	2014-01-26 04:36:17.418381	\N	\N	NC	New Caledonia (French)
162	1	2014-01-26 04:36:17.418381	\N	\N	NE	Niger
163	1	2014-01-26 04:36:17.418381	\N	\N	NF	Norfolk Island
164	1	2014-01-26 04:36:17.418381	\N	\N	NG	Nigeria
165	1	2014-01-26 04:36:17.418381	\N	\N	NI	Nicaragua
166	1	2014-01-26 04:36:17.418381	\N	\N	NL	Netherlands
167	1	2014-01-26 04:36:17.418381	\N	\N	NO	Norway
168	1	2014-01-26 04:36:17.418381	\N	\N	NP	Nepal
169	1	2014-01-26 04:36:17.418381	\N	\N	NR	Nauru
170	1	2014-01-26 04:36:17.418381	\N	\N	NT	Neutral Zone
171	1	2014-01-26 04:36:17.418381	\N	\N	NU	Niue
172	1	2014-01-26 04:36:17.418381	\N	\N	NZ	New Zealand
173	1	2014-01-26 04:36:17.418381	\N	\N	OM	Oman
174	1	2014-01-26 04:36:17.418381	\N	\N	PA	Panama
175	1	2014-01-26 04:36:17.418381	\N	\N	PE	Peru
176	1	2014-01-26 04:36:17.418381	\N	\N	PF	Polynesia (French)
177	1	2014-01-26 04:36:17.418381	\N	\N	PG	Papua New Guinea
178	1	2014-01-26 04:36:17.418381	\N	\N	PH	Philippines
179	1	2014-01-26 04:36:17.418381	\N	\N	PK	Pakistan
180	1	2014-01-26 04:36:17.418381	\N	\N	PL	Poland
181	1	2014-01-26 04:36:17.418381	\N	\N	PM	Saint Pierre and Miquelon
182	1	2014-01-26 04:36:17.418381	\N	\N	PN	Pitcairn Island
183	1	2014-01-26 04:36:17.418381	\N	\N	PR	Puerto Rico
184	1	2014-01-26 04:36:17.418381	\N	\N	PS	Palestinian Territory, Occupied
185	1	2014-01-26 04:36:17.418381	\N	\N	PT	Portugal
186	1	2014-01-26 04:36:17.418381	\N	\N	PW	Palau
187	1	2014-01-26 04:36:17.418381	\N	\N	PY	Paraguay
188	1	2014-01-26 04:36:17.418381	\N	\N	QA	Qatar
189	1	2014-01-26 04:36:17.418381	\N	\N	RE	Reunion (French)
190	1	2014-01-26 04:36:17.418381	\N	\N	RO	Romania
191	1	2014-01-26 04:36:17.418381	\N	\N	RS	Serbia
192	1	2014-01-26 04:36:17.418381	\N	\N	RU	Russian Federation
193	1	2014-01-26 04:36:17.418381	\N	\N	RW	Rwanda
194	1	2014-01-26 04:36:17.418381	\N	\N	SA	Saudi Arabia
195	1	2014-01-26 04:36:17.418381	\N	\N	SB	Solomon Islands
196	1	2014-01-26 04:36:17.418381	\N	\N	SC	Seychelles
197	1	2014-01-26 04:36:17.418381	\N	\N	SD	Sudan
198	1	2014-01-26 04:36:17.418381	\N	\N	SE	Sweden
199	1	2014-01-26 04:36:17.418381	\N	\N	SG	Singapore
200	1	2014-01-26 04:36:17.418381	\N	\N	SH	Saint Helena
201	1	2014-01-26 04:36:17.418381	\N	\N	SI	Slovenia
202	1	2014-01-26 04:36:17.418381	\N	\N	SJ	Svalbard and Jan Mayen Islands
203	1	2014-01-26 04:36:17.418381	\N	\N	SK	Slovakia
204	1	2014-01-26 04:36:17.418381	\N	\N	SL	Sierra Leone
205	1	2014-01-26 04:36:17.418381	\N	\N	SM	San Marino
206	1	2014-01-26 04:36:17.418381	\N	\N	SN	Senegal
207	1	2014-01-26 04:36:17.418381	\N	\N	SO	Somalia
208	1	2014-01-26 04:36:17.418381	\N	\N	SR	Suriname
209	1	2014-01-26 04:36:17.418381	\N	\N	SS	South Sudan
210	1	2014-01-26 04:36:17.418381	\N	\N	ST	Saint Tome (Sao Tome) and Principe
211	1	2014-01-26 04:36:17.418381	\N	\N	SV	El Salvador
212	1	2014-01-26 04:36:17.418381	\N	\N	SX	Sint Maarten (Dutch part)
213	1	2014-01-26 04:36:17.418381	\N	\N	SY	Syria
214	1	2014-01-26 04:36:17.418381	\N	\N	SZ	Swaziland
215	1	2014-01-26 04:36:17.418381	\N	\N	TC	Turks and Caicos Islands
216	1	2014-01-26 04:36:17.418381	\N	\N	TD	Chad
217	1	2014-01-26 04:36:17.418381	\N	\N	TF	French Southern Territories
218	1	2014-01-26 04:36:17.418381	\N	\N	TG	Togo
219	1	2014-01-26 04:36:17.418381	\N	\N	TH	Thailand
220	1	2014-01-26 04:36:17.418381	\N	\N	TJ	Tajikistan
221	1	2014-01-26 04:36:17.418381	\N	\N	TK	Tokelau
222	1	2014-01-26 04:36:17.418381	\N	\N	TM	Turkmenistan
223	1	2014-01-26 04:36:17.418381	\N	\N	TN	Tunisia
224	1	2014-01-26 04:36:17.418381	\N	\N	TO	Tonga
225	1	2014-01-26 04:36:17.418381	\N	\N	TP	East Timor
226	1	2014-01-26 04:36:17.418381	\N	\N	TR	Turkey
227	1	2014-01-26 04:36:17.418381	\N	\N	TT	Trinidad and Tobago
228	1	2014-01-26 04:36:17.418381	\N	\N	TV	Tuvalu
229	1	2014-01-26 04:36:17.418381	\N	\N	TW	Taiwan
230	1	2014-01-26 04:36:17.418381	\N	\N	TZ	Tanzania
231	1	2014-01-26 04:36:17.418381	\N	\N	UA	Ukraine
232	1	2014-01-26 04:36:17.418381	\N	\N	UG	Uganda
233	1	2014-01-26 04:36:17.418381	\N	\N	GB	United Kingdom
234	1	2014-01-26 04:36:17.418381	\N	\N	UM	USA Minor Outlying Islands
235	1	2014-01-26 04:36:17.418381	\N	\N	US	United States
236	1	2014-01-26 04:36:17.418381	\N	\N	UY	Uruguay
237	1	2014-01-26 04:36:17.418381	\N	\N	UZ	Uzbekistan
238	1	2014-01-26 04:36:17.418381	\N	\N	VA	Holy See (Vatican City State)
239	1	2014-01-26 04:36:17.418381	\N	\N	VC	Saint Vincent & Grenadines
240	1	2014-01-26 04:36:17.418381	\N	\N	VE	Venezuela
241	1	2014-01-26 04:36:17.418381	\N	\N	VG	Virgin Islands (British)
242	1	2014-01-26 04:36:17.418381	\N	\N	VI	Virgin Islands (USA)
243	1	2014-01-26 04:36:17.418381	\N	\N	VN	Vietnam
244	1	2014-01-26 04:36:17.418381	\N	\N	VU	Vanuatu
245	1	2014-01-26 04:36:17.418381	\N	\N	WF	Wallis and Futuna Islands
246	1	2014-01-26 04:36:17.418381	\N	\N	WS	Samoa
247	1	2014-01-26 04:36:17.418381	\N	\N	YE	Yemen
248	1	2014-01-26 04:36:17.418381	\N	\N	YT	Mayotte
249	1	2014-01-26 04:36:17.418381	\N	\N	YU	Yugoslavia
250	1	2014-01-26 04:36:17.418381	\N	\N	ZA	South Africa
251	1	2014-01-26 04:36:17.418381	\N	\N	ZM	Zambia
252	1	2014-01-26 04:36:17.418381	\N	\N	ZR	Zaire
253	1	2014-01-26 04:36:17.418381	\N	\N	ZW	Zimbabwe
\.


--
-- Data for Name: sas_pais_estado; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_pais_estado (id, creacion_uid, creacion_fecha, edicion_fecha, edicion_uid, codigo, pais_id, nombre) FROM stdin;
1	1	2014-01-26 04:36:17.418381	\N	\N	AL	235	Alabama
2	1	2014-01-26 04:36:17.418381	\N	\N	AK	235	Alaska
3	1	2014-01-26 04:36:17.418381	\N	\N	AZ	235	Arizona
4	1	2014-01-26 04:36:17.418381	\N	\N	AR	235	Arkansas
5	1	2014-01-26 04:36:17.418381	\N	\N	CA	235	California
6	1	2014-01-26 04:36:17.418381	\N	\N	CO	235	Colorado
7	1	2014-01-26 04:36:17.418381	\N	\N	CT	235	Connecticut
8	1	2014-01-26 04:36:17.418381	\N	\N	DE	235	Delaware
9	1	2014-01-26 04:36:17.418381	\N	\N	DC	235	District of Columbia
10	1	2014-01-26 04:36:17.418381	\N	\N	FL	235	Florida
11	1	2014-01-26 04:36:17.418381	\N	\N	GA	235	Georgia
12	1	2014-01-26 04:36:17.418381	\N	\N	HI	235	Hawaii
13	1	2014-01-26 04:36:17.418381	\N	\N	ID	235	Idaho
14	1	2014-01-26 04:36:17.418381	\N	\N	IL	235	Illinois
15	1	2014-01-26 04:36:17.418381	\N	\N	IN	235	Indiana
16	1	2014-01-26 04:36:17.418381	\N	\N	IA	235	Iowa
17	1	2014-01-26 04:36:17.418381	\N	\N	KS	235	Kansas
18	1	2014-01-26 04:36:17.418381	\N	\N	KY	235	Kentucky
19	1	2014-01-26 04:36:17.418381	\N	\N	LA	235	Louisiana
20	1	2014-01-26 04:36:17.418381	\N	\N	ME	235	Maine
21	1	2014-01-26 04:36:17.418381	\N	\N	MT	235	Montana
22	1	2014-01-26 04:36:17.418381	\N	\N	NE	235	Nebraska
23	1	2014-01-26 04:36:17.418381	\N	\N	NV	235	Nevada
24	1	2014-01-26 04:36:17.418381	\N	\N	NH	235	New Hampshire
25	1	2014-01-26 04:36:17.418381	\N	\N	NJ	235	New Jersey
26	1	2014-01-26 04:36:17.418381	\N	\N	NM	235	New Mexico
27	1	2014-01-26 04:36:17.418381	\N	\N	NY	235	New York
28	1	2014-01-26 04:36:17.418381	\N	\N	NC	235	North Carolina
29	1	2014-01-26 04:36:17.418381	\N	\N	ND	235	North Dakota
30	1	2014-01-26 04:36:17.418381	\N	\N	OH	235	Ohio
31	1	2014-01-26 04:36:17.418381	\N	\N	OK	235	Oklahoma
32	1	2014-01-26 04:36:17.418381	\N	\N	OR	235	Oregon
33	1	2014-01-26 04:36:17.418381	\N	\N	MD	235	Maryland
34	1	2014-01-26 04:36:17.418381	\N	\N	MA	235	Massachusetts
35	1	2014-01-26 04:36:17.418381	\N	\N	MI	235	Michigan
36	1	2014-01-26 04:36:17.418381	\N	\N	MN	235	Minnesota
37	1	2014-01-26 04:36:17.418381	\N	\N	MS	235	Mississippi
38	1	2014-01-26 04:36:17.418381	\N	\N	MO	235	Missouri
39	1	2014-01-26 04:36:17.418381	\N	\N	PA	235	Pennsylvania
40	1	2014-01-26 04:36:17.418381	\N	\N	RI	235	Rhode Island
41	1	2014-01-26 04:36:17.418381	\N	\N	SC	235	South Carolina
42	1	2014-01-26 04:36:17.418381	\N	\N	SD	235	South Dakota
43	1	2014-01-26 04:36:17.418381	\N	\N	TN	235	Tennessee
44	1	2014-01-26 04:36:17.418381	\N	\N	TX	235	Texas
45	1	2014-01-26 04:36:17.418381	\N	\N	UT	235	Utah
46	1	2014-01-26 04:36:17.418381	\N	\N	VT	235	Vermont
47	1	2014-01-26 04:36:17.418381	\N	\N	VA	235	Virginia
48	1	2014-01-26 04:36:17.418381	\N	\N	WA	235	Washington
49	1	2014-01-26 04:36:17.418381	\N	\N	WV	235	West Virginia
50	1	2014-01-26 04:36:17.418381	\N	\N	WI	235	Wisconsin
51	1	2014-01-26 04:36:17.418381	\N	\N	WY	235	Wyoming
52	1	2014-01-26 04:37:26.924415	\N	\N	dc	240	Distrito Capital
53	1	2014-01-26 04:37:26.924415	\N	\N	am	240	Amazonas
54	1	2014-01-26 04:37:26.924415	\N	\N	an	240	Anzoategui
55	1	2014-01-26 04:37:26.924415	\N	\N	ap	240	Apure
56	1	2014-01-26 04:37:26.924415	\N	\N	ar	240	Aragua
57	1	2014-01-26 04:37:26.924415	\N	\N	ba	240	Barinas
58	1	2014-01-26 04:37:26.924415	\N	\N	bo	240	Bolivar
59	1	2014-01-26 04:37:26.924415	\N	\N	ca	240	Carabobo
60	1	2014-01-26 04:37:26.924415	\N	\N	co	240	Cojedes
61	1	2014-01-26 04:37:26.924415	\N	\N	da	240	Delta Amacuro
62	1	2014-01-26 04:37:26.924415	\N	\N	fa	240	Falcon
63	1	2014-01-26 04:37:26.924415	\N	\N	gu	240	Guarico
64	1	2014-01-26 04:37:26.924415	\N	\N	la	240	Lara
65	1	2014-01-26 04:37:26.924415	\N	\N	me	240	Merida
66	1	2014-01-26 04:37:26.924415	\N	\N	mi	240	Miranda
67	1	2014-01-26 04:37:26.924415	\N	\N	mo	240	Monagas
68	1	2014-01-26 04:37:26.924415	\N	\N	ne	240	Nueva Esparta
69	1	2014-01-26 04:37:26.924415	\N	\N	po	240	Portuguesa
70	1	2014-01-26 04:37:26.924415	\N	\N	su	240	Sucre
71	1	2014-01-26 04:37:26.924415	\N	\N	ta	240	Tachira
72	1	2014-01-26 04:37:26.924415	\N	\N	tr	240	Trujillo
73	1	2014-01-26 04:37:26.924415	\N	\N	va	240	Vargas
74	1	2014-01-26 04:37:26.924415	\N	\N	ya	240	Yaracuy
75	1	2014-01-26 04:37:26.924415	\N	\N	zu	240	Zulia
\.


--
-- Name: sas_pais_estado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_pais_estado_id_seq', 75, true);


--
-- Name: sas_pais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_pais_id_seq', 253, true);


--
-- Data for Name: sas_parroquia; Type: TABLE DATA; Schema: public; Owner: jelitox
--

COPY sas_parroquia (id, creacion_uid, creacion_fecha, edicion_fecha, edicion_uid, nombre, municipio_id) FROM stdin;
1	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Huachamacare	2
2	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Marawaka	2
3	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mavaca	2
4	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sierra Parima	2
5	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ucata	3
6	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Yapacana	3
7	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caname	3
8	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Fernando Girón Tovar	4
9	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Luis Alberto Gómez	4
10	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Parhueña	4
11	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Platanillal	4
12	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Samariapo	5
13	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sipapo	5
14	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Munduapo	5
15	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guayapo	5
16	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Victorino	7
17	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Comunidad	7
18	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alto Ventuari	6
19	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Medio Ventuari	6
20	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bajo Ventuari	6
21	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Solano	8
22	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Casiquiare	8
23	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cocuy	8
24	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Anaco	9
25	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Joaquín	9
26	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Aragua	10
27	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cachipo	10
28	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Fernando de Peñalver	24
29	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Miguel	24
30	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	24
31	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Francisco del Carmen Carvajal	14
32	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Bárbara	14
33	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Francisco de Miranda	22
34	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Atapirire	22
35	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boca del Pao	22
36	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Pao	22
37	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Múcura	22
38	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Guanta	18
39	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chorrerón	18
40	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Independencia	19
41	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mamo	19
42	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Puerto La Cruz	29
43	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pozuelos	29
44	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Juan Manuel Cajigal	13
45	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Pablo	13
46	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital José Gregorio Monagas	23
47	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Piar	23
48	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Diego de Cabrutica	23
49	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Clara	23
50	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Uverito	23
51	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Zuata	23
52	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Libertad	20
53	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Carito	20
54	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Inés	20
55	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Manuel Ezequiel Bruzual	12
56	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guanape	12
57	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabana de Uchire	12
58	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Pedro María Freites	16
59	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertador	16
60	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rosa	16
61	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urica	16
62	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Píritu	25
63	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco	25
64	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital San Juan de Capistrano	26
65	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boca de Chávez	26
66	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Santa Ana	27
67	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pueblo Nuevo	27
68	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Carmen	11
69	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Cristóbal	11
70	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bergantín	11
71	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caigua	11
72	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Pilar	11
73	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Naricual	11
74	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Edmundo Barrios	28
75	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Miguel Otero Silva	28
76	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Sir Arthur Mc Gregor	21
77	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tomás Alfaro Calatrava	21
78	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Diego Bautista Urbaneja	15
79	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Morro	15
80	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Achaguas	30
81	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Apurito	30
82	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Yagual	30
83	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guachara	30
84	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mucuritas	30
85	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Queseras del Medio	30
86	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Biruaca	31
87	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Bruzual	32
88	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mantecal	32
89	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Quintero	32
90	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rincón Hondo	32
91	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Vicente	32
92	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Guasdualito	33
93	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aramendi	33
94	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Amparo	33
95	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Camilo	33
96	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urdaneta	33
97	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana San Juan de Payara	34
98	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Codazzi	34
99	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cunaviche	34
100	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Elorza	35
101	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Trinidad	35
102	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana San Fernando	36
103	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Recreo	36
104	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Peñalver	36
105	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael de Atamaica	36
106	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Camatagua	38
107	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Carmen de Cura	38
108	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Choroní	40
109	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Las Delicias	40
110	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Madre María de San José	40
111	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Joaquín Crespo	40
112	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Pedro José Ovalles	40
113	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana José Casanova Godoy	40
114	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Andrés Eloy Blanco	40
115	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Los Tacariguas	40
116	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Félix Ribas	42
117	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Castor Nieves Ríos	42
118	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Las Guacamayas	42
119	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Pao de Zárate	42
120	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Zuata	42
121	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertador	44
122	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana San Martín de Porres	44
123	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mario Briceño Iragorry	45
124	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caña de Azúcar	45
125	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Casimiro	47
126	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Güiripa	47
127	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Ollas de Caramacate	47
128	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Valle Morín	47
129	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santiago Mariño	49
130	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Arévalo Aponte	49
131	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Chuao	49
132	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Samán de Güere	49
133	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Alfredo Pacheco Miranda	49
134	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santos Michelena	50
135	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Tiara	50
136	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	51
137	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Bella Vista	51
138	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urdaneta	53
139	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Las Peñitas	53
140	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana San Francisco de Cara	53
141	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Taguay	53
142	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Zamora	54
143	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Magdaleno	54
144	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana San Francisco de Asís	54
145	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Valles de Tucutunemo	54
146	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Augusto Mijares	54
147	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Francisco Linares Alcántara	39
148	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Francisco de Miranda	39
149	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Monseñor Feliciano González	39
150	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabaneta	55
151	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rodríguez Domínguez	55
152	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ticoporo	57
153	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Andrés Bello	57
154	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Nicolás Pulido	57
155	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arismendi	58
156	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guadarrama	58
157	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Unión	58
158	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio	58
159	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Barinas	59
160	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alfredo Arvelo Larriva	59
161	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Silvestre	59
162	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Inés	59
163	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Lucía	59
164	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Torunos	59
165	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Carmen	59
166	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rómulo Betancourt	59
167	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Corazón de Jesús	59
168	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ramón Ignacio Méndez	59
169	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alto Barinas	59
170	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Palacio Fajardo	59
171	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Antonio Rodríguez Domínguez	59
172	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Dominga Ortiz de Páez	59
173	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Barinitas	60
174	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altamira	60
175	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Calderas	60
176	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Barrancas	61
177	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Socorro	61
178	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Masparrito	61
179	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Bárbara	62
180	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Ignacio Del Pumar	62
181	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedro Briceño Méndez	62
182	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ramón Ignacio Méndez	62
183	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Obispos	63
184	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Real	63
185	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Luz	63
186	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Guasimitos	63
187	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ciudad Bolivia	64
188	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ignacio Briceño	64
189	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Félix Ribas	64
190	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Paez	64
191	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad	65
192	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Dolores	65
193	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Palacios Fajardo	65
194	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rosa	65
195	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Simón Rodríguez	65
196	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ciudad de Nutrias	66
197	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Regalo	66
198	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Puerto de Nutrias	66
199	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Catalina	66
200	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Simón Bolívar	66
201	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Cantón	56
202	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz de Guacas	56
203	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Puerto Vivas	56
204	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cachamay	67
205	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chirica	67
206	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Dalla Costa	67
207	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Once de Abril	67
208	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Simón Bolívar	67
209	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Unare	67
210	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Universidad	67
211	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Vista al Sol	67
212	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pozo Verde	67
213	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Yocoima	67
214	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Cedeño	68
215	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altagracia	68
216	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ascensión Farreras	68
217	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guaniamo	68
218	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Urbana	68
219	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pijiguaos	68
220	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Gran Sabana	70
221	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ikabarú	70
222	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Salada	71
223	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Catedral	71
224	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Antonio Páez	71
225	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Sabanita	71
226	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Marhuanta	71
227	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Vista Hermosa	71
228	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Orinoco	71
229	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Panapana	71
230	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Zea	71
231	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Piar	72
232	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Andrés Eloy Blanco	72
233	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedro Cova	72
234	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Raúl Leoni	73
235	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Barceloneta	73
236	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco	73
237	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Bárbara	73
238	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Roscio	74
239	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Salom	74
240	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Sifontes	75
241	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Dalla Costa	75
242	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Isidro	75
243	1	2014-01-26 04:37:26.924415	\N	\N	Sección Capital Sucre	76
244	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aripao	76
245	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guarataro	76
246	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Majadas	76
247	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Moitaco	76
248	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Bejuma	78
249	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Canoabo	78
250	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Simón Bolívar	78
251	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Güigüe	79
252	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Belén	79
253	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Tacarigua	79
254	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Aguas Calientes	81
255	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Mariara	81
256	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Ciudad Alianza	80
257	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Guacara	80
258	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Yagua	80
259	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Morón	82
260	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Urama	82
261	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Tocuyito	83
262	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Independencia	83
263	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Los Guayos	84
264	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Miranda	86
265	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Montalbán	87
266	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Naguanagua	85
267	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Bartolomé Salom	88
268	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Democracia	88
269	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Fraternidad	88
270	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Goaigoaza	88
271	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Juan José Flores	88
272	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Unión	88
273	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Borburata	88
274	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Patanemo	88
275	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana San Diego	89
276	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana San Joaquín	90
277	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Candelaria	91
278	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Catedral	91
279	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana El Socorro	91
280	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Miguel Peña	91
281	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Rafael Urdaneta	91
282	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana San Blas	91
283	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana San José	91
284	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urbana Santa Rosa	91
285	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia No Urbana Negro Primero	91
286	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cojedes	92
287	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan de Mata Suárez	92
288	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tinaquillo	93
289	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Baúl	94
290	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	94
291	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Macapo	95
292	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Aguadita	95
293	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Pao	96
294	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad de Cojedes	97
295	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Amparo	97
296	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rómulo Gallegos	98
297	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Carlos de Austria	99
298	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Angel Bravo	99
299	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Manrique	99
300	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia General en Jefe José Laurencio Silva	100
301	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Curiapo	101
302	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Almirante Luis Brión	101
303	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Francisco Aniceto Lugo	101
304	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Renaud	101
305	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Padre Barral	101
306	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santos de Abelgas	101
307	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Imataca	102
308	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cinco de Julio	102
309	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Bautista Arismendi	102
310	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Piar	102
311	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rómulo Gallegos	102
312	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedernales	103
313	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Luis Beltrán Prieto Figueroa	103
314	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José	104
315	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Vidal Marcano	104
316	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Millán	104
317	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Leonardo Ruíz Pineda	104
318	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mariscal Antonio José de Sucre	104
319	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monseñor Argimiro García	104
320	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael	104
321	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Virgen del Valle	104
322	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altagracia	1
323	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antímano	1
324	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Candelaria	1
325	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caricuao	1
326	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Catedral	1
327	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Coche	1
328	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Junquito	1
329	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia EL Paraíso	1
330	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Recreo	1
331	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Valle	1
332	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Pastora	1
333	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Vega	1
334	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Macarao	1
335	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Agustín	1
336	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Bernardino	1
337	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José	1
338	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan	1
339	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Pedro	1
340	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rosalía	1
341	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Teresa	1
342	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	1
343	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia 23 de Enero	1
344	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan de los Cayos	105
345	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capadare	105
346	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Pastora	105
347	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertador	105
348	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Luis	106
349	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aracua	106
350	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Peña	106
351	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capatárida	107
352	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bariro	107
353	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Borojó	107
354	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guajiro	107
355	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Seque	107
356	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Zazárida	107
357	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carirubana	109
358	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Norte	109
359	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Punta Cardón	109
360	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Ana	109
361	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Vela de Coro	110
362	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Acurigua	110
363	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guaibacoa	110
364	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Calderas	110
365	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Macoruca	110
366	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedregal	112
367	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Clara	112
368	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Avaria	112
369	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Piedra Grande	112
370	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Purureche	112
371	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pueblo Nuevo	113
372	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Adícora	113
373	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Baraived	113
374	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Buena Vista	113
375	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jadacaquiva	113
376	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Moruy	113
377	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Adaure	113
378	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Hato	113
379	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Vínculo	113
380	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Churuguara	114
381	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Larga	114
382	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Paují	114
383	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Independencia	114
384	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mapararí	114
385	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jacura	115
386	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Linda	115
387	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Araurima	115
388	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Taques	116
389	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Judibana	116
390	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mene de Mauroa	117
391	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Casigua	117
392	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Félix	117
393	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio	118
394	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Gabriel	118
395	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Ana	118
396	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guzmán Guillermo	118
397	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mitare	118
398	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Río Seco	118
399	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabaneta	118
400	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chichiriviche	119
401	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boca de Tocuyo	119
402	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tocuyo de la Costa	119
403	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cabure	121
404	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Colina	121
405	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Curimagua	121
406	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Píritu	122
407	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de la Costa	122
408	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tucacas	124
409	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boca de Aroa	124
410	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	125
411	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pecaya	125
412	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz de Bucaral	127
413	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Charal	127
414	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Vegas del Tuy	127
415	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urumaco	128
416	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bruzual	128
417	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Puerto Cumarebo	129
418	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Ciénaga	129
419	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Soledad	129
420	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pueblo Cumarebo	129
421	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Zazárida	129
422	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Camaguán	130
423	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Puerto Miranda	130
424	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Uverito	130
425	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chaguaramas	131
426	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Socorro	132
427	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital San Gerónimo de Guayabal	142
428	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cazorla	142
429	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Valle de La Pascua	139
430	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Espino	139
431	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Las Mercedes	138
432	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cabruta	138
433	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rita de Manapire	138
434	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital El Sombrero	137
435	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sosa	137
436	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Calabozo	133
437	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Calvario	133
438	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Rastro	133
439	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guardatinajas	133
440	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Altagracia de Orituco	135
441	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Lezama	135
442	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad de Orituco	135
443	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Paso Real de Macaira	135
444	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco de Macaira	135
445	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael de Orituco	135
446	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Soublette	135
447	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Ortiz	141
448	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco de Tiznado	141
449	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Tiznado	141
450	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Lorenzo de Tiznado	141
451	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Tucupido	134
452	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael de Laya	134
453	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital San Juan de Los Morros	136
454	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cantagallo	136
455	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Parapara	136
456	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Guaribe	143
457	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Santa María de Ipire	144
458	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altamira	144
459	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Zaraza	140
460	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Unare	140
461	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pío Tamayo	145
462	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Quebrada Honda de Guache	145
463	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Yacambú	145
464	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Fréitez	146
465	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José María Blanco	146
466	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Catedral	147
467	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Concepción	147
468	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Cují	147
469	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan de Villegas	147
470	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rosa	147
471	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tamaca	147
472	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Unión	147
473	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aguedo Felipe Alvarado	147
474	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Buena Vista	147
475	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juárez	147
476	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Bautista Rodríguez	148
477	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cuara	148
478	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Diego de Lozada	148
479	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Paraíso de San José	148
480	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Miguel	148
481	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tintorero	148
482	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Bernardo Dorante	148
483	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Coronel Mariano Peraza	148
484	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolívar	149
485	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Anzoátegui	149
486	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guarico	149
487	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Hilario Luna y Luna	149
488	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Humocaro Alto	149
489	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Humocaro Bajo	149
490	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Candelaria	149
491	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Morán	149
492	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cabudare	150
493	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Gregorio Bastidas	150
494	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Viva	150
495	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sarare	151
496	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Buría	151
497	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Gustavo Vegas León	151
498	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Trinidad Samuel	152
499	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antonio Díaz	152
500	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Camacaro	152
501	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Castañeda	152
502	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cecilio Zubillaga	152
503	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chiquinquirá	152
504	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Blanco	152
505	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Espinoza de los Monteros	152
506	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Lara	152
507	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Mercedes	152
508	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Morillo	152
509	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Montaña Verde	152
510	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Montes de Oca	152
511	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Torres	152
512	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Heriberto Arroyo	152
513	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Reyes Vargas	152
514	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altagracia	152
515	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Siquisique	153
516	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Moroturo	153
517	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Miguel	153
518	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Xaguas	153
519	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Presidente Betancourt	154
520	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Presidente Páez 	154
521	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Presidente Rómulo Gallegos	154
522	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Gabriel Picón González	154
523	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Héctor Amable Mora	154
524	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Nucete Sardi	154
525	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pulido Méndez	154
526	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Antonio Pinto Salinas 	156
527	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mesa Bolívar	156
528	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mesa de Las Palmas	156
529	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Aricagua	157
530	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio	157
531	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Arzobispo Chacón	158
532	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capurí	158
533	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chacantá	158
534	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Molino	158
535	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guaimaral	158
536	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mucutuy	158
537	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mucuchachí	158
538	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Fernández Peña	159
539	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Matriz	159
540	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Montalbán	159
541	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Acequias	159
542	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jají	159
543	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Mesa	159
544	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José del Sur	159
545	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Caracciolo Parra Olmedo	160
546	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Florencio Ramírez	160
547	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Cardenal Quintero	161
548	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Piedras	161
549	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Guaraque	162
550	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mesa de Quintero	162
551	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Río Negro	162
552	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Julio César Salas	163
553	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Palmira	163
554	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Justo Briceño	164
555	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Cristóbal de Torondoy	164
556	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antonio Spinetti Dini	165
557	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arias	165
558	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caracciolo Parra Pérez	165
559	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Domingo Peña	165
560	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Llano	165
561	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Gonzalo Picón Febres	165
562	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jacinto Plaza	165
563	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Rodríguez Suárez	165
564	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Lasso de la Vega	165
565	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mariano Picón Salas	165
566	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Milla	165
567	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Osuna Rodríguez	165
568	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sagrario	165
569	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Morro	165
570	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Nevados	165
571	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Miranda	166
572	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Andrés Eloy Blanco	166
573	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Venta	166
574	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Piñango	166
575	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Obispo Ramos de Lora	167
576	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Eloy Paredes	167
577	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael de Alcázar	167
578	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Rangel	170
579	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cacute	170
580	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Toma	170
581	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mucurubá	170
582	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael	170
583	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Rivas Dávila	171
584	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Gerónimo Maldonado	171
585	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Sucre	173
586	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chiguará	173
587	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Estánquez	173
588	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Trampa	173
589	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pueblo Nuevo del Sur	173
590	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan	173
591	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Amparo	174
592	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Llano	174
593	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco	174
594	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tovar	174
595	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Tulio Febres Cordero	175
596	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Independencia	175
597	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia María de la Concepción Palacios Blanco	175
598	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Apolonia	175
599	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Zea	176
600	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caño El Tigre	176
601	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caucagua	177
602	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aragüita	177
603	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arévalo González	177
604	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capaya	177
605	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Café	177
606	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Marizapa	177
607	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Panaquire	177
608	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ribas	177
609	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Barlovento	178
610	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cumbo	178
611	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Baruta	179
612	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Cafetal	179
613	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Minas de Baruta	179
614	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Higuerote	180
615	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Curiepe	180
616	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tacarigua	180
617	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mamporal	181
618	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carrizal	182
619	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chacao	183
620	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Charallave	184
621	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Brisas	184
622	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Hatillo	185
623	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Teques	186
624	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altagracia de La Montaña	186
625	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cecilio Acosta	186
626	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Jarillo	186
627	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Paracotos	186
628	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Pedro	186
629	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tácata	186
630	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Teresa del Tuy	187
631	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Cartanal/	187
632	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ocumare del Tuy	188
633	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Democracia	188
634	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Bárbara	188
635	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio de Los Altos	189
636	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Río Chico	190
637	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Guapo	190
638	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tacarigua de La Laguna	190
639	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Paparo	190
640	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Fernando del Guapo	190
641	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Lucía	191
642	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cúpira	192
643	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Machurucuto	192
644	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guarenas	193
645	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco de Yare	194
646	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio de Yare	194
647	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Petare	195
648	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caucagüita	195
649	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Fila de Mariches	195
650	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Dolorita	195
651	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Leoncio Martínez	195
652	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cúa	196
653	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Nueva Cúa	196
654	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guatire	197
655	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolívar	197
656	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Acosta 	198
657	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco	198
658	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Caripe	201
659	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Guácharo	201
660	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Guanota	201
661	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabana de Piedra	201
662	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Agustín	201
663	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Teresén	201
664	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Cedeño	202
665	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Areo	202
666	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Félix	202
667	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Viento Fresco	202
668	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Ezequiel Zamora	203
669	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Tejero	203
670	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Libertador	204
671	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chaguaramas	204
672	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Alhuacas	204
673	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tabasca	204
674	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Maturín	205
675	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alto de los Godos	205
676	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boquerón	205
677	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Cocuizas	205
678	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Simón	205
679	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz	205
680	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Corozo	205
681	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Furrial	205
682	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jusepín	205
683	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Pica	205
684	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Vicente	205
685	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Piar	206
686	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aparicio	206
687	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chaguaramal	206
688	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Pinto	206
689	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guanaguana	206
690	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Toscana	206
691	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Taguaya	206
692	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Punceres	207
693	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cachipo	207
694	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Sotillo	209
695	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Barrancos de Fajardo	209
696	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Díaz	213
697	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Zabala	213
698	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital García	214
699	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Francisco Fajardo	214
700	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Gómez	215
701	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolívar	215
702	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guevara	215
703	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Matasiete	215
704	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	215
705	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Maneiro	216
706	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aguirre	216
707	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Marcano	217
708	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Adrián	217
709	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Península de Macanao	219
710	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco	219
711	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Tubores	220
712	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Barales	220
713	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Villalba	221
714	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Vicente Fuentes	221
715	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Araure	223
716	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Río Acarigua	223
717	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Esteller	224
718	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Uveral	224
719	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Guanare	225
720	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Córdoba	225
721	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de la Montaña	225
722	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan de Guanaguanare	225
723	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Virgen de la Coromoto	225
724	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Guanarito	226
725	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Trinidad de la Capilla	226
726	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Divina Pastora	226
727	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Mons.José Vicente de Unda	227
728	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Peña Blanca	227
729	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Ospino	228
730	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aparición	228
731	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Estación	228
732	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Páez	229
733	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Payara	229
734	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pimpinela	229
735	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ramón Peraza	229
736	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Papelón	230
737	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caño Delgadito	230
738	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital San Genaro de Boconoito	231
739	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antolín Tovar	231
740	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital San Rafael de Onoto	232
741	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Fe	232
742	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Thermo Morles	232
743	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Santa Rosalía	233
744	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Florida	233
745	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Sucre	234
746	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Concepción	234
747	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael de Palo Alzado	234
748	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Uvencio Antonio Velásquez	234
749	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Saguaz	234
750	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Villa Rosa	234
751	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Turén	235
752	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Canelones	235
753	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz	235
754	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Isidro Labrador	235
755	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mariño	236
756	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rómulo Gallegos	236
757	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Aerocuar	237
758	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tavera Acosta	237
759	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Río Caribe	238
760	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antonio José de Sucre	238
761	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Morro de Puerto Santo	238
762	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Puerto Santo	238
763	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan de Las Galdonas	238
764	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Pilar	239
765	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Rincón	239
766	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia General Francisco Antonio Vásquez	239
767	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guaraúnos	239
768	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tunapuicito	239
769	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Unión	239
770	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolívar	240
771	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Macarapana	240
772	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Catalina	240
773	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rosa	240
774	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Teresa	240
775	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Yaguaraparo	241
776	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Paujil	241
777	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad	241
778	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Araya	242
779	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chacopata	242
780	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manicuare	242
781	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tunapuy	243
782	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Campo Elías	243
783	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Irapa	244
784	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Campo Claro	244
785	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Marabal	244
786	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio de Irapa	244
787	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Soro	244
788	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cumanacoa	246
789	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arenas	246
790	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Aricagua	246
791	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cocollar	246
792	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Fernando	246
793	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Lorenzo	246
794	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Villa Frontado (Muelle de Cariaco)	247
795	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Catuaro	247
796	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rendón	247
797	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz	247
798	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa María	247
799	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altagracia	248
800	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ayacucho	248
801	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Inés	248
802	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Valentín Valiente	248
803	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan	248
804	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Raúl Leoni	248
805	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Gran Mariscal 	248
806	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Güiria	249
807	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bideau	249
808	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cristóbal Colón	249
809	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Punta de Piedras	249
810	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ayacucho	252
811	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rivas Berti	252
812	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Pedro del Río	252
813	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolívar	253
814	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Palotal	253
815	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Vicente Gómez	253
816	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Isaías Medina Angarita	253
817	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cárdenas	254
818	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Amenodoro Rangel Lamús	254
819	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Florida	254
820	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Fernández Feo	256
821	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alberto Adriani	256
822	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santo Domingo	256
823	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia García de Hevia	258
824	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boca de Grita	258
825	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Antonio Páez	258
826	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Independencia	261
827	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Germán Roscio	261
828	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Román Cárdenas	261
829	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jáuregui	262
830	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Emilio Constantino Guerrero	262
831	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monseñor Miguel Antonio Salas	262
832	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Junín	263
833	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Petrólea	263
834	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Quinimarí	263
835	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bramón	263
836	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad	264
837	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cipriano Castro	264
838	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Felipe Rugeles	264
839	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertador	265
840	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Don Emeterio Ochoa	265
841	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Doradas	265
842	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Joaquín de Navay	265
843	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Lobatera	266
844	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Constitución	266
845	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Panamericano	278
846	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Palmita	278
847	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedro María Ureña	268
848	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Nueva Arcadia	268
849	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Samuel Darío Maldonado	270
850	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boconó	270
851	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Hernández	270
852	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Concordia	271
853	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedro María Morantes	271
854	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Juan Bautista	271
855	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Sebastián	271
856	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Dr. Francisco Romero Lobo	271
857	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sucre	274
858	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Eleazar López Contreras	274
859	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Pablo	274
860	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia  Uribante	276
861	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cárdenas	276
862	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Pablo Peñaloza	276
863	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Potosí	276
864	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Isabel	279
865	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Araguaney	279
866	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Jagüito	279
867	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Esperanza	279
868	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Boconó	280
869	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Carmen	280
870	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mosquey	280
871	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ayacucho	280
872	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Burbusay	280
873	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia General Rivas	280
874	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guaramacal	280
875	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Vega de Guaramacal	280
876	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monseñor Jáuregui	280
877	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rafael Rangel	280
878	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Miguel	280
879	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José	280
880	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabana Grande	281
881	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cheregüé	281
882	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Granados	281
883	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chejendé	282
884	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arnoldo Gabaldón	282
885	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolivia	282
886	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carrillo	282
887	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cegarra	282
888	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Salvador Ulloa	282
889	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José	282
890	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carache	283
891	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cuicas	283
892	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Concepción	283
893	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Panamericana	283
894	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz	283
895	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Escuque	284
896	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Unión	284
897	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabana Libre	284
898	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rita	284
899	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Socorro	285
900	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antonio José de Sucre	285
901	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Caprichos	285
902	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Campo Elías	286
903	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arnoldo Gabaldón	286
904	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Apolonia	287
905	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Progreso 	287
906	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Ceiba 	287
907	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tres de Febrero 	287
908	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Dividive	288
909	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Santa	288
910	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Agua Caliente	288
911	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Cenizo	288
912	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Valerita	288
913	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monte Carmelo	289
914	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Buena Vista	289
915	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa María del Horcón	289
916	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Motatán	290
917	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Baño	290
918	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jalisco	290
919	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pampán	291
920	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Flor de Patria	291
921	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Paz	291
922	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Ana	291
923	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pampanito	292
924	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Concepción	292
925	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pampanito II	292
926	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Betijoque	293
927	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Pueblita	293
928	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Cedros	293
929	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Gregorio Hernández	293
930	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carvajal	294
931	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antonio Nicolás Briceño	294
932	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Campo Alegre	294
933	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Leonardo Suárez	294
934	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sabana de Mendoza	295
935	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Paraíso	295
936	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Junín	295
937	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Valmore Rodríguez	295
938	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Andrés Linares	296
939	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chiquinquirá	296
940	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cristóbal Mendoza	296
941	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cruz Carrillo	296
942	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Matriz	296
943	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monseñor Carrillo	296
944	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tres Esquinas	296
945	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Quebrada	297
946	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cabimbú	297
947	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jajó	297
948	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Mesa	297
949	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santiago	297
950	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tuñame	297
951	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juan Ignacio Montilla	298
952	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Beatriz	298
953	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mercedes Díaz	298
954	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Luis	298
955	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Puerta	298
956	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mendoza	298
957	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caraballeda	299
958	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carayaca	299
959	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caruao	299
960	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Catia La Mar	299
961	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Junko	299
962	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Guaira	299
963	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Macuto	299
964	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Maiquetía	299
965	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Naiguatá	299
966	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urimare	299
967	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carlos Soublette	299
968	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Bruzual	302
969	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Campo Elías	302
970	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Nirgua	308
971	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Salom	308
972	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Temerla	308
973	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Peña	309
974	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Andrés	309
975	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital San Felipe	310
976	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Albarico	310
977	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Javier	310
978	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Capital Veroes	313
979	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Guayabo	313
980	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Isla de Toas	314
981	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monagas	314
982	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Timoteo	315
983	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia General Urdaneta	315
984	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertador	315
985	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Guanipa Matos	315
986	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Marcelino Briceño	315
987	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pueblo Nuevo	315
988	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ambrosio	316
989	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carmen Herrera	316
990	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Germán Ríos Linares	316
991	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Rosa	316
992	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jorge Hernández	316
993	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rómulo Betancourt	316
994	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Benito	316
995	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Arístides Calvani	316
996	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Punta Gorda	316
997	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Encontrados	317
998	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Udón Pérez	317
999	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Carlos del Zulia	318
1000	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Moralito	318
1001	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Bárbara	318
1002	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Cruz del Zulia	318
1003	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Urribarri	318
1004	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Simón Rodríguez	319
1005	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Carlos Quevedo	319
1006	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Francisco Javier Pulgar	319
1007	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Concepción	320
1008	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Ramón Yepes	320
1009	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Mariano Parra León	320
1010	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José	320
1011	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jesús María Semprún	321
1012	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Barí	321
1013	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Concepción	322
1014	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Andrés Bello	322
1015	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chiquinquirá	322
1016	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Carmelo	322
1017	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Potreritos	322
1018	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alonso de Ojeda	323
1019	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad	323
1020	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Campo Lara	323
1021	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Eleazar López Contreras	323
1022	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Venezuela	323
1023	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Libertad	324
1024	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bartolomé de las Casas	324
1025	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Río Negro	324
1026	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José de Perijá	324
1027	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Rafael	325
1028	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Sierrita	325
1029	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Las Parcelas	325
1030	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Luis de Vicente	325
1031	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monseñor Marcos Sergio Godoy	325
1032	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ricaurte	325
1033	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Tamare	325
1034	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Antonio Borjas Romero	326
1035	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bolívar	326
1036	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cacique Mara	326
1037	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Caracciolo Parra Pérez	326
1038	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cecilio Acosta	326
1039	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Cristo de Aranza	326
1040	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Coquivacoa	326
1041	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Chiquinquirá	326
1042	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Francisco Eugenio Bustamante	326
1043	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Idelfonso Vásquez	326
1044	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Juana de Avila	326
1045	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Luis Hurtado Higuera	326
1046	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Dagnino	326
1047	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Olegario Villalobos	326
1048	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Raúl Leoni	326
1049	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Lucía	326
1050	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Venancio Pulgar	326
1051	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Isidro	326
1052	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Altagracia	327
1053	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Ana María Campos	327
1054	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Faría	327
1055	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Antonio	327
1056	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San José	327
1057	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sinamaica	328
1058	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Alta Guajira	328
1059	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Elías Sánchez Rubio	328
1060	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Guajira	328
1061	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Rosario	329
1062	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Donaldo García	329
1063	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Sixto Zambrano	329
1064	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia San Francisco	330
1065	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Bajo/	330
1066	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Domitila Flores	330
1067	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Francisco Ochoa	330
1068	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Los Cortijos	330
1069	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Marcial Hernández	330
1070	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Jose Domingo Rus	330
1071	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Santa Rita	331
1072	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Mene	331
1073	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia José Cenovio Urribarri	331
1074	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Pedro Lucas Urribarri	331
1075	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Manuel Manrique	332
1076	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rafael María Baralt	332
1077	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rafael Urdaneta	332
1078	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Bobures	333
1079	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia El Batey	333
1080	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Gibraltar	333
1081	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Heras	333
1082	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Monseñor Arturo Celestino Alvarez	333
1083	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rómulo Gallegos	333
1084	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia La Victoria	334
1085	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Rafael Urdaneta	334
1086	1	2014-01-26 04:37:26.924415	\N	\N	Parroquia Raúl Cuenca	334
\.


--
-- Name: sas_parroquia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jelitox
--

SELECT pg_catalog.setval('sas_parroquia_id_seq', 1086, true);


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
-- Name: sas_ciudad_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: jelitox
--

ALTER TABLE ONLY sas_ciudad
    ADD CONSTRAINT sas_ciudad_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES sas_pais_estado(id) ON DELETE SET NULL;


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
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

