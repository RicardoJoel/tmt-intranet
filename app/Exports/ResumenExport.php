<?php

namespace App\Exports;

use Carbon\Carbon;
/* From array */
use Maatwebsite\Excel\Concerns\FromArray;
/* Heading */
use Maatwebsite\Excel\Concerns\WithHeadings;
/* Value binders */
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
/* Auto size column */
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
/* Styling */
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
/* Drawing */
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
/* Background */
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ResumenExport extends DefaultValueBinder implements FromArray, WithHeadings, WithCustomValueBinder, ShouldAutoSize, WithStyles, WithColumnWidths, WithDrawings
{
    protected $invoices, $area, $user, $week, $time;

    public function __construct(array $invoices, $area, $user, $week, $time)
    {
        $this->invoices = $invoices;
        $this->area = $area;
        $this->user = $user;
        $this->week = $week;
        $this->time = $time;
    }

    public function array(): array
    {
        return $this->invoices;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 7,
            'C' => 7,
            'D' => 7,
            'E' => 28,
            'F' => 28,  
            'G' => 28, 
            'H' => 7
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setPath(public_path('/images/logo-tmt.png'));
        $drawing->setWidth(250);
        $drawing->setCoordinates('A1');
        return $drawing;
    }

    public function headings(): array
    {
        return [
            ['REGISTRO DE ACTIVIDADES'],
            [],[],[],
            ['UNIDAD ORGÁNICA', '', '', '', $this->area],
            ['APELLIDOS Y NOMBRES', '', '', '', $this->user],
            ['SEMANA', '', '', '', $this->week],
            ['TOTAL HORAS', '', '', '', $this->time],
            [],
            ['FECHA','INICIO','FINAL','TIEMPO','PROYECTO','ACTIVIDAD','TAREA','TERMINÓ'],
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
            return true;
        }
        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(false);
        $sheet->mergeCells('A1:H3');
        $sheet->mergeCells('A5:D5');
        $sheet->mergeCells('A6:D6');
        $sheet->mergeCells('A7:D7');
        $sheet->mergeCells('A8:D8');
        $sheet->mergeCells('E5:F5');
        $sheet->mergeCells('E6:F6');
        $sheet->mergeCells('E7:F7');
        $sheet->mergeCells('E8:F8');
        return [
            'A:H' => [
                'font' => ['size' => 9], 
                'alignment' => ['vertical' => 'center','wrapText' => true]
            ],
            'A5:F8' => [
                'font' => ['bold' => true], 
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'E5:E8' => [
                'font' => ['bold' => true, 'color' => ['argb' => 'A5C862']]
            ],
            'A10:H100' => [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'A:D' => [
                'alignment' => ['horizontal' => 'center']
            ],
            'H' => [
                'alignment' => ['horizontal' => 'center','wrapText' => false]
            ],
            '10' => [
                'font' => ['bold' => true], 
                'fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'A5C862']], 
                'alignment' => ['horizontal' => 'center']
            ],
            '1' => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'A5C862']], 
            ],
        ];
    }
}