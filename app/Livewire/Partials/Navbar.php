<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManager;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{

    public $total_items = 0;

    public function render()
    {
        return view('livewire.partials.navbar');
    }

    public function mount()
    {
        $this->total_items = count(CartManager::getAllCartItemsFromCookies());
    }

    #[On('UpdateCartItemsCounter')]
    public function updateCartItemsCounter($counter)
    {
        $this->total_items = $counter;
    }
}
