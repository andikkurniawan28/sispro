<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Setup;
use App\Models\Feature;
use App\Models\Department;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\RawMaterialCategory;

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
            'company_logo' => 'https://img.freepik.com/free-vector/colorful-bird-illustration-gradient_343694-1741.jpg?w=740&t=st=1718777239~exp=1718777839~hmac=0fb64615a1d74bf2aa6359c4fa472ff75d881de15c103bdecd09f495a98a7e6c',
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
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_role')), 'route' => 'role.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_role')), 'route' => 'role.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_role')), 'route' => 'role.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_role')), 'route' => 'role.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_role')), 'route' => 'role.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_role')), 'route' => 'role.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_user')), 'route' => 'user.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_user')), 'route' => 'user.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_user')), 'route' => 'user.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_user')), 'route' => 'user.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_user')), 'route' => 'user.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_user')), 'route' => 'user.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'activity_log')), 'route' => 'activity_log'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_unit')), 'route' => 'unit.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_unit')), 'route' => 'unit.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_unit')), 'route' => 'unit.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_unit')), 'route' => 'unit.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_unit')), 'route' => 'unit.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_unit')), 'route' => 'unit.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_raw_material_category')), 'route' => 'raw_material_category.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_raw_material_category')), 'route' => 'raw_material_category.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_raw_material_category')), 'route' => 'raw_material_category.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_raw_material_category')), 'route' => 'raw_material_category.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_raw_material_category')), 'route' => 'raw_material_category.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_raw_material_category')), 'route' => 'raw_material_category.destroy'],
            ['name' => ucfirst(str_replace('_', ' ', 'list_of_raw_material')), 'route' => 'raw_material.index'],
            ['name' => ucfirst(str_replace('_', ' ', 'create_raw_material')), 'route' => 'raw_material.create'],
            ['name' => ucfirst(str_replace('_', ' ', 'save_raw_material')), 'route' => 'raw_material.store'],
            ['name' => ucfirst(str_replace('_', ' ', 'edit_raw_material')), 'route' => 'raw_material.edit'],
            ['name' => ucfirst(str_replace('_', ' ', 'update_raw_material')), 'route' => 'raw_material.update'],
            ['name' => ucfirst(str_replace('_', ' ', 'delete_raw_material')), 'route' => 'raw_material.destroy'],
        ];
        Feature::insert($features);

        foreach (Feature::select('id')->orderBy('id')->get() as $feature) {
            Permission::insert([
                "feature_id" => $feature->id,
                "role_id" => 1,
            ]);
        }

        $units = [
            ['name' => ucfirst(str_replace('_', ' ', 'ton')), 'symbol' => 'ton'],
            ['name' => ucfirst(str_replace('_', ' ', 'kuintal')), 'symbol' => 'ku'],
            ['name' => ucfirst(str_replace('_', ' ', 'kilogram')), 'symbol' => 'kg'],
            ['name' => ucfirst(str_replace('_', ' ', 'ons')), 'symbol' => 'ons'],
            ['name' => ucfirst(str_replace('_', ' ', 'gram')), 'symbol' => 'gr'],
            ['name' => ucfirst(str_replace('_', ' ', 'miligram')), 'symbol' => 'mg'],
        ];
        Unit::insert($units);

        $raw_material_categories = [
            ['name' => ucfirst(str_replace('_', ' ', 'seasoning'))],
            ['name' => ucfirst(str_replace('_', ' ', 'flour'))],
            ['name' => ucfirst(str_replace('_', ' ', 'yeast'))],
            ['name' => ucfirst(str_replace('_', ' ', 'additive'))],
            ['name' => ucfirst(str_replace('_', ' ', 'packaging'))],
        ];
        RawMaterialCategory::insert($raw_material_categories);
    }
}
