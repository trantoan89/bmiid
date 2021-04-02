<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsModel;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        NewsModel::create([
            'news_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'show' => true,
            'news_description' => '
            BAGUIO CITY—Restrictions at the borders of Baguio and its five neighboring towns in Benguet province will be tightened again as the Cordillera is expected to be placed on general community quarantine (GCQ) starting Feb. 1, officials said.
            
            The Department of Health (DOH) last week endorsed the stricter quarantine status for the region following the surge in cases of the new coronavirus disease (Covid-19) and its more contagious variant first discovered in the United Kingdom (UK).
            But the Inter-Agency Task Force for the Management of Emerging Infectious Diseases has yet to issue guidelines for the new quarantine classification next month.
            ',
        ]);
    }
}
