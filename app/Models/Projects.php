<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'dolibarr_ref',
    'description',
    'dolibarr_id',
    'dolibarr_entity',
    'dolibarr_last_sync',
    'qbt_id',
    'qbt_last_sync'
  ];

  protected $hidden = [
    'id'
  ];
}
