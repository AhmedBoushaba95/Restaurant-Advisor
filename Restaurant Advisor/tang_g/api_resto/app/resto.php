<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Menus;

class resto extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'user_id',
    'categorie',
    'description',
    'note',
    'address',
    'phone',
    'website',
    'open_week',
    'close_week',
    'open_weekend',
    'close_weekend'
  ];

  public function menus() {
    return $this->hasMany("App\Menus");
  }

  public function avis() {
    return $this->hasMany("App\Avis");
  }

  public function user() {
    return $this->hasOne("App\User");
  }
}
