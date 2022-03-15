<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\CasesPDFController;
use App\Contracts\CasesPresenter;
use App\CasesPDFPresenter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(CasesPDFController::class)
            ->needs(CasesPresenter::class)
            ->give(function () {
                return new CasesPDFPresenter();
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
