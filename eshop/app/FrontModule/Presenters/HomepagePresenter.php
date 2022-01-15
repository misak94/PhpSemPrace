<?php

namespace App\FrontModule\Presenters;

class HomepagePresenter extends BasePresenter{

    public function renderDefault():void {
        $this->redirect('Product:list');
    }

}
