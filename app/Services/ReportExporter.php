<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ReportExporter
{
    public function excel(array $report): string
    {
        $book = new Spreadsheet();
        $book->getProperties()->setCreator('Kerangka SIPROSIR')->setTitle($report['title']);
        $book->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
        $sheet = $book->getActiveSheet()->setTitle('Laporan');
        $lastColumn = Coordinate::stringFromColumnIndex(count($report['columns']));
        $sheet->mergeCells("A1:{$lastColumn}1")->setCellValue('A1', $report['title']);
        $sheet->mergeCells("A2:{$lastColumn}2")->setCellValue('A2', $report['note']);
        $sheet->mergeCells("A3:{$lastColumn}3")->setCellValueExplicit('A3', $this->filterLabel($report), DataType::TYPE_STRING);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $index = 1;
        foreach ($report['columns'] as $key => [$label, $type]) {
            $column = Coordinate::stringFromColumnIndex($index++);
            $sheet->setCellValueExplicit($column . '5', $label, DataType::TYPE_STRING);
            $sheet->getColumnDimension($column)->setWidth($type === 'currency' ? 20 : 26);
            foreach ($report['rows'] as $offset => $row) {
                $sheet->setCellValueExplicit($column . ($offset + 6), $row[$key], in_array($type, ['number', 'currency'], true) ? DataType::TYPE_NUMERIC : DataType::TYPE_STRING);
            }
            $lastRow = max(6, count($report['rows']) + 5);
            if ($type === 'currency') {
                $sheet->getStyle("{$column}6:{$column}{$lastRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0;[Red]-"Rp" #,##0');
            }
        }
        $sheet->getStyle("A5:{$lastColumn}5")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF176B87');
        $sheet->getStyle("A5:{$lastColumn}5")->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getRowDimension(5)->setRowHeight(24);
        $sheet->freezePane('A6')->setAutoFilter("A5:{$lastColumn}" . max(5, count($report['rows']) + 5));
        if ($report['rows'] === []) {
            $sheet->setCellValue('A6', 'Tidak ada data yang sesuai filter.');
        }
        $writer = new Xlsx($book);
        $writer->setPreCalculateFormulas(false);
        ob_start();
        try {
            $writer->save('php://output');
            return (string) ob_get_contents();
        } finally {
            ob_end_clean();
            $book->disconnectWorksheets();
        }
    }

    public function pdf(array $report): string
    {
        $options = new Options();
        $options->setIsRemoteEnabled(false);
        $options->setIsPhpEnabled(false);
        $options->setDefaultFont('DejaVu Sans');
        $pdf = new Dompdf($options);
        $pdf->loadHtml(view('exports/table', ['report' => $report, 'filterLabel' => $this->filterLabel($report)]), 'UTF-8');
        $pdf->setPaper('A4', count($report['columns']) > 5 ? 'landscape' : 'portrait');
        $pdf->render();
        return $pdf->output();
    }

    private function filterLabel(array $report): string
    {
        if ($report['filters'] === []) {
            return 'Filter: semua data contoh';
        }
        $filters = [];
        foreach ($report['filters'] as $label => $value) {
            $filters[] = $label . ': ' . $value;
        }
        return implode(' | ', $filters);
    }
}
