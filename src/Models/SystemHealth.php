<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Factories\GroupFactory;
use Psy\Util\Json;
use Ramsey\Collection\Collection;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon checked_at
 * @property Carbon update_after
 * @property Carbon valid_until
 * @property string health_check
 * @property SystemHealthStatus status
 * @property Json payload
 *
 *
 */
class SystemHealth extends Model
{
    use HasFactory;

    protected $table = 'system_health';

    protected $casts =
        [
            'update_after' => 'datetime',
            'checked_at' => 'datetime',
            'valid_until' => 'datetime',
            'status' => SystemHealthStatus::class,
        ];

    protected $fillable =
        [
            'update_after',
            'checked_at',
            'valid_until',
            'health_check',
            'status',
            'payload',
        ];


    public function check($force = false) {

        if (Carbon::now() >= $this->update_after || $force) {
            $class = $this->health_check;

            $check = new $class($this->payload);
            $result = $check->check($this->payload);

            $this->checkItem($result, $check->nextCheck($this->payload), $check->validUntil($this->payload));
        }
    }

    protected function checkItem($result ,Carbon $updateAfter ,Carbon $validUntil)
    {
        if ($this->status != $result) {
            $this->createSystemHealthStats($result);
            $this->status = $result;
            $this->save();

        }
        $this->checked_at = Carbon::now();
        $this->update_after = $updateAfter;
        $this->valid_until = $validUntil;
        $this->save();

    }

    protected function createSystemHealthStats(SystemHealthStatus $result)
    {
        /** @var SystemHealthStats $lastItemStat */
        $lastItemStat = SystemHealthStats::query()->where('system_health_id', $this->id)->latest()->first();

        if (($lastItemStat?->created_at == $this->checked_at) || is_null($lastItemStat)) {
            $nextStat = new SystemHealthStats();
            $nextStat->system_health_id = $this->id;
            $nextStat->status = $result;
            $nextStat->save();
            return;
        }

        $previousStat = new SystemHealthStats();
        $previousStat->system_health_id = $this->id;
        $previousStat->status = $this->status;
        $previousStat->save();

        $nextStat = new SystemHealthStats();
        $nextStat->system_health_id = $this->id;
        $nextStat->status = $result;
        $nextStat->save();
    }

    public static function addCheck(string $health_check, mixed $payload = null) {
        $newSystem = new SystemHealth();
        $newSystem->health_check = $health_check;
        $newSystem->payload = $payload;
        $newSystem->update_after = Carbon::now();


        $newSystem->save();

    }

}
