<?php

use Htmlacademy\Utils\CsvParser;
use Htmlacademy\Utils\SqlConverter;
use Htmlacademy\Utils\SqlFileWriter;
use Htmlacademy\Exceptions\FileFormatException;
use Htmlacademy\Exceptions\SourceFileException;

require_once '../../vendor/autoload.php';

$directory = '../../data/';
$csvFiles = array_filter(scandir($directory), function ($extension) {
    return substr($extension, -4) === '.csv';
});

foreach ($csvFiles as $file) {
    $import = new SplFileObject($directory . $file);
    $parser = new CsvParser($import);
    $converter = new SqlConverter();
    $writer = new SqlFileWriter();

    try {
        $parser->parse();
        $values = $parser->getData();
        $columns = $parser->getHeaderRow();
    } catch (SourceFileException $e) {
        error_log("Couldn't parse the csv file: " . $e->getMessage());
    } catch (FileFormatException $e) {
        error_log("Error with imported file: " . $e->getMessage());
    }

    try {
        //TODO: regex for table name
        $table = substr(substr($file, 3), 0, -4);
        $statement = $converter->createInsertStatement($table, $columns, $values);
    } catch (SourceFileException $e) {
        error_log("Couldn't convert the data: " . $e->getMessage());
    }

    $writer->writeFile($statement . PHP_EOL);
}

