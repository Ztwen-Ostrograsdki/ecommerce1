<?php

namespace App\Livewire;

use App\Helpers\CartManager;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Détails Article  - ZtweN eCOMMERCE')]
class ProductsDetailsPage extends Component
{
    public $slug;

    public $quantity = 1;

    public function render()
    {
        $slug = $this->slug;

        $product = Product::where('slug', $slug)->firstOrFail();

        return view('livewire.products-details-page',
            [
                'product' => $product,
            ]
        );
    }

    public function increaseQuantity()
    {
        $this->quantity++;
    }

    public function decreaseQuantity()
    {
        $this->quantity > 1 ? $this->quantity-- : $this->quantity = 1;
    }

    public function addToCart(int $product_id)
    {
        $total_items = CartManager::addItemToCart($product_id, $this->quantity);

        $this->dispatch('UpdateCartItemsCounter', $total_items)->to(Navbar::class);
        
    }


    public function mount($slug)
    {
        $this->slug = $slug;
    }


}
