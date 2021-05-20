QH4框架扩展模块-注册模块

该模块支持账号密码注册,手机号验证码注册

### 依赖
* 该模块依赖于登录模块,主要是为了保持密码格式的一致性,所以调用了登录模块的方法


* 如果使用手机号注册,则依赖短信模块,并且需要重写 `ExtRegister::checkSmsCode()` 方法 

### api 列表
```php
actionRegisterByAccount()
```
通过账号密码注册

```php
actionRegisterByMobile()
```
通过手机号验证码注册