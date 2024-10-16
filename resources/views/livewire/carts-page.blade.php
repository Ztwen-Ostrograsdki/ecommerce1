<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-semibold text-gray-400 mb-4">Panier d'achat</h1>
      <div class="flex flex-col md:flex-row gap-4">
        <div class="md:w-3/4">
          <div class="bg-gray-600 overflow-x-auto rounded-lg border shadow-md p-6 mb-4">
            @if (count($carts_items))
            <table class="w-full">
              <thead>
                <tr class="bg-slate-700 border rounded">
                  <th class="text-left font-semibold p-2">Article</th>
                  <th class="text-left font-semibold">Prix</th>
                  <th class="text-left font-semibold">Quantité</th>
                  <th class="text-left font-semibold">Total</th>
                  <th class="text-left font-semibold">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($carts_items as $item)
                <tr wire:key='{{ $item['product_id'] }}'>
                  <td class="py-4">
                    <div class="flex items-center">
                      <a href="{{route('product', ['slug' => $item['slug']])}}">
                        <img class="h-16 w-16 mr-4 border" src="{{url('storage', $item['image'])}}" alt="{{ $item['name'] }}">
                      </a>
                      <span class="font-semibold">{{ $item['name'] }}</span>
                    </div>
                  </td>
                  <td class="py-4"><b>{{ Number::currency($item['unit_amount'], 'CFA') }}</b></td>
                  <td class="py-4">
                    <div class="flex items-center">
                      <button class="border rounded-md hover:bg-orange-500 py-2 px-4 mr-2">-</button>
                      <span class="text-center w-8"> {{ $item['quantity'] >= 10 ? $item['quantity'] : '0' . $item['quantity']  }} </span>
                      <button wire:click='incrementQuantity({{$item['product_id']}})' class="border rounded-md hover:bg-green-600 py-2 px-4 ml-2">+</button>
                    </div>
                  </td>
                  <td class="py-4 "> <b>{{ Number::currency($item['total_amount'], 'CFA') }}</b></td>
                  <td><button class="bg-slate-300 border-2 rounded-lg px-3 py-1 border-dark hover:bg-red-500 hover:text-white hover:border-red-700">Retirer</button></td>
                </tr>
                @empty
                  
                @endforelse
                
                <!-- More product rows -->
              </tbody>
            </table>
            @else
            <div class="w-full my-8 bg-slate-800 rounded-3xl py-9 border flex justify-center">
              <b class="text-orange-300 text-2xl text-center">Oupps, Le panier est vide!!!</b>
            </div>
            <div class="md">
              <a class=" w-full mt-10" href="{{route('products.home')}}">
                <h3 class="bg-blue-500 hover:bg-green-600 text-white text-2xl rounded-lg text-center py-4 mt-4 w-full">
                  <b>Faire du shopping</b>
                </h3>
              </a>
            </div>
            @endif
          </div>
        </div>
        @if (count($carts_items))
        <div class="md:w-1/4">
          <div class="bg-gray-600 border rounded-3xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Facture</h2>
            <hr class="my-2 text-slate-800 bg-slate-500">
            <div class="flex justify-between mb-2">
              <span>Sous total</span>
              <span>
                <b>{{ Number::currency($grand_total, 'CFA') }}</b>
              </span>
            </div>
            <div class="flex justify-between mb-2">
              <span>Taxes</span>
              <span>{{ $taxe }}</span>
            </div>
            <div class="flex justify-between mb-2">
              <span>Prix de livraison</span>
              <span>{{ $shipping_price }}</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between mb-2">
              <span class="font-semibold">Total</span>
              <span class="font-semibold">
                <b>{{ Number::currency(($grand_total + $taxe + $shipping_price), 'CFA') }}</b>
              </span>
            </div>
            <button class="bg-blue-500 text-white py-2 px-4 hover:bg-green-700 hover:border rounded-lg mt-4 w-full">Payer</button>

            <button wire:click='clearCart' class="bg-red-600 text-white py-2 px-4 hover:bg-red-700 hover:border rounded-lg mt-4 w-full">Vider le panier</button>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>