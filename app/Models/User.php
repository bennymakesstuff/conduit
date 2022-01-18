<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, Billable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'created_by',
    'firstname',
    'lastname',
    'uuid',
    'last_login',
    'mobile',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'id',
    'password',
    'remember_token',
    'created_by',
    'updated_by',
    'deleted_by',
    'created_at',
    'updated_at',
    'deleted_at',
    'last_conduit_login',
    'roles',
    'email_verified_at',
    'stripe_id',
    'pm_type',
    'pm_last_four',
    'trial_ends_at',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'roles' => 'array'
  ];


  /**
   * Returns a list of associated roles for the user
   * @return array
   */
  public function getRoles() {
    // Get any roles the user has
    $roles = UserRoles::query()
      ->where('user', '=', $this->uuid)
      ->get();

    $role_set = [];

    if (!is_null($roles)) {
      foreach ($roles as $role_ref) {
        $role = Roles::query()
          ->where('uuid', '=', $role_ref->role)
          ->first();

        $role = $role->toArray();
        $role['active_from'] = $role_ref->created_at;

        if ($role) {
          $role_set []= $role;
        }
      }
    }

    return $role_set;
  }


  /**
   * Returns a permission set of unique permissions for the user
   * NOTE: Derived from all of the users current roles
   * @return array
   */
  public function getPermissionSet() {
    $roles = $this->getRoles();

    $permissions_unsorted = [];

    /** @var Roles $role $role */
    foreach ($roles as $role) {
      Log::info($role);
      $role = (object) $role;
      $role_permissions = json_decode($role->permissions);

      // Move onto the next set if the role has no permissions
      if ($role_permissions === null) {
        continue;
      }

      foreach($role_permissions as $permission) {
        $permissions_unsorted[] = $permission;
      }
    }

    return array_unique($permissions_unsorted);
  }


  public static function boot()
  {
    parent::boot();

    self::creating(function($model){
      // ... code here
    });

    self::created(function($model){

    });

    self::updating(function($model){
      // ... code here
    });

    self::updated(function($model){
      // ... code here
    });

    self::deleting(function($model){
      // ... code here
    });

    self::deleted(function($model){
      // ... code here
    });
  }
}
