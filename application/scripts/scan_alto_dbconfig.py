#!/usr/bin/env python
# coding= utf-8
# author guoqi

import os,sys,signal,time
import re
import argparse
import MySQLdb

import httplib, urllib

import logging
import logging.config
(pathAbs, scName) = os.path.split(os.path.abspath(sys.argv[0]))
logging.config.fileConfig("%s/logger.ini" % (pathAbs))
logger = logging.getLogger("camelbell")

def find_in_conf(keyword, env, outfile="tguoqi"):    
    postVals = {'mainkeyword': keyword, 'env': env, 'file': outfile}
    logger.info(postVals)
    params = urllib.urlencode(postVals)
        
    headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}
    conn = httplib.HTTPConnection(host=rcHost,port=80,timeout=15)
    conn.request(method="POST", url="/fic.php", body=params, headers=headers)
   
    resp = conn.getresponse()
    data = resp.read()

    return data


def getMyPlatConn():
    ip = "172.21.100.200"
    port = 3388
    user = "camelbell"
    passwd = "camelbell"
    db = "camelbell"
    try:
        myConn = MySQLdb.connect(host=ip, port=port, user=user, passwd=passwd, db=db, charset="utf8")
        return myConn
    except MySQLdb.Error, e:
        logger.error(e)
    finally:
        pass
            
    return None

def getMyCfgConn():
    ip = "172.21.100.180"
    port = 3306
    user = "dhdba"
    passwd = "dhdba_2014"
    db = "alto"
    try:
        myConn = MySQLdb.connect(host=ip, port=port, user=user, passwd=passwd, db=db, charset="utf8")
        return myConn
    except MySQLdb.Error, e:
        logger.error("can't get cfg connection")
        logger.error(e)
    finally:
        pass
            
    return None

def doQuery(conn, qSql):
    try:
        cur = conn.cursor()
        cur.execute(qSql)
        lines = cur.fetchall()
        return lines
    except MySQLdb.Error, e:
        logger.error(e)
        conn.rollback()
    finally:
        pass
        
    return None
    
def getEnvs():
    envs = []
    qSql = "SELECT name, namecn, console FROM alto_env"
    lines = doQuery(connCfg, qSql)
    for line in lines:
        envs.append(line[0])
        
    return envs

