<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trains extends Model
{
  use HasFactory;

  protected $table = "trains";

  public function coaches()
  {
    return $this->hasMany('App\Models\Coaches', 'train_id');
  }
}
