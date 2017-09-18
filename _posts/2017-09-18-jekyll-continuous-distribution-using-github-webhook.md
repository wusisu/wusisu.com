---
layout: post
title: 接受 Github Webhook 进行 Jekyll 持续部署
tags: ["continuous distribution",github,webhook,jekyll]
categories: develop
date: 2017-09-18 15:07:36
---

由于[CNAME 和 MX 冲突的原因](https://www.zhihu.com/question/21128056)，我把我的个人博客（静态）部署在自己的 server 上了。

这其实挺傻逼的，毕竟这么好用的 Github Pages / [Coding Pages](https://coding.net/pages) 就与我无缘了。其实真可以考虑使用 www.wusisu.com 而不是 wusisu.com。

不管怎么说，现在我遇到了持续部署的问题。这个问题总归是需要解决的，毕竟 CD 是每一个程序员的追求。

<!-- more -->

通过看 Webhook 的[文档](https://developer.github.com/webhooks/) 和实操，提供代码如下。

（脚本竟然不在仓库里，迟点更新上来）
接受 Webhook 的脚本：
```php
```
然后执行即可 `php -S 0.0.0.0:51234`

执行部署的脚本：
```sh
```

当然 GitHub 上需要配置 https://github.com/wusisu/wusisu.com/settings/hooks

如此，我只用在 github 上创建文件，就能部署我的博客了。
比如此文就是如此。

