---
layout: post
title: ubuntu安装ushare架设家庭媒体服务器
categories: [ubuntu]
date: 2014-04-22 17:20:13
tags: [ubuntu,ushare,media-server]
---

家庭媒体服务器，听起来就很现代啊。
可以提供网络存储，媒体共享等功能。
像在server上下载好视频，然后在家里任何一台设备上点开就看，多过瘾。
<!--more-->

硬件需求只有一个：路由器能开启upnp

步骤如下：

1.  路由器开启upnp
2.  ubuntu上安装ushare: sudo apt-get install ushare
3.  配置之: sudo dpkg-reconfigure ushare
4.  用netstat -l找到upnp的端口，如我的是49193，访问http://192.168.0.54:49193/web/ushare.html然后设置位置即可。
至此，你就架好了基于upnp的家庭媒体服务器了。

访问的话，iphone android windows都有相应的播放器，大不了会做广告而已，差不到哪里去。linux下的我还没找到好用的。


另外：

*   我的硬盘太小，于是还是播移动硬盘里的东西好了，于是需要mount，命令是: sudo mount /dev/sdb1 /media/mymedia 当然啦，在之前要先确定自己的移动硬盘是不是sdb1，还有要自己建mymedia这个文件夹。
*   我房间的wifi热点信号无法遍布全家，于是拉了一根网线出去用迷你路由器架了一个热点，发现，选择热点模式就能让upnp中继出去，估计是一个网段的比较好说话吧。而我之前用普通模式，用的是两个网段，upnp就无法中继了。
*   至于用于播放的播放器嘛，windows下的windows media center是可以的。但我在实际使用中，因为共享了10000+张照片，而wmp会把所有照片集中在一起展示，于是卡死了。个人使用推荐[XBMC](http://xbmc.org/download/ "Download XBMC")(x-box media center)。
