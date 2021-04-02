<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CasesModel;

class caseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CasesModel::factory()->count(50)->create();
    }
}
