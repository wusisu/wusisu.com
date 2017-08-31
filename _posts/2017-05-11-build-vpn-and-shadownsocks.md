---
layout: post
title: 搭建你自己的 VPN 和 SS
tags: [vpn,ss,letsencrypt]
categories: develop
date: 2017-05-11 10:29:38
---

想搭建自己的 vpn 和 ss 吗？


## SS

[1] https://teddysun.com/342.html

```
wget --no-check-certificate -O shadowsocks.sh https://raw.githubusercontent.com/teddysun/shadowsocks_install/master/shadowsocks.sh
chmod +x shadowsocks.sh
./shadowsocks.sh 2>&1 | tee shadowsocks.log
```
即可。


## VPN

[2] https://quericy.me/blog/860/
[3] https://github.com/quericy/one-key-ikev2-vpn


### 看自己是什么虚拟机
```
wget -N --no-check-certificate https://raw.githubusercontent.com/91yun/code/master/vm_check.sh && bash vm_check.sh
```

### 用 acme.sh 来做申请 ssl key

#### 安装 acme.sh
```
curl https://get.acme.sh | sh
```
需要重新登录或者重加载配置，以使用 acme.sh 快捷命令


#### 申请 ssl key

```
acme.sh --issue --standalone -d hk.wusisu.com
```


### 用 one-key-ikev2-vpn 来部署 vpn

#### 拷贝 key

```
cp ~/.acme.sh/hk.wusisu.com/{ca.cer hk.wusisu.com.cer hk.wusisu.com.key} .
mv ca.cer ca.cert.pem
mv hk.wusisu.com.cer server.cert.pem
mv hk.wusisu.com.key server.pem
```

#### 一键安装

```
wget --no-check-certificate https://raw.githubusercontent.com/quericy/one-key-ikev2-vpn/master/one-key-ikev2.sh
chmod +x one-key-ikev2.sh
bash one-key-ikev2.sh
```

### 设置自己 renew

#### key renew

```
acme.sh --days 30 --renew --standalone -d hk.wusisu.com
```

#### 编辑 cp_key.sh

```
#! /bin/bash
cert_file="/root/.acme.sh/hk.wusisu.com/hk.wusisu.com.cer"
key_file="/root/.acme.sh/hk.wusisu.com/hk.wusisu.com.key"

sudo cp -f $cert_file /usr/local/etc/ipsec.d/certs/server.cert.pem
sudo cp -f $key_file /usr/local/etc/ipsec.d/private/server.pem
sudo cp -f $cert_file /usr/local/etc/ipsec.d/certs/client.cert.pem
sudo cp -f $key_file /usr/local/etc/ipsec.d/private/client.pem
sudo /usr/local/sbin/ipsec restart
```

#### 编辑 crontab

```
crontab -e
```

加一行

```
59 02 1 * * bash /root/cp_key.sh > /dev/null
```