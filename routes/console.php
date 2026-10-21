<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Remplit la base seulement si elle est vide (évite 3 min de chargement à chaque réveil du serveur)
Artisan::command('app:seed-if-empty', function () {
    if (\App\Models\Manga::exists()) {
        $this->info('Base déjà remplie, rien à faire.');
        return;
    }
    $this->call('db:seed', ['--force' => true]);
})->purpose('Remplit la base si elle est vide');
