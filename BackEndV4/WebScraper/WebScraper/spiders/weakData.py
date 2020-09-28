#/home/angry_antelope/BackEndV4/bin/python3.8
# coding: utf-8
import scrapy
from scrapy import signals
from scrapy import Spider
import sys
from bs4 import BeautifulSoup
import random
import string
import json
import html

# Dana Thompson
# dtdthomp54@gmail.com
# This spider is responsible for gettings a random medical condition to use as a weakness for the power

class Spider_WeakData(Spider):
	name = "weakData";
	globalResult = {"Weak":"None"};

	# Allows our spider_closed function to be called once the spider sends a "spider_closed" signal
	@classmethod
	def from_crawler(cls, crawler, *args, **kwargs):
		spider = super(Spider_WeakData, cls).from_crawler(crawler, *args, **kwargs);
		crawler.signals.connect(spider.spider_closed, signals.spider_closed);
		return spider;

	# The symptoms are listed based on whatever letter of the alphabet is in the url
	def start_requests(self):
		rLetterLower = random.choice(string.ascii_lowercase);
		url = "https://www.medicinenet.com/symptoms_and_signs/alpha_" + rLetterLower + ".htm"
		yield scrapy.Request(url=url, callback=self.parse);
		
	def parse(self, response):
		weak, weakUrl = self.parseBody(response.body);
		self.globalResult = {"Weak": {
					"Desc": weak,
					"URL": weakUrl
				}};
	
	# Only prints the final result if the spider ran successfully.
	# Won't indicate if data was unnsuccesfully extracted
	def spider_closed(self, reason):
		if reason == "finished":
			print(json.dumps(self.globalResult));
	
	#Parses the html body of the random symptom site.
	def parseBody(self, response):
		soup = BeautifulSoup(response, 'html.parser');
		container = soup.find(id="AZ_container");
		listDiv = container.find("ul");
		weakList = listDiv.findAll("li");

		# There's the sympptom and its corresponding url to show details of the symptom
		result = random.choice(weakList);
		weakHref = result.find('a', href=True);
		weakUrl = weakHref['href'];
		weak = html.unescape(result.get_text());
		weak = weak.replace("Symptoms and Signs", "");
		return weak, weakUrl;
