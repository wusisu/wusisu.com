---
layout: post
title: 微信Oauth2授权
date: 2016-05-27 16:34:52
categories: develop
tags: [wechat,oauth2]
---

本文是我个人对oauth2的理解。
<!--more-->

可以参考阮一峰的[理解OAuth 2.0](http://www.ruanyifeng.com/blog/2014/05/oauth_2_0.html)。

其实微信采取的Oauth2策略是最复杂的“授权码模式”。

步奏是这样的：
1. 我们的app跟用户索要授权。
2. 用户跟微信授权服务器说这件事，微信返回一个code。（微信调用微信内核鉴权，并不需要输入用户名密码。其实像qq的oauth需要通过输入用户名密码登录，那是不对的。因为大部分用户并无法分辨要求登录的页面是钓鱼页面还是https的qq登录页面）
3. 用户把code告诉我们的服务器。
4. 我们的服务器跟微信的服务器对code，同时提供预先在微信设置的应用密码（POST加密传输）。服务器通过应用密码确定我们身份，通过code确定用户授权给我们了。如拿回微信服务器给我们一个用户的access token和一个refresh token。（通常access token存活时间为2小时，refresh token为30天。主要原因是access token会一直带着？）
5. access token拿去用。


我们在这基础上加上自己的jwt（json web token）机制。jwt其实是一种session的升级，用localstorage取代session使用的cookies。
思路如下：

1. 用户以jwt对我们服务器发起api请求。（有无效可以最简单只通过时间过期，复杂的检测refresh token有效性）无效跳转至6
2. jwt有效，正常运行。 有需要获取微信的资源的，检测access token有效性。无效跳去4
3. 正常获取。
4. access token 无效， 通过refresh token更新access token。成功则跳去3
5. 如果refresh token无效，需要重新获取refresh token，则重新进行微信oauth2
6. 通过上述微信oauth2获得用户的openid（跟access token一起返回），access token， refresh token。以openid为主key，储存access token， refresh token。
7. 以openid为key，生成jwt，设置合理时间，并跳转至2
