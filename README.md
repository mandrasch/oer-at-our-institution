Beware: Work in progress

2DO: USE BOILERPLATE, e.g. https://wppb.me/

# OERbox for wordpress
Experimental wordpress plugin - add custom fields for OER metadata for regular posts and pages via https://metabox.io, fields will be added to HTML head in schema.org format (machine readable information)

1. Install wordpress
2. Install and activate https://wordpress.org/plugins/meta-box/
3. Copy this repo to /wp-content/plugins/
4. Install und activate oerbox-Plugin
5. Create new page/post, metadata-box is below page/post content

Approach: 1 URL = 1 OER (creative work, contains descriptions and multiple media objects)

## User roles

This plugin does not support "author" roles right now, every author should be an "editor", otherwise the hacks won't work.

- editor: Professor, Student
- admin: Technical staff, Persons in charge (they will allow publishing)

Goals:
- :heavy_check_mark: add license URL to "link rel="license""-tag in HTML head
- :o: Add schema.org LD-JSON to HTML head in a compliant way (see source code of https://www.oerbw.de/edu-sharing/components/render/eb6f6159-021e-4985-8aaa-4d8a36e9b6a2)
-  :o: Combine with custom types of http://pods.io to create a flexible mini-OER-repository for institutions

![Alt text](screenshot1.png)
![Alt text](screenshot2.png)

Projects I have in mind creating this:
- https://oer.uni-leipzig.de
- https://bridge.nrw/index.php/betriebswirtschaftslehre/

Inspired by https://oervz.openbiblio.eu/ and http://blog.lobid.org/2019/05/17/skohub.html and https://hoou.de
