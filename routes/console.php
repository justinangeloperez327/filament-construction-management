<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Build with clarity.');
})->purpose('Display an inspirational quote');
