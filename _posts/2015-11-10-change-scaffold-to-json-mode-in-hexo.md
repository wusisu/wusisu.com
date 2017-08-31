---
layout: post
title: hexo中的scaffolds模版使用json格式
date: 2015-11-10 01:50:02
categories: develop
tags: [hexo,scaffolds]
---

hexo中的scaffolds文件里存了几个模板文件，其中最重要的是`post.md`，当我使用`hexo new title`的时候，hexo会自动套用这个模版产生一个待编辑的post。hexo兼容`yaml`格式和`json`格式，<del>不过现在用`yaml`的格式的地方不多了吧？也懒得去熟悉这种格式了，于是来改成</del>*试试*`json`格式吧！

<!--more-->

```
"title": {{ title }},
"date": "{{ date }}",
"categories": ["uncategorized"],
"tags": []
;;;
```
这样子就对了。  

--

值得专门记载下来的原因是，注意`{{ title }}`那里不要使用引号，不要弄成了`"{{ title }}"`，原因是hexo生成的时候会把`{{ title }}`替换为`"title"`。  
至于data那里不用引号行不行我就懒得去试了，估计是不太行的，可阅读源码：  
 > node_moduels/hexo/lib/hexo/post.js:77  

```js
JSON.parse('{' + renderedData + '}');
```
这里`renderedData`就是渲染之后的`post.md`（不包括那个分隔符`;;;`）。
