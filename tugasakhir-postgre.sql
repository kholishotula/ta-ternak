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