def getStatParams(dbtype, keyword, env):
    logger.warn(keyword)
    
    insParam = []
    lines = find_in_conf(keyword=keyword, env=env)
    clines = (lines.split("\n"))[1:]
    logger.info("%s %s = %s" % (env, keyword, len(clines)))
    # {url:{apps:count}}
    errlines = []
    dbconns = {}
    #happs = {}
    for line in clines:
        tline = line.rstrip("\n").rstrip("\r")

        # env, appname
        vals = tline.split("/")
        if len(vals) < 5:
            #errlines.append(line)
            continue
        
        henv = vals[5]
        if cmp(env, henv):
            logger.error("[%s, %s] env not same. %s" % (keyword, env, henv))
            errlines.append(line)
            continue
        happ = vals[6]
        
        #
	ttars = tline.split(":")
        cnffile = ttars[0]
	if len(ttars)>1:
	    regContent = ":".join(ttars[1:])
	else:
	    regContent = ""

        #
        tdbconns = []
        if not cmp(dbtype, DBTYPE_MYSQL):
            tline = tline.replace("\\","")
            if re.search("jdbc:mysql", tline):
                turl = ((tline.split("jdbc:mysql"))[1].split("?"))[0].lstrip("://")
                tvals = turl.split("/")
                dbname = tvals[1].replace(">","").replace("<","").replace('"',"")
                # ip:port
                ttvals = tvals[0].split(":")
                if len(ttvals) > 2:
                    errlines.append(line)
                    continue
                ip = ttvals[0]
                port = ttvals[1]
                
                tdbconns.append("%s,%s,%s,%s" % (ip, port, dbname,cnffile))
                if not re.search("^172.", ip):
                    logger.warn(tdbconns)
                else:
                    # logger.info(tdbconns)
                    pass
            else:
                errlines.append(line)
                continue
        # 
        elif not cmp(dbtype, DBTYPE_ORACLE):
            # jdbc:oracle:thin:@
            if re.search("jdbc:oracle:thin:@", tline) or re.search("\(PROTOCOL = TCP\)\s*\(HOST", tline):
                tcstr = ""
                if re.search("jdbc:oracle:thin:@", tline) :
                    tvals = tline.split("jdbc:oracle:thin:@")
                    if len(tvals) <= 1:
                        errlines.append(line)
                        continue
                    tcstr = tvals[1]
                elif re.search("\(PROTOCOL = TCP\)\s*\(HOST", tline):
                    tcstr = tline
                #print tcstr
                    
                newAddrs = []
                # (DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = 172.21.100.220)(PORT = 1588))(ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.100.225)(PORT = 1588)))(CONNECT_DATA = (SERVICE_NAME = order.dhgate.com)))">
                # 可能会有多个
                if re.search("\(DESCRIPTION", tvals[1]):
                #if re.search("\(PROTOCOL = TCP\)\s*\(HOST", tcstr):
                    ts = tvals[1]
                    vas = ts.split("CONNECT_DATA")
                    # service name
                    srvName = None
                    if len(vas) >= 2:
                        srvs = vas[1]                        
                        srvs = re.sub("(\(|\)|\=)", " ", srvs)
                        srvs = re.sub("(\"|\>)", "", srvs)
                        srvs = re.sub("\s+", " ", srvs)
                        vsrvs = srvs.split()
                        i = 0
                        while i < len(vsrvs):
                            tv = vsrvs[i].upper()
                            if not cmp("SERVICE_NAME", tv):
                                srvName = vsrvs[i+1]
                                #logger.info(srvName)
                                if re.search("\>$", srvName):
                                    logger.error(line)
                                
                                break
                            i += 1
                    
                    # address 
                    addrs = vas[0].rstrip("(")
                    addrs = re.sub("(\(|\)|\=)", " ", addrs)
                    addrs = re.sub("\s+", " ", addrs)
                    vaddrs = addrs.split()
                    i = 0
                    thost = ""
                    tport = 0
                    while i < len(vaddrs):
                        tv = vaddrs[i].upper()
                        if not cmp("HOST", tv):
                            thost = vaddrs[i+1]
                        elif not cmp("PORT", tv):
                            tport = vaddrs[i+1]
                            if not re.search("\d$", tport):
                                logger.error(line)
                            
                        i += 1
                    newAddrs.append("%s,%s,%s,%s" % (thost, tport, srvName, cnffile))
                        
                # jdbc:oracle:thin:@172.21.100.154:1588:dhdw
                #elif re.search("^1", tcstr):
                elif re.search(".*:1588(:|\/).*", tcstr):
                    ttvals = (tvals[1].split('"'))[0].split(":")
                    ip = ttvals[0]
                    if len(ttvals) == 2:
                        (port, srvName) = ttvals[1].split("/")
                    else:
                        port = ttvals[1]
                        srvName = ttvals[2]
                 
                    newAddrs.append(",".join([ip, port, srvName, cnffile]))
                else:
                    errlines.append(line)
                    continue
                
                for newAddr in newAddrs:
                    tdbconns.append(newAddr)
            else:
                errlines.append(line)
                continue
        # 
        elif not cmp(dbtype, DBTYPE_LILEI):
            tline = tline.replace("\\","")
            
            if re.search("jdbc:postgresql", tline):
                turl = ((tline.split("jdbc:postgresql"))[1].split("?"))[0].lstrip("://")
                tvals = turl.split("/")
                dbname = tvals[1].replace(">","").replace("<","").replace('"',"")
                # ip:port
                ttvals = tvals[0].split(":")
                if len(ttvals) > 2:
                    errlines.append(line)
                    continue
                ip = ttvals[0]
                port = ttvals[1]
                
                tdbconns.append("%s,%s,%s,%s" % (ip, port, dbname,cnffile))
                if not re.search("^172.", ip):
                    logger.warn(tdbconns)
                else:
                    pass
            else:
                errlines.append(line)
                continue
        elif not cmp(dbtype, DBTYPE_KEY):
	    tremark = "%s:%s" % ("/".join(cnffile.split("/")[-3:]), regContent)
	    tremark = "/".join(cnffile.split("/")[-3:])
            tdbconns.append(",0,,%s,%s" % (cnffile, tremark))
            #logger.info("%s, %s, %s" %(henv, happ, cnffile))
        elif not cmp(dbtype, DBTYPE_APP):
            tdbconns.append(",0,,%s" % (cnffile))
            logger.info("%s, %s, %s" %(henv, happ, cnffile))
        else:
	    if cmp('', line):
		errlines.append(line)
        
        # tdbconns
        for tconn in tdbconns:
            if dbconns.has_key(tconn):
                (dbconns.get(tconn))[happ] = 1
            else:
                dbconns[tconn] = {happ:1}
    
    for eline in errlines:
        logger.error(eline)
        
    for turl, apps in dbconns.iteritems():
        tvals = turl.split(",")
        ip = tvals[0]
        port = tvals[1]
        if len(tvals) >= 3:
            dbname = tvals[2]
        else:
            dbname = None
        cnffile = tvals[3]
        if len(tvals) >= 5:
            remark = ",".join(tvals[4:])
        else:
            remark = ''

        #logger.info("%s,%s,%s" % (ip, port, dbname))
        for appName in apps.keys():
            insParam.append([env, dbtype, ip, port, dbname, appName, cnffile, keyword, remark])
    
    return insParam
        
