<?php

namespace app\modules\v1\controllers;

use app\domain\activityCategory\valueObject\CategoryDescription;
use app\domain\activityCategory\valueObject\CategoryName;
use app\domain\activityCategory\valueObject\CategorySort;
use app\modules\v1\services\ActivityCategoryService;
use app\repository\activity\ActivityCategoryRepository;
use yii\web\Controller;
use yii\web\Request;


class ActivityCategoryController extends Controller
{

    public function actionCreate(Request $request)
    {

        $categoryName = CategoryName::fromString($request->post('name'));
        $categoryDescription = CategoryDescription::fromString($request->post('description'));
        $categorySort = CategorySort::fromString($request->post('sort'));

        $result = (new ActivityCategoryService(new ActivityCategoryRepository()))->create($categoryName,$categoryDescription,$categorySort);

        \Yii::$app->response->data = $result;
    }
}
