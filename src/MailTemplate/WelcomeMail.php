<?php

namespace Mediamouse\Users\MailTemplate;

use Mediamouse\Mails\MailTemplate\MailTemplateAbstract;
use Mediamouse\Mails\Models\MailTemplate;

class WelcomeMail extends MailTemplateAbstract
{
    protected static string $key = 'UserWelcomeMail';
    protected static string $name = 'Sent welcome mail with create password link to user';

    protected static bool $bcc_active = false;
    protected static bool $bcc_allowed = false;

    protected static function configure(MailTemplate $template): void
    {
        $template->addField('name', 'Full name', test: 'John Doe', tooltip: 'The Full name of the person trying to login');
        $template->addField('username', 'User name', test: 'johndoe', tooltip: 'The User name or email of the person with what he needs to login');
        $template->addField('email', 'Email address', test: 'john.doe@example.com', tooltip: 'The Email address of the person trying to login');
        $template->addField('link', 'Create password link', test: 'https://www.example.com/create-password', tooltip: 'Link to create a new password');
    }

}
