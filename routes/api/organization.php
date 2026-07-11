<?php 

use App\Http\Controllers\API\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->controller(OrganizationController::class)->prefix('organizations')->group(function () {
    Route::post('/', 'create');
    Route::put('/{organizationID}', 'update');
    Route::delete('/{organizationID}', 'delete');

    Route::post('/{organizationID}/invite', 'inviteMember');
    Route::delete('/{organizationID}/members/{userID}', 'removeMember');
    Route::post('/{organizationID}/leave', 'leaveOrganization');
    Route::put('/{organizationID}/members/{userID}/role/{newRole}', 'changeMemberRole');

    Route::get('/', 'index');
    Route::get('/{organizationID}/members', 'getOrganizationMembers');
    
});
