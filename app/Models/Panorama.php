<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panorama extends Model
{
    protected $table = "panorama";
    protected $fillable = [
        'vrtour_id',
        'label_audio',
        'ids',
        'title',
        'title_en',
        'content',
        'content_en',
        'audio',
        'audio_en',
        'user_id'
    ];
    public function draft()
    {
        return $this->hasOne(Panorama::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Panorama::class, 'parent_id');
    }
    public function scopeVisibleFor($query, $user)
    {
        return $query->where(function ($q) use ($user) {

            if ($user->is_super_admin || $user->is_approve) {
                $q->where(function ($sub) {
                    $sub->where('is_draft', false)
                        ->where(function ($s) {
                            $s->whereDoesntHave('draft')
                                ->orWhereHas('draft', function ($d) {
                                    $d->where('status', 'rejected');
                                });
                        });
                })
                    ->orWhere(function ($sub) {
                        $sub->where('is_draft', true)
                            ->where('status', '!=', 'rejected');
                    });
            } else {
                $q->where(function ($sub) {
                    $sub->where('is_draft', false)
                        ->whereDoesntHave('draft');
                })
                    ->orWhere(function ($sub) {
                        $sub->where('is_draft', true);
                    });
            }
        });
    }
}
