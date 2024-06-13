<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MdrStatus extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mdr_status';

    // protected $fillable = ['department_id', 'department_head_id', 'deadline', 'submission_date', 'status', 'approved_date', 'rate', 'remarks'];
    protected $fillable = ['status', 'start_date'];

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
