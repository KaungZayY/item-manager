<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'created_date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['category_id'])) {
            $query->whereHas('category', function ($filterQry) use ($filters) {
                $filterQry->where('id', $filters['category_id']);
            });
        }

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['from_date']) && isset($filters['to_date'])) {
            $query->whereBetween('created_date', [$filters['from_date'], $filters['to_date']]);
        }
    
    }
}
