# -*- coding: utf-8 -*-
#/home/angry_antelope/BackEndV4/bin/python3.8-
import scrapy
import sys
from bs4 import BeautifulSoup
import random
from scrapy.crawler import CrawlerProcess
from powerData import Spider_PowerData
from bodyData import Spider_BodyData
from weakData import Spider_WeakData

# This file is responsible for running multiple spiders in the same CrawlProceess, in order to organize code better,
# and enhance performance.

custom_settings = {
		"RETRY_TIMES":10,
		"RETRY_HTTP_CODES":[500, 503, 504, 400, 403, 404, 408],
		"DOWNLOADER_MIDDLEWARES": {
			'scrapy.downloadermiddlewares.retry.RetryMiddleware': 90,
                        'scrapy_proxies.RandomProxy': 100,
			'scrapy.downloadermiddlewares.httpproxy.HttpProxyMiddleware': 110,
                        'scrapy_fake_useragent.middleware.RandomUserAgentMiddleware': 400,
			'scrapy.downloadermiddlewares.useragent.UserAgentMiddleware': None,
			},
		"PROXY_LIST":'/home/angry_antelope/BackEndV4/proxyList1.txt',
		"PROXY_MODE":0,
		"RANDOM_UA_PER_PROXY":True,
		"FAKEUSERAGENT_FALLBACK":"Mozilla",
		"LOG_ENABLED":0
                #'MEMUSAGE_ENABLED':1,
                #'MEMUSAGE_LIMIT_MB':2048
		}

#I want to ensure that this spider executes to completion before moving on to a new spider
if __name__ == "__main__":
	# since we aren't executing from the commandline, we have to import all of our settings explicitly
	process = CrawlerProcess(custom_settings);
	
	power = Spider_PowerData();
	body = Spider_BodyData();
	weak = Spider_WeakData();

	process.crawl(Spider_PowerData);
	process.crawl(Spider_BodyData);
	process.crawl(Spider_WeakData);	

	# This runs all the spiders at the same time, and it wil terminate when all the spiders have finished running.  This insures we have gotten data from all spiders
	process.start();

