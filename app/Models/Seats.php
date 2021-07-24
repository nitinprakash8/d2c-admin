<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seats extends Model
{
  use HasFactory;

  protected $table = 'row_seats';

  protected $fillable = [
    'row_id',
    'number',
    'booked',
  ];

  public function row()
  {
    return $this->belongsTo('App\Models\Rows');
  }
}
