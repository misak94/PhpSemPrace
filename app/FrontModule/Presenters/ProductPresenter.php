<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\ProductCartForm\ProductCartFormFactory;
use App\Model\Entities\Comments;
use App\Model\Facades\CommentsFacade;
use App\Model\Facades\ProductsFacade;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

/**
 * Class ProductPresenter
 * @package App\FrontModule\Presenters
 * @property string $category
 */
class ProductPresenter extends BasePresenter{
  /** @var ProductsFacade $productsFacade */
  private $productsFacade;
  /** @var ProductCartFormFactory $productCartFormFactory */
  private $productCartFormFactory;
  /** @var CommentsFacade $commentsFacade */
    private $commentsFacade;

  /** @persistent */
  public $category;

  /**
   * Akce pro zobrazení jednoho produktu
   * @param string $url
   * @throws BadRequestException
   */
  public function renderShow(string $url):void {
    try{
      $product = $this->productsFacade->getProductByUrl($url);
    }catch (\Exception $e){
      throw new BadRequestException('Produkt nebyl nalezen.');
    }

    $this->template->product = $product;
  }

  /**
   * Akce pro vykreslení přehledu produktů
   */
  public function renderList():void {
    //TODO tady by mělo přibýt filtrování podle kategorie, stránkování atp.
    $this->template->products = $this->productsFacade->findProducts(['order'=>'title']);
  }

    #region commentForm
    protected function createComponentCommentForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Jméno:')
            ->setRequired();

        $form->addEmail('email', 'E-mail:');

        $form->addTextArea('content', 'Komentář:')
            ->setRequired();

        $form->addSubmit('send', 'Publikovat komentář');

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;
    }

    public function commentFormSucceeded(\stdClass $values): void
    {
        $postId = 1;
        $comment = new Comments();
        $comment->postId = 1;
        $comment->name = $values->name;
        $comment->email = $values->email;
        $comment->content = $values->content;
        $this->commentsFacade->saveComment($comment);

        /* $this->database->table('comments')->insert([
             'post_id' => $postId,
             'name' => $values->name,
             'email' => $values->email,
             'content' => $values->content,
         ]);*/


        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }
    #endregion commentForm

  #region injections
  public function injectProductsFacade(ProductsFacade $productsFacade):void {
    $this->productsFacade=$productsFacade;
  }

  public function injectCommentsFacade(CommentsFacade $commentsFacade):void{
      $this->commentsFacade = $commentsFacade;
  }

  public function injectProductCartFormFactory(ProductCartFormFactory $productCartFormFactory):void {
    $this->productCartFormFactory=$productCartFormFactory;
  }
  #endregion injections
}