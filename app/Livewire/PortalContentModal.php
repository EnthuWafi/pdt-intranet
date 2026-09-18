<?php

namespace App\Livewire;

use App\Models\PortalPage;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
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

    #[On('open-portal-page-modal')]
    public function loadAndOpen(string $slug, string $title): void
    {
        $this->currentSlug = $slug;

        $this->page = PortalPage::firstOrNew(
            ['slug' => $slug],
            ['title' => str($slug)->replace('-', ' ')->title()->toString()]
        );

        $this->mountAction('editContent');
        
    }

    public function editContentAction(): Action
    {
        $canUpdate = auth()->user()?->can('update', PortalPage::class) ?? false;

        return Action::make('editContent')
            ->authorize($canUpdate)
            ->heading(fn () => 'Kemaskini: ' . $this->page?->title)
            ->form([
                RichEditor::make('content')
                    ->label('Kandungan')
                    ->required()
                    ->disabled(!$canUpdate),
            ])
            ->fillForm(fn () => [
                'content' => $this->page?->content,
            ])
            ->disabledFormActions(!$canUpdate) 
            ->action(function (array $data) {
                PortalPage::updateOrCreate(
                    ['slug' => $this->currentSlug],
                    ['title' => $this->currentTitle, 'content' => $data['content']]
                );
            });
    }
    public function render()
    {
        return view('livewire.portal-content-modal');
    }
}
