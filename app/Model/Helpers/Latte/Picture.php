<?php


namespace App\Model\Helpers\Latte;


use App\Model\WebImage\WebImage;
use Latte;
use Nette\Utils\Html;
use WebChemistry\Images\Resources\Providers\ImageProvider;

class Picture extends Latte\Macros\MacroSet
{


	public static function install(Latte\Compiler $compiler)
	{
		$set = new static($compiler);

		$set->addMacro('picture', [$set, 'macroPicture']);

		return $set;
	}



	public function macroPicture(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		return $writer->write(
			'echo \App\Model\Helpers\Latte\Picture::renderPicture(%node.word)'
		);
	}



	public static function renderPicture(WebImage $webImage)
	{
		if ($webImage->getImage()) {
			$picture = Html::el("picture");
			$source = Html::el("source");

			if ($webImage->getResizedImage()) {
				$string = $webImage->getResizedImage(2) . " 2x, " . $webImage->getResizedImage() . " 1x";
				$source->addAttributes(["srcset" => $string]);
			} else {
				$source->setAttribute("srcset", $webImage->getOriginalUrl());
			}

			$img = Html::el("img");
			if ($webImage->getResizedImage()) {
				$img->setAttribute("src", $webImage->getResizedImage());
			} else {
				$img->setAttribute("src", $webImage->getOriginalUrl());
			}
			$img->addAttributes(["class" => "img-fluid", "alt" => $webImage->getAlt()]);

			$picture->addHtml($source);
			$picture->addHtml($img);

			return $picture;
		}
	}

}
