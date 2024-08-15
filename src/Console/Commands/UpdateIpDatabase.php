<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Mediamouse\Users\Models\IpLocatorListing;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
use Mediamouse\Users\Policies\PolicyAbstract;

class UpdateIpDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mm-users:ip-database-update {filename?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update IP database';

    private $fp;

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        if($this->loadFile()) {
            $this->clearTable();

            while($this->loadRecords()) {}

            return self::SUCCESS;
        }
        $this->error('File ' . $this->getFileName() . ' not found');

        return self::FAILURE;
    }

    private function loadFile() : bool {
        if(!file_exists($this->getFileName())) return false;
        $this->fp = fopen($this->getFileName(), 'r');
        return $this->fp !== null;
    }

    private function loadRecords() {
        $row = fgetcsv($this->fp);

        if($row !== false) {
            $start = (int) $row[0];
            $end = (int) $row[1];
            $country_iso = $row[2];
            $country_name = $row[3];

            if($start == 0 && $end == 0) return false;

            $listing = new IpLocatorListing();
            $listing->start = $start;
            $listing->end = $end;
            $listing->country_iso = $country_iso;
            $listing->country_name = $country_name;

            $listing->save();
            return true;
        }

        return false;
    }

    private function getFileName() {
        return storage_path('ip_locator/IP2LOCATION-LITE-DB1.CSV');
    }

    private function clearTable() {
        IpLocatorListing::truncate();
    }
}
