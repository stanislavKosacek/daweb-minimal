<?php

namespace App\Model\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ClearCacheCommand extends Command
{

	protected function configure(): void
	{
		$this->setName('clear-cache');
		$this->setDescription('Remove all files form /temp/cache');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dir = __DIR__ . "/../../../temp/cache";

		$it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new \RecursiveIteratorIterator($it,
			\RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);

		$output->writeln("Finished");

		return 0;
	}

}
