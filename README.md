# Webpage2Kindle
将带有图片的网页推送到kindle

在线示例: [http://kindle.lose7.org](http://kindle.lose7.org)



## 结构

使用python抓取网页摘要, 生成文件， 通过phpmailer发送邮件完成。



### 主要文件

| 文件名                    | 用途                                                         |
| ------------------------- | ------------------------------------------------------------ |
| [summary.py](#summary.py) | 抓取网页，获取网页摘要并保存文件                             |
| index.php                 | [网站](http://kindle.lose7.org)的前端页面+后端代码(发送邮件) |
| class.phpmailer.php       | [phpmailer](https://github.com/PHPMailer/PHPMailer)核心文件, [**经过修改**](#参考文档)(请务必使用此文件) |
| class.smtp.php            | [phpmailer](https://github.com/PHPMailer/PHPMailer) smtp核心文件 |





## 文件

### summary.py

summary使用python命令行执行， 接收` -i` , ` -o` 作为参数, 分别代表` input_url` 与` output_filename` 

二者都为必选参数， 缺一不可

#### 示例:

```
python summary.py -i https://zh.moegirl.org/%E5%93%B2%E2%99%82%E5%AD%A6 -o 妖精哲学.html
```

此代码会爬取萌娘百科的[哲♂学](https://zh.moegirl.org/%E5%93%B2%E2%99%82%E5%AD%A6)词条并生成摘要文档命名为`妖精哲学.html`



#### 摘要生成

此功能来自于[timbertson/python-readbility](https://github.com/timbertson/python-readability), 非常漂亮的一个库。



## 参考文档

[[1]python-readability源码阅读](http://yifei.me/note/278)

[[2]让phpmailer支持中文名称的附件和邮件标题中文乱码(转)](#https://blog.csdn.net/kongbu0622/article/details/7313742)



