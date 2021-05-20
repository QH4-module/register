<?php
/**
 * File Name: TraitRegisterController.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/8 2:23 下午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\register;


use qh4module\register\external\ExtRegister;
use qh4module\register\models\RegisterByAccount;
use qh4module\register\models\RegisterByMobile;

/**
 * Trait TraitRegisterController
 * 账号注册模块
 * @package qh4module\register
 */
trait TraitRegisterController
{
    /**
     * @return ExtRegister
     */
    public function ext_register()
    {
        return new ExtRegister();
    }

    /**
     * 通过账号密码注册
     * @return array
     */
    public function actionRegisterByAccount()
    {
        $model = new RegisterByAccount([
            'external' => $this->ext_register(),
        ]);

        return $this->runModel($model);
    }

    /**
     * 通过手机号验证码注册
     */
    public function actionRegisterByMobile()
    {
        $model = new RegisterByMobile([
            'external' => $this->ext_register(),
        ]);

        return $this->runModel($model);
    }

}