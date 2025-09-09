<?php

namespace Mediamouse\Users\Console\Commands;

use Illuminate\Console\Command;
use Mediamouse\Users\Models\Ip6LocatorListing;

class UpdateIp6Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mm-users:ip6-database-update {filename?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update IP6 database';

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
            $start = $this->decToHexString($row[0]);
            $end = $this->decToHexString($row[1]);
            $country_iso = $row[2];
            $country_name = $row[3];

            if($start == 0 && $end == 0) return false;

            $listing = new Ip6LocatorListing();
            dump([$start, $end]);
            $listing->start = hex2bin($start);
            $listing->end = hex2bin($end);
            $listing->country_iso = $country_iso;
            $listing->country_name = $country_name;

            $listing->save();
            return true;
        }

        return false;
    }

    private function getFileName() {
        return storage_path('ip_locator/IP2LOCATION-LITE-DB1.IPV6.CSV');
    }

    private function clearTable() {
        Ip6LocatorListing::truncate();
    }


    private function decToHexString($number) {
        if ($number === "0") return str_pad(strtoupper("0"), 32, "0", STR_PAD_LEFT);;

        $hexChars = "0123456789abcdef";
        $hex = "";

        while ($number !== "0") {
            // rest bepalen (mod 16)
            $remainder = 0;
            $newNumber = "";
            for ($i = 0; $i < strlen($number); $i++) {
                $digit = (int)$number[$i];
                $tmp = $remainder * 10 + $digit;
                $newDigit = intdiv($tmp, 16);
                $remainder = $tmp % 16;
                if (!($newNumber === "" && $newDigit === 0)) {
                    $newNumber .= $newDigit;
                }
            }
            $hex = $hexChars[$remainder] . $hex;
            $number = $newNumber === "" ? "0" : $newNumber;
        }
        return str_pad(strtoupper($hex), 32, "0", STR_PAD_LEFT);
    }
}
