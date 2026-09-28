<?php

namespace App\Exports;

use Carbon\CarbonInterface;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Phar;
use PharData;
use RuntimeException;
use Throwable;

class WeeklyCallReportExport
{
    private const NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    public function generate(array $groups, CarbonInterface $weekStart, CarbonInterface $weekEnd): string
    {
        $file = tempnam(sys_get_temp_dir(), 'weekly_calls_');
        if ($file === false) {
            throw new RuntimeException('Could not create the weekly export file.');
        }

        try {
            if (!copy(resource_path('exports/weekly-call-report-template.xlsx'), $file)) {
                throw new RuntimeException('Could not copy the weekly report template.');
            }

            // PharData supports XLSX ZIP archives without requiring the optional zip extension.
            $archive = new PharData($file, 0, null, Phar::ZIP);
            $sheet = new DOMDocument();
            $sheet->loadXML($archive['xl/worksheets/sheet1.xml']->getContent(), LIBXML_NONET);
            $xpath = new DOMXPath($sheet);
            $xpath->registerNamespace('s', self::NS);
            $data = $xpath->query('/s:worksheet/s:sheetData')->item(0);
            $templates = [];
            foreach ($xpath->query('s:row', $data) as $row) {
                $templates[(int) $row->getAttribute('r')] = $row->cloneNode(true);
            }
            while ($data->firstChild) {
                $data->removeChild($data->firstChild);
            }

            $title = 'Team Leaders and their recruiters performance report ('
                . $weekStart->format('j-n-y') . ' to ' . $weekEnd->format('j-n-y') . ')';
            $this->appendRow($sheet, $data, $templates[1], 1, ['A' => $title]);
            $data->appendChild($templates[2]);

            $rowNumber = 3;
            $serial = 1;
            $countCells = [];
            $total = 0;
            $hasDuplicateMember = false;

            foreach ($groups as $index => $group) {
                $leader = $group['leader'];
                $styleRow = $leader ? 3 + ($index % 3) * 2 : 9;
                $this->appendRow($sheet, $data, $templates[$styleRow], $rowNumber++, [
                    'A' => $leader ? $serial++ : null,
                    'B' => $leader ? $this->extension($leader['phone']) : null,
                    'C' => $leader ? $leader['name'] . ' TL' : 'No senior mail group',
                    'J' => '-',
                ]);

                foreach ($group['members'] as $member) {
                    $this->appendRow($sheet, $data, $templates[$styleRow + 1], $rowNumber, [
                        'A' => $serial++,
                        'B' => $this->extension($member['phone']),
                        'C' => $member['name'],
                        'J' => (int) $member['count'],
                    ]);
                    if (!isset($countCells[$member['id']])) {
                        $countCells[$member['id']] = 'J' . $rowNumber;
                        $total += (int) $member['count'];
                    } else {
                        $hasDuplicateMember = true;
                    }
                    $rowNumber++;
                }
                $rowNumber++;
            }

            $totalRow = $this->appendRow($sheet, $data, $templates[11], $rowNumber, ['C' => 'GRAND TOTAL', 'J' => $total]);
            $totalCell = $xpath->query('s:c[@r="J' . $rowNumber . '"]', $totalRow)->item(0);
            // A recruiter may be listed in multiple mail groups; count each once in the grand total.
            $formula = $hasDuplicateMember
                ? 'SUM(' . implode(',', $countCells) . ')'
                : 'SUM(J3:J' . ($rowNumber - 1) . ')';
            $formulaNode = $sheet->createElementNS(self::NS, 'x:f', $formula);
            $totalCell->insertBefore($formulaNode, $totalCell->firstChild);

            $rowNumber += 2;
            $note = 'Called & Mailed uses CRM status and follow-up dates for the selected week. Other call-record fields are blank.';
            if ($hasDuplicateMember) {
                $note .= ' Grand total counts each recruiter once.';
            }
            $this->appendRow($sheet, $data, $templates[12], $rowNumber, ['A' => $note]);

            $merges = $xpath->query('/s:worksheet/s:mergeCells')->item(0);
            while ($merges->firstChild) {
                $merges->removeChild($merges->firstChild);
            }
            foreach (['A1:L1', 'A' . $rowNumber . ':L' . $rowNumber] as $ref) {
                $merge = $sheet->createElementNS(self::NS, 'x:mergeCell');
                $merge->setAttribute('ref', $ref);
                $merges->appendChild($merge);
            }
            $merges->setAttribute('count', '2');

            $dimension = $sheet->createElementNS(self::NS, 'x:dimension');
            $dimension->setAttribute('ref', 'A1:L' . $rowNumber);
            $sheet->documentElement->insertBefore($dimension, $sheet->documentElement->firstChild);
            $archive['xl/worksheets/sheet1.xml'] = $sheet->saveXML();

            $workbook = new DOMDocument();
            $workbook->loadXML($archive['xl/workbook.xml']->getContent(), LIBXML_NONET);
            $calculation = $workbook->createElementNS(self::NS, 'x:calcPr');
            $calculation->setAttribute('calcMode', 'auto');
            $calculation->setAttribute('fullCalcOnLoad', '1');
            $workbook->documentElement->appendChild($calculation);
            $archive['xl/workbook.xml'] = $workbook->saveXML();
            unset($archive);

            return $file;
        } catch (Throwable $exception) {
            unset($archive);
            if (is_file($file)) {
                unlink($file);
            }
            throw $exception;
        }
    }

    private function appendRow(DOMDocument $sheet, DOMElement $data, DOMElement $template, int $number, array $values): DOMElement
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
            if ($value === null || $value === '') {
                continue;
            }
            if (is_int($value)) {
                $cell->appendChild($sheet->createElementNS(self::NS, 'x:v', (string) $value));
            } else {
                $cell->setAttribute('t', 'inlineStr');
                $inline = $sheet->createElementNS(self::NS, 'x:is');
                $text = $sheet->createElementNS(self::NS, 'x:t');
                $text->appendChild($sheet->createTextNode((string) $value));
                $inline->appendChild($text);
                $cell->appendChild($inline);
            }
        }
        if (isset($values['C']) && mb_strlen($values['C']) > 32) {
            $row->setAttribute('ht', (string) (24 * ceil(mb_strlen($values['C']) / 32)));
        }
        $data->appendChild($row);

        return $row;
    }

    private function extension(?string $phone): string
    {
        // Same as the Ext. No. column on /dashboard/admin/junior: {{ $user->phone ?: '-' }}
        return $phone ?: '-';
    }
}
