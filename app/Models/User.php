<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'employee_no', 'username', 'birthday', 'gender', 'marital_status', 'join_date', 'father', 'mother', 'company', 'address', 'address_2', 'phone', 'emergency_phone',
        'district', 'deliveryfee', 'postcode', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }

    public function role()
    {
        return $this->hasOne('App\Models\Role');
    }

    public function role_user()
    {
        return $this->hasOne('App\Models\Role_user');
    }

    public function isAdmin()
    {
        return $this->roles()->where('role_id', 1)->first();
    }

    public function isManager()
    {
        return $this->roles()->where('role_id', 2)->first();
    }

    public function isEditor()
    {
        return $this->roles()->where('role_id', 3)->first();
    }

    public function isProductManager()
    {
        return $this->roles()->where('role_id', 4)->first();
    }

    public function isInventoryManager()
    {
        return $this->roles()->where('role_id', 5)->first();
    }

    public function isAccountant()
    {
        return $this->roles()->where('role_id', 6)->first();
    }

    public function isOrderViewer()
    {
        return $this->roles()->where('role_id', 7)->first();
    }
    
    public function isMember()
    {
        return $this->roles()->where('role_id', 8)->first();
    }

    public function isEmployee()
    {
        return $this->roles()->where('role_id', 9)->first();
    }

    public function isVendor()
    {

        // auth()->user()->isVendor(); on blade and controller
        // user()->isVendor() on blade
        return $this->roles()->where('role_id', 10)->first();
    }

    public function isApplicant()
    {
        return $this->roles()->where('role_id', 11)->first();
    }

    public function isProduct()
    {

        return $this->roles()->whereIn('role_id', [1, 4])->first();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    public function isDP()
    {
        return $this->roles()->whereIn('role_id', [1, 9])->first();
    }


}
