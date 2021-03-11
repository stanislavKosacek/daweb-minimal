<?php

namespace App\Model\User\User;

use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;
use App\Model\Language\Language;
use App\Model\Security\InvalidOldPasswordException;
use App\Model\User\Role\Role;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\Resources\IFileResource;
use WebChemistry\Images\Template\ImageFacade;


/**
 * User
 * @property int $id    {primary}
 * @property string|null $name
 * @property string|null $surname
 * @property DateTimeImmutable|null $dateBirth
 * @property string|null $email
 * @property string|null $password
 * @property string|null $username
 * @property string|null $phone
 * @property string|null $note
 * @property string|null $image
 * @property string|null $githubHomeworkUrl
 * @property string|null $baseNetlifyUrl
 * @property DateTimeImmutable|null $lastLogin
 * @property DateTimeImmutable|null $lastRequest
 * @property DateTimeImmutable $added
 * @property DateTimeImmutable|null $edited
 * @property DateTimeImmutable|null $deleted
 * @property string|null $forgottenPasswordHash
 * @property ManyHasMany|Role[] $roles  {m:m Role::$users, isMain=true}
 * @property Language|null $defaultLocale {m:1 Language::$users}
 * @property OneHasMany|CodeShare[] $codeShares    {1:m CodeShare::$user}
 * @property OneHasMany|HomeworkSolution[] $homeworkSolutions    {1:m HomeworkSolution::$user}
 */
class User extends Entity implements IIdentity
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
		return "avatar";
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



	function getRoles(): array
	{
		return $this->roles->getIterator()->fetchPairs("id", "name");
	}



	public function verifyPassword($password): bool
	{
		return (new Passwords())->verify($password, $this->password);
	}



	public function changePassword($oldPassword, $newPassword)
	{
		if (!is_null($this->password) && !$this->verifyPassword($oldPassword)) {
			throw new InvalidOldPasswordException();
		}
		$this->password = (new Passwords())->hash($newPassword);
	}



	public function setPassword($newPassword)
	{
		$this->password = (new Passwords())->hash($newPassword);
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
	public function getSurname(): ?string
	{
		return $this->surname;
	}



	/**
	 * @param string|null $surname
	 */
	public function setSurname(?string $surname): void
	{
		$this->surname = $surname;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getDateBirth(): ?DateTimeImmutable
	{
		return $this->dateBirth;
	}



	/**
	 * @param DateTimeImmutable|null $dateBirth
	 */
	public function setDateBirth(?DateTimeImmutable $dateBirth): void
	{
		$this->dateBirth = $dateBirth;
	}



	/**
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}



	/**
	 * @param string|null $email
	 */
	public function setEmail(?string $email): void
	{
		$this->email = $email;
	}



	/**
	 * @return string|null
	 */
	public function getUsername(): ?string
	{
		return $this->username;
	}



	/**
	 * @param string|null $username
	 */
	public function setUsername(?string $username): void
	{
		$this->username = $username;
	}



	/**
	 * @return string|null
	 */
	public function getPhone(): ?string
	{
		return $this->phone;
	}



	/**
	 * @param string|null $phone
	 */
	public function setPhone(?string $phone): void
	{
		$this->phone = $phone;
	}



	/**
	 * @return string|null
	 */
	public function getNote(): ?string
	{
		return $this->note;
	}



	/**
	 * @param string|null $note
	 */
	public function setNote(?string $note): void
	{
		$this->note = $note;
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
	 * @return DateTimeImmutable|null
	 */
	public function getLastLogin(): ?DateTimeImmutable
	{
		return $this->lastLogin;
	}



	/**
	 * @param DateTimeImmutable|null $lastLogin
	 */
	public function setLastLogin(?DateTimeImmutable $lastLogin): void
	{
		$this->lastLogin = $lastLogin;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getLastRequest(): ?DateTimeImmutable
	{
		return $this->lastRequest;
	}



	/**
	 * @param DateTimeImmutable|null $lastRequest
	 */
	public function setLastRequest(?DateTimeImmutable $lastRequest): void
	{
		$this->lastRequest = $lastRequest;
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



	/**
	 * @param DateTimeImmutable|null $edited
	 */
	public function setEdited(?DateTimeImmutable $edited): void
	{
		$this->edited = $edited;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getDeleted(): ?DateTimeImmutable
	{
		return $this->deleted;
	}



	/**
	 * @param DateTimeImmutable|null $deleted
	 */
	public function setDeleted(?DateTimeImmutable $deleted): void
	{
		$this->deleted = $deleted;
	}



	/**
	 * @return bool
	 */
	public function isDeleted(): bool
	{
		return $this->deleted ? TRUE : FALSE;
	}



	/**
	 * @return string|null
	 */
	public function getForgottenPasswordHash(): ?string
	{
		return $this->forgottenPasswordHash;
	}



	/**
	 * @param string|null $forgottenPasswordHash
	 */
	public function setForgottenPasswordHash(?string $forgottenPasswordHash): void
	{
		$this->forgottenPasswordHash = $forgottenPasswordHash;
	}



	/**
	 * @return Role[]|ManyHasMany
	 */
	public function getRolesEntity()
	{

		return $this->roles;
	}



	/**
	 * @return Language|null
	 */
	public function getDefaultLocale(): ?Language
	{
		return $this->defaultLocale;
	}



	/**
	 * @param Language|null $defaultLocale
	 */
	public function setDefaultLocale(?Language $defaultLocale): void
	{
		$this->defaultLocale = $defaultLocale;
	}



	/**
	 * @return CodeShare[]|OneHasMany
	 */
	public function getCodeShares()
	{
		return $this->codeShares;
	}



	public function getOriginalImgUrl()
	{
		if ($this->getImage()) {
			return $this->imageStorage->link($this->getImage());
		}

		return NULL;
	}



	public function getResizedImageUrl(int $size = 100): ?string
	{
		if ($this->getImage()) {
			$resized = $this->imageFacade->create($this->getImage(), ["resize" => [$size, $size, "exact"]]);
			if ($resized) {
				return $this->imageStorage->link($resized);
			}
		}

		return NULL;

	}



	/**
	 * @return string|null
	 */
	public function getGithubHomeworkUrl(): ?string
	{
		return $this->githubHomeworkUrl;
	}



	/**
	 * @param string|null $githubHomeworkUrl
	 */
	public function setGithubHomeworkUrl(?string $githubHomeworkUrl): void
	{
		$this->githubHomeworkUrl = $githubHomeworkUrl;
	}



	/**
	 * @return string|null
	 */
	public function getBaseNetlifyUrl(): ?string
	{
		return $this->baseNetlifyUrl;
	}



	/**
	 * @param string|null $baseNetlifyUrl
	 */
	public function setBaseNetlifyUrl(?string $baseNetlifyUrl): void
	{
		$this->baseNetlifyUrl = $baseNetlifyUrl;
	}
}
