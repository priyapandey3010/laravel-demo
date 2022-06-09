<?php 

namespace App\Traits;

use Illuminate\Support\Str;

trait Mutators 
{
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }
} 