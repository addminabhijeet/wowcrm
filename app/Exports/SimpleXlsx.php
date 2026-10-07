<?php

namespace App\Exports;

use Phar;
use PharData;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Small .xlsx writer (title row, styled header row, data rows) that, like WeeklyCallReportExport,
 * uses PharData so it works without the optional PHP zip extension.
 */
class SimpleXlsx
{
    /** @param array<int,string> $headers  @param iterable<array<int,scalar|null>> $rows  @param array<int,int> $widths */
    public static function download(string $filename, string $title, array $headers, iterable $rows, array $widths = []): BinaryFileResponse
    {
        $file = self::build($title, $headers, $rows, $widths);

        return response()->download($file, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public static function build(string $title, array $headers, iterable $rows, array $widths = []): string
    {
        $temp = tempnam(sys_get_temp_dir(), 'xlsx_');
        if ($temp === false) {
            throw new RuntimeException('Could not create the export file.');
        }
        @unlink($temp);
        $file = $temp . '.xlsx';

        $cols = count($headers);
        $lastCol = self::col($cols);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="2" topLeftCell="A3" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . '<sheetFormatPr defaultRowHeight="15"/><cols>';
        for ($i = 1; $i <= $cols; $i++) {
            $xml .= '<col min="' . $i . '" max="' . $i . '" width="' . ($widths[$i - 1] ?? 22) . '" customWidth="1"/>';
        }
        $xml .= '</cols><sheetData>';

        $xml .= '<row r="1" ht="24" customHeight="1">' . self::cell('A1', $title, 2) . '</row>';

        $xml .= '<row r="2" ht="22" customHeight="1">';
        foreach (array_values($headers) as $i => $h) {
            $xml .= self::cell(self::col($i + 1) . '2', $h, 1);
        }
        $xml .= '</row>';

        $r = 3;
        foreach ($rows as $row) {
            $xml .= '<row r="' . $r . '">';
            foreach (array_values($row) as $i => $v) {
                $xml .= self::cell(self::col($i + 1) . $r, $v, 0);
            }
            $xml .= '</row>';
            $r++;
        }

        $xml .= '</sheetData><mergeCells count="1"><mergeCell ref="A1:' . $lastCol . '1"/></mergeCells></worksheet>';

        $ns = 'http://schemas.openxmlformats.org/';
        $archive = new PharData($file, 0, null, Phar::ZIP);
        $archive->addFromString('[Content_Types].xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="' . $ns . 'package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>');
        $archive->addFromString('_rels/.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="' . $ns . 'package/2006/relationships">'
            . '<Relationship Id="rId1" Type="' . $ns . 'officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>');
        $archive->addFromString('xl/workbook.xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="' . $ns . 'spreadsheetml/2006/main" xmlns:r="' . $ns . 'officeDocument/2006/relationships">'
            . '<sheets><sheet name="Report" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $archive->addFromString('xl/_rels/workbook.xml.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="' . $ns . 'package/2006/relationships">'
            . '<Relationship Id="rId1" Type="' . $ns . 'officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="' . $ns . 'officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>');
        $archive->addFromString('xl/styles.xml', self::styles());
        $archive->addFromString('xl/worksheets/sheet1.xml', $xml);

        return $file;
    }

    private static function cell(string $ref, mixed $value, int $style): string
    {
        if (is_int($value) || is_float($value)) {
            return '<c r="' . $ref . '" s="' . $style . '"><v>' . $value . '</v></c>';
        }
        $text = htmlspecialchars((string) ($value ?? ''), ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8');

        return '<c r="' . $ref . '" s="' . $style . '" t="inlineStr"><is><t xml:space="preserve">' . $text . '</t></is></c>';
    }

    private static function col(int $n): string
    {
        $s = '';
        while ($n > 0) {
            $m = ($n - 1) % 26;
            $s = chr(65 + $m) . $s;
            $n = intdiv($n - 1, 26);
        }

        return $s;
    }

    private static function styles(): string
    {
        $border = '<border><left style="thin"><color rgb="FFBFD3D1"/></left><right style="thin"><color rgb="FFBFD3D1"/></right>'
            . '<top style="thin"><color rgb="FFBFD3D1"/></top><bottom style="thin"><color rgb="FFBFD3D1"/></bottom><diagonal/></border>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="3">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="14"/><color rgb="FF0B3B3A"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF05A9A4"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border>' . $border . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1" applyAlignment="1"><alignment vertical="center"/></xf>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }
}
