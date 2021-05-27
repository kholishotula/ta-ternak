--SEARCH
--instance
CREATE OR REPLACE FUNCTION search_inst(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		t1.necktag_ayah as ayah, t1.necktag_ibu as ibu 
	FROM public.ternaks AS t1, public.ras, public.pemiliks as pml, public.users as ptk
	WHERE t1.necktag = val and ras.id = t1.ras_id and pml.id = t1.pemilik_id and ptk.id = t1.user_id;
END; $$
LANGUAGE PLPGSQL;

-- spouse
CREATE OR REPLACE FUNCTION search_spouse(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		t1.necktag_ayah as ayah, t1.necktag_ibu as ibu 
	FROM public.ternaks AS t1, public.ras, public.pemiliks as pml, public.users as ptk, 
		(SELECT necktag_psg FROM public.perkawinans as kwn WHERE kwn.necktag = val) as psg
	WHERE t1.necktag = psg.necktag_psg and ras.id = t1.ras_id and pml.id = t1.pemilik_id and ptk.id = t1.user_id;
END; $$
LANGUAGE PLPGSQL;

--parent
CREATE OR REPLACE FUNCTION search_parent(n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
	FROM public.ternaks AS t1, public.ras, public.pemiliks as pml, public.users as ptk
	WHERE ras.id = t1.ras_id AND (t1.necktag = n1 OR t1.necktag = n2) and pml.id = t1.pemilik_id and ptk.id = t1.user_id;
END; $$
LANGUAGE PLPGSQL;

--sibling
CREATE OR REPLACE FUNCTION search_sibling(val char, n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select t2.necktag, t2.jenis_kelamin, ras.jenis_ras, t2.tgl_lahir,
		pml.nama_pemilik as pemilik, ptk.name as peternak,
		t2.necktag_ayah as ayah, t2.necktag_ibu as ibu
	from public.ternaks as t2, public.ras, public.pemiliks as pml, public.users as ptk
	where (t2.necktag_ayah = n1 or t2.necktag_ibu = n2)
	and ras.id = t2.ras_id and t2.necktag != val and pml.id = t2.pemilik_id and ptk.id = t2.user_id;
END; $$
LANGUAGE PLPGSQL;

--child
CREATE OR REPLACE FUNCTION search_child(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select tx.necktag, tx.jenis_kelamin, ras.jenis_ras, tx.tgl_lahir, 
		pml.nama_pemilik, ptk.name,
		tx.necktag_ayah, tx.necktag_ibu
	from public.ternaks as tx
	join public.ras on ras.id = tx.ras_id
	join public.pemiliks as pml on pml.id = tx.pemilik_id
	join public.users as ptk on ptk.id = tx.user_id
	where (tx.necktag_ayah = val or tx.necktag_ibu = val);
END; $$
LANGUAGE PLPGSQL;

--grandparent
CREATE OR REPLACE FUNCTION search_gparent(n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar, 
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select distinct ty.necktag, ty.jenis_kelamin, ras.jenis_ras,
		ty.tgl_lahir, pml.nama_pemilik, ptk.name,
		ty.necktag_ayah as ayah, ty.necktag_ibu as ibu 
	from public.ternaks as ty,
		(SELECT t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
			FROM public.ternaks AS t1
			WHERE t1.necktag = n1 OR t1.necktag = n2) as tq,
		public.ras,
		public.pemiliks as pml,
		public.users as ptk
	where (ty.necktag = tq.ayah or ty.necktag = tq.ibu)
	and ras.id = ty.ras_id and pml.id = ty.pemilik_id and ptk.id = ty.user_id;
END; $$
LANGUAGE PLPGSQL;

--grandchild
CREATE OR REPLACE FUNCTION search_gchild(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select distinct tr.necktag, tr.jenis_kelamin, ras.jenis_ras,
		tr.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		tr.necktag_ayah as ayah, tr.necktag_ibu as ibu
	from public.ternaks as tr, 
		(select tx.necktag
			from public.ternaks as tx
			where tx.necktag_ayah = val or tx.necktag_ibu = val) as tq,
		public.ras,
		public.pemiliks as pml,
		public.users as ptk 
	where ras.id = tr.ras_id
	and (tr.necktag_ayah = tq.necktag or tr.necktag_ibu = tq.necktag)
	and pml.id = tr.pemilik_id and ptk.id = tr.user_id;
END; $$
LANGUAGE PLPGSQL;

-- trigger - ternak (if add/edit ternak then add to perkawinan)
CREATE OR REPLACE FUNCTION f_update_from_ternak()
  	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
    IF NOT EXISTS (
		SELECT 1 FROM public.perkawinans 
		WHERE (necktag = NEW.necktag_ayah or necktag_psg = NEW.necktag_ayah)
		AND (necktag = NEW.necktag_ibu or necktag_psg = NEW.necktag_ibu)
	) AND NEW.necktag_ayah IS NOT NULL AND NEW.necktag_ibu IS NOT NULL THEN
	  INSERT INTO public.perkawinans(necktag, necktag_psg, tgl_kawin, created_at, updated_at)
	  VALUES (NEW.necktag_ayah, NEW.necktag_ibu, CURRENT_DATE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
      
	  INSERT INTO public.perkawinans(necktag, necktag_psg, tgl_kawin, created_at, updated_at)
	  VALUES (NEW.necktag_ibu, NEW.necktag_ayah, CURRENT_DATE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
	END IF;
	RETURN NEW;
END; $$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_from_ternak 
ON public.ternaks;

CREATE TRIGGER update_from_ternak
  	AFTER INSERT OR UPDATE
  	ON public.ternaks
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_update_from_ternak();

-- trigger - perkawinan (if add perkawinan then add reverse to perkawinan)
CREATE OR REPLACE FUNCTION f_insert_from_perkawinan()
  	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
	INSERT INTO public.perkawinans(necktag, necktag_psg, tgl_kawin, created_at, updated_at)
	VALUES (NEW.necktag_psg, NEW.necktag, NEW.tgl_kawin, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
	
	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS insert_from_perkawinan 
ON public.perkawinans;

CREATE TRIGGER insert_from_perkawinan
  	AFTER INSERT
  	ON public.perkawinans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_insert_from_perkawinan();

-- trigger - perkawinan (if delete perkawinan then delete reverse from perkawinan)
CREATE OR REPLACE FUNCTION f_delete_from_perkawinan()
  	RETURNS trigger AS $$
BEGIN
	IF MOD(OLD.id, 2) = 0 THEN
		DELETE FROM public.perkawinans as pk
		WHERE pk.id = OLD.id - 1;
	ELSEIF MOD(OLD.id, 2) = 1 THEN
		DELETE FROM public.perkawinans as pk
		WHERE pk.id = OLD.id + 1;
	END IF;
	
	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS delete_from_perkawinan 
ON public.perkawinans;

CREATE TRIGGER delete_from_perkawinan
  	AFTER DELETE
  	ON public.perkawinans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_delete_from_perkawinan();

-- trigger - perkawinan (if update perkawinan then update reverse from perkawinan)
CREATE OR REPLACE FUNCTION f_update_from_perkawinan()
  	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
	IF MOD(new.id, 2) = 0 THEN
		UPDATE public.perkawinans
		SET necktag = new.necktag_psg,
			necktag_psg = new.necktag,
			tgl_kawin = new.tgl_kawin,
			updated_at = new.updated_at
		WHERE id = new.id - 1;
	ELSIF MOD(new.id, 2) = 1 THEN
		UPDATE public.perkawinans
		SET necktag = new.necktag_psg,
			necktag_psg = new.necktag,
			tgl_kawin = new.tgl_kawin,
			updated_at = new.updated_at
		WHERE id = new.id + 1;
	END IF;
	
	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS update_from_perkawinan 
ON public.perkawinans;

CREATE TRIGGER update_from_perkawinan
  	AFTER UPDATE
  	ON public.perkawinans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_update_from_perkawinan();

-- trigger - kematian (if insert kematian then change kematian id and status to ternak)
CREATE OR REPLACE FUNCTION f_insert_from_kematian()
	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	UPDATE public.ternaks
	SET kematian_id = NEW.id,
		status_ada = false
	WHERE necktag = NEW.necktag;

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS insert_from_kematian 
ON public.kematians;

CREATE TRIGGER insert_from_kematian
  	AFTER INSERT
  	ON public.kematians
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_insert_from_kematian();

-- trigger - kematian (if update necktag kematian then change kematian id and status to ternak)
CREATE OR REPLACE FUNCTION f_update_from_kematian()
	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	IF OLD.necktag <> NEW.necktag THEN
		UPDATE public.ternaks
		SET kematian_id = null,
			status_ada = true
		WHERE necktag = OLD.necktag;

		UPDATE public.ternaks
		SET kematian_id = NEW.id,
			status_ada = false
		WHERE necktag = NEW.necktag;
	END IF;

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS update_from_kematian 
ON public.kematians;

CREATE TRIGGER update_from_kematian
  	AFTER UPDATE
  	ON public.kematians
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_update_from_kematian();

-- trigger - kematian (if delete kematian then change kematian id and status to ternak)
CREATE OR REPLACE FUNCTION f_delete_from_kematian()
	RETURNS trigger AS $$
BEGIN
	UPDATE public.ternaks
	SET kematian_id = null,
		status_ada = true
	WHERE necktag = OLD.necktag;

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS delete_from_kematian 
ON public.kematians;

CREATE TRIGGER delete_from_kematian
  	BEFORE DELETE
  	ON public.kematians
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_delete_from_kematian();

-- trigger - penjualan (if insert penjualan then change penjualan id and status to ternak)
CREATE OR REPLACE FUNCTION f_insert_from_penjualan()
	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	UPDATE public.ternaks
	SET penjualan_id = NEW.id,
		status_ada = false
	WHERE necktag = NEW.necktag;

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS insert_from_penjualan 
ON public.penjualans;

CREATE TRIGGER insert_from_penjualan
  	AFTER INSERT
  	ON public.penjualans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_insert_from_penjualan();

-- trigger - penjualan (if update necktag penjualan then change penjualan id and status to ternak)
CREATE OR REPLACE FUNCTION f_update_from_penjualan()
	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	IF OLD.necktag <> NEW.necktag THEN
		UPDATE public.ternaks
		SET penjualan_id = null,
			status_ada = true
		WHERE necktag = OLD.necktag;

		UPDATE public.ternaks
		SET penjualan_id = NEW.id,
			status_ada = false
		WHERE necktag = NEW.necktag;
	END IF;

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS update_from_penjualan 
ON public.penjualans;

CREATE TRIGGER update_from_penjualan
  	AFTER UPDATE
  	ON public.penjualans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_update_from_penjualan();

-- trigger - penjualan (if delete penjualan then change penjualan id and status to ternak)
CREATE OR REPLACE FUNCTION f_delete_from_penjualan()
	RETURNS trigger AS $$
BEGIN
	UPDATE public.ternaks
	SET penjualan_id = null,
		status_ada = true
	WHERE necktag = OLD.necktag;

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS delete_from_penjualan 
ON public.penjualans;

CREATE TRIGGER delete_from_penjualan
  	BEFORE DELETE
  	ON public.penjualans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_delete_from_penjualan();