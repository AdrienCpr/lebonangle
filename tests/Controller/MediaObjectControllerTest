<?php

namespace App\Tests\Controller;

use App\Controller\MediaObjectActionController;
use App\Entity\Picture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MediaObjectControllerTest extends TestCase
{
    public function testUploadFile(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($file, 'test');
        $uploadedFile = new UploadedFile($file, 'test.txt', 'text/plain', null, true);
        $request = new Request([], [], [], [], ['file' => $uploadedFile]);

        $controller = new MediaObjectActionController();

        $result = $controller->__invoke($request);
        $this->assertInstanceOf(Picture::class, $result);
    }

}
