<?php

namespace App\Models\Manager;

use App\Models\Manager\Achievement;
use App\Models\Manager\Conference;
use App\Models\Manager\NewsEvent;
use App\Models\Manager\Research;
use App\Models\Manager\TeamMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //Customizing the log name
            ->useLogName('Department')
            //Log changes to all the $fillable
            ->logFillable()
            //Customizing the description
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
            //Logging only the changed attributes
            ->logOnlyDirty()
            //Prevent save logs items that have no changed attribute
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'departments';
    protected $fillable = [
        'title', 'thumbnail_url', 'thumbnail_name', 'thumbnail_url_name', 'icon_url', 'icon_name', 'icon_url_name', 'description', 'slug', 'hod_message', 'hod_name', 'hod_designation', 'hod_image_url', 'hod_image_name', 'hod_image_url_name',
        'mission', 'vision', 'objective', 'about_us', 'department_banner_name', 'department_banner_url', 'department_banner_url_name', 'priority', 'created_by', 'updated_by',
        //New Fields added as per meeting
        'cover_image_url', 'cover_image_name', 'cover_image_url_name','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function newsEvent()
    {
        return $this->hasMany(NewsEvent::class, 'dpt_id');
    }

    public function teamMember()
    {
        return $this->hasMany(TeamMember::class, 'department_id');
    }

    public function achievement()
    {
        return $this->hasMany(Achievement::class, 'department_id');
    }

    public function research()
    {
        return $this->hasMany(Research::class, 'department_id');
    }

    public function conference()
    {
        return $this->hasMany(Conference::class, 'department_id');
    }
}
