<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
        // Workaround for the instance that there is no policy record present.
        $count = DB::affectingStatement("
            INSERT INTO policies
            SELECT group_has_policies.policy, NOW(), NOW(), group_has_policies.policy
            FROM group_has_policies
            LEFT JOIN policies p on group_has_policies.policy = p.policy
            WHERE p.policy IS NULL
            GROUP BY group_has_policies.policy
        ");

        if($count > 0 ) {
            $this->error("$count policies where added to the database");
        }
        else {
            $this->info("All policies were present");
        }

        /** @var Policy $policy */
        foreach($this->getPolicies() as $policy) {
            if(!class_exists($policy->policy) || !is_subclass_of(PolicyAbstract::class, $policy->policy)) {
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
