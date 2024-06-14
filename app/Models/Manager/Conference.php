<?php

namespace App\Models\Manager;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Conference extends Model
{
    use HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('Conference')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }
    protected $table = 'conferences';
    protected $fillable = [
        'name','slug','image_url','image_name','image_url_name','description'
        ,'department_id','conference_date','venue','conference_workshop','priority','created_by','updated_by'
    ];
    public function DptID()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
