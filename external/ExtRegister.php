<?php
/**
 * File Name: ExtRegister.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/8 2:24 下午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\register\external;


use qttx\components\db\DbModel;
use qttx\exceptions\Exception;
use qttx\helper\StringHelper;
use qttx\validators\AccountValidator;
use qttx\web\External;

class ExtRegister extends External
{
    /**
     * 检查短信验证码
     * @param string $mobile 手机号
     * @param string|int $code 验证码
     * @return bool 需要返回bool值
     */
    public function checkSmsCode($mobile,$code)
    {
//        if (验证码正确) {
//            return true;
//        }else{
//            return false;
//        }

        throw new Exception('没有检查验证码');
    }


    /**
     * 用户注册完成后执行的方法
     * 这里可以根据实际业务逻辑初始化一些用户的其他信息
     * @param array $user 新用户的账号信息,对应 user 表的数据
     * @param array $user_info 新注册用户的基本信息,对应 user_info 表的数组
     * @param DbModel $db 该函数会被包裹在一个事务中,函数中执行的sql务必使用该句柄
     */
    public function afterRegister($user,$user_info,$db)
    {

    }


    /**
     * 账号模式
     * @return string
     */
    public function accountMode()
    {
        return AccountValidator::TYPE_MODE2;
    }

    /**
     * 生成用户的id,默认使用雪花算法随机生成
     * 有些特定的业务场景,用户id需要按照一定规则生成,所以预留了该函数
     * 注意数据表id字段最大64位
     * @return string
     */
    public function generateUserId()
    {
        return \QTTX::$app->snowflake->id();
    }

    /**
     * userinfo 表初始化数据
     * 这里返回一个数组,键是字段名,值是字段的默认值
     * 如果你增加了userinfo表的字段,这里返回的也有效
     */
    public function userInfoInitData()
    {
        return [
            'nick_name'=>'用户'.StringHelper::random(4),
            'gender'=>0,
        ];
    }

    /**
     * 返回 `user` 表名称
     * @return string
     */
    public function userTableName()
    {
        return '{{%user}}';
    }

    /**
     * 返回 `user_info` 表的名称
     * @return string
     */
    public function userInfoTableName()
    {
        return '{{%user_info}}';
    }
}