<?php

namespace App\Model\Modal;

interface IModal
{

	public function setCloseDestination($link, $args = []);



	public function setCloseUrl($link);



	/** @return string */
	public function getClasses();

}
