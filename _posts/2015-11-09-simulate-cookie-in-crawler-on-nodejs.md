---
layout: post
title: nodejs下在爬虫中模拟cookie
date: 2015-11-09 08:08:27
categories: [develop]
tags: [crawler,cookie,request,nodejs]
---

这是关于如何在nodejs环境下，发起http请求时带上cookie的教程。
<!--more-->

其实我用python写爬虫/发起http请求会熟手一些，不过nodejs作为一个[能开发命令行程序的引擎](http://www.ruanyifeng.com/blog/2015/05/command-line-with-node.html)，我又想多熟练js的编程，于是就试着用nodejs来做一个爬虫。  
也是我对cookie的原理不熟悉吧，爬虫带cookie的时候，我试着在header上通过`setHeader`设置`Cookie`属性`a=123; b=456`这样子，但是服务器不认，也不知道是为什么。但是看到好像是说服务器可以设置不让本地读和写cookie，不知道是不是相关。  

我设置header不行，但是人家可以啊！我使用的是：
 > npm install request

在[文档](https://github.com/request/request)里有cookie的使用[说明](https://github.com/request/request#requestcookie)：
 > request.cookie  
 Function that creates a new cookie.  
 `request.cookie('key1=value1')`

以及  

```js
var j = request.jar();
var cookie = request.cookie('key1=value1');
var url = 'http://www.google.com';
j.setCookie(cookie, url);
request({url: url, jar: j}, function () {
  request('http://images.google.com')
})
```

看起来知道应该使用jar来组一个cookie然后扔给http请求使用就好了。  
但在这里有一个要注意的坑在，就是
`var cookie = request.cookie('key1=value1');`
一句，谁家的cookie只有一个property的啊！于是我就
`var cookie = request.cookie('key1=value1; key2=value2');`
，然后服务器不认。  
苦心孤诣地尝试很多遍之后，才挖掘出来应该是：

```js
var cookie;
cookie = request.cookie('key1=value1');
j.setCookie(cookie, url);
cookie = request.cookie('key2=value2');
j.setCookie(cookie, url);
```

文档不能写清楚一点吗！  

扔上代码，url是比较敏感的所以改成example了。

```js
var collectionHost = 'www.example.com';
var collectionDomain = 'http://' + collectionHost;
var jarWithCookie = request.jar();
var cookieString = 'key1=value1; key2=value2; key3=value3; key4=value4; key5=value5';
for (kv of cookieString.split('; ')) {
  jarWithCookie.setCookie(request.cookie(kv), collectionDomain);
}

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
```

其中，`cookieString`可以轻松从chrome的dev工具中搞出来。
