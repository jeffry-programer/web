<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerificationEmail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmailNew;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profiles_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'session_active',
        'birthdate',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function store()
    {
        return $this->hasOne(Store::class, 'users_id');
    }

    public function updateProfilePhoto(UploadedFile $photo, $storagePath = 'images-user')
    {
        tap($this->profile_photo_path, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'image' => $photo->storePublicly(
                    $storagePath,
                    ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    public function sendEmailVerificationNotification()
    {
        // Verificar y desencriptar el email si está encriptado
        try {
            $this->email = Crypt::decrypt($this->email);
        } catch (\Exception $e) {
            // Si el email no está encriptado, no pasa nada
        }

        // Enviar la notificación de verificación
        $this->notify(new CustomVerificationEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        // Verificar y desencriptar el email si está encriptado
        try {
            $this->email = Crypt::decrypt($this->email);
        } catch (\Exception $e) {
            // Si el email no está encriptado, no pasa nada
        }
        
        $this->notify(new CustomResetPassword($token));
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profiles_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'users_id');
    }

    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    // Método markEmailAsVerified
    public function markEmailAsVerified()
    {
        // Si el correo ya está verificado, simplemente devuelve false
        if ($this->hasVerifiedEmail()) {
            return false;
        }

        // Marca el correo como verificado asignando la fecha y hora actual
        $this->email_verified_at = now();
        $this->save();

        // Dispara el evento Verified
        event(new \Illuminate\Auth\Events\Verified($this));

        return true;
    }
}
