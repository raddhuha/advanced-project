<?php

namespace App\Models;

use App\Models\DetailTutorial;
use Illuminate\Database\Eloquent\Model;

class MasterTutorial extends Model
{
    protected $fillable = [
        'title',
        'course_code',
        'url_presentation',
        'url_finished',
        'creator_email',
    ];

    public function detailTutorials()
    {
        return $this->hasMany(DetailTutorial::class, 'master_tutorial_id');
    }
}
