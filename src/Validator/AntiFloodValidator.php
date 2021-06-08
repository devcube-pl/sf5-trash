<?php

namespace App\Validator;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntiFloodValidator extends ConstraintValidator
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * AntiFloodValidator constructor.
     * @param  CommentRepository  $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param  Comment  $value
     * @param  AntiFlood|Constraint  $constraint
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function validate($value, Constraint $constraint)
    {
        /**
         * @var $comments Comment[]
         */
        $comments = $this->commentRepository->getLastCommentForPost(
            $value->getPost()->getId(),
            $constraint->limit
        );

        if (!empty($comments)) {
            $onlyMe = true;

            foreach ($comments as $comment) {
                if ($comment->getAuthor()->getId() != $value->getAuthor()->getId()) {
                    $onlyMe = false;
                    break;
                }
            }

            if ($onlyMe) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
