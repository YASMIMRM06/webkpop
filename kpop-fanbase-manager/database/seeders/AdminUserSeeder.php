<?php 
namespace Database\Seeders; 
use App\Models\User; 
use App\Models\Permission; 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\Hash; 
class AdminUserSeeder extends Seeder 
{ 
    public function run() 
    { 
        $admin = User::create([ 
            'name' => 'Admin', 
            'email' => 'admin@kpopfanbase.com', 
            'password' => Hash::make('password'), 
            'type' => 'admin', 
            'email_verified_at' => now(), 
        ]); 
        // Atribuir todas as permissões ao admin 
        $permissions = Permission::all(); 
        $admin->permissions()->sync($permissions->pluck('id')); 
    } 
}