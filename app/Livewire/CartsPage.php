<?php

namespace App\Livewire;

use App\Helpers\CartManager;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mon Panier  - ZtweN eCOMMERCE')]
class CartsPage extends Component
{
    public $carts_items = [];

    public $grand_total;

    public $shipping_price = 1000;

    public $taxe = 0;

    public $counter = 0;

    public function mount()
    {
        $this->carts_items = CartManager::getAllCartItemsFromCookies();

        $this->grand_total = CartManager::getComputedGrandTotalValue($this->carts_items);
    }

    public function incrementQuantity(int $product_id)
    {
        $this->carts_items = CartManager::incrementItemQuantityToCart($product_id);

        $this->grand_total = CartManager::getComputedGrandTotalValue($this->carts_items);

    }

    public function clearCart()
    {
        $this->carts_items = CartManager::clearCartItemsFromCookies();

        $this->dispatch('UpdateCartItemsCounter', count($this->carts_items))->to(Navbar::class);

        $this->grand_total = CartManager::getComputedGrandTotalValue($this->carts_items);

    }

    public function render()
    {
        return view('livewire.carts-page');
    }
}
