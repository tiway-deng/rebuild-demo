<?php

namespace app\models\activity;

use Yii;

/**
 * This is the model class for table "activity_category".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $sort
 * @property int|null $updated_at
 * @property int|null $created_at
 */
class ActivityCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'updated_at', 'created_at'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'sort' => 'Sort',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
