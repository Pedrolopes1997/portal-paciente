<?php

namespace App\Filament\App\Resources\SpecialtyResource\Pages;

use App\Filament\App\Resources\MedicalSpecialtyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialty extends EditRecord
{
    protected static string $resource = MedicalSpecialtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
