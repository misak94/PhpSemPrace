<?php

namespace App\FrontModule\Presenters;

use App\Bootstrap;
use App\FrontModule\Components\ProductCartForm\ProductCartFormFactory;
use App\Model\Entities\Comments;
use App\Model\Entities\LikedBy;
use App\Model\Facades\CommentsFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\LikedByFacade;
use App\Model\Facades\UsersFacade;
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
  /** @var UserFacade $userFacade
  */private $userFacade;
  /** @var LikedByFacade $likedByFacade
  */private $likedByFacade;


  /** @persistent */
  public $category;
  public $productId;




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
    $this->productId= $product->productId;
  }

  public function render(string $user){

  }

  /**
   * Akce pro vykreslení přehledu produktů
   */
  public function renderList():void {
    //TODO tady by mělo přibýt filtrování podle kategorie, stránkování atp.
    $this->template->products = $this->productsFacade->findProducts(['order'=>'title']);
  }
 #region commentForm
    //Vytváření formuláře pro komentář
    protected function createComponentCommentForm(): Form
    {
        $form = new Form;
        $form->getElementPrototype()->class('form-group');
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;
        $renderer->wrappers['pair']['container'] = 'div class=form-group';
        $renderer->wrappers['pair']['.error'] = 'has-error';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';
        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
        $form->addTextArea('content', 'Komentář:')->setHtmlAttribute('placeholder','Zadejte komentář')
            ->setRequired()->setAttribute('class','form-control mr-3');

        $form->addSubmit('send', 'Odeslat komentář')->setAttribute('class','btn btn-primary');
        $form->addHidden('productId',$this->productId);

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;
    }
    //Metoda pro uložení komentáře
    public function commentFormSucceeded(\stdClass $values/*,string $url*/): void
    {
        $comment = new Comments();
        $comment->product = $this->productsFacade->getProduct($values->productId);
        $comment->name = $this->user->identity->name;
        $comment->content = $values->content;
        //$comment->created = date("Y.m.d H:i");
        $this->commentsFacade->saveComment($comment);
        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }

    /*public function handleLike(int $id){

        $comment = $this->commentsFacade->findComment($id);
        $comment->likes = $comment->likes +1;
        $this->commentsFacade->saveComment($comment);
        $like = new LikedBy();
        $like->user = $this->userFacade->getUser($this->user->id);
        $like->comments = $comment;
        $this->likedByFacade->liked($like);
        if($this->likedByFacade->findAllByTagAndState()){
            $this->flashMessage('Děkuji za komentář', 'success');
        }
    }*/


 #endregion commentForm


  #region injections
  public function injectProductsFacade(ProductsFacade $productsFacade):void {
    $this->productsFacade=$productsFacade;
  }

  public function injectCommentsFacade(CommentsFacade $commentsFacade):void{
      $this->commentsFacade = $commentsFacade;
  }

    public function injectUserFacade(UsersFacade $userFacade):void{
        $this->userFacade = $userFacade;
    }

    public function injectLikedByFacade(LikedByFacade $likedByFacade):void{
        $this->likedByFacade = $likedByFacade;
    }

  public function injectProductCartFormFactory(ProductCartFormFactory $productCartFormFactory):void {
    $this->productCartFormFactory=$productCartFormFactory;
  }
  #endregion injections
}