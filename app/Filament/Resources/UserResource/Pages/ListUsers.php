<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Events\EventUserCreated;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function newData($data)
    {
        dd($data);
    }


    
}
