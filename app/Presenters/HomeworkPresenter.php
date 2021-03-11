<?php

declare(strict_types=1);

namespace App\Presenters;



use App\Components\Profile\ChangePasswordFormFactory;
use App\Components\Profile\EditUserFormFactory;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolutionRepository;
use Nette\Application\BadRequestException;

final class HomeworkPresenter extends SecuredPresenter
{

	/** @var HomeworkSolutionRepository @autowire */
	protected $homeworkSolutionRepository;

	/** @var HomeworkAssignmentRepository @autowire */
	protected $homeworkAssignmentRepository;



	public function renderDefault()
	{
		if (!$this->user->isInRole("admin")) {
			$homeworkSolutions = [];
			foreach ($this->homeworkAssignmentRepository->findHomeworkAssignmentList() as $homeworkAssignment) {
				$homeworkSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($this->user->getIdentity(), $homeworkAssignment);

				if (!$homeworkSolution) {
					$homeworkSolution = new HomeworkSolution();
					$homeworkSolution->setUser($this->user->getIdentity());
					$homeworkSolution->setHomeworkAssignment($homeworkAssignment);
					$homeworkSolution = $this->orm->persistAndFlush($homeworkSolution);
				}
				$homeworkSolutions[] = $homeworkSolution;
			}

			$this->template->homeworkSolutions = $homeworkSolutions;
		} else {
			$this->template->homeworkSolutions = [];
		}

	}
}
