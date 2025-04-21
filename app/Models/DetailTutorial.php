<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTutorial extends Model
{
    protected $fillable = [
        'master_tutorial_id',
        'order',
        'type',
        'content',
        'status',
    ];

    public function masterTutorial()
    {
        return $this->belongsTo(MasterTutorial::class);
    }
}
