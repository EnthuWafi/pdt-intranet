<?php

namespace App\Filament\Resources\Notifications;

use App\Filament\Resources\Notifications\Pages\ManageNotifications;
use App\Models\Notification;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bell;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.my_work');
    }
    public static function getModelLabel(): string
    {
        return __('navigation.title');
    }


    public static function getNavigationBadge(): ?string
    {
        $count = auth()->user()?->unreadNotifications()->count() ?? 0;

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    /**
     * Strictly limits the table query to the authenticated user's records.
     */
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where('notifiable_type', $user->getMorphClass())
            ->where('notifiable_id', $user->getKey())
            ->latest();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                TextInput::make('notifiable_type')
                    ->required(),
                TextInput::make('notifiable_id')
                    ->required()
                    ->numeric(),
                Textarea::make('data')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('read_at'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('type'),
                TextEntry::make('notifiable_type'),
                TextEntry::make('notifiable_id')
                    ->numeric(),
                TextEntry::make('data')
                    ->columnSpanFull(),
                TextEntry::make('read_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('notifiable_type')
                    ->searchable(),
                TextColumn::make('notifiable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('read_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageNotifications::route('/'),
        ];
    }
}
