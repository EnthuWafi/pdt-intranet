<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserStatus;
use App\Models\Department;
use App\Models\Position;
use App\Models\Unit;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\RegistrationResponse;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                //custom fields
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])
                    ->schema([
                        Select::make('department_id')
                            ->label('Bahagian')
                            ->options(Department::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn ($set) => $set('unit_id', null))
                            ->dehydrated(false)
                            ->required(),

                        Select::make('unit_id')
                            ->label('Unit')
                            ->options(fn ($get) => Unit::query()
                                ->where('department_id', $get('department_id'))
                                ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->disabled(fn ($get): bool => ! filled($get('department_id')))
                            ->required(),
                    ]),

                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])
                    ->schema([
                        Select::make('position_id')
                            ->label(__('user.columns.position_id'))
                            ->relationship('positionRelation', 'title')
                            ->getOptionLabelFromRecordUsing(fn (Position $record) => $record->grade !== '-' 
                                ? "{$record->title} (Gred {$record->grade})" 
                                : $record->title
                            )
                            ->searchable(['title', 'grade'])
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['status'] = UserStatus::PENDING;
        return $data;
    }

     public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        if ($this->isRegisterRateLimited($this->data['email'] ?? '')) {
            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function (): Model {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        // Filament::auth()->login($user);

        // session()->regenerate();

        Notification::make()
            ->title('Pendaftaran Dihantar')
            ->body('Akaun anda sedang menunggu kelulusan pentadbir.')
            ->warning()
            ->send();

        // return app(RegistrationResponse::class);

        $this->redirect(filament()->getLoginUrl());

        return null;
    }
}
