<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\admin\District;

class AddDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ubigeo_peru_districts = include('datos_ubigeo/ubigeo_peru_2016_distritos.php');

        foreach($ubigeo_peru_districts as $district) {
            District::create([
                'id' => $district['id'],
                'name' => $district['name'],
                'departament_id' => $district['department_id'],
                'province_id' => $district['province_id'],

            ]);
        }
    }
}
