<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TauxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taux_transferts')->insert([
            [
                'montant_minimum' => 5000,
                'montant_maximum' => 300000,
                'montant_fixe' => 0,
                'taux' => 0.02,
                'created_at' => now(),
            ],
            [
                'montant_minimum' => 300001,
                'montant_maximum' => 500000,
                'montant_fixe' => 0,
                'taux' => 0.015,
                'created_at' => now(),
            ],
            [
                'montant_minimum' => 500001,
                'montant_maximum' => 1000000,
                'montant_fixe' => 6000,
                'taux' => 0,
                'created_at' => now(),
            ],
        ]);
    }
}
