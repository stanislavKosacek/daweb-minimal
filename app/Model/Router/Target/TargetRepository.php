<?php

namespace App\Model\Router\Target;

use App\Model\Language\LanguageRepository;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Mapper\IMapper;
use Nextras\Orm\Repository\IDependencyProvider;
use Nextras\Orm\Repository\Repository;
use function Symfony\Component\String\b;


/**
 * @method Target|NULL getById($id)
 */
class TargetRepository extends Repository
{


	/** @var LanguageRepository */
	private $languageRepository;



	public function __construct(IMapper $mapper, IDependencyProvider $dependencyProvider = NULL, LanguageRepository $languageRepository)
{
	parent::__construct($mapper, $dependencyProvider);
	$this->languageRepository = $languageRepository;
}



	static function getEntityClassNames(): array
	{
		return [Target::class];

	}



	public function getTargetById($id): ?Target
	{
		return $this->getById($id);
	}



	/**
	 * @return Target[]|ICollection
	 */
	public function findTargets(): ICollection
	{
		return $this->findAll()->orderBy(["presenter" => "ASC", "action" => "ASC", "locale" => "ASC"]);
	}



	/**
	 * @param string $slug
	 * @return Target|null
	 */
	public function getTargetBySlug(string $slug): ?Target
	{
		return $this->getBy(["slug" => $slug]);
	}



	/**
	 * @param array $params
	 * @return Target|null
	 */
	public function getTargetByParams(array $params): ?Target
	{
		$addParam = NULL;
		foreach ($params as $key => $value) {
			if ($key != "presenter" and $key != "action" and $key != "locale") {
				$addParam = $key;
				break;
			}
		}

		if (!isset($params["locale"])) {
			if ($defaultLocale = $this->languageRepository->getDefaultLocale()) {
				$params["locale"] = $defaultLocale->getCode();
			} else {
				$params["locale"] = "cs";
			}
		}

		if ($addParam) {
			return $this->getBy(["presenter" => $params["presenter"], "action" => $params["action"], "locale" => $params["locale"], "paramName" => $addParam,
				"paramValue" => $params[$addParam]]);
		} else {
			return $this->getBy(["presenter" => $params["presenter"], "action" => $params["action"], "locale" => $params["locale"]]);
		}

	}

}
