<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vod_detail".
 *
 * @property int $id
 * @property string $url 采集的url
 * @property string $url_id 采集的url经过加密生成的唯一字符串
 * @property string $vod_title 视频名称
 * @property string|null $vod_sub_title 视频别名
 * @property string|null $vod_blurb 简介
 * @property string|null $vod_content 详细介绍
 * @property int|null $vod_status 状态
 * @property string|null $vod_type 视频分类
 * @property string|null $vod_class 扩展分类
 * @property string|null $vod_tag
 * @property string|null $vod_pic_url 图片url
 * @property string|null $vod_pic_path 图片下载保存路径
 * @property string|null $vod_pic_thumb
 * @property string|null $vod_actor 演员
 * @property string|null $vod_director 导演
 * @property string|null $vod_writer 编剧
 * @property string|null $vod_remarks 影片版本
 * @property int|null $vod_pubdate
 * @property string|null $vod_area 地区
 * @property string|null $vod_lang 语言
 * @property string|null $vod_year 年代
 * @property int|null $vod_hits 总浏览数
 * @property int|null $vod_hits_day 一天浏览数
 * @property int|null $vod_hits_week 一周浏览数
 * @property int|null $vod_hits_month 一月浏览数
 * @property int|null $vod_up 顶数
 * @property int|null $vod_down 踩数
 * @property float|null $vod_score 总评分
 * @property int|null $vod_score_all
 * @property int|null $vod_score_num
 * @property int|null $vod_create_time 创建时间
 * @property int|null $vod_update_time 更新时间
 * @property int|null $vod_lately_hit_time 最后浏览时间
 * @property bgint|null $vod_lately_ip 最后浏览时间
 */
class VodDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vod_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'url_id', 'vod_title'], 'required'],
            [['vod_content'], 'string'],
            [['vod_status', 'vod_pubdate', 'vod_hits', 'vod_hits_day', 'vod_hits_week', 'vod_hits_month', 'vod_up', 'vod_down', 'vod_score_all', 'vod_score_num', 'vod_create_time', 'vod_update_time', 'vod_lately_hit_time', 'vod_lately_ip'], 'integer'],
            [['vod_score'], 'number'],
            [['url'], 'string', 'max' => 500],
            [['url_id'], 'string', 'max' => 100],
            [['vod_title', 'vod_sub_title', 'vod_blurb', 'vod_type', 'vod_class', 'vod_tag', 'vod_pic_url', 'vod_pic_path', 'vod_pic_thumb', 'vod_actor', 'vod_director', 'vod_writer', 'vod_remarks', 'vod_area', 'vod_lang', 'vod_year'], 'string', 'max' => 255],
            [['url_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'url_id' => 'Url ID',
            'vod_title' => '影片名称',
            'vod_sub_title' => '影片副标题',
            'vod_blurb' => 'Vod Blurb',
            'vod_content' => '影片介绍',
            'vod_status' => '状态',
            'vod_type' => '影片分类',
            'vod_class' => '扩展分类',
            'vod_tag' => '标签',
            'vod_pic_url' => '图片Url链接',
            'vod_pic_path' => '图片本地路径',
            'vod_pic_thumb' => '缩略图本地路径',
            'vod_actor' => '演员',
            'vod_director' => '导演',
            'vod_writer' => '编剧',
            'vod_remarks' => '备注',
            'vod_pubdate' => 'Vod Pubdate',
            'vod_area' => '地区',
            'vod_lang' => '语言',
            'vod_year' => '年代',
            'vod_hits' => '浏览次数',
            'vod_hits_day' => '日浏览次数',
            'vod_hits_week' => '周浏览次数',
            'vod_hits_month' => '月浏览次数',
            'vod_up' => '顶次数',
            'vod_down' => '踩次数',
            'vod_score' => '评分',
            'vod_score_all' => '总评分',
            'vod_score_num' => '评分次数',
            'vod_create_time' => '收录时间',
            'vod_update_time' => '更新时间',
            'vod_lately_hit_time' => '最近浏览时间',
            'vod_lately_ip' => '最近浏览ip',
        ];
    }

    /***
     * @return \yii\db\ActiveQuery
     * 获取视频对应的播放地址
     */
    public function getPlayurls()
    {
        return $this->hasMany(PlayUrl::className(), ['url_id' => 'url_id']);
    }

    public function getCommentary()
    {
        $query = VodDetail::find()->where(['like', 'vod_title', $this->vod_title])
            ->andFilterWhere(['not', ['id' => $this->id]])
            //->andFilterWhere(['like', 'vod_type', '解说'])
            ->all();
        return $query;


    }


    public function fields()
    {
        $fields = parent::fields();
        unset($fields['url']);
        unset($fields['url_id']);
        /***
         * 给model增加commentary属性
         * @param $model
         * @return mixed
         */
//        $fields['commentary'] = function ($model) {
//            $query = $model::find()->where(['like', 'vod_title', $this->vod_title])
//                //->andFilterWhere(['like', 'vod_type', '解说'])
//                ->andFilterWhere(['not', ['id' => $this->id]])
//                ->all();
//            return $query;
//        };
        return $fields;

    }


    /**
     * 重写extraFields 添加关联字段
     * @return array|false
     */

    public function extraFields()
    {
        return ['commentary','playurls'];
    }

    public function afterFind()
    {
        $this->updateHitnum();
        // $this->sendTobaidu();
        parent::afterFind(); // TODO: Change the autogenerated stub

    }


    public function updateHitnum()
    {
        $now = time(); //当前时间
        if (date("d", $this->vod_lately_hit_time) == date("d", $now)) {//同一天
            $this->vod_hits_day += 1;
        } else {
            $this->vod_hits_day = 0;
        }
        if (date("W", $this->vod_lately_hit_time) == date("W", $now)) {//同一周
            $this->vod_hits_week += 1;
        } else {
            $this->vod_hits_week = 0;
        }
        if (date("m", $this->vod_lately_hit_time) == date("m", $now)) {//同一月
            $this->vod_hits_month += 1;
        } else {
            $this->vod_hits_month = 0;
        }
        $this->vod_hits += 1;
        $this->vod_lately_hit_time = $now; //更新点击时间
        $this->save();

    }

    public function sendTobaidu()
    {
        $url = Yii::$app->request->hostInfo . Yii::$app->request->url;
        $urls = [$url];
        $api = 'http://data.zz.baidu.com/urls?site=https://www.shipinbofang.com&token=TEb3H5aalWBP6n8i';
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        //echo $result;
    }

}
