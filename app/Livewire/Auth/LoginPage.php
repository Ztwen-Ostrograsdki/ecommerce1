<?php

namespace App\Livewire\Auth;

use Akhaled\LivewireSweetalert\Toast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginPage extends Component
{
    use Toast;

    #[Validate('required|email|exists:users|min:3|max:255')]
    public $email;

    #[Validate('required|string|min:5')]
    public $password;
    
    public function render()
    {
        return view('livewire.auth.login-page');
    }

    public function login()
    {
        if($this->validate()){

            $data = ['email' => $this->email, 'password' => $this->password];

            if(Auth::attempt($data)){

                $this->toast("Connexion établie!", 'success');

                request()->session()->regenerate();

                return redirect()->intended();
            }
            else{
                $this->toast("Aucune correspondance touvée", 'error');

                $this->addError('email', "Les informations ne correspondent pas");

                $this->addError('password', "Les informations ne correspondent pas");

                $this->reset('password');

                return back()->withErrors(["email" => "Les informations ne correspondent pas!"])->onlyInput('email');
            }

        }

    }
}
