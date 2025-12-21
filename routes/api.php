<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CharityProjectController;
use App\Http\Controllers\Api\DonationController;
use App\Models\CharityProject;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('api')
    ->group(function (Router $router) {
        $router->get('/', function () {
            return response()->json(['message' => 'API v1']);
        });

        Route::model('project', CharityProject::class);

        // Charity
        $router->get('projects', [CharityProjectController::class, 'index'])->name('projects.index');
        $router->get('projects/{slug}', [CharityProjectController::class, 'show'])->name('projects.show');

        // Donations
        $router->post('donations', [DonationController::class, 'store'])->name('donations.store');
    });
