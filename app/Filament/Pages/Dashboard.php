<?php

namespace App\Filament\Pages;

use App\Livewire\PieChart;
use App\Livewire\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Page;

class Dashboard extends BaseDashboard
{

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            PieChart::class
        ];
    }


    public static function canAccess(): bool
    {
        $user = auth()->user();

        if($user->isStudent()){
            return false;
        }
        return true;
    }

}
