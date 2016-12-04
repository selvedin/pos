<?php

namespace app\controllers;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class SettingsController extends AdminController
{
      public function actionIndex()
    {
	return $this->render("index");
    }
 
}
