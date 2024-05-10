<?php

namespace App\Admin;

use App\DeptHead\Attachments;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\OnGoingInnovation;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DepartmentGroup extends Model
{
    protected $table = 'department_kpi_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    public function departmentalGoals() {
        return $this->hasMany(DepartmentalGoals::class);
    }

    public function departmentKpi() {
        return $this->hasMany(DepartmentKPI::class, 'department_group_id');
    }

    public function processDevelopment() {
        return $this->hasMany(ProcessDevelopment::class);
    }

    public function innovation() {
        return $this->hasMany(Innovation::class);
    }
}
