DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE IF NOT EXISTS `tbl_user`
(
    `id`             VARCHAR(64)  NOT NULL,
    `account`        VARCHAR(100) NULL COMMENT '账号',
    `mobile`         VARCHAR(15)  NULL COMMENT '手机号',
    `email`          VARCHAR(150) NULL COMMENT '邮箱',
    `password`       CHAR(32)     NULL COMMENT '密码',
    `salt`           CHAR(8)      NULL COMMENT '密码混淆随机数',
    `create_time`    BIGINT       NOT NULL COMMENT '注册时间',
    `wechat_unionid` VARCHAR(150) NULL,
    `wechat_openid`  VARCHAR(150) NULL,
    `qq_id`          VARCHAR(150) NULL,
    `alipay_id`      VARCHAR(150) NULL,
    `weibo_id`       VARCHAR(150) NULL,
    `apple_id`       VARCHAR(150) NULL,
    `state`          TINYINT      NOT NULL DEFAULT 1 COMMENT '状态 1正常2禁止登录3账号异常',
    `del_time`       BIGINT       NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    COMMENT = '用户主表，存储用户账号相关信息';

CREATE INDEX `account_index` ON `tbl_user` (`account` ASC);

CREATE INDEX `mobile_index` ON `tbl_user` (`mobile` ASC);

CREATE UNIQUE INDEX `account_UNIQUE` ON `tbl_user` (`account` ASC);

CREATE UNIQUE INDEX `mobile_UNIQUE` ON `tbl_user` (`mobile` ASC);
