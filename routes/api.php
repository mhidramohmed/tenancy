<?php

require __DIR__.'/tenant_api.php';
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreationController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/status', function () {
        $db = DB::connection();
        // dd($db);

        return response()->json([
                'status' => 'local API is working',
                'db' => $db->getDatabaseName() ,
            ]);

        });
    });


    Route::post('/test',[ CreationController ::class , 'createTenant']);




}


