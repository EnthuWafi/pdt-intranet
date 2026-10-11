<?php

namespace App\Filament\Resources\PortalPages\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PortalPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->readOnly(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('content')
                    ->columnSpanFull(),
                TextInput::make('metadata'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('blocks'),
                 TextInput::make('title')
                    ->label('Tajuk')
                    ->required(),
                Builder::make('blocks')
                    ->label('Kandungan Blok')
                    ->blocks([
                        Builder\Block::make('heading')
                            ->label('Heading')
                            ->icon('heroicon-m-h-top')
                            ->schema([
                                TextInput::make('text')->required(),
                                \Filament\Forms\Components\Select::make('level')
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
                        Builder\Block::make('faq_accordion')
                            ->label('FAQ Accordion')
                            ->icon('heroicon-m-question-mark-circle')
                            ->schema([
                                Repeater::make('faqs')
                                    ->label('Soalan Lazim')
                                    ->schema([
                                        TextInput::make('question')->required(),
                                        RichEditor::make('answer')->required(),
                                    ])
                                    ->columns(1)
                            ]),
                    ])
                    ->required(),
            ]);
    }
}
