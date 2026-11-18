<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuestsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filter;

    public function __construct($filter = [])
    {
        $this->filter = $filter;
    }

    public function query()
    {
        $query = Guest::query()->with('nation');

        if (!empty($this->filter['name'])) {
            $query->where('name', 'like', '%' . $this->filter['name'] . '%');
        }
        if (!empty($this->filter['email'])) {
            $query->where('email', 'like', '%' . $this->filter['email'] . '%');
        }
        if (!empty($this->filter['status'])) {
            $query->where('status', $this->filter['status']);
        }
        if (!empty($this->filter['investor_type'])) {
            $query->where('investor_type', $this->filter['investor_type']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Họ và tên',
            'Email',
            'Số điện thoại',
            'Loại NĐT',
            'Tổ chức',
            'Mã số thuế',
            'Quốc gia',
            'Trạng thái',
            'Ngày đăng ký',
            'Lý do khóa (nếu có)'
        ];
    }

    public function map($guest): array
    {
        $typeLabels = [
            'individual' => 'Cá nhân',
            'organization' => 'Tổ chức',
            'fdi' => 'FDI'
        ];

        $statusLabels = [
            'pending' => 'Chờ duyệt',
            'active' => 'Hoạt động',
            'locked' => 'Đã khóa',
            'rejected' => 'Từ chối'
        ];

        return [
            $guest->id,
            $guest->name,
            $guest->email,
            $guest->phone,
            $typeLabels[$guest->investor_type] ?? $guest->investor_type,
            $guest->organization_name,
            $guest->tax_code,
            $guest->nation->name ?? '',
            $statusLabels[$guest->status] ?? $guest->status,
            $guest->created_at ? $guest->created_at->format('d/m/Y H:i') : '',
            $guest->locked_reason ?? ''
        ];
    }
}
