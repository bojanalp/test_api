<?php

namespace AppBundle\Command;

use AppBundle\Entity\CityEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ClearPopulationCommand extends ContainerAwareCommand {

  protected function configure() {
    $this->setName('population:clear')
      ->setDescription('Dumps the existing data to JSON file to /tmp/ folder and deletes all city records from the database')
      ->addArgument('directory', InputArgument::OPTIONAL, 'Define target directory for dump');
  }

  public function execute(InputInterface $input, OutputInterface $output) {
    $em = $this
      ->getContainer()
      ->get('doctrine')
      ->getManager();
    $dbData = $em
      ->getRepository(CityEntity::class)
      ->findAll();

    $cities = [];
    foreach ($dbData as $city) {
      $cities[] = $city->expose();
    }

    $dirname = $input->getArgument("directory") ?: "/tmp/";

    // if data dump is successful, then truncate table
    if ($this->dumpData($dirname, ["cities" => $cities], $output)) {
      $this->truncateTable($em, $output);
    }
  }

  private function dumpData($dirname, $data, $output) {
    $filesystem = new Filesystem();
    try {
      if (!$filesystem->exists($dirname)) {
        $filesystem->mkdir($dirname, 0777);
        $output->writeln("new directory created");
      }
      $filename = $dirname."/dump_".time();
      $filesystem->dumpFile($filename, json_encode($data));
      $output->writeln("Data dumped at $filename");
      return true;
    } catch (IOExceptionInterface $e) {
      $output->writeln("An error occurred while creating your file/directory: ".$e->getMessage());
    }
  }

  private function truncateTable($em, $output) {
    $cmd = $em->getClassMetadata(CityEntity::class);
    $connection = $em->getConnection();
    $connection->beginTransaction();
    try {
      $connection->query("TRUNCATE TABLE cities");
      $em->flush();
      $output->writeln("Table truncated!");
    } catch (\Exception $e) {
      $output->writeln("An error occurred while truncating table: ".$e->getMessage() );
    }
  }
  
}