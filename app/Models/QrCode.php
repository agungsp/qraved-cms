<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the restaurant that owns the QrCode
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id', 'qr_id');
    }

    public function scopeAvailable($query)
    {
        return $query->doesntHave('restaurant');
    }
}
