<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';
    protected $fillable = ['user_id', 'work_started', 'work_ended', 'break_started', 'break_ended'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
