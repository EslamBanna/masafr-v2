<?php

namespace App\Models\Masafr;

use App\Models\Common\RequestTrip;
use App\Models\Masafr\Fatoorah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FatoorahList extends Model
{
  use HasFactory;

  protected $table = 'fatoorah_lists';
  protected $fillable = [
    'request_trip_id',
    'accepted',
    // 'insurance_fee',
    // 'website_service'
  ];


  public function fatoorah()
  {
    return $this->hasMany(Fatoorah::class, 'fatoorah_list_id', 'id');
  }

  public function requestTrip()
  {
    return $this->belongsTo(RequestTrip::class, 'request_trip_id', 'id');
  }
  public function getCreatedAtAttribute($value)
  {
      return $value ?? '';
  }
  public function getUpdatedAtAttribute($value)
  {
      return $value ?? '';
  }
}
