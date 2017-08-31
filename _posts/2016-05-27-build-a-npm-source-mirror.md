---
layout: post
title: 构建npm镜像源
date: 2016-05-27 15:46:38
categories: develop
tags: [local-npm,npm,mirror]
---

其实有[淘宝npm镜像](https://npm.taobao.org/)个人是完全无必要做npm的镜像的。
一般的研发团队的宽带也无须考虑。但是我们是[长城宽带](http://club.autohome.com.cn/bbs/thread-a-100024-44338780-1.html)。
正好公司里有一台没啥事做的服务器，决定上手搞一个npm的镜像。

<!--more-->

## 原因
1. npm安装的时间很长，同时依赖于公司带宽。
2. 大规模/大量前端项目，可以把公用的模块独立成npm模块，单独管理。所以需要私有的npm仓储。

## 解决方案
我们暂时不需要第二点，故使用[local-npm](https://github.com/nolanlawson/local-npm)解决npm加速问题就好了。
>以后可以考虑换用[cnpmjs](https://github.com/cnpm/cnpmjs.org)来做私有仓储。

### fullfatdb
fullfat就是全脂的意思，主要相对下面skim脱脂而言的。
~~我们使用淘宝的fullfatdb: https://registry.npm.taobao.org/~~
用的是官网的，因为local-npm对cnpmjs的镜像不兼容。给local-npm发了个[issue](https://github.com/nolanlawson/local-npm/issues/123)，暂时没空去贡献代码。

>当前 registry.npm.taobao.org 是从 r.cnpmjs.org 进行全量同步的
>Our public registry: r.cnpmjs.org, syncing from registry.npmjs.org

### skimdb
[skim](http://dict.youdao.com/w/skim)是脱脂的意思。去掉了上面fullfatdb中二进制的部分，应该只是metadata部分，大概0.95GB大小。
使用官方的 https://skimdb.npmjs.com/registry 进行克隆。因为暂时好像还没有国内镜像源。

## 部署
服务部署在本地开发服务器上。
```sh
npm install -g local-npm
mkdir npm
cd npm
#local-npm -r https://registry.npm.taobao.org #不能使用-r taobao了，因为taobao是cnpmjs架设的，而-r只支持local-npm或者源站。
local-npm -u http://192.168.174.233:5080 #必须用上-u，否则会败，如果已经败了，在client上记得跑`npm cache clean`清理一下。
```

## 使用
不管用不用本地的npm服务源，都推荐用[nrm](https://github.com/Pana/nrm)进行npm源控制。就像不管你用不用多版本node，都推荐你使用[nvm](http://nvm.sh)一样。

### nrm
安装`npm install -g nrm`，使用很简单。
可以有
```sh
nrm test
nrm use taobao
```
其实不用test直接use就好了。
你可以直接输入`nrm`看看所有命令。
其中有一个比较有趣的`nrm home taobao`。

### local-dev
>[local-dev] 用 192.168.174.233 代替。 (公司内部服务器地址)

使用nrm添加新源：
```sh
nrm add local http://[local-dev]:5080 http://[local-dev]:5080/_browse
nrm use local
```
就可以了。
现在你可以`nrm test`看看谁延迟更低。

### search
对，如果你注意到了的话，http://[local-dev]:5080/_browse 是我们的一个npm搜索引擎。
可以通过
```sh
nrm home local
```
查看。

## 注意
由于只同步了0.95GB的metadata，二进制包并未同步。第一次下载模块时可能比平时慢（二进制包由server从官网下载，比淘宝要慢；当然查metadata还是块一些的）
第二次就快了。
