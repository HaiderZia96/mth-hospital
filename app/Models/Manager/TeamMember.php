<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeamMember extends Model
{
    use HasFactory;

    use HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('TeamMember')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }
    protected $table = 'team_members';
    protected $fillable = [
        'name','address','phone_no','image_url','image_name','image_url_name','designation', 'description', 'education','employment', 'membership','status','department_id','sitting_time','speciality','slug','created_by,updated_by'

    ];
    public function DptID()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
