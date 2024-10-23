<?php

namespace App\Livewire\Auth;

use Akhaled\LivewireSweetalert\Toast;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPasswordPage extends Component
{
    use Toast;

    #[Validate('required|string|confirmed|min:5')]
    public $password;
    
    public $token;

    public $password_reset_key;

    public $email;

    public $password_confirmation;

    public $not_request_sent = false;

    public function mount($token)
    {
        if($token) $this->token = $token;
    }

    
    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }

    public function savePassword()
    {
        if($this->token){

            $this->validate([
                'token' => 'required|string',
            ]);

        }
        else{

            $this->validate([
                'password_reset_key' => 'required|string',
                'email' => 'required|email|max:255|exists:users,email',
            ]);

            $user = User::where('email', $this->email)->whereNotNull('password_reset_key')->first();

            if($user){

                $hash_key = $user->password_reset_key;

                if(Hash::check($this->password_reset_key, $hash_key)){


                }
                else{

                    $message = "La clé ne correspond pas";

                    session()->flash('error', $message);

                    $this->toast($message, 'info', 7000);

                    $this->addError('email', $message);
    
                }

            }
            else{

                $this->not_request_sent = true;

                $message = "Aucune réinitialisation n'a été demandée pour ce compte";

                session()->flash('error', $message);

                $this->toast($message, 'info', 7000);

                $this->addError('email', $message);

            }
        }
    }

    public function updated($password_confirmation)
    {
        $this->validateOnly('password');
    }
}
