---
layout: post
title: javascript正则多处匹配
date: 2015-11-09 18:27:34
categories: [develop]
tags: [crawler,regex,javascript]
---

正则匹配内容的多处地方的注意事项。

<!--more-->

一般情况下，javascript的正则在匹配到第一个位置之后，就不在匹配了。然而在做爬虫的时候常常需要得到所有匹配的内容，这时可以用[g修饰符](http://javascript.ruanyifeng.com/stdlib/regexp.html#toc16)来开启全局匹配。  

```js
var regex = /b/g;

var str = 'abba';

regex.test(str); // true
regex.test(str); // true
regex.test(str); // false
```
于是想获得所有符合的内容，可以通过：

```js
var aFilePath = 'path to file';
var regex = /src=\"(pic\?id=\d{2,})\"/g;
var data = yield fs.readFileAsync(aFilePath);
var name = regex.exec(data)[1];
var urls = [];
var match;
picUrlRegex.lastIndex = 0;
do {
  match = picUrlRegex.exec(data);
  if(match)
    urls.push(match[0]);
} while (match);
```

实现。  
这里有一个特别需要主要的是，你需要手动的使用`picUrlRegex.lastIndex = 0;`来复位索引。
