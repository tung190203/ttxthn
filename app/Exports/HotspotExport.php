<?php

namespace App\Exports;

use App\Models\Hotspot;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HotspotExport extends DefaultValueBinder implements FromArray, WithCustomValueBinder, WithStyles, WithColumnFormatting
{
    private const HEADER_ROW     = 5;
    private const DATA_START_ROW = 6;

    // Màu giao diện của file
    private const COLOR_TITLE  = '1F4E78';
    private const COLOR_HEADER = '1F4E78';
    private const COLOR_ZEBRA  = 'F2F7FB';
    private const COLOR_BORDER = 'BFBFBF';

    // key => tiêu đề cột (thứ tự này cũng là thứ tự cột trong file)
    public const FIELDS = [
        'project'             => 'Dự án',
        'position'            => 'Vị trí (mã lô)',
        'status'              => 'Trạng thái lô đất',
        'acreage'             => 'Diện tích',
        'unit'                => 'Đơn vị',
        'product_type'        => 'Loại sản phẩm',
        'intended_use'        => 'Mục đích sử dụng',
        'price'               => 'Giá gốc (VND)',
        'lease_price'         => 'Giá thuê (VND)',
        'sale_price'          => 'Giá bán (VND)',
        'transaction_note'    => 'Ghi chú giao dịch',
        'available_amenities' => 'Tiện ích sẵn có',
        'contact_info'        => 'Thông tin liên hệ',
        'visible'             => 'Hiển thị',
        'pending'             => 'Có bản nháp chờ duyệt không',
        'updated_at'          => 'Cập nhật lần cuối',
    ];

    // Trường được chọn sẵn khi mở popup
    public const DEFAULT_FIELDS = ['project', 'position', 'status', 'acreage', 'unit', 'product_type'];

    private const MONEY_FIELDS = ['price', 'lease_price', 'sale_price'];

    // Cột văn bản dài: căn trái cho dễ đọc, các cột còn lại căn giữa
    private const LEFT_FIELDS = ['intended_use', 'transaction_note', 'available_amenities', 'contact_info'];

    // Độ rộng từng cột (mặc định 18)
    private const WIDTHS = [
        'project'             => 32,
        'position'            => 16,
        'status'              => 22,
        'acreage'             => 14,
        'unit'                => 10,
        'product_type'        => 22,
        'intended_use'        => 36,
        'price'               => 18,
        'lease_price'         => 18,
        'sale_price'          => 18,
        'transaction_note'    => 36,
        'available_amenities' => 36,
        'contact_info'        => 30,
        'visible'             => 14,
        'pending'             => 22,
        'updated_at'          => 20,
    ];

    private array $fields;

    public function __construct(
        private Collection $hotspots,
        array $fields,
        private string $projectName,
        private Collection $productTypes,
        private string $exportedBy,
        private string $filterText
    ) {
        // Giữ đúng thứ tự khai báo trong FIELDS, bỏ key lạ
        $this->fields  = array_values(array_intersect(array_keys(self::FIELDS), $fields));
        $this->hotspots = $hotspots->values();
    }

    public function array(): array
    {
        $headers = array_merge(['STT'], array_map(fn ($key) => self::FIELDS[$key], $this->fields));

        $rows = [
            ['BÁO CÁO LÔ ĐẤT'],
            ['Xuất lúc ' . now()->format('d/m/Y H:i:s') . ' bởi ' . $this->exportedBy],
            ['Bộ lọc: ' . $this->filterText . ' | Tổng: ' . $this->hotspots->count() . ' lô'],
            [''],            // dòng đệm (không dùng [] vì thư viện sẽ bỏ qua dòng rỗng)
            $headers,
        ];

        foreach ($this->hotspots as $index => $hotspot) {
            $row = [$index + 1];
            foreach ($this->fields as $key) {
                $row[] = $this->value($hotspot, $key);
            }
            $rows[] = $row;
        }

        return $rows;
    }

    private function value(Hotspot $h, string $key)
    {
        return match ($key) {
            'project'  => $this->projectName,
            'position' => str_starts_with($h->potision, 'cmss_') ? substr($h->potision, 5) : $h->potision,
            'status'   => optional($h->investmentProductStatus)->name ?? 'Chưa có trạng thái',
            'acreage'  => is_numeric($h->acreage) ? (float) $h->acreage : (string) $h->acreage,
            'unit'     => $h->unit == 1 ? 'km' : 'ha',
            'product_type' => $this->productTypes[$h->product_type] ?? '',
            'intended_use' => (string) $h->intended_use,
            'price'       => is_null($h->price) ? '' : (int) $h->price,
            'lease_price' => is_null($h->lease_price) ? '' : (int) $h->lease_price,
            'sale_price'  => is_null($h->sale_price) ? '' : (int) $h->sale_price,
            'transaction_note'    => (string) $h->transaction_note,
            'available_amenities' => (string) $h->available_amenities,
            'contact_info'        => (string) $h->contact_info,
            'visible'  => $h->opacity ? 'Hiển thị' : 'Ẩn',
            'pending'  => ($h->draft && $h->draft->status !== 'rejected') ? 'Có' : 'Không',
            'updated_at' => optional($h->updated_at)->format('d/m/Y H:i'),
            default    => '',
        };
    }

    // Ô văn bản bắt đầu bằng "=" (ví dụ tooltip "= - Ký hiệu: ...") phải là chuỗi, không phải công thức
    public function bindValue(Cell $cell, $value): bool
    {
        if (is_string($value) && str_starts_with($value, '=')) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        $formats = [];
        foreach ($this->fields as $index => $key) {
            $col = Coordinate::stringFromColumnIndex($index + 2); // +1 vì cột A là STT
            if (in_array($key, self::MONEY_FIELDS, true)) {
                $formats[$col] = '#,##0';
            } elseif ($key === 'acreage') {
                $formats[$col] = '0.0000';
            }
        }
        return $formats;
    }

    public function styles(Worksheet $sheet)
    {
        $colCount = count($this->fields) + 1;
        $lastCol  = Coordinate::stringFromColumnIndex($colCount);
        $lastRow  = $sheet->getHighestRow();

        // ---- Độ rộng cột ----
        $sheet->getColumnDimension('A')->setWidth(7);
        foreach ($this->fields as $index => $key) {
            $col = Coordinate::stringFromColumnIndex($index + 2);
            $sheet->getColumnDimension($col)->setWidth(self::WIDTHS[$key] ?? 18);
        }

        // ---- Phần đầu báo cáo (3 dòng): gộp ô, căn giữa ----
        foreach ([1, 2, 3] as $r) {
            $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
            $sheet->getStyle("A{$r}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18)->getColor()->setRGB(self::COLOR_TITLE);
        $sheet->getRowDimension(1)->setRowHeight(34);
        $sheet->getStyle('A2:A3')->getFont()->setItalic(true)->setSize(11)->getColor()->setRGB('595959');

        // ---- Dòng tiêu đề cột ----
        $headerRange = 'A' . self::HEADER_ROW . ':' . $lastCol . self::HEADER_ROW;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::COLOR_HEADER]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::COLOR_BORDER]]],
        ]);
        $sheet->getRowDimension(self::HEADER_ROW)->setRowHeight(34);

        // ---- Vùng dữ liệu ----
        if ($lastRow >= self::DATA_START_ROW) {
            $dataRange = 'A' . self::DATA_START_ROW . ':' . $lastCol . $lastRow;
            $sheet->getStyle($dataRange)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::COLOR_BORDER]]],
            ]);

            // Dòng xen kẽ màu
            for ($r = self::DATA_START_ROW; $r <= $lastRow; $r++) {
                if (($r - self::DATA_START_ROW) % 2 === 1) {
                    $sheet->getStyle("A{$r}:{$lastCol}{$r}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::COLOR_ZEBRA);
                }
            }

            // Cột văn bản dài: căn trái
            foreach ($this->fields as $index => $key) {
                if (in_array($key, self::LEFT_FIELDS, true)) {
                    $col = Coordinate::stringFromColumnIndex($index + 2);
                    $sheet->getStyle("{$col}" . self::DATA_START_ROW . ":{$col}{$lastRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setIndent(1);
                }
            }

            // Cột trạng thái: tô đúng màu của trạng thái (giống màu hotspot trên VRTour)
            $statusIndex = array_search('status', $this->fields, true);
            if ($statusIndex !== false) {
                $col = Coordinate::stringFromColumnIndex($statusIndex + 2);
                foreach ($this->hotspots as $i => $h) {
                    $hex = optional($h->investmentProductStatus)->color_code;
                    if ($hex && preg_match('/^#[0-9A-Fa-f]{6}$/', $hex)) {
                        $cell = $col . (self::DATA_START_ROW + $i);
                        $sheet->getStyle($cell)->getFill()
                            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(strtoupper(ltrim($hex, '#')));
                        $sheet->getStyle($cell)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                    }
                }
            }
        }

        // ---- Tiện ích khi dùng ----
        $sheet->freezePane('A' . self::DATA_START_ROW);
        $sheet->setAutoFilter($headerRange);
        $sheet->setShowGridlines(false);

        // ---- Khi in: khổ ngang, vừa chiều rộng, lặp lại dòng tiêu đề ----
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0)
            ->setRowsToRepeatAtTopByStartAndEnd(self::HEADER_ROW, self::HEADER_ROW);

        return [];
    }
}