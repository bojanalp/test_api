<?php

namespace AppBundle\Command;

use AppBundle\Entity\CityEntity;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Unirest\Request as UR;

class GetPopulationCommand extends ContainerAwareCommand {

  protected function configure() {
    $this->setName('population:get')
      ->setDescription('Loads top 100 populated cities from the OpenDataSoft into database')
      ->addArgument('total_cities', InputArgument::OPTIONAL, 'How many cities would you like to load?');
  }

  public function execute(InputInterface $input, OutputInterface $output) {
    $em = $this
      ->getContainer()
      ->get('doctrine')
      ->getManager();
    $repo = $em
      ->getRepository(CityEntity::class);

    $rows = $input->getArgument('total_cities') ?: 100;
    $data = UR::get('https://public.opendatasoft.com/api/records/1.0/search/?dataset=worldcitiespop&sort=population&facet=country&rows='.$rows);
    $raw_body = json_decode($data->raw_body);
    
    foreach ($raw_body->records as $record) {
      // if city exists in database, then update population
      // otherwise insert all data
      if ($id = $this->check($repo, $record->fields->city,$record->fields->country)) {
        $city = $this->updateCity($repo, $id, $record);
      } else {
        $city = $this->insertCity($record);
      }
      $em->persist($city);
    }
    $em->flush();
    $output->writeln("cities imported");
  }


  // check if there is an entry for city
  private function check($repo, $name, $country) {
    $city = $repo
      ->findOneBy(["name" => $name, "country" => $country]);
    return ( isset($city) ? $city->getId() : NULL );
  }

  private function insertCity($record) {
    $city = new CityEntity();
    $city->setName($record->fields->city);
    $city->setCountry($record->fields->country);
    $city->setPopulation($record->fields->population);
    $city->setLng($record->fields->longitude);
    $city->setLat($record->fields->latitude);
    return $city;
  }

  private function updateCity($repo, $id, $record) {
    $city = $repo->find($id);
    $city->setPopulation($record->fields->population);
    return $city;
  }
  
}