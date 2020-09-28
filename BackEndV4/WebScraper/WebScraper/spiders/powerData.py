#/home/angry_antelope/BackEndV4/bin/python3.8
# coding: utf-8
import scrapy
from scrapy import Spider
from scrapy import signals
import sys
from bs4 import BeautifulSoup
import random
import json
import html

# Dana Thompson
# dtdthomp54@gmail.com
# This spider is responsible for retrieving the super power name, desc, and limitations

class Spider_PowerData(Spider):
        name = "powerData";
        globalResult = {"Power":"None"};

        # Sets up signals that execute spider functions when triggered, in this case, spider_closed
        @classmethod
        def from_crawler(cls, crawler, *args, **kwargs):
            spider = super(Spider_PowerData, cls).from_crawler(crawler, *args, **kwargs);
            crawler.signals.connect(spider.spider_closed, signals.spider_closed);
            return spider;

        def start_requests(self):
            urls = ['http://powerlisting.fandom.com/wiki/Special:Random']
            for url in urls:
                yield scrapy.Request(url=url, callback=self.parse)

        def parse(self, response):
            limit = self.parseLimit(response.body);
            desc = self.parsePower(response.body);
            special = self.parseSpecialCond(response);
            resultUrl = response.request.url;
            urlSplit = resultUrl.split("/");			

            # Setup the proper json object so it can be turned into a string
            self.globalResult = {"Power": {
                "Name": urlSplit[len(urlSplit) - 1].replace("_", " "),
                "Desc": desc,
                "Limit": limit,
                "Special": special
            }};
		
        # Only prints the final result if the spider ran successfully.
        # Won't indicate if data was unnsuccesfully extracted
        def spider_closed(self, reason):
            if reason == "finished":
                print(json.dumps(self.globalResult));


        # Parses the html body of the super power site.  Gets ability name and description.
        def parsePower(self, response):
            soup = BeautifulSoup(response, 'html.parser');
            capabilities = soup.find(id="Capabilities");
            descHtml = capabilities.find_next("p")
            desc = html.unescape(descHtml.get_text());
		
            return desc.replace("\n", "");

        # Checks to see if there are Limitations to print, and if there are, return a random one
        def parseLimit(self, response):
            soup = BeautifulSoup(response, 'html.parser');
	
            if soup.find(id="Limitations"):
                limit = soup.find(id="Limitations");
                descHtml = limit.find_next("ul");
                limitList = descHtml.findAll("li");

                weakness = html.unescape(random.choice(limitList).get_text());
                return weakness.replace("\n", "");
            else:
                return "None";
			
        # Checks if the power needs a special flag, like "SMART POWERS" won't need a range
        def parseSpecialCond(self, response):
            soup = BeautifulSoup(response.body, 'html.parser');
            resultUrl = response.request.url;
            urlSplit = resultUrl.split("/");		

            if soup.find("li", attrs={"data-name":"Enhancements"}):
                return "No Range";
            elif "Physiology" in urlSplit[len(urlSplit) - 1]:
                return "No Range,body"
            elif soup.find("li", attrs={"data-name":"Smart Powers"}):
                return "No Range,activity"
            else:
                return "None"
