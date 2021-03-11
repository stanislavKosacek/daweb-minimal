<?php


namespace App\Model\Helpers\Latte;


use Latte;
use Nette\Utils\Html;

class YoutubeEmbed extends Latte\Macros\MacroSet
{


	public static function install(Latte\Compiler $compiler)
	{
		$set = new static($compiler);

		$set->addMacro('youtubeEmbed', [$set, 'macroYoutubeEmbed']);

		return $set;
	}



	public function macroYoutubeEmbed(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		return $writer->write(
			'echo \App\Model\Helpers\Latte\YoutubeEmbed::renderYoutubeEmbed(%node.word)'
		);
	}



	public static function renderYoutubeEmbed(string $videoId)
	{

		$img = "hqdefault.jpg";
		if (@file_get_contents("https://i.ytimg.com/vi/" . $videoId ."/maxresdefault.jpg")) {
			$img = "maxresdefault.jpg";
		}

		$liteYoutube = Html::el("lite-youtube");
		$liteYoutube->setAttribute("videoid", $videoId);
		$liteYoutube->setAttribute("class", "lazy");
		$liteYoutube->setAttribute("data-bg", "https://i.ytimg.com/vi/". $videoId ."/" . $img);
		$divBtn = Html::el("div");
		$divBtn->setAttribute("class", "lty-playbtn");
		return $liteYoutube->addHtml($divBtn);
	}

}
