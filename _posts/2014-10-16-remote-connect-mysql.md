---
layout: post
title: 远程连接mysql
categories: develop
date: 2014-10-16 08:59:57
tags: mysql
---

mysql开启远程访问（如navicat）需要修改两处地方：
<!--more-->

1. mysql授权某用户可以远程连接到哪些库：

```sh
grant all privileges on *.* to root@'%' identified by 'mypassword' with grant option;
flush privileges;
```

其中，all privileges表示所有权限，*.* 表示所有表，root@'%'表示root用户来自任何来源。

2. mysql 服务允许远程连接，找到my.cnf，注释其中的bind-address = 127.0.0.1即可。ubuntu上在/etc/mysql/my.cnf。

p.s. mysql版本5.6
