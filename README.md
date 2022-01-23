# Note jotter

Note jotter is a minimalist note-taking tool written in PHP. Note jotter stores content is in a plain text file. The editor create a backup copy of the text file on each save.

## Dependencies

- PHP 7.x or higher

# Installation and usage

To run Note jotter locally, run the `php -S localhost:8000` command and point your browser to _localhost:8000_

To deploy Note jotter on a web server, move the *note-jotter* directory to the document root of the server. Point your browser to *127.0.0.1/note-jotter* (replace *127.0.0.1* with the actual IP address or domain name of your server).

# Bookmarklet

```javascript
javascript:var snippet = prompt('Snippet'); location.href='http://127.0.0.1/index.php?snippet='+escape(snippet)+'&url='+encodeURIComponent(location.href)+'&password=password'
```
