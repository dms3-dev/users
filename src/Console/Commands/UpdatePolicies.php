<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
use Mediamouse\Users\Policies\PolicyAbstract;

class UpdatePolicies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mediamouse-users:policies-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Policies';

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        /** @var Policy $policy */
        foreach($this->getPolicies() as $policy) {
            if(!class_exists($policy->policy)) {
//                $policy->delete();
            }
            else {
                $policy_class = $policy->policy;

                /** @var PolicyAbstract $policyObject */
                $policyObject = new $policy_class();

                $policy->name = $policyObject->name();
                $policy->save();
            }

            /** @var Group $group */
            foreach($this->getGroups() as $group) {
                $group->createPolicies();
            }
        }


        return self::SUCCESS;
    }

    private function getPolicies(): Collection|array
    {
        return Policy::query()->get();
    }

    private function getGroups(): Collection|array {
        return Group::query()->get();
    }
}
