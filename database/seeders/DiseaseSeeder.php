<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiseaseModel;
use App\Models\Risk;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $d1 = DiseaseModel::create([
            'disease_name' => 'Corona Virus', 
            'description' => 'Coronavirus disease (COVID-19) is an infectious disease caused by a newly discovered coronavirus.',
            'homepage' => true,
        ]);

        $r1 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d1->id,
        ]);

        $d2 = DiseaseModel::create([
            'disease_name' => 'Dengue', 
            'description' => 'Dengue is a mosquito-borne viral infection. The virus responsible for causing dengue, is called dengue virus (DENV). There are four DENV serotypes, meaning that it is possible to be infected four times.',
            'homepage' => false,
        ]);

        $r2 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d2->id,
        ]);

        $d3 = DiseaseModel::create([
            'disease_name' => 'Chickenpox', 
            'description' => 'Chickenpox is an infection caused by the varicella-zoster virus. It causes an itchy rash with small, fluid-filled blisters. Chickenpox is highly contagious to people who have not had the disease or been vaccinated against it. Today, a vaccine is available that protects children against chickenpox.',
            'homepage' => false,
        ]);

        $r3 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d3->id,
        ]);

        $d4 = DiseaseModel::create([
            'disease_name' => 'Diphtheria', 
            'description' => 'Diphtheria is a serious infection caused by strains of bacteria called Corynebacterium diphtheriae that make toxin (poison). It can lead to difficulty breathing, heart failure, paralysis, and even death. CDC recommends vaccines for infants, children, teens, and adults to prevent diphtheria.',
            'homepage' => false,
        ]);

        $r4 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d4->id,
        ]);

        $d5 = DiseaseModel::create([
            'disease_name' => 'HIV/AIDS', 
            'description' => 'The human immunodeficiency viruses are two species of Lentivirus that infect humans. Over time, they cause acquired immunodeficiency syndrome, a condition in which progressive failure of the immune system allows life-threatening opportunistic infections and cancers to thrive.',
            'homepage' => false,
        ]);

        $r5 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d5->id,
        ]);

        $d6 = DiseaseModel::create([
            'disease_name' => 'Infectious mononucleosis', 
            'description' => 'Refers to a group of symptoms usually caused by the Epstein-Barr virus (EBV). It typically occurs in teenagers, but you can get it at any age. The virus is spread through saliva, which is why some people refer to it as “the kissing disease.”',
            'homepage' => false,
        ]);

        $r6 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d6->id,
        ]);

        $d7 = DiseaseModel::create([
            'disease_name' => 'Influenza (flu)', 
            'description' => 'Flu is a contagious respiratory illness caused by influenza viruses that infect the nose, throat, and sometimes the lungs. It can cause mild to severe illness, and at times can lead to death. The best way to prevent flu is by getting a flu vaccine each year.',
            'homepage' => false,
        ]);

        $r7 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d7->id,
        ]);

        $d8 = DiseaseModel::create([
            'disease_name' => 'Lyme disease', 
            'description' => 'Lyme disease is caused by the bacterium Borrelia burgdorferi and rarely, Borrelia mayonii. It is transmitted to humans through the bite of infected blacklegged ticks. Typical symptoms include fever, headache, fatigue, and a characteristic skin rash called erythema migrans.',
            'homepage' => false,
        ]);

        $r8 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d8->id,
        ]);

        $d9 = DiseaseModel::create([
            'disease_name' => 'Measles', 
            'description' => 'Measles is a highly contagious illness caused by a virus that replicates in the nose and throat of an infected child or adult. Then, when someone with measles coughs, sneezes or talks, infected droplets spray into the air, where other people can inhale them.',
            'homepage' => false,
        ]);

        $r9 = Risk::create([
            'low_risk' => 1,
            'severe_risk' => 2,
            'high_risk' => 3,
            'disease_id' => $d9->id,
        ]);
    }
}
