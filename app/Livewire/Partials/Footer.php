<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManager;
use Livewire\Attributes\On;
use Livewire\Component;

class Footer extends Component
{

    public $cart_empty = true;
    
    public function render()
    {
        return view('livewire.partials.footer');
    }

    public function mount()
    {
        $this->cart_empty = count(CartManager::getAllCartItemsFromCookies()) == 0;
    }

    public function clearCart()
    {
        $carts_items = CartManager::clearCartItemsFromCookies();

        $this->dispatch('UpdateCartItemsCounter', count($carts_items));

    }

    #[On('UpdateCartItemsCounter')]
    public function updateCartItemsCounter($counter)
    {
        $this->cart_empty = $counter == 0;
    }
}
