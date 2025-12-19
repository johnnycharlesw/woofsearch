import selenium
import selenium.webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.firefox.firefox_profile import FirefoxProfile
import os
import subprocess
import sys
import asyncio
import aiohttp
import mariadb
import mariadb_conf

from collections import deque



from urllib.robotparser import RobotFileParser
from urllib.parse import urljoin

# Create a parser
rp = RobotFileParser()

# Point it to the robots.txt URL
rp.set_url("https://example.com/robots.txt")

# Fetch and read the file
rp.read()


# WoofSearchBot - Firefox-loving edition
class WoofSearchBot(selenium.webdriver.Firefox):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.visited_urls = set()
        self._mariadb=mariadb.Connection(
            user=mariadb_conf.user,
            password=mariadb_conf.password,
            host=mariadb_conf.host,
            database=mariadb_conf.database
        )
        self.url_queue = deque()
        start_url = "http://woofsearch.localhost/spider.php"
        self.url_queue.append(start_url)
        self.mariadb=mariadb.Cursor(self._mariadb)
        self.driver_path = os.path.join(os.path.dirname(__file__), "geckodriver")
        self.options = selenium.webdriver.FirefoxOptions()
        self.options.add_argument("--disable-gpu")
        self.options.add_argument("--disable-dev-shm-usage")
        self.options.add_argument("--disable-accelerated-2d-canvas")
        self.options.add_argument("--disable-software-rasterization")
        self.options.add_argument("--disable-gpu-rasterization")
        self.options.add_argument("--disable-software-rendering")
        self.options.add_argument("--disable-accelerated-compositing")
        self.options.add_argument("--disable-gpu-compositing")
        self.options.add_argument("--disable-accelerated-3d-apis")
        self.options.add_argument("--disable-gpu-3d-apis")
        self.options.add_argument("--disable-accelerated-video")
        self.options.add_argument("--disable-gpu-video")
        self.options.add_argument("--disable-accelerated-webgl")
        self.options.add_argument("--disable-gpu-webgl")
        self.options.add_argument("--user-agent=WoofSearchBot/0.0.2")

    # Start crawling
    def crawl(self):
        while self.url_queue:
            url = self.url_queue.popleft()
            # Check robots.txt

            # Check if URL is allowed
            if not self.is_allowed(url):
                continue
            if url in self.visited_urls:
                continue
            self.visited_urls.add(url)
            # Print the URL being visited
            print(f"Visiting: {url}")

            # Navigate to it in Firefox
            self.view_page_in_firefox(url)

            links = [a.get_attribute("href") for a in self.find_elements("tag name", "a")]
            for link in links:
                if link and link.startswith("http") and link not in self.visited_urls:
                    self.url_queue.append(link)

    def is_allowed(self,url):
        # Check robots.txt
        self._is_allowed(url)
        return rp.can_fetch("WoofSearchBot/0.0.2", url)
    
    # def _is_allowed(self,url):
    #     from urllib.parse import urlparse, urljoin

    #     parsed = urlparse(url)
    #     root = f"{parsed.scheme}://{parsed.netloc}"  # "https://example.com"
    #     robots_url = urljoin(root, "/robots.txt")   # "https://example.com/robots.txt"

    #     print(robots_url)

    #     rp.set_url(robots_url)
    #     rp.read()

    async def _is_allowed(self, url):
        """
        Fetch and parse robots.txt asynchronously for the domain of `url`.
        Caches the parser per domain.
        Returns a RobotFileParser or None if fetch failed.
        """
        parsed = urlparse(url)
        domain = parsed.netloc

        # Return cached parser if available
        if domain in self.robots_cache:
            return self.robots_cache[domain]

        robots_url = urlunparse((parsed.scheme, domain, "/robots.txt", "", "", ""))
        rp = RobotFileParser()
        rp.set_url(robots_url)

        try:
            async with aiohttp.ClientSession() as session:
                async with session.get(robots_url) as resp:
                    if resp.status == 200:
                        text = await resp.text()
                        rp.parse(text.splitlines())
                    else:
                        print(f"robots.txt not found for {domain}, assuming allowed.")
                        rp = None
        except Exception as e:
            print(f"Failed to fetch {robots_url}: {e}. Assuming allowed.")
            rp = None

        self.robots_cache[domain] = rp
        return rp

    def view_page_in_firefox(self, url):
        # Open the page
        self.get(url)
        self.save_page(url)
        self.save_links(url)

    def save_page(self,url):
        # Save the page to MariaDB
        self.mariadb.execute(
            "INSERT IGNORE INTO pages (title, url, content) VALUES (?, ?, ?)",
            (self.title, url, self.page_source)
        )
        self._mariadb.commit()

    def save_links(self,url):
        # Save the links to MariaDB
        for link in self.find_elements("tag name", "a"):
            absolute_link = urljoin(url, link.get_attribute("href"))
            if absolute_link.startswith("http") and absolute_link not in self.visited_urls:
                self.url_queue.append(absolute_link)
                self.mariadb.execute(
                    "INSERT INTO links (from_url, to_url) VALUES (?, ?)",
                    (url, absolute_link)
                )
                self._mariadb.commit()

    def give_woofsearchbot_its_own_profile():
        # Create a new profile
        profile = webdriver.FirefoxProfile()
        profile.set_preference("general.useragent.override", "WoofSearchBot/0.0.2")
        profile.set_preference("network.proxy.type", 0)
        profile.set_preference("network.proxy.http", "")
        profile.set_preference("network.proxy.http_port", 0)
        profile.set_preference("network.proxy.https", "")
        profile.set_preference("network.proxy.https_port", 0)
        profile.set_preference("network.proxy.ssl", "")
        profile.set_preference("network.proxy.ssl_port", 0)
        profile.set_preference("network.proxy.no_proxies", "")
        profile.set_preference("network.proxy.autoconfig_url", "")
        profile.set_preference("network.proxy.autoconfig_script", "")
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        profile.set_preference("network.proxy.autoconfig_flags", 0)
        
if __name__ == "__main__":
    bot = WoofSearchBot()
    bot.crawl()