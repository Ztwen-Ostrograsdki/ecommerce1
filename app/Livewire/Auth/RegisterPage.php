<?php

namespace App\Livewire\Auth;

use Akhaled\LivewireSweetalert\Toast;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegisterPage extends Component
{
    use WithFileUploads, Toast;

    #[Validate('required|email|unique:users|min:3|max:255')]
    public $email;

    #[Validate('required|string|confirmed:password|min:5')]
    public $password;
    
    #[Validate('nullable|image|mimes:jpeg,png,jpg|max:2400')]
    public $profil_photo;

    #[Validate('required|string|min:5')]
    public $password_confirmation;

    #[Validate('required|string|min:3|max:255')]
    public $name;

    public function render()
    {
        return view('livewire.auth.register-page');
    }

    public function register()
    {

        if($this->validate()){

            if($this->profil_photo){

                $extension = $this->profil_photo->extension();

                $file_name = getdate()['year'].''.getdate()['mon'].''.getdate()['mday'].''.getdate()['hours'].''.getdate()['minutes'].''.getdate()['seconds']. '' .  Str::random(20);

                $this->profil_photo->storeAs('public/users/', $file_name . '.' . $extension);
            }

            if($this->profil_photo){
                $user = User::create([
                    'name' => Str::ucwords($this->name),
                    'password' => Hash::make($this->password),
                    'email' => $this->email,
                    'profil_photo' => 'users/' . $file_name . '.' . $extension,
                ]);
            }
            else{
                $user = User::create([
                    'name' => Str::ucwords($this->name),
                    'password' => Hash::make($this->password),
                    'email' => $this->email,
                ]);
            }

            if($user){

                $auth = auth()->login($user);

                $this->toast("Vous avez été enregistré avec succès! Veuillez vérifier votre boite mail afin de confirmer votre compte!", 'success');

                return redirect()->intended();
            }
            else{

                if($this->profil_photo){
                    
                    Storage::delete($file_name . '.' . $extension);
                }

            }
        }
        else{
            
        }

    }
}
