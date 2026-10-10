<?php

namespace Tests\Feature;

use App\Http\Middleware\NormalizeMalformedUploads;
use Illuminate\Http\Request;
use Tests\TestCase;

class NormalizeMalformedUploadsTest extends TestCase
{
    public function test_malformed_file_bag_returns_a_validation_response_instead_of_throwing(): void
    {
        $request = Request::create('/upload', 'POST');
        $request->headers->set('Accept', 'application/json');
        $request->files->set('image', ['name' => 'download.jpg']);
        $continued = false;

        $response = app(NormalizeMalformedUploads::class)->handle(
            $request,
            function () use (&$continued) {
                $continued = true;
                return response('processed');
            }
        );

        $this->assertSame(422, $response->getStatusCode());
        $this->assertFalse($continued);
        $this->assertSame(
            'فایل ارسالی قابل پردازش نیست. لطفاً فایل را دوباره انتخاب و ارسال کنید.',
            $response->getData(true)['message']
        );
    }
}
