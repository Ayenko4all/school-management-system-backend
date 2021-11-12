<?php

namespace Database\Seeders;

use App\Models\SubjectType;
use App\Options\SubjectTypeOptions;
use Illuminate\Database\Seeder;

class SubjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types =  defaultOptionNames(SubjectTypeOptions::class);

        foreach($types as $type){
            SubjectType::create(['name'=> $type]);
        }
    }
}
