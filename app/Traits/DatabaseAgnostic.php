<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DatabaseAgnostic
{
    /**
     * Get database driver name
     */
    protected function getDatabaseDriver(): string
    {
        $driver = config('database.default');
        return config("database.connections.{$driver}.driver");
    }

    /**
     * Get YEAR function for current database
     */
    protected function getYearFunction(string $column): string
    {
        return $this->getDatabaseDriver() === 'sqlite' 
            ? "strftime('%Y', {$column})" 
            : "YEAR({$column})";
    }

    /**
     * Get MONTH function for current database
     */
    protected function getMonthFunction(string $column): string
    {
        return $this->getDatabaseDriver() === 'sqlite' 
            ? "strftime('%m', {$column})" 
            : "MONTH({$column})";
    }

    /**
     * Get DATE function for current database
     */
    protected function getDateFunction(string $column): string
    {
        return $this->getDatabaseDriver() === 'sqlite' 
            ? "date({$column})" 
            : "DATE({$column})";
    }

    /**
     * Get QUARTER function for current database
     */
    protected function getQuarterFunction(string $column): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            return "CASE 
                WHEN strftime('%m', {$column}) IN ('01', '02', '03') THEN 1
                WHEN strftime('%m', {$column}) IN ('04', '05', '06') THEN 2
                WHEN strftime('%m', {$column}) IN ('07', '08', '09') THEN 3
                ELSE 4
            END";
        } else {
            return "QUARTER({$column})";
        }
    }

    /**
     * Get DAY function for current database
     */
    protected function getDayFunction(string $column): string
    {
        return $this->getDatabaseDriver() === 'sqlite' 
            ? "strftime('%d', {$column})" 
            : "DAY({$column})";
    }

    /**
     * Get WEEK function for current database
     */
    protected function getWeekFunction(string $column): string
    {
        return $this->getDatabaseDriver() === 'sqlite' 
            ? "strftime('%W', {$column})" 
            : "WEEK({$column})";
    }

    /**
     * Get CONCAT function for current database
     */
    protected function getConcatFunction(array $columns): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            return implode(' || ', $columns);
        } else {
            return 'CONCAT(' . implode(', ', $columns) . ')';
        }
    }

    /**
     * Get IF/CASE function for current database
     */
    protected function getIfFunction(string $condition, string $trueValue, string $falseValue): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            return "CASE WHEN {$condition} THEN {$trueValue} ELSE {$falseValue} END";
        } else {
            return "IF({$condition}, {$trueValue}, {$falseValue})";
        }
    }

    /**
     * Get COALESCE function (works on all databases)
     */
    protected function getCoalesceFunction(array $columns): string
    {
        return 'COALESCE(' . implode(', ', $columns) . ')';
    }

    /**
     * Get date format function for current database
     */
    protected function getDateFormatFunction(string $column, string $format): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            // Convert MySQL format to SQLite format
            $sqliteFormat = str_replace(
                ['%Y', '%m', '%d', '%H', '%i', '%s'],
                ['%Y', '%m', '%d', '%H', '%M', '%S'],
                $format
            );
            return "strftime('{$sqliteFormat}', {$column})";
        } else {
            return "DATE_FORMAT({$column}, '{$format}')";
        }
    }

    /**
     * Get TIMESTAMPDIFF function for current database
     */
    protected function getTimestampDiffFunction(string $unit, string $startDate, string $endDate): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            switch (strtoupper($unit)) {
                case 'DAY':
                    return "julianday({$endDate}) - julianday({$startDate})";
                case 'MONTH':
                    return "(strftime('%Y', {$endDate}) - strftime('%Y', {$startDate})) * 12 + (strftime('%m', {$endDate}) - strftime('%m', {$startDate}))";
                case 'YEAR':
                    return "strftime('%Y', {$endDate}) - strftime('%Y', {$startDate})";
                default:
                    return "julianday({$endDate}) - julianday({$startDate})";
            }
        } else {
            return "TIMESTAMPDIFF({$unit}, {$startDate}, {$endDate})";
        }
    }

    /**
     * Get GROUP_CONCAT function for current database
     */
    protected function getGroupConcatFunction(string $column, string $separator = ','): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            return "GROUP_CONCAT({$column}, '{$separator}')";
        } else {
            return "GROUP_CONCAT({$column} SEPARATOR '{$separator}')";
        }
    }

    /**
     * Get LIMIT function for current database
     */
    protected function getLimitFunction(int $limit, int $offset = 0): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            return $offset > 0 ? "LIMIT {$limit} OFFSET {$offset}" : "LIMIT {$limit}";
        } else {
            return $offset > 0 ? "LIMIT {$offset}, {$limit}" : "LIMIT {$limit}";
        }
    }

    /**
     * Get REGEXP function for current database
     */
    protected function getRegexpFunction(string $column, string $pattern): string
    {
        if ($this->getDatabaseDriver() === 'sqlite') {
            return "{$column} REGEXP '{$pattern}'";
        } else {
            return "{$column} REGEXP '{$pattern}'";
        }
    }

    /**
     * Get CAST function for current database
     */
    protected function getCastFunction(string $column, string $type): string
    {
        return "CAST({$column} AS {$type})";
    }

    /**
     * Get ROUND function for current database
     */
    protected function getRoundFunction(string $column, int $decimals = 2): string
    {
        return "ROUND({$column}, {$decimals})";
    }

    /**
     * Get ABS function for current database
     */
    protected function getAbsFunction(string $column): string
    {
        return "ABS({$column})";
    }

    /**
     * Get SUM function for current database
     */
    protected function getSumFunction(string $column): string
    {
        return "SUM({$column})";
    }

    /**
     * Get COUNT function for current database
     */
    protected function getCountFunction(string $column = '*'): string
    {
        return "COUNT({$column})";
    }

    /**
     * Get AVG function for current database
     */
    protected function getAvgFunction(string $column): string
    {
        return "AVG({$column})";
    }

    /**
     * Get MAX function for current database
     */
    protected function getMaxFunction(string $column): string
    {
        return "MAX({$column})";
    }

    /**
     * Get MIN function for current database
     */
    protected function getMinFunction(string $column): string
    {
        return "MIN({$column})";
    }
}
