---
layout: post
title: javascript下的编码转换
date: 2015-11-10 08:00:06
categories: [develop]
tags: [crawler,javascript,encoding,iconv,gbk,utf8]
---

在中国桌面操作系统，windows还是占主流，windows中文版使用的是GBK编码，然后我神奇的发现我所爬的那个网站，即使在linux下，返回的html竟然也是GBK编码的！！  

<!--more-->

javascript原生就支持几种编码转换，但是好像就两三种，种类特别少。一般情况下我们写javascript都是出于开发者环境，少遇到非Unicode的编码，所以考虑编码的情况并不多。但是万一遇到了咧？  
那个时候，就该使用[iconv-lite](https://github.com/ashtuchkin/iconv-lite)库了！  

 > npm install iconv-lite  

具体使用看教程就好，这里只提醒一下：使用`iconv.decode(buffer, encoding)`的时候，第一个参数传的是`Buffer`。做爬虫的时候，应该用`http`或者`request`直接拿出`Buffer`来，然后用`iconv-lite`转换。直接拿`body`估计是不行的。  

```js
var iconv = require('iconv-lite');
var getBufferWithHeader = co.wrap(function *(url) {
  return new Promise(function (resolve) {
  var buffer = [];
  request({url, jar: jarWithCookie}, function (error, response, body) {})
    .on('data', function (chunk) {
      buffer.push(chunk);
    })
    .on('end', function () {
      buffer = Buffer.concat(buffer);
      resolve(buffer);
    });
  })
});
var getPageWithHeader = co.wrap(function *(url) {
  var out = yield getBufferWithHeader(url);
  return iconv.decode(out, 'GBK');
})
```
