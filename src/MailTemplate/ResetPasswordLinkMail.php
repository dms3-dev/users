<?php

namespace Mediamouse\Users\MailTemplate;

use Mediamouse\Mails\MailTemplate\MailTemplateAbstract;
use Mediamouse\Mails\Models\MailTemplate;

class ResetPasswordLinkMail extends MailTemplateAbstract {
    protected static string $key = 'UserForgotPassword';
    protected static string $name = 'Sent password reset link to user';

    protected static function configure(MailTemplate $template): void
    {
        $template->addField('name', 'Full name', test: 'John Doe', tooltip: 'The Full name of the person trying to login');
        $template->addField('email', 'Email address', test: 'john.doe@example.com', tooltip: 'The Email address of the person trying to login');
        $template->addField('ip', 'IP address', test: '212.126.25.38', tooltip: 'The IP-Address of the person trying to login');
        $template->addField('link', 'Reset link', test: 'https://www.example.com/reset-password', tooltip: 'Link to reset your password');
    }

}
