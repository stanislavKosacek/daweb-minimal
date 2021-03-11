<?php


namespace App\Model\Helpers\Event;


use App\Presenters\HomepagePresenter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TestSubscriber implements EventSubscriberInterface
{

	public static function getSubscribedEvents(): array
	{
		return [
			HomepagePresenter::class => 'log',
		];
	}



	public function log(TestEvent $event): void
	{
		bdump($event->getA());
	}
}
