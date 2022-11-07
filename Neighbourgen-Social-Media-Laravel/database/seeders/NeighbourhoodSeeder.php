<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Neighbourhood;

class NeighbourhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $neighbbourhoods= array("Mirpur", "Mohammadpur", "Badda", "Kuratoli", "Dhanmondi", "Gulshan", 
        "Banani", "Uttara", "Bashundhara", "Gulistan", "Farmgate", "Khilgaon", "Shyamoli", "Pallabi");

        foreach($neighbbourhoods as $neighbbourhood){
            Neighbourhood::create([
                'name' => $neighbbourhood,
                'address' => $neighbbourhood
            ]);
        }
    }
}
