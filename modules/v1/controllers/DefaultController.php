<?php

namespace app\modules\v1\controllers;

use app\domain\activityCategory\valueObject\CategoryDescription;
use app\domain\activityCategory\valueObject\CategoryName;
use app\domain\activityCategory\valueObject\CategorySort;
use app\helper\ResponseHelper;
use app\modules\v1\services\ActivityCategoryService;
use app\repository\activity\ActivityCategoryRepository;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(Request $request)
    {
        $categoryName = CategoryName::fromString($request->get('name'));
        $categoryDescription = CategoryDescription::fromString($request->get('description'));
        $categorySort = CategorySort::fromString($request->get('sort'));

        (new ActivityCategoryService(new ActivityCategoryRepository()))->create($categoryName,$categoryDescription,$categorySort);

        ResponseHelper::success('success');
    }
}
