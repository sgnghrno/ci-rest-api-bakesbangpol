# ci-rest-api-bakesbangpol
 Sebuah API dari Aplikasi Intelejen Bakesbangpol berbasis android.

LIST ENDPOINT:

LAPORAN ============================================================
[GET]
baseurl/api/laporan/all/ (select all laporan)
baseurl/api/laporan/all/limit/(num) (select all with limit)
baseurl/api/laporan/id_user/(num) (select laporan by id user)
baseurl/api/laporan/id_laporan/(num) (select laporan by id laporan)

USER ===============================================================
[GET]
baseurl/api/auth/all (select all user)
baseurl/api/auth/id_user/(num) (select user by id user)
baseurl/api/auth/level/(num) (select user by level)

PEMBERITAHUAN ======================================================
[GET]
baseurl/api/pemberitahuan/all (select all pemberitahuan)
baseurl/api/pemberitahuan/id_pemberitahuan/(num) (select pemberitahuan by id pemberitahuan)
baseurl/api/pemberitahuan/id_user/(num) (select pemberitahuan by id user)