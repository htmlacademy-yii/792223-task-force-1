<?php

declare(strict_types=1);

namespace Htmlacademy\Utils;

use Htmlacademy\Exceptions\FileFormatException;
use Htmlacademy\Exceptions\SourceFileException;
use SplFileObject;

class CsvParser
{
    /** @var \SplFileObject */
    private $file;

    /** @var resource */
    private $filePointer;

    /** @var array */
    private $data = [];

    public function __construct(SplFileObject $file)
    {
        $this->file = $file;
    }

    /**
     * @throws \Htmlacademy\Exceptions\FileFormatException
     * @throws \Htmlacademy\Exceptions\SourceFileException
     */
    private function parse(): void
    {
        if (!$this->file->isFile()) {
            throw new SourceFileException("File {$this->file->getFilename()} does not exist");
        }

        $this->filePointer = $this->file->openFile('r');

        if (!$this->filePointer->isReadable()) {
            throw new SourceFileException("Could not read the {$this->file->getFilename()} file");
        }

        if ($this->filePointer->getExtension() !== 'csv') {
            throw new FileFormatException("File extension for {$this->file->getFilename()} is not .csv");
        }

        $this->filePointer->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );

        while ($line = $this->getNextLine()) {
            $this->data[] = $line;
        }
    }

    /**
     * @return array
     * @throws \Htmlacademy\Exceptions\FileFormatException
     * @throws \Htmlacademy\Exceptions\SourceFileException
     */
    public function getHeaderRow(): array
    {
        if (empty($this->data)) {
            $this->parse();
        }

        return $this->data[0];
    }

    /**
     * @param bool $withHeader
     *
     * @return array
     * @throws \Htmlacademy\Exceptions\FileFormatException
     * @throws \Htmlacademy\Exceptions\SourceFileException
     */
    public function getData(bool $withHeader = false): array
    {
        if (empty($this->data)) {
            $this->parse();
        }

        if ($withHeader) {
            return $this->data;
        }

        return array_slice($this->data, 1);
    }

    /**
     * @return array|null
     */
    private function getNextLine(): ?array
    {
        $line = null;

        if ($this->filePointer->valid()) {
            $line = $this->filePointer->fgetcsv();
            //TODO: check why yield consumes too mush memory
        }

        return $line;
    }
}
