<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/confirm-compte', function () {
    return view('auth.confirm');
});
Route::post('/confirme-compte', 'Auth\LoginController@confirmCompte')->name('confirme-compte');
Route::post('/ask-password', 'Auth\ResetPasswordController@askPassword')->name('ask-password');

Auth::routes();
Route::get('/home-admin', 'HomeController@admin')->name('home-admin');
Route::get('/home-superviseur', 'HomeController@superviseur')->name('home-superviseur');
Route::get('/home-comptable', 'HomeController@comptable')->name('home-comptable');
Route::get('/home-agent', 'HomeController@agent')->name('home-agent');

//les routes du module Parametre
Route::namespace('Parametre')->middleware('auth')->name('parametre.')->prefix('parametre')->group(function () {
    //Configurations
    Route::resource('configurations', 'ConfigurationController');

    //Route countries
    Route::resource('countries', 'CountryController');
    Route::get('list-countries', 'CountryController@listCountries');

    //Route cities
    Route::resource('cities', 'CityController');
    Route::get('list-cities', 'CityController@listCities');
    Route::get('list-cities-by-country/{country}', 'CityController@listCitiesByCountry');

    //Route municipalities  
    Route::resource('municipalities', 'MunicipalityController');
    Route::get('list-municipalities', 'MunicipalityController@listMunicipalities');
    Route::get('list-municipalities-by-city/{city}', 'MunicipalityController@listMunicipalitiesByCity');

    //Route agencies
    Route::resource('agencies', 'AgencyController');
    Route::get('list-agencies', 'AgencyController@listAgencies');
    Route::get('list-agencies-by-country/{country}', 'AgencyController@listAgenciesByCountry');
    Route::get('list-agencies-by-city/{city}', 'AgencyController@listAgenciesByCity');

    //Route caisses
    Route::resource('caisses', 'CaisseController');
    Route::get('list-caisses', 'CaisseController@listCaisse');
    Route::get('list-caisses-by-country/{country}', 'CaisseController@listCaisseByCountry');
    Route::get('list-caisses-by-city/{city}', 'CaisseController@listCaisseByCity');
    Route::get('list-caisses-by-agency/{agency}', 'CaisseController@listCaisseByAgency');
    
    //Route customers 
    Route::resource('customers', 'CustomerController');
    Route::get('list-customers', 'CustomerController@listCustomers');
    Route::get('list-customers-by-country/{country}', 'CustomerController@listCustomersByCountry');
    Route::get('find-customer/{id}', 'CustomerController@findCustomer');

    //Route banks
    Route::resource('banks', 'BankController');
    Route::get('list-banks', 'BankController@listBank');
    Route::get('find-bank/{id}', 'BankController@findBank');
    Route::get('list-banks-by-country/{country}', 'BankController@listBankByCountry');

    //Route partenairs 
    Route::resource('partenairs', 'PartenairController');
    Route::get('list-partenairs', 'PartenairController@listPartenairs');
    Route::get('find-partenair/{id}', 'PartenairController@findPartenair');
    Route::get('list-partenairs-by-country/{country}', 'PartenairController@listPartenairsByCountry');

    //Route Taux transfert
    Route::resource('taux-transferts', 'TauxTransfertController');
    Route::get('list-taux-transferts', 'TauxTransfertController@listTauxTransfert');
});

//les routes du module Operation
Route::namespace('Operation')->middleware('auth')->name('operation.')->prefix('operation')->group(function () {

    //Route oprations
    Route::resource('operations', 'OperationController');
    Route::get('list-operations', 'OperationController@listOperations');
    Route::get('list-operations-by-reference/{reference}', 'OperationController@listOperationsByReference');
    Route::get('list-operations-by-type/{type}', 'OperationController@listOperationsByType');
    Route::get('list-operations-by-state/{state}', 'OperationController@listOperationsByState');
    Route::get('list-operations-by-concerne/{concerne}', 'OperationController@listOperationsByConcerne');
    Route::get('recu-operation/{id}', 'OperationController@recuOperation');

        /** Partenaire */
        Route::get('operations-partenairs', 'OperationController@vueOperationPartenair')->name('operations-partenairs');
        Route::get('list-operations-partenairs', 'OperationController@listOperationsPartenairs');
        Route::get('list-operations-by-partenairs/{partenair}', 'OperationController@listOperationsByPartenairs');

        //** Bank */
        Route::get('operations-banks', 'OperationController@vueOperationBank')->name('operations-banks');
        Route::get('list-operations-banks', 'OperationController@listOperationsBanks');
        Route::get('list-operations-by-banks/{bank}', 'OperationController@listOperationsByBanks');

        //** Décaissement - Encaissement */
        Route::get('decaissement-encaissement', 'OperationController@vueOperationDecaissementEncaissement')->name('decaissement-encaissement');
        Route::get('list-decaissement-encaissement', 'OperationController@listOperationsDecaissementEncaissement');
        Route::get('list-decaissement-encaissement-by-caisse/{caisse}', 'OperationController@listOperationsDecaissementEncaissementByCaisse');

    //Route CaisseOuverte 
    Route::resource('caisse-ouvertes', 'CaisseOuverteController');
    Route::post('open-caisse', 'CaisseOuverteController@openCaisse')->name('open-caisse');
    Route::post('close-caisse', 'CaisseOuverteController@closeCaisse')->name('close-caisse');
    Route::get('find-open-caisse/{id}', 'CaisseOuverteController@findOpenCaisse');
    Route::get('get-caisse-infos-cloture/{id}', 'CaisseOuverteController@getCaisseInfosCloture');
    Route::get('list-opened-caisses-by-agency/{agency}', 'CaisseOuverteController@listOpenedCaisseByAgency');

    //Route send money 
    Route::resource('send-money', 'SendMoneyController');
    Route::get('list-send-money', 'SendMoneyController@listSendMoney');
    Route::get('list-send-money-by-code/{code}', 'SendMoneyController@listSendMoneyByCode');
    Route::get('list-send-money-by-sender/{sender}', 'SendMoneyController@listSendMoneyBySender');
    Route::get('list-send-money-by-recipient/{recipient}', 'SendMoneyController@listSendMoneyByRecipient');
    Route::get('list-send-money-by-destination/{destination}', 'SendMoneyController@listSendMoneyByDestination');
    Route::get('list-send-money-by-state/{state}', 'SendMoneyController@listSendMoneyByState');
    Route::get('list-send-money-by-date/{date}', 'SendMoneyController@listSendMoneyByDate');
});

//les routes du module Auth
Route::namespace('Auth')->middleware('auth')->name('auth.')->prefix('auth')->group(function () {
    Route::resource('users', 'UserController');
    Route::get('list-users', 'UserController@listUser');
    Route::get('user-profil', 'UserController@profil')->name('user.profil');
    Route::put('update-profil/{user}', 'UserController@updateProfil')->name('user.update-profil');
    Route::put('update-password/{user}', 'UserController@updatePassword')->name('user.update-password');
});

