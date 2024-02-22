<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    const COMPLETE_STATUS = 1;
    const INCOMPLETE_STATUS = 0;

    protected $fillable = [
        'user_id',
        'description',
        'title',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUserEmail(Builder $query, $email): Builder
    {
        return $query->whereHas('user', function ($query) use ($email) {
            $query->where('email', 'like', '%' . $email . '%');
        });
    }

    public function scopeDescription(Builder $query, $description): Builder
    {
        return $query->where('description', 'like', '%' . $description . '%');
    }

    public function scopeTitle(Builder $query, $title): Builder
    {
        return $query->where('title', 'like', '%' . $title . '%');
    }

    public function scopeStartDateFrom(Builder $query, $startDate): Builder
    {
        return $query->where('start_date', '>=', $startDate);
    }

    public function scopeStartDateTo(Builder $query, $startDate): Builder
    {
        return $query->where('start_date', '<=', $startDate);
    }

    public function scopeEndDateFrom(Builder $query, $endDate): Builder
    {
        return $query->where('end_date', '>=', $endDate);
    }

    public function scopeEndDateTo(Builder $query, $endDate): Builder
    {
        return $query->where('end_date', '<=', $endDate);
    }
}
