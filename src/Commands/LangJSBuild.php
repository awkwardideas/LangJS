<?php namespace AwkwardIdeas\LangJS\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use AwkwardIdeas\LangJS\LangJS;

class LangJSBuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'langjs:build {--d=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build language files from php to js';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('d') !=""){
            $destination = $this->option('d');
        }else{
            $destination= $this->ask("Where do you want to save the language file? (For public/js, just type js. Path is created using public as a starting point.)");
        }
        $this->comment("Building Language files to: $destination");
        $this->comment(PHP_EOL . LangJS::BuildLangFiles($destination).PHP_EOL);

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('destination', null, InputOption::VALUE_OPTIONAL, "output destination")
        );
    }
}
