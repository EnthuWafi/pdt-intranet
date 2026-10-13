<?php

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Feedback;
use App\Enums\FeedbackCategory;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\ToggleButtons;

new class extends Component implements HasActions, HasForms
{
    use InteractsWithForms, InteractsWithActions;

    public ?string $category = null;
    public ?string $title = null;
    public ?string $message = null;
    public ?int $rating = null;
    public ?string $email = null;

    #[On('open-feedback-modal')]
    public function openModal(): void
    {
        $this->form->fill();
        $this->dispatch('open-modal', id: 'feedback-modal');
    }

   public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Row 1: Kategori & Tajuk
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])
                    ->schema([
                        Select::make('category')
                            ->label('Kategori')
                            ->placeholder('Pilih kategori')
                            ->options([
                                'general' => __('feedback.category.general'),
                                'bug' => __('feedback.category.bug'),
                                'feature' => __('feedback.category.feature'),
                                'complaint' => __('feedback.category.complaint'),
                                'other' => __('feedback.category.other'),
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('title')
                            ->label('Tajuk')
                            ->placeholder('Contoh: Cadangan susun atur menu')
                            ->required()
                            ->maxLength(255),
                    ]),

                // Row 2: Maklum Balas
                Textarea::make('message')
                    ->label('Maklum Balas')
                    ->placeholder('Terangkan cadangan atau isu dengan ringkas dan jelas.')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                // Row 3: Rating Scale (1 - 5)
                ToggleButtons::make('rating')
                    ->label('Tahap pengalaman menggunakan Portal')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->inline()
                    ->nullable()
                    ->columnSpanFull(),

                // Row 4: E-mel
                TextInput::make('email')
                    ->label('E-mel untuk dihubungi semula (pilihan)')
                    ->placeholder('nama@perak.gov.my')
                    ->email()
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public function submitFeedbackAction(): Action
    {
        return Action::make('submitFeedback')
            ->label(__('common.footer.give_feedback'))
            ->color('warning')
            ->action(function () {
                $data = $this->form->getState();

                Feedback::create($data);

                Notification::make()
                    ->title('Maklum balas berjaya dihantar. Terima kasih!')
                    ->success()
                    ->send();

                $this->form->fill();
                $this->dispatch('close-modal', id: 'feedback-modal');
            });
    }
};
?>

<div>
    <x-filament::modal id="feedback-modal" width="5xl" :close-button="false">
        {{-- Custom Modal Header --}}
        <div class="relative overflow-hidden -mx-6 -mt-6 p-6 pb-5 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 rounded-t-2xl">
            {{-- Diagonal Accent Bar in Top-Right --}}
            <div class="absolute -top-6 right-36 w-6 h-28 bg-yellow-300 dark:bg-yellow-400 rotate-45 pointer-events-none opacity-80"></div>

            <div class="flex items-start justify-between gap-4 relative z-10">
                <div class="flex items-start gap-4">
                    {{-- Left Yellow Square Badge --}}
                    <div class="w-12 h-12 shrink-0 rounded-2xl bg-yellow-400 flex items-center justify-center text-black shadow-sm">
                        <x-filament::icon
                            icon="heroicon-o-chat-bubble-bottom-center-text"
                            class="w-7 h-7 text-neutral-900"
                        />
                    </div>

                    {{-- Title & Subtitle Group --}}
                    <div>
                        <span class="block text-[11px] font-bold tracking-widest text-amber-700 dark:text-amber-400 uppercase">
                            {{ __('common.organization.portallong') }}
                        </span>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight mt-0.5">
                            {{ __('common.footer.give_feedback') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Sila berikan pandangan, cadangan atau aduan anda untuk penambahbaikan portal.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button 
                        type="button" 
                        x-on:click="$dispatch('close-modal', { id: 'feedback-modal' })"
                        class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 transition"
                    >
                        <x-heroicon-m-x-mark class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Body: Form --}}
        <div class="py-4 space-y-4">
             <div class="p-4 rounded-r-xl border-l-4 border-yellow-400 bg-amber-50/70 dark:bg-amber-950/20 text-slate-600 dark:text-slate-300 text-xs sm:text-sm leading-relaxed">
                Maklum balas digunakan untuk membantu penambahbaikan Portal Intranet PDT Manjung sepanjang fasa testing. Sila elakkan memasukkan kata laluan atau maklumat sulit.
            </div>
            {{ $this->form }}
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
            <x-filament::button
                type="button"
                color="gray"
                x-on:click="$dispatch('close-modal', { id: 'feedback-modal' })"
            >
                Batal
            </x-filament::button>

            {{ $this->submitFeedbackAction }}
        </div>
    </x-filament::modal>

    <x-filament-actions::modals />
</div>
