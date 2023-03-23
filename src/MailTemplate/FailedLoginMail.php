<?php

namespace Mediamouse\Users\MailTemplate;

use Mediamouse\Mails\MailTemplate\MailTemplateAbstract;
use Mediamouse\Mails\Models\MailTemplate;

class LoginChallengeMail extends MailTemplateAbstract {
    protected static string $key = 'UserFailedLogin';
    protected static string $name = 'Sent failed login email to user';

    protected static function configure(MailTemplate $template): void
    {
        $template->addField('name', 'Full name', test: 'John Doe', tooltip: 'The Full name of the person trying to login');
        $template->addField('email', 'Email address', test: 'john.doe@example.com', tooltip: 'The Email address of the person trying to login');
        $template->addField('ip', 'IP address', test: '212.126.25.38', tooltip: 'The IP-Address of the person trying to login');
        $template->addField('count', 'Nr of attempts', test: '1', tooltip: 'Total number of failed attempts since last login');
    }

}
