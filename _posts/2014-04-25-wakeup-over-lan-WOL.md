---
layout: post
title: 关于网络唤醒，WOL
categories: develop
date: 2014-04-25 11:46:40
tags: python
---

有做一个远程开机的需要，所以python代码如下
<!--more-->

```py
-*- coding: utf-8 -*-
import socket
import struct

s = socket.socket(socket.AF_INET,socket.SOCK_DGRAM)
s.setsockopt(socket.SOL_SOCKET,socket.SO_BROADCAST,1)

data = ""
for i in range(6):
  data += struct.pack('B',255)

mac = "DC-9C-52-11-3E-A8"
macs = map(lambda x:int(x,16),mac.split('-'))
for i in range(16):
  data += struct.pack('6B',*macs)

print repr(data)

s.sendto(data,('255.255.255.255',7))
s.close()
```

其中那个端口为7或者9，有些说的是随便什么端口都行。实测88是可以的。
