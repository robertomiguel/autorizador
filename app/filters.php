<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	//if (Auth::check()) return Redirect::to('/');
	if (Auth::guest()) {
		if(Request::ajax()) {
			return 'Su sesión expiró.';
		} else {
			return Redirect::guest('/')->withFlashMessage('Requiere inicio de sesión');
		}
	}
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

//--- Acceso a las rutas de admin... si falla, redirecciona al inicio
Route::filter('soyadmin', function() {
    if(Auth::user()->nivel==99) {} else {return Redirect::to('/')->withFlashMessage('Acceso denegado');}
});

//--- Acceso a las rutas de usuario... si falla, redirecciona al inicio
Route::filter('soyusuario', function() {

	if( Auth::user()->nivel > 0 && Auth::user()->estado == 0 ) {
    	return View::make('login.cambiarclave')->withFlashMessage('Nuevo Usuario o Reseteo de Clave');}

    if( Auth::user()->nivel > 0 && Auth::user()->estado == 1 ) {
    	Usuario::actualizaFechaIp();
    } else {
    	Auth::logout();
    	return Redirect::to('/')->withFlashMessage('Acceso denegado');}
});

//--- Acceso a las rutas de comercio... si falla, redirecciona al inicio
Route::filter('soycomercio', function() {

	if( Auth::user()->nivel > 1 && Auth::user()->estado == 0 ) {
    	return View::make('login.cambiarclave')->withFlashMessage('Nuevo Usuario o Reseteo de Clave');}

    if( Auth::user()->nivel > 1 && Auth::user()->estado == 1) { // && ComercioControlador::estado()=='A'
    	Usuario::actualizaFechaIp();
    } else {
    	Auth::logout();
    	return Redirect::to('/')->withFlashMessage('Acceso denegado');}
});