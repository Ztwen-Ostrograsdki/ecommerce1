<?php

namespace App\Livewire\Auth;

use Akhaled\LivewireSweetalert\Toast;
use App\Models\User;
use App\Notifications\SendEmailVerificationKeyToUser;
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

                $email_verify_key = Str::random(6);

                $user->notify(new SendEmailVerificationKeyToUser($email_verify_key));

                $auth = $user->forceFill([
                   'email_verify_key' => Hash::make($email_verify_key)
                ])->save();

                if($auth){

                    $message = "Incription lancée avec succès! Un courriel vous a été envoyé pour confirmation, veuillez vérifier votre boite mail.";

                    $this->toast($message, 'info', 5000);

                    session()->flash('success', $message);

                    return redirect(route('email.verification', ['email' => $this->email]))->with('success', "Confirmer votre compte en renseignant le code qui vous été envoyé!");
                    
                }
                else{

                    $user->delete();

                    if($this->profil_photo){
                    
                        Storage::delete($file_name . '.' . $extension);
                    }

                    redirect(route('register'))->with('error', "L'incription a échoué! Veuillez réessayer!");

                }
                
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
