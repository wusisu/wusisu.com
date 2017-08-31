---
layout: post
title: express中使用cors提供跨域服务
date: 2015-11-10 01:48:41
categories: [develop]
tags: [nodejs,express,cors]
---

由于浏览器里存在跨域问题，之前一段时间各种各样的跨域手法都出来了。之后却突然都销声匿迹了，估计是因为现在的浏览器都支持[cors](https://en.wikipedia.org/wiki/Cross-origin_resource_sharing)模式来解决跨域问题，而许多新的服务器都开启了这样子的服务。看了一眼发现开这样的服务原来这么简单！  

<!--more-->

cors的原理挺简单的，弄一个`Access-Control-Allow-Origin`字段在header里就差不多了。但是在express下使用还有[更简单的](https://github.com/expressjs/cors)：  

> npm install cors

然后再express的app那里：

```js
var cors = require('cors');
app.use(cors());
```

就好了。
