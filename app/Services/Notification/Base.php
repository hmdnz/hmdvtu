<?php

namespace App\Services\Notification;

class Base 
{
//     MAIL_MAILER=smtp
// MAIL_HOST=smtp.mailtrap.io
// MAIL_PORT=2525
// MAIL_USERNAME=your_mailtrap_username
// MAIL_PASSWORD=your_mailtrap_password
// MAIL_ENCRYPTION=tls
// MAIL_FROM_ADDRESS=example@example.com
// MAIL_FROM_NAME="Your App Name"
    protected $mailHost;
    protected $mailPort;
    protected $mailUsername;
    protected $mailPassword;
    protected $mailEncrption;
    protected $mailAddress;
    protected $mailName;

    public function __construct()
    {
        $this->mailHost = env('MAIL_HOST');
        $this->mailPort = env('MAIL_PORT');
        $this->mailUsername = env('MAIL_USERNAME');
        $this->mailPassword = env('MAIL_PASSWORD');
        $this->mailEncrption = env('MAIL_ENCRYPTION');
        $this->mailAddress = env('MAIL_ADDRESS');
        $this->mailName = env('MAIL_NAME');
        
    }
}