---
layout: post
title: 关于Java，对象化，Spring/Bean的一些随想
date: 2016-08-16 21:59:58
categories: [develop]
tags: [java,spring]
---


最近在全力学习什么叫做Java，什么叫做Spring，什么是Bean。随便写写想法。

<!--more-->

## 名词王国
首先介绍这篇文章[名词王国里的死刑（翻译）](http://lcwangchao.github.io/%E5%87%BD%E6%95%B0%E5%BC%8F%E7%BC%96%E7%A8%8B/2012/07/02/excution_in_the_kingdom_of_nouns/)，就尽了全力在嘲讽Java的唯对象化编程的。
在学习Java的过程中，特别是现在在看一个不那么成熟，但已经比较庞大的项目，更加是心有同感。
这是一切的前提。
比如一个Restful处理OAuth的api，需要有一个OAuthController, OAuthProvider, OAuthService, OAuthConfig.....
心累。

## 松耦合
Java的问题还不止名词滥用一个。
另外一个问题是，耦合性过高的问题。为了解决这个问题，Java竭尽脑汁。
出了Interface，这样，类之间就不那么需要互相依赖了。
然而，还是需要有一个主控来调用他们，赋值使用的。
最后是[Spring](https://spring.io/)出来统一了天下。

## Interface vs Duck Test
其实Interface和Duck Test（鸭子方法）是一回事。
鸭子方法解释为：『如果它看起来像鸭子，游泳像鸭子，叫声像鸭子，那么它可能就是只鸭子。』
Interface就是在事先说好了鸭子应该长得像什么，然后有这些功能的都可以扮演鸭子。
不知道所谓的AOP编程是不是指这个呢？存疑。

## Spring and Bean
看了一篇[博客](http://www.cnblogs.com/vicis/p/4750473.html)，觉得是学Bean最好的方法。
简单说就是自己最小化的模拟着做了一个Spring的Bean系统，而且还是最新的基于注解（annotation）的。
我把它给实现并上传到[github](https://github.com/wusisu/java-spring-bean-annotation-learn)了。
简单说，Spring自己有一个注册库。所有注解为Bean的，就是在里面注册了可以用的一个创建实例的方法。
用@Autowired/@Resource/@Qualifier的，是由Spring控制着往里面装载实例的地方。
以此达到上述的松耦合。
但是实际上，实现的方式无非是通过『字符串』或者『类』，归根结底是采用了动态语言的性质。

## java.lang.reflect vs meta programming
有一段时间，Ruby的元编程火到到处都是。
其实Java的反射也是一回事，只是废话比较多，语言比较臃肿。
现在最新的注解，其实是依赖于反射的。

## Java vs JavaScript
实话实说，做JavaEE，感觉还不如用JavaScript写。
当然，写nodejs其实对人的要求更高。
但是JavaEE好臃肿，代码冗长，体积臃肿。
相对比，我觉得Java是在细节和底层做了许多优化，比如『静态语言』这一项也看做是优化，而nodejs则是底层随便写。
然而底层高效的Java如果不好好写顶端的话，就会变得很搞笑了。
我想写Nodejs。
