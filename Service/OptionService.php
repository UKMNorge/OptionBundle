<?php

namespace UKMNorge\OptionBundle\Service;

use Exception;
use UKMNorge\OptionBundle\Entity\Option;

class OptionService {
	private $doctrine;
	private $repo;
	private $em;

	public function __construct($doctrine) {
		$this->doctrine = $doctrine;
		$this->repo = $doctrine->getRepository("UKMOptionBundle:Option");
		$this->em = $this->doctrine->getManager();
	}

	public function get( $key ) {
		$option = $this->repo->findOneBy(array("name"=> $key));
		if( null == $option ) {
			return null;
			return false;
		}
		return $option->getValue();
	}
	
	public function set( $key, $value ) {
		if( null == $value || false == $value || 'false' == $value ) {
			$this->delete( $key );
		}
		
		$option = $this->repo->findOneBy(array("name" => $key));
		if (!$option) {
			$option = new Option();
			$option->setName($key);
		}
		$option->setValue($value);
		
		$this->em->persist($option);
		$this->em->flush();
		
		return $this;
	}
	
	public function delete( $key ) {
		$option = $this->repo->findOneBy(array("name" => $key));

		if($option) {
			$this->em->remove($option);
			$this->em->flush();
		}
		return $this;
	}
}