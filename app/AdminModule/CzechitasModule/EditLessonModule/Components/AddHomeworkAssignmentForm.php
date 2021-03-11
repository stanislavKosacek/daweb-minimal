<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Components;



use App\AdminModule\SettingsModule\Components\SetTargetForm;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\Page\Page;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Target\Target;
use App\Model\Router\Target\TargetRepository;
use Nette\Application\LinkGenerator;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Nextras\Dbal\Utils\DateTimeImmutable;

interface AddHomeworkAssignmentFormFactory
{

	public function create(Lesson $lesson): AddHomeworkAssignmentForm;
}



class AddHomeworkAssignmentForm
{

	use SmartObject;

	/** @var Lesson */
	private $lesson;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var TargetRepository */
	private $targetRepository;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Lesson $lesson, Orm $orm, BaseFormFactory $baseFormFactory, TargetRepository $targetRepository, LinkGenerator $linkGenerator)
	{
		$this->lesson = $lesson;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->targetRepository = $targetRepository;
		$this->linkGenerator = $linkGenerator;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addGroup();
		$form->addText("name", "Název*")
			 ->setRequired("Zadejte název");

		$form->addText("deadline", "Deadline")
			 ->setHtmlType("datetime-local");

		$form->addSubmitButton("send", "Odeslat");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$homework = new HomeworkAssignment();
		$gitFolder = $this->lesson->getType() . "/" . Strings::webalize($values->name);
		$homework->setGitFolder($gitFolder);
		if ($values->deadline) {
			$homework->setDeadline(new DateTimeImmutable($values->deadline));
		} else {
			$homework->setDeadline(NULL);
		}
		$homework->setLesson($this->lesson);


		$page = new Page();
		$page->setType(Page::TYPE_HOMEWORK);
		$page->setName($values->name);
		$page->setPublished(new DateTimeImmutable());

		$page->setHomeworkAssignment($homework);
		$homework->setPage($page);

		$this->orm->persist($page);
		$this->orm->persist($homework);

		$target = new Target();
		$target->setPresenter("Page");
		$target->setAction("default");
		$target->setParamName("id");
		$target->setParamValue($page->getId());
		$target->setLocale("cs");
		$target->setSlug($this->getTargetUrlByPageName($values->name));
		$target->setTitle(Strings::firstUpper($values->name));


		$link = $this->linkGenerator->link("Page:default", ["locale" => "cs", "id" => $page->getId()]);


		if (isset($link) and $link and strlen($link) < 60) {
			$link = SetTargetForm::strReplaceFirst($form->getPresenter()->getHttpRequest()->getUrl()->baseUrl, "", $link);
			$redirect = new Redirect();
			$redirect->setFrom($link);
			$redirect->setTo($target->getSlug());
			$this->orm->persistAndFlush($redirect);
		}

		$this->orm->persist($target);

		$page->setTarget($target);
		$this->orm->persist($page);
		$this->orm->flush();

		$this->onSuccess($homework);
	}



	private function getTargetUrlByPageName($name)
	{
		$lessonSlug = "";
		if ($this->lesson->getPage() and $this->lesson->getPage()->getTarget() and $this->lesson->getPage()->getTarget()->getSlug()) {
			$lessonSlug = $this->lesson->getPage()->getTarget()->getSlug();
		}

		$url = $lessonSlug != "" ? $lessonSlug . "/" . Strings::webalize($name) : Strings::webalize($name);

		$target = $this->targetRepository->getTargetBySlug($url);

		if (!$target) {
			return $url;
		} else {
			$i = 2;

			while ($target) {
				$url = $lessonSlug != "" ? $lessonSlug . "/" . Strings::webalize($name . "-" . $i) : Strings::webalize($name . "-" . $i);
				$target = $this->targetRepository->getTargetBySlug($url);
				$i++;
			}

			return $url;
		}

	}

}
