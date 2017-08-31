---
layout: post
title: Ubuntu架设apt源服务器
categories: ubuntu
date: 2014-04-22 16:10:28
tags: ubuntu
---

一直觉得架设更新源是很牛逼的事情，放google一下才发现加起来就两分钟的操作，然后等就行了。
<!--more-->

我是按着[这里](http://diandian.db89.org/post/2012-07-27/ubuntu-source-update "ubuntu12.04本地搭建ubuntu更新源")来搞的。

其实我就这样转发一下链接应该也就差不多了，但若真的如此，我的博客就白花心思去架设了。我还是把我的操作说一下，给大伙儿做个印证。

&nbsp;

首先是获取更新源镜像的过程：

```sh
sudo apt-get install apache2 apt-mirror
sudo vi /etc/apt/mirror.list
sudo apt-mirror
```

我先打印一下我的mirror.list:

```sh
set nthreads 20
set _tilde 0
deb-i386 http://mirrors.ustc.edu.cn/ubuntu precise main restricted universe multiverse
deb-i386 http://mirrors.ustc.edu.cn/ubuntu precise-security main restricted universe multiverse
deb-i386 http://mirrors.ustc.edu.cn/ubuntu precise-updates main restricted universe multiverse
clean http://mirrors.ustc.edu.cn/ubuntu
```

安装apt-mirror之后，在/etc/apt/下就会有一个可以使用的配置文件存在，不过我们一般要自行进行少许配置的。比如说我使用的源就是用ubuntu软件中心帮我测速得到的地址，这里少说要60G的，自然是越快越好。我因为暂时用的都是32位机子，所以配置的都是deb-i386，deb指的是64位，deb-src则是源码包。

按我的配置，运行apt-mirror之后，它会发出20个线程去wget各个目标文件，然后要下60G左右的deb包。正在挂着的时候，跑上来写个日志，现在跑到1G了。嗯，看某文件夹的大小是这样：
> du -sh /var/spool/apt-mirror

完成之后，在/var/spool/apt-mirror的3个目录下，各会有个mirrors.ustc.edu.cn文件夹，把这个文件夹用apache2映射到http上，便可以成为ubuntu源了。apache2的配置也不复杂，会在以后献上。
