#!/bin/bash

#dependencies
sudo apt -y install python-lzma python-crypto
sudo apt -y install libqt4-opengl python-opengl python-qt4 python-qt4-gl python-numpy python-scipy python-pip

sudo pip install pyqtgraph
sudo pip install capstone

# Install standard extraction utilities（必选）  
sudo apt -y install mtd-utils gzip bzip2 tar arj lhasa p7zip p7zip-full cabextract cramfsswap squashfs-tools sleuthkit default-jdk lzop srecord
#Install binwalk
#sudo apt-get install binwalk
cd binwalk
sudo python setup.py install
cd ..

# Install sasquatch to extract non-standard SquashFS images（必选）  
sudo apt -y install zlib1g-dev liblzma-dev liblzo2-dev  
cd sasquatch && sudo ./build.sh
cd ..

# Install jefferson to extract JFFS2 file systems（可选）  
sudo pip install cstruct  
cd jefferson && sudo python setup.py install
cd ..

# Install ubi_reader to extract UBIFS file systems（可选）  
sudo apt -y install liblzo2-dev python-lzo   
cd ubi_reader && sudo python setup.py install
cd ..
# Install yaffshiv to extract YAFFS file systems（可选）   
cd yaffshiv && sudo python setup.py install
cd ..

#install unstuff (closed source) to extract StuffIt archive files
sudo cp stuff/bin/unstuff /usr/local/bin/