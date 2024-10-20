<?php

namespace App\Livewire\Partials;

use Akhaled\LivewireSweetalert\Toast;
use App\Helpers\CartManager;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{

    use Toast;

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

    public function clearCart()
    {
        $carts_items = CartManager::clearCartItemsFromCookies();

        $this->dispatch('UpdateCartItemsCounter', count($carts_items));

    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        $this->toast("Vous avez été déconneté avec succès!!!");

        return redirect(route('login'));

    }
}
