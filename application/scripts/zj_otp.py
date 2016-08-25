#!/usr/bin/env python
#coding:utf-8
import pyotp
if __name__ == '__main__':
    oKey = 'dasiniwoyebushuo'
    otp = pyotp.TOTP(oKey,interval=1800).now()
    print otp
    exit(0)
