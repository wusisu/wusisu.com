---
layout: post
title: nodejs下清理文件名
date: 2015-11-10 07:44:59
categories: develop
tags: [crawler,filename,nodejs]
---

我做爬虫的时候，遇到一个问题：我想把页面的标题作为文件夹名字，结果出错了，仔细一看，发现标题里含有字符`/`。  

<!--more-->

使用windows的人应该都遇到过，给一个文件命名带有`?`的名字时，他就会告诉你有七八个字符是不能作为文件名的。  
所以写一个函数把这些字符滤掉。  

```js
var makeFilenameValid = function (rawFilename) {
  var InvalidCharRegex = /[\/:\*\?\"< >\|]/;
  var okFilename = '';
  _.each(rawFilename, function (char) {
    var match = InvalidCharRegex.test(char);
    if (!match){
      okFilename += char;
    }
  });
  return okFilename;
}
```
