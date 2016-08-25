#!//bin/env python
#coding:utf-8
'''
Created on 2016年7月13日

@author: guoqi
'''
import os, sys, time, signal
from datetime import datetime
import logging
import MySQLdb
from multiprocessing import Process;
import argparse
import threading
logger = logging.getLogger()
logger.setLevel(logging.INFO)
logft = logging.Formatter('[%(asctime)s - %(levelname)s] %(message)s')

SPLIT_SUM = 64
DB_SUM = 4


class tableThread(threading.Thread):
    def __init__(self, splitNo, tbName, strCol, strWhere, dataSource='rs'):
        threading.Thread.__init__(self)
        self.splitNo = splitNo
        self.dbNo = self.splitNo/SPLIT_SUM+1
        self.tbNo = str(hex(splitNo)[2:]).zfill(2)
        self.tbName = "%s_%s" % (tbName, self.tbNo)
        self.qSql = "select %s from %s %s ;" % (strCol, self.tbName, strWhere)
        self.lines = None
	self.dataSource = dataSource

    def getMyConn(self):
	if not cmp('ro', self.dataSource):
	    ip = "10.21.100.14%s" % (self.dbNo)
	elif not cmp('rw', self.dataSource):
	    ip = "10.21.100.6%s" % (self.dbNo)
	else:
	    ip = "10.21.100.7%s" % (self.dbNo)

        db = "prodb0%s" % (self.dbNo)
        port = 3388
        user = "dbread"
        passwd = "dbread_(234)"
        try:
            myConn = MySQLdb.connect(host=ip, port=port, user=user, passwd=passwd, db=db, charset="utf8")
            return myConn
        except MySQLdb.Error, e:
            logger.error("can't get  connection %s" % (e))
        finally:
            pass
                
        return None
    
    def getTbName(self):
        return self.tbName
    
    def getQSql(self):
        return self.qSql
    
    def getLines(self):
        return self.lines
    
    def run(self):
        logger.info("tableThread begin %s, %s" % (self.tbName, self.qSql))
	
        conn =  self.getMyConn()
        if conn != None:
            try:
                cursor = conn.cursor()
                cursor.execute(self.qSql)
                lines = cursor.fetchall()
                self.lines = lines
            except MySQLdb.Error, e:
                self.lines = None
                logger.error(e)
            finally:
                conn.close()
        else:
            logger.error("Connect error")

	cnt = -1
	if self.lines != None:
	    cnt = len(self.lines)
        logger.info("tableThread end %s, %s" % (self.tbName, cnt))

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
 
def scan_product_split(dbNo, args):
    begindt = datetime.now()
    thrds = []
    for i in range(0, SPLIT_SUM):
        splitNo = dbNo*SPLIT_SUM + i
	# data_source not open
        thrd = tableThread(splitNo, args.table, args.scol, args.swhere)
        thrd.setDaemon(True)
        thrds.append(thrd)
    
    next_start_index = 0
    while next_start_index<SPLIT_SUM:
        alive_thread = 0
        for i in range(0, SPLIT_SUM):
            curThrd = thrds[i]
            if curThrd.isAlive():
                alive_thread += 1
            else:
                if next_start_index<=i :
                    if alive_thread < args.parallels:
                        curThrd.start()
                        next_start_index = i+1
                        alive_thread += 1
                        break
                    else:
                        logger.info("next run %s " % (curThrd.getTbName()))
                        break
                else:
                    continue
        
        time.sleep(args.sleep/1000.0)

    time.sleep(1)
    # save
    platConn = getMyPlatConn()
    if platConn != None:
	try:
	    cur_plat = platConn.cursor()

	    # 
	    delSql = "DELETE FROM tools_scan_product_split where uniq_id='%s' " % (args.uniq_id)
	    cur_plat.execute(delSql)

	    insParams = []
	    for i in range(0, SPLIT_SUM):
		curThrd = thrds[i]
		curTableName =  curThrd.getTbName()
		lines =  curThrd.getLines()
		if lines != None and len(lines) > 0:
		    for line in lines:
			if len(line) <= 0:
			    continue
			(productid, itemcode, supplierid) = line
			insParams.append([args.uniq_id, curTableName, productid, itemcode, supplierid])
	    if len(insParams) > 0:
		insSql = '''INSERT INTO tools_scan_product_split (uniq_id, tb_name, productid, itemcode, supplierid) VALUES (%s, %s, %s, %s, %s)'''
		logger.info("insert %s " % (insParams))
		cur_plat.executemany(insSql,insParams)
	    platConn.commit()
	    logger.info(" %s commit " % (dbNo))
	except MySQLdb.Error, e:
	    logger.error(e)
	    platConn.rollback()
	finally:
	    platConn.close()
        
    time.sleep(1)
    enddt = datetime.now()
    logger.warn(" Finish %s, %s-%s" % (dbNo, begindt, enddt))
    
