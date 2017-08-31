---
layout: post
title: 使用vuejs开发大型SPA
date: 2015-10-23 15:47:10
updated: 2016-03-14 14:59:44
categories: develop
tags: [vuejs,SPA,javascript]
---

一直觉得react太折腾，ng太庞大，knockout太老土，像我需要开发大型SPA的，总是找不到路可走。如果你有跟我一样的感受，那来试试vuejs吧。
<!--more-->

## vuejs
[vuejs](cn.vuejs.org "vuejs官网")是一个在美国的中国小伙子开发的一个数据绑定库，有着简洁的代码风格。  
作者尤雨溪[开发并维护着这个项目](http://www.zhihu.com/question/36292298/answer/67049956?utm_campaign=webshare&utm_source=weibo&utm_medium=zhihu "维护一个大型开源项目是怎样的体验？")，项目的两个核心：
 > 本质上 Vue.js 做的是两件事情，数据绑定和视图层组件化。

都非常吸引我，因为我认为大型SPA必须要采取组件式开发，另外我需要一个纯粹（不要像angular这么繁杂）的数据绑定库。  

vuejs在实现上很吸引人的一点是，它跟[knockoutjs](knockoutjs.com "knockoutjs")一样，采取*依赖链*而不是脏数据 检查的方式来实现绑定，这样其运算量不会因为监听器的数量增加而增加，在一定程度上保证了大型SPA的性能。  

## components
见vuejs文档的components一章。  
另见vuejs文档*创建大型应用*一章。这章提供了一种.vue后缀的开发方法。由于组件应该是包括了*模版、逻辑代码、样式*的，于是怎么布置这些个文件的位置就是一种学问了，通常我们会给每一个component一个文件夹，index.js作为入口。而.vue可以把三样内容放在一个.vue文件里。这样文件树突然就变得超级的干净简洁了。（你可以从他的demo看到他使用stylus、coffeescript、jade全都是最简洁的语言）  
<del>但是我放弃了使用.vue，虽然尤雨溪甚至提供了sublime显色插件，但是毕竟多了一层编译过程，有时候显色不正常还算小事，出了错不好定位那就比较耽误事儿了，所以我采取的是[webpack](http://webpack.github.io/)+[style-loader](https://github.com/webpack/style-loader)的解决方案，其实这也是[vueify](https://github.com/vuejs/vueify)的工作方式(它使用的是[insert-css](https://github.com/substack/insert-css))，只是我使用更传统的布局，手动地加载罢了。</del>   

```js
var vue = require('vue');
var genData = function(){
  require('style!./style.less');
  return {};
};
var component = vue.extend({
  template: require('./template.jade')(),
  data: genData
})
module.exports = component;
```
当然了，webpack.config得相应配置上

```js
module: {
  loaders: [
    { test: /\.jade$/, loader: "jade-loader"},
    { test: /\.html$/, loaders: ["raw-loader"]},
    { test: /\.less$/, loaders: ["raw-loader","less-loader"]},
    { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader"}
]}
```

**updated：2016-03-14** 还是用上了webpack + .vue的架构，没办法不用不行啊。
特别是引入了babel之后就更难找到出错的地方了，不过接受这一设定的话，整个项目突然变简洁了。
当然还是有难以定位出错点的问题，想其他办法解决吧。

## vue-router
[vue-router](http://vuejs.github.io/vue-router/zh-cn/)提供了开发SPA所需的路由和历史功能，与vuejs配合那自然是真真好的。

## vuex
[vuex](http://vuejs.github.io/vuex/zh-cn/)提供了开发大型SPA的状态管理。

## 后记
[vuejs正式版1.0.0](https://github.com/vuejs/vue/releases/tag/1.0.0)于2015年10月27日终于面世了。
