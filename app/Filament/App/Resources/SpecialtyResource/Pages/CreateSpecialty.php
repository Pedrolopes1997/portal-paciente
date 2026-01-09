<?php

namespace App\Filament\App\Resources\SpecialtyResource\Pages;

use App\Filament\App\Resources\MedicalSpecialtyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecialty extends CreateRecord
{
    protected static string $resource = MedicalSpecialtyResource::class;
}
