## firmware_analysis  
物联网设备分析环境搭建与工具使用，包括脚本换阿里源、binwalk的完整安装、firmwalker和rips的使用    

#### 系统环境
Ubuntu

#### 分析工具
#### binwalk  
install_full_binwalk文件夹中，运行： sudo install_binwalk.sh  
#### firmwalker  
无需安装，直接使用，使用方式：./firmwalker.sh file_path  
#### rips
需安装web网站解析环境:  
```  
    sudo apt install apache2 php7.2 libapache2-mod-php7.2  
    sudo /etc/init.d/apache2 restart
```  
然后，将rips文件夹其拷贝到/var/www/html/中  

#### 附加工具  
#### change_sources.sh
ubuntu下，将apt源和pip源换成阿里云的源
 

