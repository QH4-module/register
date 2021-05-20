<?php
/**
 * File Name: RegisterByMobile.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/8 3:14 下午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\register\models;


use qh4module\login\HpPassword;
use qttx\helper\ArrayHelper;

class RegisterByMobile extends RegisterBase
{
    /**
     * @var string 接收参数,手机号
     */
    public $mobile;

    /**
     * @var string 接收参数,验证码
     */
    public $code;

    /**
     * @var string 接收参数,密码,非必须
     */
    public $password;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return $this->mergeRules([
            [['mobile', 'code'], 'required'],
            [['mobile'], 'mobile'],
            [['mobile'], 'unique', ['table' => $this->external->userTableName(), 'field' => 'mobile', 'message' => '{attribute}已被注册']],
            [['code'], 'match', 'pattern' => '/^[0-9A-Za-z]+$/'],
            [['code'], 'ruleCheckCode']
        ], parent::rules());
    }

    /**
     * 检查验证码
     * @param $value
     * @return bool|string
     */
    public function ruleCheckCode($value)
    {
        if ($this->external->checkSmsCode($this->mobile, $this->code)) {
            return true;
        }else{
            return '{attribute}错误';
        }
    }

    /**
     * @inheritDoc
     */
    public function attributeLangs()
    {
        return $this->mergeLanguages([
            'mobile' => '手机号',
            'code' => '验证码',
            'password' => '密码',
        ], parent::attributeLangs());
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $db = $this->external->getDb();

        $db->beginTrans();

        try {

            $user_id = $this->external->generateUserId();

            // 插入用户表
            $user_cols = $this->insertUser($user_id, $db);

            // 插入基本信息表
            $user_info_cols = $this->insertInfo($user_id, $db);

            // 注册成功后
            $this->external->afterRegister($user_cols, $user_info_cols, $db);


            $db->commitTrans();

            return true;

        } catch (\Exception $exception) {
            $db->rollBackTrans();
            throw $exception;
        }
    }


    protected function insertUser($user_id, $db)
    {
        $cols = [
            'id' => $user_id,
            'mobile' => $this->mobile,
            'create_time' => time(),
            'state' => 1,
            'del_time' => 0
        ];

        if ($this->password) {
            list($pwd, $salt) = HpPassword::generatePassword($this->password);
            $cols['password'] = $pwd;
            $cols['salt'] = $salt;
        }

        $db->insert($this->external->userTableName())
            ->cols($cols)
            ->query();

        return $cols;
    }

}