<?php

namespace App\Livewire\Sections;

use App\Models\Category;
use Livewire\Component;

class CategorySection extends Component
{
    public function render()
    {
        $categories = Category::orderBy('name', 'asc')->where('is_active', 1)->get();


        return view('livewire.sections.category-section',
            [
                'categories' => $categories,
            ]
        );
    }
}
