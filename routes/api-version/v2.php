<?php

use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityDocController;
use App\Http\Controllers\ActivityTeamController;
use App\Http\Controllers\AdminDocCategoryController;
use App\Http\Controllers\AdminDocController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharteredAccountantController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailStoneSpektekController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTeamController;
use App\Http\Controllers\SpektekController;
use App\Http\Controllers\SubSpektekController;
use App\Http\Controllers\TimelinesController;
use App\Http\Controllers\UploadChunkController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Users
    // Route::get('/users', [UserController::class, 'getAll'])->middleware('role:SUPERADMIN,ADMIN,USER');
    Route::get('/users', [UserController::class, 'getAll']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{id}', [UserController::class, 'getById']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'softDelete']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);

    // Companies
    Route::post('/companies', [CompanyController::class, 'create']);
    Route::get('/companies', [CompanyController::class, 'getAll']);
    Route::get('/companies/search', [CompanyController::class, 'search']);
    Route::get('/companies/{id}', [CompanyController::class, 'getById']);
    Route::post('/companies/{id}', [CompanyController::class, 'update']);
    Route::delete('/companies/{id}', [CompanyController::class, 'softDelete']);

    // Projects
    Route::post('/projects', [ProjectController::class, 'create']);
    Route::get('/projects', [ProjectController::class, 'getAll']);
    Route::get('/projects/search', [ProjectController::class, 'search']);
    Route::get('/projects/{id}', [ProjectController::class, 'getById']);
    Route::get('/projects/dar/{id}', [ProjectController::class, 'darProjectById']);
    Route::patch('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'softDelete']);

    // Project Teams
    Route::post('/project-teams', [ProjectTeamController::class, 'create']);
    Route::get('/project-teams', [ProjectTeamController::class, 'getAll']);
    Route::get('/project-teams/search', [ProjectTeamController::class, 'search']);
    Route::get('/project-teams/{id}', [ProjectTeamController::class, 'getById']);
    Route::patch('/project-teams/{id}', [ProjectTeamController::class, 'update']);
    Route::delete('/project-teams/{projectId}', [ProjectTeamController::class, 'delete']);

    // Activity Teams
    Route::post('/activity-teams', [ActivityTeamController::class, 'create']);
    Route::get('/activity-teams', [ActivityTeamController::class, 'getAll']);
    Route::get('/activity-teams/search', [ActivityTeamController::class, 'search']);
    Route::get('/activity-teams/{id}', [ActivityTeamController::class, 'getById']);
    Route::patch('/activity-teams/{id}', [ActivityTeamController::class, 'update']);
    Route::delete('/activity-teams/{id}', [ActivityTeamController::class, 'delete']);

    // Admin Doc Category
    Route::post('/admin-doc-categories', [AdminDocCategoryController::class, 'create']);
    Route::get('/admin-doc-categories', [AdminDocCategoryController::class, 'getAll']);
    Route::get('/admin-doc-categories/search', [AdminDocCategoryController::class, 'search']);
    Route::get('/admin-doc-categories/{id}', [AdminDocCategoryController::class, 'getById']);
    Route::patch('/admin-doc-categories/{id}', [AdminDocCategoryController::class, 'update']);
    Route::delete('/admin-doc-categories/{id}', [AdminDocCategoryController::class, 'softDelete']);

    // Admin Docs
    Route::post('/admin-docs', [AdminDocController::class, 'create']);
    Route::get('/admin-docs', [AdminDocController::class, 'getAll']);
    Route::get('/admin-docs/search', [AdminDocController::class, 'search']);
    Route::get('/admin-docs/{id}', [AdminDocController::class, 'getById']);
    Route::delete('/admin-docs/{id}', [AdminDocController::class, 'softDelete']);
    Route::get('/docs/move', [AdminDocController::class, 'movePath']);

    // Activity Category
    Route::post('/activity-categories', [ActivityCategoryController::class, 'create']);
    Route::get('/activity-categories', [ActivityCategoryController::class, 'getAll']);
    Route::get('/activity-categories/search', [ActivityCategoryController::class, 'search']);
    Route::get('/activity-categories/{id}', [ActivityCategoryController::class, 'getById']);
    Route::post('/activity-categories/{id}', [ActivityCategoryController::class, 'update']);
    Route::delete('/activity-categories/{id}', [ActivityCategoryController::class, 'softDelete']);

    // Activity
    Route::post('/activities', [ActivityController::class, 'create']);
    Route::get('/activities', [ActivityController::class, 'getAll']);
    Route::get('/activities/search', [ActivityController::class, 'search']);
    Route::get('/activities/{id}', [ActivityController::class, 'getById']);
    Route::patch('/activities/{id}', [ActivityController::class, 'update']);
    Route::delete('/activities/{id}', [ActivityController::class, 'softDelete']);

    // Activity Docs
    Route::post('/activity-docs', [ActivityDocController::class, 'create']);
    Route::get('/activity-docs', [ActivityDocController::class, 'getAll']);
    Route::get('/activity-docs/search', [ActivityDocController::class, 'search']);
    Route::get('/activity-docs/tags', [ActivityDocController::class, 'getAllTags']);
    Route::get('/activity-docs/{id}', [ActivityDocController::class, 'getById']);
    Route::post('/activity-docs/{id}', [ActivityDocController::class, 'update']);
    Route::delete('/activity-docs/{id}', [ActivityDocController::class, 'softDelete']);

    // Upload Chunk
    Route::post('/upload-chunks', [UploadChunkController::class, 'create']);
    Route::delete('/upload-chunks', [UploadChunkController::class, 'delete']);

    // Timelines
    Route::post('/timelines', [TimelinesController::class, 'create']);
    Route::get('/timelines', [TimelinesController::class, 'getAll']);
    Route::get('/timelines/search', [TimelinesController::class, 'search']);
    Route::get('/timelines/{id}', [TimelinesController::class, 'getById']);
    Route::patch('/timelines/{id}', [TimelinesController::class, 'update']);
    Route::delete('/timelines/{id}', [TimelinesController::class, 'softDelete']);

    // MailStone Spektek
    // Route::post('/mailstone-spekteks', [MailStoneSpektekController::class, 'create']);
    // Route::get('/mailstone-spekteks', [MailStoneSpektekController::class, 'getAll']);
    // Route::get('/mailstone-spekteks/search', [MailStoneSpektekController::class, 'search']);
    // // Route::get('/mailstone-spekteks/{id}', [MailStoneSpektekController::class, 'getById']);
    // Route::post('/mailstone-spektek/bulkCreate', [MailStoneSpektekController::class, 'bulkCreate']);
    // Route::patch('/mailstone-spekteks/{id}', [MailStoneSpektekController::class, 'update']);
    // Route::delete('/mailstone-spekteks/{id}', [MailStoneSpektekController::class, 'softDelete']);

    // Spektek
    Route::post('/spekteks', [SpektekController::class, 'create']);
    Route::post('/spekteks/bulkCreate', [SpektekController::class, 'bulkCreate']);
    Route::get('/spekteks', [SpektekController::class, 'getAll']);
    Route::get('/spekteks/search', [SpektekController::class, 'search']);
    Route::get('/spekteks/{id}', [SpektekController::class, 'getById']);
    Route::patch('/spekteks/{id}', [SpektekController::class, 'update']);
    Route::patch('/spekteks/{id}/updateQtyReceived', [SpektekController::class, 'updateQtyReceived']);
    Route::patch('/spekteks/{id}/updateProgressPercentage', [SpektekController::class, 'updateProgressPercentage']);
    Route::delete('/spekteks/{id}', [SpektekController::class, 'softDelete']);

    // Sub Spektek
    Route::post('/sub-spekteks', [SubSpektekController::class, 'create']);
    Route::get('/sub-spekteks', [SubSpektekController::class, 'getAll']);
    Route::patch('/sub-spekteks/{id}', [SubSpektekController::class, 'update']);
    Route::patch('/sub-spekteks/{id}/updateQtyReceived', [SubSpektekController::class, 'updateQtyReceived']);
    Route::patch('/sub-spekteks/{id}/updateProgressPercentage', [SubSpektekController::class, 'updateProgressPercentage']);
    Route::delete('/sub-spekteks/{id}', [SubSpektekController::class, 'softDelete']);

    // Chartered Accountant
    Route::post('/chartered-accountants', [CharteredAccountantController::class, 'create']);
    Route::get('/chartered-accountants', [CharteredAccountantController::class, 'getAll']);
    Route::get('/chartered-accountants/search', [CharteredAccountantController::class, 'search']);
    Route::get('/chartered-accountants/{id}', [CharteredAccountantController::class, 'getById']);
    Route::post('/chartered-accountants/{id}', [CharteredAccountantController::class, 'update']);
    Route::delete('/chartered-accountants/{id}', [CharteredAccountantController::class, 'softDelete']);
// });
