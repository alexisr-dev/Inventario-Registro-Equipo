<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Support\Facades\Auth; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    


    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //


        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            /** @var \App\Models\User|null $user */
            $user = auth::user(); // Aquí forzamos el reconocimiento por parte de Intelephense
        
            $panelSwitch->visible(fn (): bool => $user?->hasAnyRole(['super_admin']));
            $panelSwitch->simple();
        });
        
        
        
        
    }
    
}
