<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rows extends Model
{
  use HasFactory;

  protected $table = 'coach_rows';

  public function seats()
  {
    return $this->hasMany('App\Models\Seats', 'row_id');
  }
}
