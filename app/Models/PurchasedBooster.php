<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedBooster extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "purchased_boosters";
    protected $fillable = [
        'user_id',
        'booster_id',
        'type',
        'trx',
        'amount',
        'is_expired',
    ];
    public function booster()
    {
        return $this->belongsTo(Booster::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
