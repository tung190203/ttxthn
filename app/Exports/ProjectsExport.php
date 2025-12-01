<?php

namespace App\Exports;

use App\Models\Interest;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font; // Import thêm Font

class ProjectsExport implements FromArray, WithStyles, ShouldAutoSize
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Sử dụng local variable để tính tổng
        $projects = Project::withRelations()->get();

        $data = [];

        // Khởi tạo biến tổng cộng
        $sum_view_num = 0;
        $sum_interests = 0;
        
        // Cần tổng hợp dữ liệu trước khi thêm vào mảng data
        $project_data = [];
        $stt = 1;
        foreach ($projects as $project) {
            // Xử lý districts
            $districts = $project->districts
                ->map(function ($district) {
                    // Cần kiểm tra method_exists cho tính linh hoạt
                    if (method_exists($district, 'getTranslation')) {
                        return $district->getTranslation('name', 'vi') ?? '';
                    } 
                    return $district->name['vi'] ?? '';
                })
                ->filter() 
                ->implode(', ');

            $unit = $project->unit == 1 ? 'km' : 'ha';
            
            // Tính tổng
            $sum_view_num += $project->view_num ?? 0;
            $sum_interests += $project->interests()->count() ?? 0;

            $project_data[] = [
                $stt++,
                $project->name ?? '',
                $districts ?? '', 
                $project->area . ' ' . $unit ?? '',
                $project->price ? $project->price . ' tỷ đồng' : '',
                $project->quy_hoach_tong_the ?? '0',
                $project->quy_hoach_chi_tiet ?? '0',
                $project->thuoc_danh_muc ?? '0',
                $project->so_hoa ?? '0',
                $project->chuan_thuan_chu_truong ?? '0',
                $project->lua_chon_nha_dau_tu ?? '0',
                $project->giai_phong_mat_bang ?? '0',
                $project->phuong_an_ky_thuat ?? '0',
                $project->hoan_thanh ?? '0',
                $project->view_num ?? 0,
                $project->interests()->count() ?? 0
            ];
        }

        // --- Bắt đầu gán dữ liệu cho các Row Excel ---

        // ===== ROW 1 (Excel Row 1): Header =====
        $data[] = [
            'STT',                  
            'THÔNG TIN CƠ BẢN',     
            '', '', '',             
            'KẾ HOẠCH TRIỂN KHAI',  
            '', '', '', '', '', '', '', '', 
            'THỐNG KÊ TRUY CẬP',    
            ''                      
        ];

        // ===== ROW 2 (Excel Row 2): Header con =====
        $data[] = [
            '', 
            'Tên dự án', 
            'Địa điểm (Phường, xã)', 
            'Quy mô (ha hoặc km)',   
            'Tổng mức đầu tư (tỷ đồng)', 
            'Chuẩn bị dự án',       
            '', '',
            'Xúc tiến đầu tư',      
            '', '',
            'Giám sát thực hiện',   
            '', '',
            'Truy cập dự án',       
            'Đăng ký nhận thông tin' 
        ];

        // ===== ROW 3 (Excel Row 3): Header chi tiết =====
        $data[] = [
            '', '', '', '', '', 
            'Quy hoạch tổng thể (1/5.000 trở lên)', 
            'Quy hoạch chi tiết (1/500 hoặc 1/2.000)', 
            'Thuộc danh mục thu hút đầu tư của Thành phố', 
            'Số hóa', 
            'Chấp thuận chủ trương đầu tư', 
            'Lựa chọn nhà đầu tư', 
            'Giải phóng mặt bằng', 
            'Phương án kỹ thuật', 
            'Hoàn thành', 
            '', 
            ''  
        ];

        // ===== ROW 4 (Excel Row 4): DÒNG TỔNG CỘNG (SUM ROW) =====
        $data[] = [
            '', // A4
            '', // B4
            '', // C4
            '', // D4
            '', // E4
            '', // F4
            '', // G4
            '', // H4
            '', // I4
            '', // J4
            '', // K4
            '', // L4
            '', // M4
            '', // N4
            $sum_view_num,
            $sum_interests
        ];

        // === Data Rows (BẮT ĐẦU TỪ DÒNG 5) ===
        $data = array_merge($data, $project_data);

        return $data;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn(); // P
        
        // Định nghĩa các vùng cần gộp (không đổi)
        // ... (1. MERGE CELLS giữ nguyên)
        $sheet->mergeCells('B1:E1'); $sheet->mergeCells('F1:N1'); $sheet->mergeCells('O1:P1');
        $sheet->mergeCells('A1:A3'); 
        $sheet->mergeCells('B2:B3'); $sheet->mergeCells('C2:C3'); $sheet->mergeCells('D2:D3'); $sheet->mergeCells('E2:E3'); 
        $sheet->mergeCells('F2:H2'); $sheet->mergeCells('I2:K2'); $sheet->mergeCells('L2:N2');   
        $sheet->mergeCells('O2:O3'); $sheet->mergeCells('P2:P3');

        // 2. ==== ROW HEIGHT (Chiều cao hàng) ====
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(60); 
        $sheet->getRowDimension(4)->setRowHeight(25); // Chiều cao cho dòng Tổng cộng

        // 3. ==== HEADER STYLE (Định dạng tiêu đề) - Rows 1-3 ====
        $sheet->getStyle('A1:' . $highestColumn . '3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => '000000']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, 
                'vertical' => Alignment::VERTICAL_CENTER,     
                'wrapText' => true                            
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'] 
            ]
        ]);
        
        // 3.1 ==== STYLE CHO DÒNG TỔNG CỘNG (ROW 4) ====
        $sheet->getStyle('A4:' . $highestColumn . '4')->applyFromArray([
            'font' => [
                'bold' => true, // Tiêu đề Tổng cộng phải in đậm
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Căn giữa cho O4, P4
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [ // Loại bỏ màu nền
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF'] // Nền trắng
            ]
        ]);
        
        // // 3.2 Căn trái chữ "Tổng cộng"
        // $sheet->getStyle('A4:N4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);


        // 4. ==== BORDER (Viền) ====
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN, 
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        
        // 5. ==== Center Align Data (Căn giữa dữ liệu) - BẮT ĐẦU TỪ DÒNG 5 ====
        
        // Căn giữa cột STT (A) và các cột trạng thái (F đến P)
        $sheet->getStyle('A5:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F5:P' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Căn giữa dọc cho tất cả dữ liệu
        $sheet->getStyle('A5:' . $highestColumn . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }
}