# Readme

## Installing
Assuming you are not using something akin to MVC, you be able to place the calendar folder 
somewhere in within the root of your site, even in another sub folder.

You should be able to access the calendar at
calendar/

So, for example, if you install the calendar folder into the root of your site,
then it would be accessible as: https://mysite.com/calendar

## Database
Your database credentials need to be added to in src/db.php.
Open it up and you will see something like:

``` 
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "calendar";
```
Simply replace each value, between quotes, with your own value.


The code base is based off of the field names provided in your SQL.
i.e.:
CREATE TABLE `events` (
`id` int(11) NOT NULL,
`title` varchar(255) NOT NULL,
`event_start` datetime NOT NULL,
`event_end` datetime DEFAULT NULL
)

You need at least these four columns as named, in order for the calendar script to work.

## Adding new categories and colors
To add new categories and colors, open up the index.php file 
and locate these lines:

``` 
<select id="eventCategory" class="form-select">
    <option value="category1" data-color="#ff0000">Category 1</option>
    <option value="category2" data-color="#00ff00">Category 2</option>
    <option value="category3" data-color="#0000ff">Category 3</option>
</select>
```

If you want to add a new line, just duplicate the last option
and change it to say what you want.

``` 
<select id="eventCategory" class="form-select">
    <option value="category1" data-color="#ff0000">Category 1</option>
    <option value="category2" data-color="#00ff00">Category 2</option>
    <option value="category3" data-color="#0000ff">Category 3</option>
    <option value="category4" data-color="#f4ebd5">Category 4</option>
</select>
```

NOTE: Make sure your option values are unique
