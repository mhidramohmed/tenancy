<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class CreationController extends Controller
{
    public function createTenant(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'resto_name' => 'required|string|unique:domains,domain',
        ]);

        $id = $request->resto_name ;




        $client= Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'db_name' => $id,
        ]);




        $tenant = Tenant::create(['id' => $id, 'name' => $id]);

        // 2. Créer le domaine
        $domain = $tenant->domains()->create(['domain' => $id . "."."localhost:8000"]);


        // 3. Créer l'utilisateur dans le tenant
        tenancy()->initialize($tenant);



        // Vérifier si la table exist
        // if (!Schema::hasTable('users')) {
        //     tenancy()->end();

        //     return response()->json(['error' => 'Users table does not exist'], 500);
        // }

        $userData = [
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make("Admin@1234"),
            'email_verified_at' => now(),
        ];

        $user = \App\Models\User::create($userData);

        tenancy()->end();

        return response()->json([
            'success' => true,
            'message' => 'database created successfully',
            'data'    => [
            'domain'      => $domain->domain,
            'credentials' => [
                'email'    => $user->email,
                'password' => "Admin@1234",
            ],

            ],
        ], 201);






        return response()->json(['message' => 'Tenant created successfully', 'tenant_id' => $tenant->id]);
    }
}