def saveStat(dbtype, keys, insParams):
    if len(insParams) <= 0:
        return
    
    #logger.info(insParams)
    # save
    myconn = getMyPlatConn()
    if myconn == None:
        return
    
    try:
        cur = myconn.cursor()
        
        if not cmp(dbtype, DBTYPE_KEY):
            delSql = "DELETE FROM tools_scan_alto_dbconfig where dbtype='%s' " % (dbtype)
        else:
            delSql = "DELETE FROM tools_scan_alto_dbconfig where dbtype='%s' and find_key in ('%s') " % (dbtype, "','".join(keys))
        cur.execute(delSql)
        
        insSql = '''INSERT INTO tools_scan_alto_dbconfig (env_name, dbtype, host, port, dbname, app_name, cnf, `find_key`,remark) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,%s)'''
        cur.executemany(insSql,insParams)
        
        myconn.commit()
        return True
    except MySQLdb.Error, e:
        logger.error(e)
        myconn.rollback()
    finally:
        myconn.close()
        
    return False

def sub_all(args):
    sub_mysql(args)
    sub_oracle(args)
    #sub_lilei(args)

def sub_mysql(args):
    insParams = []
    dbtype = DBTYPE_MYSQL

    ports = ["3306", "3342", "3388", "3389", "3390", "3391", "3392", "3378"]
    
    for env in envs:
        '''
        if cmp("lgreadonlyproduction", env):
            continue
        '''
        for port in ports:
            insParam = getStatParams(dbtype, port, env)
            if len(insParam) > 0:
                insParams.extend(insParam)
                
    saveStat(dbtype, ports, insParams)
    

def sub_oracle(args):
    insParams = []
    dbtype = DBTYPE_ORACLE
    
    ports = ["1588"]
    for env in envs:
        for host in ports:
            insParam = getStatParams(dbtype, host, env)
            if len(insParam) > 0:
                insParams.extend(insParam)
            
        time.sleep(3)
    saveStat(dbtype, ports, insParams)

def sub_lilei(args):
    insParams = []
    dbtype = DBTYPE_LILEI
    
    ports = ["5432", ]
    for env in envs:
        for host in ports:
            insParam = getStatParams(dbtype, host, env)
            if len(insParam) > 0:
                insParams.extend(insParam)
        
    saveStat(dbtype, ports, insParams)

def sub_key(args):
    insParams = []
    dbtype = DBTYPE_KEY
    keys = args.values.split(SPLITER_FIRST)
    for env in envs:
        for key in keys:
            insParam = getStatParams(dbtype, key, env)
            if len(insParam) > 0:
                insParams.extend(insParam)
            
        time.sleep(3)
    saveStat(dbtype, envs, insParams)

def sub_app(args):
    insParams = []
    dbtype = DBTYPE_APP
     
    keys = args.values.split(SPLITER_FIRST)
    for env in envs:
        for key in keys:
            insParam = getStatParams(dbtype, key, env)
            if len(insParam) > 0:
                insParams.extend(insParam)
            
        time.sleep(3)
    saveStat(dbtype, envs, insParams)

def sub_env(args):
    
    ipstr = "172.16.20.11,172.16.20.12,172.16.20.151,172.16.20.154,172.16.20.157,172.16.20.160,172.16.20.163,172.16.20.164,172.16.20.166,172.16.20.169,172.16.20.172,172.16.20.176,172.16.20.178,172.16.20.182,172.16.20.184,172.16.20.19,172.16.20.192,172.16.20.29,172.16.40.100,172.16.40.204,172.16.40.210,172.16.40.25,172.16.40.26,172.16.40.27,172.16.40.29,172.16.40.35,172.16.40.37,172.16.40.39,172.16.40.58,172.16.40.84,172.16.40.87,172.16.40.89,172.16.40.93,172.16.40.94,172.16.40.95,172.16.41.221,172.16.50.101,172.16.50.102,172.16.50.92,172.16.50.93,172.16.50.94,172.16.50.95,172.16.70.121,172.16.70.122,172.21.130.41,172.21.20.101,172.21.20.24,172.21.20.39,172.21.21.11,172.21.21.111,172.21.21.119,172.21.21.122,172.21.30.147,172.21.30.173,172.21.30.25,172.21.31.11,172.21.31.12,172.21.40.102,172.21.40.113,172.21.40.114,172.21.40.115,172.21.40.116,172.21.40.205,172.21.40.206,172.21.40.80,172.21.40.84,172.21.40.86,172.21.40.88,172.21.41.113,172.21.50.166,172.21.50.43,172.21.71.12"
    tipstr = args.ipstr
    ips = tipstr.split(",")

    oks = []
    errs = []
    for ip in ips:
        qSql = "select ip, envname, group_concat(app_name) from dhdba.tb_tmp_app_deploy where ip='%s' group by ip, envname" % (ip)
        lines = doQuery(connCfg, qSql)
        if len(lines) <= 0:
            errs.append(ip)
        else:
            for line in lines:
                oks.append([ip, line[1], line[2]])
    for ip in oks:
        print "%s" % (",".join(ip))
        
        
    print "Erros:%s" % (",".join(errs))
            
    
