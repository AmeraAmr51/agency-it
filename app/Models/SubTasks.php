<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTasks extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_name',
        'status',
        'start_date',
        'details',
        'end_date',
        'project_id',
        'employee_id'

    ];
    public function employee(){
        return $this->belongsTo(User::class ,'employee_id');
        
    }
    public function project(){
        return $this->belongsTo(Projects::class ,'project_id');
        
    }

}
