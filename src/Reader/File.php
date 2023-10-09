<?php

namespace H22k\CommissionCalculator\Reader;

use Generator;
use H22k\CommissionCalculator\Exception\Reader\FileCanNotOpenException;
use H22k\CommissionCalculator\Exception\Reader\FileNotFoundException;

class File
{
    private $file;

    /**
     * @throws FileCanNotOpenException|FileNotFoundException
     */
    public function __construct(private readonly string $fileName, string $mode = 'r')
    {
        if (!file_exists($fileName)) {
            throw new FileNotFoundException($fileName);
        }

        $this->file = fopen($fileName, $mode);

        if (!$this->file) {
            throw new FileCanNotOpenException(sprintf("File %s can not open!", $fileName));
        }
    }

    public function __destruct()
    {
        $this->closeFile();
    }

    public function readByLine(): Generator
    {
        while ($buffer = fgets($this->file)) {
            yield $buffer;
        }
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    private function closeFile(): void
    {
        if ($this->file) {
            fclose($this->file);
            $this->file = null;
        }
    }
}