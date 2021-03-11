<?php


namespace App\Model\Email\EmailType;


use App\Model\Email\Email\Email;

interface IEmailType
{

	/**
	 * @param array $variables
	 * @return Email|null
	 */
	public function createEmail(?string $from = NULL, ?string $to = NULL, ?string $subject = NULL, ?string $locale = NULL, ?array $variables = NULL): ?Email;



	/**
	 * @return string
	 */
	public static function getEmailType(): string;



	/**
	 * @return string
	 */
	public static function getTranslatedName(): string;
}
