select * from public.ternaks;
select * from public.peternakans;
select * from public.peternaks;
select * from public.pemiliks;
select * from public.perkawinans;
select * from public.kematians;
select * from public.riwayat_penyakits;
select * from public.penyakits;
select * from public.migrations;
select * from public.users;
select * from public.oauth_access_tokens;
select * from public.oauth_auth_codes;
select * from public.oauth_clients;
select * from public.oauth_personal_access_clients;
select * from public.oauth_refresh_tokens;

drop table public.perkawinans;
drop table public.riwayat_penyakits;
drop table public.ternaks;
drop table public.peternaks;
drop table public.peternakans;
drop table public.pemiliks;
drop table public.penyakits;
drop table public.ras;
drop table public.kematians;


-- SEARCH
-- instance
CREATE OR REPLACE FUNCTION search_inst(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, blood char, peternakan varchar,
			  ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, t1.blood, ptk.nama_peternakan, t1.necktag_ayah as ayah, t1.necktag_ibu as ibu 
	FROM public.ternaks AS t1, public.ras, public.peternakans as ptk
	WHERE t1.necktag = val and ras.id = t1.ras_id and ptk.id = t1.peternakan_id;
END; $$
LANGUAGE PLPGSQL;

select * from public.search_inst('OEHCUq');
select * from public.search_inst('M68ONz');
select * from public.search_inst('4Wa5c4');

-- parent
CREATE OR REPLACE FUNCTION search_parent(n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, blood char, peternakan varchar,
			  ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	SELECT t1.necktag, t1.jenis_kelamin, ras.jenis_ras, 
		t1.tgl_lahir, t1.blood, ptk.nama_peternakan, t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
	FROM public.ternaks AS t1, public.ras, public.peternakans as ptk
	WHERE ras.id = t1.ras_id AND (t1.necktag = n1 OR t1.necktag = n2) and ptk.id = t1.peternakan_id;
END; $$
LANGUAGE PLPGSQL;
	
select * from public.search_parent('M68ONz', '4Wa5c4');

-- sibling
CREATE OR REPLACE FUNCTION search_sibling(val char, n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, blood char, peternakan varchar,
			  ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select t2.necktag, t2.jenis_kelamin, ras.jenis_ras, t2.tgl_lahir, t2.blood, ptk.nama_peternakan,
		t2.necktag_ayah as ayah, t2.necktag_ibu as ibu
	from public.ternaks as t2, public.ras, public.peternakans as ptk
	where (t2.necktag_ayah = n1 or t2.necktag_ibu = n2)
	and ras.id = t2.ras_id and t2.necktag != val and ptk.id = t2.peternakan_id;
END; $$
LANGUAGE PLPGSQL;

select * from public.search_sibling('OEHCUq', 'M68ONz', '4Wa5c4');

-- child
CREATE OR REPLACE FUNCTION search_child(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, blood char, peternakan varchar,
			  ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select tx.necktag, tx.jenis_kelamin, ras.jenis_ras, tx.tgl_lahir, tx.blood, ptk.nama_peternakan,
		tx.necktag_ayah, tx.necktag_ibu
	from public.ternaks as tx
	join public.ras on ras.id = tx.ras_id
	join public.peternakans as ptk on ptk.id = tx.peternakan_id
	where (tx.necktag_ayah = val or tx.necktag_ibu = val);
END; $$
LANGUAGE PLPGSQL;

select * from public.search_child('SrtEdd');
select * from public.search_child('QnUDYA');

-- grandparent
CREATE OR REPLACE FUNCTION search_gparent(n1 char, n2 char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, blood char, peternakan varchar,
			  ayah char, ibu char) AS $$
BEGIN
	RETURN QUERY
	select distinct ty.necktag, ty.jenis_kelamin, ras.jenis_ras, ty.tgl_lahir, ty.blood, ptk.nama_peternakan,
		ty.necktag_ayah as ayah, ty.necktag_ibu as ibu 
	from public.ternaks as ty, (SELECT t1.necktag_ayah as ayah, t1.necktag_ibu as ibu  
		FROM public.ternaks AS t1
		WHERE t1.necktag = n1 OR t1.necktag = n2) as tq, public.ras, public.peternakans as ptk
	where (ty.necktag = tq.ayah or ty.necktag = tq.ibu)
	and ras.id = ty.ras_id and ptk.id = ty.peternakan_id;
END; $$
LANGUAGE PLPGSQL;

select * from public.search_gparent('QnUDYA', 'kp6m7X');
select * from public.search_gparent('QnUDYA', 'jf0k5C');

-- grandchild
CREATE OR REPLACE FUNCTION search_gchild(val char) 
RETURNS TABLE (necktag char, jenis_kelamin varchar,
			  jenis_ras varchar, tgl_lahir date, blood char, peternakan varchar,
			  ayah char, ibu char) AS $$
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
END; $$
LANGUAGE PLPGSQL;

select * from public.search_gchild('SrtEdd');
select * from public.search_gchild('QnUDYA');

-- fungsi riwayat penyakit individu ternak
CREATE OR REPLACE FUNCTION rp_ternak(val char) 
RETURNS TABLE(nama_penyakit varchar, tgl_sakit date,
			 obat varchar, lama_sakit integer, keterangan varchar) AS $$
BEGIN
	RETURN QUERY
	select pk.nama_penyakit, rp.tgl_sakit, rp.obat, 
		rp.lama_sakit, rp.keterangan 
	from public.riwayat_penyakits as rp
	join public.ternaks as tr on tr.necktag = rp.necktag
	join public.penyakits as pk on pk.id = rp.penyakit_id
	where rp.necktag = val
	order by tgl_sakit asc;
END; $$
LANGUAGE PLPGSQL;

select * from public.rp_ternak('QnUDYA');

-- fungsi riwayat penyakit pada tiap penyakit
CREATE OR REPLACE FUNCTION rp_penyakit(val integer) 
RETURNS TABLE(necktag char, tgl_sakit date, obat varchar, 
			  lama_sakit integer, keterangan varchar) AS $$
BEGIN
	RETURN QUERY
	select rp.necktag, rp.tgl_sakit, rp.obat, 
		rp.lama_sakit, rp.keterangan 
	from public.riwayat_penyakits as rp
	join public.ternaks as tr on tr.necktag = rp.necktag
	join public.penyakits as pk on pk.id = rp.penyakit_id
	where rp.penyakit_id = val
	order by tgl_sakit asc;
END; $$
LANGUAGE PLPGSQL;

select * from public.rp_penyakit(1);

-- trigger - ternak (add to perkawinan, change status)
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
END; $$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_from_ternak 
ON public.ternaks;

CREATE TRIGGER update_from_ternak
  AFTER INSERT OR UPDATE
  ON public.ternaks
  FOR EACH ROW
  EXECUTE PROCEDURE f_update_from_ternak();
  
-- trigger - perkawinan (add reverse to perkawinan)
CREATE OR REPLACE FUNCTION f_insert_from_perkawinan()
  RETURNS trigger AS $$
BEGIN
	IF pg_trigger_depth() <> 1 THEN
        RETURN NEW;
    END IF;
	
	INSERT INTO public.perkawinans(necktag, necktag_psg, tgl, created_at, updated_at)
	VALUES (NEW.necktag_psg, NEW.necktag, NEW.tgl, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
	
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

-- trigger - perkawinan (delete reverse from perkawinan)
CREATE OR REPLACE FUNCTION f_delete_from_perkawinan()
  RETURNS trigger AS $$
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
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS delete_from_perkawinan 
ON public.perkawinans;

CREATE TRIGGER delete_from_perkawinan
  AFTER DELETE
  ON public.perkawinans
  FOR EACH ROW
  EXECUTE PROCEDURE f_delete_from_perkawinan();
  
-- trigger - perkawinan (update reverse from perkawinan)
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
END; $$
LANGUAGE plpgsql; 

DROP TRIGGER IF EXISTS update_from_perkawinan 
ON public.perkawinans;

CREATE TRIGGER update_from_perkawinan
  AFTER UPDATE
  ON public.perkawinans
  FOR EACH ROW
  EXECUTE PROCEDURE f_update_from_perkawinan();
  

-- grafik ras						
select ras.jenis_ras as ras, coalesce(count(tr.necktag), 0) as jumlah
from public.ternaks as tr
right join public.ras on ras.id = tr.ras_id
where tr.status_ada = true
and tr.jenis_kelamin = 'Jantan'
group by ras.jenis_ras
order by ras.jenis_ras

-- tras not included
select ras.jenis_ras as ras, coalesce(count(tr.necktag), 0) as jumlah
from public.ternaks as tr
join public.ras on ras.id = tr.ras_id
where tr.status_ada = true
group by ras.jenis_ras
order by ras.jenis_ras

select *
from public.ternaks


