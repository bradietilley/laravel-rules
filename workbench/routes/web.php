<?php

use BradieTilley\AuditLogs\AuditLogger;
use Illuminate\Support\Facades\Route;
use Workbench\App\Models\User;

Route::get('request-logging-test/{user}', function (User $user, AuditLogger $recorder) {
    $recorder->record('Done something', $user);

    return response()->json([
        //
    ]);
})->name('request-logging-test-route');
