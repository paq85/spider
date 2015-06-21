#!/usr/bin/env bash
# Use if you want to install ZeroMQ in Ubuntu with PHP extension

# ZeroMQ as system tool
sudo apt-get install -y libtool pkg-config build-essential autoconf automake uuid-dev
wget http://download.zeromq.org/zeromq-4.1.2.tar.gz
tar -zxvf zeromq-4.1.2.tar.gz
cd zeromq-4.1.2
./configure --without-libsodium
sudo make install
sudo ldconfig

# ZeroMQ for PHP
sudo pecl install zmq-beta
sudo touch /etc/php5/cli/conf.d/20-zmq.ini
sudo sh -c 'echo "extension=zmq.so" >>/etc/php5/cli/conf.d/20-zmq.ini'