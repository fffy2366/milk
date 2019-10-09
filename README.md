# 婴儿喂奶记录

## php7.2 + redis

## 部署

### 安装docker
```sh
yum update 
yum install -y docker-io
```

### 制作nginx，php镜像
```sh
bin/build nginx
bin/build php
```

### 运行部署代码 
```
bin/start

bin/copytocontainer --all

docker-compose restart app
```


> 参考

[centos 6.5添加阿里云yum源](https://blog.csdn.net/yizhixiaocaiji26/article/details/78388526)
[CentOS6.x 安装 Docker 和 Docker Compose](https://blog.csdn.net/kinginblue/article/details/73527832)
[docker使用国内镜像进行加速](https://my.oschina.net/u/3703365/blog/1810028)
