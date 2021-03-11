<?php


namespace App\Model\Helpers\Latte;


use App\Model\Helpers\Event\TestEvent;
use App\Model\User\User\User;
use Latte;
use Nette\Utils\Html;

class FrontendProfilePicture extends Latte\Macros\MacroSet
{


	public static function install(Latte\Compiler $compiler)
	{
		$set = new static($compiler);

		$set->addMacro('frontendProfilePicture', [$set, 'macroFrontendProfilePicture']);

		return $set;
	}



	public function macroFrontendProfilePicture(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		[$user, $size, $basePath] = explode(" ", $node->args);

		return $writer->write(
			"echo \App\Model\Helpers\Latte\FrontendProfilePicture::renderFrontendProfilePicture($user $size $basePath)"
		);
	}



	public static function renderFrontendProfilePicture(User $user, int $size, $basePath = "/")
	{
		if ($user->getImage()) {
			$picture = Html::el("picture");
			$source = Html::el("source");


			$source->setAttribute("srcset", $user->getResizedImageUrl($size * 2) . " 2x, " . $user->getResizedImageUrl($size) . " 1x");


			$img = Html::el("img");

			$img->setAttribute("data-src", $user->getResizedImageUrl($size));
			$img->addAttributes(["alt", $user->getName() . " " . $user->getSurname()]);

			$img->addAttributes(["class" => "rounded-circle lazy"]);

			$picture->addHtml($source);
			$picture->addHtml($img);

			return $picture;
		} else {
			$img = Html::el("img");

			$img->setAttribute("data-src", "$basePath/src/front/images/czechitas_logo_small.png");
			$img->addAttributes(["alt", $user->getName() . " " . $user->getSurname(), "width" => $size . "px"]);

			$img->addAttributes(["class" => "rounded-circle lazy"]);

			return $img;
		}
	}

}
