<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cities")
 */
class CityEntity {
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer", length=100)
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $name;

  /**
   * @ORM\Column(type="string", length=2)
   */
  private $country;

  /**
   * @ORM\Column(type="integer")
   */
  private $population;

  /**
   * @ORM\Column(type="decimal", precision=9, scale=6, nullable=true)
   */
  private $lng;

  /**
   * @ORM\Column(type="decimal", precision=8, scale=6, nullable=true)
   */
  private $lat;

  /**
   * Get the value of id
   */ 
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get the value of name
   */ 
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */ 
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of country
   */ 
  public function getCountry()
  {
    return $this->country;
  }

  /**
   * Set the value of country
   *
   * @return  self
   */ 
  public function setCountry($country)
  {
    $this->country = $country;

    return $this;
  }

  /**
   * Get the value of population
   */ 
  public function getPopulation()
  {
    return $this->population;
  }

  /**
   * Set the value of population
   *
   * @return  self
   */ 
  public function setPopulation($population)
  {
    $this->population = $population;

    return $this;
  }

  /**
   * Get the value of lng
   */ 
  public function getLng()
  {
    return $this->lng;
  }

  /**
   * Set the value of lng
   *
   * @return  self
   */ 
  public function setLng($lng)
  {
    $this->lng = $lng;

    return $this;
  }

  /**
   * Get the value of lat
   */ 
  public function getLat()
  {
    return $this->lat;
  }

  /**
   * Set the value of lat
   *
   * @return  self
   */ 
  public function setLat($lat)
  {
    $this->lat = $lat;

    return $this;
  }

  public function expose() {
    return get_object_vars($this);
  }
}