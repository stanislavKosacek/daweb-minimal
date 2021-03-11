<?php


namespace App\Model\Email\EmailType\NewRegistration;


use App\Model\Email\Email\Email;
use App\Model\Email\EmailSettings;
use App\Model\Email\EmailType\IEmailType;
use App\Model\Orm;
use App\Model\User\User\User;
use Contributte\Translation\Translator;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;

class NewRegistration implements IEmailType
{

	public const TYPE = "NewRegistration";

	/** @var array */
	private $emailSettings;

	/** @var Translator */
	private $translator;

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var Orm */
	private $orm;



	public function __construct(Translator $translator, ITemplateFactory $templateFactory, LinkGenerator $linkGenerator, Orm $orm, EmailSettings $emailSettings)
	{
		$this->translator = $translator;
		$this->emailSettings = $emailSettings->getEmailSettings();
		$this->templateFactory = $templateFactory;
		$this->linkGenerator = $linkGenerator;
		$this->orm = $orm;
	}



	public function createEmail(?string $from = NULL, ?string $to = NULL, ?string $subject = NULL, ?string $locale = NULL, ?array $variables = NULL): ?Email
	{
		if ($locale) {
			$this->translator->setLocale($locale);
		} else {
			$this->translator->setLocale("cs");
			$locale = "cs";
		}

		$email = new Email();
		$email->setFrom($this->emailSettings["from"]);
		$email->setTo($to);
		if ($subject) {
			$email->setSubject($subject);
		} else {
			$email->setSubject($this->translator->translate("email.newRegistration.subject"));
		}

		$email->setBody($this->getEmailBody($locale, $variables["user"]));
		$email->setLocale($locale);
		$email->setEmailType(self::getEmailType());

		$this->orm->persistAndFlush($email);


		return $email;
	}



	public static function getEmailType(): string
	{
		return self::TYPE;
	}



	private function getEmailBody($locale, User $user): string
	{
		$template = $this->templateFactory->createTemplate();
		$this->translator->setLocale($locale);

		$template->user = $user;

		$template->setTranslator($this->translator);
		$template->setFile(__DIR__ . "/NewRegistration.latte");
		$template->add("lang", $locale);
		$template->getLatte()->addProvider('uiControl', $this->linkGenerator);

		$body = $template->renderToString();

		return $body;

	}



	public static function getTranslatedName(): string
	{
		return "Nová registrace";
	}
}
