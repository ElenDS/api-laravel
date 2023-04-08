<?php

namespace App\Services;

use App\Jobs\SendEmailVerificationJob;
use App\Models\VerifyLink;

class SendVerifyLinkService
{
    public function sendVerificationMail(int $id, string $email): void
    {
        $hash = uniqid();
        $link = new VerifyLink();
        $link->email = $email;
        $link->uniqid = $hash;
        $link->save();

        $verifyLink = 'http://api.localhost/verify/' . $id . '/' . $hash;

        SendEmailVerificationJob::dispatch($verifyLink, $email);
    }
    public function findVerifyLink($email, $hash): VerifyLink
    {
        return VerifyLink::where([
            'uniqid' => $hash,
            'email' => $email
        ])->first();
    }
    public function deleteLink(string $email, string $hash): void
    {
        $link = $this->findVerifyLink($email, $hash);
        $link->delete();
    }

}
