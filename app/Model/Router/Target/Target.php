<?php

namespace App\Model\Router\Target;

use Nextras\Orm\Entity\Entity;


/**
 * User
 * @property int $id    {primary}
 * @property string $presenter
 * @property string|null $action
 * @property string|null $slug
 * @property string|null $paramName
 * @property string|null $paramValue
 * @property string|null $locale
 * @property string|null $title
 * @property string|null $description
 */
class Target extends Entity
{




	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return string
	 */
	public function getPresenter(): string
	{
		return $this->presenter;
	}



	/**
	 * @param string $presenter
	 */
	public function setPresenter(string $presenter): void
	{
		$this->presenter = $presenter;
	}



	/**
	 * @return string|null
	 */
	public function getAction(): ?string
	{
		return $this->action;
	}



	/**
	 * @param string|null $action
	 */
	public function setAction(?string $action): void
	{
		$this->action = $action;
	}



	/**
	 * @return string|null
	 */
	public function getSlug(): ?string
	{
		return $this->slug;
	}



	/**
	 * @param string|null $slug
	 */
	public function setSlug(?string $slug): void
	{
		$this->slug = $slug;
	}



	/**
	 * @return string|null
	 */
	public function getParamName(): ?string
	{
		return $this->paramName;
	}



	/**
	 * @param string|null $paramName
	 */
	public function setParamName(?string $paramName): void
	{
		$this->paramName = $paramName;
	}



	/**
	 * @return string|null
	 */
	public function getParamValue(): ?string
	{
		return $this->paramValue;
	}



	/**
	 * @param string|null $paramValue
	 */
	public function setParamValue(?string $paramValue): void
	{
		$this->paramValue = $paramValue;
	}



	/**
	 * @return string|null
	 */
	public function getLocale(): ?string
	{
		return $this->locale;
	}



	/**
	 * @param string|null $locale
	 */
	public function setLocale(?string $locale): void
	{
		$this->locale = $locale;
	}



	/**
	 * @return string|null
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}



	/**
	 * @param string|null $title
	 */
	public function setTitle(?string $title): void
	{
		$this->title = $title;
	}



	/**
	 * @return string|null
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}



	/**
	 * @param string|null $description
	 */
	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}


}
