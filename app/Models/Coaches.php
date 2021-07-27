<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coaches extends Model
{
  use HasFactory;

  protected $table = "coaches";

  public function train()
  {
    return $this->belongsTo('App\Models\Trains');
  }

  public function rows()
  {
    return $this->hasMany('App\Models\Rows', 'coach_id');
  }
}
