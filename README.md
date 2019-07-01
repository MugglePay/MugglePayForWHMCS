# bitpayxForWHMCS 配置步骤 （5分钟）

## 1. 配置文件
把这个github里面的文件放在相对于的路径下面。
https://github.com/bitpaydev/bitpayxForWHMCS

下载解压即可。


👇
在linux下面可以如下操作

```

# 找到你的whmcs的路径
cd /data/wwwroot/whmcs.xxxxx.com/

# 先确认你现在还没有bitpayx，下面命令返回为空。
ls modules/gateways/bitpayx*php


mkdir bitpayx-tmp
cd bitpayx-tmp

wget https://github.com/bitpaydev/bitpayxForWHMCS/archive/master.zip
unzip master.zip

# 把文件复制过去
mv bitpayxForWHMCS-master/modules/gateways/bitpayx* ../modules/gateways/.

# 删除没用的临时文件
cd ..
rm -rf bitpayx-tmp

# 确认已经有了bitpayx
ls modules/gateways/bitpayx*php


```


## 2. 后台确认
添加完之后，按照下图打开支付设置。

<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-settings.png" />

后台应该多了几个可选项，按照开通的权限选择
<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-select-payment.png" />

## 3. 设置bitpay_secret。
在商户后台获取后台token，填入开通几个支付渠道。


## 4. 开始收款！
<img src="https://cdn.mugglepay.com/docs/whmcs/whmcs-user.png" />





## 注意事项
1）收款默认CNY，如果你有其他的货币，请跟管理员联系。<br />
2）退款请联系管理员。<br />
Email: mugglepay[at]gmail.com
https://t.me/joinchat/GLKSKhUnE4GvEAPgqtChAQ

