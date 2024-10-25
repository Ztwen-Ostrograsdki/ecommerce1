<?php
namespace App\Helpers\Dater\TraitsManagers\Users;

use App\Notifications\SendEmailVerificationKeyToUser;
use App\Notifications\SendPasswordResetKeyToUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



trait UserTrait{

    public function emailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    public function emailNotVerified()
    {
        return is_null($this->email_verified_at);
    }

    public function markAsVerified()
    {
        if($this->emailNotVerified()){
    
            $this->forceFill([
                'email_verify_key' => null,
                'email_verified_at' => now(),
            ])->setRememberToken(Str::random(60));
 
            $this->save();

        }

        return $this->emailVerified();
    }

    public function sendVerificationLinkOrKeyToUser()
    {
        if($this->emailVerified()){

            return $this;
        }

        $email_verify_key = Str::random(6);

        $this->notify(new SendEmailVerificationKeyToUser($email_verify_key));

        $auth = $this->forceFill([
            'email_verify_key' => Hash::make($email_verify_key)
        ])->save();

        return $this;
    }

    public function sendPasswordResetKeyToUser(string $key = null)
    {
        $password_reset_key = Str::random(6);
        
        if($key) $password_reset_key = $key;

        $this->notify(new SendPasswordResetKeyToUser($password_reset_key));

        $this->forceFill([
            'password_reset_key' => Hash::make($password_reset_key)
        ])->save();
    }




    




}