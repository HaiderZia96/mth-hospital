<?php

namespace App\Models\Department;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DepartmentBanner extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('DepartmentBanner')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }
    protected $table = 'department_banners';
    protected $fillable = [
        'image',
        'title',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];
    public function departments()
    {
        return $this->hasMany(Department::class, 'banner_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
