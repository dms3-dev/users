<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $this->withProgressBar($this->getChecks(), function(SystemHealth $check) {

            $check->check($this->option('force'));
        });

        return self::SUCCESS;
    }

    private function getChecks(): Collection|array
    {
        return SystemHealth::query()->get();
    }


}
