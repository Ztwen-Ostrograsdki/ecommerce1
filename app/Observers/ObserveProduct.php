<?php

namespace App\Observers;

use App\Events\EventNewProductWasCreated;
use App\Models\Product;

class ObserveProduct
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        broadcast(new EventNewProductWasCreated($product));
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        broadcast(new EventNewProductWasCreated($product));
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        broadcast(new EventNewProductWasCreated($product));
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
