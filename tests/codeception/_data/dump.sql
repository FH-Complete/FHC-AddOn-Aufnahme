DELETE FROM public.tbl_person WHERE person_id IN (SELECT person_id FROM public.tbl_kontakt WHERE kontakttyp = 'email' AND kontakt = 'codeception@technikum-wien.at');
DELETE FROM public.tbl_kontakt WHERE kontakttyp = 'email' AND kontakt = 'codeception@technikum-wien.at';

SELECT SETVAL('tbl_person_person_id_seq', (SELECT MAX(person_id) +1 FROM tbl_person));