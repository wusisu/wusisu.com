---
layout: post
title: 构建运行脚本的微服务
date: 2016-03-20 13:15:46
categories: develop
tags: [nodejs,python,micro-service,socket.io]
---

我[女朋友](https://github.com/eniffesun)正在[学习python](https://github.com/1ta/study_python)，写的脚本都是从stdin输入，从stdout输出的；而我又是一个js全栈，感觉写一个简单的python脚本调用的界面+nodejs微服务应该很有意思。

<!--more-->

#demo
[http://ali.wusisu.com/sun/](http://ali.wusisu.com/sun/)

## child_process
首先我要先实现在nodejs中调用python，并且要转发输入输出流。

```js
'use strict'
var child_process = require('child_process')
var cp = child_process.spawn('python3', ['./files/circumference.py'])
cp.stdout.pipe(process.stdout)
process.stdin.pipe(cp.stdin)
cp.on('close', (code) => {
  process.exit(code)
})
```

做的事情简单清晰易懂：
1. 用child_process的spawn调用一个python3进程cp。
2. 转发cp的标准输出到当前程序的标准输出（命令行）。
3. 转发当前程序的标准输入到cp的标准输入。
4. 监听cp的结束事件。

结果是调用这个[`node wrapper.js`](https://github.com/1ta/study_python/blob/master/wrapper.js)跟直接调用`python3 ./files/circumference.py`没有任何区别。

## 曲折

事实上开发的时候走了点弯路的。
我很幼稚的认为，使用`Content-Type: text/event-stream`能够解决问题。
只要client端发起一个`POST`请求，但不马上结束连接，而是持续的发送内容；server端使用`event-stream`持续地返回内容。
事实上我已经把模拟的client端和server端写好了，也完全达到了目标效果，然并卵，在js里没法用ajax慢慢地发`POST`请求。

event-stream: 告诉client/browser服务器会慢慢地返回内容，你收到多少直接拿去用就好。

## [socket.io](https://github.com/socketio/socket.io)
socket.io也并没有什么需要说的了，就简单推荐一下，好用。

### websocket

在socket.io之前我考虑过直接上websocket，因为之前用event-stream实现的时候并没有使用第三方的包，直接用原生的`http`然后把`stream`给`pipe`出去可爽了。于是就想着原生上websocket。
nodejs的[文档](http://npm.taobao.org/mirrors/node/latest/docs/api/http.html#http_event_upgrade)其实提到了一下websocket的。文中让我响应`upgrade`事件然后可以拿到一个socket然后可以pipe给它自己。
然后就没有更多关于怎么发起一个websocket的资讯了。然而在google/baidu等搜索引擎中，并未能找到关于websocket足够给我用的实践，全都在不清不楚地大概说websocket有什么好处，像什么样子。（如果以后有空，自己实践一下，再上文章）
反倒是有了新的东西，在要求upgrade的时候要带一个base64的key，然后在服务器response的时候要给另外一个encrypted的base64。然后还不说这个key是干什么的，简直就是各种不清不楚。

## 文件遍历/Promise
之前习惯用[stage-2](http://babeljs.io/docs/plugins/preset-stage-3/)的[async](http://babeljs.io/docs/plugins/transform-async-to-generator/)来写同步执行。不过现在babel还没有全面进入node-v5.7.1，要用async需要引入许多babel包和配置，正好前面都做比较原生的事情，于是想着用原生来写。
首先考虑了callback写法，遍历`./files`目录后调用回调。结果发现`Promise.all`还是做了不少事情，把这个写到我的递归函数中还是比较难看的，于是还是上Promise吧。

```js
var fs = require('fs')
var lookForwardFiles = function(path){
  return new Promise((res,rej)=>{
    fs.stat(path, (err, stat)=>{
      if(err) return res([])
      if(!stat.isDirectory()) return res([path])
      fs.readdir(path, (err, files)=>{
        if(err) return res([])
        var allP = files.map(f=>lookForwardFiles(path + '/' + f))
        Promise.all(allP).then(data=>res([].concat.apply([],data)))
      })
    })
  })
}
```

node支持了arrow functions还是蛮爽的，就是用`=>`来代替'function'。
这里用到了一个技巧`[].concat.apply([],data)`，可以把形如`[[1,2],[3],[4],5]`的data转化为`[1,2,3,4,5]`。
其实也可以用

```js
data.reduce((all,d)=>all.concat(d),[])
```

来代替。

## commit
给个当前[commit](https://github.com/1ta/study_python/tree/2d46040207109f936cb75d054bae7683a8591203)的链接，万一以后不用socket.io上原生websocket了呢。
