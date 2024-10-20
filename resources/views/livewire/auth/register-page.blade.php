<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="flex h-full items-center">
      <main class="w-full max-w-lg mx-auto p-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
          <div class="p-4 sm:p-7">
            <div class="text-center">
              <h1 class="block text-2xl font-bold text-gray-800 dark:text-white uppercase">inscription</h1>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Vous avez déjà un compte?
                <a class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="{{route('login')}}">
                  Connectez-vous ici
                </a>
              </p>
              <div class=" bg-transparent w-full p-2 text-center py-6 my-2" wire:loading wire:target='profil_photo'>
                <b class=" text-yellow-700 text-center">
                  Chargement photo en cours... Veuillez patientez!
                </b>
              </div>
              @if($profil_photo)
              <div class="flex justify-center rounded-full p-2 my-2" >
                  <img wire:loaded wire:target='profil_photo' class="mt-1  h-40 w-40 border rounded-full" src="{{$profil_photo->temporaryUrl()}}" alt="">
              </div>
              @else
              <div class="flex justify-center @if(!$name) hidden @endif rounded-full p-2 my-2" >
                <b wire:loaded.remove wire:target='profil_photo' class="pt-2 text-9xl  bg-gray-900 uppercase text-center align-middle text-gray-400 h-40 w-40 border rounded-full">
                  {{ Str::upper(Str::substr($name, 0, 1)) }}
                </b>
            </div>
              @endif
            </div>
            <hr class="my-5 border-slate-300">
            <!-- Form -->
            <form wire:submit.prevent='register'>
              <div class="grid gap-y-4">
                <!-- Form Group -->
                <div>
                  <label for="name" class="block text-sm mb-2 cursor-pointer dark:text-white">Votre Pseudo</label>
                  <div class="relative">
                    <input placeholder="Renseignez votre pseudo..." wire:model.live='name' type="text" id="name" name="name" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="email-error">
                    @error('name')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('name')
                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label for="email" class="block text-sm mb-2 cursor-pointer dark:text-white">Adresse mail</label>
                  <div class="relative">
                    <input placeholder="Renseignez votre adresse mail" wire:model.live='email' type="email" id="email" name="email" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="email-error">
                    @error('email')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('email')
                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>
                <!-- End Form Group -->
  
                <!-- Form Group -->
                <div>
                  <div class="flex justify-between items-center">
                    <label for="password" class="block text-sm mb-2 cursor-pointer dark:text-white">Mot de passe</label>
                  </div>
                  <div class="relative">
                    <input placeholder="Choisissez un mot de passe" wire:model.live='password' type="password" id="password" name="password" class="py-3 border px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="password-error">
                    @error('password_confirmation')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('password')
                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <div class="flex justify-between items-center">
                    <label for="password_confirmation" class="block text-sm mb-2 cursor-pointer dark:text-white">Confirmez</label>
                  </div>
                  <div class="relative">
                    <input placeholder="Confirmez le mot de passe..." wire:model.live='password_confirmation' type="password" id="password_confirmation" name="password_confirmation" class="py-3 border px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="password_confirmation-error">
                    @error('password_confirmation')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('password_confirmation')
                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <div class="flex justify-between items-center">
                    <label for="profil_photo" class="block text-sm mb-2 cursor-pointer dark:text-white">Photo de profil (Facultative)</label>
                  </div>
                  <div class="relative">
                    <input placeholder="Choisissez une photo de profil" wire:model='profil_photo' type="file" id="profil_photo" name="profil_photo" class="py-3 border px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="profil_photo-error">
                    @error('profil_photo')
                    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                      <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                      </svg>
                    </div>
                    @enderror
                  </div>
                  @error('profil_photo')
                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>
                <!-- End Form Group -->
                <span wire:click='register' class="w-full cursor-pointer py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                  S'inscrire
                </span>
              </div>
            </form>
            <!-- End Form -->
          </div>
        </div>
    </div>
  </div>