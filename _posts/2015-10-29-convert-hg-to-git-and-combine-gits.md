---
layout: post
title: 从hg迁移到git，以及合并两个git
date: 2015-10-29 14:02:59
categories: develop
tags: git
---

手上有一个大的项目，分别是好几个hg库。  
我想合并成一个git库。  
<!--more-->

分几个步骤吧：  

1. 转换hg库为git库  
2. 合并两个git库
3. 重复1、2

## 转hg库为git库
 > 参考git-scm的[文档](https://git-scm.com/book/zh/v2/Git-%E4%B8%8E%E5%85%B6%E4%BB%96%E7%B3%BB%E7%BB%9F-%E8%BF%81%E7%A7%BB%E5%88%B0-Git#Mercurial)。  


## 合并两个git库
不知道是参考哪里的方案了，不过思路很简单。  

1. 首先有一个git库：`/tmp/git1`，以及另外一个git库`/tmp/git2`
  `$ cd /tmp/git1  `

2. 其次设置git2为git1的一个源  
  `$ git remote add git2 /tmp/git2`

3. fetch之。（似乎pull会帮你合并，还是fetch安全，[参考](http://longair.net/blog/2009/04/16/git-fetch-and-merge/)）
  `$ git fetch git2 master:branch2  `

4. merge之。（我用的是图形操作，其实fetch也是用图形操作的，到这里就差不多了）

## 排序？
merge成功之后，git库在sourceTree中的表示方式是最下面是git2的，上面是git1的。我觉得应该按时间排那样子才酷炫，但是还没有花时间去研究怎么搞。待更新。
