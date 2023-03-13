<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = ['keyword'];

    public $incrementing = false;
    public $timestamps = false;


    public function investigator(): BelongsTo
    {
        return $this->belongsTo(Investigator::class, 'investigator_id', 'orcid');
    }
}
