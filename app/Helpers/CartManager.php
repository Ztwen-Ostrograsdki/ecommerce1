<?php
namespace App\Helpers;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cookie;
use PhpParser\JsonDecoder;

class CartManager{

    /* add item to cart */

    public static function addItemToCart(int $product_id, $quantity = 1)
    {
        $carts_items = self::getAllCartItemsFromCookies();

        $already_exist_item = null;

        foreach($carts_items as $key => $item){

            if($item['product_id'] == $product_id){

                $already_exist_item = $key;

                break;
            }
        }

        if($already_exist_item !== null){

            $carts_items[$already_exist_item]['quantity'] = $carts_items[$already_exist_item]['quantity'] + $quantity;

            $carts_items[$already_exist_item]['total_amount'] = $carts_items[$already_exist_item]['quantity'] * $carts_items[$already_exist_item]['unit_amount'];


        }
        else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images', 'slug']);

            if($product){
                $carts_items[$product_id] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->images[0],
                    'quantity' => $quantity,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }

        self::addCartItemsToCookies($carts_items);

        return count($carts_items);
    }

    /* remove item from cart */

    public static function removeCartItem(int $product_id)
    {
        $carts_items = self::getAllCartItemsFromCookies();

        if(count($carts_items)){

            foreach($carts_items as $key => $item){
                if($item['product_id'] == $product_id){

                    //unset($carts_items[$product_id]);

                    unset($carts_items[$key]);
                }
            }
        }

        self::addCartItemsToCookies($carts_items);

        return $carts_items;

    }

    /* add cart items to cookies */

    public static function addCartItemsToCookies(array $carts_items)
    {
        Cookie::queue('carts_items', json_encode($carts_items), 60 * 24 * 30);
    }

    /* clear cart item from cookies */

    public static function clearCartItemsFromCookies()
    {
        Cookie::queue(Cookie::forget('carts_items'));

        return [];
    }

    /* get all cart item from cart */

    public static function getAllCartItemsFromCookies()
    {
        $carts_items = json_decode(Cookie::get('carts_items'), true);

        if(!$carts_items){

            $carts_items = [];
        }

        return $carts_items;
    }

    /* increment cart item quantity */

    public static function incrementItemQuantityToCart($product_id, $quantity = 1)
    {
        $carts_items = self::getAllCartItemsFromCookies();

        foreach($carts_items as $key => $item){

            if($item['product_id'] == $product_id){

                $carts_items[$key]['quantity'] = $carts_items[$key]['quantity'] + $quantity;

                $carts_items[$key]['total_amount'] = $carts_items[$key]['quantity'] * $carts_items[$key]['unit_amount'];

            }
        }

        self::addCartItemsToCookies($carts_items);

        return $carts_items;
    }

    /* derement cart item quantity */

    public static function decrementItemQuantityFromCart($product_id)
    {
        $carts_items = self::getAllCartItemsFromCookies();

        foreach($carts_items as $key => $item){

            if($item['product_id'] == $product_id){

                if($item[$key]['quantity'] > 1){

                    $carts_items[$key]['quantity']--;

                    $carts_items[$key]['total_amount'] = $carts_items[$key]['quantity'] * $carts_items[$key]['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookies($carts_items);

        return $carts_items;
    }

    /* computed grand total
        @return int 
    */

    public static function computedGrandTotal(array $items): int
    {
        return array_sum(array_column($items, 'total_amount'));
    }
    
    /* computed grand total
        @return int 
    */

    public static function getComputedGrandTotalValue(array $items): int
    {
        return array_sum(array_column($items, 'total_amount'));
    }

    

    



}