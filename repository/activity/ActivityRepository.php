<?php


namespace app\repository\activity;


use app\domain\activity\specification\ActivitySpecificationInterface;
use app\models\activity\Activity;
use app\domain\activity\Activity as ActivityVo;

class ActivityRepository extends Activity implements ActivitySpecificationInterface
{
    public static function create(ActivityVo $activityDO)
    {
        $activity = new self();
        $activity->title = $activityDO->getTitle();
        $activity->content = $activityDO->getContent();
        $activity->province = $activityDO->getAddress()->getProvince();
        $activity->city = $activityDO->getAddress()->getCity();
        $activity->address_detail = $activityDO->getAddress()->getAddressDetail();
        $activity->user_id = $activityDO->getUserId();
        $activity->connect_phone = $activityDO->getConnectPhone();
        $activity->from_time = $activityDO->getActivityTime()->getFromTime();
        $activity->to_time = $activityDO->getActivityTime()->getToTime();

        $activity->save();

        return $activity;
    }
}