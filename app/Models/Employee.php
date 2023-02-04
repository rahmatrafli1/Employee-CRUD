<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = "employees";
    protected $fillable = ["name", "job_name", "manager_name", "hire_date", "salary", "commission"];
}
