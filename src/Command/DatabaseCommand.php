<?php

namespace Finko\QueueBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DatabaseCommand
 *
 * @package Finko\QueueBundle\Command
 */
class DatabaseCommand extends Command
{
    /**
     * The cache directory path.
     *
     * @var string
     */
//    private $cacheDirectoryPath;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * DatabaseCommand constructor.
     *
     * @param string $cacheDirectoryPath
     * @param KernelInterface $kernel
     */
    public function __construct($cacheDirectoryPath, KernelInterface $kernel)
    {
//        $this->cacheDirectoryPath = $cacheDirectoryPath . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR . 'output';
        $this->kernel = $kernel;

        parent::__construct();
    }

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('queue:database:create:jobs')
             ->setDescription('Create a required entity and repository for storing jobs in database.')
             ->setHelp('Create a required entity and repository for storing jobs in database');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $msg = '<question>Please select the type of database that you want to use. (0):</question> ';
        $question = new ChoiceQuestion($msg, ['orm', 'mongo'], 0);
        $question->setErrorMessage('Mapping %s is invalid valid choices are 0 and 1.');
        $flavor = $helper->ask($input, $output, $question);

        $question = new Question('<question>Please enter the class name for the entity (Job):</question> ', 'Job');
        $entityName = $helper->ask($input, $output, $question);

        $target = ($flavor === 'orm') ? 'table' : 'document';
        $msg = '<question>Please enter the '. $target .' name where you want to store the jobs (jobs):</question> ';
        $question = new Question($msg, 'jobs');
        $tableName = $helper->ask($input, $output, $question);

	    $bundleName = 'App';

        $outputPath = $this->getOutputPath();
        $types = [$entityName => 'Entity.txt', $entityName.'Repository' => 'Repository.txt'];

        foreach ($types as $key => $type) {

            if(stripos($type, 'Repository') !== false) {
	            $namespace = $this->getNamespace('App', $flavor, true);
                $content = $this->replacePlaceholders($this->getTemplate($flavor, $type), $namespace, $bundleName, $entityName, $tableName);
                file_put_contents($outputPath . '/Repository/' . $key . '.php', $content);
            } else {
	            $namespace = $this->getNamespace('App', $flavor);
                $content = $this->replacePlaceholders($this->getTemplate($flavor, $type), $namespace, $bundleName, $entityName, $tableName);
                file_put_contents($outputPath . '/Entity/'  . $key . '.php', $content);
            }
        }


        $output->writeln('Entity and Repository are generated');
        $output->writeln('Please update <info>app/config/packages/queue.yaml</info> config file');
        $output->writeln('the <info>finko_queue.connections.database.repository</info> key with generated fullqualified repository classname');
    }

    /**
     * @param string $flavor
     * @param string $type
     *
     * @return string
     */
    protected function getTemplate($flavor, $type)
    {
        $ds = DIRECTORY_SEPARATOR;
        $templatePath = __DIR__ . $ds . 'Stubs' . $ds . ucfirst($flavor) . $ds . $type;
        $template = file_get_contents($templatePath);

        return $template;
    }

    /**
     * @param $template
     * @param $ns
     * @param $entity
     * @param $bundleName
     * @param $table
     *
     * @return string
     */
    protected function replacePlaceholders($template, $ns, $bundleName, $entity, $table)
    {
        return str_replace(['{{namespace}}', '{{className}}', '{{tableName}}', '{{bundleName}}'], [$ns, $entity, $table, $bundleName], $template);
    }

    /**
     * @throws IOException
     *
     * @return string
     */
    protected function getOutputPath()
    {
        $outputPath = $this->kernel->getProjectDir()."/src";

        if (!is_writable($outputPath)) {

            throw new IOException(sprintf('The directory "%s" is not writable.', $outputPath), 0, null, $outputPath);
        }

        return realpath($outputPath) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param object $bundle
     * @param string $flavor
     * @param string $repository
     *
     * @return string
     */
    private function getNamespace($src, $flavor, $repository = false)
    {

	    if(!$repository) {

	    	$case = ($flavor === 'orm') ? 'Entity' : 'Document';
	    } else {
	    	$case = 'Repository';
	    }

        return $src . '\\' . $case;
    }
}
