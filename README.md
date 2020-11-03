# ci-rest-api-bakesbangpol
 Sebuah API dari Aplikasi Intelejen Bakesbangpol berbasis android.

LIST ENDPOINT:

LAPORAN ============================================================
[GET]
baseurl/api/laporan/all/ (select all laporan) <br>
baseurl/api/laporan/all/limit/(num) (select all with limit) <br>
baseurl/api/laporan/id_user/(num) (select laporan by id user) <br>
baseurl/api/laporan/id_laporan/(num) (select laporan by id laporan) <br>

USER ===============================================================
[GET]
baseurl/api/auth/all (select all user) <br> 
baseurl/api/auth/id_user/(num) (select user by id user) <br>
baseurl/api/auth/level/(num) (select user by level) <br>

PEMBERITAHUAN ======================================================
[GET]
baseurl/api/pemberitahuan/all (select all pemberitahuan) <br>
baseurl/api/pemberitahuan/id_pemberitahuan/(num) (select pemberitahuan by id pemberitahuan) <br>
baseurl/api/pemberitahuan/id_user/(num) (select pemberitahuan by id user) <br>