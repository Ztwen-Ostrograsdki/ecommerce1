<?php

namespace App\Livewire;

use App\Helpers\CartManager;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;


#[Title('Validation de mon panier  - ZtweN eCOMMERCE')]
class CheckoutPage extends Component
{
    public $carts_items = [];

    public $grand_total;

    public $shipping_price = 1000;

    public $order_id;

    public $taxe = 0;

    public $counter = 0;

    #[Validate('required')]
    public $address;

    #[Validate('required')]
    public $first_name;

    #[Validate('required')]
    public $last_name;

    #[Validate('required')]
    public $street_address;

    #[Validate('required')]
    public $city;

    #[Validate('required')]
    public $state;

    #[Validate('required')]
    public $zip_code;

    #[Validate('required')]
    public $phone;

    #[Validate('required')]
    public $image;


    public function mount()
    {
        $this->carts_items = CartManager::getAllCartItemsFromCookies();

        $this->grand_total = CartManager::getComputedGrandTotalValue($this->carts_items);
    }
    
    public function render()
    {
        $payments_methods = config('app.payments_methods');

        return view('livewire.checkout-page', 
            [
                'payments_methods' => $payments_methods
            ]
        );
    }

    public function checkout()
    {
        $this->validate();
    }
}
