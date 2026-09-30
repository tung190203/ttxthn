<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;
use App\Supports\SearchQueryParser;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class IndustrialProject extends Model
{

    use HasFactory, HasTranslations, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    protected $table = 'industrial_projects';
    const INDUSTRIAL_PROJECT_PER_PAGE = 9;

    protected $fillable = [
        'project_id',
        'code',
        'name',
        'link',
        'acreage',
        'description',
        'product_type',
        'intended_use',
        'unit',
        'price',
    ];

    public $translatable = [
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function hotspots()
    {
        return $this->hasOne(Hotspot::class, 'potision', 'code')->whereColumn(
            'hotspot.vrtour_id',
            'industrial_projects.project_id'
        );
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type', 'id');
    }

    public function scopeSmartSearch($query, string $search)
    {
        $search = trim($search);
        // Ưu tiên tìm theo TÊN: nếu có dự án trùng tên thì bỏ qua số, không coi là diện tích
        if (SearchQueryParser::isNameSearch($search)) {
            $like = '%' . mb_strtolower($search) . '%';
            $projectIds = Project::whereRaw('LOWER(name) LIKE ?', [$like])->pluck('id');
            return $query->where(function ($q) use ($like, $projectIds) {
                $q->whereRaw('LOWER(name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(code) LIKE ?', [$like])
                    ->orWhereIn('project_id', $projectIds); // lấy các lô thuộc dự án trùng tên
            });
        }

        $parsed = SearchQueryParser::parse($search);
        // Mã lô: AND với các điều kiện khác
        foreach ($parsed['codes'] as $code) {
            $like = "%{$code}%";
            $query->where(function ($q) use ($like) {
                $q->whereRaw('LOWER(code) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(name) LIKE ?', [$like]);
            });
        }

        // Từ khóa: OR giữa các từ đồng nghĩa và các cột
        if ($parsed['keywords']) {
            $query->where(function ($q) use ($parsed) {
                foreach ($parsed['keywords'] as $kw) {
                    $like = "%{$kw}%";
                    $q->orWhereRaw('LOWER(name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(code) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(intended_use) LIKE ?', [$like]);
                }
            });
        }

        // Diện tích: quy đổi acreage về ha (unit 0 = ha, 1 = km²), dung sai ±10%
        if ($parsed['areas_ha']) {
            $query->where(function ($q) use ($parsed) {
                foreach ($parsed['areas_ha'] as $ha) {
                    $q->orWhereRaw(
                        'acreage * (CASE WHEN unit = 1 THEN 100 ELSE 1 END) BETWEEN ? AND ?',
                        [$ha * 0.9, $ha * 1.1]
                    );
                }
            });
        }
        return $query;
    }

    public static function filterProjectIds(Request $request)
    {
        return self::query()
            ->select('project_id')
            ->when($request->filled('search'), fn($q) => $q->smartSearch($request->search))
            ->when(
                $request->filled('project_id') && $request->project_id !== 'all',
                fn($q) => $q->where('project_id', $request->project_id)
            )
            ->when(
                $request->filled('product_type') && $request->product_type !== 'all',
                fn($q) => $q->where('product_type', $request->product_type)
            )
            ->when(
                $request->has('price') && (int) $request->price > 0,
                fn($q) => $q->where(fn($s) => $s->whereNull('price')->orWhere('price', '<=', (int) $request->price))
            )
            ->pluck('project_id');
    }
    public function scopeFilterByRequest($query, Request $request)
    {
        return $query
            ->when($request->filled('search'), fn($q) => $q->smartSearch($request->search))
            ->when(
                $request->filled('project_id') && $request->project_id !== 'all',
                fn($q) => $q->where('project_id', $request->project_id)
            )
            ->when(
                $request->filled('product_type') && $request->product_type !== 'all',
                fn($q) => $q->where('product_type', $request->product_type)
            )
            ->when(
                $request->has('price') && (int) $request->price > 0,
                fn($q) => $q->where(fn($s) => $s->whereNull('price')->orWhere('price', '<=', (int) $request->price))
            );
    }
}
