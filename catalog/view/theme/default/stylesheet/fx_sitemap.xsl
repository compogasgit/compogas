<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes" />
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>#FX Sitemap⁴</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<style type="text/css">
					table{
						width: 100%;
					}
				
					body {
						font-size:12px;
						font-family: arial;
						background: #607d8b;
					}
					
					td {
						font-size:12px;
					}
					
					th {
						text-align:left;
						padding-right:30px;
						font-size:12px;
					}
					
					a {
						color:#444;
						text-decoration: none;
						border-bottom: 1px dotted;
					}
					
					table {
						border: 1px solid #69c;
						background: #f8f8f8;
					}
					th {
					  color: #607d8b;
					  border-bottom: 1px dashed #69c;
					  padding: 12px 17px;
					  font-size:14px;
					  font-weight: 600
					}
					td {
					  color: #669;
					  padding: 7px 17px;
					}
					tr:hover td {background: #ccddff;}

					h1, h2 {display: inline}
					h2{ font-size: 25px; color: #eee}
					h1{ font-size: 30px; color: #fb5}
					h1 b, h2 b, .powered a p {color:#29D!important; display:inline}
					h1 span, h2 span, .powered a span{color:#FB5151}
				</style>
			</head>
			<body>
				<xsl:apply-templates></xsl:apply-templates>
				<div id="footer">
				</div>
			</body>
		</html>
	</xsl:template>
	
	
	<xsl:template match="sitemap:urlset">
        <h2>#<b>F</b><span>X</span> Sitemap</h2><h1>⁴</h1>
		<div id="content">
			<table cellpadding="5">
				<tr style="border-bottom:1px black solid;">
					<th>loc</th>
					<th>lastmod</th>
					<th>priority</th>
					<th>changefreq</th>
				</tr>
				<xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
				<xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
				<xsl:for-each select="./sitemap:url">
					<tr>
						<td>
							<xsl:variable name="itemURL">
								<xsl:value-of select="sitemap:loc"/>
							</xsl:variable>
							<a href="{$itemURL}">
								<xsl:value-of select="sitemap:loc"/>
							</a>
						</td>
						<td>
							<xsl:value-of select="sitemap:lastmod"/>
						</td>
						<td>
							<xsl:value-of select="sitemap:priority"/>
						</td>
						<td>
							<xsl:value-of select="sitemap:changefreq"/>
						</td>
					</tr>
				</xsl:for-each>
			</table>
		</div>
	</xsl:template>
	
	
	<xsl:template match="sitemap:sitemapindex">
        <h2>#<b>F</b><span>X</span> Sitemap</h2><h1>⁴</h1>
		<div id="content">
			<table cellpadding="5">
				<tr style="border-bottom:1px black solid;">
					<th>loc</th>
				</tr>
				<xsl:for-each select="./sitemap:sitemap">
					<tr>
						<td>
							<xsl:variable name="itemURL">
								<xsl:value-of select="sitemap:loc"/>
							</xsl:variable>
							<a href="{$itemURL}">
								<xsl:value-of select="sitemap:loc"/>
							</a>
						</td>
					</tr>
				</xsl:for-each>
			</table>
		</div>
	</xsl:template>
</xsl:stylesheet>