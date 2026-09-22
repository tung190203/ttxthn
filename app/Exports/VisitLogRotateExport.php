<?php

namespace App\Exports;

use App\Models\VisitLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitLogRotateExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $months;
    protected $olderThan;
    protected $startDate;
    protected $endDate;

    public function __construct($months = 1, $olderThan = false, $startDate = null, $endDate = null)
    {
        $this->months = $months;
        $this->olderThan = $olderThan;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = VisitLog::query();

        if ($this->startDate && $this->endDate) {
            // Filter by specific date range (e.g. a specific month)
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        } elseif ($this->months != 0 && $this->months !== null) {
            $cutoffDate = now()->subMonths($this->months);
            if ($this->olderThan) {
                $query->where('created_at', '<', $cutoffDate);
            } else {
                $query->where('created_at', '>=', $cutoffDate);
            }
        }

        return $query->orderBy('created_at', 'asc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'IP Address',
            'User Agent',
            'Path',
            'Is Bot',
            'Visitor ID',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->ip_address,
            $log->user_agent,
            $log->path,
            $log->is_bot ? 'Yes' : 'No',
            $log->visitor_id,
            $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '',
            $log->updated_at ? $log->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
