<?php

namespace GreenCape\CodeGen\Command;

use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCommand extends Command
{
    /**
     * @var string The name of the command (the part after "codegen")
     */
    protected static $defaultName = 'generate';

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $project;

    /**
     * @var string
     */
    private $output;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * Configure the command options and parameters
     */
    protected function configure(): void
    {
        $this->setDescription('Generates the code for a project')
             ->addOption('project', 'p', InputOption::VALUE_REQUIRED, 'The path to the project definition file.', __DIR__ . '/project.json')
             ->addOption('template', 't', InputOption::VALUE_REQUIRED, 'The path to the template to use for code generation.', dirname(__DIR__) . '/templates/joomla35classic')
             ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'The path to the output directory for the generated code.', dirname(__DIR__, 2) . '/generated')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->project  = $input->getOption('project');
        $this->template = $input->getOption('template');
        $this->output   = $input->getOption('output');
        $this->io       = new SymfonyStyle($input, $output);

        $this->salutation();

        if (!is_readable($this->project)) {
            $this->io->error("Unable to read project definition {$this->project}.");

            return 1;
        }

        if (!is_readable($this->template)) {
            $this->io->error("Unable to read template directory {$this->template}.");

            return 1;
        }

        (new Generator())->project(new Project(json_decode(file_get_contents($this->project), true)))
                         ->template($this->template)
                         ->output($this->output)
                         ->generate()
        ;

        return 0;
    }

    /**
     * Send a hello to the output
     */
    private function salutation(): void
    {
        $this->io->title(sprintf("CodeGen - A Universal Code Generator\nby Niels Braczek (C)2004-%s. All rights reserved.", date('Y')));

        $this->io->writeln([
            "Project:   {$this->project}",
            "Template:  {$this->template}",
            "Output:    {$this->output}",
        ], OutputInterface::VERBOSITY_DEBUG);
    }
}
