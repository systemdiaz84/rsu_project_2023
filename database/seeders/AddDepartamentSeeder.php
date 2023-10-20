<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\admin\Departament;

class AddDepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ubigeo_peru_departments = include('datos_ubigeo/ubigeo_peru_2016_departamentos.php');

        foreach($ubigeo_peru_departments as $department) {
            Departament::create([
                'id' => $department['id'],
                'name' => $department['name']
            ]);
        }
    }
}
