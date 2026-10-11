<?php

namespace App\Livewire;

use App\Models\PortalPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Livewire\Component;
use Livewire\Attributes\On;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class PortalContentModal extends Component implements HasActions, HasForms
{
    use InteractsWithForms, InteractsWithActions;

    public ?string $currentSlug = null;
    public ?PortalPage $page = null;

    #[On('load-portal-page')]
    public function loadAndOpen(string $slug): void
    {
        $this->currentSlug = $slug;

        $this->page = PortalPage::firstOrNew(
            ['slug' => $slug],
            ['title' => str($slug)->replace('-', ' ')->title()->toString()]
        );

        $this->dispatch('open-modal', id: 'portal-content-modal');
    }

    public function editContentAction(): Action
    {
        $canUpdate = auth()->user()?->can('update', $this->page) ?? false;

        return Action::make('editContent')
            ->label('Kemaskini Kandungan')
            ->icon('heroicon-m-pencil-square')
            ->color('warning')
            ->authorize($canUpdate)
            ->heading(fn () => 'Kemaskini: ' . ($this->page?->title ?? $this->currentSlug))
            ->form([
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
                                \Filament\Forms\Components\Textarea::make('content')->required(),
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
            ])
            ->fillForm(fn () => [
                'title' => $this->page?->title,
                'blocks' => $this->page?->blocks ?? [],
            ])
            ->action(function (array $data) {
                $this->page = PortalPage::updateOrCreate(
                    ['slug' => $this->currentSlug],
                    [
                        'title' => $data['title'],
                        'blocks' => $data['blocks'],
                    ]
                );
            });
    }

    public function render()
    {
        return view('livewire.portal-content-modal');
    }
}
