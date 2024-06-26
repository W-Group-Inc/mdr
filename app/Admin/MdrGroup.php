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
use OwenIt\Auditing\Contracts\Auditable;

class MdrGroup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function departmentalGoals() {
        return $this->hasMany(DepartmentalGoals::class);
    }

    public function mdrSetup() {
        return $this->hasMany(MdrSetup::class);
    }

    public function processDevelopment() {
        return $this->hasMany(ProcessDevelopment::class);
    }

    public function innovation() {
        return $this->hasMany(Innovation::class);
    }
}
