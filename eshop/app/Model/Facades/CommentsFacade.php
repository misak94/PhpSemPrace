<?php

namespace App\Model\Facades;

use App\Model\Repositories\CommentsRepository;
use App\Model\Entities\Comments;
use Nette\Http\FileUpload;
use Nette\Utils\Strings;


/**
 * Class CommentsFacade
 * @package App\Model\Facades
 */
class CommentsFacade{

/** @var CommentsRepository $commentsRepository  */
    private $commentsRepository;

    public function saveComment(Comments $comment):bool{
    return (bool)$this->commentsRepository->persist($comment);

    }
    public function __construct(CommentsRepository $CommentsRepository){
        $this->commentsRepository = $CommentsRepository;
    }

    public function findComment(int $id){
        return $this->commentsRepository->find($id);
    }


}