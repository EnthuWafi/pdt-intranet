<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->label('Foto Profil')
                    ->collection('avatar')
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(2048)
                    ->columnSpanFull(),

                $this->getNameFormComponent()
                    ->label('Nama Penuh'),

                $this->getEmailFormComponent()
                    ->label('E-mel Rasmi')
                    ->disabled(), // Lock email to prevent auth mismatches

                TextInput::make('position')
                    ->label('Jawatan')
                    ->disabled(),

                TextInput::make('department')
                    ->label('Bahagian / Unit')
                    ->disabled(),

                $this->getPasswordFormComponent()
                    ->label('Kata Laluan Baharu'),

                $this->getPasswordConfirmationFormComponent()
                    ->label('Sahkan Kata Laluan'),
            ]);
    }
}