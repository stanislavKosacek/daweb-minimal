<?php

namespace App\Components\Profile;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\SmartObject;
use WebChemistry\Images\IImageStorage;

interface EditUserFormFactory
{

	function create(User $user): EditUserForm;
}



class EditUserForm
{

	use SmartObject;

	/** @var User */
	private $user;

	/** @var UserRepository */
	private $userRepository;

	/**@var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var IImageStorage */
	private $storage;

	/** @var array */
	public $onSuccess = [];



	public function __construct(User $user, UserRepository $userRepository, Orm $orm, BaseFormFactory $baseFormFactory, IImageStorage $storage)
	{
		$this->user = $user;
		$this->userRepository = $userRepository;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->storage = $storage;
	}



	public function getForm()
	{
		$form = $this->baseFormFactory->create();
		$form->addText("email", "Email")
			 ->setDisabled(TRUE);
		$form->addText('name', 'Jméno')
			 ->setRequired("Zadejte jméno");
		$form->addText('surname', 'Příjmení')
			->setRequired("Zadejte příjmení");
//		$form->addSelect('notify', 'notify', [FALSE => "Ne", TRUE => "Ano"])->setTranslator(NULL);

		$form->addImageUpload("image", "Profilový obrázek");
		$form->addText("github", "GitHub URL");
		$form->addText("netlify", "Netlify URL");
		$form->addSubmit('save', 'Upravit');

		$form->setDefaults([
			"email" => $this->user->getEmail(),
			"name" => $this->user->getName(),
			"surname" => $this->user->getSurname(),
			"github" => $this->user->getGithubHomeworkUrl(),
			"netlify" => $this->user->getBaseNetlifyUrl(),

		]);

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$this->user->setName($values->name);
		$this->user->setSurname($values->surname);
		$this->user->setGithubHomeworkUrl($values->github);
		$this->user->setBaseNetlifyUrl($values->netlify);

		if ($values->image) {
			if ($this->user->getImage()) {
				$this->storage->delete($this->user->getImage());
			}
			$this->user->setImage($this->storage->save($values->image)->getId());
		}
//		$this->user->setEmailNotification($values->notify);
//
		$this->orm->persistAndFlush($this->user);
		$this->onSuccess();
	}
}
