<?php


namespace App\Model\Helpers\Latte;


use App\Model\User\User\User;
use App\Model\WebImage\WebImage;
use Latte;
use Nette\Utils\Html;
use WebChemistry\Images\Resources\Providers\ImageProvider;

class AdminProfilePicture extends Latte\Macros\MacroSet
{


	public static function install(Latte\Compiler $compiler)
	{
		$set = new static($compiler);

		$set->addMacro('adminProfilePicture', [$set, 'macroAdminProfilePicture']);

		return $set;
	}



	public function macroAdminProfilePicture(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		[$user, $size] = explode(" ", $node->args);

		return $writer->write(
			"echo \App\Model\Helpers\Latte\AdminProfilePicture::renderAdminProfilePicture($user $size)"
		);
	}



	public static function renderAdminProfilePicture(User $user, int $size)
	{
		if ($user->getImage()) {
			$picture = Html::el("picture");
			$source = Html::el("source");


			$source->setAttribute("srcset", $user->getResizedImageUrl($size * 2) . " 2x, " . $user->getResizedImageUrl($size) . " 1x");


			$img = Html::el("img");

			$img->setAttribute("src", $user->getResizedImageUrl($size));
			$img->addAttributes(["alt", $user->getName() . " " . $user->getSurname()]);

			$img->addAttributes(["class" => "rounded-circle"]);

			$picture->addHtml($source);
			$picture->addHtml($img);

			return $picture;
		}
	}

}
