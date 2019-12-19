<?php

declare(strict_types=1);

namespace Htmlacademy\Utils;

use SplFileObject;

class SqlFileWriter
{
    private $directoryPath = ('../../data/sql_dump/');

    /**
     * Export data in file and return path to file
     *
     * @param string $data
     *
     * @return void
     */
    public function writeFile(string $data): void
    {
        $fileName = 'fixtures_' . time() . '.sql';
        $file = new SplFileObject($this->directoryPath . $fileName, "a");
        $file->fwrite($data);
    }
}

