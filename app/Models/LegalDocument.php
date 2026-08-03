<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LegalDocument extends Model
{
    use LogsActivity;
    protected $table = "legal_document";

    protected $fillable = [
        'vrtour_id',
        'name',
        'name_en',
        'download',
        'user_id',
    ];
    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')->where('type', SkinApproval::TYPE_DOCUMENT)->where('status', 'pending');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
