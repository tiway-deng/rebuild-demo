<?php

namespace app\models\v1\services;


use app\domain\AbstractId;
use app\domain\activity\Activity;
use app\domain\activity\valueObject\ActivityAddress;
use app\domain\activity\valueObject\ActivityConnectPhone;
use app\domain\activity\valueObject\ActivityContent;
use app\domain\activity\valueObject\ActivityTime;
use app\domain\activity\valueObject\ActivityTitle;
use app\domain\activityCategory\valueObject\CategoryId;
use App\domain\user\valueObject\UserId;
use app\repository\activity\ActivityCategoryRepository;
use app\repository\activity\ActivityRepository;

class ActivityService
{

    public function create(CategoryId $categoryId, ActivityTitle $title, ActivityContent $content, ActivityConnectPhone $connectPhone, ActivityAddress $activityAddress, ActivityTime $activityTime)
    {
        //数据查询
        $category = ActivityCategoryRepository::findOne($categoryId->id());

        //创建活动
        $userId = UserId::fromString(\Yii::$app->user->id);
        $activity = Activity::create($userId,$categoryId,$title,$content,$connectPhone,$activityAddress,$activityTime,ActivityCategoryRepository::class);
        $activityDO = ActivityRepository::create($activity);
        //查

    }
}