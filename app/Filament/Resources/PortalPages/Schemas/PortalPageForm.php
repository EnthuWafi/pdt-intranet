<?php

namespace App\Filament\Resources\PortalPages\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PortalPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])
                    ->schema([
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required(),
                    ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tajuk')
                            ->required()
                            ->columnSpan(2),

                        Select::make('icon')
                            ->label('Ikon')
                            ->options([
                                'heroicon-o-question-mark-circle' => 'Soalan / Bantuan (?)',
                                'heroicon-o-chat-bubble-left-right' => 'Mesej / FAQ',
                                'heroicon-o-phone' => 'Telefon / Hubungi',
                                'heroicon-o-information-circle' => 'Informasi (i)',
                                'heroicon-o-book-open' => 'Buku / Panduan',
                            ])
                            ->default('heroicon-o-question-mark-circle')
                            ->required()
                            ->columnSpan(1),
                    ]),

                Textarea::make('description')
                    ->label('Keterangan / Sari Kata')
                    ->rows(2)
                    ->placeholder('Contoh: Panduan penggunaan, akses dan sokongan Portal Intranet PDT Manjung.')
                    ->columnSpanFull(),
                Builder::make('blocks')
                    ->label('Kandungan Blok')
                    ->blocks([
                        Builder\Block::make('heading')
                            ->label('Heading')
                            ->icon('heroicon-m-hashtag')
                            ->schema([
                                TextInput::make('text')->required(),
                                Select::make('level')
                                    ->options([
                                        'h2' => 'H2',
                                        'h3' => 'H3',
                                        'h4' => 'H4',
                                    ])
                                    ->default('h2')
                                    ->required(),
                            ]),
                        Builder\Block::make('paragraph')
                            ->label('Paragraph')
                            ->icon('heroicon-m-bars-3-bottom-left')
                            ->schema([
                                Textarea::make('content')->required(),
                            ]),
                        Builder\Block::make('rich_text')
                            ->label('Rich Text')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                RichEditor::make('content')->required(),
                            ]),
                        Builder\Block::make('accordion')
                            ->label('Accordion')
                            ->icon('heroicon-m-question-mark-circle')
                            ->schema([
                                Repeater::make('faqs')
                                    ->label('Soalan Lazim')
                                    ->schema([
                                        TextInput::make('question')->required(),
                                        RichEditor::make('answer')->required(),
                                    ])
                                    ->columns(1)
                                    ->collapsible()
                            ]),
                        Builder\Block::make('info_cards')
                            ->label('Kad Maklumat (Grid 2 Kolum)')
                            ->icon('heroicon-m-squares-2x2')
                            ->schema([
                                Repeater::make('cards')
                                    ->label('Senarai Kad')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('icon')
                                                    ->label('Ikon')
                                                    ->options([
                                                        'help' => 'Help / Question Mark (?)',
                                                        'key' => 'Key / Akaun',
                                                        'shield' => 'Shield / Keselamatan',
                                                        'plus' => 'Plus (+) / Permohonan',
                                                        'bell' => 'Bell / Notifikasi',
                                                        'arrows' => 'Arrows / Integrasi',
                                                        'chat' => 'Chat / Ruang Sembang',
                                                        'alert' => 'Alert (!) / Masalah',
                                                    ])
                                                    ->default('help')
                                                    ->required()
                                                    ->columnSpan(1),

                                                TextInput::make('title')
                                                    ->label('Tajuk Kad')
                                                    ->required()
                                                    ->columnSpan(2),
                                            ]),

                                        Textarea::make('description')
                                            ->label('Keterangan')
                                            ->rows(3)
                                            ->required(),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->columns(1),
                            ]),
                    ])
                    ->collapsible()
                    ->cloneable()
                    ->required(),
            ]);
    }
}
