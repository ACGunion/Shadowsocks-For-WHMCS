# Shadowsocks For WHMCS

This module is available automatically sales & management [Shadowsocks Manyuser](https://github.com/mengskysama/shadowsocks/tree/manyuser) in the WHMCS.

## Quick start

: ) In fact, it is not "Quick" ...

You can read the [Installation documentation](https://www.zntec.cn/archives/whmcs-ss-module.html) for information on this module contents, and more.

## Copyright and license

Copyright 2016 ZNTEC.CN. Code released under [the MIT license](https://github.com/babytomas/Shadowsocks-For-WHMCS/blob/master/LICENSE).

# 关于修改

修改了API中的公共函数,替换了已经被PHP弃用且删除的mysql函数,采用mysqli取代,现在API应当可以在PHP5.5及以上版本使用,甚至支持了PHP7.

原版的公共函数被命名为function_OLD.php了

增加了一个参数,用于区分用户套餐.原版的程序无法区分用户套餐,全部节点共用一张数据表,导致一旦节点地址泄露,拥有任意套餐的用户都可以访问全部节点,这无法区分VIP节点和免费节点,修改版可以通过设置套餐参数来控制数据表了.
一旦你设置了套餐参数,请确保你的数据库中已经有了一个对应的表,创建规则是user+[套餐编号],例如套餐编号为2,则应当创建user2表.

WHMCS模组方面,修改了认证方式,现在不仅可以通过UA限制访问,也可以使用HTTP基础认证限制,Apache用户会方便很多,只需要将API目录设置HTTP基础认证,然后在WHMCS后台添加服务器时设置服务器用户和密码为HTTP基础验证的用户和密码.

HTTP前台方面,添加了两个图表,本以为可以高大上一些,结果我JavaScript和前端大抵上是还给老师了,于是自适应有问题,而且有一些冗长且无用的代码,手机或分辨率低的用户访问前台时,图标可能会超出屏幕,如果不喜欢,可以用原版代码中的clientarea.tpl替换掉

添加了一个新文件Base64Url.php用于生成URL Safe的 base64代码,但是没有用到,也不知道以后会不会用到.

增加了一个curlTest.php文件用于测试curl获取HTTP基础认证后的文件,没有删除,可以自行删除了.

数据库转储文件中的PID问题已经被改为smallint,理论上可以存放足够的用户了.

格式化部分代码,现在应该更好看了...

修复了代码中部分KB->MB->GB的换算错误,但是不知道是否引入了新的错误..

## 问题和解决

原作者已经表明这是一个Demo项目,他不应该被用于生产环境,若需要全功能的SS模组,请购买商业版,这个代码没有经过检查,可能会有很多错误,如果我发现的话,可能会改,如果你发现的话,可以提出来,我尝试去改.这是一个学习PHP刚刚两个月用户写出来的代码,无法保证其可用性与安全性

这是一个采用面向过程的思想完成的项目,其中也存在着很多冗余的代码,如果你愿意修改,请随意.

如果你有好想法,你可以写出来,给原作者发Pull Requests.

## 截图

![前台面板](https://ws1.sinaimg.cn/large/879fc274ly1fenq3ucewzj20wv0jp0vc.jpg)
