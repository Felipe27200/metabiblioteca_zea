<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investigator extends Model
{
    use HasFactory;

    protected $fillable = ['orcid', 'name', 'last_name', 'principal_email'];

    protected $primaryKey = 'orcid';

    /**
     * non-Incrementing, non-integer primary key.
     */
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class, 'investigator_id');
    }
}
