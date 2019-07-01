# bitpayxForWHMCS 配置步骤 （5分钟）

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


## 4. 开始收款！
<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-user.png" />





## 注意事项
1）收款默认CNY，如果你有其他的货币，请跟管理员联系。<br />
2）退款请联系管理员。<br />
Email: mugglepay[at]gmail.com
https://t.me/joinchat/GLKSKhUnE4GvEAPgqtChAQ

