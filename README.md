# c-wikigame
Returns a link to a random, popular, or specific Wikipedia article and links contained in the article. Sort of based on ["Six Degrees of Kevin Bacon"](https://en.wikipedia.org/wiki/Six_Degrees_of_Kevin_Bacon) but with Wikipedia articles instead of people.
## Usage
### Begin game with links
Get random starting article and a random goal article. The starting article in this case is `Walter Sullivan (novelist)` and the goal article is `Enjoy It While It Lasts`. The 25 links are links to articles appearing in `Walter Sullivan (novelist)`.
```php
<?php
include_once 'c-wikigame.php';

$wikigame = new wikigame();

$wikigame->get_start(25, 'rand', '');
```
```
[["Walter Sullivan (novelist)","Enjoy It While It Lasts"],["https:\/\/en.wikipedia.org\/wiki\/Walter_Sullivan_(novelist)","https:\/\/en.wikipedia.org\/wiki\/Enjoy It While It Lasts"],["Nashville, Tennessee","Southern United States","novelist","literary criticism","Vanderbilt University","Fellowship of Southern Writers","Donald Davidson (poet)","United States Marine Corps","World War II","Iowa City, Iowa","Master of Fine Arts","University of Iowa","Andrew Nelson Lytle","Samuel F. Pickering, Jr.","Sewanee Review","Episcopal Church (United States)","Prayer Book Society of the USA","Catholic Church","Edwin M. Yoder Jr.","Library of Congress"]]
```
### Check a given link
Check to see if a given article appears in another article. In this case, the goal article is `Geroge Washington` and the random artile is `United States`. Since `United States` appears in `George Washington` a notice is displayed. If the random article did not appear in the goal article then an output similar to the one above would display.
```php
<?php
include_once 'c-wikigame.php';

$wikigame = new wikigame();

$wikigame->check(25, 'United States', 'George Washington');
```
```
["found_article"]
```
