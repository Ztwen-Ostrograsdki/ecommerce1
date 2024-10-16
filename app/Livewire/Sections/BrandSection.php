<?php

namespace App\Livewire\Sections;

use App\Models\Brand;
use Livewire\Component;

class BrandSection extends Component
{
    public function render()
    {
        $brands = Brand::orderBy('name', 'asc')->where('is_active', 1)->get();


        return view('livewire.sections.brand-section',
            [
                'brands' => $brands,
            ]
        );
    }

    
}
