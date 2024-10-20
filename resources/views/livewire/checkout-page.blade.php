<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
	<h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
		Payement
	</h1>
	<div class="grid grid-cols-12 gap-4">
		<div class="md:col-span-12 lg:col-span-8 col-span-12">
			<!-- Card -->
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<!-- Shipping Address -->
				<div class="mb-6">
					<h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
						Adresse de livraion
					</h2>
					<div class="grid grid-cols-2 gap-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="first_name">
								Nom du receveur
							</label>
							<input name="first_name" wire:model='first_name' placeholder="Renseignez le nom du receveur..." class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="first_name" type="text">
							</input>
							@error('first_name')
								<small class="text-red-600"> {{ $message }} </small>
							@enderror
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="last_name">
								Prénoms du receveur
							</label>
							<input name="last_name" wire:model='last_name' placeholder="Renseignez les Prénoms du receveur..." class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="last_name" type="text">
							</input>
							@error('last_name')
								<small class="text-red-600"> {{ $message }} </small>
							@enderror
						</div>
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="phone">
							Contacts
						</label>
						<input name="phone" wire:model='phone' placeholder="Renseignez les contacts du receveur..." class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="phone" type="text">
						</input>
						@error('phone')
							<small class="text-red-600"> {{ $message }} </small>
						@enderror
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="street_address">
							Adresse
						</label>
						<input name="street_address" wire:model='street_address' placeholder="Renseignez l'adresse de réception..." class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="street_address" type="text">
						</input>
						@error('street_address')
							<small class="text-red-600"> {{ $message }} </small>
						@enderror
					</div>
					<div class="mt-4">
						<label class="block text-gray-700 dark:text-white mb-1" for="city">
							Ville
						</label>
						<input name="city" wire:model='city' placeholder="Renseignez la ville de réception..." class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="city" type="text">
						</input>
					</div>
					<div class="grid grid-cols-2 gap-4 mt-4">
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="state">
								Etat ou Commune
							</label>
							<input name="state" wire:model='state' placeholder="Dans quel état ou commune se trouve le récepteur...?" class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="state" type="text">
							</input>
						</div>
						<div>
							<label class="block text-gray-700 dark:text-white mb-1" for="zip_code">
								Code ZIP ou Postal
							</label>
							<input name="zip_code" wire:model='zip_code' placeholder="Renseignez un code ZIP ou postal..." class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none" id="zip_code" type="text">
							</input>
						</div>
					</div>
				</div>
				<div for="payment_method" class="text-lg font-semibold dark:text-white mb-4">
                    <hr class="bg-slate-400 my-1 h-1 rounded">
					Méthode de Payement
                    <hr class="bg-slate-400 my-1 h-1 rounded">
				</div>
				<div class="w-full flex justify-between gap-4 mt-4">
					<div class="w-7/12">
						<select name="payment_method" wire:model='payment_method' class="bg-slate-600 py-2 border rounded-xl w-full text-center text-lg" name="" id="payment_method">
							<option value="{{ null }}"> Sélectionner la méthode de payement </option>
							@foreach ($payments_methods as $key => $methd)
								<option value="{{ $key }}"> {{ $methd }} </option>
							@endforeach
						</select>
					</div>
					<div class="w-3/12">
						<a href="{{route('my_cart')}}" class="bg-blue-500 w-full py-3 rounded-xl block text-center text-lg text-white hover:bg-blue-700">
							Revenir au panier
						</a>
					</div>
				</div>
				
			</div>
			<!-- End Card -->
		</div>
		<div class="md:col-span-12 lg:col-span-4 col-span-12">
			<div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold text-center text-gray-100 dark:text-white mb-2">
					Détails de la commande
				</div>
                <hr class="bg-slate-400 my-4 h-1 rounded">
				<div class="flex justify-between mb-2 dark:text-white font-bold">
					<span>
						Sous total
					</span>
					<span>
						{{ Number::currency($grand_total, 'CFA') }}
					</span>
				</div>
				<div class="flex justify-between mb-2  dark:text-white font-bold">
					<span>
						Taxes
					</span>
					<span>
						0.00
					</span>
				</div>
				<div class="flex justify-between mb-2  dark:text-white font-bold">
					<span>
						Coût de la livraion
					</span>
					<span>
						0.00
					</span>
				</div>
				<hr class="bg-slate-400 my-4 h-1 rounded">
				<div class="flex justify-between mb-2  dark:text-white font-bold">
					<span>
						Montant total
					</span>
					<span>
						{{ Number::currency($grand_total, 'CFA') }}
					</span>
				</div>
				</hr>
			</div>
			<button wire:click='checkout' class="bg-green-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-green-700">
				Soumettre l'achat
			</button>
			<div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
				<div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
					Détails du panier <span class="float-right text-orange-500">{{ App\Helpers\Dater\Formattors\Formattors::numberZeroFormattor(count($carts_items)) }} article(s)</span>
				</div>
				<ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
					@foreach ($carts_items as $product_id => $item)
					<li class="py-3 sm:py-4">
						<div class="flex items-center">
							<div class="flex-shrink-0">
								<img alt="Neil image" class="w-12 h-12 rounded" src="{{url('storage', $item['image'])}}">
								</img>
							</div>
							<div class="flex-1 min-w-0 ms-4">
								<p class="text-sm font-medium text-gray-900 truncate dark:text-white">
									{{ $item['name'] }}
								</p>
								<p class="text-sm text-gray-500 truncate dark:text-gray-400">
									Quantité: {{ App\Helpers\Dater\Formattors\Formattors::numberZeroFormattor($item['quantity']) }}
								</p>
							</div>
							<div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
								{{ Number::currency($item['total_amount'], 'CFA') }}
							</div>
						</div>
					</li>
					@endforeach
					
				</ul>
			</div>
		</div>
	</div>
</div>