<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['midlleware' => 'web'], function() {
	//auth
	Auth::routes();
	// Auth::routes(['verify' => true]);

	//index
	Route::get('/', 'Auth\LoginController@showLoginForm');
	Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	Route::post('register', 'Auth\RegisterController@create');
	Route::get('/home', 'HomeController@index')->middleware('auth')->name('home');
	// Route::get('/home', 'HomeController@index')->middleware('auth', 'verified')->name('home');

	Route::get('wilayah/kabupaten/{prov_id}', 'WilayahController@getKabupaten');
	Route::get('wilayah/kecamatan/{kab_id}', 'WilayahController@getKecamatan');

	//--------------------- admin --------------------------------------
	// Route::prefix('admin')->middleware('can:isAdmin', 'auth', 'verified')->group(function(){
	Route::prefix('admin')->middleware('can:isAdmin', 'auth')->group(function(){
		//dashboard
		Route::get('/', 'Admin\HomeController@index')->name('admin');

		
		Route::namespace('Admin')->name('admin.')->group(function(){
			// search
			Route::get('search', 'HomeController@search')->name('search');

			// profil
			Route::get('profile', 'ProfileController@index')->name('profile');
			Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
			Route::put('profile/edit', 'ProfileController@update')->name('profile.update');
			Route::post('password/change', 'ProfileController@postChangePassword')->name('password.update');

			//data
			Route::resource('ternak', 'TernakController')->except(['create']);
			Route::get('ternaktrash', 'TernakController@trash')->name('ternak.trash');
			Route::get('ternak/restore/{id}', 'TernakController@restore')->name('ternak.restore');
			Route::get('ternakrestore', 'TernakController@restoreAll')->name('ternak.restoreAll');
			Route::delete('ternak/fdelete/{id}', 'TernakController@fdelete')->name('ternak.fdelete');
			Route::delete('ternakfdelete', 'TernakController@fdeleteAll')->name('ternak.fdeleteAll');
			Route::resource('ras', 'RasController')->except(['create', 'show']);
			Route::resource('riwayat', 'RiwayatPenyakitController')->except(['create', 'show']);
			Route::resource('kematian', 'KematianController')->except(['create', 'show']);
			Route::resource('pemilik', 'PemilikController')->except(['create']);
			Route::resource('grup-peternak', 'GrupPeternakController')->except(['create', 'show']);
			Route::resource('perkawinan', 'PerkawinanController')->except(['create', 'show']);
			Route::get('perkawinan/pasangan/{id}', 'PerkawinanController@getPasangan');
			Route::resource('penjualan', 'PenjualanController')->except(['create', 'show']);
			Route::resource('perkembangan', 'PerkembanganController')->except(['create']);

			// data peternak
			Route::resource('peternak', 'PeternakController')->except(['create', 'show']);

			// data peternak yang belum terverifikasi
			Route::get('verifikasi', 'VerifikasiController@index')->name('verifikasi');
			Route::get('verifikasi/users', 'VerifikasiController@getUsers')->name('verifikasi.users');
			Route::get('verifikasi/users/{id}', 'VerifikasiController@verifyUser');

			//barcode
			Route::get('barcode', 'BarcodeController@index')->name('barcode');
			Route::get('barcode/pdf', 'BarcodeController@generatePdf')->name('barcode.pdf');

			//perkawinan
			Route::get('match', 'MatchController@index')->name('match');
			Route::get('match/ternak', 'MatchController@match')->name('match.ternak');

			//grafik
			Route::get('grafik', 'GrafikController@index')->name('grafik');
			Route::get('grafik/ras', 'GrafikController@grafikRas')->name('grafik.ras');
			Route::get('grafik/umur', 'GrafikController@grafikUmur')->name('grafik.umur');
			Route::get('grafik/lahir', 'GrafikController@grafikLahir')->name('grafik.lahir');
			Route::get('grafik/mati', 'GrafikController@grafikMati')->name('grafik.mati');
			Route::get('grafik/jual', 'GrafikController@grafikJual')->name('grafik.jual');
			Route::get('grafik/kawin', 'GrafikController@grafikKawin')->name('grafik.kawin');

			//laporan
			Route::get('laporan', 'LaporanController@index')->name('laporan');
			Route::post('laporan/lahir', 'LaporanController@lahir')->middleware('cors')->name('laporan.lahir');
			Route::post('laporan/mati', 'LaporanController@mati')->middleware('cors')->name('laporan.mati');
			Route::post('laporan/jual', 'LaporanController@jual')->middleware('cors')->name('laporan.jual');
			Route::post('laporan/kawin', 'LaporanController@kawin')->middleware('cors')->name('laporan.kawin');
			Route::post('laporan/sakit', 'LaporanController@sakit')->middleware('cors')->name('laporan.sakit');
			Route::post('laporan/perkembangan', 'LaporanController@perkembangan')->middleware('cors')->name('laporan.perkembangan');
			Route::post('laporan/ada', 'LaporanController@ada')->middleware('cors')->name('laporan.ada');
			Route::get('laporan/export/{param}', 'LaporanController@export')->name('laporan.export');
		});
	});


	//---------------------------------peternak--------------------------------------------
	// Route::prefix('peternak')->middleware('can:isPeternak', 'auth', 'verified')->group(function(){
	Route::prefix('peternak')->middleware('can:isPeternak', 'auth')->group(function(){
		Route::get('/', 'Peternak\HomeController@index')->name('peternak');

		Route::namespace('Peternak')->name('peternak.')->group(function(){
			Route::get('dashboard', 'HomeController@index')->name('dashboard');
			// search
			Route::get('search', 'HomeController@search')->name('search');

			// profil
			Route::get('profile', 'ProfileController@index')->name('profile');
			Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
			Route::put('profile/edit', 'ProfileController@update')->name('profile.update');
			Route::post('password/change', 'ProfileController@postChangePassword')->name('password.update');

			//data
			Route::resource('ternak', 'TernakController')->except(['create']);
			Route::get('ternaktrash', 'TernakController@trash')->name('ternak.trash');
			Route::get('ternak/restore/{id}', 'TernakController@restore')->name('ternak.restore');
			Route::get('ternakrestore', 'TernakController@restoreAll')->name('ternak.restoreAll');
			Route::delete('ternak/fdelete/{id}', 'TernakController@fdelete')->name('ternak.fdelete');
			Route::delete('ternakfdelete', 'TernakController@fdeleteAll')->name('ternak.fdeleteAll');
			Route::resource('ras', 'RasController')->except(['create', 'show']);
			Route::resource('riwayat', 'RiwayatPenyakitController')->except(['create', 'show']);
			Route::resource('kematian', 'KematianController')->except(['create', 'show']);
			Route::resource('pemilik', 'PemilikController')->except(['create']);
			Route::resource('perkawinan', 'PerkawinanController')->except(['create', 'show']);
			Route::get('perkawinan/pasangan/{id}', 'PerkawinanController@getPasangan');
			Route::resource('penjualan', 'PenjualanController')->except(['create', 'show']);
			Route::resource('perkembangan', 'PerkembanganController')->except(['create']);

			//barcode
			Route::get('barcode', 'BarcodeController@index')->name('barcode');
			Route::get('barcode/pdf', 'BarcodeController@generatePdf')->name('barcode.pdf');

			//perkawinan
			Route::get('match', 'MatchController@index')->name('match');
			Route::get('match/ternak', 'MatchController@match')->name('match.ternak');

			//grafik
			Route::get('grafik', 'GrafikController@index')->name('grafik');
			Route::get('grafik/ras', 'GrafikController@grafikRas')->name('grafik.ras');
			Route::get('grafik/umur', 'GrafikController@grafikUmur')->name('grafik.umur');
			Route::get('grafik/lahir', 'GrafikController@grafikLahir')->name('grafik.lahir');
			Route::get('grafik/mati', 'GrafikController@grafikMati')->name('grafik.mati');
			Route::get('grafik/jual', 'GrafikController@grafikJual')->name('grafik.jual');
			Route::get('grafik/kawin', 'GrafikController@grafikKawin')->name('grafik.kawin');

			//laporan
			Route::get('laporan', 'LaporanController@index')->name('laporan');
			Route::post('laporan/lahir', 'LaporanController@lahir')->middleware('cors')->name('laporan.lahir');
			Route::post('laporan/mati', 'LaporanController@mati')->middleware('cors')->name('laporan.mati');
			Route::post('laporan/jual', 'LaporanController@jual')->middleware('cors')->name('laporan.jual');
			Route::post('laporan/kawin', 'LaporanController@kawin')->middleware('cors')->name('laporan.kawin');
			Route::post('laporan/sakit', 'LaporanController@sakit')->middleware('cors')->name('laporan.sakit');
			Route::post('laporan/perkembangan', 'LaporanController@perkembangan')->middleware('cors')->name('laporan.perkembangan');
			Route::post('laporan/ada', 'LaporanController@ada')->middleware('cors')->name('laporan.ada');
			Route::get('laporan/export/{param}', 'LaporanController@export')->name('laporan.export');
		});
	});

	//--------------------- ketua-grup --------------------------------------
	// Route::prefix('ketua-grup')->middleware('can:isKetua', 'auth', 'verified')->group(function(){
	Route::prefix('ketua-grup')->middleware('can:isKetua', 'auth')->group(function(){
		//dashboard
		Route::get('/', 'Ketua\HomeController@index')->name('ketua-grup');
			
		Route::namespace('Ketua')->name('ketua-grup.')->group(function(){
			// search
			Route::get('search', 'HomeController@search')->name('search');
	
			// profil
			Route::get('profile', 'ProfileController@index')->name('profile');
			Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
			Route::put('profile/edit', 'ProfileController@update')->name('profile.update');
			Route::post('password/change', 'ProfileController@postChangePassword')->name('password.update');
	
			//data
			Route::resource('ternak', 'TernakController')->except(['create']);
			Route::get('ternaktrash', 'TernakController@trash')->name('ternak.trash');
			Route::get('ternak/restore/{id}', 'TernakController@restore')->name('ternak.restore');
			Route::get('ternakrestore', 'TernakController@restoreAll')->name('ternak.restoreAll');
			Route::delete('ternak/fdelete/{id}', 'TernakController@fdelete')->name('ternak.fdelete');
			Route::delete('ternakfdelete', 'TernakController@fdeleteAll')->name('ternak.fdeleteAll');
			Route::resource('ras', 'RasController')->except(['create', 'show']);
			Route::resource('riwayat', 'RiwayatPenyakitController')->except(['create', 'show']);
			Route::resource('kematian', 'KematianController')->except(['create', 'show']);
			Route::resource('pemilik', 'PemilikController')->except(['create']);
			Route::resource('perkawinan', 'PerkawinanController')->except(['create', 'show']);
			Route::get('perkawinan/pasangan/{id}', 'PerkawinanController@getPasangan');
			Route::resource('penjualan', 'PenjualanController')->except(['create', 'show']);
			Route::resource('perkembangan', 'PerkembanganController')->except(['create']);
	
			// data di grup saya
			//peternak
			Route::get('grup-saya/peternak', 'GrupSaya\PeternakController@index')->name('grup-saya.peternak');
			Route::get('grup-saya/peternak/get', 'GrupSaya\PeternakController@getUsers')->name('grup-saya.peternak.get');
			Route::get('grup-saya/peternak/verifikasi/{id}', 'GrupSaya\PeternakController@verifyUser');
			//ternak
			Route::get('grup-saya/ternak', 'GrupSaya\TernakController@index')->name('grup-saya.ternak');
			Route::get('grup-saya/ternak/get', 'GrupSaya\TernakController@getTernaks')->name('grup-saya.ternak.get');
			//riwayat penyakit
			Route::get('grup-saya/riwayat', 'GrupSaya\RiwayatPenyakitController@index')->name('grup-saya.riwayat');
			Route::get('grup-saya/riwayat/get', 'GrupSaya\RiwayatPenyakitController@getRiwayats')->name('grup-saya.riwayat.get');
			//perkembangan
			Route::get('grup-saya/perkembangan', 'GrupSaya\PerkembanganController@index')->name('grup-saya.perkembangan');
			Route::get('grup-saya/perkembangan/get', 'GrupSaya\PerkembanganController@getPerkembangans')->name('grup-saya.perkembangan.get');
	
			//barcode
			Route::get('barcode', 'BarcodeController@index')->name('barcode');
			Route::get('barcode/pdf', 'BarcodeController@generatePdf')->name('barcode.pdf');
	
			//perkawinan
			Route::get('match', 'MatchController@index')->name('match');
			Route::get('match/ternak', 'MatchController@match')->name('match.ternak');
	
			//grafik
			Route::get('grafik', 'GrafikController@index')->name('grafik');
			Route::get('grafik/ras', 'GrafikController@grafikRas')->name('grafik.ras');
			Route::get('grafik/umur', 'GrafikController@grafikUmur')->name('grafik.umur');
			Route::get('grafik/lahir', 'GrafikController@grafikLahir')->name('grafik.lahir');
			Route::get('grafik/mati', 'GrafikController@grafikMati')->name('grafik.mati');
			Route::get('grafik/jual', 'GrafikController@grafikJual')->name('grafik.jual');
			Route::get('grafik/kawin', 'GrafikController@grafikKawin')->name('grafik.kawin');
	
			//laporan
			Route::get('laporan', 'LaporanController@index')->name('laporan');
			Route::post('laporan/lahir', 'LaporanController@lahir')->middleware('cors')->name('laporan.lahir');
			Route::post('laporan/mati', 'LaporanController@mati')->middleware('cors')->name('laporan.mati');
			Route::post('laporan/jual', 'LaporanController@jual')->middleware('cors')->name('laporan.jual');
			Route::post('laporan/kawin', 'LaporanController@kawin')->middleware('cors')->name('laporan.kawin');
			Route::post('laporan/sakit', 'LaporanController@sakit')->middleware('cors')->name('laporan.sakit');
			Route::post('laporan/perkembangan', 'LaporanController@perkembangan')->middleware('cors')->name('laporan.perkembangan');
			Route::post('laporan/ada', 'LaporanController@ada')->middleware('cors')->name('laporan.ada');
			Route::get('laporan/export/{param}', 'LaporanController@export')->name('laporan.export');
		});
	});

// });

