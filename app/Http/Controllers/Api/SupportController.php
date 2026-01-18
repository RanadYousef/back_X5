<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\SupportRequest;
use App\Mail\SupportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SupportController extends BaseApiController
{
    /*
     * Send support email
     */
    public function send(SupportRequest $request)
    {
        try {
            $data = $request->validated();

            // send email to support address
            Mail::to(config('mail.support_email'))->send(
                new SupportMail($data)
            );

            return $this->success([], 'donation request sent successfully');

        } catch (Exception $e) {
            Log::error('Support email failed', [
                'error' => $e->getMessage()
            ]);

            return $this->error('Failed to send support request', 400);
        }
    }
}