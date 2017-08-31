---
layout: post
title: 使用端口映射帮助开发web服务
date: 2016-06-27 17:27:02
categories: develop
tags: [web developing,ssh]
---

## 问题

当前在开发微信公众号的开发服务器，遇到一个问题是：微信希望我的url是某安全域名下的某80端口。
而我的开发设备在某内网，80端口还被[长城宽带](http://club.autohome.com.cn/bbs/thread-a-100024-44338780-1.html)封了。
怎么办？

当然我肯定有远端的开发服务器的。
我之前的做法是：修改代码，上传至远端，部署重启，debug。
效率十分十分十分慢。

然而实际上，非常非常简单就能完成远端80端口代理至本机的3000端口。

<!--more-->

## 解决方案

我主要是通过[此文](https://www.ibm.com/developerworks/cn/linux/l-cn-sshforward/)学习的。

总而言之
```shell
ssh -NR 80:127.0.0.1:3000 root@dev.server.com
```
就可以了。

这时候，访问http://dev.server.com/ 就相当于直接访问http://127.0.0.1:3000 。

## nginx
当然了，上述要求服务器的80端口不被占用。
如果有多人使用，或者服务器有80端口使用的情况下。
通过nginx转发也能轻松实现web开发环境配置。

```
server {
        listen 80 default_server;
        listen [::]:80 default_server ipv6only=on;
        client_max_body_size   10M;
        client_body_buffer_size   128k;

        server_name dev.server.com;

        index index.html;

        location / {
                root /website;
        }
        location ~ /a/ {
                rewrite /a/(.*)$ /$1 break;
                proxy_pass http://localhost:3000;
        }
        location ~ /b/ {
                rewrite /b/(.*)$ /$1 break;
                proxy_pass http://localhost:3031;
        }
}
```
这样子的配置
http://dev.server.com/a/ 就相当于直接访问http://127.0.0.1:3000 。
http://dev.server.com/b/ 就相当于直接访问http://127.0.0.1:3031 。
