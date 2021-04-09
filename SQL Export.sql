--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.21
-- Dumped by pg_dump version 9.6.21

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


--
-- Name: f_delete_from_perkawinan(); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.f_delete_from_perkawinan() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
	IF MOD(OLD.id, 2) = 0 THEN
		DELETE FROM public.perkawinans as pk
		WHERE pk.id = OLD.id - 1;
	ELSIF MOD(OLD.id, 2) = 1 THEN
		DELETE FROM public.perkawinans as pk
		WHERE pk.id = OLD.id + 1;
	END IF;
	
	RETURN NEW;
END; $$;


ALTER FUNCTION public.f_delete_from_perkawinan() OWNER TO siternak;

--
-- Name: f_insert_from_perkawinan(); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.f_insert_from_perkawinan() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
	INSERT INTO public.perkawinans(necktag, necktag_psg, tgl, created_at, updated_at)
	VALUES (NEW.necktag_psg, NEW.necktag, NEW.tgl, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
	
	RETURN NEW;
END; $$;


ALTER FUNCTION public.f_insert_from_perkawinan() OWNER TO siternak;

--
-- Name: f_update_from_perkawinan(); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.f_update_from_perkawinan() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
	IF MOD(new.id, 2) = 0 THEN
		UPDATE public.perkawinans
		SET necktag = new.necktag_psg,
			necktag_psg = new.necktag,
			tgl = new.tgl,
			updated_at = new.updated_at
		WHERE id = new.id - 1;
	ELSIF MOD(OLD.id, 2) = 1 THEN
		UPDATE public.perkawinans
		SET necktag = new.necktag_psg,
			necktag_psg = new.necktag,
			tgl = new.tgl,
			updated_at = new.updated_at
		WHERE id = new.id + 1;
	END IF;
	
	RETURN NEW;
END; $$;


ALTER FUNCTION public.f_update_from_perkawinan() OWNER TO siternak;

--
-- Name: f_update_from_ternak(); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.f_update_from_ternak() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
    IF NOT EXISTS (
		SELECT 1 FROM public.perkawinans 
		WHERE (necktag = NEW.necktag_ayah or necktag_psg = NEW.necktag_ayah)
		AND (necktag = NEW.necktag_ibu or necktag_psg = NEW.necktag_ibu)
	) AND NEW.necktag_ayah IS NOT NULL AND NEW.necktag_ibu IS NOT NULL THEN
	  INSERT INTO public.perkawinans(necktag, necktag_psg, tgl, created_at, updated_at)
	  VALUES (NEW.necktag_ayah, NEW.necktag_ibu, CURRENT_DATE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
      
	  INSERT INTO public.perkawinans(necktag, necktag_psg, tgl, created_at, updated_at)
	  VALUES (NEW.necktag_ibu, NEW.necktag_ayah, CURRENT_DATE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
	END IF;
	
	IF NEW.kematian_id IS NOT NULL THEN
		UPDATE public.ternaks
		SET status_ada = false
		WHERE necktag = NEW.necktag;
	END IF;
	RETURN NEW;
END; $$;


ALTER FUNCTION public.f_update_from_ternak() OWNER TO siternak;

--
-- Name: rp_penyakit(integer); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.rp_penyakit(val integer) RETURNS TABLE(necktag character, tgl_sakit date, obat character varying, lama_sakit integer, keterangan character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	select rp.necktag, rp.tgl_sakit, rp.obat, 
		rp.lama_sakit, rp.keterangan 
	from public.riwayat_penyakits as rp
	join public.ternaks as tr on tr.necktag = rp.necktag
	join public.penyakits as pk on pk.id = rp.penyakit_id
	where rp.penyakit_id = val
	order by tgl_sakit asc;
END; $$;


ALTER FUNCTION public.rp_penyakit(val integer) OWNER TO siternak;

--
-- Name: rp_ternak(character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.rp_ternak(val character) RETURNS TABLE(nama_penyakit character varying, tgl_sakit date, obat character varying, lama_sakit integer, keterangan character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	select pk.nama_penyakit, rp.tgl_sakit, rp.obat, 
		rp.lama_sakit, rp.keterangan 
	from public.riwayat_penyakits as rp
	join public.ternaks as tr on tr.necktag = rp.necktag
	join public.penyakits as pk on pk.id = rp.penyakit_id
	where rp.necktag = val
	order by tgl_sakit asc;
END; $$;


ALTER FUNCTION public.rp_ternak(val character) OWNER TO siternak;

--
-- Name: search_child(character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.search_child(val character) RETURNS TABLE(necktag character, jenis_kelamin character varying, jenis_ras character varying, tgl_lahir date, blood character, peternakan character varying, ayah character, ibu character)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	select tx.necktag, tx.jenis_kelamin, ras.jenis_ras, tx.tgl_lahir, tx.blood, ptk.nama_peternakan,
		tx.necktag_ayah, tx.necktag_ibu
	from public.ternaks as tx
	join public.ras on ras.id = tx.ras_id
	join public.peternakans as ptk on ptk.id = tx.peternakan_id
	where (tx.necktag_ayah = val or tx.necktag_ibu = val);
END; $$;


ALTER FUNCTION public.search_child(val character) OWNER TO siternak;

--
-- Name: search_gchild(character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.search_gchild(val character) RETURNS TABLE(necktag character, jenis_kelamin character varying, jenis_ras character varying, tgl_lahir date, blood character, peternakan character varying, ayah character, ibu character)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	select distinct tr.necktag, tr.jenis_kelamin, ras.jenis_ras, tr.tgl_lahir, tr.blood, ptk.nama_peternakan,
		tr.necktag_ayah as ayah, tr.necktag_ibu as ibu
	from public.ternaks as tr, (select tx.necktag
		from public.ternaks as tx
		where tx.necktag_ayah = val or tx.necktag_ibu = val) as tq, public.ras, public.peternakans as ptk 
	where ras.id = tr.ras_id
	and (tr.necktag_ayah = tq.necktag or tr.necktag_ibu = tq.necktag)
	and ptk.id = tr.peternakan_id;
END; $$;


ALTER FUNCTION public.search_gchild(val character) OWNER TO siternak;

--
-- Name: search_gparent(character, character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.search_gparent(n1 character, n2 character) RETURNS TABLE(necktag character, jenis_kelamin character varying, jenis_ras character varying, tgl_lahir date, blood character, peternakan character varying, ayah character, ibu character)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	select distinct ty.necktag, ty.jenis_kelamin, ras.jenis_ras, ty.tgl_lahir, ty.blood, ptk.nama_peternakan,
		ty.necktag_ayah as ayah, ty.necktag_ibu as ibu 
	from public.ternaks as ty, (SELECT t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
		FROM public.ternaks AS t1
		WHERE t1.necktag = n1 OR t1.necktag = n2) as tq, public.ras, public.peternakans as ptk
	where (ty.necktag = tq.ayah or ty.necktag = tq.ibu)
	and ras.id = ty.ras_id and ptk.id = ty.peternakan_id;
END; $$;


ALTER FUNCTION public.search_gparent(n1 character, n2 character) OWNER TO siternak;

--
-- Name: search_inst(character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.search_inst(val character) RETURNS TABLE(necktag character, jenis_kelamin character varying, jenis_ras character varying, tgl_lahir date, blood character, peternakan character varying, ayah character, ibu character)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, t1.blood, ptk.nama_peternakan, t1.necktag_ayah as ayah, t1.necktag_ibu as ibu 
	FROM public.ternaks AS t1, public.ras, public.peternakans as ptk
	WHERE t1.necktag = val and ras.id = t1.ras_id and ptk.id = t1.peternakan_id;
END; $$;


ALTER FUNCTION public.search_inst(val character) OWNER TO siternak;

--
-- Name: search_parent(character, character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.search_parent(n1 character, n2 character) RETURNS TABLE(necktag character, jenis_kelamin character varying, jenis_ras character varying, tgl_lahir date, blood character, peternakan character varying, ayah character, ibu character)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, t1.blood, ptk.nama_peternakan, t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
	FROM public.ternaks AS t1, public.ras, public.peternakans as ptk
	WHERE ras.id = t1.ras_id AND (t1.necktag = n1 OR t1.necktag = n2) and ptk.id = t1.peternakan_id;
END; $$;


ALTER FUNCTION public.search_parent(n1 character, n2 character) OWNER TO siternak;

--
-- Name: search_sibling(character, character, character); Type: FUNCTION; Schema: public; Owner: siternak
--

CREATE FUNCTION public.search_sibling(val character, n1 character, n2 character) RETURNS TABLE(necktag character, jenis_kelamin character varying, jenis_ras character varying, tgl_lahir date, blood character, peternakan character varying, ayah character, ibu character)
    LANGUAGE plpgsql
    AS $$
BEGIN
	RETURN QUERY
	select t2.necktag, t2.jenis_kelamin, ras.jenis_ras, t2.tgl_lahir, t2.blood, ptk.nama_peternakan,
		t2.necktag_ayah as ayah, t2.necktag_ibu as ibu
	from public.ternaks as t2, public.ras, public.peternakans as ptk
	where (t2.necktag_ayah = n1 or t2.necktag_ibu = n2)
	and ras.id = t2.ras_id and t2.necktag != val and ptk.id = t2.peternakan_id;
END; $$;


ALTER FUNCTION public.search_sibling(val character, n1 character, n2 character) OWNER TO siternak;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: kematians; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.kematians (
    id bigint NOT NULL,
    tgl_kematian date NOT NULL,
    waktu_kematian time(0) without time zone,
    penyebab character varying(255),
    kondisi character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.kematians OWNER TO siternak;

--
-- Name: kematians_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.kematians_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.kematians_id_seq OWNER TO siternak;

--
-- Name: kematians_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.kematians_id_seq OWNED BY public.kematians.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO siternak;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.migrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO siternak;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.oauth_access_tokens (
    id character varying(100) NOT NULL,
    user_id bigint,
    client_id bigint NOT NULL,
    name character varying(255),
    scopes text,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_access_tokens OWNER TO siternak;

--
-- Name: oauth_auth_codes; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.oauth_auth_codes (
    id character varying(100) NOT NULL,
    user_id bigint NOT NULL,
    client_id bigint NOT NULL,
    scopes text,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_auth_codes OWNER TO siternak;

--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.oauth_clients (
    id bigint NOT NULL,
    user_id bigint,
    name character varying(255) NOT NULL,
    secret character varying(100),
    redirect text NOT NULL,
    personal_access_client boolean NOT NULL,
    password_client boolean NOT NULL,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_clients OWNER TO siternak;

--
-- Name: oauth_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.oauth_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oauth_clients_id_seq OWNER TO siternak;

--
-- Name: oauth_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.oauth_clients_id_seq OWNED BY public.oauth_clients.id;


--
-- Name: oauth_personal_access_clients; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.oauth_personal_access_clients (
    id bigint NOT NULL,
    client_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_personal_access_clients OWNER TO siternak;

--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.oauth_personal_access_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oauth_personal_access_clients_id_seq OWNER TO siternak;

--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.oauth_personal_access_clients_id_seq OWNED BY public.oauth_personal_access_clients.id;


--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.oauth_refresh_tokens (
    id character varying(100) NOT NULL,
    access_token_id character varying(100) NOT NULL,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


ALTER TABLE public.oauth_refresh_tokens OWNER TO siternak;

--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO siternak;

--
-- Name: pemiliks; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.pemiliks (
    id bigint NOT NULL,
    ktp character(16) NOT NULL,
    nama_pemilik character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.pemiliks OWNER TO siternak;

--
-- Name: pemiliks_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.pemiliks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pemiliks_id_seq OWNER TO siternak;

--
-- Name: pemiliks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.pemiliks_id_seq OWNED BY public.pemiliks.id;


--
-- Name: penyakits; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.penyakits (
    id bigint NOT NULL,
    nama_penyakit character varying(50) NOT NULL,
    ket_penyakit character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.penyakits OWNER TO siternak;

--
-- Name: penyakits_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.penyakits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.penyakits_id_seq OWNER TO siternak;

--
-- Name: penyakits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.penyakits_id_seq OWNED BY public.penyakits.id;


--
-- Name: perkawinans; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.perkawinans (
    id bigint NOT NULL,
    necktag character(6) NOT NULL,
    necktag_psg character(6) NOT NULL,
    tgl date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.perkawinans OWNER TO siternak;

--
-- Name: perkawinans_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.perkawinans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.perkawinans_id_seq OWNER TO siternak;

--
-- Name: perkawinans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.perkawinans_id_seq OWNED BY public.perkawinans.id;


--
-- Name: peternakans; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.peternakans (
    id bigint NOT NULL,
    nama_peternakan character varying(100) NOT NULL,
    keterangan character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.peternakans OWNER TO siternak;

--
-- Name: peternakans_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.peternakans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.peternakans_id_seq OWNER TO siternak;

--
-- Name: peternakans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.peternakans_id_seq OWNED BY public.peternakans.id;


--
-- Name: ras; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.ras (
    id bigint NOT NULL,
    jenis_ras character varying(50) NOT NULL,
    ket_ras character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ras OWNER TO siternak;

--
-- Name: ras_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.ras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ras_id_seq OWNER TO siternak;

--
-- Name: ras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.ras_id_seq OWNED BY public.ras.id;


--
-- Name: riwayat_penyakits; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.riwayat_penyakits (
    id bigint NOT NULL,
    penyakit_id bigint NOT NULL,
    necktag character(6) NOT NULL,
    tgl_sakit date,
    obat character varying(50),
    lama_sakit integer,
    keterangan character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.riwayat_penyakits OWNER TO siternak;

--
-- Name: riwayat_penyakits_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.riwayat_penyakits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.riwayat_penyakits_id_seq OWNER TO siternak;

--
-- Name: riwayat_penyakits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.riwayat_penyakits_id_seq OWNED BY public.riwayat_penyakits.id;


--
-- Name: ternaks; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.ternaks (
    necktag character(6) NOT NULL,
    pemilik_id bigint,
    peternakan_id bigint NOT NULL,
    ras_id bigint NOT NULL,
    kematian_id bigint,
    jenis_kelamin character varying(20) NOT NULL,
    tgl_lahir date,
    bobot_lahir double precision,
    pukul_lahir time(0) without time zone,
    lama_dikandungan integer,
    lama_laktasi integer,
    tgl_lepas_sapih date,
    blood character(1) NOT NULL,
    necktag_ayah character(6),
    necktag_ibu character(6),
    bobot_tubuh double precision,
    panjang_tubuh double precision,
    tinggi_tubuh double precision,
    cacat_fisik character varying(255),
    ciri_lain character varying(255),
    status_ada boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.ternaks OWNER TO siternak;

--
-- Name: users; Type: TABLE; Schema: public; Owner: siternak
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    username character varying(255) NOT NULL,
    role character varying(50) DEFAULT 'peternak'::character varying NOT NULL,
    peternakan_id bigint,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password_first character varying(255),
    password character varying(255) NOT NULL,
    register_from_admin boolean DEFAULT false NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO siternak;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: siternak
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO siternak;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: siternak
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: kematians id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.kematians ALTER COLUMN id SET DEFAULT nextval('public.kematians_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: oauth_clients id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_clients_id_seq'::regclass);


--
-- Name: oauth_personal_access_clients id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_personal_access_clients ALTER COLUMN id SET DEFAULT nextval('public.oauth_personal_access_clients_id_seq'::regclass);


--
-- Name: pemiliks id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.pemiliks ALTER COLUMN id SET DEFAULT nextval('public.pemiliks_id_seq'::regclass);


--
-- Name: penyakits id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.penyakits ALTER COLUMN id SET DEFAULT nextval('public.penyakits_id_seq'::regclass);


--
-- Name: perkawinans id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.perkawinans ALTER COLUMN id SET DEFAULT nextval('public.perkawinans_id_seq'::regclass);


--
-- Name: peternakans id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.peternakans ALTER COLUMN id SET DEFAULT nextval('public.peternakans_id_seq'::regclass);


--
-- Name: ras id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ras ALTER COLUMN id SET DEFAULT nextval('public.ras_id_seq'::regclass);


--
-- Name: riwayat_penyakits id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.riwayat_penyakits ALTER COLUMN id SET DEFAULT nextval('public.riwayat_penyakits_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: kematians; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.kematians VALUES (1, '2020-09-16', '06:15:00', 'Perut kembung', 'Tidak baik', '2020-09-18 14:38:39', '2020-09-18 14:53:12');
INSERT INTO public.kematians VALUES (2, '2020-10-23', '14:06:00', 'Penyakit kuku dan mulut', 'Baik', '2020-10-26 14:07:30', '2020-10-26 14:14:05');


--
-- Name: kematians_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.kematians_id_seq', 2, true);


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.migrations VALUES (17, '2016_06_01_000001_create_oauth_auth_codes_table', 1);
INSERT INTO public.migrations VALUES (18, '2016_06_01_000002_create_oauth_access_tokens_table', 1);
INSERT INTO public.migrations VALUES (19, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1);
INSERT INTO public.migrations VALUES (20, '2016_06_01_000004_create_oauth_clients_table', 1);
INSERT INTO public.migrations VALUES (21, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1);
INSERT INTO public.migrations VALUES (22, '2020_03_17_022901_create_peternakans_table', 1);
INSERT INTO public.migrations VALUES (23, '2020_03_18_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (24, '2020_03_18_000001_create_password_resets_table', 1);
INSERT INTO public.migrations VALUES (25, '2020_03_18_073148_create_ras_table', 1);
INSERT INTO public.migrations VALUES (26, '2020_03_18_090352_create_pemiliks_table', 1);
INSERT INTO public.migrations VALUES (27, '2020_03_18_101955_create_penyakits_table', 1);
INSERT INTO public.migrations VALUES (28, '2020_03_18_103327_create_kematians_table', 1);
INSERT INTO public.migrations VALUES (29, '2020_03_18_124745_create_ternaks_table', 1);
INSERT INTO public.migrations VALUES (30, '2020_03_18_132641_create_perkawinans_table', 1);
INSERT INTO public.migrations VALUES (31, '2020_03_18_132735_create_riwayat_penyakits_table', 1);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.migrations_id_seq', 31, true);


--
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.oauth_access_tokens VALUES ('fd8061bf0e200f56776fbf766083ee2e0c3710b153dab3aec4b5f169f76c81cee168eb50e3bf6f78', 1, 1, 'appToken', '[]', false, '2020-07-12 17:03:22', '2020-07-12 17:03:22', '2021-07-12 17:03:22');
INSERT INTO public.oauth_access_tokens VALUES ('96714562d7fa806b6a1aac67b15b160137697e01043ccc946e6c040f23b77e9775931e6e9a71326a', 2, 1, 'appToken', '[]', false, '2020-07-12 17:05:29', '2020-07-12 17:05:29', '2021-07-12 17:05:29');
INSERT INTO public.oauth_access_tokens VALUES ('03493e0ed44ff2d9fb2122aa371e390ff6c5fc6a6ad8f18d63d1786333c46dc2259b3b10c0d8cfcf', 3, 1, 'appToken', '[]', false, '2020-07-12 17:09:30', '2020-07-12 17:09:30', '2021-07-12 17:09:30');
INSERT INTO public.oauth_access_tokens VALUES ('5c8d8242be1eab281312ff47fb340c060002fe2df5d0410936f8ebf1e201f1500cec24a8f21fafa9', 1, 1, 'appToken', '[]', false, '2020-07-12 17:27:04', '2020-07-12 17:27:04', '2021-07-12 17:27:04');
INSERT INTO public.oauth_access_tokens VALUES ('2ca13ba0e17ca92bedab48ce3aac8dcd03aad2a48152217c6c96529b8a75526b19f736065509cbe8', 3, 1, 'appToken', '[]', false, '2020-07-12 17:27:15', '2020-07-12 17:27:15', '2021-07-12 17:27:15');
INSERT INTO public.oauth_access_tokens VALUES ('e17842459b144fab89c2156042a10de13f0f4244eacdbb29993cff1ce61d6dc618e228ed1e1c6315', 2, 1, 'appToken', '[]', false, '2020-07-12 17:27:39', '2020-07-12 17:27:39', '2021-07-12 17:27:39');
INSERT INTO public.oauth_access_tokens VALUES ('080583742c5c9780086de1a11a4eca8cf9baff43d74ec42a5dbd71847fba5e4cb7c4f2daecb92a49', 1, 1, 'appToken', '[]', false, '2020-07-13 11:12:28', '2020-07-13 11:12:28', '2021-07-13 11:12:28');
INSERT INTO public.oauth_access_tokens VALUES ('bdc7d7a7e040ef910e9fa6c61edf098e92d0d8c80c270700f79d981e72a1aaad26e93d5f6d61e6b3', 1, 1, 'appToken', '[]', false, '2020-09-15 21:29:38', '2020-09-15 21:29:38', '2021-09-15 21:29:38');
INSERT INTO public.oauth_access_tokens VALUES ('a11a01005ae27c8253717f7f504754cc3ac0959241ace71ecb877dccb59d64f688e3d20ad7fd9080', 1, 1, 'appToken', '[]', false, '2020-09-15 22:44:37', '2020-09-15 22:44:37', '2021-09-15 22:44:37');
INSERT INTO public.oauth_access_tokens VALUES ('e7af7d0b7b6b375b466cfddafd730be39f55c57b54f934f0bb167f9bfb9de8ee0c69b5bc79a71e37', 1, 1, 'appToken', '[]', false, '2020-09-15 22:49:31', '2020-09-15 22:49:31', '2021-09-15 22:49:31');
INSERT INTO public.oauth_access_tokens VALUES ('728e63d8d53a5c5196aadb1dad4bcfd54b940954c0d8e049ee95c22a656a44a32565a1933ce49a2c', 1, 1, 'appToken', '[]', false, '2020-09-16 16:44:24', '2020-09-16 16:44:24', '2021-09-16 16:44:24');
INSERT INTO public.oauth_access_tokens VALUES ('c6b7fc94dec5cfba2abc32feb88b13d32fd999c63048de72b2f329ea60f9c7a83a5d744e4ca16b67', 1, 1, 'appToken', '[]', false, '2020-09-16 18:04:56', '2020-09-16 18:04:56', '2021-09-16 18:04:56');
INSERT INTO public.oauth_access_tokens VALUES ('f975c8f1fdfcc66b91bae613153b78e3876049f4c1245f1b21197dbfbe5d81cb198d42f206eacab8', 1, 1, 'appToken', '[]', false, '2020-09-16 20:45:08', '2020-09-16 20:45:08', '2021-09-16 20:45:08');
INSERT INTO public.oauth_access_tokens VALUES ('8212b5847c832b339038cad11fdf70d0e6842ffad241720b0da24d63080cc56228a960858fa61ced', 1, 1, 'appToken', '[]', false, '2020-09-16 20:50:55', '2020-09-16 20:50:55', '2021-09-16 20:50:55');
INSERT INTO public.oauth_access_tokens VALUES ('4a3899dd053cd0f444cea224e57eebb94732c47fcef524fd8c955a34de78c94f31740103bd2939e6', 1, 1, 'appToken', '[]', false, '2020-10-26 09:57:25', '2020-10-26 09:57:25', '2021-10-26 09:57:25');
INSERT INTO public.oauth_access_tokens VALUES ('2437cf998b637aa90f5a28b1190ee060e94c76b441ab4e1b7e1777c62076c8925e453da6e7a2277d', 1, 1, 'appToken', '[]', false, '2020-10-26 10:23:42', '2020-10-26 10:23:42', '2021-10-26 10:23:42');
INSERT INTO public.oauth_access_tokens VALUES ('c286b1f9a631f7c0373130f83d9617375cf91cd694563f7bd0e9b4a493cf403379daacd89917f390', 8, 1, 'appToken', '[]', false, '2020-10-26 10:44:57', '2020-10-26 10:44:57', '2021-10-26 10:44:57');
INSERT INTO public.oauth_access_tokens VALUES ('68587a1bf86297391d3ba2a1ff578005648973198cd95d6dda8b2809232a1080cdfa71e4a064815a', 3, 1, 'appToken', '[]', false, '2020-10-26 19:41:23', '2020-10-26 19:41:23', '2021-10-26 19:41:23');
INSERT INTO public.oauth_access_tokens VALUES ('49a7c006040a782448419e585386fbe7b99300d1fa3d61a3f7396d344625f8ddbf9a754ce491fc32', 3, 1, 'appToken', '[]', false, '2020-10-26 19:41:37', '2020-10-26 19:41:37', '2021-10-26 19:41:37');
INSERT INTO public.oauth_access_tokens VALUES ('f5603fd566ec381de029b58fefd0b2b2cbe7b28942cb3328b9d2d0cf7c0e84f691d6cde6ba47ad6c', 2, 1, 'appToken', '[]', false, '2020-10-26 20:01:38', '2020-10-26 20:01:38', '2021-10-26 20:01:38');
INSERT INTO public.oauth_access_tokens VALUES ('d587169ad17dc9ff3d66c7b929162ddd1309975b0e6af09a9f8296c0e9cd8b3632f70b8a4856d8bb', 1, 1, 'appToken', '[]', false, '2020-10-28 10:10:38', '2020-10-28 10:10:38', '2021-10-28 10:10:38');
INSERT INTO public.oauth_access_tokens VALUES ('c340e10bdc379ae98ee29764c8511446c0c9972d65697773460d96b5da021d6bae122503e666fe04', 2, 1, 'appToken', '[]', false, '2020-10-29 11:47:16', '2020-10-29 11:47:16', '2021-10-29 11:47:16');
INSERT INTO public.oauth_access_tokens VALUES ('5df481fc2ed152b29f0bfbc25316a66ab0ececd1b2eb0494aebf9c159d8f36ca39bd0353135a3b49', 3, 1, 'appToken', '[]', false, '2020-10-29 15:28:06', '2020-10-29 15:28:06', '2021-10-29 15:28:06');
INSERT INTO public.oauth_access_tokens VALUES ('58fb22c15740570f61ba3b3ea0b6ad40fb02d56ac603c8ce02cefc5870c6e9604a7f2af5bfc3be55', 1, 1, 'appToken', '[]', false, '2020-10-29 15:29:20', '2020-10-29 15:29:20', '2021-10-29 15:29:20');
INSERT INTO public.oauth_access_tokens VALUES ('c20b2a4afee8f5d39b6a3a88ae7da2af7609deec8789086b0ded0c3891d761ae1cdf6a1143f2953d', 2, 1, 'appToken', '[]', false, '2020-11-02 09:53:40', '2020-11-02 09:53:40', '2021-11-02 09:53:40');
INSERT INTO public.oauth_access_tokens VALUES ('bb807f71816f9a783ef7ec477ca04e0a028866c4c0b195e84d7294f296c25ee704d445e60c88de3c', 1, 1, 'appToken', '[]', false, '2020-11-02 11:46:52', '2020-11-02 11:46:52', '2021-11-02 11:46:52');
INSERT INTO public.oauth_access_tokens VALUES ('874ac5b4e3880fa4c202519d262450cf940232ccfd130f351422a3b746182431d783a38681079281', 12, 1, 'appToken', '[]', false, '2020-11-02 13:47:22', '2020-11-02 13:47:22', '2021-11-02 13:47:22');
INSERT INTO public.oauth_access_tokens VALUES ('1e986f24ac09c341658b8301857da993b2cf93c8140850d8db973562d99e6bee8fea130843589740', 1, 1, 'appToken', '[]', false, '2020-11-19 21:20:56', '2020-11-19 21:20:56', '2021-11-19 21:20:56');
INSERT INTO public.oauth_access_tokens VALUES ('87a89c743801057460a669cf32f3e42f68e9b0c148e2f94feb43e975355d3222efc85f3fdb28f202', 8, 1, 'appToken', '[]', false, '2020-12-21 17:47:24', '2020-12-21 17:47:24', '2021-12-21 17:47:24');
INSERT INTO public.oauth_access_tokens VALUES ('102b963aa6699f0398e034523aac3ae0055e7387c34f4864cbc6825c8d0e1215fcb4aff98a5a3e9a', 2, 1, 'appToken', '[]', false, '2020-12-21 17:51:28', '2020-12-21 17:51:28', '2021-12-21 17:51:28');
INSERT INTO public.oauth_access_tokens VALUES ('e98d97defe49116b0270a69d19c16cbace0f9763edeac870563a056305c6137ea5fffd643b0b635a', 1, 1, 'appToken', '[]', false, '2021-01-13 18:12:20', '2021-01-13 18:12:20', '2022-01-13 18:12:20');
INSERT INTO public.oauth_access_tokens VALUES ('8d68c4ac8c2cb7533880ffa1775d2ab46e851dcce6edaeddf726e126e308795688ff660cc55ef09d', 1, 1, 'appToken', '[]', false, '2021-01-13 18:12:53', '2021-01-13 18:12:53', '2022-01-13 18:12:53');
INSERT INTO public.oauth_access_tokens VALUES ('65777132ff47acaba9e215653f32a30b437dba76d588614e9a728293177e69769b89f9db08b03fb4', 1, 1, 'appToken', '[]', false, '2021-01-13 19:08:06', '2021-01-13 19:08:06', '2022-01-13 19:08:06');
INSERT INTO public.oauth_access_tokens VALUES ('b1a376ddd33951f16098c44bdef9beee07befda4033ed68b8bb9684a52b5bc664dd684088588d1f9', 8, 1, 'appToken', '[]', false, '2021-02-02 09:51:52', '2021-02-02 09:51:52', '2022-02-02 09:51:52');
INSERT INTO public.oauth_access_tokens VALUES ('6808799edb1858d7e13fad8bca0e719df7518ddcd88cbcf7a6b675f48bf16e1919970848080228f2', 8, 1, 'appToken', '[]', false, '2021-02-04 07:54:42', '2021-02-04 07:54:42', '2022-02-04 07:54:42');


--
-- Data for Name: oauth_auth_codes; Type: TABLE DATA; Schema: public; Owner: siternak
--



--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.oauth_clients VALUES (1, NULL, 'SITERNAK Personal Access Client', 'EmetO4zU8Z94bVh2i9CLFMyXMGZM83lB3N7ZLm27', 'http://localhost', true, false, false, '2020-07-12 16:35:47', '2020-07-12 16:35:47');
INSERT INTO public.oauth_clients VALUES (2, NULL, 'SITERNAK Password Grant Client', 'cWocXdk2Anzsm2AmbJSV6QCWXei4b7i7RTh8OGKf', 'http://localhost', false, true, false, '2020-07-12 16:35:47', '2020-07-12 16:35:47');


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.oauth_clients_id_seq', 2, true);


--
-- Data for Name: oauth_personal_access_clients; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.oauth_personal_access_clients VALUES (1, 1, '2020-07-12 16:35:47', '2020-07-12 16:35:47');


--
-- Name: oauth_personal_access_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.oauth_personal_access_clients_id_seq', 1, true);


--
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: public; Owner: siternak
--



--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: siternak
--



--
-- Data for Name: pemiliks; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.pemiliks VALUES (1, '3522110719999927', 'Anto Sudi', '2020-09-18 14:10:20', '2020-09-18 14:13:55');
INSERT INTO public.pemiliks VALUES (2, '1234567899011234', 'Muslimin', '2020-10-26 11:10:33', '2020-10-26 11:10:33');
INSERT INTO public.pemiliks VALUES (3, '3271046504930003', 'Wiranto', '2020-10-26 14:39:21', '2020-10-26 14:51:10');
INSERT INTO public.pemiliks VALUES (4, '3521022204990003', 'MININ', '2020-11-02 09:32:16', '2020-11-02 09:32:16');


--
-- Name: pemiliks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.pemiliks_id_seq', 4, true);


--
-- Data for Name: penyakits; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.penyakits VALUES (1, 'Herpes', 'Penyakit kulit akibat virus', '2020-09-18 13:47:50', '2020-09-18 13:56:55');
INSERT INTO public.penyakits VALUES (2, 'Antraks', 'Penyakit menular akut dan mematikan yang disebabkan bakteri Bacillus anthracis', '2020-10-26 14:57:58', '2020-10-26 15:32:47');


--
-- Name: penyakits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.penyakits_id_seq', 2, true);


--
-- Data for Name: perkawinans; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.perkawinans VALUES (1, 'Q4KeVF', 'dMAQ0q', '2020-01-23', '2020-09-18 22:28:22', '2020-09-18 22:33:16');
INSERT INTO public.perkawinans VALUES (2, 'dMAQ0q', 'DLdTk0', '2020-01-17', '2020-10-26 22:32:09', '2020-10-26 22:34:42');


--
-- Name: perkawinans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.perkawinans_id_seq', 2, true);


--
-- Data for Name: peternakans; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.peternakans VALUES (1, 'Ternak Jaya', 'Jalan Gumo', '2020-07-12 17:03:56', '2020-07-12 17:03:56');
INSERT INTO public.peternakans VALUES (2, 'Dukuh Abadi Lor', 'Jalan Dukuh Kupang Desa Meliwis', '2020-09-19 15:12:57', '2020-09-19 15:17:19');
INSERT INTO public.peternakans VALUES (4, 'Mangli Jaya', 'Puncu Kediri', '2020-10-26 11:11:38', '2020-10-26 11:11:38');
INSERT INTO public.peternakans VALUES (5, 'AYS FARM', 'Peternakan berbasis pariwisata.', '2020-10-27 17:28:34', '2020-10-27 17:28:34');
INSERT INTO public.peternakans VALUES (8, 'MININ GANG MAKAM', 'GANG MAKAM, SETRO, GRESIK', '2020-11-02 08:47:25', '2020-11-02 08:47:25');


--
-- Name: peternakans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.peternakans_id_seq', 8, true);


--
-- Data for Name: ras; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.ras VALUES (1, 'Alpen', 'Jenis kambing berukuran sedang hingga besar', '2020-09-18 13:11:39', '2020-09-18 13:26:27');
INSERT INTO public.ras VALUES (2, 'Etawa', 'Kambing Susu', '2020-10-26 11:16:33', '2020-10-26 11:16:33');
INSERT INTO public.ras VALUES (3, 'Jawa Randu', 'Persilangan antara kambing peranakan etawa dan kambing kacang', '2020-10-26 20:27:01', '2020-10-26 20:30:30');
INSERT INTO public.ras VALUES (4, 'Sanen', 'Lembah Sanen, Swiss', '2020-10-27 18:28:17', '2020-10-27 18:28:17');


--
-- Name: ras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.ras_id_seq', 16, true);


--
-- Data for Name: riwayat_penyakits; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.riwayat_penyakits VALUES (1, 1, 'Q4KeVF', '2020-09-18', 'Vitamin D', 11, 'Gejala gatal', '2020-09-18 20:55:21', '2020-09-18 21:02:37');
INSERT INTO public.riwayat_penyakits VALUES (2, 2, 'dMAQ0q', '2020-10-21', 'Antibiotik Doksisiklin', 10, NULL, '2020-10-26 20:56:36', '2020-10-26 21:02:25');


--
-- Name: riwayat_penyakits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.riwayat_penyakits_id_seq', 2, true);


--
-- Data for Name: ternaks; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.ternaks VALUES ('dMAQ0q', 1, 1, 1, NULL, 'Jantan', '2020-09-08', 4, '17:48:00', 5, 2, '2020-09-30', 'O', NULL, NULL, 7, 75, 40, 'Kaki hanya 3', NULL, true, '2020-09-18 17:49:04', '2020-09-18 20:51:27', NULL);
INSERT INTO public.ternaks VALUES ('DLdTk0', 3, 4, 3, NULL, 'Betina', '2019-10-27', 4, '21:11:00', 5, 2, '2020-10-18', 'O', 'dMAQ0q', 'Q4KeVF', 7, 75, 40, NULL, NULL, true, '2020-10-26 21:13:59', '2020-10-26 22:29:16', NULL);
INSERT INTO public.ternaks VALUES ('Q4KeVF', 1, 1, 1, NULL, 'Betina', '2020-09-01', 8, '09:30:00', 5, 2, '2020-11-01', 'O', NULL, NULL, 7, 75, 40, 'Kaki hanya 3', NULL, true, '2020-09-18 15:14:25', '2020-11-02 11:05:52', NULL);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: siternak
--

INSERT INTO public.users VALUES (3, 'Karno Sukro', 'karsukro', 'peternak', NULL, 'karno@gmail.com', NULL, NULL, '$2y$10$i.GoNSdpeBq.VnnZpBBD.OiInTpxdiIEG72O3ENIJijFHPdYos.oi', false, NULL, '2020-07-12 17:08:29', '2020-07-12 17:08:29');
INSERT INTO public.users VALUES (12, 'MINIM GANG MAKAM', 'miningangmakam', 'peternak', 8, 'dwi_yanto4687@yahoo.co.id', NULL, '0uOEtwap', '$2y$10$wZ75ucy9rkdK9/yGS7dL3e8yHbwgAQ0/RbT61iDSDuU6zX5POdISS', true, NULL, '2020-11-02 09:28:22', '2020-11-02 09:28:22');
INSERT INTO public.users VALUES (4, 'Indah Purwitasari', 'indahpw', 'peternak', 1, 'indahpw12@gmail.com', NULL, '72gt8aN7', '$2y$10$oaM5xstg3GjHhnt5BbBq.uwxZkkp5SCXkVe6ab6BnPH.CiHLz1MQu', true, NULL, '2020-09-19 09:25:44', '2020-09-19 09:25:44');
INSERT INTO public.users VALUES (5, 'Wikana Darwin Eka', 'wikanadr', 'peternak', 1, 'wikanadr45@gmail.com', NULL, 'PZeXrGYY', '$2y$10$dDubIeubdvKSnhgRGrLTq.snTdKZO4fOuY.snlWZ.OZ4OYQO7fe2u', true, NULL, '2020-09-19 14:37:33', '2020-09-19 14:43:44');
INSERT INTO public.users VALUES (7, 'Agung Sipayung', 'agungsipayung', 'admin', NULL, 'agungsipayung69@gmail.com', NULL, NULL, '$2y$10$5G26kHufx89e0jiN8qGPT.kuPKZNMG57prHoFhFNUnqeTWmmVfsgG', false, 'bsHBI8U1X7kUPvPTUM36oDORm3lP1WzAPTVfXLmiMp3IhDtsZkKa80sYqTNb', '2020-10-25 19:21:36', '2020-10-25 19:21:36');
INSERT INTO public.users VALUES (2, 'Anto Budiono', 'antobudiono', 'peternak', 1, 'anto.budi@gmail.com', NULL, 'iaBjXio7', '$2y$10$zYOzdbyoaWUkVSFvKhd30uSbcv7Obi8tSp8kbB47ZBEvPC1nL6FmK', true, 'ja12ytfOaFbSYiP5oUTEeGtCb8kbL9e3iWufoSzNnfEdagdZs8iDXTW0qYgV', '2020-07-12 17:04:33', '2020-10-25 23:50:15');
INSERT INTO public.users VALUES (1, 'Navinda Meutia', 'nmmutiaa', 'admin', NULL, 'nm.rosebw@gmail.com', NULL, NULL, '$2y$10$.ZaXbia/3nTZVHJSJTIrFuxTepathkdvnCODhjz1sjla2V4wNaoD.', false, 'l8dipzCd1xkVJdCIbeKXvIIpuqlEyAgBJPEhiHUY46DdRICLbk5pOCeLBA3L', '2020-07-12 16:36:40', '2020-10-25 23:11:45');
INSERT INTO public.users VALUES (6, 'Indah Purwitasari', 'indahpwt', 'peternak', NULL, 'indahpwt124@gmail.com', NULL, NULL, '$2y$10$EeCpj1EBwLKkDsddOkP5gORCkNyWyTq28nNX.7skwNaGBg.TsLuNq', false, NULL, '2020-10-25 14:43:01', '2020-10-25 14:43:01');
INSERT INTO public.users VALUES (8, 'Kusuma', 'akusuma', 'admin', NULL, 'akusuma31@gmail.com', NULL, NULL, '$2y$10$83hPQG3bOJ8Kmj25k7qCpu3OQxBNsKHjWzHZhSWp.6XAM7arwLPR.', false, NULL, '2020-10-25 19:37:39', '2020-10-25 19:37:39');
INSERT INTO public.users VALUES (10, 'Ahmad Yasir', 'ahmadyasirsun3', 'peternak', 5, 'ahmadyasir.ays@gmail.com', NULL, 'qeomtT1y', '$2y$10$Emd8hCRQfIGdG41FLvfV9eGOhnNumJ/wdwmXvBlbjVZDLyWRMPw56', true, NULL, '2020-10-27 17:33:12', '2020-10-27 17:33:12');
INSERT INTO public.users VALUES (11, 'Muslimin', 'muslimin', 'peternak', 4, 'muslimin.manglijaya@gmail.com', NULL, 'f4YRHWPJ', '$2y$10$0.wXsrMxJ3wbinwBla6yBOlcJiSrj05v3bAZ0UWiZoWp3UPbAEHSq', true, NULL, '2020-10-27 18:24:20', '2020-10-27 18:24:20');


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: siternak
--

SELECT pg_catalog.setval('public.users_id_seq', 12, true);


--
-- Name: kematians kematians_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.kematians
    ADD CONSTRAINT kematians_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: oauth_access_tokens oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: oauth_auth_codes oauth_auth_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_auth_codes
    ADD CONSTRAINT oauth_auth_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_clients oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_personal_access_clients oauth_personal_access_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_personal_access_clients
    ADD CONSTRAINT oauth_personal_access_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_refresh_tokens oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: pemiliks pemiliks_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.pemiliks
    ADD CONSTRAINT pemiliks_pkey PRIMARY KEY (id);


--
-- Name: penyakits penyakits_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.penyakits
    ADD CONSTRAINT penyakits_pkey PRIMARY KEY (id);


--
-- Name: perkawinans perkawinans_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.perkawinans
    ADD CONSTRAINT perkawinans_pkey PRIMARY KEY (id);


--
-- Name: peternakans peternakans_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.peternakans
    ADD CONSTRAINT peternakans_pkey PRIMARY KEY (id);


--
-- Name: ras ras_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ras
    ADD CONSTRAINT ras_pkey PRIMARY KEY (id);


--
-- Name: riwayat_penyakits riwayat_penyakits_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.riwayat_penyakits
    ADD CONSTRAINT riwayat_penyakits_pkey PRIMARY KEY (id);


--
-- Name: ternaks ternaks_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ternaks
    ADD CONSTRAINT ternaks_pkey PRIMARY KEY (necktag);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: oauth_access_tokens_user_id_index; Type: INDEX; Schema: public; Owner: siternak
--

CREATE INDEX oauth_access_tokens_user_id_index ON public.oauth_access_tokens USING btree (user_id);


--
-- Name: oauth_auth_codes_user_id_index; Type: INDEX; Schema: public; Owner: siternak
--

CREATE INDEX oauth_auth_codes_user_id_index ON public.oauth_auth_codes USING btree (user_id);


--
-- Name: oauth_clients_user_id_index; Type: INDEX; Schema: public; Owner: siternak
--

CREATE INDEX oauth_clients_user_id_index ON public.oauth_clients USING btree (user_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: siternak
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: perkawinans perkawinans_necktag_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.perkawinans
    ADD CONSTRAINT perkawinans_necktag_foreign FOREIGN KEY (necktag) REFERENCES public.ternaks(necktag) ON DELETE CASCADE;


--
-- Name: riwayat_penyakits riwayat_penyakits_necktag_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.riwayat_penyakits
    ADD CONSTRAINT riwayat_penyakits_necktag_foreign FOREIGN KEY (necktag) REFERENCES public.ternaks(necktag) ON DELETE CASCADE;


--
-- Name: riwayat_penyakits riwayat_penyakits_penyakit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.riwayat_penyakits
    ADD CONSTRAINT riwayat_penyakits_penyakit_id_foreign FOREIGN KEY (penyakit_id) REFERENCES public.penyakits(id) ON DELETE CASCADE;


--
-- Name: ternaks ternaks_kematian_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ternaks
    ADD CONSTRAINT ternaks_kematian_id_foreign FOREIGN KEY (kematian_id) REFERENCES public.kematians(id) ON DELETE CASCADE;


--
-- Name: ternaks ternaks_pemilik_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ternaks
    ADD CONSTRAINT ternaks_pemilik_id_foreign FOREIGN KEY (pemilik_id) REFERENCES public.pemiliks(id) ON DELETE CASCADE;


--
-- Name: ternaks ternaks_peternakan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ternaks
    ADD CONSTRAINT ternaks_peternakan_id_foreign FOREIGN KEY (peternakan_id) REFERENCES public.peternakans(id) ON DELETE CASCADE;


--
-- Name: ternaks ternaks_ras_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.ternaks
    ADD CONSTRAINT ternaks_ras_id_foreign FOREIGN KEY (ras_id) REFERENCES public.ras(id) ON DELETE CASCADE;


--
-- Name: users users_peternakan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: siternak
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_peternakan_id_foreign FOREIGN KEY (peternakan_id) REFERENCES public.peternakans(id) ON DELETE CASCADE;


--
-- Name: TABLE kematians; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.kematians TO siternak_postgre;


--
-- Name: TABLE migrations; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.migrations TO siternak_postgre;


--
-- Name: TABLE oauth_access_tokens; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.oauth_access_tokens TO siternak_postgre;


--
-- Name: TABLE oauth_auth_codes; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.oauth_auth_codes TO siternak_postgre;


--
-- Name: TABLE oauth_clients; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.oauth_clients TO siternak_postgre;


--
-- Name: TABLE oauth_personal_access_clients; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.oauth_personal_access_clients TO siternak_postgre;


--
-- Name: TABLE oauth_refresh_tokens; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.oauth_refresh_tokens TO siternak_postgre;


--
-- Name: TABLE password_resets; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.password_resets TO siternak_postgre;


--
-- Name: TABLE pemiliks; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.pemiliks TO siternak_postgre;


--
-- Name: TABLE penyakits; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.penyakits TO siternak_postgre;


--
-- Name: TABLE perkawinans; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.perkawinans TO siternak_postgre;


--
-- Name: TABLE peternakans; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.peternakans TO siternak_postgre;


--
-- Name: TABLE ras; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.ras TO siternak_postgre;


--
-- Name: TABLE riwayat_penyakits; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.riwayat_penyakits TO siternak_postgre;


--
-- Name: TABLE ternaks; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.ternaks TO siternak_postgre;


--
-- Name: TABLE users; Type: ACL; Schema: public; Owner: siternak
--

GRANT ALL ON TABLE public.users TO siternak_postgre;


--
-- PostgreSQL database dump complete
--

