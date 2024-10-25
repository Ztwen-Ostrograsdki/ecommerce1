<?php

namespace App\Livewire\Auth;

use Akhaled\LivewireSweetalert\Toast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class EmailVerificationPage extends Component
{
    use Toast;

    public $email;

    public $email_verify_key;

    public $user;

    public $confirmed = false;

    public $key_expired = false;

    
    public function mount($email = null, $key = null)
    {
        if($email) $this->$email = $email;

        if($this->email){

            $user = User::where('email', $this->email)->whereNotNull('email_verify_key')->first();

            if($user){

                if($user->email_verified_at){

                    $this->confirmed = true;
                }
                else{

                    $this->user = $user;

                }

            }
            else{
                return abort(404, "La page est introuvable");
            }

        }

        if($key) $this->email_verify_key = $key;
    }

    
    public function render()
    {
        return view('livewire.auth.email-verification-page');
    }

    public function confirmEmail()
    {
        $status = false;

        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
        ]);

        if(!$this->user){

            $user = User::where('email', $this->email)->whereNotNull('email_verify_key')->first();

            if($user){

                if($user->email_verified_at){

                    $this->confirmed = true;
                }
                else{

                    $this->user = $user;

                }

            }
            else{
                return abort(404, "La page est introuvable");
            }

        }

        if($this->user && $this->email){

            $this->validate([
                'email_verify_key' => 'required|string',
            ]);

            $user = $this->user;

            if(!$user->email_verified_at){

                $hash_key = $user->email_verify_key;

                $this->key_expired = $user->updated_at >= 3600 * 24;

                if(!$this->key_expired){

                    if(Hash::check($this->email_verify_key, $hash_key)){

                        if(!$user->email_verified_at){
    
                            $user->forceFill([
                                'email_verify_key' => null,
                                'email_verified_at' => now(),
                            ])->setRememberToken(Str::random(60));
                 
                            $status = $user->save();
    
                        }
    
                        if($status){
    
                            return redirect(route('login'))->with('success', "Votre compte a été confirmé avec succès!");
                        }
                        else{
    
                            $message = "Une erreure s'est produite";
    
                            session()->flash('error', $message);
    
                            $this->toast($message, 'info', 7000);
    
                            $this->addError('email_verify_key', $message);
    
                        }
                    }
                    else{
    
                        $message = "La clé ne correspond pas";
    
                        session()->flash('error', $message);
    
                        $this->toast($message, 'info', 7000);
    
                        $this->addError('email_verify_key', $message);
        
                    }
                }
                else{

                    $message = "La clé a déjà expiré";
    
                    session()->flash('error', $message);

                    $this->toast($message, 'info', 7000);

                    $this->addError('email_verify_key', $message);
                }

            }
            else{

                $this->confirmed = true;

                $message = "Ce compte a déja été confirmé!";

                session()->flash('info', $message);

                $this->toast($message, 'info', 7000);

                return redirect(route('login'))->with('success', $message);
            }
        }

        
    }


}
