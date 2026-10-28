<?php

namespace App\Exports;

use Carbon\CarbonInterface;
use DOMDocument;
use DOMElement;
use DOMXPath;
use ZipArchive;
use RuntimeException;
use Throwable;

/** Uses the approved daily report's actual fonts, fills, borders and A4 layout. */
class DailyGroupCallReportExport
{
    private const NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    public function generate(array $rows, CarbonInterface $date): string
    {
        $temp = tempnam(sys_get_temp_dir(), 'daily_group_');
        if ($temp === false) {
            throw new RuntimeException('Could not create the daily report file.');
        }
        $file = $temp . '.xlsx';
        rename($temp, $file);

        try {
            if (!copy(resource_path('exports/daily-group-call-report-template.xlsx'), $file)) {
                throw new RuntimeException('Could not copy the daily report template.');
            }
            $archive = new ZipArchive();
            if ($archive->open($file) !== true) {
                throw new RuntimeException('Could not open the daily report template.');
            }
            $sheet = new DOMDocument();
            $sheet->loadXML($archive->getFromName('xl/worksheets/sheet1.xml'), LIBXML_NONET);
            $xpath = new DOMXPath($sheet);
            $xpath->registerNamespace('s', self::NS);
            $data = $xpath->query('/s:worksheet/s:sheetData')->item(0);
            $templates = [];
            foreach ($xpath->query('s:row', $data) as $row) {
                $rowNumber = (int) $row->getAttribute('r');
                if ($rowNumber <= 4) {
                    $templates[$rowNumber] = $row->cloneNode(true);
                }
            }
            while ($data->firstChild) {
                $data->removeChild($data->firstChild);
            }
            $serial = (int) ((gmmktime(0, 0, 0, (int) $date->format('n'), (int) $date->format('j'), (int) $date->format('Y'))
                - gmmktime(0, 0, 0, 12, 30, 1899)) / 86400);
            $data->appendChild($templates[1]);
            $this->append($sheet, $data, $templates[2], 2, ['A' => $serial]);
            $number = 3;
            $day = $date->format('l');
            foreach ($rows as $row) {
                $this->append($sheet, $data, $templates[$row['leader'] ? 3 : 4], $number++, [
                    'A' => $row['name'], 'B' => $serial, 'C' => $day,
                    'D' => $row['called_mailed'], 'E' => $row['incoming'], 'F' => $row['outgoing'],
                    'G' => $row['inbound_duration'] === null ? null : $row['inbound_duration'] / 86400,
                    'H' => $row['outbound_duration'] === null ? null : $row['outbound_duration'] / 86400,
                    'I' => $row['extension'],
                ]);
            }
            $last = $number - 1;
            $xpath->query('/s:worksheet/s:dimension')->item(0)?->setAttribute('ref', "A1:I{$last}");
            $setup = $xpath->query('/s:worksheet/s:pageSetup')->item(0);
            $setup->setAttribute('paperSize', '9');
            $setup->setAttribute('orientation', 'landscape');
            $setup->setAttribute('fitToWidth', '1');
            // Keep ordinary teams on one page; permit additional pages for a larger roster.
            $setup->setAttribute('fitToHeight', count($rows) <= 28 ? '1' : '0');
            $archive->addFromString('xl/worksheets/sheet1.xml', $sheet->saveXML());

            $book = new DOMDocument();
            $book->loadXML($archive->getFromName('xl/workbook.xml'), LIBXML_NONET);
            $bookPath = new DOMXPath($book);
            $bookPath->registerNamespace('s', self::NS);
            $names = $bookPath->query('/s:workbook/s:definedNames')->item(0);
            foreach (iterator_to_array($names->childNodes) as $name) {
                if ($name instanceof DOMElement && in_array($name->getAttribute('name'), ['_xlnm.Print_Area', '_xlnm.Print_Titles'], true)) {
                    $names->removeChild($name);
                }
            }
            foreach (['_xlnm.Print_Area' => "'Daily Calls'!\$A\$1:\$I\${$last}", '_xlnm.Print_Titles' => "'Daily Calls'!\$1:\$2"] as $name => $value) {
                $node = $book->createElementNS(self::NS, 'definedName');
                $node->setAttribute('name', $name);
                $node->setAttribute('localSheetId', '0');
                $node->appendChild($book->createTextNode($value));
                $names->appendChild($node);
            }
            $archive->addFromString('xl/workbook.xml', $book->saveXML());
            if (!$archive->close()) {
                throw new RuntimeException('Could not save the daily report file.');
            }
            unset($archive);

            return $file;
        } catch (Throwable $exception) {
            unset($archive);
            @unlink($file);
            throw $exception;
        }
    }

    private function append(DOMDocument $sheet, DOMElement $data, DOMElement $template, int $number, array $values): void
    {
        $row = $template->cloneNode(true);
        $row->setAttribute('r', (string) $number);
        foreach ($row->getElementsByTagNameNS(self::NS, 'c') as $cell) {
            $column = preg_replace('/\d+$/', '', $cell->getAttribute('r'));
            $cell->setAttribute('r', $column . $number);
            $cell->removeAttribute('t');
            while ($cell->firstChild) {
                $cell->removeChild($cell->firstChild);
            }
            $value = $values[$column] ?? null;
            if ($value === null) {
                continue;
            }
            if (is_int($value) || is_float($value)) {
                $cell->appendChild($sheet->createElementNS(self::NS, 'v', (string) $value));
            } else {
                // Always literal text, including names beginning with '='.
                $cell->setAttribute('t', 'inlineStr');
                $inline = $sheet->createElementNS(self::NS, 'is');
                $text = $sheet->createElementNS(self::NS, 't');
                $text->appendChild($sheet->createTextNode((string) $value));
                $inline->appendChild($text);
                $cell->appendChild($inline);
            }
        }
        $data->appendChild($row);
    }
}
