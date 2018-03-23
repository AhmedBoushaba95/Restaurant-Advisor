<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
  protected $fillable = [
    'id',
    'user_id',
    'resto_id',
    'description',
    'note'
  ];

  public function resto() {
    return $this->hasOne("App\\resto");
  }

  public function user() {
    return $this->hasOne("App\User");
  }
}
