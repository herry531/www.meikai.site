<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    //
    public function scopeGender($query, $gender)
    {
        if (!in_array($gender, ['m', 'f'])) {
            return $query;
        }

        return $query->whereHas('profile', function ($query) use ($gender) {
            $query->where('gender',  $gender);
        });


    }



}
