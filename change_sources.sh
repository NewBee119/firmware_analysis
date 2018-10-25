#!/bin/bash

# [*]change ubuntu system sources to aliyun source

#:<<BLOCK
sudo mv /etc/apt/sources.list /etc/apt/sources.list.bak.1

codename=`lsb_release -c | cut -c 11-`
echo "codename is $codename"

sudo touch /etc/apt/sources.list

sudo echo "deb http://mirrors.aliyun.com/ubuntu/ $codename main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb-src http://mirrors.aliyun.com/ubuntu/ $codename main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb http://mirrors.aliyun.com/ubuntu/ $codename-security main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb-src http://mirrors.aliyun.com/ubuntu/ $codename-security main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb http://mirrors.aliyun.com/ubuntu/ $codename-updates main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb-src http://mirrors.aliyun.com/ubuntu/ $codename-updates main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb http://mirrors.aliyun.com/ubuntu/ $codename-backports main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb-src http://mirrors.aliyun.com/ubuntu/ $codename-backports main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb http://mirrors.aliyun.com/ubuntu/ $codename-proposed main restricted universe multiverse" >> /etc/apt/sources.list
sudo echo "deb-src http://mirrors.aliyun.com/ubuntu/ $codename-proposed main restricted universe multiverse" >> /etc/apt/sources.list

sudo apt-get update
#BLOCK

# [*]change pip sources to aliyun source
if [ ! -d ~/.pip ];then
    mkdir ~/.pip
fi
   
if [ -f ~/.pip/pip.conf ];
then
    sudo mv ~/.pip/pip.conf ~/.pip/pip.conf.bak
    sudo touch ~/.pip/pip.conf
else
    sudo touch ~/.pip/pip.conf
fi

sudo echo "[global]" >> ~/.pip/pip.conf
sudo echo "index-url = https://mirrors.aliyun.com/pypi/simple" >> ~/.pip/pip.conf
