<?php

namespace App\Livewire;

use App\Filament\Resources\PortalPages\PortalPageResource;
use App\Models\PortalPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Livewire\Attributes\Computed;
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
    // public ?PortalPage $page = null;

    #[Computed]
    public function page(): ?PortalPage
    {
        if (! $this->currentSlug) {
            return null;
        }

        return PortalPage::firstOrNew(
        ['slug' => $this->currentSlug],
        [
            'title' => str($this->currentSlug)->replace('-', ' ')->title()->toString(),
            'description' => 'Panduan dan maklumat berkaitan Portal PDT Manjung.',
            'icon' => 'heroicon-o-question-mark-circle',
            'blocks' => [],
        ]
    );
    }

    #[On('load-portal-page')]
    public function loadAndOpen(string $slug): void
    {
        $this->currentSlug = $slug;

        unset($this->page);

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
            ->modalHeading(fn () => 'Kemaskini: ' . ($this->page?->title ?? $this->currentSlug))
            ->schema(function (Schema $schema) {
                return PortalPageResource::form($schema);
            })
            ->fillForm(fn () => [
                'slug' => $this->currentSlug,
                'title' => $this->page?->title,
                'description' => $this->page?->description,
                'icon' => $this->page?->icon,
                'blocks' => $this->page?->blocks ?? [],
            ])
            ->action(function (array $data) {
                $this->page = PortalPage::updateOrCreate(
                    ['slug' => $this->currentSlug],
                    [
                        'title' => $data['title'],
                        'description' => $data['description'] ?? null,
                        'icon' => $data['icon'] ?? 'heroicon-o-question-mark-circle',
                        'blocks' => $data['blocks'] ?? [],
                    ]
                );

                // unset($this->page);

            });
    }

    public function render()
    {
        return view('livewire.portal-content-modal');
    }
}
