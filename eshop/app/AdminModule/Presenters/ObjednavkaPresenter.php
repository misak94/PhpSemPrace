<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\ObjednavkaEditForm\ObjednavkaEditForm;
use App\AdminModule\Components\ObjednavkaEditForm\ObjednavkaEditFormFactory;
use App\Model\Facades\ObjednavkaFacade;
use App\Bootstrap;
use App\FrontModule\Components\ProductCartForm\ProductCartForm;
use App\FrontModule\Components\ProductCartForm\ProductCartFormFactory;
use App\Model\Entities\Comments;
use App\Model\Entities\LikedBy;
use App\Model\Facades\CommentsFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\LikedByFacade;
use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\UsersFacade;
use mysql_xdevapi\Exception;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\Utils\Paginator;

class ObjednavkaPresenter extends BasePresenter
{
    /**@var ObjednavkaFacade $objednavkaFacade */
    private $objednavkaFacade;
    /** @var ObjednavkaEditFormFactory $objednavkaEditFormFactory */
    private $objednavkaEditFormFactory;

    public $objednavkaId;

    public function renderList():void {
        $this->template->objednavka = $this->objednavkaFacade->findObjednavkas(['order'=>'created DESC']);

    }

    public function renderDetail(int $objednavkaId) {

        $this->template->objednavka = $this->objednavkaFacade->findObjednavka($objednavkaId);
    }

    /**
     * Formulář na editaci objednávek
     * @return ObjednavkaEditForm
     */
    public function createComponentObjednavkaEditForm(string $objednavkaId)/*:ObjednavkaEditForm*/ {
        /*$form = $this->objednavkaEditFormFactory->create();
        $form->onCancel[]=function(){
            $this->redirect('list');
        };
        $form->onFinished[]=function($message=null){
            if (!empty($message)){
                $this->flashMessage($message);
            }
            $this->redirect('list');
        };
        $form->onFailed[]=function($message=null){
            if (!empty($message)){
                $this->flashMessage($message,'error');
            }
            $this->redirect('list');
        };
        return $form;*/
        return new form();
    }

    public function handleOdeslat($id){

    $objednavka = $this->objednavkaFacade->getObjednavka($id);
    $objednavka->stav = "odesláno";
    $this->objednavkaFacade->saveObjednavka($objednavka);
    $this->flashMessage('Stav objednávky změněn na odesláno', 'success');

}

    public function handleZrusit($id){

        $objednavka = $this->objednavkaFacade->getObjednavka($id);
        $objednavka->stav = "zrušeno";
        $this->objednavkaFacade->saveObjednavka($objednavka);
        $this->flashMessage('Stav objednávky změněn na zrušeno', 'success');

    }

    public function handlePrijato($id){

        $objednavka = $this->objednavkaFacade->getObjednavka($id);
        $objednavka->stav = "přijato";
        $this->objednavkaFacade->saveObjednavka($objednavka);
        $this->flashMessage('Stav objednávky změněn na přijato', 'success');

    }

    //Metoda pro uložení komentáře
    public function commentFormSucceeded(\stdClass $values/*,string $url*/): void
    {
       /* $comment = new Comments();
        $comment->product = $this->productsFacade->getProduct($values->productId);
        $comment->name = $this->user->identity->name;
        $comment->content = $values->content;
        //$comment->created = date("Y.m.d H:i");
        $this->commentsFacade->saveComment($comment);
        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');*/
    }




    public function injectObjednavkaFacade(ObjednavkaFacade $objednavkaFacade){
        $this->objednavkaFacade = $objednavkaFacade;
    }

    public function injectObjednavkaEditFormFactory(ObjednavkaEditFormFactory $objednavkaEditFormFactory){
        $this->objednavkaEditFormFactory=$objednavkaEditFormFactory;
    }


}