<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v2')->namespace('API')->group(function(){
	Route::post('login', 'UserController@login');
	Route::post('register', 'UserController@register');

	Route::middleware('auth:api')->group(function(){
		Route::get('profile', 'UserController@get_user_details_info');
		Route::get('logout', 'UserController@logout');

		Route::apiResource('ras', 'RasController');
		Route::apiResource('pemilik', 'PemilikController');
		Route::apiResource('kematian', 'KematianController');
		Route::apiResource('penjualan', 'PenjualanController');
		Route::apiResource('ternak', 'TernakController');
		Route::apiResource('peternak', 'PeternakController');
		Route::apiResource('grup-peternak', 'GrupPeternakController');
		Route::apiResource('perkawinan', 'PerkawinanController');
		Route::apiResource('riwayat', 'RiwayatPenyakitController');
		Route::apiResource('perkembangan', 'PerkembanganController');

		Route::get('ternaktrash', 'TernakController@trash');
		Route::get('ternaktrash/{id}', 'TernakController@trashid');
		Route::get('ternaktrash/restore/{id}', 'TernakController@restore');
		Route::get('ternaktrash/restore', 'TernakController@restoreAll');
		Route::delete('ternaktrash/fdelete/{id}', 'TernakController@fdelete');
		Route::delete('ternaktrash/fdelete', 'TernakController@fdeleteAll');

		Route::get('scan/{id}', 'ScanController@search');
		Route::get('match', 'MatchController@match');

		Route::get('verifikasi', 'VerifikasiController@index');
		Route::get('verifikasi/{id}', 'VerifikasiController@verifyUser');

		Route::get('grup-saya/peternak', 'GrupSayaController@peternak');
		Route::get('grup-saya/ternak', 'GrupSayaController@ternak');
		Route::get('grup-saya/riwayat', 'GrupSayaController@riwayat');
		Route::get('grup-saya/perkembangan', 'GrupSayaController@perkembangan');

		Route::get('grafik', 'GrafikController@index');
		Route::get('grafik/ras', 'GrafikController@grafikRas');
		Route::get('grafik/umur', 'GrafikController@grafikUmur');
		Route::get('grafik/lahir', 'GrafikController@grafikLahir');
		Route::get('grafik/mati', 'GrafikController@grafikMati');
		Route::get('grafik/jual', 'GrafikController@grafikJual');
		Route::get('grafik/kawin', 'GrafikController@grafikKawin');

		Route::get('barcode', 'BarcodeController@index');

		Route::get('laporan', 'LaporanController@index');

		//for select option
		Route::get('options', 'OptionsController@index');

	});

});

