--
-- PostgreSQL database dump
--

\restrict WVtlf1KgrhHixRytxRbPtZxRjidwECZrnfwkat2aHewg5blqjjydWcNgtlZuDAy

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.6

-- Started on 2026-03-02 14:41:06

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 223 (class 1255 OID 16791)
-- Name: demandevehicule(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.demandevehicule() RETURNS void
    LANGUAGE plpgsql
    AS $$
   DECLARE

   BEGIN
   Insert into demande(datereserv,numero, datedebut, matricule, notype, immat, duree,
dateretoureffectif, etat)
	VALUES('15/12/2025',2,'15/12/2025','VU2U2U31U',2,"null","initial");
   END;
$$;


ALTER FUNCTION public.demandevehicule() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 221 (class 1259 OID 16510)
-- Name: demande; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.demande (
    datereserv date NOT NULL,
    numero integer NOT NULL,
    datedebut date,
    matricule integer,
    notype integer,
    duree integer,
    dateretoureffectif date,
    etat character varying(50),
    immat character varying(10)
);


ALTER TABLE public.demande OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16530)
-- Name: location; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.location (
    numero integer NOT NULL,
    "dateDebut" date,
    "kmDebut" integer,
    "dateRetour" date,
    "kmRetour" integer,
    immatriculation integer
);


ALTER TABLE public.location OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16488)
-- Name: personne; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personne (
    matricule integer NOT NULL,
    nom character varying(20),
    prenom character varying(20),
    mdp character varying(50),
    telephone integer,
    "noService" integer
);


ALTER TABLE public.personne OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16478)
-- Name: service; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.service (
    numero integer NOT NULL,
    libelle character varying(20)
);


ALTER TABLE public.service OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16483)
-- Name: type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.type (
    numero integer NOT NULL,
    libelle character varying(20)
);


ALTER TABLE public.type OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16498)
-- Name: vehicule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vehicule (
    marque character varying,
    modele character varying,
    "noType" integer,
    immat character varying NOT NULL
);


ALTER TABLE public.vehicule OWNER TO postgres;

--
-- TOC entry 4827 (class 0 OID 16510)
-- Dependencies: 221
-- Data for Name: demande; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.demande (datereserv, numero, datedebut, matricule, notype, duree, dateretoureffectif, etat, immat) FROM stdin;
2025-10-15	1	2025-10-20	1001	1	3	2025-10-23	En cours	AB123CD
2025-10-16	2	2025-10-22	1002	2	5	2025-10-27	Validée	CD456EF
2025-10-17	3	2025-10-18	1003	3	1	2025-10-19	Annulée	1234XY
\.


--
-- TOC entry 4828 (class 0 OID 16530)
-- Dependencies: 222
-- Data for Name: location; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.location (numero, "dateDebut", "kmDebut", "dateRetour", "kmRetour", immatriculation) FROM stdin;
\.


--
-- TOC entry 4825 (class 0 OID 16488)
-- Dependencies: 219
-- Data for Name: personne; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personne (matricule, nom, prenom, mdp, telephone, "noService") FROM stdin;
1001	Dupont	Jean	motdepasse1	612345678	1
1002	Martin	Sophie	motdepasse2	687654321	2
1003	Bernard	Pierre	motdepasse3	655555555	3
1004	Durand	Marie	motdepasse4	699999999	1
\.


--
-- TOC entry 4823 (class 0 OID 16478)
-- Dependencies: 217
-- Data for Name: service; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.service (numero, libelle) FROM stdin;
1	Informatique
2	Ressources Humaines
3	Logistique
4	Comptabilité
\.


--
-- TOC entry 4824 (class 0 OID 16483)
-- Dependencies: 218
-- Data for Name: type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.type (numero, libelle) FROM stdin;
1	Voiture
2	Camionnette
3	Moto
4	Véhicule utilitaire
\.


--
-- TOC entry 4826 (class 0 OID 16498)
-- Dependencies: 220
-- Data for Name: vehicule; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vehicule (marque, modele, "noType", immat) FROM stdin;
Renault	Clio	1	AB123CD
Peugeot	Partner	4	CD456EF
Yamaha	MT-07	3	1234XY
Citroën	Berlingo	2	EF789GH
\.


--
-- TOC entry 4672 (class 2606 OID 16534)
-- Name: location cp_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.location
    ADD CONSTRAINT cp_pk PRIMARY KEY (numero);


--
-- TOC entry 4670 (class 2606 OID 16514)
-- Name: demande demande_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.demande
    ADD CONSTRAINT demande_pkey PRIMARY KEY (datereserv, numero);


--
-- TOC entry 4666 (class 2606 OID 16492)
-- Name: personne personne_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personne
    ADD CONSTRAINT personne_pkey PRIMARY KEY (matricule);


--
-- TOC entry 4662 (class 2606 OID 16482)
-- Name: service service_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.service
    ADD CONSTRAINT service_pkey PRIMARY KEY (numero);


--
-- TOC entry 4664 (class 2606 OID 16487)
-- Name: type type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.type
    ADD CONSTRAINT type_pkey PRIMARY KEY (numero);


--
-- TOC entry 4668 (class 2606 OID 16504)
-- Name: vehicule vehicule_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vehicule
    ADD CONSTRAINT vehicule_pkey PRIMARY KEY (immat);


--
-- TOC entry 4675 (class 2606 OID 16525)
-- Name: demande demande_immat_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.demande
    ADD CONSTRAINT demande_immat_fkey FOREIGN KEY (immat) REFERENCES public.vehicule(immat);


--
-- TOC entry 4676 (class 2606 OID 16515)
-- Name: demande demande_matricule_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.demande
    ADD CONSTRAINT demande_matricule_fkey FOREIGN KEY (matricule) REFERENCES public.personne(matricule);


--
-- TOC entry 4677 (class 2606 OID 16520)
-- Name: demande demande_notype_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.demande
    ADD CONSTRAINT demande_notype_fkey FOREIGN KEY (notype) REFERENCES public.type(numero);


--
-- TOC entry 4673 (class 2606 OID 16493)
-- Name: personne personne_noService_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personne
    ADD CONSTRAINT "personne_noService_fkey" FOREIGN KEY ("noService") REFERENCES public.service(numero);


--
-- TOC entry 4674 (class 2606 OID 16505)
-- Name: vehicule vehicule_noType_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vehicule
    ADD CONSTRAINT "vehicule_noType_fkey" FOREIGN KEY ("noType") REFERENCES public.type(numero);


-- Completed on 2026-03-02 14:41:06

--
-- PostgreSQL database dump complete
--

\unrestrict WVtlf1KgrhHixRytxRbPtZxRjidwECZrnfwkat2aHewg5blqjjydWcNgtlZuDAy

