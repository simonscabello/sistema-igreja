<?php

test('registration screen is disabled', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
})->skip('Registro público desabilitado em routes/auth.php');

test('new users cannot register while route is disabled', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();
})->skip('Registro público desabilitado em routes/auth.php');
