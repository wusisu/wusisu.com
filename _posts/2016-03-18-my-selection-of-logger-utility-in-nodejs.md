---
layout: post
title: nodejs日志控件的选择
date: 2016-03-18 15:31:05
categories: [develop]
tags: [nodejs,logger,service,morgan,bunyan]
---

做服务器开发，必不可少的一个模块是日志系统。
在Java界，有大牛[log4j](https://zh.wikipedia.org/wiki/Log4j)，感觉都可以毫无顾虑地直接选择这个模块。但是在nodejs就没有这样事实上的标准了。
于是我花了些时间研究哪个nodejs日志模块好用。

<!--more-->

## 结论

最后我选中的日志插件是
- [bunyan](https://github.com/trentm/node-bunyan): a simple and fast JSON logging module for node.js services
- [morgan](https://github.com/expressjs/morgan): HTTP request logger middleware for node.js

## 评价

### morgan
morgan是expressjs默认带着的超简单的日志系统，专门用来生成http的请求记录。其生成的日志个人感觉于Nginx的日志十分类似。用来产生一个http请求日志再好不过了。
使用非常简单：

```js
import morgan from 'koa-morgan'
export const loggerMiddleware = morgan('combined')
app.use(loggerMiddleware)
```

我这里用的是koa2，所以用封装过的morgan来写。

### bunyan
bunyan是一个会产生JSON格式的日志的日志模块。
其实可读性不高。其提供了把json转成易读性高日志的阅读工具，但是总觉得不太实用。
不过当我们把它作为一种记载重要操作的操作记录工具（这属于业务功能而不是技术功能了）时，就挺合适使用了。

```js
const Writable = require('stream').Writable
const writeStream = new Writable({ objectMode: true })
const DEBUG = process.env.DEBUG
writeStream._write = function(log, encoding, next){
  if(log.level>40) console.error(log)
  if (DEBUG && log.level<=40) {
    console.log(log)
  }
  if (log.level<30) return
  let item = new Model
  item.obj = log
  item.save(next)
}
import bunyan from 'bunyan'
export const logger = bunyan.createLogger({
  name:'app',
  src: true,
  streams: [{
    level: 'trace',
    type: 'raw',
    stream: writeStream
  }]
})
```

写入一个记录的方式是:
```js
logger.info({message:'hello world!'})
```

这里用的比较复杂。本来bunyan的那个streams直接放console或者文件流是非常简明的，在这里我创建了一个writeStream来接受数据流，然后再写一个回调来把数据流存入mongodb中。这样的用例在业务上是非常常见的。
