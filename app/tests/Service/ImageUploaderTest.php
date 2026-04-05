<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\ImageUploader;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToWriteFile;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploaderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testUploadWritesStreamToS3AndReturnsFilename(): void
    {
        $tmpFile = tmpfile();
        $tmpPath = stream_get_meta_data($tmpFile)['uri'];

        $fileMock = Mockery::mock(UploadedFile::class);
        $fileMock->shouldReceive('getClientOriginalName')->andReturn('photo.jpg');
        $fileMock->shouldReceive('getPathname')->andReturn($tmpPath);

        $s3Mock = Mockery::mock(FilesystemOperator::class);
        $s3Mock->shouldReceive('writeStream')
            ->once()
            ->with('photo.jpg', Mockery::type('resource'));

        $uploader = new ImageUploader($s3Mock);
        $result = $uploader->upload($fileMock);

        $this->assertSame('photo.jpg', $result);

        fclose($tmpFile);
    }

    public function testUploadPropagatesFilesystemException(): void
    {
        $tmpFile = tmpfile();
        $tmpPath = stream_get_meta_data($tmpFile)['uri'];

        $fileMock = Mockery::mock(UploadedFile::class);
        $fileMock->shouldReceive('getClientOriginalName')->andReturn('photo.jpg');
        $fileMock->shouldReceive('getPathname')->andReturn($tmpPath);

        $s3Mock = Mockery::mock(FilesystemOperator::class);
        $s3Mock->shouldReceive('writeStream')
            ->andThrow(UnableToWriteFile::atLocation('photo.jpg'));

        $uploader = new ImageUploader($s3Mock);

        $this->expectException(UnableToWriteFile::class);
        $uploader->upload($fileMock);

        fclose($tmpFile);
    }
}
