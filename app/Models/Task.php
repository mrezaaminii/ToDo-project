<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'title',
        'description',
        'color',
        'due_at',
        'is_done',
        'user_id',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'is_done' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->timezone("Asia/Tehran")->format("Y-m-d H:i:s");
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)->timezone("Asia/Tehran")->format("Y-m-d H:i:s");
    }

    public function getDueAtAttribute($value) {
        return Carbon::parse($value)->timezone("Asia/Tehran")->format("Y-m-d H:i:s");
    }
}
