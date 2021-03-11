<?php


namespace App\Model;


use App\Model\Comment\CommentRepository;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Czechitas\Countdown\CountdownRepository;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Czechitas\ExerciseSolutionFile\ExerciseSolutionFileRepository;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolutionRepository;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Czechitas\LessonFile\LessonFileRepository;
use App\Model\Czechitas\LessonTeamRole\LessonTeamRoleRepository;
use App\Model\Email\Email\EmailRepository;
use App\Model\Language\LanguageRepository;
use App\Model\Page\Page\PageRepository;
use App\Model\Page\PageBlock\PageBlockRepository;
use App\Model\Router\Redirect\RedirectRepository;
use App\Model\Router\Target\TargetRepository;
use App\Model\User\Role\RoleRepository;
use App\Model\User\User\UserRepository;
use App\Model\WebImage\WebImageRepository;
use Nextras\Orm\Model\Model;

/**
 * Model
 *
 * @property-read UserRepository $users
 * @property-read RoleRepository $roles
 * @property-read TargetRepository $targets
 * @property-read LanguageRepository $languages
 * @property-read RedirectRepository $redirects
 * @property-read EmailRepository $emails
 * @property-read PageRepository $pages
 * @property-read PageBlockRepository $pageBlocks
 * @property-read WebImageRepository $webImages
 * @property-read CommentRepository $comments
 * @property-read CodeShareRepository $codeShares
 * @property-read LessonRepository $lessons
 * @property-read LessonFileRepository $lessonFiles
 * @property-read LessonTeamRoleRepository $lessonTeamRoles
 * @property-read HomeworkAssignmentRepository $homeworkAssignments
 * @property-read HomeworkSolutionRepository $homeworkSolutions
 * @property-read CountdownRepository $countdowns
 * @property-read ExerciseRepository $exercises
 * @property-read ExerciseSolutionFileRepository $exerciseSolutionFiles
 */
class Orm  extends Model
{

}
