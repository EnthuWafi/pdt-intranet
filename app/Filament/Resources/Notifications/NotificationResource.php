<?php

namespace App\Filament\Resources\Notifications;

use App\Filament\Resources\Notifications\Pages\ManageNotifications;
use App\Models\Notification;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
        return __('notification.title');
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

    // public static function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('type')
    //                 ->required(),
    //             TextInput::make('notifiable_type')
    //                 ->required(),
    //             TextInput::make('notifiable_id')
    //                 ->required()
    //                 ->numeric(),
    //             Textarea::make('data')
    //                 ->required()
    //                 ->columnSpanFull(),
    //             DateTimePicker::make('read_at'),
    //         ]);
    // }

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
                IconColumn::make('read_at')
                    ->label('Status')
                    ->icon(fn ($state): string => is_null($state) ? 'heroicon-s-envelope' : 'heroicon-o-envelope-open')
                    ->color(fn ($state): string => is_null($state) ? 'primary' : 'gray')
                    ->tooltip(fn ($state): string => is_null($state) ? 'Unread' : 'Read'),

                TextColumn::make('data.title')
                    ->label('Title')
                    ->weight(fn (Notification $record): string => $record->unread() ? 'bold' : 'normal')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('data->title', 'ilike', "%{$search}%");
                    }),

                TextColumn::make('data.body')
                    ->label('Message')
                    ->limit(60)
                    ->color('gray')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('data->body', 'ilike', "%{$search}%");
                    }),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('unread')
                    ->label('Unread Only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('read_at')),
            ])
            ->actions([
                Action::make('view_details')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->modalHeading(fn (Notification $record): string => $record->data['title'] ?? 'Notification Details')
                    ->modalDescription(fn (Notification $record): string => $record->data['body'] ?? '')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->action(function (Notification $record) {
                        $record->markAsRead();
                    }),

                Action::make('mark_as_read')
                    ->label('Mark Read')
                    ->icon('heroicon-m-check')
                    ->color('gray')
                    ->visible(fn (Notification $record): bool => $record->unread())
                    ->action(fn (Notification $record) => $record->markAsRead()),

                Action::make('mark_as_unread')
                    ->label('Mark Unread')
                    ->icon('heroicon-m-arrow-uturn-left')
                    ->color('gray')
                    ->visible(fn (Notification $record): bool => $record->read())
                    ->action(fn (Notification $record) => $record->markAsUnread()),
            ])
            ->bulkActions([
                BulkAction::make('mark_all_read')
                    ->label('Mark Selected as Read')
                    ->icon('heroicon-m-check')
                    ->action(fn (Collection $records) => $records->each->markAsRead()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageNotifications::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        // Return false to prevent creating records
        return false;
    }
}
