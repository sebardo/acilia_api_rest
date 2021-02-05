--
-- PostgreSQL database dump
--

-- Dumped from database version 10.15 (Ubuntu 10.15-0ubuntu0.18.04.1)
-- Dumped by pg_dump version 10.15 (Ubuntu 10.15-0ubuntu0.18.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner:
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner:
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: category; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.category (
                                 id integer NOT NULL,
                                 name character varying(255) NOT NULL,
                                 description text
);


ALTER TABLE public.category OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.category_id_seq OWNER TO postgres;

--
-- Name: oauth2_access_token; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth2_access_token (
                                            identifier character(80) NOT NULL,
                                            client character varying(32) NOT NULL,
                                            expiry timestamp(0) without time zone NOT NULL,
                                            user_identifier character varying(128) DEFAULT NULL::character varying,
                                            scopes text,
                                            revoked boolean NOT NULL
);


ALTER TABLE public.oauth2_access_token OWNER TO postgres;

--
-- Name: COLUMN oauth2_access_token.expiry; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_access_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN oauth2_access_token.scopes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_access_token.scopes IS '(DC2Type:oauth2_scope)';


--
-- Name: oauth2_authorization_code; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth2_authorization_code (
                                                  identifier character(80) NOT NULL,
                                                  client character varying(32) NOT NULL,
                                                  expiry timestamp(0) without time zone NOT NULL,
                                                  user_identifier character varying(128) DEFAULT NULL::character varying,
                                                  scopes text,
                                                  revoked boolean NOT NULL
);


ALTER TABLE public.oauth2_authorization_code OWNER TO postgres;

--
-- Name: COLUMN oauth2_authorization_code.expiry; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_authorization_code.expiry IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN oauth2_authorization_code.scopes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_authorization_code.scopes IS '(DC2Type:oauth2_scope)';


--
-- Name: oauth2_client; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth2_client (
                                      identifier character varying(32) NOT NULL,
                                      secret character varying(128) DEFAULT NULL::character varying,
                                      redirect_uris text,
                                      grants text,
                                      scopes text,
                                      active boolean NOT NULL,
                                      allow_plain_text_pkce boolean DEFAULT false NOT NULL
);


ALTER TABLE public.oauth2_client OWNER TO postgres;

--
-- Name: COLUMN oauth2_client.redirect_uris; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_client.redirect_uris IS '(DC2Type:oauth2_redirect_uri)';


--
-- Name: COLUMN oauth2_client.grants; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_client.grants IS '(DC2Type:oauth2_grant)';


--
-- Name: COLUMN oauth2_client.scopes; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_client.scopes IS '(DC2Type:oauth2_scope)';


--
-- Name: oauth2_refresh_token; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oauth2_refresh_token (
                                             identifier character(80) NOT NULL,
                                             access_token character(80) DEFAULT NULL::bpchar,
                                             expiry timestamp(0) without time zone NOT NULL,
                                             revoked boolean NOT NULL
);


ALTER TABLE public.oauth2_refresh_token OWNER TO postgres;

--
-- Name: COLUMN oauth2_refresh_token.expiry; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.oauth2_refresh_token.expiry IS '(DC2Type:datetime_immutable)';


--
-- Name: product; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.product (
                                id integer NOT NULL,
                                category_id integer,
                                name character varying(255) NOT NULL,
                                price numeric(10,2) NOT NULL,
                                currency character varying(255) DEFAULT NULL::character varying,
                                featured boolean NOT NULL
);


ALTER TABLE public.product OWNER TO postgres;

--
-- Name: product_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.product_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_id_seq OWNER TO postgres;

--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: oauth2_access_token; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: oauth2_authorization_code; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: oauth2_client; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.oauth2_client VALUES ('de2396e3ee0bc41c1c60fb1658be96ec', 'a576c347cb938debb1c11b28e08080ad06d71d433a68f62ec762e210355ec1efdb9118d95070780efc9e66b2b232e77b27d41127402cc7a1c9c71f4347a8e583', NULL, NULL, NULL, true, false);


--
-- Data for Name: oauth2_refresh_token; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.category_id_seq', 51, true);


--
-- Name: product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.product_id_seq', 50, true);


--
-- Name: category category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: oauth2_access_token oauth2_access_token_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_access_token
    ADD CONSTRAINT oauth2_access_token_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_authorization_code oauth2_authorization_code_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_authorization_code
    ADD CONSTRAINT oauth2_authorization_code_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_client oauth2_client_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_client
    ADD CONSTRAINT oauth2_client_pkey PRIMARY KEY (identifier);


--
-- Name: oauth2_refresh_token oauth2_refresh_token_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_refresh_token
    ADD CONSTRAINT oauth2_refresh_token_pkey PRIMARY KEY (identifier);


--
-- Name: product product_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- Name: idx_454d9673c7440455; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_454d9673c7440455 ON public.oauth2_access_token USING btree (client);


--
-- Name: idx_4dd90732b6a2dd68; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_4dd90732b6a2dd68 ON public.oauth2_refresh_token USING btree (access_token);


--
-- Name: idx_509fef5fc7440455; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_509fef5fc7440455 ON public.oauth2_authorization_code USING btree (client);


--
-- Name: idx_d34a04ad12469de2; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_d34a04ad12469de2 ON public.product USING btree (category_id);


--
-- Name: oauth2_access_token fk_454d9673c7440455; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_access_token
    ADD CONSTRAINT fk_454d9673c7440455 FOREIGN KEY (client) REFERENCES public.oauth2_client(identifier) ON DELETE CASCADE;


--
-- Name: oauth2_refresh_token fk_4dd90732b6a2dd68; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_refresh_token
    ADD CONSTRAINT fk_4dd90732b6a2dd68 FOREIGN KEY (access_token) REFERENCES public.oauth2_access_token(identifier) ON DELETE SET NULL;


--
-- Name: oauth2_authorization_code fk_509fef5fc7440455; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oauth2_authorization_code
    ADD CONSTRAINT fk_509fef5fc7440455 FOREIGN KEY (client) REFERENCES public.oauth2_client(identifier) ON DELETE CASCADE;


--
-- Name: product fk_d34a04ad12469de2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product
    ADD CONSTRAINT fk_d34a04ad12469de2 FOREIGN KEY (category_id) REFERENCES public.category(id);


--
-- PostgreSQL database dump complete
--
