<?php

namespace App\Livewire;

use Akhaled\LivewireSweetalert\Toast;
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

    use Toast;

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

    public $image_indexes = [];

    public $current_index = 0;

    public $max_price = 90000000;

    public $sections = [
        'by_created_at' => 'Les plus récents',
        'by_price_up' => 'Prix croissants',
        'by_price_down' => 'Prix décroissants',
        'by_more_bought' => 'Plus achétés',
        'by_name_up' => 'Noms croissants',
        'by_name_down' => 'Noms décroissants',
        'on_sale' => 'En vente',
        'on_news' => 'Nouveauté',
        null => 'Tout',

    ];

    public $selected_section = null;

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

        $this->toast('Toast message', 'success', 5000);

        $this->dispatch('UpdateCartItemsCounter', $total_items)->to(Navbar::class);

        
        
    }

    public function render()
    {
        $images_indexes = [];

        $query = Product::query()->where('is_active', 1);

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


        if($this->selected_section){

            if($this->selected_section !== null){

                $section = $this->selected_section;

                if($section == 'by_name_up'){
                    $query->orderBy('name', 'asc');
                }

                if($section == 'by_name_down'){
                    $query->orderBy('name', 'desc');
                }

                if($section == 'by_created_at'){
                    $query->orderBy('created_at', 'asc');
                }

                if($section == 'by_price_up'){
                    $query->orderBy('price', 'asc');
                }

                if($section == 'by_price_down'){
                    $query->orderBy('price', 'desc');
                }

                if($section == 'on_sale'){
                    $query->where('on_sale', 1);
                }
            }
        }


        $brands = Brand::orderBy('name', 'asc')->where('is_active', 1)->get(['id', 'name', 'slug']);

        $categories = Category::orderBy('name', 'asc')->where('is_active', 1)->get(['id', 'name', 'slug']);

        foreach($query->get() as $p){

            if($this->current_index == $p->id){

                $images = $p->images;

                $current = $this->image_indexes[$p->id]['current'];

                if($current + 1 < count($images)){

                    $index = $current + 1;
                }
                else{
                    $index = 0;
                }

                $this->image_indexes[$p->id] = [
                    'index' => $index, 
                    'current' => $index
                ];

            }
            else{
                if(isset($this->image_indexes[$p->id]) && $this->image_indexes[$p->id]['index'] !== 0){
                    $this->image_indexes[$p->id] = [
                        'index' => $this->image_indexes[$p->id]['index'], 
                        'current' => $this->image_indexes[$p->id]['index']
                    ];
                }
                else{
                    $this->image_indexes[$p->id] = [
                        'index' => 0, 
                        'current' => 0
                    ];
                }
               
            }
           
        }



        return view('livewire.products-page',
            [
                'products' => $query->paginate(6),
                'brands' => $brands,
                'categories' => $categories,
                'products_status' => $products_status,
            ]
        );
    }

    public function reloadImageIndex($product_id)
    {
        if($this->current_index !== $product_id){

            $this->current_index = $product_id;
        } 
    }
}
