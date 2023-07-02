<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BasePage
{
    protected function getWidgets(): array
    {
        return [
            AccountWidget::class,
            // FilamentInfoWidget::class
        ];
    }
}
