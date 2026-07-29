<?php

namespace App\Exports;

use App\Models\SiteVisitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiteVisitorRotateExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $months;
    protected $olderThan;

    public function __construct($months = 3, $olderThan = false)
    {
        $this->months = $months;
        $this->olderThan = $olderThan;
    }

    public function collection()
    {
        $query = SiteVisitor::query();
        
        if ($this->months != 0 && $this->months !== null) {
            $cutoffDate = now()->subMonths($this->months)->toDateString();
            if ($this->olderThan) {
                $query->where('visit_date', '<', $cutoffDate);
            } else {
                $query->where('visit_date', '>=', $cutoffDate);
            }
        }

        return $query->orderBy('visit_date', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'IP Address',
            'Visit Date',
            'Hits',
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

    public function map($visitor): array
    {
        return [
            $visitor->id,
            $visitor->ip_address,
            $visitor->visit_date,
            $visitor->hits,
            $visitor->created_at ? $visitor->created_at->format('Y-m-d H:i:s') : '',
            $visitor->updated_at ? $visitor->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
