<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

// Route::prefix('api')
//     ->middleware([
//         'api',
//         InitializeTenancyByDomain::class,
//         PreventAccessFromCentralDomains::class,
//     ])
//     ->group(function () {
//         Route::get('/status', function () {
//             return response()->json([
//                 'status' => 'Tenant API is working',
//                 'tenant_id' => tenant('id'),
//             ]);
//         });
//     });




Route::middleware([
'api',
InitializeTenancyByDomain::class,
PreventAccessFromCentralDomains::class,
])->group(function () {
Route::get('/', function () {
    return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
});

Route::get('/status', function () {
    $db = DB::connection();
    // dd($db);

    return response()->json([
            'status' => 'tenant API is working',
            'db' => $db->getDatabaseName() ,
        ]);

});
});
