<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
use Mediamouse\Users\Models\SystemHealth;
use Mediamouse\Users\Policies\PolicyAbstract;

class CheckSystemHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mediamouse-users:update-system-health {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check System Health';

    private array $errors;
    private bool $changed = false;

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $this->errors = [];
        $this->withProgressBar($this->getChecks(), function(SystemHealth $check) {
            $status = $check->status;

            $check->check($this->option('force'));

            if($check->status !== $status && in_array($check->status, [
                    SystemHealthStatus::ERROR,
                    SystemHealthStatus::WARNING,
                ])) {
                $this->changed = true;
            }

            $this->mark($check->status,  (new ($check->health_check))->getName($check->status));

        });

        if($this->changed) {
            $this->sendErrorMail('Jeroen', 'jeroen@mediamouse.nl',  $this->errors);
            $this->sendErrorMail('Gijs', 'gijs@mediamouse.nl',  $this->errors);
            $this->sendErrorMail('George', 'georgios@mediamouse.nl',  $this->errors);

        }

        return self::SUCCESS;
    }

    private function mark($status, $text) {
        $this->errors[] = $status->value . ' ' . $text . '\n';
    }

    private function getChecks(): Collection|array
    {
        return SystemHealth::query()->get();
    }

    public function sendErrorMail(string $name, string $email, $payload)
    {
        Mail::send([
//            'html' => "Hey {$name} " . ' There is an error with ' . $class_name . ' on ' . env('APP_NAME'),
            'raw'  => implode("\n", $payload),
        ],[], function (\Illuminate\Mail\Message $message) use ($name, $email) {

            $message
                ->to($email)
//                ->setBody(new TextPart("Hey {$name} " . ' There is an error with ' . $class_name . ' on ' . env('APP_NAME')), 'text/html')
                ->subject('System health error list of' . env('APP_NAME'))
//                ->addPart(new TextPart("Hey {$name} " . ' There is an error with ' . $class_name . ' on ' . env('APP_NAME')), 'text/plain')
//            ->send()
            ;
        });

    }

}
