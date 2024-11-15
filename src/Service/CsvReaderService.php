<?php

namespace App\Service;

use Exception;

class CsvReaderService
{
    /**
     * Returns valid rows of file
     *
     * @throws Exception
     */
    public function getRows(string $filename): array
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new Exception('File does not exist or is not readable');
        }

        $rows = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$rows) {
            throw new Exception('File is empty');
        }

        $rows = $this->getValidRows($rows);

        return $rows;
    }

    private function getValidRows(array $rows): array
    {
        $validRows = [];

        foreach ($rows as $row) {
            if ($this->isValidRow($row)) {
                $validRows[] = $row;
            }
        }

        return $validRows;
    }

    private function isValidRow(string $row): bool
    {
        if (!mb_detect_encoding($row, 'ASCII', true)) {
            return false;
        }
        if (empty($row)) {
            return false;
        }

        return true;
    }
}