def sigint_handler(signum, frame):
    exit(0)

if __name__ == '__main__':
    reload(sys)
    sys.setdefaultencoding("utf-8")

    #
    signal.signal(signal.SIGINT, sigint_handler)
    signal.signal(signal.SIGHUP, sigint_handler)
    signal.signal(signal.SIGTERM, sigint_handler)

    (pathAbs, scName) = os.path.split(os.path.abspath(sys.argv[0]))
    
    #
    SPLITER_FIRST = ","
    DBTYPE_MYSQL = "mysql"
    DBTYPE_ORACLE = "oracle"
    DBTYPE_LILEI = "lilei"
    DBTYPE_KEY = "key"
    DBTYPE_APP = "app"
    #
    parents_parser = argparse.ArgumentParser(add_help=False)
    parents_parser.add_argument("-d","--debug", dest="debug", action='store_true', help="print debug info")
    parents_parser.add_argument("-l","--logfile", dest="logFile", help=u"日志文件,默认在终端输出")
    parents_parser.add_argument("-o","--outfile", dest="outFile", help=u"导出内容到文件")

    
    #
    rcHost = '172.21.200.21'
    platHost = '172.21.100.200'
    sdesc = u'''depend http://%s/find_in_conf.php. save %s:3388/camelbell tools_scan_alto_dbconfig''' % (rcHost, platHost)
    parser = argparse.ArgumentParser(description=sdesc)
    subparsers = parser.add_subparsers()

    # all
    all_parser = subparsers.add_parser('all', parents=[parents_parser], help="")
    all_parser.set_defaults(func=sub_all)
    
    # mysql
    mysql_parser = subparsers.add_parser('mysql', parents=[parents_parser], help="")
    mysql_parser.set_defaults(func=sub_mysql)
    
    # oracle
    oracle_parser = subparsers.add_parser('oracle', parents=[parents_parser], help="")
    oracle_parser.set_defaults(func=sub_oracle)
    
    # lilei
    lilei_parser = subparsers.add_parser('lilei', parents=[parents_parser], help="")
    lilei_parser.set_defaults(func=sub_lilei)
    
    # key
    key_parser = subparsers.add_parser('key', parents=[parents_parser], help="")
    key_parser.add_argument("-v","--vals", dest="values", required=True, help=u"SPLIT BY '%s'" % (SPLITER_FIRST) )
    key_parser.add_argument("-e","--env", dest="env", required=True, help=u"")
    key_parser.set_defaults(func=sub_key)
    
    # app  host env
    env_parser = subparsers.add_parser('env', parents=[parents_parser], help="")
    env_parser.add_argument("-s","--ips", dest="ipstr", required=True, help=u"" )
    env_parser.set_defaults(func=sub_env)
    
    # app
    key_parser = subparsers.add_parser('app', parents=[parents_parser], help="")
    key_parser.add_argument("-v","--vals", dest="values", required=True, help=u"SPLIT BY '%s'" % (SPLITER_FIRST) )
    key_parser.set_defaults(func=sub_app)
    
    #
    args = parser.parse_args()

    
    connPlat = getMyPlatConn()
    if connPlat == None:
        exit(1)
        
    connCfg = getMyCfgConn()
    if connCfg == None:
        exit(1)
    
    env_ag = args.env
    env_all = getEnvs()
    if not cmp("ALL", env_ag):
	envs = env_all
    elif env_all.count(env_ag) > 0:
	envs = [env_ag,]
    else:
	logger.error("env :%s" % (env_ag))
	exit(1)
    logger.warn("env %s" % (envs))
    
    #
    args.func(args)
    
    exit(0)
    

