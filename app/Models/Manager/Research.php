<?php

namespace App\Models\Manager;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Research extends Model
{
    use HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('Research')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }
    protected $table = 'researches';
    protected $fillable = [
        'title','department_id','year','author','journal','publish','impact_factor','priority','detail','created_by','updated_by'
    ];
    public function DptID()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function attachID()
    {
        return $this->hasMany(Attachment::class, 'research_id');
    }
}
