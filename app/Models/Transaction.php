<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Transaction extends Model
{
   protected $fillable = ['user_id', 'total'];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
