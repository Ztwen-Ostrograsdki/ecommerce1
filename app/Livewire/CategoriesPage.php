<?php
namespace App\Livewire;


use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;

#[Title('Les Catégories  - ZtweN eCOMMERCE')]
class CategoriesPage extends Component
{
    
    public function render()
    {
        $categories = Category::orderBy('name', 'asc')->where('is_active', 1)->get();

        return view('livewire.categories-page', 
            ['categories' => $categories]
        );
    }
}
