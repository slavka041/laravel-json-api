<?php

/** @var \Illuminate\Routing\Router $router */
$router->post('login', 'Auth\LoginController@login')->name('login');
$router->post('logout', 'Auth\LoginController@logout')->name('logout');
$router->post('register', 'Auth\RegisterController@register')->name('register');
$router->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$router->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
