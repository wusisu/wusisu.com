---
layout: post
title: ubuntu下编译pyactivemq
categories: develop
date: 2014-04-22 16:12:48
tags: [ubuntu,python,jms]
---

我的任务是把python的一个项目移植到linux下。pyactivemq在windows下只用直接下载一个pyd就好，而linux要编译。而这个项目已经好几年没有人动过了，所以有很多依赖的项目需要选择特定版本而不是最新版本。
<!--more-->

首先解释几个名词：

*  [JMS](http://activemq.apache.org/jms.html "JMS")（java message service）：java信使服务。一般称为jms中间件。
*  [activemq](http://activemq.apache.org/ "Apache ActiveMQ")：大概是active message queue的意思吧。我不太了解activemq和jms之间的关系，但是把他俩等价视之在大部分情况下都是对的。
*  [openwire](http://activemq.apache.org/openwire.html "OpenWire") &amp; [stomp](http://stomp.github.io/ "stomp")：activemq支持的两种协议，区别好像是stomp只支持文本信息，而openwire支持文本和字节流两种。估计也是因为如此，openwire只支持C、C++以及C#；而stomp在各类主流语言中都有现成的客户端。
*  [pyactivemq](https://code.google.com/p/pyactivemq/ "pyactivemq")：这是python下支持openwire的module，这不跟上面我说的矛盾，因为他就是由C++版本的activemq编译成的。

好了，因为公司的架构是基于openwire的，而重新开一个stomp协议给我动静比较大（我一个人搞不定），所以先试着编译个linux下的pyactivemq。


[这个](http://sensatic.net/activemq/pyactivemq-on-ubuntu.html "PYACTIVEMQ ON UBUNTU")教程很重要，在pyactivemq的项目中有链接过去。

1.  首先去上面的4点过去看看，上面写得很清楚了，需要activemq-cpp，而且要2.2.6的版本的。好了，下载好准备编译。另外需要几个包：
  sudo apt-get install libboost-python-dev libcppunit-dev uuid-dev autoconf automake libtool build-essential
2.  看看readme，也说得很清楚，需要别的几个项目，能够apt掉的自然直接apt，但是[apr](http://apr.apache.org/ "apr")（apache portable runtime）需要自己编译，因为我发现通过apt-get来的apr版本太高，会configure不过。
3.  好，去下载apr和apr-util。嗯，实测apr-1.3.12和apr-util-1.3.12能编译运行，[这](http://archive.apache.org/dist/apr/ "apache-apr源码仓库")是我千辛万苦找来的下载地址。
4.  好，编译安装要教吗？先装apr：进入文件夹，./configure --prefix=/opt/apr/，make，sudo make install。再装apr-util：进入文件夹，./configure --prefix=/opt/apr-util/ --with-apr=/opt/apr/，make，sudo make install。这里在configure的时候指定了安装地址，因为后面的安装需要找到之前的项目地址，而我又不想把他装到系统中，因为老版本的apr会影响别的项目的，[比如](http://wusisu.com/blog/index.php/setup-mercurial-server/ "架设Mercurial服务器")我的apache2服务器在把这些东西安装到系统环境中就运行不了了。
5.  然后就是activemq-cpp了。进入文件夹，mkdir config，./autogen.sh，./configure --prefix=/opt/activemq-cpp --with-apr=/opt/apr/ --with-apr-util=/opt/apr-util/，make，sudo make install。这里呢要先用autogen.sh来生成configure，然后再继续。
6.  最后是编译pyactivemq了。在pyactivemq的源码文件夹，可以看到说明文档，两步，python setup.py build以及sudo python setup.py install。但是在这之前，你要先修改setup.py，把里面3处地址改正确。
好了，大功告成。
