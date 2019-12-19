<?php

declare(strict_types=1);

namespace Htmlacademy\Utils;

use Htmlacademy\Exceptions\SourceFileException;

class SqlConverter
{
    /**
     * Wrap strings in quoted identifiers.
     * For table and column names.
     *
     * @param string|array $value
     *
     * @return string|array
     */
    private function formatIdentifiers($value)
    {
        //TODO: strict types ?
        if (!is_array($value)) {
            return "`{$value}`";
        }

        return array_map(function ($backtick) {
            return $backtick = "`{$backtick}`";
        }, $value);
    }

    /**
     * Convert values in array according to MySQL syntax.
     *
     * Cast numeric strings to integers,
     * empty or 'null' strings to NULL
     * wrap dates and strings in single quotes.
     *
     * @param array $values
     *
     * @return array
     */
    private function convertValues(array $values): array
    {
        return array_map(function ($formatValue) {
            if ($formatValue === '' || strtoupper($formatValue) === 'NULL') {
                return 'NULL';
            }
            if (is_numeric($formatValue)) {
                return (int)$formatValue;
            } else {
                return "'{$formatValue}'";
            }
        }, $values);
    }

    /**
     * @param string $table
     * @param array $columns
     * @param array $values
     *
     * @return string
     * @throws \Htmlacademy\Exceptions\SourceFileException
     */
    public function createInsertStatement(string $table, array $columns, array $values): string
    {
        $formattedValues = null;
        for ($i = 0; $i <= count($values) - 1; $i++) {
            $rowElements = $this->convertValues($values[$i]);
            if (count($columns) !== count($values[$i])) {
                throw new SourceFileException("Number of values is not equal to number of columns for table {$table} row {$i}");
            }
            $rowString = implode(', ', $rowElements);
            $formattedValues .= "({$rowString}),\n";
        }

        $formattedTable = $this->formatIdentifiers($table);
        $formattedColumns = implode(', ', $this->formatIdentifiers($columns));
        $formattedValues = substr_replace($formattedValues, ';', -2);

        $statement = "INSERT INTO\n{$formattedTable}\n({$formattedColumns})\nVALUES\n{$formattedValues}";

        return $statement . PHP_EOL;
    }
}
