<?php

namespace APP\Tests\Unit\Reader;

use H22k\CommissionCalculator\Exception\Reader\FileCanNotOpenException;
use H22k\CommissionCalculator\Exception\Reader\FileNotFoundException;
use H22k\CommissionCalculator\Reader\File;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private vfsStreamFile $readableFile;
    private vfsStreamFile $notReadableFile;

    const READABLE_FILE_CONTENT = '{"bin":"4745030","amount":"2000.00","currency":"GBP"}';

    protected function setUp(): void
    {
        $fileSystem = vfsStream::setup();
        $this->readableFile = vfsStream::newFile('readable.txt', 444)
            ->setContent(self::READABLE_FILE_CONTENT)
            ->at($fileSystem);

        $this->notReadableFile = vfsStream::newFile('not_readable.txt', 000)
            ->at($fileSystem);
    }

    /**
     * @throws FileCanNotOpenException
     */
    public function test_throw_filenotfoundexception_if_file_not_exist(): void
    {
        $NOT_EXIST_FILENAME = 'not_exists_file_name.txt';
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage(sprintf("%s is not found!", $NOT_EXIST_FILENAME));

        new File($NOT_EXIST_FILENAME);
    }

    /**
     * @throws FileNotFoundException
     */
    public function test_throw_filecannotopenexception_if_file_is_not_readable()
    {
        $FILENAME = $this->notReadableFile->url();
        $this->assertFileIsNotReadable($FILENAME);

        $this->expectException(FileCanNotOpenException::class);
        $this->expectExceptionMessage(sprintf("File %s can not open!", $FILENAME));

        new File($FILENAME);
    }

    /**
     * @throws FileNotFoundException
     * @throws FileCanNotOpenException
     */
    public function test_readByLine_method_should_can_read_file_content_line_by_line()
    {
        $FILENAME = $this->readableFile->url();
        $this->assertFileIsReadable($FILENAME);

        $file = new File($FILENAME);

        $lineCount = 0;

        foreach ($file->readByLine() as $item) {
            $this->assertEquals(self::READABLE_FILE_CONTENT, $item);
            $lineCount++;
        }

        $this->assertEquals(1, $lineCount);
    }

}