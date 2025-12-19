import os
import time
import random
import asyncio
from collections import deque
from urllib.parse import urlparse, urljoin

import mariadb
import mariadb_conf
from urllib.robotparser import RobotFileParser

from selenium import webdriver
from selenium.webdriver.firefox.options import Options

# ==============================================================
# 🐾 WoofSearchBot - Firefox-loving edition (multi-tab optimized)
# ==============================================================

class WoofSearchBot:
    def __init__(self, start_url="http://woofsearch.localhost/spider.php", max_tabs=3):
        print("🐾 WoofSearchBot (Firefox-loving edition) starting up...")

        self.max_tabs = max_tabs
        self.visited_urls = set()
        self.url_queue = deque([start_url])
        self.robots_cache = {}
        self.profile=webdriver.FirefoxProfile()

        # ----- Firefox setup -----
        self.driver_path = os.path.join(os.path.dirname(__file__), "geckodriver")
        self.options = Options()
        self.options.profile=self.profile
        self.options.set_preference("general.useragent.override", "WoofSearchBot/0.0.3 (Firefox-loving edition)")
        self.driver = webdriver.Firefox(
            options=self.options,
        )

        self.profile.set_preference("privacy.trackingprotection.enabled", True)
        self.profile.set_preference("privacy.trackingprotection.pbmode.enabled", True)
        self.profile.set_preference("privacy.trackingprotection.socialtracking.enabled", True)
        self.profile.set_preference("privacy.trackingprotection.cryptomining.enabled", True)
        self.profile.set_preference("privacy.trackingprotection.fingerprinting.enabled", True)

        # ----- MariaDB setup -----
        self.db = mariadb.connect(
            user=mariadb_conf.user,
            password=mariadb_conf.password,
            host=mariadb_conf.host,
            database=mariadb_conf.database
        )
        self.cursor = self.db.cursor()

    # -----------------------------------------
    # robots.txt check
    # -----------------------------------------
    def is_allowed(self, url: str):
        parsed = urlparse(url)
        domain = parsed.netloc

        blocked_domains = [
            "youtube.com",
            "www.youtube.com",
            "twitch.tv",
            "www.twitch.tv",
            "twitter.com",
            "www.twitter.com",
            "x.com",
            "www.x.com",
            "tiktok.com",
            "www.tiktok.com"
        ]

        if domain not in self.robots_cache:
            robots_url = f"{parsed.scheme}://{domain}/robots.txt"
            rp = RobotFileParser()
            rp.set_url(robots_url)
            try:
                rp.read()
                self.robots_cache[domain] = rp
            except Exception as e:
                print(f"⚠️ Could not read {robots_url}: {e}")
                self.robots_cache[domain] = None

        rp = self.robots_cache.get(domain)
        if not rp:
            return True
        return rp.can_fetch("WoofSearchBot/0.0.3", url) and not domain in blocked_domains

    # -----------------------------------------
    # page processing
    # -----------------------------------------
    def save_page(self, url):
        try:
            self.cursor.execute(
                "INSERT IGNORE INTO pages (title, url, content) VALUES (?, ?, ?)",
                (self.driver.title, url, self.driver.page_source)
            )
            self.db.commit()
        except Exception as e:
            print(f"⚠️ DB insert failed for {url}: {e}")

    def extract_links(self, base_url):
        links = []
        for a in self.driver.find_elements("tag name", "a"):
            href = a.get_attribute("href")
            if href:
                abs_url = urljoin(base_url, href)
                if abs_url.startswith("http"):
                    links.append(abs_url)
        return links

    # -----------------------------------------
    # multi-tab crawling
    # -----------------------------------------
    async def crawl(self):
        tasks = []
        for _ in range(self.max_tabs):
            task = asyncio.create_task(self.crawl_tab())
            tasks.append(task)
        await asyncio.gather(*tasks)

    async def crawl_tab(self):
        while self.url_queue:
            url = None
            # Dequeue safely
            if self.url_queue:
                url = self.url_queue.popleft()
            if not url:
                await asyncio.sleep(1)
                continue

            if url in self.visited_urls or not self.is_allowed(url):
                continue

            try:
                self.visited_urls.add(url)
                print(f"🦊 Visiting {url}")
                self.driver.execute_script(f"window.open('{url}', '_blank');")
                self.driver.switch_to.window(self.driver.window_handles[-1])

                time.sleep(random.uniform(1.5, 3.0))  # polite delay
                self.save_page(url)

                links = self.extract_links(url)
                for link in links:
                    if link not in self.visited_urls:
                        self.url_queue.append(link)
                        try:
                            self.cursor.execute("INSERT INTO links (from_url, to_url) VALUES (?, ?)", (url, link))
                        except:
                            pass

                self.db.commit()
                self.driver.close()
                self.driver.switch_to.window(self.driver.window_handles[0])

            except Exception as e:
                print(f"❌ Error visiting {url}: {e}")
                try:
                    self.driver.switch_to.window(self.driver.window_handles[0])
                except Exception:
                    pass

    def shutdown(self):
        print("🛑 Shutting down WoofSearchBot...")
        try:
            self.driver.quit()
        except:
            pass
        self.cursor.close()
        self.db.close()


# ==============================================================
# 🔧 Run it
# ==============================================================

if __name__ == "__main__":
    bot = WoofSearchBot(max_tabs=10)
    try:
        asyncio.run(bot.crawl())
    finally:
        bot.shutdown()
