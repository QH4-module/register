<?php
/**
 * File Name: RegisterBase.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/8 3:15 下午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\register\models;


use qh4module\register\external\ExtRegister;
use qttx\components\db\DbModel;
use qttx\web\ServiceModel;

class RegisterBase extends ServiceModel
{
    /**
     * @var string 接收参数,昵称,非必须
     */
    public $nick_name;

    /**
     * @var string 接收参数,头像,非必须
     */
    public $avatar;

    /**
     * @var string 接收参数,非必须,性别
     * 1 男 2女
     */
    public $gender;

    /**
     * @var ExtRegister
     */
    protected $external;


    /**
     * @inheritDoc
     */
    public function rules()
    {
        return $this->mergeRules([
            [['nick_name'], 'string', 'max' => 20],
            [['avatar'], 'string', 'max' => 200],
            [['gender'], 'in', 'range' => [1, 2]],
        ], $this->external->rules());
    }

    /**
     * @inheritDoc
     */
    public function attributeLangs()
    {
        $this->mergeLanguages([
            'nick_name' => '昵称',
            'avatar' => '头像',
            'gender' => '性别',
        ], $this->external->attributeLangs());
    }


    /**
     * @param $user_id
     * @param $db DbModel
     */
    protected function insertInfo($user_id, $db)
    {
        $cols = [
            'nick_name' => '',
            'avatar' => '',
            'gender' => 0,
            'balance' => 0,
            'scores' => 0,
            'level' => 1,
            'city_id' => 0,
            'description' => ''
        ];
        $cols = array_merge($cols, $this->external->userInfoInitData());
        if ($this->nick_name) {
            $cols['nick_name'] = $this->nick_name;
        }
        if ($this->avatar) {
            $cols['avatar'] = $this->avatar;
        }
        if ($this->gender) {
            $cols['gender'] = $this->gender;
        }
        $cols['user_id'] = $user_id;

        $db->insert($this->external->userInfoTableName())
            ->cols($cols)
            ->query();

        return $cols;
    }
}