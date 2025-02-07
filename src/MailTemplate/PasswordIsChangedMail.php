<?php

namespace Mediamouse\Users\MailTemplate;

use Mediamouse\Mails\MailTemplate\MailTemplateAbstract;
use Mediamouse\Mails\Models\MailTemplate;

class PasswordIsChangedMail extends MailTemplateAbstract {
    protected static string $key = 'UserPasswordReset';
    protected static string $name = 'Inform user about password reset';

    protected static bool $bcc_active = false;
    protected static bool $bcc_allowed = false;

    protected static function configure(MailTemplate $template): void
    {
        $template->addField('name', 'Full name', test: 'John Doe', tooltip: 'The Full name of the person trying to login');
        $template->addField('email', 'Email address', test: 'john.doe@example.com', tooltip: 'The Email address of the person trying to login');
        $template->addField('ip', 'IP address', test: '212.126.25.38', tooltip: 'The IP-Address of the person trying to login');
    }

}
