<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Coins extends Controller_Template {

    public $template = 'layouts/admin';
    private $info;

    public function __construct($request, $response) {
        $this->info = new AdminInfo();
        $this->translator = FrontHelper::getTranslation();
        View::set_global('translator', $this->translator);
        parent::__construct($request, $response);
    }

    public function action_index() {
        $view = View::factory('admin/coins');
        $this->template->set('page_name', 'Gifts');
        $view->coins = ORM::factory('costs')->find_all()->as_array();
        $this->template->information = $this->info->getInfoMessage();
        $this->template->set('content', $view);
    }

    public function action_createnew() {
        $post = $this->request->post();
        if ($_POST) {
            $post = Safely::safelyGet($_POST);
            ORM::factory('costs')->createNew($post);
        }
        $information = $this->translator['text28'];
        $this->info->setInfoRedirect($information, 'coins');
        
    }

    public function action_editall() {
        $post = $this->request->post();
        if ($_POST) {
            $post = Safely::safelyGet($_POST);
            ORM::factory('costs')->editAll($post);
        }
        $information = $this->translator['text29'];
        $this->info->setInfoRedirect($information, 'coins');
    }

    public function action_delete() {
        $id = $this->request->param('id', '');
        ORM::factory('gifts')->where('id', '=', $id)->find()->delete();
        $information = $this->translator['text22'];
        $this->info->setInfoRedirect($information, 'gifts');
    }
       
    public function action_add() {
        $view = View::factory('admin/coins/add');
        if($_POST) {
            $post = Safely::safelyGet($_POST);
            ORM::factory('coins')->addCoinsByAdmin($post);
            $information = $this->translator['text30'];
            $this->info->setInfoRedirect($information, 'coins','add');            
        }
        $this->template->set('page_name', 'Add coins');
        $view->actions = ORM::factory('actions')->find_all()->as_array();
        $this->template->information = $this->info->getInfoMessage();
        $this->template->set('content', $view);
    }    
}

