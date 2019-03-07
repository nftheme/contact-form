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
        if (!is_dir('database')) {
            mkdir('database', 0755);
        }
        if (!is_dir('database/migrations')) {
            mkdir('database/migrations', 0755);
        }
        if (!is_dir('storage/app')) {
            mkdir('storage/app', 0755);
        }
        if (!is_dir('vendor/nf/contact-form/resources/cache')) {
            mkdir('vendor/nf/contact-form/resources/cache', 0755);
        }

        if (!file_exists('database/migrations/2018_01_01_000000_create_contact_table.php')) {
            copy('vendor/nf/contact-form/src/database/migrations/2018_01_01_000000_create_contact_table.php', 'database/migrations/2018_01_01_000000_create_contact_table.php');
        }
    }
}
