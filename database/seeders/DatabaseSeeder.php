<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Setup;
use App\Models\Feature;
use App\Models\Department;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $setup = [
            'app_name' => ucwords(str_replace('_', ' ', 'sispro')),
            'company_name' => ucwords(str_replace('_', ' ', 'CV. Kendali Sinergi Aktif')),
            'company_logo' => 'https://img.freepik.com/premium-vector/link-data-logo-design-concept_393879-3105.jpg?w=740',
        ];
        Setup::insert($setup);

        $departments = [
            ["name" => ucwords(str_replace("_", " ", "director"))],
            ["name" => ucwords(str_replace("_", " ", "production"))],
            ["name" => ucwords(str_replace("_", " ", "quality_control"))],
            ["name" => ucwords(str_replace("_", " ", "R&D"))],
            ["name" => ucwords(str_replace("_", " ", "logistic"))],
            ["name" => ucwords(str_replace("_", " ", "PPIC"))],
        ];
        Department::insert($departments);

        $roles = [
            ["name" => ucwords(str_replace("_", " ", "director")), "department_id" => 1],
            ["name" => ucwords(str_replace("_", " ", "production_manager")), "department_id" => 2],
            ["name" => ucwords(str_replace("_", " ", "production_supervisor")), "department_id" => 2],
            ["name" => ucwords(str_replace("_", " ", "production_admin")), "department_id" => 2],
            ["name" => ucwords(str_replace("_", " ", "production_operator")), "department_id" => 2],
            ["name" => ucwords(str_replace("_", " ", "quality_control_manager")), "department_id" => 3],
            ["name" => ucwords(str_replace("_", " ", "quality_control_supervisor")), "department_id" => 3],
            ["name" => ucwords(str_replace("_", " ", "quality_control_admin")), "department_id" => 3],
            ["name" => ucwords(str_replace("_", " ", "quality_control_operator")), "department_id" => 3],
            ["name" => ucwords(str_replace("_", " ", "R&D_manager")), "department_id" => 4],
            ["name" => ucwords(str_replace("_", " ", "R&D_supervisor")), "department_id" => 4],
            ["name" => ucwords(str_replace("_", " ", "R&D_admin")), "department_id" => 4],
            ["name" => ucwords(str_replace("_", " ", "R&D_operator")), "department_id" => 4],
            ["name" => ucwords(str_replace("_", " ", "logistic_manager")), "department_id" => 5],
            ["name" => ucwords(str_replace("_", " ", "logistic_supervisor")), "department_id" => 5],
            ["name" => ucwords(str_replace("_", " ", "logistic_admin")), "department_id" => 5],
            ["name" => ucwords(str_replace("_", " ", "logistic_operator")), "department_id" => 5],
            ["name" => ucwords(str_replace("_", " ", "PPIC_manager")), "department_id" => 6],
            ["name" => ucwords(str_replace("_", " ", "PPIC_supervisor")), "department_id" => 6],
            ["name" => ucwords(str_replace("_", " ", "PPIC_admin")), "department_id" => 6],
            ["name" => ucwords(str_replace("_", " ", "PPIC_operator")), "department_id" => 6],
        ];
        Role::insert($roles);

        $users = [
            ["name" => ucwords(str_replace("_", " ", "director")), "username" => "director", "password" => bcrypt("director"), "is_active" => 1, "role_id" => 1],
        ];
        User::insert($users);

        $features = [
            ['name' => ucfirst(str_replace('_', ' ', 'setup')), 'route' => 'setup.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_setup')), 'route' => 'setup.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_department')), 'route' => 'department.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_department')), 'route' => 'department.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_department')), 'route' => 'department.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_department')), 'route' => 'department.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_department')), 'route' => 'department.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_department')), 'route' => 'department.destroy'],
        ];
        Feature::insert($features);

        foreach (Feature::select('id')->orderBy('id')->get() as $feature) {
            Permission::insert([
                "feature_id" => $feature->id,
                "role_id" => 1,
            ]);
        }
    }
}
