---
layout: post
title: 如何获得一个函数的形参个数
date: 2015-11-17 10:00:35
categories: [develop]
tags: [function,javascript,length]
---

一直都不知道如何获取javascript中，函数的形参个数。

<!--more-->

javascript作为一个函数式编程的语言，hooker的使用多不胜数。我遇到一个很灵活的一个[hooker接收器](http://vuejs.github.io/vue-router/zh-cn/pipeline/hooks.html)：  

>钩子函数异步 resolve 规则  
我们经常需要在钩子函数中进行异步操作。在一个异步的钩子被 resolve 之前，切换会处于暂停状态。钩子的 resolve 遵循以下规则：  
 - 如果钩子返回一个 Promise，则钩子何时 resolve 取决于该 Promise 何时 resolve。  
 - 如果钩子既不返回 Promise，也没有任何参数，则该钩子将被同步 resolve。  
 - 如果钩子不返回 Promise，但是有一个参数 (transition)，则钩子会等到 transition.next(), transition.abort() 或是 transition.redirect() 之一被调用才 resolve。

我估计他是对返回值做判断，看看是不是`object`有没有`.then`属性以及`.then`是不是一个函数。这样就知道是不是`promise`了。
但是我真心绞尽脑汁都没弄明白怎么他怎么实现用了一个参数`transition.next()`的情况的。

答案在[这里](http://javascript.ruanyifeng.com/grammar/function.html#toc8)。

真心的，我看了好多好多本javascript的书籍，但是一直都没有注意到这个地方！！！！
大概是没说吧？！！

```js
var fn = function(x, y){return x+y;};
fn.length // 2
```
