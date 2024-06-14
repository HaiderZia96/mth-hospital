<?php

namespace App\Models\Manager;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NewsEvent extends Model
{
    use HasFactory, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('NewsEvent')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }
    protected $table = 'news_events';
    protected $fillable = [
        'name','slug','dpt_id','e_cate','e_date','short_description','long_description','thumbnail_url','thumbnail_name','thumbnail_url_name','banner_url','banner_name','banner_url_name','tag','priority','created_by','updated_by'
    ];
    public function DptID()
    {
        return $this->belongsTo(Department::class, 'dpt_id');
    }
    public function eCateID()
    {
        return $this->belongsTo(EventCategory::class, 'e_cate');
    }
}
