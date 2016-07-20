<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_logs}}".
 *
 * @property integer $id
 * @property integer $id_user
 * @property string $event_text
 * @property string $date_create
 * @property string $date_update
 *
 * @property User $user
 */
class UserLogs extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_logs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user'], 'integer'],
            [['event_text'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['event_text'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_user' => Yii::t('app', 'Id User'),
            'event_text' => Yii::t('app', 'Event Text'),
            'date_create' => Yii::t('app', 'Date Create'),
            'date_update' => Yii::t('app', 'Date Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
