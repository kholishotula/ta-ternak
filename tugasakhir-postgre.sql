--SEARCH
--instance
CREATE OR REPLACE FUNCTION search_inst(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT tI.necktag, tI.jenis_kelamin, ras.jenis_ras, 
		tI.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		tI.necktag_ayah as ayah, tI.necktag_ibu as ibu 
	FROM public.ternaks AS tI, public.ras, public.pemiliks as pml, public.users as ptk
	WHERE tI.necktag = val and ras.id = tI.ras_id and pml.id = tI.pemilik_id and ptk.id = tI.user_id;
END; $$
LANGUAGE PLPGSQL;

--spouse
CREATE OR REPLACE FUNCTION search_spouse(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT tSp.necktag, tSp.jenis_kelamin, ras.jenis_ras, 
		tSp.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		tSp.necktag_ayah as ayah, tSp.necktag_ibu as ibu 
	FROM public.ternaks AS tSp, public.ras, public.pemiliks as pml, public.users as ptk, 
		(SELECT necktag_psg FROM public.perkawinans as kwn WHERE kwn.necktag = val) as psg
	WHERE tSp.necktag = psg.necktag_psg and ras.id = tSp.ras_id and pml.id = tSp.pemilik_id and ptk.id = tSp.user_id;
END; $$
LANGUAGE PLPGSQL;

--parent
CREATE OR REPLACE FUNCTION search_parent(n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT tP.necktag, tP.jenis_kelamin, ras.jenis_ras, 
		tP.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		tP.necktag_ayah as ayah, tP.necktag_ibu as ibu  
	FROM public.ternaks AS tP, public.ras, public.pemiliks as pml, public.users as ptk
	WHERE ras.id = tP.ras_id AND (tP.necktag = n1 OR tP.necktag = n2) and pml.id = tP.pemilik_id and ptk.id = tP.user_id;
END; $$
LANGUAGE PLPGSQL;

--sibling
CREATE OR REPLACE FUNCTION search_sibling(val char, n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select tSi.necktag, tSi.jenis_kelamin, ras.jenis_ras, tSi.tgl_lahir,
		pml.nama_pemilik as pemilik, ptk.name as peternak,
		tSi.necktag_ayah as ayah, tSi.necktag_ibu as ibu
	from public.ternaks as tSi, public.ras, public.pemiliks as pml, public.users as ptk
	where (tSi.necktag_ayah = n1 or tSi.necktag_ibu = n2)
	and ras.id = tSi.ras_id and tSi.necktag != val and pml.id = tSi.pemilik_id and ptk.id = tSi.user_id;
END; $$
LANGUAGE PLPGSQL;

--child
CREATE OR REPLACE FUNCTION search_child(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select tC.necktag, tC.jenis_kelamin, ras.jenis_ras, tC.tgl_lahir, 
		pml.nama_pemilik, ptk.name,
		tC.necktag_ayah, tC.necktag_ibu
	from public.ternaks as tC
	join public.ras on ras.id = tC.ras_id
	join public.pemiliks as pml on pml.id = tC.pemilik_id
	join public.users as ptk on ptk.id = tC.user_id
	where (tC.necktag_ayah = val or tC.necktag_ibu = val);
END; $$
LANGUAGE PLPGSQL;

--grandparent
CREATE OR REPLACE FUNCTION search_gparent(n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar, 
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select distinct tGP.necktag, tGP.jenis_kelamin, ras.jenis_ras,
		tGP.tgl_lahir, pml.nama_pemilik, ptk.name,
		tGP.necktag_ayah as ayah, tGP.necktag_ibu as ibu 
	from public.ternaks as tGP,
		(SELECT t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
			FROM public.ternaks AS t1
			WHERE t1.necktag = n1 OR t1.necktag = n2) as tq,
		public.ras,
		public.pemiliks as pml,
		public.users as ptk
	where (tGP.necktag = tq.ayah or tGP.necktag = tq.ibu)
	and ras.id = tGP.ras_id and pml.id = tGP.pemilik_id and ptk.id = tGP.user_id;
END; $$
LANGUAGE PLPGSQL;

--grandchild
CREATE OR REPLACE FUNCTION search_gchild(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, pemilik varchar,
			  peternak varchar, ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select distinct tGC.necktag, tGC.jenis_kelamin, ras.jenis_ras,
		tGC.tgl_lahir, pml.nama_pemilik as pemilik, ptk.name as peternak,
		tGC.necktag_ayah as ayah, tGC.necktag_ibu as ibu
	from public.ternaks as tGC, 
		(select tx.necktag
			from public.ternaks as tx
			where tx.necktag_ayah = val or tx.necktag_ibu = val) as tq,
		public.ras,
		public.pemiliks as pml,
		public.users as ptk 
	where ras.id = tGC.ras_id
	and (tGC.necktag_ayah = tq.necktag or tGC.necktag_ibu = tq.necktag)
	and pml.id = tGC.pemilik_id and ptk.id = tGC.user_id;
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

-- LOG

-- Penjualan
-- after insert
CREATE OR REPLACE FUNCTION f_log_ins_penjualan()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'insert', 'penjualans', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_ins_penjualan 
ON public.penjualans;

CREATE TRIGGER log_ins_penjualan
  	AFTER INSERT
  	ON public.penjualans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_ins_penjualan();

-- after update
CREATE OR REPLACE FUNCTION f_log_upd_penjualan()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'update', 'penjualans', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_upd_penjualan 
ON public.penjualans;

CREATE TRIGGER log_upd_penjualan
  	AFTER UPDATE
  	ON public.penjualans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_upd_penjualan();

-- after delete
CREATE OR REPLACE FUNCTION f_log_del_penjualan()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	OPEN cur_user(OLD.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'delete', 'penjualans', OLD.id, CURRENT_TIMESTAMP);

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_del_penjualan 
ON public.penjualans;

CREATE TRIGGER log_del_penjualan
  	AFTER DELETE
  	ON public.penjualans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_del_penjualan();

-- Kematian
-- after insert
CREATE OR REPLACE FUNCTION f_log_ins_kematian()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'insert', 'kematians', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_ins_kematian 
ON public.kematians;

CREATE TRIGGER log_ins_kematian
  	AFTER INSERT
  	ON public.kematians
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_ins_kematian();

-- after update
CREATE OR REPLACE FUNCTION f_log_upd_kematian()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'update', 'kematians', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_upd_kematian 
ON public.kematians;

CREATE TRIGGER log_upd_kematian
  	AFTER UPDATE
  	ON public.kematians
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_upd_kematian();

-- after delete
CREATE OR REPLACE FUNCTION f_log_del_kematian()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	OPEN cur_user(OLD.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'delete', 'kematians', OLD.id, CURRENT_TIMESTAMP);

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_del_kematian 
ON public.kematians;

CREATE TRIGGER log_del_kematian
  	AFTER DELETE
  	ON public.kematians
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_del_kematian();

-- Perkembangan
-- after insert
CREATE OR REPLACE FUNCTION f_log_ins_perkembangan()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'insert', 'perkembangans', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_ins_perkembangan 
ON public.perkembangans;

CREATE TRIGGER log_ins_perkembangan
  	AFTER INSERT
  	ON public.perkembangans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_ins_perkembangan();

-- after update
CREATE OR REPLACE FUNCTION f_log_upd_perkembangan()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'update', 'perkembangans', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_upd_perkembangan 
ON public.perkembangans;

CREATE TRIGGER log_upd_perkembangan
  	AFTER UPDATE
  	ON public.perkembangans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_upd_perkembangan();

-- after delete
CREATE OR REPLACE FUNCTION f_log_del_perkembangan()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	OPEN cur_user(OLD.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'delete', 'perkembangans', OLD.id, CURRENT_TIMESTAMP);

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_del_perkembangan 
ON public.perkembangans;

CREATE TRIGGER log_del_perkembangan
  	AFTER DELETE
  	ON public.perkembangans
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_del_perkembangan();

-- Riwayat Penyakit
-- after insert
CREATE OR REPLACE FUNCTION f_log_ins_riwayat_penyakit()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'insert', 'riwayat_penyakits', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_ins_riwayat_penyakit 
ON public.riwayat_penyakits;

CREATE TRIGGER log_ins_riwayat_penyakit
  	AFTER INSERT
  	ON public.riwayat_penyakits
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_ins_riwayat_penyakit();

-- after update
CREATE OR REPLACE FUNCTION f_log_upd_riwayat_penyakit()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	OPEN cur_user(NEW.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'update', 'riwayat_penyakits', NEW.id, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_upd_riwayat_penyakit 
ON public.riwayat_penyakits;

CREATE TRIGGER log_upd_riwayat_penyakit
  	AFTER UPDATE
  	ON public.riwayat_penyakits
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_upd_riwayat_penyakit();

-- after delete
CREATE OR REPLACE FUNCTION f_log_del_riwayat_penyakit()
	RETURNS trigger AS $$
DECLARE
	id_user integer;
	rec_user record;
	cur_user cursor (id char(6)) FOR
		SELECT user_id
		FROM public.ternaks
		WHERE necktag = id;
BEGIN
	OPEN cur_user(OLD.necktag);
	LOOP
		FETCH cur_user into rec_user;
		EXIT WHEN NOT FOUND;
		id_user := rec_user.user_id;
	END LOOP;
	CLOSE cur_user;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (id_user, 'delete', 'riwayat_penyakits', OLD.id, CURRENT_TIMESTAMP);

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_del_riwayat_penyakit 
ON public.riwayat_penyakits;

CREATE TRIGGER log_del_riwayat_penyakit
  	AFTER DELETE
  	ON public.riwayat_penyakits
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_del_riwayat_penyakit();

-- Ternak
-- after insert
CREATE OR REPLACE FUNCTION f_log_ins_ternak()
	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (NEW.user_id, 'insert', 'ternaks', NEW.necktag, CURRENT_TIMESTAMP);

	RETURN NEW;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_ins_ternak 
ON public.ternaks;

CREATE TRIGGER log_ins_ternak
  	AFTER INSERT
  	ON public.ternaks
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_ins_ternak();

-- after update
CREATE OR REPLACE FUNCTION f_log_upd_ternak()
	RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;

	IF OLD.deleted_at IS NOT NULL THEN
		INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
		VALUES (NEW.user_id, 'restore', 'ternaks', NEW.necktag, CURRENT_TIMESTAMP);
		RETURN NEW;
	ELSIF NEW.deleted_at IS NOT NULL THEN
		INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
		VALUES (NEW.user_id, 'soft delete', 'ternaks', NEW.necktag, CURRENT_TIMESTAMP);
		RETURN NEW;
	ELSE
		INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
		VALUES (NEW.user_id, 'update', 'ternaks', NEW.necktag, CURRENT_TIMESTAMP);
		RETURN NEW;
	END IF;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_upd_ternak 
ON public.ternaks;

CREATE TRIGGER log_upd_ternak
  	AFTER UPDATE
  	ON public.ternaks
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_upd_ternak();

-- after delete
CREATE OR REPLACE FUNCTION f_log_del_ternak()
	RETURNS trigger AS $$
BEGIN
	INSERT INTO public.logs(user_id, aktivitas, tabel, pk_tabel, waktu)
	VALUES (OLD.user_id, 'force delete', 'ternaks', OLD.necktag, CURRENT_TIMESTAMP);

	RETURN OLD;
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS log_del_ternak 
ON public.ternaks;

CREATE TRIGGER log_del_ternak
  	AFTER DELETE
  	ON public.ternaks
  	FOR EACH ROW
  	EXECUTE PROCEDURE f_log_del_ternak();