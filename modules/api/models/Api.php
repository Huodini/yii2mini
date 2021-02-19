<?php

namespace app\modules\api\models;

use Yii;
/**
* @OA\Schema(
*      schema="Api",
*     @OA\Property(
*        property="id",
*        description="",
*        type="integer",
*        format="int64",
*    ),
*     @OA\Property(
*        property="book",
*        description="",
*        type="string",
*        maxLength=25,
*    ),
*     @OA\Property(
*        property="author",
*        description="",
*        type="string",
*        maxLength=25,
*    ),
*     @OA\Property(
*        property="author_id",
*        description="",
*        type="integer",
*        format="int64",
*    ),
* )
*/

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $book
 * @property string $author
 * @property int $author_id
 *
 * @property Author $author0
 * @property Author $author1
 */
class Api extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii2tech\ar\softdelete\SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted_at' =>  time(),
                ],
                'restoreAttributeValues' => [
                    'deleted_at' => 0
                ]
//                'replaceRegularDelete' => true
            ],
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' =>  time(),
            ],
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id'], 'integer'],
            [['book', 'author'], 'string', 'max' => 25],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book' => 'Book',
            'author' => 'Author',
            'author_id' => 'Author ID',
        ];
    }


    public static function find()
    {
    $query = parent::find();

    $query->attachBehavior('softDelete', \yii2tech\ar\softdelete\SoftDeleteQueryBehavior::className());

    return $query->notDeleted();
    }

    public function fields()
    {
        $fields = parent::fields();
        $customFields = [
            'created_at' => function ($model) {
                return \Yii::$app->formatter->asDatetime($model->created_at,'php:c');
            },
            'updated_at' => function ($model) {
                return \Yii::$app->formatter->asDatetime($model->updated_at,'php:c');
            },
        ];
        unset($fields['deleted_at']);

        return \yii\helpers\ArrayHelper::merge($fields, $customFields);
    }

    public function extraFields()
    {
        return [
            'creator',
            'updater'
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAuthor0()
    {
    return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAuthor1()
    {
    return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

}
