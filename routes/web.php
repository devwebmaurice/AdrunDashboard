<?php

use App\Task;

Route::get('/', function () { return redirect('/admin/home'); });


// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
$this->post('login', 'Auth\LoginController@login')->name('auth.login');
$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::post('permissions_mass_destroy', ['uses' => 'Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    
    Route::resource('/adrun/availability-overview', 'Adrun\AvailabilityController');
    Route::resource('/adrun/availability-details', 'Adrun\AvailabilityDetailsController');
    
    
    Route::resource('/adrun/annonceur', 'Adrun\AdrunSettingsAnnonceurController');
    Route::resource('/adrun/format', 'Adrun\AdrunSettingsFormatController');
    Route::resource('/adrun/ciblage', 'Adrun\AdrunSettingsCiblageController');
    Route::resource('/adrun/pack', 'Adrun\AdrunSettingsPackController');
    Route::resource('/adrun/editeur', 'adrun\AdrunSettingsEditeurController');

});


Route::get('/ajax-crud/pack/tasks/{task_id?}',function($task_id){
    
    $task = Task::find($task_id);
    
    return Response::json($task);
});


Route::get('/ajax-crud/pack/list_website/{task_id?}',function($task_id){
    
    $pack = Task::addWebsiteToPack($task_id);
    
    return Response::json($pack);
});


Route::get('/ajax-crud/pack/delete_website/{task_id?}',function($task_id){
    
    $pack = Task::deleteWebsiteToPack($task_id);
    
    return Response::json($pack);
});