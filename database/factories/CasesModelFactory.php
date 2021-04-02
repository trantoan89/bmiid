<?php

namespace Database\Factories;

use App\Models\CasesModel;
use App\Models\BarangayModel;
use App\Models\DiseaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CasesModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CasesModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-60 days', 'now');
        $active = $this->faker->numberBetween(0, 30);

        return [
            'case_date' => $date->format('Y-m-d'), 
            'active' => $this->faker->numberBetween(0, 30),
            'deceased' => $this->faker->numberBetween(0, 1),
            'recovered' => $this->faker->numberBetween(0, $active), 
            'barangay_id' => BarangayModel::inRandomOrder()->value('id'),
            'disease_id' => DiseaseModel::inRandomOrder()->value('id'),
            'status' => 'approved',
            'created_by' => User::inRandomOrder()->value('id'),
            'approved_by' => User::inRandomOrder()->value('id'),
        ];
    }
}
