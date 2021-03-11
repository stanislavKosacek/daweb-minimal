<?php


namespace App\AdminModule\CzechitasModule\Modal\Exercise;



use App\AdminModule\CzechitasModule\Components\Exercise\SetExerciseFormFactory;
use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Modal\BaseModal;

interface SetExerciseModalFactory
{

	public function create(?Exercise $exercise = NULL): SetExerciseModal;
}



class SetExerciseModal extends BaseModal
{


	/** @var Exercise|null */
	private $exercise;

	/** @var SetExerciseFormFactory */
	private $factory;



	public function __construct(?Exercise $exercise = NULL, SetExerciseFormFactory $factory)
	{
		$this->exercise = $exercise;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->exercise);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->exercise ? "Cvičení bylo upraveno" : "Cvičení bylo přidáno", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetExerciseModal.latte");
		$this->template->title = $this->exercise ? "Upravit cvičení" : "Přidat cvičení";
		$this->template->render();
	}
}
