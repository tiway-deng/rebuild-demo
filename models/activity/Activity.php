<?php

namespace app\models\activity;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $content
 * @property string|null $province
 * @property string|null $city
 * @property string|null $address_detail
 * @property int|null $user_id
 * @property string|null $connect_phone
 * @property int|null $from_time
 * @property int|null $to_time
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'user_id', 'from_time', 'to_time'], 'integer'],
            [['content'], 'string'],
            [['title', 'address_detail'], 'string', 'max' => 255],
            [['province', 'city'], 'string', 'max' => 30],
            [['connect_phone'], 'string', 'max' => 13],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'province' => 'Province',
            'city' => 'City',
            'address_detail' => 'Address Detail',
            'user_id' => 'User ID',
            'connect_phone' => 'Connect Phone',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
        ];
    }
}
