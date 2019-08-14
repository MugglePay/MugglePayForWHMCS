# bitpayxForWHMCS 配置步骤 （5分钟）

## 0. 请确认所需要权限

您已经开通了需要的权限。
注意，如果你只有 “加密货币”，那只能接受加密货币。如需要开通其他支付方式，请按照该页面的流程操作。

<img src="https://cdn.mugglepay.com/docs/whmcs/permission.png" />


## 1. 配置文件
把这个github里面的文件放在相对于的路径下面。
https://github.com/bitpaydev/bitpayxForWHMCS

下载解压即可。确保你的modules/gateways里面多了bitpayx的几个文件。



```

# 找到你的whmcs的路径
cd /data/wwwroot/whmcs.xxxxx.com/

# 确认已经有了bitpayx
ls modules/gateways/bitpayx*php

```


## 2. 后台确认
添加完之后，按照下图打开支付设置。

<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-settings.png" />

后台应该多了几个可选项，按照开通的权限选择
<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-select-payment.png" />

## 3. 设置bitpay_secret。
在商户后台获取后台token，填入支付渠道的设置中（如果同时开通多种bitpay的支付，用相同的token即可）。
‘个人设置’-> ‘API认证’ -> '用在后台服务器' ->

<img src="https://cdn.mugglepay.com/docs/whmcs/getapi.png" />


## 4. 开始收款！
<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-user.png" />



## 5. 国际支付宝（进阶）

使用国际支付宝教程如下 （主要针对国外网站/产品，收取支付宝的人民币的国外商户）。
请务必确保您已经开通过了 国际支付宝 权限。 请务先了解结算时间以及费率。

仅需1行改动 
https://github.com/bitpaydev/bitpayxForWHMCS/compare/alipay-global?expand=1

## 注意事项
1）收款默认CNY，如果你有其他的货币，请跟管理员联系。<br />
2) WHMCS暂时不支持更改价格。如果需要更改价格，请让用户重新生成一个订单支付
3）退款请联系管理员。<br />
Email: mugglepay[at]gmail.com
https://t.me/joinchat/GLKSKhUnE4GvEAPgqtChAQ


## 6. 内嵌二维码
支持：
PC端打开：支付宝手机扫码之后，手机唤起支付宝。
手机端打开：点击二维码，唤起支付宝。
PC端打开：微信手机扫码
手机端打开：微信 不支持。

<img src="https://cdn.mugglepay.com/docs/whmcs/innercode.jpg" />



