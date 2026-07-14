<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    protected $fillable = [

        'fitness_assessment_id',

        'chronic_illness',
        'chronic_illness_details',

        'major_surgery',
        'major_surgery_details',

        'current_medications',
        'medication_name',

    ];
}