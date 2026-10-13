<?php

namespace App\Filament\Resources\Feedback\Tables;

use App\Enums\FeedbackCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedbackTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Tajuk')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                TextColumn::make('rating')
                    ->label('Penilaian')
                    ->badge()
                    ->alignCenter()
                    ->color(fn (?int $state): string => match ($state) {
                        5 => 'success',
                        4 => 'info',
                        3 => 'warning',
                        1, 2 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?int $state): string => $state ? "★ {$state}/5" : '—')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('E-mel')
                    ->icon('heroicon-m-envelope')
                    ->placeholder('Tiada e-mel')
                    ->copyable()
                    ->copyMessage('Alamat e-mel disalin')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('message')
                    ->label('Mesej')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dihantar Pada')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(FeedbackCategory::class),

                SelectFilter::make('rating')
                    ->label('Tahap Pengalaman')
                    ->options([
                        5 => '5 Bintang (Sangat Berpuas Hati)',
                        4 => '4 Bintang (Berpuas Hati)',
                        3 => '3 Bintang (Sederhana)',
                        2 => '2 Bintang (Kurang Berpuas Hati)',
                        1 => '1 Bintang (Tidak Berpuas Hati)',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
