<?php

namespace Vicoders\ContactForm\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    protected function configure()
    {
        $this->setName('contact:publish')
            ->setDescription('Publish view file for theme option');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir($this->app->appPath() . '/database')) {
            mkdir($this->app->appPath() . '/database', 0755);
        }
        if (!is_dir($this->app->appPath() . '/database/migrations')) {
            mkdir($this->app->appPath() . '/database/migrations', 0755);
        }
        if (!is_dir($this->app->appPath() . '/storage/app')) {
            mkdir($this->app->appPath() . '/storage/app', 0755);
        }
        if (!is_dir($this->app->appPath() . '/vendor/nf/contact-form/resources/cache')) {
            mkdir($this->app->appPath() . '/vendor/nf/contact-form/resources/cache', 0755);
        }

        if (!file_exists($this->app->appPath() . '/database/migrations/2018_01_01_000000_create_contact_table.php')) {
            copy($this->app->appPath() . '/vendor/nf/contact-form/src/database/migrations/2018_01_01_000000_create_contact_table.php', $this->app->appPath() . '/database/migrations/2018_01_01_000000_create_contact_table.php');
        }
    }
}
