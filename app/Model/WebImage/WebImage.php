<?php

namespace App\Model\WebImage;

use Nette\Utils\Image;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use WebChemistry\Images\Bridges\Hydration\Adapters\ImageFieldAdapter;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\Resources\IFileResource;
use WebChemistry\Images\Resources\Providers\ImageProvider;
use WebChemistry\Images\Template\ImageFacade;


/**
 * WebImage
 * @property int $id    {primary}
 * @property string $image
 * @property string|null $name
 * @property string|null $alt
 * @property int|null $renderWidth
 * @property int|null $width
 * @property int|null $height
 * @property string|null $contentType
 * @property int|null $size
 * @property DateTimeImmutable $added
 * @property DateTimeImmutable|null $edited
 */
class WebImage extends Entity
{

	/** @var IImageStorage */
	private $imageStorage;

	/** @var ImageFacade */
	private $imageFacade;



	public function injectImageStorage(IImageStorage $imageStorage, ImageFacade $imageFacade)
	{
		$this->imageStorage = $imageStorage;
		$this->imageFacade = $imageFacade;
	}



	public static function getNamespace()
	{
		return "webimages";
	}



	public function onCreate(): void
	{
		parent::onCreate();
		$this->added = new DateTimeImmutable();
	}



	public function onBeforeUpdate(): void
	{
		parent::onBeforeUpdate();
		$this->edited = new DateTimeImmutable();
	}



	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	public function getImage(): ?IFileResource
	{
		if ($this->image) {
			return $this->imageStorage->createResource($this->image);
		}

		return NULL;
	}



	/**
	 * @param string|null $image
	 */
	public function setImage(?string $image): void
	{
		$this->image = $image;
	}



	/**
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}



	/**
	 * @param string|null $name
	 */
	public function setName(?string $name): void
	{
		$this->name = $name;
	}



	/**
	 * @return string|null
	 */
	public function getAlt(): ?string
	{
		return $this->alt;
	}



	/**
	 * @param string|null $alt
	 */
	public function setAlt(?string $alt): void
	{
		$this->alt = $alt;
	}



	/**
	 * @return int|null
	 */
	public function getRenderWidth(): ?int
	{
		return $this->renderWidth;
	}



	/**
	 * @param int|null $renderWidth
	 */
	public function setRenderWidth(?int $renderWidth): void
	{
		$this->renderWidth = $renderWidth;
	}



	/**
	 * @return int|null
	 */
	public function getRenderHeight(): ?int
	{
		if ($this->renderWidth) {
			$originalWidth = $this->getWidth();
			$resize = $this->renderWidth / $originalWidth;

			return round($this->getHeight() * $resize);
		}

		return NULL;
	}



	/**
	 * @return string|null
	 */
	public function getWidth(): ?string
	{
		return $this->width;
	}



	/**
	 * @param string|null $width
	 */
	public function setWidth(?string $width): void
	{
		$this->width = $width;
	}



	/**
	 * @return string|null
	 */
	public function getHeight(): ?string
	{
		return $this->height;
	}



	/**
	 * @param string|null $height
	 */
	public function setHeight(?string $height): void
	{
		$this->height = $height;
	}



	/**
	 * @return string|null
	 */
	public function getContentType(): ?string
	{
		return $this->contentType;
	}



	/**
	 * @param string|null $contentType
	 */
	public function setContentType(?string $contentType): void
	{
		$this->contentType = $contentType;
	}



	/**
	 * @return int|null
	 */
	public function getSize(): ?int
	{
		return $this->size;
	}



	/**
	 * @param int|null $size
	 */
	public function setSize(?int $size): void
	{
		$this->size = $size;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getEdited(): ?DateTimeImmutable
	{
		return $this->edited;
	}



	public function getResizedImage(int $changeSize = 1): ?string
	{
		if ($this->getImage() and $this->getRenderWidth() and $this->getRenderHeight()) {
			$resized = $this->imageFacade->create($this->getImage(), ["resize" => [$this->getRenderWidth() * $changeSize, $this->getRenderHeight() * $changeSize, "exact"]]);
			if ($resized) {
				return $this->imageStorage->link($resized);
			}
		}

		return NULL;

	}


	public function getOriginalUrl(): ?string
	{
		if ($this->getImage()) {
			return $this->imageStorage->link($this->getImage());
		}

		return NULL;
	}
}
