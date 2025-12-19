USE woofsearch;

-- Simple keyword search procedure
DELIMITER $$

CREATE PROCEDURE search_pages(IN query TEXT)
BEGIN
  SELECT
    title,
    url,
    MATCH(content, title) AGAINST(query IN NATURAL LANGUAGE MODE) AS relevance
  FROM pages
  WHERE MATCH(content, title) AGAINST(query IN NATURAL LANGUAGE MODE)
  ORDER BY relevance DESC
  LIMIT 50;
END $$

DELIMITER ;
