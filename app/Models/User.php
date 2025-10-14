<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'username',
        'main_group',
        'sub_group',
        'password',
        'role',
        'is_active',
        'two_factor_enabled',
        'two_factor_authenticated_at',
        'is_two_factor_authenticated',
    ];

    public function mainGroup()
    {
        return $this->belongsTo(Group::class, 'main_group');
    }

    public function subGroup()
    {
        return $this->belongsTo(Group::class, 'sub_group');
    }


    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_group', 'user_id', 'group_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class, 'AFFECT', 'AFFECT');
    }

    public function isActive()
    {
        return $this->is_active;
    }
 

}

