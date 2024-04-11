<?php

namespace Mediamouse\Users\Database;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\View\Components\Error;
use Illuminate\Console\View\Components\Info;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Prompts\Output\ConsoleOutput;

class CreateViews
{
    use \Illuminate\Console\Concerns\InteractsWithIO;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    public static function event(CommandFinished $event) {
        if(str_starts_with($event->command, 'migrate')) {
            (new CreateViews())->run();
        }
    }

    private function path(): string {
        return database_path('/views');
    }

    private function files() {
        if(!file_exists($this->path())) return [];

        $dh = opendir($this->path());
        $files = array();

        while($file = readdir($dh)) {
            if(str_ends_with($file, '.sql')) {
                $files[] = $file;
            }
        }

        return $files;
    }

    public function run() {
        $previous_size = 0;
        $files = $this->files();

        while(sizeof($files) != $previous_size) {
            $previous_size = sizeof($files);

            foreach($files as $k => $file) {
                try {
                    DB::unprepared(file_get_contents(database_path('/views/' . $file)));
                    unset($files[$k]);
                } catch (\Throwable $e) { }
            }
        }

        if(sizeof($files) > 0) {
            (new Error($this->output))->render('The following files could not be executed because of errors!');
            foreach($files as $k => $file) {
                $this->comment(($k + 1) . ': ' . $file);
            }
        }
        else {
            (new Info($this->output))->render('All queries executed');
        }
    }

}
