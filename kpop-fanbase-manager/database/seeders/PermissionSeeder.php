<?php 
namespace Database\Seeders; 
use App\Models\Permission; 
use Illuminate\Database\Seeder; 
class PermissionSeeder extends Seeder 
{ 
    public function run() 
    { 
        $permissions = [ 
            ['name' => 'Gerenciar Usuários', 'slug' => 'manage-users', 'description' => 'Pode criar, editar e deletar usuários'], 
            ['name' => 'Gerenciar Grupos', 'slug' => 'manage-groups', 'description' => 'Pode criar, editar e deletar grupos'], 
            ['name' => 'Gerenciar Eventos', 'slug' => 'manage-events', 'description' => 'Pode criar, editar e deletar eventos'], 
            ['name' => 'Gerenciar Permissões', 'slug' => 'manage-permissions', 'description' => 'Pode atribuir permissões a 
usuários'], 
        ]; 
        foreach ($permissions as $permission) { 
            Permission::create($permission); 
        } 
    } 
} 