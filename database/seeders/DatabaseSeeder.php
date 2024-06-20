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
use App\Models\RawMaterial;
use App\Models\ProductStatus;
use App\Models\ProductCategory;
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
            ['name' => ucwords(str_replace('_', ' ', 'setup')), 'route' => 'setup.index'],
            ['name' => ucwords(str_replace('_', ' ', 'update_setup')), 'route' => 'setup.update'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_department')), 'route' => 'department.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_department')), 'route' => 'department.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_department')), 'route' => 'department.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_department')), 'route' => 'department.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_department')), 'route' => 'department.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_department')), 'route' => 'department.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_role')), 'route' => 'role.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_role')), 'route' => 'role.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_role')), 'route' => 'role.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_role')), 'route' => 'role.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_role')), 'route' => 'role.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_role')), 'route' => 'role.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_user')), 'route' => 'user.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_user')), 'route' => 'user.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_user')), 'route' => 'user.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_user')), 'route' => 'user.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_user')), 'route' => 'user.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_user')), 'route' => 'user.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'activity_log')), 'route' => 'activity_log'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_unit')), 'route' => 'unit.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_unit')), 'route' => 'unit.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_unit')), 'route' => 'unit.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_unit')), 'route' => 'unit.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_unit')), 'route' => 'unit.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_unit')), 'route' => 'unit.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_raw_material_category')), 'route' => 'raw_material_category.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_raw_material_category')), 'route' => 'raw_material_category.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_raw_material_category')), 'route' => 'raw_material_category.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_raw_material_category')), 'route' => 'raw_material_category.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_raw_material_category')), 'route' => 'raw_material_category.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_raw_material_category')), 'route' => 'raw_material_category.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_raw_material')), 'route' => 'raw_material.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_raw_material')), 'route' => 'raw_material.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_raw_material')), 'route' => 'raw_material.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_raw_material')), 'route' => 'raw_material.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_raw_material')), 'route' => 'raw_material.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_raw_material')), 'route' => 'raw_material.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_product_category')), 'route' => 'product_category.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_product_category')), 'route' => 'product_category.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_product_category')), 'route' => 'product_category.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_product_category')), 'route' => 'product_category.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_product_category')), 'route' => 'product_category.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_product_category')), 'route' => 'product_category.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_product_status')), 'route' => 'product_status.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_product_status')), 'route' => 'product_status.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_product_status')), 'route' => 'product_status.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_product_status')), 'route' => 'product_status.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_product_status')), 'route' => 'product_status.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_product_status')), 'route' => 'product_status.destroy'],
            ['name' => ucwords(str_replace('_', ' ', 'list_of_product')), 'route' => 'product.index'],
            ['name' => ucwords(str_replace('_', ' ', 'create_product')), 'route' => 'product.create'],
            ['name' => ucwords(str_replace('_', ' ', 'save_product')), 'route' => 'product.store'],
            ['name' => ucwords(str_replace('_', ' ', 'edit_product')), 'route' => 'product.edit'],
            ['name' => ucwords(str_replace('_', ' ', 'update_product')), 'route' => 'product.update'],
            ['name' => ucwords(str_replace('_', ' ', 'delete_product')), 'route' => 'product.destroy'],
        ];
        Feature::insert($features);

        foreach (Feature::select('id')->orderBy('id')->get() as $feature) {
            Permission::insert([
                "feature_id" => $feature->id,
                "role_id" => 1,
            ]);
        }

        $units = [
            ['name' => ucwords(str_replace('_', ' ', 'ton')), 'symbol' => 'ton'],
            ['name' => ucwords(str_replace('_', ' ', 'kuintal')), 'symbol' => 'ku'],
            ['name' => ucwords(str_replace('_', ' ', 'kilogram')), 'symbol' => 'kg'],
            ['name' => ucwords(str_replace('_', ' ', 'ons')), 'symbol' => 'ons'],
            ['name' => ucwords(str_replace('_', ' ', 'gram')), 'symbol' => 'gr'],
            ['name' => ucwords(str_replace('_', ' ', 'miligram')), 'symbol' => 'mg'],
        ];
        Unit::insert($units);

        $raw_material_categories = [
            ['name' => ucwords(str_replace('_', ' ', 'seasoning'))],
            ['name' => ucwords(str_replace('_', ' ', 'flour'))],
            ['name' => ucwords(str_replace('_', ' ', 'yeast'))],
            ['name' => ucwords(str_replace('_', ' ', 'additive'))],
            ['name' => ucwords(str_replace('_', ' ', 'packaging'))],
        ];
        RawMaterialCategory::insert($raw_material_categories);

        $raw_materials = [
            ['name' => ucwords(str_replace('_', ' ', 'tepung_bogasari')), "code" => "M1", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_segitiga_biru')), "code" => "M2", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_cakra_biru')), "code" => "M3", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_A')), "code" => "M4", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_B')), "code" => "M5", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_C')), "code" => "M6", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_D')), "code" => "M7", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_E')), "code" => "M8", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_F')), "code" => "M9", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_G')), "code" => "M10", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_H')), "code" => "M11", "raw_material_category_id" => 2, "unit_id" => 3],
            ['name' => ucwords(str_replace('_', ' ', 'tepung_tapioka_I')), "code" => "M12", "raw_material_category_id" => 2, "unit_id" => 3],
        ];
        RawMaterial::insert($raw_materials);

        $product_categories = [
            ['name' => ucwords(str_replace('_', ' ', 'meat'))],
            ['name' => ucwords(str_replace('_', ' ', 'bakery'))],
            ['name' => ucwords(str_replace('_', ' ', 'sauce'))],
        ];
        ProductCategory::insert($product_categories);

        $product_statuses = [
            ['name' => ucwords(str_replace('_', ' ', 'test'))],
            ['name' => ucwords(str_replace('_', ' ', 'production'))],
        ];
        ProductStatus::insert($product_statuses);
    }
}
