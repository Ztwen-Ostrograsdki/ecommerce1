<?php


use Akhaled\LivewireSweetalert\Confirm;
use Livewire\Component;
use Livewire\Attributes\On;

class MyComponent extends Component
{
    use Confirm;

    public function save()
    {
        $this->confirm(
            event: 'savedConfirmed',
            data: [
                'key' => 'value',
            ]
        )
    }

    #[On('savedConfirmed')]
    public function onSavedConfirmations(array $data)
    {
        dd($data['key']); // value
    }
}