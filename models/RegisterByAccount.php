<?php
/**
 * File Name: RegisterByAccount.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/8 2:34 下午
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
use qh4module\register\external\ExtRegister;
use qttx\components\db\DbModel;
use qttx\helper\ArrayHelper;

class RegisterByAccount extends RegisterBase
{
    /**
     * @var string 接收参数,账号
     */
    public $account;

    /**
     * @var string 接收参数,密码
     */
    public $password;

    /**
     * @var string 接收参数,重复密码,非必须
     */
    public $repeat_pwd;

    //  注意查看父类,还有一些接收参数

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return $this->mergeRules([
            [['account', 'password'], 'required'],
            [['account'], 'account', 'mode' => $this->external->accountMode()],
            [['account'], 'unique', ['table' => $this->external->userTableName(), 'field' => 'account', 'message' => '{attribute}已被注册']],
            [['repeat_pwd'], 'compare', ['compareAttribute' => 'password', 'message' => '两次输入密码不一致']],
        ], parent::rules());
    }

    /**
     * @inheritDoc
     */
    public function attributeLangs()
    {
        return $this->mergeLanguages([
            'account' => '账号',
            'password' => '密码',
            'repeat_pwd' => '重复密码',
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
        list($pwd, $salt) = HpPassword::generatePassword($this->password);
        $cols = [
            'id' => $user_id,
            'account' => $this->account,
            'password' => $pwd,
            'salt' => $salt,
            'create_time' => time(),
            'state' => 1,
            'del_time' => 0
        ];
        $db->insert($this->external->userTableName())
            ->cols($cols)
            ->query();

        return $cols;
    }

}