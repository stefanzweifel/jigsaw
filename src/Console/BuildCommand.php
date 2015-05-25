<?php namespace Jigsaw\Jigsaw\Console;

use Illuminate\Filesystem\Filesystem;
use Jigsaw\Jigsaw\Jigsaw;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    private $sourcePath;
    private $buildPath;
    private $jigsaw;

    public function __construct($jigsaw, $sourcePath, $buildPath)
    {
        $this->sourcePath = $sourcePath;
        $this->buildPath = $buildPath;
        $this->jigsaw = $jigsaw;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('build')
            ->setDescription('Build your site.')
            ->addOption('env', null, InputOption::VALUE_REQUIRED, "What environment should we use to build?", 'local');
    }

    protected function fire()
    {
        $config = $this->loadConfig();
        $this->jigsaw->build($this->sourcePath, $this->buildPath, $config);
        $this->info('Site built successfully!');
    }

    private function loadConfig()
    {
        $env = $this->input->getOption('env');

        if ($env !== null && file_exists(getcwd() . "/config.{$env}.php")) {
            $environmentConfig = include getcwd() . "/config.{$env}.php";
        } else {
            $environmentConfig = [];
        }

        return array_merge(include getcwd() . '/config.php', $environmentConfig);
    }
}
