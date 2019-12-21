<?php

declare(strict_types=1);

namespace Htmlacademy\Utils;

use SplFileObject;

class SqlFileWriter
{
    /** @var string */
    private $directoryPath;

    /**
     * SqlFileWriter constructor.
     *
     * @param string $directoryPath
     */
    public function __construct(string $directoryPath)
    {
        $this->directoryPath = $directoryPath;
    }

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

