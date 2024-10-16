<?php

namespace App\Livewire;

use App\Helpers\CartManager;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Les Catégories  - ZtweN eCOMMERCE')]

class ProductsPage extends Component
{

    protected $listeners = [
        'OnFireBrandSelected' => 'reloadBrandSelected',
    ];

    use WithPagination;
    
    public $selected_categories = [];

    public $selected_brands = [];

    public $on_sale;

    public $is_featured;

    public $is_active;

    public $in_stock;

    public $step = 10000;

    public $price_range = 5000;

    public $min_price = 0;

    public $max_price = 90000000;

    public function reloadBrandSelected($brand_id)
    {
        dd($this);

        $this->selected_brands[] = $brand_id;
    }

    public function resetPriceRange()
    {
        $this->reset('price_range');
    }

    public function mount($c = null, $b = null) 
    {
        if($b){
            $brand = Brand::where('slug', $b)->firstOrFail();
            if($brand){
                $this->selected_brands[] = $brand->id;
            }
        }

        if($c){
            $category = Category::where('slug', $c)->firstOrFail();
            if($category){
                $this->selected_categories[] = $category->id;
            }
        }
    }

    public function addToCart(int $product_id)
    {
        $total_items = CartManager::addItemToCart($product_id);

        $this->dispatch('UpdateCartItemsCounter', $total_items)->to(Navbar::class);
        
    }

    public function render()
    {
        $query = Product::query()->orderBy('name', 'asc')->where('is_active', 1);

        if($query){

            $this->min_price = $query->min('price');

            $this->max_price = $query->max('price');

        }

        $products_status = config('app.products_status');

        if(!empty($this->selected_categories)){
            $query->whereIn('category_id', $this->selected_categories);
        }

        if(!empty($this->selected_brands)){
            $query->whereIn('brand_id', $this->selected_brands);
        }

        if($this->is_featured){
            $query->where('is_featured', 1);
        }

        if($this->on_sale){
            $query->where('on_sale', 1);
        }

        if($this->is_active){
            $query->where('is_active', 1);
        }

        if($this->in_stock){
            $query->where('in_stock', 1);
        }

        if($this->price_range && $this->price_range > $this->min_price){
            $query->whereBetween('price', [$this->min_price - ($this->min_price * 0.05), $this->price_range + ($this->price_range * 0.05)]);
        }





        $brands = Brand::orderBy('name', 'asc')->where('is_active', 1)->get(['id', 'name', 'slug']);

        $categories = Category::orderBy('name', 'asc')->where('is_active', 1)->get(['id', 'name', 'slug']);

        return view('livewire.products-page',
            [
                'products' => $query->paginate(6),
                'brands' => $brands,
                'categories' => $categories,
                'products_status' => $products_status,
            ]
        );
    }
}
