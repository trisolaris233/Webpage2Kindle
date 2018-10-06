
# -*- coding: UTF-8 -*-

import re
import sys
import getopt
import base64
import requests

from bs4 import BeautifulSoup
from readability import Document


GENERAL_HEADERS = {
    'Accept':
    'application/json, text/plain, */*',
    'Accept - Encoding':
    'gzip, deflate, br',
    'Accept-Language':
    'zh-CN,zh;',
    'User-Agent':
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063'
}

# 获取图片url的后缀名
def get_extension(url):
    rlist = url.split('/')
    try:
        return rlist[len(rlist) - 1].split('.')[1]
    except:
        return None

def main(argv):
    outputfile = ''
    inputurl = ''
    try:
        opts, args = getopt.getopt(argv, "o:i:", ["output=", "input="])
    except:
        return 
    
    for opt, arg in opts:
        if opt == '-o':
            outputfile = arg
        if opt == '-i':
            inputurl = arg
    
    try:
        res = requests.get(inputurl, headers=GENERAL_HEADERS)
    except:
        pass
    
    # 获取降噪内容
    text = Document(res.text).summary().encode('utf-8')

    soup = BeautifulSoup(text, "lxml")
    imgs = soup.find_all('img')

    # 遍历下载所有图片
    for i in imgs:
        img_link = i.attrs['src']
        extension = get_extension(img_link)
        try:
            r = requests.get(i.attrs['src'])
        except:
            pass
        if extension != None:
            # 将原文中的图片以base64替换之
            text = text.replace(i.attrs['src'], "data:image/%s;base64,%s"%(extension, base64.b64encode(r.content)))
        


    # 输出文件
    f = open(outputfile, 'w')
    f.write('<!DOCTYPE html><html><head><meta charset="UTF-8"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Document</title></head><body>')
    f.write(text)
    f.write('</body></html>')
    f.close()

    

if __name__ == "__main__":
    main(sys.argv[1:])
        