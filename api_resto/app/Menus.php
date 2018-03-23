<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
  protected $fillable = [
    'id',
    'restos_id',
    'name',
    'description',
    'price'
  ];

  public function resto() {
    return $this->hasOne("App\\resto");
  }
}
