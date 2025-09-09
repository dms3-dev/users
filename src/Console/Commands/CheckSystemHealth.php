<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Mail;
use Mediamouse\Mails\Mail\SimpleMail;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
use Mediamouse\Users\Models\SystemHealth;
use Mediamouse\Users\Policies\PolicyAbstract;
use Mediamouse\Users\SystemHealth\HealthCheckAbstract;

class CheckSystemHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mm-users:system-health {--force} {--discover} {--reset}';

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
        if($this->option('discover')) $this->discover();
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
        }

        return self::SUCCESS;
    }

    private function mark($status, $text) {
        $this->errors[] = $status->value . ' ' . $text;
    }

    private function getChecks(): Collection|array
    {
        return SystemHealth::query()->get();
    }

    private function discover() {
        if($this->option('reset')) {
            SystemHealth::all()->each->delete();
        }

        $this->info('Discovering health checks');
        $this->discoverHealthChecks( app_path('SystemHealth'), 'App\\SystemHealth');
    }

    private function discoverHealthChecks(string $directory, string $namespace): void
    {
        if (blank($directory) || blank($namespace)) return;

        $filesystem = app(Filesystem::class);

        if ((!$filesystem->exists($directory)) && (!str($directory)->contains('*'))) return;
        $namespace = str($namespace);
        foreach ($filesystem->allFiles($directory) as $file) {
            $variableNamespace = $namespace->contains('*') ? str_ireplace(
                ['\\' . $namespace->before('*'), $namespace->after('*')],
                ['', ''],
                str_replace([DIRECTORY_SEPARATOR], ['\\'], (string) str($file->getPath())->after(base_path())),
            ) : null;

            if (is_string($variableNamespace)) {
                $variableNamespace = (string) str($variableNamespace)->before('\\');
            }

            $class = (string) $namespace
                ->append('\\', $file->getRelativePathname())
                ->replace('*', $variableNamespace ?? '')
                ->replace([DIRECTORY_SEPARATOR, '.php'], ['\\', '']);


            if(!class_exists($class)) continue;
            if(!is_subclass_of($class, HealthCheckAbstract::class)) continue;

            $class::register();
        }
    }

    public function sendErrorMail(string $name, string $email, $payload)
    {
        Mail::to($email)->send(new SimpleMail('System health error list of ' . config('app.name'), implode("\r\n", $payload)));
    }

}
