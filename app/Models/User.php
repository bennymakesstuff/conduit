<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, Billable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
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

}
