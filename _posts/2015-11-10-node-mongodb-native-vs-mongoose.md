---
layout: post
title: node-mongodb-native VS mongoose
date: 2015-11-10 08:20:26
categories: [develop]
tags: [nodejs,mongodb,node-mongodb-native,mongoose]
---

nodejs很适合做http数据服务器，用上express之类的框架很容易就搭出一套http service来。然后数据库感觉就跟mongodb很搭，这样子一套下来全都是javascript技术栈。但是nodejs跟mongodb之间的接口应该选择哪一个呢？

<!--more-->

我关注到的库有两个：[node-mongodb-native](https://github.com/mongodb/node-mongodb-native)和[mongoose](https://github.com/Automattic/mongoose)。  

node-mongodb-native是mongodb的官方nodejs封装。其实应该说是简单封装。使用它的感觉就像是直接在shell里用`mongo`操作，特别的灵活以及效率。甚爱之。  
mongoose是一个深封装的[ORM](https://zh.wikipedia.org/wiki/%E5%AF%B9%E8%B1%A1%E5%85%B3%E7%B3%BB%E6%98%A0%E5%B0%84)库。需要像mysql设计表结构那样子设计Schema，然后把Schema具象为Model，然后可以面向对象的使用数据库了。  

---

我是更爱高效灵活地node-mongodb-native的，然而使用之有一点不好，就是：感觉这个项目的team并没有花多少心思和时间在这上面，更多地是应付的做了一个专业的封装。  
我这是什么意思呢？  
node-mongodb-native库的封装很专业，但是文档是比较应付式的，阅读和资讯获取都挺难，至少我做了近一个月还是不知道web service应该怎样**标准**地去连接数据库（应该说怎么用数据库，连接一次然后一直使用这个db呢；还是每次用`db.open`；`db.open`是不是表示从连接池里拿一个连接？）。其开发人员是从对接口的封装出发来做这个项目，而不是从用户使用的角度来开发和写文档。  
我在网上看人家对这两者的比较时看到他们说，大概意思是使用node-mongodb-native会比较折腾。  
我现在认为这里的折腾不是指写起来麻烦，而是没有响应的指导和合适的文档，许多事情需要去读源码，去做测试（这种测试还不好做，也许某种用法低并发的时候适用，多了就不行了）。  

这个项目的开发人员总该知道怎样使用吧！你倒是做个demo或者在doc中写出来啊！你倒是告诉我是不是针对每一个http请求都应该发起一个db的connection啊！你告诉我是不是对每一个http请求不用发起新connection但是需要重新`db.open`啊！你不说谁知道在web service中应该怎么用这个库啊！！！文档里每一个demo都是连接数据库-打开db-写入-关闭连接。也是醉了。  

最后的结果是遇到不知名原因的bug，一个`collection.find`返回的`promise`卡`pending`，然后找不到原因和答案，放弃之。  

---

mongoose这种把动态类型当作静态类型的思路其实我是不喜欢的（其实不是静态类型，只是做类型转换啦，我是说我不喜欢这样的思路而已）。就像我不喜欢微软的TypeSrcipt（包括ES6）给javascript弄上静态类型和class一样，人家javascript明明本身是基于prototype设计的，硬是弄上class搞毛！  
但是mongoose的开发看来是熟知node-mongodb-native的源码的，这二次封装（在node-mongodb-native的基础上进行封装）然后提供了较为完善的文档，也算是一个合适的选择。  

这里有两个点需要注意一下：  

1. 我现在使用的mongoose版本是4.2.5，其依赖`"mpromise": "0.5.4"`。也就是说mongoose返回的`Promise`是`mpromise`提供的promise实现，而这个promise实现实测不支持`promise.reject`方法。
2. 同样是这个版本。我使用文档里说的方法`mongoose.model('User', userSchema);`注册Model。这个时候model是被注册到`mongoose.models`的。于是在`mocha -w`的时候，会出现`OverwriteModelError`，估计是`mocha -w`的时候里面的机制并不会清空`mongoose.models`，然后重载model注册模块，于是报错。根据[issue#1251](https://github.com/Automattic/mongoose/issues/1251)，网友提供的`mongoose.models = {};mongoose.modelSchemas = {};`似乎可以解决这个问题。比较逗的是**aheckmann**拼死不认为这是一个bug，一直说我们用错了。没太注意看，不过也许用`connection.model('User', userSchema);`来注册也能解决这个问题，这也许是aheckmann的意思。