def sigint_handler(signum, frame):
    exit(0)

if __name__=='__main__':
    reload(sys)
    sys.setdefaultencoding("utf-8")

    (pathAbs, scName) = os.path.split(os.path.abspath(sys.argv[0]))
    
    #
    signal.signal(signal.SIGINT, sigint_handler)
    signal.signal(signal.SIGHUP, sigint_handler)
    signal.signal(signal.SIGTERM, sigint_handler)
    
    DEFAULT_SLEEP_MS = 1000
    DEFAULT_PARALLELS = 8
    DEFAULT_DATASOURCE = 'rs'
    DEFAULT_COLUMNS = u" productid, itemcode, supplierid "
    DEFAULT_OUTFILE = u"/tmp/scan_product_split.log"
    sdesc = u'''并行扫描所有商品分片表。每个分片库一个进程，每个线程对应1个分片表 '''
    parser = argparse.ArgumentParser(description=sdesc)
    parser.add_argument("-s","--sleep", dest="sleep", type=int, default=DEFAULT_SLEEP_MS , help=u"sleep $N ms. default '%s'ms" % (DEFAULT_SLEEP_MS))
    parser.add_argument("-o","--out-file", dest="outfile", default=DEFAULT_OUTFILE, help=u"logfile. default '%s'" % (DEFAULT_OUTFILE))
    parser.add_argument("-c","--columns", dest="scol", default=DEFAULT_COLUMNS , help=u"default '%s'" % (DEFAULT_COLUMNS))
    parser.add_argument("-p","--parallels", dest="parallels", type=int, default=DEFAULT_PARALLELS, help=u"default '%s'" % (DEFAULT_PARALLELS))
    parser.add_argument("-w","--where", dest="swhere", required=True, help=u"必须是where开头 " )
    parser.add_argument("-t","--table", dest="table", required=True, help=u"不含分片号的表名" )
    parser.add_argument("-u","--unique-id", dest="uniq_id", required=True, help=u"UNIQUE id" )
    parser.add_argument("-d","--data-source", dest="data_source", default=DEFAULT_DATASOURCE, choices=['rw','ro','rs'], help=u"" )
    
    args = parser.parse_args()

    logfile = args.outfile
    #logfile = '/dev/null'
    fh = logging.FileHandler(logfile)
    fh.setFormatter(logft)
    logger.addHandler(fh)

    begindt = datetime.now()
    plist = []
    for i in range(0, DB_SUM):
        p = Process(target = scan_product_split, args=(i, args))
        p.start()
        plist.append(p)

    is_exit = False
    while not is_exit:
        is_exit = True
        for p in plist:
            if p.is_alive():
                is_exit = False
                break
        time.sleep(1)
        
    
    time.sleep(1)
    enddt = datetime.now()
    logger.warn("Finish : %s - %s" % (begindt, enddt))
    exit(0)
    
    
