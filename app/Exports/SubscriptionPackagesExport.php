<?php

namespace App\Exports;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SubscriptionPackagesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, ShouldAutoSize
{
    private $no = 0;

    public function collection(): Collection
    {
        return SubscriptionPackage::withCount([
            'books',
            'subscriptionPackageUsers as active_users_count' => function ($query) {
                $query->where('expired_date', '>=', now()->toDateString());
            }
        ])->get();
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'Nama Paket Langganan',
            'Harga',
            'Jumlah Buku',
            'Pelanggan Aktif',
        ];
    }

    public function map($item): array
    {
        return [
            ++$this->no,
            $item->name,
            'Rp ' . number_format($item->price ?? 0, 0, ',', '.'),
            $item->books_count,
            $item->active_users_count > 0 ? $item->active_users_count : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Judul pada Baris 1
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Data Paket Langganan dengan Buku dan Pengguna');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(32);

        $totalRows = $this->collection()->count() + 2;

        return [
            // Teks judul ukuran font seperti tag h2
            'A1' => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],

            // Header tebal dengan warna abu-abu (Baris 2)
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],

            // Tabel diberi border dari A2 sampai E{totalRows}
            "A2:E{$totalRows}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}

